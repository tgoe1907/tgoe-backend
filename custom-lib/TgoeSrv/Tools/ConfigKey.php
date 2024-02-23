<?php
declare(strict_types = 1);
namespace TgoeSrv\Tools;

enum ConfigKey : string
{
    //DB
    case DB_HOST = 'db|host';
    case DB_PORT = 'db|port';
    case DB_DBNAME = 'db|dbname';
    case DB_USER = 'db|user';
    case DB_PASSWORD = 'db|password';
    
    //EASYVEREIN
    case EASYVEREIN_APIKEY = 'easyverein|apikey';
    case EASYVEREIN_SERVICEURL = 'easyverein|serviceurl';
    
    //ASPOSE
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

