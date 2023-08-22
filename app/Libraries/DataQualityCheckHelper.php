<?php
namespace App\Libraries;

class DataQualityCheckHelper
{
    private static $filehandler = false;
    private const LOCKFILE = WRITEPATH . 'DataQualityCheckJob.lock';
    private const CACHE_KEY_RESULT = 'DataQualityCheckHelper_result';
    private const CACHE_KEY_STATUS = 'DataQualityCheckHelper_status';
    private const CACHE_DURATION = 30*24*60*60; //30 days
    
    public static function isLocked(): bool
    {
        $tmphandler = fopen(self::LOCKFILE, "w+");
        if (!$tmphandler) {
            return true;
        }
        
        $locked = flock($tmphandler, LOCK_EX | LOCK_NB);
        
        if( $locked) {
            //unlock immediately
            flock($tmphandler, LOCK_UN);
        }
        
        fclose($tmphandler);
        
        return !$locked;
    }

    public static function obtainLock(): bool
    {
        //note: needs to keep file handler in static variable,
        //because GC would release file in case it's not used
        //any more.
        
        self::$filehandler = fopen(self::LOCKFILE, "w+");
        if (! self::$filehandler) {  
            return false;
        }

        return flock(self::$filehandler, LOCK_EX | LOCK_NB);
    }
    
    public static function releaseLock(): void
    {
        if( self::$filehandler !== false) {
            flock(self::$filehandler, LOCK_UN);
            fclose(self::$filehandler);
            self::$filehandler = false;
        }
    }
    
    public static function writeResult(DataQualityCheckResult $data) {
        cache()->save(self::CACHE_KEY_RESULT, $data, self::CACHE_DURATION);
    }
    
    public static function readResult() : DataQualityCheckResult {
        return cache(self::CACHE_KEY_RESULT);
    }
    
    public static function writeStatusmessage(string $message) {
        cache()->save(self::CACHE_KEY_STATUS, $message, self::CACHE_DURATION );
    }
    
    public static function readStatusmessage() : string {
        return cache(self::CACHE_KEY_STATUS);
    }
}

