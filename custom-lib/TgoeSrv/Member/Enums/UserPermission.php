<?php
namespace TgoeSrv\Member\Enums;

use TgoeSrv\Tools\ConfigKey;

enum UserPermission: string
{

    case DATAQUALITYCHECK = 'DATAQUALITYCHECK';

    case DIVISIONSTATISTICS = 'DIVISIONSTATISTICS';

    case MEMBERDATACONFIRMATION = 'MEMBERDATACONFIRMATION';

    public static function getConfigKey( UserPermission $p ) : ?ConfigKey {
        switch( $p ) {
            case self::DATAQUALITYCHECK: return ConfigKey::EASYVEREIN_PERMISSION_DATAQUALITYCHECK;
            case self::DIVISIONSTATISTICS: return ConfigKey::EASYVEREIN_PERMISSION_DIVISIONSTATISTICS;
            case self::MEMBERDATACONFIRMATION: return ConfigKey::EASYVEREIN_PERMISSION_MEMBERDATACONFIRMATION;
        }
        
        return null;
    }
}

