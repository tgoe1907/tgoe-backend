<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Validator\Impl;

use TgoeSrv\Member\Validator\MemberGroupValidator;
use TgoeSrv\Member\MemberGroup;
use TgoeSrv\Member\Enums\MemberGroupCustomProperty;
use TgoeSrv\Member\Enums\ValidationSeverity;
use TgoeSrv\Member\Enums\DosbSport;

class SportsClassGroupCustomPropertiesValidator extends MemberGroupValidator
{
    private static $mandatoryProperties = [
        MemberGroupCustomProperty::TRAINER => ValidationSeverity::WARNING,
        MemberGroupCustomProperty::LOCATION => ValidationSeverity::WARNING,
        MemberGroupCustomProperty::TIME => ValidationSeverity::WARNING,
        MemberGroupCustomProperty::WEEKDAY => ValidationSeverity::WARNING,
        MemberGroupCustomProperty::DOSB_SPORT => ValidationSeverity::ERROR,
    ];

    protected function getValidatorName(): string
    {
        return 'Freitext-Eigenschaften der Gruppe prüfen';
    }

    public function testMemberGroup(MemberGroup $memberGroup): void
    {
        // this test only applies to sport course groups
        if (! $memberGroup->isClassGroup()) {
            return;
        }

        // check mandatory properties are existing and not empty
        foreach (self::$mandatoryProperties as $p => $sev) {
            /* @var $p MemberGroupCustomProperty */
            $v = $memberGroup->getCustomProperty($p);
            if ($v === null) {
                $this->addMessage($sev, $memberGroup, 'Freitext-Eigenschaft fehlt: ' . $p->value);
            } elseif (empty($v)) {
                $this->addMessage($sev, $memberGroup, 'Freitext-Eigenschaft hat keinen Wert: ' . $p->value);
            }
        }

        // check for valid value of DOSB sport property
        $v = $memberGroup->getCustomProperty(MemberGroupCustomProperty::DOSB_SPORT);
        if( $v !== null && $v == DosbSport::UNKNOWN ) {
            $this->addMessage(ValidationSeverity::ERROR, $memberGroup, "Der angegebene Wert von {$p->value} kann keiner bekannten Sportart zugeordnet werden.");
        }
        
    }
}

