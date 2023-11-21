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
<title>Qualitätsprüfung Mitgliederdaten</title>
</head>
<body>
<h1>Qualitätsprüfung Mitgliederdaten</h1>
<br />
<a href="/admin">zurück</a><br />
<br />
Status: <?= $statusMessage ?><br />
Letzte Aktualisierung: <?= date('d.m.Y H:i', $updateTimestamp ) ?>

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
            $link = 'https://easyverein.com/app/profile/'.$member->getId();
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
            $link = '#';
        }
        else {
            $type = "unbekannt";
            $name = "";
            $id = "";
        }
        ?>
        <tr>
        	<td><?= esc($type) ?></td>
        	<td><a href="<?= esc($link) ?>" target='_blank'><?= esc($id)?></a></td>
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