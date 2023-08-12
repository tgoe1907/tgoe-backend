<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Validator;

use TgoeSrv\Member\Member;

abstract class SingleMemberValidator extends MemberListValidator
{

    public abstract function testMember(Member $member): void;

    public function testMemberList(array $members): void
    {
        foreach ($members as $member)
            $this->testMember($member);
    }
}
?>