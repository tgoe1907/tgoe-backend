<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Enums;

// TODO: performance for getting the values? better solution?
enum DosbSport: string
{

    case UNKNOWN = 'XX|unbekannt';

    case JUDO = 'B16|Judo';

    case GYMNASTICS = 'B36|Turnen';

    case ATHLETICS = 'B18|Leichtathletik';

    case CYCLING = 'B20|Radsport';

    case LAWN_SPORTS = 'C21|Rasenkraftsport';

    case SKI = 'B29|Ski';

    case VOLLEYBALL = 'B37|Volleyball';

    case DISABLED_SPORTS = 'C04|Behindertensport';

    public function getKey(): string
    {
        $a = explode('|', $this->value);
        return $a[0];
    }

    public function getName(): string
    {
        $a = explode('|', $this->value);
        return $a[1];
    }

    /**
     * Find enum value matching the key. Uses ignore case string comparison.
     * 
     * @param string $key Key to search for.
     * @return DosbSport Enumeration value or NULL in case key does not exist.
     */
    public static function findByKey(string $key): ?DosbSport
    {
        foreach( DosbSport::cases() as $e) {
            if( $e->getKey() == $key ) return $e;
        }
        
        return null;
    }
}

