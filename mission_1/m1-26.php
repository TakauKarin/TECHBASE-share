<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_1-26</title>
</head>
<body>
    <?php
        $str = "さしすせそ";
        $filename = "mission_1-24.txt";
        
        $fp = fopen($filename, "a");
        fwrite($fp, $str.PHP_EOL);
        fclose($fp);
        
        echo "書き込み成功！<br>";
        
        if(file_exists($filename)){
            $texts = file($filename,FILE_IGNORE_NEW_LINES);  //記入例では$linesとしていた
            foreach($texts as $text){
                echo $text . "<br>";
            }
        }
    ?>
</body>
</html>