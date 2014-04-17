<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = ''; // Relative to the root

// $verifyToken = md5('unique_salt' . $_POST['timestamp']);

//&& $_POST['token'] == $verifyToken
if (!empty($_FILES) ) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'];
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	
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
        echo '1';
    } else {
        echo 'Invalid file type.';
    }
	
}
?>