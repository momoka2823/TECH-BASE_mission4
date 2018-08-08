<head>
  <meta charset="utf-8">
</head>
<body>

<?php

$Editflag = '';
$Delete = '';
//格納-------------------------------------------------------------------
if(isset($_POST['comment'])&&isset($_POST['name'])&&isset($_POST['password'])){
  $Comment = $_POST['comment'];
  $Name = $_POST['name'];
  $Password = $_POST['password'];
}

if(isset($_POST['delete'])){
  $Delete = $_POST['delete'];
  $Deletepass = $_POST['deletepass'];
}

if(isset($_POST['edit'])){
  $Edit = $_POST['edit'];
  $Editpass = $_POST['editpass'];
}

if(isset($_POST['editflag'])){
  $Editflag = $_POST['editflag'];
}
//データベース接続--------------------------------------------------------------
$dsn = 'データベース名';
//new PDO('mysql:host=localhost;dbname=test', $user, $pass)
$user = 'ユーザー名';
$pass = 'パスワード';
$pdo = new PDO($dsn,$user,$pass);

//テーブル削除-----------------------------------------------------------------
// $sql= "DROP TABLE mission4";
// $stmt = $pdo->query($sql);
// exit;

//テーブル作成-----------------------------------------------------------------
$sql= "CREATE TABLE mission4"
." ("
. "id INT,"
. "name char(32),"
. "comment TEXT,"
. "password TEXT,"
. "jikan TEXT"
.");";
$stmt = $pdo->query($sql);

//データの数を取得------------------------------------------------------------
$sql = 'SELECT * FROM mission4 order by id desc limit 1';
$stmt = $pdo->query($sql);
$count = $stmt->fetchColumn();

//データを入力----------------------------------------------------------------
//https://qiita.com/tabo_purify/items/0a69fd48018c4ebfd2f2 : prepare()
if(($Comment && $Name && $Password)&&(!$Editflag)){
  $sql = $pdo -> prepare("INSERT INTO mission4 (id, name, comment, password, jikan) VALUES (:id, :name, :comment, :password, :jikan)");
  $sql -> bindParam(':id', $id, PDO::PARAM_STR);
  $sql -> bindParam(':name', $Name, PDO::PARAM_STR);
  $sql -> bindParam(':comment', $Comment, PDO::PARAM_STR);
  $sql -> bindParam(':password', $Password, PDO::PARAM_STR);
  $sql -> bindParam(':jikan', $Time, PDO::PARAM_STR);
  $id = $count + 1;
  $Time = new DateTime();
  $Time = $Time -> format('Y年m月d日 H時i分s秒');
  $sql -> execute();//準備したprepareに入っているSQL分を実行
}

//データの削除(削除のフォームに番号入力→削除)--------------------------------------
if($Delete){
  $sql = "SELECT * FROM mission4 where id=$Delete";
  $result = $pdo->query($sql);
  foreach ($result as $row){
    $Dpasu = $row['password'];
  }
  if($Dpasu == $Deletepass){
    $sql = "DELETE FROM mission4 where id=$Delete";
    $result = $pdo->query($sql);
  }
}

//編集モード---------------------------------------------------------------------
if($Edit){
  $sql = "SELECT * FROM mission4 where id=$Edit";
  $result = $pdo->query($sql);
  foreach ($result as $row){
    $Epasu = $row['password'];
  }
  if($Epasu == $Editpass){
    $sql = "SELECT * FROM mission4 where id=$Edit";
    $result = $pdo->query($sql);
    foreach ($result as $row){
      $editname = $row['name'];
      $editcomment = $row['comment'];
      $Editflag = $row['id'];
    }
    $editname = $row['name'];
    $editcomment = $row['comment'];
    $editpassword = $row['password'];
  }
}

//データの編集------------------------------------------------------------------
else if($Editflag){
  $nm = $_POST['name'];
  $kome = $_POST['comment'];
  $pasu = $_POST['password'];
  $jikan = new DateTime();
  $jikan = $jikan -> format('Y年m月d日 H時i分s秒');
  $sql = "update mission4 set name='$nm' , comment='$kome', password='$pasu', jikan ='$jikan' where id = $Editflag";
  $result = $pdo->query($sql);
  $Editflag = '';
}

//フォーム-----------------------------------------------------------------------------
?>
<form action = "" method = "post">
  　　　名前：<input type = "text" name ="name" value = "<?php echo $editname; ?>"><br>
  　コメント：<input type = "text" name ="comment" value = "<?php echo $editcomment; ?>"><br>
  パスワード：<input type "text" name = "password" value = "<?php echo $editpassword; ?>">
  　　　　　　<input type = "hidden" name ="editflag" value = "<?php echo $Editflag; ?>"><br>
  <input type = "submit" value ="送信"><br>
</form>

<form action = "" method = "post">
  　削除番号：<input type = "text" name ="delete"><br>
  パスワード：<input type = "text" name = "deletepass"><br>
  <input type = "submit" value ="削除"><br>
</form>

<form action = "" method = "post">
  　編集番号：<input type = "text" name ="edit"><br>
  パスワード：<input type = "text" name = "editpass"><br>
  <input type = "submit" value ="編集"><br>
</form>
<?php

//入力したデータをselectによって表示する-------------------------------------------
$sql = 'SELECT * FROM mission4';
$results = $pdo -> query($sql);
foreach ($results as $row){
  echo $row['id'].',';
  echo $row['name'].',';
  echo $row['comment'].',';
  echo $row['jikan'].'<br>';
 }

/*まとめ-------------------------------------------------------------------------
https://www.dbonline.jp/mysql/type/index4.html：時間のデータ型
https://teratail.com/questions/15881：現在時刻の取得
-----------------------------------------------------------------------------*/
?>
</body>
