<?php

require_once "connection.php";

if(isset($_REQUEST['update_id']))
{
	try
	{
        //get "update_id" from index.php page through anchor tag operation and store in "$id" variable
        //アンカータグ操作を介してindex.phpページから「update_id」を取得し、「$ id」変数に格納します
		$id = $_REQUEST['update_id']; 
		$select_stmt = $db->prepare('SELECT * FROM tbl_file WHERE id =:id'); //sql select query
		$select_stmt->bindParam(':id',$id);
		$select_stmt->execute(); 
		$row = $select_stmt->fetch(PDO::FETCH_ASSOC);
		extract($row);
	}
	catch(PDOException $e)
	{
		$e->getMessage();
	}
	
}

if(isset($_REQUEST['btn_update']))
{
	try
	{
		$name	=$_REQUEST['txt_name'];	//テキストボックス名 "txt_name"
		
		$image_file	= $_FILES["txt_file"]["name"];
		$type		= $_FILES["txt_file"]["type"];	//ファイル名 "txt_file"
		$size		= $_FILES["txt_file"]["size"];
		$temp		= $_FILES["txt_file"]["tmp_name"];
            //set upload folder path
			//アップロードフォルダのパスを設定します
		$path="upload/".$image_file; 

        //set upload folder path for update time previous file remove and new file upload for next use
		//アップロードフォルダのパスを更新時間の前のファイルの削除と次の使用のための新しいファイルのアップロードに設定します

		$directory="upload/"; 
		
		if($image_file)
		{
            //ファイル拡張子を確認します
			if($type=="image/jpg" || $type=='image/jpeg' || $type=='image/png' || $type=='image/gif') 
			{	
                //check file not exist in your upload folder path
                //アップロードフォルダのパスにファイルが存在しないことを確認します
				if(!file_exists($path)) 
				{
					if($size < 5000000) // ファイルサイズ5MBを確認します
					{
                        //リンク解除機能は前のファイルを削除します
                        //unlink function remove previous file
						unlink($directory.$row['image']); 
                        	//move upload file temperory directory to your upload folder
                            //アップロードファイルの一時ディレクトリをアップロードフォルダに移動します
						move_uploaded_file($temp, "upload/" .$image_file);
					}
					else //エラーメッセージファイルのサイズが5MB以下
					{
						$errorMsg="ファイルを大きくするには5MBのサイズをアップロードしてください"; 
					}
				}
				else //エラーメッセージファイルがアップロードフォルダパスに存在しません
				{	
					$errorMsg="ファイルはすでに存在します...アップロードフォルダを確認してください"; 
				}
			}
			else //エラーメッセージファイル拡張子
			{
				$errorMsg="JPG、JPEG、PNG、GIFファイル形式をアップロード.....ファイル拡張子を確認する"; 
			}
		}
		else //前の画像よりも新しい画像を選択しない場合はそれです。
		{
			$image_file=$row['image']; 
		}
	
		if(!isset($errorMsg))
		{
			$update_stmt=$db->prepare('UPDATE tbl_file SET name=:name_up, image=:file_up WHERE id=:id'); //sql update query
			$update_stmt->bindParam(':name_up',$name);
			$update_stmt->bindParam(':file_up',$image_file);	//bind all parameter //すべてのパラメータをバインドします
			$update_stmt->bindParam(':id',$id);
			 
			if($update_stmt->execute())
			{
				$updateMsg="ファイルの更新に成功しました......。 ";	//file update success message
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
	
	
	<nav class="navbar navbar-light bg-warning">
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
		if(isset($updateMsg)){
		?>
			<div class="alert alert-success">
				<strong>アップデート ！ <?php echo $updateMsg; ?></strong>
			</div>
        <?php
		}
		?>   
		
			<form method="post" class="form-horizontal" enctype="multipart/form-data">
					
				<div class="form-group">
				<label class="col-sm-3 control-label">名前</label>
				<div class="col-sm-6">
				<input type="text" name="txt_name" class="form-control" value="<?php echo $name; ?>" required/>
				</div>
				</div>
					
					
				<div class="form-group">
				<label class="col-sm-3 control-label">ファイル</label>
				<div class="col-sm-6">
				<input type="file" name="txt_file" class="form-control" value="<?php echo $image; ?>"/>
				<p><img src="upload/<?php echo $image; ?>" height="100" width="100" /></p>
				</div>
				</div>
					
					
				<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9 m-t-15">
				<input type="submit"  name="btn_update" class="btn btn-primary" value="Update">
				<a href="index.php" class="btn btn-danger">キャンセル</a>
				</div>
				</div>
					
			</form>
			
		</div>
		
	</div>
			
	</div>
										
	</body>
</html>