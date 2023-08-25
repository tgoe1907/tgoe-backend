<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use TgoeSrv\Member\Api\MemberGroupService;
use TgoeSrv\Document\GroupMemberDataConfirmationListService;
use TgoeSrv\Member\Api\MemberService;

class MemberDataConfirmation extends BaseController
{
    private const GROUPLIST_CACHE_KEY = 'MemberDataConfirmation_getGroupListCached';
    private const GROUPLIST_CACHE_DURATION = 600;
    
    public function index()
    {
        $list = $this->getGroupListCached();
        
        $data = [
            'grouplist' => $list['grouplist'],
            'cacheage' => $list['cacheage'],
        ];
        
        return view('admin/member-data-confirmation/home', $data);
    }
    
    public function refreshCache()  {
        cache()->delete(self::GROUPLIST_CACHE_KEY);
        return redirect()->route('admin/member-data-confirmation');
    }
    
    public function downloadList($key) {
        $list = $this->getGroupListCached();
        $group = $list['grouplist'][$key];
        
        $ms = new MemberService();
        $members = $ms->findMembersOfGroup($group->getId());
        
        
        $filePath = GroupMemberDataConfirmationListService::generateDocument($group, $members);

        if( $filePath !== null ) {
            return $this->response->download($filePath, null)->setFileName('test.docx');
        }
    }
    
    private function getGroupListCached() : array {
        if( !$list = cache(self::GROUPLIST_CACHE_KEY)) {
            $srv = new MemberGroupService();
            $grouplist = $srv->getAllMemberGroups(false);
            
            $list = [
                'grouplist' => $grouplist,
                'cacheage' => time(),
            ];
                        
            cache()->save(self::GROUPLIST_CACHE_KEY, $list, self::GROUPLIST_CACHE_DURATION);
        }
        
        return $list;
    }
}
