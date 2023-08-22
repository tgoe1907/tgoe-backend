<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Validator;

use TgoeSrv\Member\Api\MemberGroupService;
use TgoeSrv\Member\Api\MemberService;
use TgoeSrv\Member\Validator\Impl\MemberBasicValidator;
use TgoeSrv\Member\Validator\Impl\SportsClassGroupCustomPropertiesValidator;
use TgoeSrv\Member\Validator\Impl\MembershipNumberValidator;
use TgoeSrv\Member\Validator\Impl\MembershipFeeValidator;
use TgoeSrv\Member\Validator\Impl\MemberSalutationValidator;

abstract class ValidationRunner
{
    public static function runAll() {
        $msg = array();
        
        //load data from API first
        $srvG = new MemberGroupService();
        $grouplist = $srvG->getAllMemberGroups(false);
        
        $srvM = new MemberService();
        $members = $srvM->getAllMembers();
        
        //then execute all validators and collect result messages
        
        $v = new MemberBasicValidator();
        $v->testMemberList($members);
        $msg[] = $v->getMessages();
        
        $v = new MemberSalutationValidator();
        $v->testMemberList($members);
        $msg[] = $v->getMessages();
        
       
        $v = new MembershipFeeValidator();
        $v->testMemberList($members);
        $msg[] = $v->getMessages();
        
        $v = new MembershipNumberValidator();
        $v->testMemberList($members);
        $msg[] = $v->getMessages();
        
        $v = new SportsClassGroupCustomPropertiesValidator();
        $v->testMemberGroupList($grouplist);
        $msg[] = $v->getMessages();
        
        //flatten result messages array
        $result = array();
        foreach( $msg as $m ) {
            $result = array_merge($result, $m);
        }
        
        return $result;
    }

}
