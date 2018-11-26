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
}catch(PDOException $e){
    echo '<div class="server_error">現在サーバーエラーが発生しています。</div>';
}


if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $error = '';
    $success = '';

    if(empty($_POST['comment'])){
        $error = 'コメントが入力されていません。';
    } elseif(strlen($_POST['comment']) > 1000){
        $error = 'コメントは1000文字以内でご記入ください。';
    } else {
        $comment = htmlspecialchars( $_POST['comment'] ,ENT_QUOTES, 'UTF-8');

        try{

            $sql = 'UPDATE com_table SET comment=? WHERE id = ?';
            $data[] = $comment;
            $data[] = $comment_id;
            $stmt = $dbh->prepare($sql);
            $stmt->execute($data);

            $success = 'コメントを編集しました。';

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
    <title>コメント編集ページ</title>
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

            $check_sql = 'SELECT comment FROM com_table WHERE id = ?';
            $check_data[] = $comment_id;
            $check_stmt = $dbh->prepare($check_sql);
            $check_stmt->execute($check_data);

            $row = $check_stmt->fetch(PDO::FETCH_ASSOC);
            $now_com = $row['comment'];

            $dbh = null;

        ?>

        <form action="update.php?id=<?php echo $comment_id; ?>" method="post" class="comment_form">
            <label>コメント</label>
            <textarea name="comment"><?php echo $now_com; ?></textarea>
            <input type="submit" name="submit" value="編集">
        </form>

        <a href="dashboard.php" class="go_home">戻る</a>

    </section>

</body>

</html>
