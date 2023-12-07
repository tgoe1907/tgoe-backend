<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use TgoeSrv\Member\Api\MemberGroupService;
use TgoeSrv\Document\GroupMemberDataConfirmationListService;
use TgoeSrv\Member\Api\MemberService;
use TgoeSrv\Member\MemberGroup;
use App\Libraries\CIHelper;

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
        
        $ci = new CIHelper();
        $ci->setHeadline("Erhebung Gruppenmitglieder");
        $ci->initMenuLoggedin();
        return $ci->view('admin/member-data-confirmation/home', $data);
    }
    
    public function refreshCache()  {
        cache()->delete(self::GROUPLIST_CACHE_KEY);
        return redirect()->route('admin/member-data-confirmation');
    }
    
    public function downloadList($key, $format) {
        $list = $this->getGroupListCached();
        
        /**
         * 
         * @var MemberGroup $group
         */
        $group = $list['grouplist'][$key];
        
        $ms = new MemberService();
        $members = $ms->findMembersOfGroup($group->getId());
        
        $createPdf = strtolower($format) == 'pdf';
        $filename = 'Gruppenliste_'.$group->getKey().'_'.date('d-m-Y').( $createPdf ? '.pdf' : '.docx' );
        
        $filePath = GroupMemberDataConfirmationListService::generateDocument($group, $members, $createPdf);

        if( $filePath !== null ) {
            return $this->response->download($filePath, null)->setFileName($filename);
        }
    }
    
    private function getGroupListCached() : array {
        if( !$list = cache(self::GROUPLIST_CACHE_KEY)) {
            $srv = new MemberGroupService();
            $grouplist = $srv->getAllMemberGroups(false);
            
            //remove membership groups
            foreach( $grouplist as $k=>$g ) {
                if( $g->isMemberFeeGroup() ) unset( $grouplist[$k] );
            }
            
            $list = [
                'grouplist' => $grouplist,
                'cacheage' => time(),
            ];
                        
            cache()->save(self::GROUPLIST_CACHE_KEY, $list, self::GROUPLIST_CACHE_DURATION);
        }
        
        return $list;
    }
}
