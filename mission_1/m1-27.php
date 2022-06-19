<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_1-27</title>
    <form action="", method=post>
        <input type=number name="num">
        <input type=submit name="submit">
    </form>    
</head>
<body>
    <?php
        $num = $_POST["num"];
        $filename = "mission_1-27.txt";
        
        $fp = fopen($filename, "a");
        fwrite($fp, $num . PHP_EOL);
        fclose($fp);
        

        $numbers = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach($numbers as $number){
            $number = intval($number);
            if($number % 15 == 0){
                echo "FizzBuzz ";
            }elseif($number % 3 == 0){
                echo "Fizz ";
            }elseif($number % 5 == 0){
                echo "Buzz ";
            }else{
                echo $number . " ";
            }
        }
    ?>
</body>
</html>