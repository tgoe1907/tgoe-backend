<html>
<head>
<title>Erhebung Gruppenmitglieder</title>
</head>
<body>
<h1>Erhebung Gruppenmitglieder</h1>
<br />
<a href="/admin">zurück</a><br />
<br />
Stand <?= date('H:i', $cacheage ) ?> <a href="/admin/member-data-confirmation/refresh">(aktualisieren)</a><br />
<table border=1>
<?php foreach( $grouplist as $group ) { 
    ?>
	<tr>
		<td><?= esc($group->getKey())?></td>
		<td><?= esc($group->getName())?></td>
		<td><a href='/admin/member-data-confirmation/downloadlist/<?= esc($group->getKey()) ?>'>Liste</a></td>
	</tr>
<?php }?>
</table>

</body>
</html>