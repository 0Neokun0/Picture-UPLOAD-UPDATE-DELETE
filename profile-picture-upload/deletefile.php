<?php

require_once "connection.php";

if(isset($_REQUEST['delete_id']))
{
    //select image from Database to delete
    //データベースから画像を選択して削除します

    $id = $_REQUEST['delete']; // get delete_id and sote in $id variable

    $select_stmt = $db->prepare('SELECT * FROM tbl_file WHERE id = :id');

    $select_stmt->bindParam(':id', $id);
    $select_stmt->execute();
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
    // unlink fuction will permanently remove the file
    //リンク解除機能はファイルを完全に削除します
    unlink("upload/" .$row['image']);

    // delete the orginal record from the batabase
    //データベースから元のレコードを削除します

    $delete_stmt = $db->prepare('DELETE * FROM tbl_file WHERE id = :id');

    $select_stmt->bindParam('id', $id);
    $delete_stmt->execute();

    header("Location:index.php");
}

?>