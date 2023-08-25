<?php
namespace TgoeSrv\Member\Validator\Impl;

use TgoeSrv\Member\Validator\MemberListValidator;
use TgoeSrv\Member\Member;
use TgoeSrv\Member\Enums\ValidationSeverity;

class MembershipNumberUniqueValidator extends MemberListValidator
{

    protected function getValidatorName() : string
    {
        return 'Einmalige Mitgliedsnummern';
    }

    public function testMemberList(array $members) : void
    {
        $usedNumbers = array();
        
        foreach( $members as $m ) {
            /**
             * @var Member $m
             */
            if( isset($usedNumbers[$m->getMembershipNumber()])) {
                $name = $usedNumbers[$m->getMembershipNumber()]->getFullName();
                $this->addMessage(ValidationSeverity::ERROR, $m, "Mitgliedsnummer {$m->getMembershipNumber()} wird bereits bei Mitglied '{$name}' verwendet.");
            }
            else {
                $usedNumbers[$m->getMembershipNumber()] = $m;
            }
        }
    }
}

