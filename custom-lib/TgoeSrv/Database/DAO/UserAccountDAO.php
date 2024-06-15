<?php 
namespace TgoeSrv\Database\DAO;

use TgoeSrv\Database\DbHelper;
use TgoeSrv\Database\Entities\UserAccount;
use TgoeSrv\Tools\Logger;

class UserAccountDAO {
    /**
     * Try to get user account by e-mail. If none or multipe matching records exist, function returns null;
     *
     * @param string $email
     * @return UserAccount|NULL
     */
    public static function findUniqueUserAccountByEMail(string $email): ?UserAccount
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
            Logger::info("User not found E-Mail '{$email}' did not match any record.");
            return null;
        }
        
        if ($cnt > 1) {
            Logger::info("E-Mail '{$email}' matches many records. Do not accept.");
            return null;
        }

        /**
         *
         * @var UserAccount $userAccount
         */
        $userAccount = $users[0];
        
        return $userAccount;
    }
}
?>