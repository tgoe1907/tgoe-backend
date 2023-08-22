<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Api;

use TgoeSrv\Member\Member;

class MemberService extends EasyvereinBase
{
    private function findMemberListInternal(array $queryParams) :array {
        $resultList = $this->executeRestQueryWithPaging('member', $queryParams);
        $resultObjList = array();
        foreach( $resultList as $item ) {
            $o = new Member($item);
            $resultObjList[$o->getId()] = $o;
        }
        
        return $resultObjList;
    }
    
    /**
     * Fetch all members of a member group.
     * 
     * @param int $groupId ID of member group.
     * @return array List of members.
     */
    public function findMembersOfGroup(int $groupId): array
    {
        $queryParams = [
            'query' => Member::easyvereinQueryString,
            'orderString' => Member::easyvereinDefaultOrder,
            'memberGroups' => $groupId,
        ];
        
        return $this->findMemberListInternal($queryParams);
    }
    
    /**
     * Fetch all members.
     *
     * @return array List of members.
     */
    public function getAllMembers(): array
    {
        $queryParams = [
            'query' => Member::easyvereinQueryString,
            'orderString' => Member::easyvereinDefaultOrder,
        ];
        
        return $this->findMemberListInternal($queryParams);
    }
    
}

