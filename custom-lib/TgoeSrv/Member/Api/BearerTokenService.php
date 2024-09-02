<?php
declare(strict_types = 1);
namespace TgoeSrv\Member\Api;

use TgoeSrv\Tools\Logger;
use TgoeSrv\Tools\SettingsManager;
use TgoeSrv\Tools\SettingsKey;

class BearerTokenService extends EasyvereinBase
{
    /**
     * Fetch new bearer token and return it.
     * @return string
     */
    public function fetchRefreshToken() :string {
        $resultList = $this->executeRestQuery('refresh-token');
        return $resultList['Bearer'];
    }
    
    /**
     * Fetch new bearer token and save to settings.
     */
    public function updateBearerTokenInSettings() {
        $token = $this->fetchRefreshToken();
        SettingsManager::setStringValue(SettingsKey::EASYVEREIN_BEARER_TOKEN, $token);
        Logger::info(__METHOD__.' Bearer token refreshed and saved.');
    }
}

