<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Validator\Impl;

use TgoeSrv\Member\Validator\SingleMemberValidator;
use TgoeSrv\Member\Member;
use TgoeSrv\Member\Enums\ValidationSeverity;

class MemberBasicValidator extends SingleMemberValidator
{

    private const DATE_1907 = - 1988154000;

    protected function getValidatorName(): string
    {
        return 'Grundlegende Prüfungen des Mitglieds';
    }

    public function testMember(Member $member): void
    {
        if( $member->getResignationDate() < time() ) {
            //do not validate resigned members
            continue;
        }
        
        $error = false;
        
        if (empty($member->getStreet())) {
            $this->addMessage(ValidationSeverity::WARNING, $member, "Straße sollte nicht leer sein.");
            $error = true;
        }
        
        if (empty($member->getCity()) || empty($member->getZip())) {
            $this->addMessage(ValidationSeverity::WARNING, $member, "Ort/PLZ sollte nicht leer sein.");
            $error = true;
        }
       
        
        if (empty($member->getJoinDate()) || $member->getJoinDate() < self::DATE_1907) {
            $this->addMessage(ValidationSeverity::WARNING, $member, "Beitrittsdatum sollte nicht leer sein.");
            $error = true;
        }

        if (empty($member->getDateOfBirth()) || $member->getDateOfBirth() < self::DATE_1907) {
            $this->addMessage(ValidationSeverity::WARNING, $member, "Geburtsdatum sollte nicht leer sein.");
            $error = true;
        }

        if (! $error && $member->getJoinDate() < $member->getDateOfBirth()) {
            $this->addMessage(ValidationSeverity::WARNING, $member, "Beitrittsdatum kann nicht vor dem Geburtsdatum liegen.");
        }
    }
}

