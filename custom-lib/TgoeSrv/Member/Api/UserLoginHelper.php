<?php
namespace TgoeSrv\Member\Api;

use TgoeSrv\Member\MemberUserInformation;
use TgoeSrv\Tools\ConfigManager;
use TgoeSrv\Tools\ConfigKey;
use TgoeSrv\Tools\Logger;

class UserLoginHelper extends EasyvereinBase
{

    /**
     * Tr to get user info by provided credentials. Return null in case login not matching.
     * 
     * @param string $username
     * @param string $password
     * @return MemberUserInformation|NULL
     */
    public function verifyUserLogin(string $username, string $password): ?MemberUserInformation
    {
        $queryParams = [
            'query' => MemberUserInformation::easyvereinQueryString,
            'custom_field_name' => ConfigManager::getValue(ConfigKey::EASYVEREIN_CUSTOMFIELD_LOGINNAME),
            'custom_field_value' => $username,
            'limit' => 1
        ];

        $res = $this->executeRestQuery('member', $queryParams);

        // make sure only one record matches the user name
        if (empty($res['count'])) {
            $cnt = intval($res['count']);

            if ($cnt == 0) {
                Logger::info("Login failed for user '{$username}'. Username did not match any record.");
                return null;
            }

            if ($cnt > 1) {
                Logger::info("Login failed for user '{$username}'. Username matched multiple records.");
                return null;
            }
        }

        // parse result and create user information object
        $userInfo = new MemberUserInformation($res['results'][0]);

        // double-check user name (to make sure query by API does not do any relaxed matching)
        if (strtolower($username) != strtolower($userInfo->getLoginUsername())) {
            Logger::info("Double-check of user name for login '{$username}' failed.");
            return null;
        }

        // check password
        if (! password_verify($password, $userInfo->getLoginPasswordHash())) {
            Logger::info("Password check for login '{$username}' failed.");
            return null;
        }
        
        //clear sensitive information from user info object for more safety
        $userInfo->clearLoginCustomFields();

        return $userInfo;
    }
}

