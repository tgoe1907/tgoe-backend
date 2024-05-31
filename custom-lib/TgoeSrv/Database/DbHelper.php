<?php
namespace TgoeSrv\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use TgoeSrv\Tools\ConfigManager;
use TgoeSrv\Tools\ConfigKey;


class DbHelper {
    private static ?EntityManager $doctrineEm = null;
    
    public static function getEntityManager() {
        if( empty(self::$doctrineEm) ) {
            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: array(__DIR__."/Entities"),
                isDevMode: true,
                );
            
            $connectionParams = [
                'driver'   => 'pdo_mysql',
                'host'     => ConfigManager::getValue(ConfigKey::DB_HOST),
                'port'     => ConfigManager::getValue(ConfigKey::DB_PORT),
                'user'     => ConfigManager::getValue(ConfigKey::DB_USER),
                'password' => ConfigManager::getValue(ConfigKey::DB_PASSWORD),
                'dbname'   => ConfigManager::getValue(ConfigKey::DB_DBNAME),
            ];
            
            $connection = DriverManager::getConnection($connectionParams, $config);
            self::$doctrineEm = new EntityManager($connection, $config);
        }
        
        return self::$doctrineEm;
    }
}

?>