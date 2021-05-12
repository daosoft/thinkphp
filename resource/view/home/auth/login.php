<!DOCTYPE html>
<html lang="zh-Hans">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/sentsin/layui@v2.6.6/dist/css/layui.css">
    <script src="https://cdn.jsdelivr.net/gh/sentsin/layui@v2.6.6/dist/layui.js"></script>
    {:token_meta()}
</head>
<body>
User Login
<form action="" method="post">
    <input type="submit" value="submit">
    {:token_field()}
</form>
</body>
</html>
