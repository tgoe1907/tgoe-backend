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
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Meldungen</h3>

    <div class="card-tools">
      Letzte Aktualisierung: <?= date('d.m.Y H:i', $updateTimestamp ) ?>
    </div>
  </div>
  <div class="card-body p-0">
    <table class="table">
      <thead>
        <tr>
        	<th>Typ</th>
        	<th>Nr.</th>
        	<th>Name</th>
        	<th>Prüfung</th>
        	<th>Schweregrad</th>
        	<th>Beanstandung</th>
        </tr>
      </thead>
      <tbody>
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
                $idHtml = '<a href="'.esc($link).'" target="_blank">'.esc($id).'</a>';
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
                $idHtml = esc($id);;
            }
            else {
                $type = "unbekannt";
                $name = "";
                $id = "";
            }
            
            
            
            ?>
            <tr>
            	<td><?= esc($type) ?></td>
            	<td><?= $idHtml ?></td>
            	<td><?= esc($name)?></td>
            	<td><?= esc($vm->getValidatorName())?></td>
            	<td><span class="badge <?= $vm->getSeverity()->getBackgroundCssClass() ?>"><?= esc($vm->getSeverity()->getDisplayName())?></span></td>
            	<td><?= esc($vm->getMessage())?></td>
            </tr>
            <?php 
        }
        ?>
      </tbody>
    </table>
  </div>
