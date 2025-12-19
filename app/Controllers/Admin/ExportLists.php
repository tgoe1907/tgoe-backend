<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use TgoeSrv\Member\Api\MemberGroupService;
use TgoeSrv\Document\GroupMemberDataConfirmationListService;
use TgoeSrv\Member\Api\MemberService;
use TgoeSrv\Member\MemberGroup;
use App\Libraries\CIHelper;
use TgoeSrv\Member\Member;
use TgoeSrv\Tools\CSVTool;

class ExportLists extends BaseController
{

    public function index()
    {
        $ci = new CIHelper();
        $ci->setHeadline("Empfängerliste Jahrbuch erzeugen");
        $ci->initMenuLoggedin();
        return $ci->view('admin/export-lists/home');
    }
    
    public function birthday( $year, $birthdays = '') {
        $year = intval( $year );
        if( $year < 2000 ) $year = date('Y');
        $referencetimestamp = mktime(23,59,59,12,31,$year);
        
        if( strlen(trim($birthdays)) > 0) {
            $birthdays = explode(',', $birthdays);
            array_walk($birthdays, 'intval');
        } 
        
        //get all members
        $srvM = new MemberService();
        $members = $srvM->getAllMembers();
        
        $list = array();
        foreach ($members as $m) {
            $age = $m->getAge($referencetimestamp);
            
            //in case only special birthdays should be exported
            if( is_array($birthdays) ) { 
                if( !in_array($age, $birthdays)) continue; //skip if not needed
            }
            
            //add to result list and use birthday as key to sort easily
            $key = date('m-d', $m->getDateOfBirth()).'-'.$m->getMembershipNumber();
            $list[$key] = $m;
        }
        
        unset($members);
        
        // sort list by key
        ksort($list);
        
        //prepare export data
        $exportdata=array();
        foreach ($list as $m) {
            $data = array();
            $data['Name'] = $m->getFullName();
            $data['Straße'] = $m->getStreet();
            $data['PLZ_Ort'] = $m->getZip().' '.$m->getCity();
            $data['Mitgliedsnummer'] = $m->getMembershipNumber();
            $data['Geburtstag'] = date('d.m.Y', $m->getDateOfBirth());
            $data['Alter in '.$year] = $m->getAge($referencetimestamp);
            
            $exportdata[] = $data;
        }
        
        unset($list);

        $filedata = CSVTool::hasmap2csv($exportdata);
        $filename = date('Y-m-d_H-i') . "_Geburtstagsliste_für_Jahr_{$year}.csv";
        
        return $this->response->download($filename, $filedata)->setFileName($filename);
    }
    
    public function jubilee( $year, $jubilees = '' ) {
        $year = intval( $year );
        if( $year < 2000 ) $year = date('Y');
        $referencetimestamp = mktime(23,59,59,12,31,$year);
        
        if( strlen(trim($jubilees)) > 0) {
            $jubilees = explode(',', $jubilees);
            array_walk($jubilees, 'intval');
        }
        
        //get all members
        $srvM = new MemberService();
        $members = $srvM->getAllMembers();
        
        $list = array();
        foreach ($members as $m) {
            $age = $m->getMembershipYears($referencetimestamp);
            
            //in case only special birthdays should be exported
            if( is_array($jubilees) > 0) {
                if( !in_array($age, $jubilees)) continue; //skip if not needed
            }
            
            //add to result list and use jubilee age to sort easily
            $key = $age.'-'.$m->getMembershipNumber();
            $list[$key] = $m;
        }
        
        unset($members);
        
        // sort list by key
        ksort($list);
        
        //prepare export data
        $exportdata=array();
        foreach ($list as $m) {
            $data = array();
            $data['Name'] = $m->getFullName();
            $data['Straße'] = $m->getStreet();
            $data['PLZ_Ort'] = $m->getZip().' '.$m->getCity();
            $data['Mitgliedsnummer'] = $m->getMembershipNumber();
            $data['Jubiläum in '.$year] = $m->getMembershipYears($referencetimestamp);
            
            $exportdata[] = $data;
        }
        
        unset($list);
        
        $filedata = CSVTool::hasmap2csv($exportdata);
        $filename = date('Y-m-d_H-i') . "_Jubiläumsliste_für_Jahr_{$year}.csv";
        
        return $this->response->download($filename, $filedata)->setFileName($filename);
    }

    public function yearbookRecipients()
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
        
        $filedata = CSVTool::hasmap2csv($exportdata);
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
