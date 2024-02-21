<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use TgoeSrv\Member\Api\MemberGroupService;
use App\Libraries\DataQualityCheckResult;
use App\Libraries\DataQualityCheckHelper;
use App\Libraries\CIHelper;
use TgoeSrv\Member\Enums\MemberGroupCustomProperty;

class DivisionStatistics extends BaseController
{
    
    public function index()
    {
        $ci = new CIHelper();
        
        $gs = new MemberGroupService();
        $groups = $gs->getAllMemberGroups(false);
        
        $divisions = array();
        $costcenters = array();
        
        foreach( $groups as $k => $g ) {
            if( strlen($k) < 2 ) continue;
            if( !$g->isClassGroup() ) continue;
            
            //use first 2 characters of group as division key
            $divisionKey = substr($k,0,2);
            
            //get cost center from group
            $ccKey = $g->getCustomProperty(MemberGroupCustomProperty::COST_CENTER);
            
            //summarize by divisions
            if( !isset($divisions[$divisionKey])) $divisions[$divisionKey] = 0;
            $divisions[$divisionKey] += $g->getLinkedItems();
            
            //summarize by cost centers
            if( !isset($costcenters[$ccKey])) $costcenters[$ccKey] = 0;
            $costcenters[$ccKey] += $g->getLinkedItems();
        }
        
        //calculate percentages for divisions
        $totalCount = array_sum( $divisions );
        $divisionPercentages = array();
        foreach( $divisions as $k => $v ) {
            $divisionPercentages[$k] = round( $v/$totalCount*100, 1 );
        }
        
        //calculate percentages for cost centers
        $totalCount = array_sum( $costcenters );
        $costcenterPercentages = array();
        foreach( $costcenters as $k => $v ) {
            $costcenterPercentages[$k] = round( $v/$totalCount*100, 1 );
        }
        
        //sort arrays by key
        ksort($divisions);
        
        $data = [
            'divisions' => $divisions,
            'divisionPercentages' => $divisionPercentages,
            'costcenters' => $costcenters,
            'costcenterPercentages' => $costcenterPercentages,
        ];
        
        
        $ci->setHeadline("Abteilungs-Statistik");
        $ci->initMenuLoggedin();
        return $ci->view('admin/division-statistics/home', $data);
    }
    

}
