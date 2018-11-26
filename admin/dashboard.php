<?php

    session_start();

    session_regenerate_id(true);

    //エラー処理
    if(!isset($_SESSION['id'])){
        header('Location:login.php');
        exit();
    } else {
        echo '<div class="top_bar"><p>こんにちは！&emsp;' . $_SESSION['email'] .'さん&emsp;<a href="logout.php" class="logout_button">ログアウト</a></p></div>';
    }

?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ダッシュボード</title>
    <meta name="viewport" content="width=1000">
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

    <body>

    <section class="wrap">

        <h1>コメント一覧</h1>

        <table class="db_table">

            <thead>
                <tr><th>名前</th><th>コメント</th><th>メールアドレス</th><th>操作</th></tr>
            </thead>

            <tbody>
                <?php

                require '../db_info/db_info.php';

                //データベース接続
                $dbh = new PDO(
                    'mysql:host=' . $host . '; dbname=' . $db_name . '; charset=utf8',
                    $user,
                    $pass,
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    )
                );


                //sql
                $sql = 'SELECT * FROM com_table WHERE 1';
                $stmt = $dbh->prepare($sql);
                $stmt->execute();

                //すべてのコメントを表示
                while(true){
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($row == false){
                        break;
                    }

                    echo '<tr><td>' . $row['name'] . '</td><td>' . $row['comment'] . '</td><td>' . $row['email'] . '</td><td><a href="update.php?id=' . $row['id'] .'">編集</a>/<a href="delete.php?id=' . $row['id'] .'">削除</a>/<a href="display.php?id=' . $row['id'] .'">承認</a>/<a href="reply.php?id=' . $row['id'] .'">返信</a></td></tr>';

                }

                //データベース接続解除
                $dbh = null;

                ?>
            </tbody>

        </table>

    </section>

</body>

</html>
