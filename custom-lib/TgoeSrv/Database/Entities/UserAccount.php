<?php 
namespace TgoeSrv\Database\Entities;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
#[Table(name: 'user_account')]
class UserAccount
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'id', unique: true)]
    private int $id;
    
    #[Column(name: 'email', length: 50)]
    private string $email;
    
    #[Column(name: 'password_hash', length: 100)]
    private string $passwordHash;
    
    #[Column(name: 'failed_login_count')]
    private int $failedLoginCount;
    
    #[Column(name: 'first_name', length: 50)]
    private string $firstName;
    
    #[Column(name: 'family_name', length: 50)]
    private string $familyName;
    
    #[Column(name: 'last_login')]
    private ?\DateTime $lastLogin;
    
    /**
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return clone $this->lastLogin;
    }

    /**
     */
    public function setLastLoginNow()
    {
        $this->lastLogin = new \DateTime();
    }

    /**
     * @return number
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * @return number
     */
    public function getFailedLoginCount()
    {
        return $this->failedLoginCount;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * @param number $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param string $passwordHash
     */
    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    /**
     * @param number $failedLoginCount
     */
    public function setFailedLoginCount($failedLoginCount)
    {
        $this->failedLoginCount = $failedLoginCount;
    }
    
    /**
     */
    public function incrementFailedLoginCount()
    {
        $this->failedLoginCount++;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @param string $familyName
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;
    }

    /**
     * Combine first name and family name delimited by space.
     * Returns empty string
     * in case both name parts are empty. Never returns null.
     *
     * @return string
     */
    public function getFullName(): string
    {
        return $this->firstName . (! empty($this->firstName) ? ' ' : '') . $this->familyName;
    }
}
?>