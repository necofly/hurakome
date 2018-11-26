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


if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $error = '';
    $success = '';

    if(empty($_POST['reply'])){
        $error = '返信コメントが入力されていません。';
    } elseif(strlen($_POST['reply']) > 1000){
        $error = '返信コメントは1000文字以内でご記入ください。';
    } elseif($error === '') {
        $reply = htmlspecialchars( $_POST['reply'] ,ENT_QUOTES, 'UTF-8');

        try{

            $dbh = new PDO(
                'mysql:host=' . $host . '; dbname=' . $db_name . '; charset=utf8',
                $user,
                $pass,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                )
            );

            $sql = 'UPDATE com_table SET reply=? WHERE id = ?';
            $data[] = $reply;
            $data[] = $comment_id;
            $stmt = $dbh->prepare($sql);
            $stmt->execute($data);

            $success = 'コメントに返信しました。';

            //接続解除
            $dbh = null;

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
    <title>コメント返信ページ</title>
    <meta name="viewport" content="width=1000">
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>

    <section class="wrap ww">

        <?php

            if(isset($error)){
                echo '<ul class="error"><li>' . $error . '</li></ul>';
            }

             if(isset($success)){
                echo '<ul class="success"><li>' . $success . '</li></ul>';
            }

        ?>

        <form action="reply.php?id=<?php echo $comment_id; ?>" method="post" class="comment_form">
            <label>返信</label>
            <textarea name="reply"></textarea>
            <input type="submit" name="submit" value="返信">
        </form>

        <a href="dashboard.php" class="go_home">戻る</a>

    </section>

</body>

</html>
