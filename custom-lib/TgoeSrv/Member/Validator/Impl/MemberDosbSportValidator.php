<?php
namespace TgoeSrv\Member\Validator\Impl;

use TgoeSrv\Member\Validator\SingleMemberValidator;
use TgoeSrv\Member\Member;
use TgoeSrv\Member\Enums\MemberGroupCustomProperty;
use TgoeSrv\Member\Enums\ValidationSeverity;

class MemberDosbSportValidator extends SingleMemberValidator
{

    private int $nextReportingDate;

    public function __construct()
    {
        // find next end of january
        $y = date("Y");
        if (date('m') > 1)
            $y ++;
        $this->nextReportingDate = mktime(23, 59, 59, 1, 1, $y); //assumption that we do reporting some day in January
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
            $this->addMessage(ValidationSeverity::ERROR, $member, "Es ist keine DOSB Sportart zugeordnet. Bei passiven Mitgliedern die zuletzt oder hauptsächlich ausgeübte Sportart verwenden.");
        }


        // Build list containing all DosbSport values of assigned membership groups.
        // Use hashmap to make sure we don't duplicate DOSB sports in the list.
        $groups = $member->getMemberGroups();

        $sportsOfGroups = array();
        if (count($groups) > 0) {
            foreach ($groups as $g) {
                $s = $g->getCustomProperty(MemberGroupCustomProperty::DOSB_SPORT);
                if( $s !== null && strlen($s) > 0 && $s != "neutral" ) $sportsOfGroups[] = $s;
            }
        }

        // if we collected some sports from assigned groups, make sure member has all sports assigned
        $recommendAdd = array_diff($sportsOfGroups, $sportsOfMember);

        // If there is more than one sport assigned to the member, make sure it matches with the groups.
        $recommendRemove = array();
        if (count($sportsOfMember) > 1 || count($recommendAdd) > 0) {
            $recommendRemove = array_diff($sportsOfMember, $sportsOfGroups);
        }
        
        //create message to add new sports
        if( count($recommendAdd) > 0 ) {
            $this->addMessage(ValidationSeverity::ERROR, $member, 'DOSB Sportart fehlt: ' . implode(', ', $recommendAdd));
        }
        
        //create message to remove existing sports
        if( count($recommendRemove) > 0 ) {
            $this->addMessage(ValidationSeverity::ERROR, $member, 'DOSB Sportart ggf. entfernen (eine behalten): ' . implode(', ', $recommendRemove));
        }
    }
}

