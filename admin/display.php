<?php

session_start();

session_regenerate_id(true);

if(!isset($_SESSION['id'])){
    header('Location:login.php');
    exit();
} else {
    echo '<div class="top_bar"><p>こんにちは！&emsp;' . $_SESSION['email'] .'さん&emsp;<a href="logout.php" class="logout_button">ログアウト</a></p></div>';
}

require '../db_info/db_info.php';

$comment_id = $_GET['id'];


if(isset($_POST["display"])){

        try{

            $display = true;

            $dbh = new PDO(
                'mysql:host=' . $host . '; dbname=' . $db_name . '; charset=utf8',
                $user,
                $pass,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                )
            );

            $sql = 'UPDATE com_table SET display=? WHERE id = ?';
            $data[] = $display;
            $data[] = $comment_id;
            $stmt = $dbh->prepare($sql);
            $stmt->execute($data);

            $dbh = null;

            header('Location:dashboard.php');
            exit();

        }catch(PDOException $e){
            echo '<div class="server_error">現在サーバーエラーが発生しています。</div>';
        }

    }

?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>コメント承認ページ</title>
    <meta name="viewport" content="width=1000">
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>

    <section class="wrap ww">

        <form action="display.php?id=<?php echo $comment_id; ?>" method="post" class="comment_form">
            <label>コメントを承認しますか？</label>
            <input type="submit" name="display" value="承認">
        </form>

        <a href="dashboard.php" class="go_home">戻る</a>

    </section>

</body>

</html>
