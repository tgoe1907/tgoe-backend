<?php
namespace TgoeSrv\Member\Enums;

enum MemberDosbGender : string
{
    case MALE = 'm';
    case FEMALE = 'w';
    case DIVERSE = 'd';
    case UNKNOWN = 'u';
    
    /**
     * Find enum value matching the key. Uses ignore case string comparison.
     *
     * @param string $key Key to search for.
     * @return DosbSport Enumeration value or UNKNOWN in case key does not exist.
     */
    public static function findByKey(string $key): ?MemberDosbGender
    {
        foreach( MemberDosbGender::cases() as $e) {
            if( $e->value == $key ) return $e;
        }
        
        return self::UNKNOWN;
    }
}

