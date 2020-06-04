<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Почтовые отделения</title>
</head>
<?php
$data = null;
$status = "";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$dbuser = 'postgres';
    $dbpassword = '1234';
    $host = 'localhost';
    $dbname = 'lab3';
	 $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser , $dbpassword );
    if(isset($_POST["find"])) {
        $sql = 'SELECT * from public."Postoffice" where "ID_Post" = :ID_Post';
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':ID_Post' => $_POST["ID_Post"]));
        $data = $sth->fetchAll();
        if(count($data) > 0){
            $status = "Результат:";
        }else{
            $status = "Информации нет.";
        }
    }
	elseif(isset($_POST["edit"])) {
		if($_POST["ID_Post"] != ""){
			$sql = 'SELECT * from public."Postoffice" where "ID_Post" = :ID_Post';
			$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':ID_Post' => intval($_POST["ID_Post"])));
			$data = $sth->fetchAll();
		}
		elseif($_POST["ID_Post"] != "" && count($data) > 0){
			$sql = 'UPDATE public."Postoffice" SET "Branch_number"= :Branch_number, "Post_adress"= :Post_adress where "ID_Post" = :ID_Post';
			$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':ID_Post' => $_POST["ID_Post"],':Branch_number' => $_POST["Branch_number"],':Post_adress' => $_POST["Post_adress"]));
			$data = $sth->fetchAll();
			$status = "Изменено";
			$data = null;
		}
    }
	elseif(isset($_POST["insert"])) {
		$sql = 'INSERT INTO public."Postoffice"("Branch_number", "Post_adress") VALUES (:Branch_number, :Post_adress)';
		$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':Branch_number' => $_POST["Branch_number"],':Post_adress' => $_POST["Post_adress"]));
		$data = $sth->fetchAll();
		 $status = "Добавлено";
		 $data = null;
	}
	elseif(isset($_POST["delete"])) {
        $sql = 'delete from public."Postoffice" where "ID_Post" = :ID_Post';
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':ID_Post' => $_POST["ID_Post"]));
        $data = $sth->fetchAll();
        $status = "Удалено.";
        $data = null;  
	}
}
?>
<body>
<form action="" method="post">
	Введите ID </br>
    <input name="ID_Post" value="<?php echo '' ?>"> </br>
    <button type="submit" name="find">Поиск</button>
    <button type="submit" name="delete">Удаление</button>
</form>
<?php echo $status ?>
</br>
<form action="" method="post">
	 Идентификатор почты</br>
    <input name="ID_Post" size="30" value="<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && $data) echo $data[0]['ID_Post']?>">
	 <br>Номер отделения</br>
    <input name="Branch_number" size="30" value="<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && $data) echo $data[0]['Branch_number']?>">
	 <br>Адрес отделения</br>
    <input name="Post_adress" size="30" value="<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && $data) echo $data[0]['Post_adress']?>"></br>
    <button type="submit" name="insert">Добавление</button>
	<button type="submit" name="edit">Редактирование</button>
	<br><a href="index.php">"Назад"</a><br/>
</form>
</body>