<?php
declare(strict_types = 1);
namespace TgoeSrv\Tools;

//TODO: performance for getting the values? better solution?
enum ConfigKey : string
{
    case EASYVEREIN_APIKEY = 'easyverein|apikey';
    case EASYVEREIN_SERVICEURL = 'easyverein|serviceurl';
    case EASYVEREIN_CUSTOMFIELD_LOGINNAME = 'easyverein|customfield_loginname';
    case EASYVEREIN_CUSTOMFIELD_PASSWORDHASH = 'easyverein|customfield_passwordhash';
    case EASYVEREIN_CUSTOMFIELD_PERMISSIONS = 'easyverein|customfield_permissions';
    case EASYVEREIN_PERMISSION_DATAQUALITYCHECK = 'easyverein|permission_dataqualitycheck';
    case EASYVEREIN_PERMISSION_DIVISIONSTATISTICS = 'easyverein|permission_divisionstatistics';
    case EASYVEREIN_PERMISSION_MEMBERDATACONFIRMATION = 'easyverein|permission_memberdataconfirmation';
    case ASPOSE_CLIENTID = 'aspose|clientid';
    case ASPOSE_CLIENTSECRET = 'aspose|clientsecret';
    
    public function getSection() : string 
    {
        $a = explode('|', $this->value);
        return $a[0];
    }
    
    public function getSetting() : string
    {
        $a = explode('|', $this->value);
        return $a[1];
    }
}

