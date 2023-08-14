<?php
use TgoeSrv\Member\MemberUserInformation;
use TgoeSrv\Member\Api\UserLoginHelper;
use TgoeSrv\Tools\ConfigManager;
use TgoeSrv\Tools\ConfigKey;

require __DIR__ . '/../vendor/autoload.php';
?>
<html>
<body>
	<form method='POST' action='test-pw.php'>
		User <input name='user'><br />
		Password <input name='pwd' type="password"><br />
		<input type="submit" value="check">
	</form>
	<br />
	<?php 

	if( isset($_REQUEST['user']) ) {
	    $h = new UserLoginHelper();
	    $r = $h->verifyUserLogin($_REQUEST['user'], $_REQUEST['pwd']);
	    var_dump($r);
	}
	?>
</body>
</html>