<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-4</title>
</head>
<body>
    <?php session_start(); ?>
    
    <form action="" method="post">
        <div>
            <label for="name">名前</label>
            <input type="text" id="name" name="name" value="<?php if(isset($_SESSION["ed_name"])){echo $_SESSION["ed_name"];}?>">
        </div>
        <div>
            <label for="comment">コメント</label>
            <input type="text" id="comment" name="comment" value="<?php if(isset($_SESSION["ed_comment"])){echo $_SESSION["ed_comment"];}?>">
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
        <div>
            <label for="edit">編集対象番号</label>
            <input type="number" id="edit" name="edit">
        </div>
        <div>
            <input type="submit" name="ed_button" value="編集">
        </div>
        <div>
            <input type="hidden" name="ed_num" value="<?php if(isset($_SESSION["ed_num"])){echo $_SESSION["ed_num"];}?>">
        </div>
    </form>
    <?php
        $filename = "m3-4.txt";
        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        $count_lines = count($lines); //行数
        $data_to_write = []; //ループしながらファイルに書き込むデータを入れていき、ループのあとに書き込む
        foreach($lines as $line){
            $formdata_array = explode("<>", $line);
            $num = intval($formdata_array[0]); //読み込んだ行の投稿番号
            
            
            if(empty($_POST["submit"]) && empty($_POST["del_button"])){ //操作をしていないとき
                foreach($formdata_array as $formdata){
                    echo $formdata . " "; //テキストファイルの中身を表示
                }
                echo "<br>";
            }
            
            if(isset($_POST["ed_button"]) && isset($_POST["edit"])){ //編集ボタンが押されたときの処理
                $ed_num = $_POST["edit"];
                if($num == $ed_num){
                    $ed_name = $formdata_array[1];
                    $ed_comment = $formdata_array[2];
                }
            }
            
            
            if(!empty($_POST["submit"])){ //送信ボタンが押された時
                if(!empty($count_lines)){
                    $next_num = $count_lines + 1; //次の番号
                }else{
                    $next_num = 1;
                }
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $date = date("Y/m/d H:i:s");
                $data = implode("<>", $formdata_array);
                
                if(empty($_POST["ed_num"])){  //送信モード
                    $data_to_write[] = $data;
                    foreach($formdata_array as $formdata){
                        echo $formdata . " ";
                    }
                    echo "<br>";
                    if($num == $count_lines){ //一番最後の行を読み込んでいる時
                        $newdata = $next_num . "<>" . $name . "<>" . $comment . "<>" . $date;
                        $data_to_write[] = $newdata;
                        echo $next_num . " " . $name . " " . $comment . " " . $date . "<br>";
                    }
                }else{  //編集モード
                    if($num != $_POST["ed_num"]){ //編集番号と投稿番号が違う場合
                        $data_to_write[] = $data;
                        foreach($formdata_array as $formdata){
                            echo $formdata . " ";
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
                if($num != $del_num){
                    if($num > $del_num){
                        $formdata_array[0] = $num - 1;
                    }
                    $data = implode("<>", $formdata_array);
                    $data_to_write[] = $data;
                    foreach($formdata_array as $formdata){
                        echo $formdata . " ";
                    }
                    echo "<br>";
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
        
        if(isset($ed_name)){ //編集ボタンが押され、かつ名前やコメントを正常に取得できているとき
            $_SESSION["ed_num"] = $ed_num; //値を保持
            $_SESSION["ed_name"] = $ed_name;
            $_SESSION["ed_comment"] = $ed_comment;
        }else{ //編集ボタン以外を操作したとき
            $_SESSION["ed_num"] = "";
            $_SESSION["ed_name"] = "";
            $_SESSION["ed_comment"] = "";
        }
        if(isset($_POST["ed_button"])){ //編集ボタンが押されたときは、処理を終えたあとにブラウザを更新する
            header("Location: " . $_SERVER['PHP_SELF']);
        }


    ?>
</body>
</html>
