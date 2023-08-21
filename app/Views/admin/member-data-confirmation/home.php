<html>
<head>
<title>Member Data Confirmation</title>
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
	</tr>
<?php }?>
</table>

</body>
</html>