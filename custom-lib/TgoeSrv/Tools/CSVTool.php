<?php
namespace TgoeSrv\Tools;

class CSVTool
{
    public const UTF8_BOM = "\xEF\xBB\xBF";
    
    public static function hasmap2csv( array $hashmap ) : String {
        //build csv data
        $filedata = self::UTF8_BOM;
        for( $i = 0; $i<count($hashmap); $i++) {
            $data = $hashmap[$i];
            
            if( $i == 0) {
                $keys = array_keys($data);
                $filedata .= implode(';', $keys)."\r\n";
            }
            
            //quote the values
            $values = array();
            foreach( $keys as $k ) {
                $values[$k] = '"'.str_replace('"', '""', $data[$k]).'"';
            }
            
            $filedata .= implode(';', $values)."\r\n";
        }
        
        return $filedata;
    }
}

