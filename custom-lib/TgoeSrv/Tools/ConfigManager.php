<?php
declare(strict_types = 1);
namespace TgoeSrv\Tools;

class ConfigManager
{

    private array $iniArray;

    private static ?ConfigManager $cnf = NULL;

    const CONFIG_FILE_NAME = "tgoe-config.ini";

    private function __construct()
    {
        // try to find file
        $configFile = ROOTPATH . self::CONFIG_FILE_NAME;
        if (!file_exists($configFile)) {
            throw new \Exception('Cannot find config file in application root path. Expected file name is ' . $configFile);
        }

        $this->iniArray = parse_ini_file($configFile, true);
        if ($this->iniArray === false) {
            throw new \Exception('Error while reading config file. Check for correct ini file format.');
        }
    }

    private function getValueInternal(ConfigKey $key): string
    {
        $section = $key->getSection();
        $setting = $key->getSetting();

        if (! isset($this->iniArray[$section]) || ! isset($this->iniArray[$section][$setting])) {
            throw new \Exception('Cannot find config value for section=' . $section . ' / setting=' . $setting);
        }

        return $this->iniArray[$section][$setting];
    }

    private static function getInstance(): ConfigManager
    {
        if (self::$cnf == null) {
            self::$cnf = new ConfigManager();
        }

        return self::$cnf;
    }

    public static function getValue(ConfigKey $key): string
    {
        return self::getInstance()->getValueInternal($key);
    }
}

