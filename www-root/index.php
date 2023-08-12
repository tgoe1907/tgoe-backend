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


?>