<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Enums;

enum ValidationSeverity
{
    case ERROR;
    case WARNING;
    case INFO;
    
    public function getDisplayName() : string {
        switch ($this) {
            case self::ERROR: return 'Fehler';
            case self::WARNING: return 'Warnung';
            case self::INFO: return 'Hinweis';
        }
        
        return '';
    }
    
    public function getBackgroundCssClass() : string {
        switch ($this) {
            case self::ERROR: return 'bg-danger';
            case self::WARNING: return 'bg-warning';
            case self::INFO: return 'bg-info';
        }
        
        return '';
    }
}

