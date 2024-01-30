<?php
use App\Libraries\DataQualityCheckResult;
use TgoeSrv\Member\Validator\ValidationMessage;
use TgoeSrv\Member\Member;
use TgoeSrv\Member\MemberGroup;

/**
 *
 * @var DataQualityCheckResult $result
 */
?>
<div class="card">
	<div class="card-header">
		<b>Hinweise:</b>
		<ul>
			<li>Mitglieder, die in mehreren Gruppen aktiv sind, werden auch
				mehrfach gezählt!</li>
			<li>Passive Mitglieder werden nicht gezählt.</li>
		</ul>
	</div>
	<div class="card-body p-0">
		<table class="table">
			<thead>
				<tr>
					<th>Abteilungs-Kürzel</th>
					<th>Aktive Gruppenmitglieder</th>
					<th>Prozentsatz</th>
				</tr>
			</thead>
			<tbody>
        <?php
        foreach ($divisions as $k => $v) {
            $percent = $percentages[$k];
            ?>
            <tr>
					<td><?= esc($k) ?></td>
					<td><?= $v ?></td>
					<td><?= number_format($percent, 1, ',', '') ?> %</td>
				</tr>
            <?php
        }
        ?>
      </tbody>
		</table>
	</div>
</div>