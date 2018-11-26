<?php

    require '../db_info/db_info.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        //メッセージの初期化
        $error = '';
        $success = '';

        //入力値のチェック
        if(empty($_POST['email']) || empty($_POST['password']) || empty($_POST['password2'])){
            $error = '未入力の項目があります。';
        } elseif(strlen($_POST['email']) > 100 || strlen($_POST['password']) > 15 || strlen($_POST['password2']) > 15){
            $error = 'メールアドレスは100文字以内、パスワードは15文字以内でご入力ください。';
        }elseif(strlen($_POST['password']) < 4 || strlen($_POST['password2']) < 4){
            $error = 'パスワードは4文字以上15文字以内でご入力ください。';
        } elseif($_POST['password'] != $_POST['password2']){
            $error = 'パスワードが一致しません。';
        }else {
            $email = htmlspecialchars($_POST['email'],ENT_QUOTES, 'UTF-8');
            $password1 = htmlspecialchars($_POST['password'],ENT_QUOTES, 'UTF-8');
            $password2 = htmlspecialchars($_POST['password2'],ENT_QUOTES, 'UTF-8');

            try{

                //データベース接続
                $dbh = new PDO(
                    'mysql:host=' . $host . '; dbname=' . $db_name .'; charset=utf8',
                    $user,
                    $pass,
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    )
                );

                //入力したメールアドレスがすでに存在するかチェック
                $check_sql = 'SELECT id FROM user_table WHERE email=?';
                $check_data[] = $email;
                $check_stmt = $dbh->prepare($check_sql);
                $check_stmt->execute($check_data);

                $row = $check_stmt->fetch(PDO::FETCH_ASSOC);

                if($row == false){
                    //メールアドレスが使われていなかったら追加処理
                    $sql = 'INSERT INTO user_table(email,password) VALUES (?,?)';
                    $data[] = $email;
                    $data[] = $password1;
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute($data);

                    $success = '新規登録が完了しました。<a href="login.php">ログインページ</a>からログインしてください。';
                } else {
                    //すでに入力したアドレスのデータがあればエラー
                    $error = 'このメールアドレスはすでに使われています。';
                }


            }catch(PDOException $e){
                echo '<div class="server_error">現在サーバーエラーが発生しています。</div>';
            }

        }

    }


?>




<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>新規登録ページ</title>
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

                if(isset($success)){
                    echo '<ul class="success"><li>' . $success . '</li></ul>';
                }

            ?>

            <form action="signup.php" method="post" class="login_form">
                <label>メールアドレス</label>
                <input type="email" name="email">
                <label>パスワード</label>
                <input type="password" name="password">
                <label>パスワード（確認）</label>
                <input type="password" name="password2">
                <input type="submit" name="submit" value="登録">
            </form>

            <center><a href="login.php">アカウントをお持ちの方はこちら</a></center>

        </div>

    </section>

</body>

</html>
