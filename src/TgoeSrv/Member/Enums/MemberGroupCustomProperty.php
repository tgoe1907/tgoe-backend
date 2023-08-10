<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Enums;

enum MemberGroupCustomProperty : string
{
    case TRAINER = 'Trainer';
    case WEEKDAY = 'Tag';
    case TIME = 'Zeit';
    case LOCATION = 'Ort';
    case DOSB_SPORT = 'DOSB-Sportart';
    
    /**
     * Find enum value matching the key. Uses ignore case string comparison.
     *
     * @param string $key Key to search for.
     * @return DosbSport Enumeration value or NULL in case key does not exist.
     */
    public static function findByKey(string $key): ?MemberGroupCustomProperty
    {
        foreach( MemberGroupCustomProperty::cases() as $e) {
            if( $e->value == $key ) return $e;
        }
        
        return null;
    }
}

