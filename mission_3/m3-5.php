<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
</head>
<body>    
    <?php
    
    /*はじめはファイルに書き込むなどの処理だけする。ブラウザに表示はしない。
    
    いきなり$_POSTが出てきますが、ボタンを押してPOST送信をした場合は、formを再度読み込むまでは$_POSTに値が保持されるようです。
    header()を使うとGET送信になるっぽいので$_POSTの中身は消えてしまいます。
    逆に、私がいろいろ試したかんじでは、多分$_SESSIONはPOST送信のとき（つまりボタンが押されたとき）は値を保持していないと思われます。
    なので、$_SESSIONにはheader()で読み込んだときのみ値を持ち越せているのだと思います。
    
    どちらでもできるのでどっちを使っても良いとは思いますが、ボタンを押す動作がpost送信なのにわざわざsessionでやると複雑になってしまうなと反省したので、今回は$_POSTを使って書きました。
    */
    
        $filename = "m3-5.txt";
        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        $formdata_arrays = [];
        foreach($lines as $line){
            $formdata_arrays[] = explode("<>", $line); //配列を$formdata_arraysに追加していく。二次元配列になる。
        }
        $count_lines = count($lines); //行数
        $error = "";     //この3つはエラー確認のための変数です。すみません変数の作り方が雑過ぎて少し変なのでわかりにくいかもです
        $error_num = "";
        $error_pass = "";
        
        if(!empty($_POST["submit"])){ //送信ボタンが押された時
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $date = date("Y/m/d H:i:s");
            $post_password = $_POST["post_password"];
            if(!empty($name) && !empty($comment) && !empty($post_password)){ //全部入力しているとき
                if(empty($_POST["ed_num"])){ //送信モード
                    $next_num = $count_lines + 1; //次の番号
                    $newdata = $next_num . "<>" . $name . "<>" . $comment . "<>" . $date . "<>" . $post_password . "<><br>";
                    $fp = fopen($filename, "a");
                    fwrite($fp, $newdata . PHP_EOL);
                    fclose($fp);
                }else{ //編集モード
                    $ed_num = $_POST["ed_num"];
                    $fp = fopen($filename, "w");
                    foreach($formdata_arrays as $formdata_array){
                        if($ed_num != $formdata_array[0]){// 編集番号と投稿番号が一致しない場合
                            $data = implode("<>", $formdata_array);
                            fwrite($fp, $data . "<>" . PHP_EOL);
                        }else{ //編集番号と投稿番号が一致する場合
                            $newdata = $ed_num . "<>" . $name . "<>" . $comment . "<>" . $date . "<>" . $post_password . "<><br>";
                            fwrite($fp, $newdata . PHP_EOL);
                            $ed_num = "";
                        }
                    }
                    fclose($fp);
                }    
            }else{//入力がたりないとき
                $error = "empty";
            }
            
            
        }elseif(!empty($_POST["del_button"])){ //削除ボタンが押された時
            $del_num = $_POST["delete"];
            $del_password = $_POST["del_password"];
            if(!empty($del_num) && !empty($del_password)){//過去の書き込みがあり、全て入力されているとき
                $fp = fopen($filename, "w");
                $wrote_nums = 0; //投稿番号を取得。書き込んだときだけ増やす。
                foreach($formdata_arrays as $formdata_array){
                    if($del_num != $formdata_array[0] | $del_password != $formdata_array[4]){//投稿番号が一致していないか、パスワードが違うとき
                        if($del_num == $formdata_array[0]){
                            $error_pass = "wrong_pass";
                        }
                        $formdata_array[0] = $wrote_nums + 1; //今までに書きこまれている行のうち最後の投稿番号に1を足す
                        $data = implode("<>", $formdata_array);
                        fwrite($fp, $data . PHP_EOL);
                        $wrote_nums++;
                    }
                }
                fclose($fp);
                if($del_num > $count_lines | $del_num < 0){
                    $error_num = "wrong_num";
                }
            }else{//入力がたりないとき
                $error = "empty";
            }
        }
        
        elseif(!empty($_POST["ed_button"])){//編集ボタンが押されたとき
            $ed_num = $_POST["edit"];
            $ed_password = $_POST["ed_password"];
            $ed_name = "";
            $ed_comment = "";
            $check = 0;
            
            if(!empty($ed_num) && !empty($ed_password)){ //全て入力があるとき
                foreach($formdata_arrays as $formdata_array){
                    if($ed_num == $formdata_array[0] && $ed_password == $formdata_array[4]){ 
                        $ed_name = $formdata_array[1];
                        $ed_comment = $formdata_array[2];
                        $check = 1; //編集する行の値を取得できたことを示す
                    }
                }
                if($check == 0){
                    if($ed_num > $count_lines | $ed_num < 0){ //編集する行が存在していないとき
                        $error_num = "wrong_num";
                    }else{
                        $error_pass = "wrong_pass";
                    }
                    $ed_num = "";
                    $ed_password = "";
                }
            }
            if(empty($_POST["edit"])){
                $error_num = "empty_num";
                $ed_num = "";
                $ed_password = "";
            }
            if(empty($_POST["ed_password"])){
                $error_pass = "empty_pass";
                $ed_num = "";
                $ed_password = "";
            }
        }
        
    ?>
    
    //ここからブラウザに表示。
    
    <h2>昨日の夜ごはん</h2>
    
    <form action="" method="post">
        <div>                       //本当はちゃんとCSSかテーブルとか使ってやったほうがいいはずですが面倒だったのでラベルのあとに空白を入れて揃えました、、
            <label for="name">名前　　　　</label>
            <input type="text" id="name" name="name" value="<?php if(!empty($ed_name)){echo $ed_name;}?>">
        </div>
        <div>
            <label for="comment">コメント　　</label>
            <input type="text" id="comment" name="comment" value="<?php if(!empty($ed_comment)){echo $ed_comment;}?>">
        </div>
        <div>
            <label for="post_password">パスワード　</label>
            <input type="text" id="post_password" name="post_password" value="<?php if(!empty($ed_password)){echo $ed_password;}?>">
        </div>
        <div>
            <input type="submit" name="submit">
        </div>
        <br>
        <div>
            <label for="delete">削除対象番号</label>
            <input type="number" id="delete" name="delete">
        </div>
        <div>
            <label for="del_password">パスワード　</label>
            <input type="text" id="del_password" name="del_password">
        </div>
        <div>
            <input type="submit" name="del_button" value="削除">
        </div>
        <br>
        <div>
            <label for="edit">編集対象番号</label>
            <input type="number" id="edit" name="edit">
        </div>
        <div>
            <label for="ed_password">パスワード　</label>
            <input type="text" id="ed_password" name="ed_password">
        </div>
        <div>
            <input type="submit" name="ed_button" value="編集">
        </div>
        <div>
            <input type="hidden" name="ed_num" value="<?php if(!empty($ed_num)){echo $ed_num;}?>">
        </div>
    </form>    
        
    <?php    
        echo "<br>";
        
        $lines = file($filename, FILE_IGNORE_NEW_LINES); //上のほうで書き換えたファイルを読み込む
        foreach($lines as $line){
            $formdata_array = explode("<>", $line); //配列を$formdata_arraysに追加していく。二次元配列になる。
            for($i=0; $i<4; $i++){  
                echo $formdata_array[$i] . "  ";
            }
            echo "<br>";
        }
        
        //エラーがあったら表示していく
        
        if(empty($lines)){
            echo "投稿がありません<br>";
        }
        echo "<br>";
        
        if(!empty($_POST["submit"]) && $error=="empty"){
            if(empty($name)){
                echo "名前 ";
            }
            if(empty($comment)){
                echo "コメント ";
            }
            if(empty($post_password)){
                echo "パスワード ";
            }
            echo "を入力してください<br>";
        }elseif(!empty($_POST["del_button"])){
            if($error_pass == "wrong_pass"){
                echo "パスワードが間違っています<br>";
            }elseif($error_num == "wrong_num"){
                echo "編集番号が間違っています<br>";
            }elseif($error == "empty"){
                if(empty($del_num)){
                    echo "削除対象番号 ";
                }
                if(empty($del_password)){
                    echo "パスワード ";
                }
                echo "を入力してください<br>";                
            }
        }elseif(!empty($_POST["ed_button"])){
            if($error_num == "wrong_num"){
                echo "編集番号が間違っています<br>";
            }elseif($error_pass == "wrong_pass"){
                echo "パスワードが間違っています<br>";
            }else{
                if($error_num == "empty_num"){
                    echo "編集対象番号 ";
                }
                if($error_pass == "empty_pass"){
                    echo "パスワード ";
                }
                if($error_num == "empty_num" | $error_pass == "empty_pass"){
                    echo "を入力してください<br>";
                }
            }
            
        }
        


        
        
        



    ?>
</body>
</html>
