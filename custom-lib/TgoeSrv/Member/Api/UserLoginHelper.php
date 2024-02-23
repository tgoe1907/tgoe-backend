<?php
namespace TgoeSrv\Member\Api;

use TgoeSrv\Database\DbHelper;
use TgoeSrv\Database\Entities\UserAccount;
use TgoeSrv\Member\MemberUserInformation;
use TgoeSrv\Tools\Logger;

class UserLoginHelper extends EasyvereinBase
{

    /**
     * Tr to get user info by provided credentials. Return null in case login not matching.
     * 
     * @param string $email
     * @param string $password
     * @return MemberUserInformation|NULL
     */
    public function verifyUserLogin(string $email, string $password): ?UserAccount
    {
        $em = DbHelper::getEntityManager();
        
        $qb = $em->createQueryBuilder()
            ->select('ua')
            ->from('TgoeSrv\Database\Entities\UserAccount', 'ua')
            ->where('ua.email = :email')
            ->setParameter('email', $email);
        
        $users = $qb->getQuery()->getResult();

        // make sure only one record matches the user name
        $cnt = intval($users);

        if ($cnt == 0) {
            Logger::info("Login failed for user '{$email}'. Username did not match any record.");
            return null;
        }

        if ($cnt > 1) {
            Logger::info("Login failed for user '{$email}'. Username matched multiple records.");
            return null;
        }

        /**
         * 
         * @var UserAccount $userAccount
         */
        $userAccount = $users[0];

        // check password
        if (!password_verify($password, $userAccount->getPasswordHash())) {
            Logger::info("Password check for login '{$email}' failed.");
            
            //increment failed login counter
            Logger::info("Increment failed login count for '{$email}'.");
            $userAccount->incrementFailedLoginCount();
            $em->persist($userAccount);
            $em->flush();
            
            return null;
        }
        
        //after successfull login reset failed login counter and set last login timestamp
        $userAccount->setFailedLoginCount(0);
        $userAccount->setLastLoginNow();
        $em->persist($userAccount);
        $em->flush();

 
        return $userAccount;
    }
}

