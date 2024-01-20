<?php
declare(strict_types = 1);
namespace TgoeSrv\Member;

use TgoeSrv\Member\Enums\MemberDosbGender;
use TgoeSrv\Member\Enums\DosbSport;

class Member implements \Stringable
{

    public const easyvereinQueryString = '{id,membershipNumber,joinDate,resignationDate,emailOrUserName,_isChairman,contactDetails{salutation,firstName,familyName,dateOfBirth,street,city,zip,privateEmail},memberGroups{memberGroup' . MemberGroup::easyvereinQueryString . '},integrationDosbSport{title},integrationDosbGender,relatedMembers{membershipNumber},_relatedMember{membershipNumber}}';

    public const easyvereinDefaultOrder = "contactDetails__familyName,contactDetails__firstName";

    private int $id = - 1;

    private string $membershipNumber = '';

    private ?int $joinDate = null;

    private ?int $resignationDate = null;

    private String $loginName = '';

    private bool $isAdmin = false;

    private string $salutation = '';

    private string $firstName = '';

    private string $familyName = '';

    private ?int $dateOfBirth = null;

    private string $street = '';

    private string $city = '';

    private string $zip = '';

    private string $privateEmail = '';

    private MemberDosbGender $dosbGender = MemberDosbGender::UNKNOWN;

    private array $dosbSport = array();

    private array $memberGroups = array();
    
    private array $childMemberNumbers = array();
    
    private ?string $parentMemberNumber = null;


    public function __construct(?array $arr = null)
    {
        if ($arr != null) {
            $this->id = $arr['id'];
            $this->membershipNumber = $arr['membershipNumber'];
            $this->loginName = $arr['emailOrUserName'];
            $this->isAdmin = $arr['_isChairman'];

            $this->firstName = $arr['contactDetails']['firstName'];
            $this->familyName = $arr['contactDetails']['familyName'];
            $this->street = $arr['contactDetails']['street'];
            $this->city = $arr['contactDetails']['city'];
            $this->zip = $arr['contactDetails']['zip'];
            $this->privateEmail = $arr['contactDetails']['privateEmail'];
            $this->salutation = $arr['contactDetails']['salutation'];

            if (is_array($arr['memberGroups']))
            {
                foreach ($arr['memberGroups'] as $membership) {
                    $g = new MemberGroup($membership['memberGroup']);
                    $this->memberGroups[] = $g;
                }
            }

            if (isset($arr['joinDate']))
                $this->joinDate = strtotime($arr['joinDate']); // ISO 8601 date

            if (isset($arr['resignationDate']))
                $this->resignationDate = strtotime($arr['resignationDate']); // ISO 8601 date

            if (isset($arr['contactDetails']['dateOfBirth']))
                $this->dateOfBirth = strtotime($arr['contactDetails']['dateOfBirth']); // date format yyyy-MM-dd

            $this->dosbGender = MemberDosbGender::findByKey($arr['integrationDosbGender']);

            foreach ($arr['integrationDosbSport'] as $sportData) {
                $sportString = $sportData['title'];
                $sportString = strval($sportString);
                if (strlen($sportString) >= 3) {
                    $sport = DosbSport::findByKey(substr($sportString, 0, 3));
                    if (isset($sport))
                        $this->dosbSport[$sport->getKey()] = $sport;
                }
            }
            
            foreach ($arr['relatedMembers'] as $relMember) {
                $this->childMemberNumbers[] = $relMember['membershipNumber'];
            }
            
            if( $arr['_relatedMember'] !== null && count($arr['_relatedMember']) > 0) {
                $this->parentMemberNumber = $arr['_relatedMember']['membershipNumber'];
            }
        }
    }

    public function __toString(): string
    {
        return "Member[id=\"{$this->id}\", membershipNumber=\"{$this->membershipNumber}\", firstName=\"{$this->firstName}\", familyName=\"{$this->familyName}\"]";
    }

    /**
     *
     * @return number
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getMembershipNumber()
    {
        return $this->membershipNumber;
    }

    /**
     *
     * @return number
     */
    public function getJoinDate()
    {
        return $this->joinDate;
    }

    /**
     *
     * @return number
     */
    public function getResignationDate() : ?int
    {
        return $this->resignationDate;
    }

    /**
     *
     * @return string
     */
    public function getLoginName()
    {
        return $this->loginName;
    }

    /**
     *
     * @return boolean
     */
    public function isIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     *
     * @return string
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     *
     * @return string
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     *
     * @return number
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     *
     * @return string
     */
    public function getPrivateEmail()
    {
        return $this->privateEmail;
    }

    /**
     *
     * @return \TgoeSrv\Member\Enums\MemberDosbGender
     */
    public function getDosbGender()
    {
        return $this->dosbGender;
    }

    /**
     *
     * @return array
     */
    public function getDosbSport()
    {
        return $this->dosbSport;
    }

    /**
     *
     * @return array
     */
    public function getMemberGroups()
    {
        return $this->memberGroups;
    }
    
    /**
     * @return array
     */
    public function getChildMemberNumbers() : array
    {
        return $this->childMemberNumbers;
    }
    
    /**
     * @return string
     */
    public function getParentMemberNumber() : ?string
    {
        return $this->parentMemberNumber;
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
     * Calculate age of member by using bith date
     * 
     * @param ?int $referenceDate Reference date for age calculation. If not set, age on current day is calculated.
     * @return int
     */
    public function getAge( ?int $referenceDate = null ) : int
    {
        if( $this->getDateOfBirth() === null ) return 0;
        if( $referenceDate === null ) $referenceDate = time();
        
        //calculate age by year only
        $years = intval(date('Y', $referenceDate)) - intval(date('Y', $this->getDateOfBirth()));
                
        //In case birthday is after reference date, member did not have birthday in this year, yet.
        //To check this, calculate day of birthday in reference year.
        if( $referenceDate> mktime(0,0,0,intval(date('m', $this->getDateOfBirth())), intval(date('d', $this->getDateOfBirth())), intval(date('Y', $referenceDate)) )) {
            $years--;
        }
        
        return $years;
    }
    
    /**
     * Check is member is below 18 years old.
     * 
     * @return bool
     */
    public function isUnder18() : bool
    {
        return $this->getAge() < 18;
    }
}

