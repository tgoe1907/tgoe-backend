<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use TgoeSrv\Member\Api\MemberGroupService;
use App\Libraries\DataQualityCheckResult;
use App\Libraries\DataQualityCheckHelper;
use App\Libraries\CIHelper;

class DataQualityCheck extends BaseController
{
    private const CACHE_KEY = 'DataQualityCheck_CombinedCache';
    private const CACHE_DURATION = 600;
    
    public function index()
    {
        $ci = new CIHelper();
        
        /**
         * 
         * @var DataQualityCheckResult $result
         */
        $result = DataQualityCheckHelper::readResult();
        if( $result === null )
        {
            $updateTimestamp = -1;
            $validationMessages = array();
            $ci->addMessage('Status', 'Es liegt kein Prüfergebnis vor. Bitte Ausführung des Prüfprogramms abwarten.', CIHelper::MSG_WARNING);
        }
        else 
        {
            $updateTimestamp = $result->updateTimestamp;
            $statusMessage = DataQualityCheckHelper::readStatusmessage();
            $ci->addMessage('Status', $statusMessage, CIHelper::MSG_INFO);
            
            $validationMessages = array();
            foreach( $result->validationMessages as $vm ) {
                $validationMessages[$vm->__toString()] = $vm;
            }
            
            ksort($validationMessages);
        }
        
        $data = [
            'updateTimestamp' => $updateTimestamp,
            'validationMessages' => $validationMessages
        ];
        
        
        $ci->setHeadline("Qualitätsprüfung Mitgliederdaten");
        $ci->initMenuLoggedin();
        return $ci->view('admin/data-quality-check/home', $data);
    }
    

}
