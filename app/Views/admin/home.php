<html>
<head>
<title>Welcome Admin</title>
</head>
<body>
Welcome <?= esc(session()->get('userdata')->getFullName()) ?>
<br />
<a href="/login/logout">Logout</a>
</body>
</html>