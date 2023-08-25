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
        //find next end of january
        $y = date("Y");
        if( date('m') > 1 ) $y++;
        $this->nextReportingDate = mktime(23,59,59,1,31,$y);
    }
    

    protected function getValidatorName(): string
    {
        return 'DOSB Sportart-Zuordnung';
    }

    public function testMember(Member $member) : void
    {
        //in case member cancelled membership before next reporting to DOSB,
        //we can skip validation
        if( $member->getResignationDate() !== null && $member->getResignationDate() < $this->nextReportingDate ) {
            return;
        }
        
        // check number of assigned sports
        $sports = $member->getDosbSport();
        
        if( count($sports) == 0 ) {
            $this->addMessage(ValidationSeverity::WARNING, $member, "Es ist keine DOSB Sportart zugeordnet.");
        }
        else if (count($sports)>1) {
            $this->addMessage(ValidationSeverity::WARNING, $member, "Es sind mehrere DOSB Sportarten zugeordent.");
        }
            
        // check match between group memberships and assigned sports
        $groups = $member->getMemberGroups();
        if( count($groups) > 0 ) {
            //build list containing all DosbSport values of 
            //assigned membership groups
            $sportsOfGroups = array();
            foreach( $groups as $g ) {
                /**
                 *  @var $g MemberGroup
                 *  @var $s ?DosbSport
                 */
                $s = $g->getDosbSportCustomProperty();
                if( $s !== null && $s != DosbSport::UNKNOWN ) {
                    $sportsOfGroups[] = $s;
                }
            }
            
            //if we collected some sports, check agains assigned DOSB sport
            if( count($sportsOfGroups) > 0 ) {
                $match = false;
                foreach( $sportsOfGroups as $sog ) {
                    $match |= in_array($sog, $sports);
                }
                
                if( !$match ) {
                    $this->addMessage(ValidationSeverity::WARNING, $member, 'Zugeordnete DOSB Sportart passt nicht zu den aktiven Sportgruppen.');
                }
            }
        }
        
    }
}

