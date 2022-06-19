<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_2-4</title>
    <form action="" method="post">
        <input type="text" name="comment" value="コメント">
        <input type="submit" name="submit">
    </form>
</head>
<body>
    <?php
        echo "↓みなさんが書き込んでくれたコメントが表示されます<br><br>";
        
        $filename = "m2-4.txt";
        
        if(empty($_POST["comment"]) == False){
            $comment = $_POST["comment"];

            $fp = fopen($filename, "a");
            fwrite($fp, $comment . PHP_EOL);
            fclose($fp);
        }
        
        $texts = file($filename, FILE_IGNORE_NEW_LINES);
        foreach($texts as $text){
            echo $text . "<br>";
        }
    ?>
</body>
</html>