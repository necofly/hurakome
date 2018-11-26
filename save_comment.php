<?php

require 'db_info/db_info.php';

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

    if(empty($_POST['comment']) || empty($_POST['name']) || empty($_POST['email'])){
        $error = '入力されていない項目があります。';
    } elseif(strlen($_POST['comment']) > 1000){
        $error = 'コメントは1000文字以内でご記入ください。';
    } elseif(strlen($_POST['name']) > 15){
        $error = 'お名前は15文字以内でご入力ください。';
    } elseif(strlen($_POST['email']) > 100){
        $error = 'メールアドレスは100文字以内でご入力ください。';
    } else {
        $comment = htmlspecialchars( $_POST['comment'] ,ENT_QUOTES, 'UTF-8');
        $name = htmlspecialchars( $_POST['name'] ,ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars( $_POST['email'] ,ENT_QUOTES, 'UTF-8');

        try{

            $sql = 'INSERT INTO com_table(comment,name,email) VALUE (?,?,?)';
            $data[] = $comment;
            $data[] = $name;
            $data[] = $email;
            $stmt = $dbh->prepare($sql);
            $stmt->execute($data);

            $success = 'コメントを投稿しました。承認をお待ちください。';

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
    <title>コメント入力ページ</title>
    <meta name="viewport" content="width=1000">
    <meta name="robots" content="noindex,nofollow">
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

    <section class="wrap ww">
        <img src="images/logo.jpg" class="ic">

        <?php

        if(isset($error)){
            echo '<ul class="error"><li>' . $error . '</li></ul>';
        }

         if(isset($success)){
            echo '<ul class="success"><li>' . $success . '</li></ul>';
        }

        ?>

        <form action="save_comment.php" method="post" class="comment_form">
            <label>コメント</label>
            <textarea name="comment"></textarea>
            <label>お名前</label>
            <input type="text" name="name">
            <label>メールアドレス</label>
            <input type="email" name="email">
            <input type="submit" name="submit" value="投稿">
        </form>


        <h2>コメント一覧</h2>

        <ul class="comment_list">

        <?php

        try{

            $comlist_sql = 'SELECT * FROM com_table WHERE display=?';
            $comlist_data[] = true;
            $comlist_stmt = $dbh->prepare($comlist_sql);
            $comlist_stmt->execute($comlist_data);

            while(true){
                $row = $comlist_stmt->fetch(PDO::FETCH_ASSOC);
                if($row == false){
                    break;
                }

                echo '<li>' . $row['comment'] . '<br><span class="name">by ' . $row['name'] . '</span>';
                
                if(!empty($row['reply'])){
                    echo '<ul class="reply"><li>' . $row['reply'] . '</li></ul>';
                }
                echo '</li>';

            }

            $dbh = null;

        }catch(PDOException $e){
            echo '<div class="server_error">現在コメントを取得できません。</div>';
        }

        ?>

        </ul>

    </section>

</body>

</html>
