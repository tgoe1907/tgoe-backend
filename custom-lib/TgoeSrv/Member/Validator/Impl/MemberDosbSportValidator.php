<?php
namespace TgoeSrv\Member\Validator\Impl;

use TgoeSrv\Member\Validator\SingleMemberValidator;
use TgoeSrv\Member\Member;
use TgoeSrv\Member\Enums\ValidationSeverity;
use TgoeSrv\Member\MemberGroup;
use TgoeSrv\Member\Enums\DosbSport;

class MemberDosbSportValidator extends SingleMemberValidator
{

    private int $nextReportingDate;

    public function __construct()
    {
        // find next end of january
        $y = date("Y");
        if (date('m') > 1)
            $y ++;
        $this->nextReportingDate = mktime(23, 59, 59, 1, 31, $y);
    }

    protected function getValidatorName(): string
    {
        return 'DOSB Sportart-Zuordnung';
    }

    public function testMember(Member $member): void
    {
        // in case member cancelled membership before next reporting to DOSB,
        // we can skip validation
        if ($member->getResignationDate() !== null && $member->getResignationDate() < $this->nextReportingDate) {
            return;
        }

        // check number of assigned sports
        $sportsOfMember = $member->getDosbSport();
        if (count($sportsOfMember) == 0) {
            $this->addMessage(ValidationSeverity::WARNING, $member, "Es ist keine DOSB Sportart zugeordnet.");
        }

        // Build list containing all DosbSport values of assigned membership groups.
        // Use hashmap to make sure we don't duplicate DOSB sports in the list.
        $groups = $member->getMemberGroups();
        $sportsOfGroups = array();
        if (count($groups) > 0) {
            foreach ($groups as $g) {
                /**
                 *
                 * @var $g MemberGroup
                 * @var $s ?DosbSport
                 */
                $s = $g->getDosbSportCustomProperty();
                if ($s !== null && $s != DosbSport::UNKNOWN) {
                    $sportsOfGroups[$s->getKey()] = $s;
                }
            }
        }

        // if we collected some sports from assigned groups, make sure member has all sports assigned
        if (count($sportsOfGroups) > 0) {
            foreach ($sportsOfGroups as $sportOfGroup) {
                if (! in_array($sportOfGroup, $sportsOfMember)) {
                    $this->addMessage(ValidationSeverity::WARNING, $member, 'DOSB Sportart fehlt: ' . $sportOfGroup->getName());
                }
            }
        }

        // If there is more than one sport assigned to the member, make sure it matches with the groups.
        if (count($sportsOfMember) > 1) {
            foreach( $sportsOfMember as $sportOfMember ) {
                if (! in_array($sportOfMember, $sportsOfGroups)) {
                    $this->addMessage(ValidationSeverity::WARNING, $member, 'DOSB Sportart ggf. entfernen (eine behalten): ' . $sportOfMember->getName());
                }
            }
        }
    }
}

