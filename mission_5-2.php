<?php
	$dsn = 'データベース名';	//4-1
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

	$sql = "CREATE TABLE IF NOT EXISTS tbtest1"	//4-2
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "time char(32),"
	. "pass char(32)"
	.");";
	$stmt = $pdo->query($sql);

	
	
	$delete="";  
	$name="";
	$comment=""; 
	$no = 0;
	if(isset($_POST['comment'])&&isset($_POST['name'])&&isset($_POST['passward'])){
		if($_POST['comment']=="" || $_POST['comment']=="コメント" ||$_POST['name']=="" || $_POST['name']=="名前"||empty($_POST['passward'])){
			echo "エラー";
		}else{	//編集用のコメントではないとき
			
			$sql = $pdo -> prepare("INSERT INTO tbtest1 (name, comment, time,pass) VALUES (:name, :comment, :time,:pass)");  //4-5
			$sql -> bindParam(':name', $name, PDO::PARAM_STR);
			$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$sql -> bindParam(':time',$time, PDO::PARAM_STR);
			$sql -> bindParam(':pass',$pass, PDO::PARAM_STR);
			$time = date("Y-m-d H:i:s");
			$name = $_POST['name'];
			$comment = $_POST['comment'];
			$pass = $_POST['passward'];
			$sql -> execute();
			
			
		
			
		}

	}elseif(isset($_POST['delete'])&&isset($_POST['passward'])){	//削除フォームに送信があったとき
		$sql = 'SELECT * FROM tbtest1';	//4-6
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
			$pass=$row['pass'];
		}
		if($_POST['passward']==$pass){
			$delete = $_POST['delete'];
			$id = $delete;	//4-8
			$sql = 'delete from tbtest1 where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
		}	

	}elseif(isset($_POST['edit'])&&isset($_POST['passward'])){		//編集する番号の入力があったとき
		if(!empty($_POST['edit'])){
		
			$edit = $_POST['edit'];
		}
	}elseif(isset($_POST['nameedit'])||isset($_POST['commentedit'])&&isset($_POST['passward'])){ //編集用のコメントになっているとき
		$sql = 'SELECT * FROM tbtest1';	//4-6
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
			$pass=$row['pass'];
		}
		if($_POST['passward']==$pass){
			$id = $_POST['editno']; //変更する投稿番号  4-7
			$name = $_POST['nameedit'];
			$comment = $_POST['commentedit'];
			$time = date("Y-m-d H:i:s");
			$sql = 'update tbtest1 set name=:name,comment=:comment,time=:time where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);	
			$stmt->bindParam(':time',$time, PDO::PARAM_STR);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

		}

	}	
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>5-1</title>
	<head>
	<body>
		<h1>DB掲示板</h1>
		<h4>パスワードは新規投稿で設定</h4>
		<h3>--------------------------------------------</h3>
		<form method="post" action="mission_5-1.php">
		<input type="text" name= "<?php if(!empty($edit)){echo "nameedit";}else{echo "name";} ?>"; placeholder="<?php if(!empty($edit)){$sql = 'SELECT * FROM tbtest1';	//4-6
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
	echo $row['name'].',';
	}}else{echo "名前";} ?>" >
		<br/>
		
		<input type="text" name="<?php if(!empty($edit)){echo "commentedit";}else{echo "comment";} ?>"placeholder="<?php if(!empty($edit)){$sql = 'SELECT * FROM tbtest1';	//4-6
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
	echo $row['comment'].' ';
	}}else{echo "コメント";} ?>" >
	
		<input type="hidden" name="<?php if(!empty($edit)){echo "editno";} ?>"value="<?php if(!empty($edit)){echo $edit;} ?>" >
		<br/>
		<input type="text" name="passward" placeholder = "password" >
		<br/>
		<input type="submit" value="送信">
		<br/>
		</form>
		<form method = "post" action = "mission_5-1.php">
		<input type="text" name="delete" placeholder = "No.?" >
		<br/>
		<input type="text" name="passward" placeholder = "password">
		<br/>
		<input type="submit" value="削除">
		
		</form>
		<form method = "post" action = "mission_5-1.php">
		<input type = "text" name= "edit" placeholder = "No.?">
		</br>
		<input type="text" name="passward" placeholder = "password">
		<br/>
		<input type = "submit" value = "編集">
		</form>
		</body>
	

</html>
<?php
	$sql = 'SELECT * FROM tbtest1';	//4-6
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].' ';
	echo $row['time'].'<br>';
	echo "<hr>";
	}
?>