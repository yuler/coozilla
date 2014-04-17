<?php
// $error = ""; //上传文件出错信息
// $msg = "";
// $fileElementName = 'picture';
//     $allowType = array(".jpg",".gif",".png"); //允许上传的文件类型
//     $num      = strrpos($_FILES['picture']['name'] ,'.');  
// $fileSuffixName    = substr($_FILES['picture']['name'],$num,8);//此数可变  
// $fileSuffixName    = strtolower($fileSuffixName); //确定上传文件的类型
    
// $upFilePath             = 'd:/'; //最终存放路径

// if(!empty($_FILES[$fileElementName]['error']))
// {
//    switch($_FILES[$fileElementName]['error'])
//    {

//     case '1':
//      $error = '传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值';
//      break;
//     case '2':
//      $error = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
//      break;
//     case '3':
//      $error = '文件只有部分被上传';
//      break;
//     case '4':
//      $error = '没有文件被上传';
//      break;

//     case '6':
//      $error = '找不到临时文件夹';
//      break;
//     case '7':
//      $error = '文件写入失败';
//      break;
//     default:
//      $error = '未知错误';
//    }
// }elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none')
// {
//    $error = '没有上传文件.';
// }else if(!in_array($fileSuffixName,$allowType))
// {
//    $error = '不允许上传的文件类型'; 
// }else{
//   $ok=@move_uploaded_file($_FILES['fileToUpload']['tmp_name'],$upFilePath);
//    if($ok === FALSE){
//     $error = '上传失败';
//    }
// }
function CurrentFullPath()
{
    $root = $_SERVER['DOCUMENT_ROOT'] ;
    $self = $_SERVER['PHP_SELF'] ;
    return $root.substr($self,0,-strlen(strrchr($self,"/")));
}

if (!empty($_FILES)) {
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

    }
}

?>