<?php
declare(strict_types = 1);
namespace TgoeSrv\Member;

use TgoeSrv\Tools\ConfigManager;
use TgoeSrv\Tools\ConfigKey;
use TgoeSrv\Member\Enums\UserPermission;

class MemberUserInformation implements \Stringable
{

    public const easyvereinQueryString = '{id,membershipNumber,customFields{value,customField{name}},contactDetails{firstName,familyName,privateEmail}}';

    private int $id = - 1;

    private string $membershipNumber = '';

    private string $firstName = '';

    private string $familyName = '';

    private string $privateEmail = '';

    private array $customFields = array();
    
    private array $permissionCheckCache = array();

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    public function __construct(?array $arr = null)
    {
        if ($arr != null) {
            $this->id = $arr['id'];
            $this->membershipNumber = $arr['membershipNumber'];

            $this->firstName = $arr['contactDetails']['firstName'];
            $this->familyName = $arr['contactDetails']['familyName'];
            $this->privateEmail = $arr['contactDetails']['privateEmail'];

            foreach ($arr['customFields'] as $cf) {
                $cfName = $cf['customField']['name'];
                $cfValue = $cf['value'];
                $this->customFields[$cfName] = $cfValue;
            }
        }
    }

    public function __toString(): string
    {
        return "MemberUserInformation[id=\"{$this->id}\", membershipNumber=\"{$this->membershipNumber}\", firstName=\"{$this->firstName}\", familyName=\"{$this->familyName}\"]";
    }

    /**
     *
     * @return number
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getMembershipNumber(): string
    {
        return $this->membershipNumber;
    }

    /**
     *
     * @return string
     */
    public function getPrivateEmail(): string
    {
        return $this->privateEmail;
    }

    /**
     *
     * @return array
     */
    public function getCustomField($fieldName): ?string
    {
        return isset($this->customFields[$fieldName]) ? $this->customFields[$fieldName] : null;
    }

    public function getLoginUsername(): ?string
    {
        return $this->getCustomField(ConfigManager::getValue(ConfigKey::EASYVEREIN_CUSTOMFIELD_LOGINNAME));
    }

    public function getLoginPasswordHash(): ?string
    {
        return $this->getCustomField(ConfigManager::getValue(ConfigKey::EASYVEREIN_CUSTOMFIELD_PASSWORDHASH));
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
    
    /**
     * For security reasons it can be useful to clear senstitive information.
     * E.g. when this object is stored in the session (which could be compromised).
     */
    public function clearLoginCustomFields() :void {
        unset($this->customFields[ConfigManager::getValue(ConfigKey::EASYVEREIN_CUSTOMFIELD_LOGINNAME)]);
        unset($this->customFields[ConfigManager::getValue(ConfigKey::EASYVEREIN_CUSTOMFIELD_PASSWORDHASH)]);
    }
    
    public function hasPermission( UserPermission $p ) : bool {
        if( !isset( $this->permissionCheckCache[$p->value]) ) {
            $mappingConfigKey = UserPermission::getConfigKey($p);
            
            if( $mappingConfigKey !== null ) {
                $permissionMappedName = ConfigManager::getValue($mappingConfigKey);
            }
            else $permissionMappedName = null;
            
            if( $permissionMappedName !== null ) {
                $permissions = $this->getCustomField(ConfigManager::getValue(ConfigKey::EASYVEREIN_CUSTOMFIELD_PERMISSIONS));
                
                //check if requested permission is in list of given permissions
                $r = in_array($permissionMappedName, $permissions);
            }
            else $r = false;
            
            $this->permissionCheckCache[$p->value] = $r;
        }
        
        return $this->permissionCheckCache[$p->value];
    }
}

