<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_2-2</title>
    <form action="" method="post">
        <input type="text" name="comment" value="コメント">
        <input type="submit" name="submit">
    </form>
</head>
<body>
    <?php
        if(empty($_POST["comment"]) == False){
            $comment = $_POST["comment"];
            $filename = "m2-2.txt";

            $fp = fopen($filename, "w");
            fwrite($fp, $comment);
            fclose($fp);
            
            if($comment == "完成！"){
                echo "おめでとう！";
            }
        }
    ?>
</body>
</html>