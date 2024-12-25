<?php
namespace TgoeSrv\Member\Validator\Impl;

use TgoeSrv\Member\Validator\MemberListValidator;
use TgoeSrv\Member\Member;
use TgoeSrv\Member\MemberGroup;
use TgoeSrv\Member\Enums\ValidationSeverity;

class FamilyMembershipValidator extends MemberListValidator
{

    private string $payingGroupKey = "B-GB";

    private string $freeGroupKey = "B-FA";

    protected function getValidatorName(): string
    {
        return 'Familienverknüpfungen';
    }

    public function testMemberList(array $members): void
    {
        $familyList = array();

        foreach ($members as $m) {
            /**
             *
             * @var Member $m
             */

            // Generate error in case of complex membership relations. One member should never be child and parent of a relation at the same time.
            if ($m->getParentMemberNumber() !== null && count($m->getChildMemberNumbers()) > 0) {
                $this->addMessage(ValidationSeverity::ERROR, $m, "Innerhalb einer Familie müssen alle Mitglieder zu einer einzigen Person verknüpft sein.");
            }

            $addToFamily = null;

            // Sort members into families according to their parent member's membership number.
            if ($m->getParentMemberNumber() !== null)
                $addToFamily = $m->getParentMemberNumber();

            // And also include the parent members to this list. (if a member has childs, then we know it's a parent)
            if (count($m->getChildMemberNumbers()) > 0)
                $addToFamily = $m->getMembershipNumber();

            if ($addToFamily !== null) {
                // init array for the family in case it's not existing, yet.
                if (! array_key_exists($addToFamily, $familyList))
                    $familyList[$addToFamily] = array();

                // add member to the family
                $familyList[$addToFamily][$m->getMembershipNumber()] = $m;
            }
        }

        foreach ($familyList as $parentMembershipNumber => $familyMembers) {
            /**
             *
             * @var Member $parent
             */
            $parent = $familyMembers[$parentMembershipNumber];

            $countPayingOver18 = 0;
            $countPayingUnder18 = 0;
            $countFree = 0;
            $hasMemberOver18 = false;

            foreach ($familyMembers as $m) {
                /**
                 *
                 * @var Member $m
                 */
                if ($m->isUnder18() && // unter 18 years old
                ! ($m->getResignationDate() !== null && $m->getResignationDate() < time()) && // membership not cancelled
                $parent->getIbanUnified() != $m->getIbanUnified()) // different iban
                {
                    $this->addMessage(ValidationSeverity::WARNING, $m, "Familienmitglieder unter 18 Jahren müssen dieselbe IBAN wie das Hauptmitglied der Familie für den Beitragseinzug verwenden.  {$parent->getIban()}, {$m->getIban()}");
                }

                $hasMemberOver18 |= ! $m->isUnder18();

                // count different membership fee cases
                foreach ($m->getMemberGroups() as $group) {
                    /* @var $group MemberGroup */
                    $k = $group->getKey();

                    // do not count for resigned members
                    if ($m->getResignationDate() !== null && $m->getResignationDate() < time()) {
                        continue;
                    }

                    if ($k == $this->freeGroupKey) {
                        $countFree ++;
                        break;
                    }

                    if ($k == $this->payingGroupKey) {
                        if ($m->isUnder18()) {
                            $countPayingUnder18 ++;
                        } else {
                            $countPayingOver18 ++;
                        }

                        break;
                    }
                }
            }

            // Note: Members over 18 years using free family membership are already reported by MembershipFeeValidator.

            if ($countFree > 0 && $countPayingOver18 + $countPayingUnder18 < 2) {
                $this->addMessage(ValidationSeverity::ERROR, $parent, "Für Familienmitgliedschaft müssen mindestens zwei Mitglieder Grundbeitrag bezahlen.");
            }

            if ($countPayingUnder18 > 0 && $countPayingOver18 >= 2) {
                $this->addMessage(ValidationSeverity::WARNING, $parent, "Prüfen, ob in der Familie evtl. zu viele Mitglieder Grundbeitrag bezahlen.");
            }

            if ($hasMemberOver18 && $parent->isUnder18()) {
                $this->addMessage(ValidationSeverity::WARNING, $parent, "Wenn die Eltern auch Mitglied sind, sollte die Familie auf ein Elternteil verknüpft werden.");
            }
        }
    }
}

