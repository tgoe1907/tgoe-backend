<?php
declare(strict_types = 1);
namespace TgoeSrv\Tools;

use TgoeSrv\Database\DAO\SettingsDAO;
use TgoeSrv\Database\Entities\Settings;
use TgoeSrv\Database\DbHelper;

class SettingsManager
{
    private array $settings;

    private static ?SettingsManager $instance = NULL;

    private function __construct()
    {
        $arr = SettingsDAO::findAllSettings();
        
        $this->settings = array();
        foreach( $arr as $s ) {
            $this->settings[$s->getKey()] = $s;
        }

    }

    private function getStringValueInternal(SettingsKey $key): string
    {
        if (! isset($this->settings[$key->value])) {
            throw new \Exception('Cannot find settings value for key=' . $key->value );
        }
        
        return $this->settings[$key->value]->getStringValue();
    }
    
    private function setStringValueInternal(SettingsKey $key, string $stringValue )
    {
        if (! isset($this->settings[$key->value])) {
            throw new \Exception('Cannot find settings value for key=' . $key->value );
        }
        
        /**
         * 
         * @var Settings $obj
         */
        $obj = $this->settings[$key->value];
        $obj->setStringValue($stringValue);
        DbHelper::getEntityManager()->flush();
    }
    
    
    private static function getInstance(): SettingsManager
    {
        if (self::$instance == null) {
            self::$instance = new SettingsManager();
        }

        return self::$instance;
    }

    public static function getStringValue(SettingsKey $key): string
    {
        return self::getInstance()->getStringValueInternal($key);
    }
    
    
    public static function setStringValue(SettingsKey $key, string $stringValue )
    {
        setStringValueInternal($key, $stringValue);
    }
}

