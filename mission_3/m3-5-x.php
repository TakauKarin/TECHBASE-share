<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
</head>
<body>
    
    //途中です
    
    <?php
        session_start();
        if($_SERVER["REQUEST_METHOD"] != "POST"){
            $_SESSION["ed_num"] = "";
            $_SESSION["ed_name"] = "";
            $_SESSION["ed_comment"] = "";
        }
    ?>
    
    <form action="" method="post">
        <div>
            <label for="name">名前</label>
            <input type="text" id="name" name="name" value="<?php if(!empty($_SESSION["ed_name"])){echo $_SESSION["ed_name"];}?>">
        </div>
        <div>
            <label for="comment">コメント</label>
            <input type="text" id="comment" name="comment" value="<?php if(!empty($_SESSION["ed_comment"])){echo $_SESSION["ed_comment"];}?>">
        </div>
        <div>
            <label for="post_password">パスワード</label>
            <input type="text" id="post_password" name="post_password">
        </div>
        <div>
            <input type="submit" name="submit">
        </div>
        <div>
            <label for="delete">削除対象番号</label>
            <input type="number" id="delete" name="delete">
        </div>
        <div>
            <label for="del_password">パスワード</label>
            <input type="text" id="del_password" name="del_password">
        </div>
        <div>
            <input type="submit" name="del_button" value="削除">
        </div>
        <div>
            <label for="edit">編集対象番号</label>
            <input type="number" id="edit" name="edit">
        </div>
        <div>
            <label for="ed_password">パスワード</label>
            <input type="text" id="ed_password" name="ed_password">
        </div>
        <div>
            <input type="submit" name="ed_button" value="編集">
        </div>
        <div>
            <input type="text" name="ed_num" value="<?php if(!empty($_SESSION["ed_num"])){echo $_SESSION["ed_num"];}?>">
        </div>
    </form>
    
    //途中です
    
    
    <?php
        $filename = "m3-5.txt";
        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        $count_lines = count($lines); //行数
        $wrote_nums = []; //投稿番号を取得。削除するときに参照。書き込んだときだけ増やす。
        $data_to_write = []; //ループしながらファイルに書き込むデータを入れていき、ループのあとに書き込む
        
        echo "<br>";
        
        if(!empty($_POST["submit"])){
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $date = date("Y/m/d H:i:s");
            $password = $_POST["post_password"];
            if(empty($name) | empty($comment) | empty($password)){ //どれかが入力されていないとき
                if(empty($name)){
                    echo "名前、";
                }
                if(empty($comment)){
                    echo "コメント、";
                }
                if(empty($password)){
                    echo "パスワード";
                }
                echo "を入力してください";
                exit; //処理終了.これでいいか検討
            }
        }
        
        
        if(empty($lines)){ //ファイルに過去の書き込みがないとき
            if(!empty($_POST["submit"])){ //送信ボタンを押された時
                $newdata = 1 . "<>" . $name . "<>" . $comment . "<>" . $date . "<>" . $password;
                $data_to_write[] = $newdata;
                echo 1 . " " . $name . " " . $comment . " " . $date . "<br>";
            }else{ //操作をしていないor削除ボタンor編集ボタン
                echo "投稿がありません" . "<br>";
            }
        }else{
            foreach($lines as $line){
                $formdata_array = explode("<>", $line);
                $formdata_array[0] = (int)$formdata_array[0];
                $num = $formdata_array[0]; //読み込んだ行の投稿番号
                
                if(empty($_POST["submit"]) && empty($_POST["del_button"])){ //操作をしていないとき
                    for($i=0; $i<4; $i++){
                        echo $formdata_array[$i] . " "; //読み込んでいる行を表示
                    }
                    echo "<br>";
                }
                
                if(isset($_POST["ed_button"]) && isset($_POST["edit"])){ //編集ボタンが押されたときの処理
                    $ed_num = $_POST["edit"];     //パスワードチェック　まだやってない
                    if($num == $ed_num){
                        $ed_name = $formdata_array[1];
                        $ed_comment = $formdata_array[2];
                    }
                }
                
                
                if(!empty($_POST["submit"])){ //送信ボタンが押された時
                    $next_num = $count_lines + 1; //次の番号
                    
                    $data = implode("<>", $formdata_array);
                    
                    if(empty($_POST["ed_num"])){  //送信モード
                        $data_to_write[] = $data;
                        for($i=0; $i<4; $i++){
                            echo $formdata_array[$i] . " ";
                        }
                        echo "<br>";
                        if($num == $count_lines){ //一番最後の行を読み込んでいる時
                            $newdata = $next_num . "<>" . $name . "<>" . $comment . "<>" . $date . "<>" . $password;
                            $data_to_write[] = $newdata;
                            echo $next_num . " " . $name . " " . $comment . " " . $date . "<br>"; //新規投稿を表示
                        }
                    }else{  //編集モード
                        $ed_num =  $_POST["ed_num"];
                        if($num != $ed_num){ //編集番号と投稿番号が違う場合
                            $data_to_write[] = $data;
                            for($i=0; $i<4; $i++){
                                echo $formdata_array[$i] . " ";
                            }
                            echo "<br>";
                        }else{ //編集番号と投稿番号が同じ場合
                            $newdata = $num . "<>" . $name . "<>" . $comment . "<>" . $date;
                            $data_to_write[] = $newdata;
                            $new_formdata = $num . " " . $name . " " . $comment . " " . $date;
                            echo $new_formdata . "<br>";
                        }
                    }
                }    
                
                if(!empty($_POST["del_button"])){ //削除ボタンが押されたときの処理
                    $del_num = $_POST["delete"];
                    $password = $_POST["password"];
                    if($num == $del_num && $formdata_array[4] != $password){
                        $formdata_array[0] = end($wrote_nums) + 1; //今までに書きこまれている行のうち最後の投稿番号に1を足す
                        $data = implode("<>", $formdata_array);
                        $data_to_write[] = $data;
                        $wrote_nums[] = $formdata_array[0];
                        for($i=0; $i<4; $i++){
                            echo $formdata_array[$i] . " ";
                        }
                        echo "<br>";
                        if($num == $del_num){
                            echo "<br>" . "パスワードが違います" . "<br>";
                        }
                    }
                }
        }
                
        }
        if(!empty($data_to_write)){
            $fp = fopen($filename, "w");
            foreach($data_to_write as $data){
                fwrite($fp, $data . PHP_EOL);
            }
            fclose($fp);
        }
        
        if(isset($ed_name)){
            $_SESSION["ed_num"] = $ed_num;
            $_SESSION["ed_name"] = $ed_name;
            $_SESSION["ed_comment"] = $ed_comment;
        }else{
            $_SESSION["ed_num"] = "";
            $_SESSION["ed_name"] = "";
            $_SESSION["ed_comment"] = "";
        }
        if(isset($_POST["ed_button"])){
            header("Location: " . $_SERVER['PHP_SELF']);
        }


    ?>
</body>
</html>
