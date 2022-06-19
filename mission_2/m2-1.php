<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_2-1</title>
    <form action="" method="post">
        <input type="text" name="comment" value="コメント">
        <input type="submit" name="submit">
    </form>
</head>
<body>
    <?php
        $comment = $_POST["comment"];
        if(isset($comment)){
            echo $comment . " を受け付けました";
        }
    ?>
</body>
</html>