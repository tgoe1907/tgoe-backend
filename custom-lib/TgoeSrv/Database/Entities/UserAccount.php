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
    
    #[Column(name: 'first_name', length: 50)]
    private string $firstName;
    
    #[Column(name: 'family_name', length: 50)]
    private string $familyName;
    
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