<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Validator\Impl;

use TgoeSrv\Member\Validator\SingleMemberValidator;
use TgoeSrv\Member\Member;
use TgoeSrv\Member\MemberGroup;
use TgoeSrv\Member\Enums\ValidationSeverity;

class MembershipFeeValidator extends SingleMemberValidator
{

    private static array $deprecated = [
        'B-GF'
    ];

    private static array $valid = [
        'B-FA',
        'B-GB',
        'B-EM',
        'B-GS',
        'B-GF'
    ];

    private int $effectiveCancellationDeadline;

    public function __construct()
    {
        $currentyear = intval(date('Y'));
        
        // calculate cancellation date to ignore members for this validation
        if (intval(date('d')) > 6) {
            // use begin of next year
            $this->effectiveCancellationDeadline = mktime(0, 0, 0, 1, 1, $currentyear + 1);
        } else {
            // use begin of current year
            $this->effectiveCancellationDeadline = mktime(0, 0, 0, 1, 1, $currentyear);
        }
    }

    protected function getValidatorName(): string
    {
        return 'Prüfung Beitragsgruppen';
    }

    public function testMember(Member $member): void
    {
        $memberGroupKeys = array();
        foreach ($member->getMemberGroups() as $group ) {
            /* @var $group MemberGroup */
            $memberGroupKeys[] = $group->getKey();
        }
        
        // check member has no deprecated groups
        $dep = array_intersect($memberGroupKeys, self::$deprecated);
        if( count($dep) > 0) {
            $this->addMessage(ValidationSeverity::WARNING, $member, "Mitglied hat veraltete Gruppen: ".implode(', ', $dep));
        }
        
        // exit for members who already cancelled
        if( $member->getResignationDate() !== null && $member->getResignationDate() < $this->effectiveCancellationDeadline ) {
            // membership is cancelled before next charging of member fee, additional checks can be skipped
            return;
        }
        
        // check member has exactly one member fee group
        $dep = array_intersect($memberGroupKeys, self::$valid);
        if( count($dep) == 0) {
            if( $member->getResignationDate() !== null ) {
                $cancelled = date('d.m.Y', $member->getResignationDate());
                $this->addMessage(ValidationSeverity::WARNING, $member, "Mitglied hat keine Grundbeitrags-Gruppe. Gekündigt: $cancelled" );}
            else {
                $this->addMessage(ValidationSeverity::ERROR, $member, "Mitglied hat keine Grundbeitrags-Gruppe." );
            }

        }
        elseif( count($dep) > 1 ) {
            $this->addMessage(ValidationSeverity::ERROR, $member, "Mitglied hat mehrere Grundbeitrags-Gruppen.");
        }
        
    }
}

