<?php

    session_start();

    $_SESSION = array();

    if(isset($_COOKIE[session_name()]) == true){
        setcookie(session_name() , '' ,time()-42000,'/');
    }

    session_destroy();

?>




<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログアウトしました</title>
    <meta name="viewport" content="width=1000">
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>

    <section class="wrap">

        <h1>ログアウトしました。</h1>
        <p><a href="login.php">ログインページに戻る</a></p>

    </section>

</body>

</html>
