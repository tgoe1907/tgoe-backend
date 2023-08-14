<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Validator;

abstract class MemberListValidator extends Validator
{
    public abstract function testMemberList( array $members ) : void;
}

