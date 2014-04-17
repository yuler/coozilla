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

if ($_POST['file']) {
	unlink(CurrentFullPath().'/'.'../'.$_POST['file']);
}

?>