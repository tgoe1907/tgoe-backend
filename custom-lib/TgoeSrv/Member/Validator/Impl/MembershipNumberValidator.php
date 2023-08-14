<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Validator\Impl;

use TgoeSrv\Member\Validator\SingleMemberValidator;
use TgoeSrv\Member\Member;
use TgoeSrv\Member\Enums\ValidationSeverity;

class MembershipNumberValidator extends SingleMemberValidator
{

    private const VALIDATION_REGEX = '/^([0-9]{3,6})$/';

    protected function getValidatorName(): string
    {
        return 'Prüfung Mitgliedsnummer';
    }

    public function testMember(Member $member): void
    {
        if (empty($member->getMembershipNumber())) {
            $this->addMessage(ValidationSeverity::ERROR, $member, 'Mitgliedsnummer darf nicht leer sein.');
        } else if (! preg_match(self::VALIDATION_REGEX, $member->getMembershipNumber())) {
            $this->addMessage(ValidationSeverity::ERROR, $member, 'Mitgliedsnummer muss aus 3 bis 6 Ziffern bestehen. (Ggf. auf Leerzeichen am Anfang/Ende achten.)');
        }
    }
}

