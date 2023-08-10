<?php
declare(strict_types = 1);
namespace TgoeSrv\Tools;

//TODO: performance for getting the values? better solution?
enum ConfigKey : string
{
    case EASYVEREIN_APIKEY = 'easyverein|apikey';
    case EASYVEREIN_SERVICEURL = 'easyverein|serviceurl';
    
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
?>
