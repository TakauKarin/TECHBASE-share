<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-3</title>
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
        <div>
            <label for="delete">削除対象番号</label>
            <input type="number" id="delete" name="delete">
        </div>
        <div>
            <input type="submit" name="del_button" value="削除">
        </div>
    </form>
    
    
    <?php
    
        //あまりうまく書けていないので参考程度にしてください
    
        $filename = "m3-3.txt";
        if(file_exists($filename)){ //反省：最初から空のテキストファイルを作っておけばわざわざこんな分岐をしなくてよかった。
            $nums = [];
            $lines = file($filename, FILE_IGNORE_NEW_LINES);
            $formdata_arrays = [];
            foreach($lines as $line){
                $formdata_array = explode("<>", $line);
                $nums[] = intval($formdata_array[0]); //$numsの中身はint型
                $formdata_arrays[] = $formdata_array; //$formdata_arraysは二次元配列
                if(empty($_POST["del_button"])){ //削除対象番号が入力されている場合は削除後のものを書くのでいらない
                    foreach($formdata_array as $formdata){
                        echo $formdata . " "; //テキストファイルの中身を表示
                    }
                    echo "<br>";
                }
                                        //反省：ここに削除機能をいれたほうが書くこと少なくて済む。
                $num = end($nums) + 1; 
            }
        }else{ //反省：最初にfile_exists()を使わないなら、if(empty($nums))とかで分岐できる
            $num = 1;
        }
        
        
        
        if(!empty($_POST["submit"])){ //送信ボタンが押された時の処理
            
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $date = date("Y/m/d H:i:s");
            $data = $num . "<>" . $name . "<>" . $comment . "<>" . $date;
            
            $fp = fopen($filename, "a");
            fwrite($fp, $data . PHP_EOL);
            fclose($fp);
            
            $new_formdata = $num . " " . $name . " " . $comment . " " . $date . "<br>";
            echo $new_formdata; //新しい投稿を表示
        }elseif(!empty($_POST["del_button"])){ //削除ボタンが押された時の処理
            $del_num = $_POST["delete"]; //int型
            if(file_exists($filename)){
                if(in_array($del_num, $nums)){
                    $fp = fopen($filename, "w");
                    foreach($formdata_arrays as $formdata_array){
                        intval($formdata_array[0]);
                        if($formdata_array[0] != $del_num){
                            if($formdata_array[0] > $del_num){
                                $formdata_array[0] = $formdata_array[0] - 1;
                            }
                            $data = implode("<>", $formdata_array);
                            fwrite($fp, $data . PHP_EOL);
                            foreach($formdata_array as $formdata){
                                echo $formdata . " "; 
                            }
                            echo "<br>";
                        }
                    }
                    fclose($fp);
                }else{
                    echo "<br>削除対象番号がありません<br>";
                }
            }else{
                echo "<br>まだ投稿されていません<br>";
            }
        }
        
        
        
    ?>
</body>
</html>
