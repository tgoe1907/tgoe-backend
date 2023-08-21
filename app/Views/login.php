<html>
<head>
<title>Login Form</title>
</head>
<body>
	<?php
if (isset($messages)) {
    foreach ($messages as $message) {
        echo '<i>' . esc($message) . '</i><br />';
    }
}
?>
	Login Form<br />
	<form method='POST' action='/login'>
		User <input name='loginform-usr'><br /> 
		Password <input name='loginform-pwd' type="password"><br />
		<input type="submit" value="check">
	</form>
</body>
</html>