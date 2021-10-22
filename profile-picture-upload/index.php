<?php

	require_once "connection.php";
	
	if(isset($_REQUEST['delete_id']))
	{
		// select image from db to delete
        //削除するデータベースから画像を選択します

		$id=$_REQUEST['delete_id'];	
		
		$select_stmt= $db->prepare('SELECT * FROM tbl_file WHERE id =:id');
		$select_stmt->bindParam(':id',$id);
		$select_stmt->execute();
		$row=$select_stmt->fetch(PDO::FETCH_ASSOC);
        //unlink function permanently remove your file
        //リンク解除機能はファイルを完全に削除します

		unlink("upload/".$row['image']);
		
		//delete an orignal record from db
        // dbから元のレコードを削除します

		$delete_stmt = $db->prepare('DELETE FROM tbl_file WHERE id =:id');
		$delete_stmt->bindParam(':id',$id);
		$delete_stmt->execute();
		
		header("Location:index.php");
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
			<div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           <h3><a href="add.php"><span class="glyphicon glyphicon-plus"></span>&nbsp; ファイルの追加</a></h3>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>名前</th>
                                            <th>ファイル</th>
                                            <th>編集</th>
                                            <th>削除</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$select_stmt=$db->prepare("SELECT * FROM tbl_file");	//sql select query
									$select_stmt->execute();
									while($row=$select_stmt->fetch(PDO::FETCH_ASSOC))
									{
									?>
                                        <tr>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><img src="upload/<?php echo $row['image']; ?>" width="100px" height="60px"></td>
                                            <td><a href="edit.php?update_id=<?php echo $row['id']; ?>" class="btn btn-warning">編集</a></td>
                                            <td><a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger">削除</a></td>
                                        </tr>
                                    <?php
									}
									?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
				
		</div>
		
	</div>
			
	</div>
									
	</body>
</html>