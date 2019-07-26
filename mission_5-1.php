<?php
 $editname="";
 $editcomment="";
 $editpass="";
 $editNum="";
 ?>

<?php
 error_reporting(E_ALL & ~E_NOTICE);
 $dsn='データベース名';
 $user='ユーザ名';
 $password='パスワード';
  
 //データベースに接続
 $pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
 $sql="CREATE TABLE IF NOT EXISTS my_tb"
 ."("
 ."id INT AUTO_INCREMENT PRIMARY KEY,"
 ."name char(32),"
 ."comment TEXT,"
 ."data char(32),"
 ."pass char(32)"
 .");";
$stmt=$pdo->query($sql);

 //送信ボタンを押した時
 if(isset($_POST['buttom1'])){
     //もしも編集番号が入っていなかったら
   if(empty($_POST['editNum'])){
   //データを入力する
   $sql = $pdo -> prepare("INSERT INTO my_tb (name, comment,data,pass) VALUES (:name,:comment,:data,:pass)");
   $sql -> bindParam(':name', $name, PDO::PARAM_STR);
   $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
   $sql -> bindParam(':data', $data, PDO::PARAM_STR);
   $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
   $name=$_POST['name'];
   $comment=$_POST['comment'];
   $data=date("Y/m/d H:i:s");
   $pass=$_POST['pass'];
   $sql -> execute();
   //もしも編集番号が入っていたら
    }else{
        $edit=$_POST['editNum'];
        $edpass=$_POST['pass'];
        $name=$_POST['name'];
        $comment=$_POST['comment'];
        $pass=$_POST['pass'];
        $sql='SELECT*FROM my_tb';
        $stmt=$pdo->query($sql);
        $results=$stmt->fetchAll();
        foreach($results as $row){
            if(($row['pass']==$edpass) && ($edit==$row['id'])){
                $editname=$name;
                $editcomment=$comment;
                $editdate=date("Y-m-d H:i:s");
                $sql='update my_tb set name=:editname,comment=:editcomment,data=:editdate,pass=:pass where id=:edit';
                $stmt=$pdo->prepare($sql);
                $stmt->bindParam(':editname',$editname,PDO::PARAM_STR);
                $stmt->bindParam(':editcomment',$editcomment,PDO::PARAM_STR);
                $stmt->bindParam(':editdate',$editdate,PDO::PARAM_STR);
                $stmt->bindParam(':pass',$pass,PDO::PARAM_STR);
                $stmt->bindParam(':edit',$edit,PDO::PARAM_INT);
                $stmt->execute();
                 }
            }
        }
    }          

if(isset($_POST['buttom2'])){
    $deleted=$_POST['deleted'];
    $delpass=$_POST['delpass'];
    $sql='SELECT*FROM my_tb';
    $stmt=$pdo->query($sql);
    $results=$stmt->fetchAll();
    foreach($results as $row){
    if($row['pass']==$delpass && $row['id']==$deleted){
        $sql='delete from my_tb where id=:deleted';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':deleted',$deleted,PDO::PARAM_INT);
        $stmt->execute();
    }
  }
}

if(isset($_POST['buttom3'])){
    $edit=$_POST['edit'];
    $edpass=$_POST['edpass'];
    $sql='SELECT*FROM my_tb';
    $stmt=$pdo->query($sql);
    $results=$stmt->fetchAll();
    foreach($results as $row){
        if($row['pass']==$edpass && $edit==$row['id']){
            $editname=$row['name'];
            $editcomment=$row['comment'];
            $editpass=$row['pass'];
            $editNum=$edit;
    }
}
}
?>

 <!DOCTYPE html> 
 <html lang="ja">
 <head>
  <meta charset="utf-8">
  <title>投稿フォーム</title>
 </head>
 <body>
   <form action="mission_5-1.php" method="post">
   <p>名前:</p>
   <input type="text" name="name" value="<?php echo $editname; ?>"><br>
   <p>コメント:</p>
   <input type="text" name="comment" value="<?php echo $editcomment; ?>"><br>
    <p>パスワード:</p>
  <input type="text" name="pass" value="<?php echo $editpass; ?>"><br>
  <input type="submit" name="buttom1" value="送信"><br>
   <p>削除対象番号:</p>
   <input type="text" name="deleted"><br>
   <p>パスワード:</p>
   <input type="text" name="delpass"><br>
   <input type="submit" name="buttom2" value="削除"><br>
   <p>編集対象番号:</p>
   <input type="text" name="edit"><br>
    <p>パスワード:</p>
    <input type="text" name="edpass"><br>
   <input type="submit" name="buttom3" value="編集"><br> 
   <input type="hidden" name="editNum" value="<?php echo $editNum; ?>">
   </form>
 </body>
</html>
<?php

$sql='SELECT*FROM my_tb';               
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
    foreach($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['data'];
        echo "<hr>";
    }

    
?>