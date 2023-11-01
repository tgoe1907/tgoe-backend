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
        if( $result === null )
        {
            $updateTimestamp = -1;
            $validationMessages = array();
            $statusMessage = 'Es liegt kein Prüfergebnis vor. Bitte Ausführung des Prüfprogramms abwarten.';
        }
        else 
        {
            $updateTimestamp = $result->updateTimestamp;
            $statusMessage = DataQualityCheckHelper::readStatusmessage();
            
            $validationMessages = array();
            foreach( $result->validationMessages as $vm ) {
                $validationMessages[$vm->__toString()] = $vm;
            }
            
            ksort($validationMessages);
        }
        
        $data = [
            'updateTimestamp' => $updateTimestamp,
            'validationMessages' => $validationMessages,
            'statusMessage' => $statusMessage,
        ];
        
        return view('admin/data-quality-check/home', $data);
    }
    

}
