<?php

require_once "connection.php";

if(isset($_REQUEST['btn_insert']))
{
	try
	{
		$name	= $_REQUEST['txt_name'];	//textbox name "txt_name"
			
		$image_file	= $_FILES["txt_file"]["name"];
		$type		= $_FILES["txt_file"]["type"];	//file name "txt_file"	
		$size		= $_FILES["txt_file"]["size"];
		$temp		= $_FILES["txt_file"]["tmp_name"];
		
		$path="upload/".$image_file; //set upload folder path //アップロードフォルダのパスを設定します
		
		if(empty($name)){
			$errorMsg="名前を入力してください";
		}
		else if(empty($image_file)){
			$errorMsg="画像を選択してください";
		}
		else if($type=="image/jpg" || $type=='image/jpeg' || $type=='image/png' || $type=='image/gif') //check file extension
		{	
			if(!file_exists($path)) //check file not exist in your upload folder path
			{
				if($size < 5000000) //check file size 5MB //ファイルサイズ5MBを確認します
				{
                    //move upload file temperory directory to your upload folder
                    //アップロードファイルの一時ディレクトリをアップロードフォルダに移動します
					move_uploaded_file($temp, "upload/" .$image_file); 
				}
				else //error message file size not large than 5MB //エラーメッセージファイルのサイズが5MB以下
				{
					$errorMsg="ファイルを大きくするには5MBのサイズをアップロードしてください"; 
				}
			}
			else //error message file not exists your upload folder path //エラーメッセージファイルがアップロードフォルダパスに存在しません
			{	
				$errorMsg="ファイルはすでに存在します...アップロードフォルダを確認してください"; 
			}
		}
		else //error message file extension  //エラーメッセージファイル拡張子
		{
			$errorMsg="JPG、JPEG、PNG、GIFファイル形式をアップロード.....ファイル拡張子を確認する"; 
		}
		
		if(!isset($errorMsg))
		{
			$insert_stmt=$db->prepare('INSERT INTO tbl_file(name,image) VALUES(:fname,:fimage)'); //sql insert query					
			$insert_stmt->bindParam(':fname',$name);	
			$insert_stmt->bindParam(':fimage',$image_file);	  //bind all parameter 
		
			if($insert_stmt->execute())
			{
                //クエリ成功メッセージを実行します
                //execute query success message
				$insertMsg="ファイルのアップロードに成功しました.......。";
                // 1秒更新し、index.phpページにリダイレクトします
                //refresh 1 second and redirect to index.php page
				header("refresh:1;index.php");
			}
		}
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
}

?>
<!-------------------------------------------- ADD PHP HTML---------------------------------------------------------->
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
<title>アップロード更新削除画像システム</title>
		
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<script src="js/jquery-1.12.4-jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
		
</head>

	<body>
	
	
	<nav class="navbar navbar-dark bg-warning">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="https://upcolor.weblike.jp/">Upcolor</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a herf= "">ホームページ</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	
	<div class="wrapper">
	
	<div class="container">
			
		<div class="col-lg-12">
		
		<?php
		if(isset($errorMsg))
		{
			?>
            <div class="alert alert-danger">
            	<strong>間違い ！ <?php echo $errorMsg; ?></strong>
            </div>
            <?php
		}
		if(isset($insertMsg)){
		?>
			<div class="alert alert-success">
				<strong>成功 ！ <?php echo $insertMsg; ?></strong>
			</div>
        <?php
		}
		?>   
		
			<form method="post" class="form-horizontal" enctype="multipart/form-data">
					
				<div class="form-group">
				<label class="col-sm-3 control-label">名前</label>
				<div class="col-sm-6">
				<input type="text" name="txt_name" class="form-control" placeholder="enter name" />
				</div>
				</div>
					
					
				<div class="form-group">
				<label class="col-sm-3 control-label">ファイル</label>
				<div class="col-sm-6">
				<input type="file" name="txt_file" class="form-control" />
				</div>
				</div>
					
					
				<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9 m-t-15">
				<input type="submit"  name="btn_insert" class="btn btn-success " value="確定">
				<a href="index.php" class="btn btn-danger">キャンセル</a>
				</div>
				</div>
					
			</form>
			
		</div>
		
	</div>
			
	</div>
										
	</body>
</html>