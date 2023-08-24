<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use TgoeSrv\Member\Api\MemberGroupService;
use App\Libraries\DataQualityCheckResult;
use App\Libraries\DataQualityCheckHelper;

class DataQualityCheck extends BaseController
{
    private const CACHE_KEY = 'DataQualityCheck_CombinedCache';
    private const CACHE_DURATION = 600;
    
    public function index()
    {
        /**
         * 
         * @var DataQualityCheckResult $result
         */
        $result = DataQualityCheckHelper::readResult();
        $updateTimestamp = $result->updateTimestamp;
        
        $validationMessages = array();
        foreach( $result->validationMessages as $vm ) {
            $validationMessages[$vm->__toString()] = $vm;
        }
        
        ksort($validationMessages);
        
        $data = [
            'updateTimestamp' => $updateTimestamp,
            'validationMessages' => $validationMessages,
            'statusmessage' => DataQualityCheckHelper::readStatusmessage(),
        ];
        
        return view('admin/data-quality-check/home', $data);
    }
    

}
