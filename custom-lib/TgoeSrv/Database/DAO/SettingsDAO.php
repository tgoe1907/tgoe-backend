<?php 
namespace TgoeSrv\Database\DAO;

use TgoeSrv\Database\DbHelper;

class SettingsDAO {
    /**
     * Read all settings records
     * 
     * @return array|NULL
     */
    public static function findAllSettings(): ?array
    {
        $em = DbHelper::getEntityManager();
        
        $qb = $em->createQueryBuilder()
        ->select('s')
        ->from('TgoeSrv\Database\Entities\Settings', 's');
        
        return $qb->getQuery()->getResult();
    }
}
?>