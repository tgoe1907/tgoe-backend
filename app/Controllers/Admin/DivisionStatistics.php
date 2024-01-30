<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use TgoeSrv\Member\Api\MemberGroupService;
use App\Libraries\DataQualityCheckResult;
use App\Libraries\DataQualityCheckHelper;
use App\Libraries\CIHelper;

class DivisionStatistics extends BaseController
{
    
    public function index()
    {
        $ci = new CIHelper();
        
        $gs = new MemberGroupService();
        $groups = $gs->getAllMemberGroups(false);
        
        $divisions = array();
        
        foreach( $groups as $k => $g ) {
            if( strlen($k) < 2 ) continue;
            if( !$g->isClassGroup() ) continue;
            
            //use first 2 characters of group as division key
            $divisionKey = substr($k,0,2);
            
            //summarize linked items
            if( !isset($divisions[$divisionKey])) $divisions[$divisionKey] = 0;
            $divisions[$divisionKey] += $g->getLinkedItems();
        }
        
        //calculate percentages
        $totalCount = array_sum( $divisions );
        $percentages = array();
        foreach( $divisions as $k => $v ) {
            $percentages[$k] = round( $v/$totalCount*100, 1 );
        }
        
        //sort array by key
        ksort($divisions);
        
        $data = [
            'divisions' => $divisions,
            'percentages' => $percentages 
        ];
        
        
        $ci->setHeadline("Abteilungs-Statistik");
        $ci->initMenuLoggedin();
        return $ci->view('admin/division-statistics/home', $data);
    }
    

}
