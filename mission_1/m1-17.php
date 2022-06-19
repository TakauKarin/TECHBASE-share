<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_1-17</title>
</head>
<body>
    <?php
        $num = 4;
        if ($num % 15 == 0){
            echo "FizzBuzz";
        }elseif($num % 3 == 0){
            echo "Fizz";
        }elseif($num % 5 == 0){
            echo "Buzz";
        }else{
            echo $num;
        }
    ?>
</body>
</html>