<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Validator\Impl;

use TgoeSrv\Member\Validator\SingleMemberValidator;
use TgoeSrv\Member\Member;
use TgoeSrv\Member\Enums\MemberDosbGender;
use TgoeSrv\Member\Enums\ValidationSeverity;
use TgoeSrv\Member\Enums\DosbSport;

class MemberSalutationValidator extends SingleMemberValidator
{

    private $validSalutations = [
        'Frau' => MemberDosbGender::FEMALE,
        'Herr' => MemberDosbGender::MALE
    ];

    protected function getValidatorName(): string
    {
        return 'Korrekte Anrede und DOSB Geschlecht';
    }

    public function testMember(Member $member): void
    {
        $hasError = false;

        // check salutation
        if (empty($member->getSalutation())) {
            $hasError = true;
            $this->addMessage(ValidationSeverity::ERROR, $member, "Anrede darf nicht leer sein.");
        } else if (! isset($this->validSalutations[$member->getSalutation()])) {
            $hasError = true;
            $this->addMessage(ValidationSeverity::ERROR, $member, "Anrese muss einer der Werte sein: " . implode(', ', array_keys($this->validSalutations)));
        }

        // check DOSB gender
        if ($member->getDosbGender() == MemberDosbGender::UNKNOWN) {
            $hasError = true;
            $this->addMessage(ValidationSeverity::ERROR, $member, "DOSB Geschlecht ist leer oder hat einen ungültigen Wert.");
        }

        // in case no privous error, check consistency of salutation and gender
        if (! $hasError && $this->validSalutations[$member->getSalutation()] != $member->getDosbGender()) {
            $this->addMessage(ValidationSeverity::ERROR, $member, "DOSB Geschlecht passt nicht zur Anrede.");
        }
    }
}

