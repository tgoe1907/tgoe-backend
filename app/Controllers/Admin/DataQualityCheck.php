<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use TgoeSrv\Member\Api\MemberGroupService;

class DataQualityCheck extends BaseController
{
    private const CACHE_KEY = 'DataQualityCheck_CombinedCache';
    private const CACHE_DURATION = 600;
    
    public function index()
    {
        $list = $this->getGroupListCached();
        
        //TODO: DOES IT MAKE SENSE TO USE CACHED DATA?
        
        $data = [
            'grouplist' => $list['grouplist'],
            'cacheage' => $list['cacheage'],
        ];
        
        return view('admin/data-quality-check/home', $data);
    }
    
    public function refreshCache()  {
        cache()->delete(self::GROUPLIST_CACHE_KEY);
        return redirect()->route('admin/data-quality-check');
    }
    
    private function getListsCached() : array {
        if( !$list = cache(self::CACHE_KEY)) {
            $srvG = new MemberGroupService();
            $grouplist = $srvG->getAllMemberGroups(false);
            
            $srvM = new MemberService();
            $members = $srvM->getAllMembers(1);
            
            $list = [
                'grouplist' => $grouplist,
                'members' => $members,
                'cacheage' => time(),
            ];
                        
            cache()->save(self::CACHE_KEY, $list, self::CACHE_DURATION);
        }
        
        return $list;
    }
}
