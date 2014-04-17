<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
function CurrentFullPath()
{
    $root = $_SERVER['DOCUMENT_ROOT'] ;
    $self = $_SERVER['PHP_SELF'] ;
    return $root.substr($self,0,-strlen(strrchr($self,"/")));
}

 // Relative to the root
// $verifyToken = md5('unique_salt' . $_POST['timestamp']);

//&& $_POST['token'] == $verifyToken
if (!empty($_FILES) ) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath =  rtrim(CurrentFullPath(),'/').'/'.date('Ymd');
	$file = md5(uniqid(mt_rand(), true)).date('YmdHis').'.'.pathinfo($_FILES['Filedata']['name'])['extension'];
	$targetFile = rtrim($targetPath,'/').'/'.$file;
	if (!file_exists($targetPath))
	{
	  mkdir($targetPath);
	}
	// Validate the file type
	// move_uploaded_file($tempFile,$targetFile);
	// $fileTypes = array('jpg','jpeg','gif','png','doc','xdoc','avi','wmv'); // File extensions
	// $fileParts = pathinfo($_FILES['Filedata']['name']);
	// if (in_array($fileParts['extension'],$fileTypes)) {
	// 	move_uploaded_file($tempFile,$targetFile);
	// 	echo '1';
	// } else {
	// 	echo 'Invalid file type.';
	// }


	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
    $fileParts = pathinfo($_FILES['Filedata']['name']);
    if( in_array( strtolower( $fileParts['extension'] ), $fileTypes ) ) {
        move_uploaded_file($tempFile,$targetFile);
        echo 'uploads/'.date('Ymd').'/'.$file;
    } else {
        echo 'Invalid file type.';
    }
	
}
?>