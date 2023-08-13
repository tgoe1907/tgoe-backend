<?php
require __DIR__. '/../vendor/autoload.php';

use TgoeSrv\Tools\Logger;
use TgoeSrv\Tools\ConfigManager;
use TgoeSrv\Tools\ConfigKey;
use TgoeSrv\Member\Api\Easyverein;
use TgoeSrv\Member\Enums\MemberGroupCustomProperty;
use TgoeSrv\Member\Api\MemberGroupService;
use TgoeSrv\Member\Api\MemberService;
use TgoeSrv\Member\Validator\Impl\MemberBasicValidator;
use TgoeSrv\Member\Validator\Impl\MemberSalutationValidator;
use TgoeSrv\Member\Validator\Impl\MembershipFeeValidator;
use TgoeSrv\Member\Validator\Impl\MembershipNumberValidator;
use TgoeSrv\Member\Validator\Impl\SportsClassGroupCustomPropertiesValidator;

/*
$e = new MemberGroupService();
$res = $e->getAllMemberGroups(false);

var_dump($res);

foreach( $res as $o ) {
    echo $o."\r\n";
    echo $o->getCustomProperty(MemberGroupCustomProperty::DOSB_SPORT)."\r\n";
    echo $o->getDosbSportCustomProperty()->value."\r\n";
    echo $o->isMemberFeeGroup()."\r\n";
    echo $o->isClassGroup()."\r\n";
    
}
*/

$g = new MemberGroupService();
$groups = $g->getAllMemberGroups();

$e = new MemberService();
$members = $e->getAllMembers();

/*
foreach( $members as $o ) {
    echo $o."\r\n";
}
*/


$v = new MemberBasicValidator();
$v->testMemberList($members);
$res = $v->getMessages();

foreach( $res as $o ) {
    echo $o."\r\n";
}


$v = new MemberSalutationValidator();
$v->testMemberList($members);
$res = $v->getMessages();

foreach( $res as $o ) {
    echo $o."\r\n";
}

$v = new MembershipFeeValidator();
$v->testMemberList($members);
$res = $v->getMessages();

foreach( $res as $o ) {
    echo $o."\r\n";
}


$v = new MembershipNumberValidator();
$v->testMemberList($members);
$res = $v->getMessages();

foreach( $res as $o ) {
    echo $o."\r\n";
}


$v = new SportsClassGroupCustomPropertiesValidator();
$v->testMemberGroupList($groups);
$res = $v->getMessages();

foreach( $res as $o ) {
    echo $o."\r\n";
}
?>