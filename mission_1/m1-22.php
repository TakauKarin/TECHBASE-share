<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_1-22</title>
</head>
<body>
    <?php
        $vegetables = array("キャベツ","レタス","ハクサイ","ホウレンソウ","コマツナ");
        for($i=0; $i<count($vegetables); $i++){
            echo $vegetables[$i] . "<br>";
        }
    ?>
</body>
</html>