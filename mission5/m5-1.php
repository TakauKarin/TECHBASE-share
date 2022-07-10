<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <?php
        $dsn = 'mysql:dbname=*****;host=localhost';
        $user = '*****';
        $password = '*****';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        
        $sql = "CREATE TABLE IF NOT EXISTS tbform"  //tbformというテーブルを作成
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date char(19),"
        . "password varchar(15)"
        .");";
        $stmt = $pdo->query($sql);
        
        
        $error_empty = 0;
        $error_wrong = 0;
        
        
        if(!empty($_POST["submit"])){ //送信ボタンが押された時
            if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["post_password"])){ //全部入力しているとき
                if(empty($_POST["ed_num"])){ //送信モード
                    $sql = $pdo -> prepare("INSERT INTO tbform (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                    $sql -> bindParam(':password', $pass, PDO::PARAM_STR); //データ型あとで調べる
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $date = date("Y/m/d H:i:s");
                    $pass = $_POST["post_password"];
                    $sql -> execute();
                }else{ //編集モード
                    $id = $_POST["ed_num"]; //変更する投稿番号
                    $name = $_POST["name"];
                    $comment = $_POST["comment"]; //変更したい名前、コメント
                    $date = date("Y/m/d H:i:s");
                    $pass = $_POST["post_password"];
                    $sql = 'UPDATE tbform SET name=:name,comment=:comment, date=:date, password=:password WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $pass, PDO::PARAM_STR); //データ型あとで調べる
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }else{
                $error_empty = 1;
            }
        }    
            
        if(!empty($_POST["del_button"])){ //削除ボタンが押された時
            $id = $_POST["del_num"];
            $pass = $_POST["del_password"];
            if(!empty($pass) && !empty($id)){//全て入力されているとき
                $sql = 'delete from tbform where id=:id AND password=:password';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':password', $pass, PDO::PARAM_STR); //データ型あとで調べる
                $result = $stmt->fetch();
                $stmt->execute();
                if(!$result){
                    $error_wrong = 1;
                }
            }else{
                $error_empty = 1;
            }
        }
            
        
        if(!empty($_POST["ed_button"])){//編集ボタンが押されたとき
            $id = $_POST["edit"];
            $pass = $_POST["ed_password"];
            
            if(!empty($id) && !empty($pass)){ //全て入力があるとき
                $sql = 'SELECT * FROM tbform WHERE id=:id AND password=:password';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':password', $pass, PDO::PARAM_STR); //データ型調べる
                $stmt->execute();
                $result = $stmt->fetch();
                if($result){
                    $ed_name = $result["name"];
                    $ed_comment = $result["comment"];
                    $ed_password = $result["password"];
                    $ed_num = $result["id"];
                    
                }else{
                    $error_wrong = 1;
                }
                
            }else{
                $error_empty = 1;
            }
        }
    
    ?>
    
    <form action="" method="post">
        <div>
            <label for="name">名前　　　　</label>
            <input type="text" id="name" name="name" value="<?php if(!empty($ed_name)){echo $ed_name;}?>">
        </div>
        <div>
            <label for="comment">コメント　　</label>
            <input type="text" id="comment" name="comment" value="<?php if(!empty($ed_comment)){echo $ed_comment;}?>">
        </div>
        <div>
            <label for="post_password">パスワード　</label>
            <input type="password" id="post_password" name="post_password" value="<?php if(!empty($ed_password)){echo $ed_password;}?>">
        </div>
        <div>
            <input type="submit" name="submit">
        </div>
        <br>
        <div>
            <label for="del_num">削除対象番号</label>
            <input type="number" id="del_num" name="del_num">
        </div>
        <div>
            <label for="del_password">パスワード　</label>
            <input type="password" id="del_password" name="del_password">
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
            <input type="password" id="ed_password" name="ed_password">
        </div>
        <div>
            <input type="submit" name="ed_button" value="編集">
        </div>
        <div>
            <input type="text" name="ed_num" value="<?php if(!empty($ed_num)){echo $ed_num;}?>">
        </div>
    </form>
    
    <?php
        $sql = 'SELECT * FROM tbform';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
            echo "<hr>";
        }
        
        if($error_empty == 1){
            echo "全て入力してください";
        }
        if($error_wrong == 1){
            echo "入力が間違っています";
        }
    ?>
</body>
</html>