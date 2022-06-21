<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-1</title>
</head>
<body>
    <form action="" method="post">
        <div>
            <label for="name">名前</label>
            <input type="text" id="name" name="name">
        </div>
        <div>
            <label for="comment">コメント</label>
            <input type="text" id="comment" name="comment">
        </div>
        <div>
            <input type="submit" name="submit">
        </div>
    </form>
    <?php
        $filename = "m3-1.txt";
        if(!empty($_POST["submit"])){
            
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            if(file_exists($filename)){
                $lines = file($filename, FILE_IGNORE_NEW_LINES);
                $nums = [];
                foreach($lines as $line){
                    $form_datas = explode("<>", $line);
                    $nums[] = $form_datas[0];
                }
                $num = intval(end($nums)) + 1;
            }else{
                $num = 1;
            }
            $date = date("Y/m/d H:i:s");
            $data = $num . "<>" . $name . "<>" . $comment . "<>" . $date . PHP_EOL;
            
            $fp = fopen($filename, "a");
            fwrite($fp, $data);
            fclose($fp);

        }
        
    ?>
</body>
</html>