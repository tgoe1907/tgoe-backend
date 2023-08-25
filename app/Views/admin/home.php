<html>
<head>
<title>Welcome Admin</title>
</head>
<body>
Willkommen <?= esc(session()->get('userdata')->getFullName()) ?>
<br />
<a href='/admin/member-data-confirmation'>Erhebung Gruppenmitglieder</a><br />
<a href='/admin/data-quality-check'>Qualitätsprüfung Mitgliederdaten</a><br />
<a href="/login/logout">Logout</a><br />
</body>
</html>