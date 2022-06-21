<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-2</title>
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
        $filename = "m3-2.txt";
        if(file_exists($filename)){
            $nums = [];
            $lines = file($filename, FILE_IGNORE_NEW_LINES);
            foreach($lines as $line){
                $formdatas = explode("<>", $line);
                $nums[] = $formdatas[0];
                foreach($formdatas as $formdata){
                    echo $formdata . " ";
                }
                echo "<br>";
                $num = intval(end($nums)) + 1;
            }
        }else{
            $num = 1;
        }
        if(!empty($_POST["submit"])){
            
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $date = date("Y/m/d H:i:s");
            $data = $num . "<>" . $name . "<>" . $comment . "<>" . $date . PHP_EOL;
            
            $fp = fopen($filename, "a");
            fwrite($fp, $data);
            fclose($fp);
            
            $new_formdata = $num . " " . $name . " " . $comment . " " . $date . "<br>";
            echo $new_formdata;
        }
        
    ?>
</body>
</html>