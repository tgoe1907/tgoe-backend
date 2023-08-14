<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Validator;

use TgoeSrv\Member\MemberGroup;

abstract class MemberGroupValidator extends Validator
{

    public abstract function testMemberGroup(MemberGroup $memberGroup): void;

    public function testMemberGroupList(array $memberGroups): void
    {
        foreach ($memberGroups as $memberGroup)
            $this->testMemberGroup($memberGroup);
    }
}
