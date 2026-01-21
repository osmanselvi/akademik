<!DOCTYPE html>
<html>
<head>
    <title>Giriş Yap</title>
</head>
<body>
    <h2>Giriş Yap</h2>
    <form method="post" action="?action=login">
        <label>Kullanıcı Adı: <input type="text" name="username" required></label><br>
        <label>Şifre: <input type="password" name="password" required></label><br>
        <button type="submit">Giriş Yap</button>
    </form>
    <a href="?action=register">Kayıt Ol</a>
</body>
</html>
