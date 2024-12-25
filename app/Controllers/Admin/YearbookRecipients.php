<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use TgoeSrv\Member\Api\MemberGroupService;
use TgoeSrv\Document\GroupMemberDataConfirmationListService;
use TgoeSrv\Member\Api\MemberService;
use TgoeSrv\Member\MemberGroup;
use App\Libraries\CIHelper;
use TgoeSrv\Member\Member;

class YearbookRecipients extends BaseController
{

    public function index()
    {
        $ci = new CIHelper();
        $ci->setHeadline("Empfängerliste Jahrbuch erzeugen");
        $ci->initMenuLoggedin();
        return $ci->view('admin/yearbook-recipients/home');
    }

    public function download()
    {
        $srvM = new MemberService();
        $membersRaw = $srvM->getAllMembers();
        
        //convert to an hashmap using membership number as key
        $members = array();
        foreach( $membersRaw as $m ) {
            $members[$m->getMembershipNumber()] = $m;
        }
        
        //free some memory
        unset( $membersRaw); 

        // prepare new list to collect valis recipients
        // key is used to (1) group members who get one issue only and (2) sort list later
        // value is an array of all members for this key
        $recipients = array();

        foreach ($members as $m) {
            $excludeMember = false;

            // rules to exclude member
            $excludeMember |= $m->getResignationDate() !== null && $m->getResignationDate() < time() + 60 * 24 * 60 * 60; // exclude if membership is cancelled in next 60 days

            // if not excluded, add member to list in correct group
            if (!$excludeMember) {
                $parent = null;
                if( $m->getParentMemberNumber() != null && array_key_exists($m->getParentMemberNumber(), $members)) {
                    $parent = $members[$m->getParentMemberNumber()];
                }

                // rules to combine on same label with parent member
                $combineWithParent = $parent != null && (
                    $m->getAge() < 16  //family members below 16
                    || self::isSameAddress($m, $parent) //family members living at same place
                    );
                
                $x = $combineWithParent ? $parent : $m;
                
                $key = $x->getZip() == '76470' ? '00000' : $x->getZip(); // order by ZIP, Ötigheim on top of all
                $key .= $x->getStreet(); // oder by street
                $key .= '__'; // spacer for better debugging
                $key .= $x->getMembershipNumber();

                if (! array_key_exists($key, $recipients)) {
                    $recipients[$key] = array();
                }
                
                $recipients[$key][] = $m;
            }
        }
        
        //free some memory
        unset ($members);

        // sort list by key
        ksort($recipients);
        
        $exportdata = array();
        foreach($recipients as $a) {
            //find parent and sort by age
            $parent = null;
            $sortable = array();
            for( $i = 0; $i < count($a); $i++ ) {
                /**
                 * @var Member
                 */
                $m = $a[$i];
                if( $m->getParentMemberNumber() == null ) {
                    $parent = $m;
                }
                
                //create array to be sorted by age
                $sortable[$m->getAge()*1000 + $i] = $m;
            }

            //sort ascending
            krsort($sortable, SORT_NUMERIC);
            
            //if no parent found, use oldest member (first element in sorted list)
            if( $parent == null ) $parent = reset($sortable);
            
            //build additional names/member numbers array
            $additionalNames = array();
            $additionalMemberNumbers = array();
            foreach( $sortable as $m ) {
                if( $m == $parent ) continue;
                $additionalNames[] = $m->getFullName();
                $additionalMemberNumbers[] = $m->getMembershipNumber();
            }
            
            //use main receipients data from parent
            $data = array();
            $data['Name'] = $parent->getFullName();
            $data['WeitereNamen'] = implode(', ', $additionalNames);
            $data['Straße'] = $parent->getStreet();
            $data['PLZ_Ort'] = $parent->getZip().' '.$parent->getCity();
            $data['HauptMitgliedsnummer'] = $parent->getMembershipNumber();
            $data['WeitereMitgliedsnummern'] = implode(', ', $additionalMemberNumbers);
            
            $exportdata[] = $data;
        }
        
        //build csv data
        $filedata = "";
        for( $i = 0; $i<count($exportdata); $i++) {
            $data = $exportdata[$i];
            
            if( $i == 0) {
                $keys = array_keys($data);
                $filedata .= implode(';', $keys)."\r\n";
            }
            
            //quote the values
            $values = array();
            foreach( $keys as $k ) {
                $values[$k] = '"'.str_replace('"', '""', $data[$k]).'"';
            }
            
            $filedata .= implode(';', $values)."\r\n";
        }
        
        $filename = date('Y-m-d_H-i') . '_Export-Jahrbuch-Empfänger.csv';

        return $this->response->download($filename, $filedata)->setFileName($filename);
    }

    private static function isSameAddress(Member $m1, Member $m2): bool
    {
        $street1 = self::unifyStreetString($m1->getStreet());
        $street2 = self::unifyStreetString($m2->getStreet());
        return $street1 == $street2 && $m1->getZip() == $m2->getZip();
    }
    
    private static function unifyStreetString($s) {
        $s = strtolower($s);
        $s = str_replace(array('straße', 'str.'), 'str', $s);
        $s = str_replace(array(' ', "\t"), '', $s);
        return $s;
    }
}
