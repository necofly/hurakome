<?php

session_start();

require '../db_info/db_info.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $error = '';

    if(empty($_POST['email']) || empty($_POST['password'])){
        $error = '未入力の項目があります。';
    } elseif(strlen($_POST['email']) > 100 || strlen($_POST['password']) > 16){
        $error = 'メールアドレスは100文字以内、パスワードは15文字以内でご入力ください。';
    } else {
        $email = htmlspecialchars($_POST['email'],ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($_POST['password'],ENT_QUOTES, 'UTF-8');

        try{

            $dbh = new PDO(
                'mysql:host=' . $host . '; dbname=' . $db_name .'; charset=utf8',
                $user,
                $pass,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                )
            );

            $sql = 'SELECT id FROM user_table WHERE email=? AND password=?';
            $data[] = $email;
            $data[] = $password;
            $stmt = $dbh->prepare($sql);
            $stmt->execute($data);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row == false){
                echo '<div class="server_error">メールアドレスまたはパスワードが間違っています。</div>';
            } else {
                $_SESSION['id']=$row['id'];
                $_SESSION['email']=$email;
                header('Location: dashboard.php');
                exit();
            }

            $dbh = null;

        } catch(PDOException $e){
            echo '<div class="server_error">現在サーバーエラーが発生しています。</div>';
        }

    }

}

?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログインページ</title>
    <meta name="viewport" content="width=1000">
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>

    <section class="wrap login">

        <div class="login_wrap">
            <img src="../images/logo.jpg" class="ic logo">

            <?php

                if(isset($error)){
                    echo '<ul class="error"><li>' . $error . '</li></ul>';
                }

            ?>

            <form action="login.php" method="post" class="login_form">
                <label>メールアドレス</label>
                <input type="email" name="email">
                <label>パスワード</label>
                <input type="password" name="password">
                <input type="submit" name="submit" value="ログイン">
            </form>

            <center><a href="signup.php">アカウントをお持ちでない方はこちら</a></center>

        </div>

    </section>

</body>

</html>
