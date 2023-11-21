<?php
declare(strict_types = 1);
namespace TgoeSrv\Member;

use TgoeSrv\Member\Enums\DosbSport;
use TgoeSrv\Member\Enums\MemberGroupCustomProperty;

class MemberGroup implements \Stringable
{

    public const easyvereinQueryString = "{id,name,short,descriptionOnInvoice,orderSequence}";

    public const easyvereinDefaultOrder = "orderSequence";

    
    private const FEE_ACTIVE_MEMBER_KEY = "B-AK";
    private const FEE_GROUP_PREFIX = "B-";
    private const NON_CLASS_GROUP_PREFIX = "X-";

    private int $id = - 1;

    private string $key = '';

    private string $name = '';

    private string $description = '';

    private int $orderSequence = - 1;

    private array $customPropertyCache;

    public function __construct(?array $arr = null)
    {
        if ($arr != null) {
            $this->id = $arr['id'];
            $this->key = $arr['short'];
            $this->name = $arr['name'];
            
            if( isset($arr['orderSequence']) ) $this->orderSequence =  $arr['orderSequence'];
            if( isset($arr['descriptionOnInvoice'])) $this->description = $arr['descriptionOnInvoice'];
        }
    }

    public function __toString(): string
    {
        return "MemberGroup[id=\"{$this->id}\", key=\"{$this->key}\", name=\"{$this->name}\"]";
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getOrderSequence(): string
    {
        return $this->orderSequence;
    }

    /**
     * In description, we use key/value format to specify custom properties.
     * Use this method to get a value.
     *
     * @param MemberGroupCustomProperty $p
     * @return string Value of custom property. Empty string in case it exists but has no value. NULL in case it does not exist.
     */
    public function getCustomProperty(MemberGroupCustomProperty $memberGroupCustomProperty): ?string
    {
        if (! isset($this->customPropertyCache)) {
            // parse description and initialize customPropertyCache
            $this->customPropertyCache = array();
            $lines = explode("\n", $this->description);
            foreach ($lines as $line) {
                $parts = explode('=', $line);
                $prop = MemberGroupCustomProperty::findByKey(trim($parts[0]));
                if (isset($prop)) {
                    $value = count($parts) > 1 ? $parts[1] : '';
                    $this->customPropertyCache[$prop->value] = $value;
                }
            }
        }

        return isset($this->customPropertyCache[$memberGroupCustomProperty->value]) ? $this->customPropertyCache[$memberGroupCustomProperty->value] : null;
    }

    /**
     * Returns DOSB sport custom property converted to enumeration.
     * Returns null in case no value provided or shorter than 3 chars.
     * Returns UNKNOWN value in case key not implemented in enumeration.
     * String value must start by 3 character key in order to find enumeration value.
     *
     * @return DosbSport
     */
    public function getDosbSportCustomProperty(): ?DosbSport
    {
        $stringValue = $this->getCustomProperty(MemberGroupCustomProperty::DOSB_SPORT);
        if (! isset($stringValue))
            return null;

        $stringValue = trim($stringValue);
        if (strlen($stringValue) < 3)
            return null;

        $dosbSport = DosbSport::findByKey(substr($stringValue, 0, 3));
        return isset($dosbSport) ? $dosbSport : DosbSport::UNKNOWN;
    }

    /**
     * Check if this group is used for charging membership fee.
     *
     * @return bool
     */
    public function isMemberFeeGroup(): bool
    {
        return $this->key != null && str_starts_with($this->key, self::FEE_GROUP_PREFIX);
    }
    
    /**
     * Check if this group is used for active membership
     *
     * @return bool
     */
    public function isActiveMembershipFeeGroup(): bool
    {
        return $this->key != null && str_starts_with($this->key, self::FEE_ACTIVE_MEMBER_KEY);
    }

    /**
     * Check if this group represents a sports class.
     *
     * @return bool
     */
    public function isClassGroup(): bool
    {
        return 
            $this->key !== null &&
            (!$this->isMemberFeeGroup() && !str_starts_with($this->key, self::NON_CLASS_GROUP_PREFIX));
    }
}

