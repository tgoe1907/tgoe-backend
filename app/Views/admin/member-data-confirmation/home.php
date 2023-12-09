<?php 
use TgoeSrv\Member\MemberGroup;
use TgoeSrv\Member\Enums\MemberGroupCustomProperty;
?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Sportgruppen</h3>

    <div class="card-tools">
      Stand <?= date('H:i', $cacheage ) ?> | <a href="/admin/member-data-confirmation/refresh"><i class="fa fa-refresh"></i> aktualisieren</a>
    </div>
  </div>
  <div class="card-body p-0">
    <table class="table">
      <thead>
        <tr>
        	<th>Kürzel</th>
        	<th>Gruppenname<br />DOSB-Sportart</th>
        	<th>Trainer<br />Ort</th>
        	<th>Tag<br />Zeit</th>
        	<th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach( $grouplist as $group ) { 
            /*
             * @var MemberGroup $group
             */
            ?>
        	<tr>
        		<td><?= esc($group->getKey()) ?></td>
            	<td>
            		<?= esc($group->getName())?><br >
            		<?= esc($group->getCustomProperty(MemberGroupCustomProperty::DOSB_SPORT))?>
        		</td>
        		<td>
        			<?= esc($group->getCustomProperty(MemberGroupCustomProperty::TRAINER))?><br />
        			<?= esc($group->getCustomProperty(MemberGroupCustomProperty::LOCATION))?>
    			</td>
        		<td>
        			<?= esc($group->getCustomProperty(MemberGroupCustomProperty::WEEKDAY))?><br />
					<?= esc($group->getCustomProperty(MemberGroupCustomProperty::TIME))?>
				</td>
        		<td>
            		<a href='/admin/member-data-confirmation/downloadlist/<?= esc($group->getKey()) ?>/pdf'><i class="fa fa-file-pdf"></i> PDF</a>
            		|
            		<a href='/admin/member-data-confirmation/downloadlist/<?= esc($group->getKey()) ?>/word'><i class="fa fa-file-word"></i> Word</a>
        		</td>
        	</tr>
        <?php }?>
      </tbody>
    </table>
  </div>
</div>