<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Api;

use TgoeSrv\Member\MemberGroup;

class MemberGroupService extends EasyvereinBase
{
    /**
     * Retrieve a list of all member groups from Easyverein API.
     *
     * @param bool Use ID as key in associative array. If FALSE, use key string as key in associative array.
     * @return array Array of MemberGroup objects.
     */
    public function getAllMemberGroups( bool $useIdAsKey = true): array
    {
        $queryParams = [
            'query' => MemberGroup::easyvereinQueryString,
            'orderString' => MemberGroup::easyvereinDefaultOrder
        ];
        
        $resultList = $this->executeRestQueryWithPaging('member-group', $queryParams);
        $resultObjList = array();
        foreach( $resultList as $item ) {
            $o = new MemberGroup($item);
            $k = $useIdAsKey ? $o->getId() : $o->getKey();
            $resultObjList[$k] = $o;
        }
        
        return $resultObjList;
    }
}

