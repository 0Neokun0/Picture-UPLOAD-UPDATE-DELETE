<?php
$db_host="localhost"; //localhost server //ローカルホストサーバー
$db_user="root";	//database username //データベースのユーザー名
$db_password="";	//database password //データベースパスワード
$db_name="db_fileupload";	//データベース名

try
{
	$db=new PDO("mysql:host={$db_host};dbname={$db_name}",$db_user,$db_password);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOEXCEPTION $e)
{
	$e->getMessage();
}

?>



