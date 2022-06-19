<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_1-24</title>
</head>
<body>
    <?php
        $str = "あいうえお";
        $filename = "mission_1-24.txt";
        $fp = fopen($filename, "a");
        fwrite($fp, $str.PHP_EOL);
        fclose($fp);
       
        echo "書き込み成功！";
    ?>
</body>
</html>