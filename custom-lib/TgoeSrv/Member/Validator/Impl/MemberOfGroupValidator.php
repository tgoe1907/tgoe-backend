<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Validator\Impl;

use TgoeSrv\Member\Validator\SingleMemberValidator;
use TgoeSrv\Member\Member;
use TgoeSrv\Member\Enums\ValidationSeverity;
use TgoeSrv\Member\MemberGroup;

class MemberOfGroupValidator extends SingleMemberValidator
{

    private const DATE_1907 = - 1988154000;

    protected function getValidatorName(): string
    {
        return 'Prüfung Gruppenmitgliedschaften';
    }

    public function testMember(Member $member): void
    {
        if (!empty($member->getResignationDate()) && $member->getResignationDate() < time()) {
            $groups = $member->getMemberGroups();
            $hasClassGroups = false;
            foreach( $groups as $g ) {
                /**
                 * @var MemberGroup $g
                 */
                if ( $g->isClassGroup() ) {
                    $hasClassGroups = true;
                    break;
                }
            }
            
            if( $hasClassGroups ) {
                $this->addMessage(ValidationSeverity::WARNING, $member, "Nach Ende der Mitgliedschaft Sportgruppen entfernen.");
            }
            
        }
    }
}

