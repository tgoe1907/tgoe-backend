<?php
namespace TgoeSrv\Document;

use Aspose\Words\WordsApi;
use TgoeSrv\Tools\ConfigKey;
use TgoeSrv\Tools\ConfigManager;

class AsposeCloudFactory
{
    public static function getAsposeWords() : WordsApi {
        $clientid = ConfigManager::getValue(ConfigKey::ASPOSE_CLIENTID);
        $clientsecret = ConfigManager::getValue(ConfigKey::ASPOSE_CLIENTSECRET);
        return new WordsApi($clientid, $clientsecret);
    }
    
    /**
     * Returns TgoeSrv default template directory. In case file name is provided,
     * it will be appended, so full path of the file is returned. 
     * 
     * @param string $templateFileName
     * @return string
     */
    public static function getTemplateDirectory(?string $templateFileName = null) :string {
        $dir = __DIR__ . '/Templates';
        if( $templateFileName !== null ) $dir = $dir.'/'.$templateFileName;
        return $dir;
    }
}

