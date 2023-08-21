<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use TgoeSrv\Member\Api\MemberGroupService;

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
