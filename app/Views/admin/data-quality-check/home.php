<?php 
use App\Libraries\DataQualityCheckResult;
use TgoeSrv\Member\Validator\ValidationMessage;
use TgoeSrv\Member\Member;
use \TgoeSrv\Member\MemberGroup;

/**
 *
 * @var DataQualityCheckResult $result
 */
?>
<html>
<head>
<title>Member Data Confirmation</title>
</head>
<body>
<h1>Erhebung Gruppenmitglieder</h1>
<br />
<a href="/admin">zurück</a><br />
<br />
Stand <?= date('d.m.Y H:i', $updateTimestamp ) ?>

<table border=1>
    <tr>
    	<th>Typ</th>
    	<th>Nr.</th>
    	<th>Name</th>
    	<th>Prüfung</th>
    	<th>Schweregrad</th>
    	<th>Beanstandung</th>
    </tr>
    <?php 
    foreach($validationMessages as $vm) {
        /**
         * @var ValidationMessage $vm
         */
        
        if( $vm->getTargetObject() instanceof Member ) {
            /**
             * 
             * @var Member $member
             */
            $member = $vm->getTargetObject();
            $type = "Mitglied";
            $name = $member->getFullName();
            $id = $member->getMembershipNumber();
        } 
        else if( $vm->getTargetObject() instanceof MemberGroup ) {
            /**
             * 
             * @var MemberGroup $group
             */
            $group = $vm->getTargetObject();
            $type = "Gruppe";
            $name = $group->getName();
            $id = $group->getKey();
        }
        else {
            $type = "unbekannt";
            $name = "";
            $id = "";
        }
        ?>
        <tr>
        	<td><?= esc($type) ?></td>
        	<td><?= esc($id)?></td>
        	<td><?= esc($name)?></td>
        	<td><?= esc($vm->getValidatorName())?></td>
        	<td><?= esc($vm->getSeverity()->getDisplayName())?></td>
        	<td><?= esc($vm->getMessage())?></td>
        </tr>
        <?php 
    }
    ?>
</table>

</body>
</html>