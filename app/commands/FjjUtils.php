<?php

class FjjUtils{
	//创建文件目录
	public static function mkdirs($dir, $mode = 0777){   
		if (is_dir($dir) || @mkdir($dir, $mode,true)) return true;   
		return @mkdir($dir, $mode,true);  
	}
	
	//得到文件扩展名
	public static function get_extension($str)
	{
		return substr(strrchr($str, '.'), 1);
	}

	//图片缩放
	public static function resizeImage($im,$maxwidth,$maxheight,$name,$filetype)
	{
	    $pic_width = imagesx($im);
	    $pic_height = imagesy($im);

	    if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
	    {
	        if($maxwidth && $pic_width>$maxwidth)
	        {
	            $widthratio = $maxwidth/$pic_width;
	            $resizewidth_tag = true;
	        }

	        if($maxheight && $pic_height>$maxheight)
	        {
	            $heightratio = $maxheight/$pic_height;
	            $resizeheight_tag = true;
	        }

	        if($resizewidth_tag && $resizeheight_tag)
	        {
	            if($widthratio<$heightratio)
	                $ratio = $widthratio;
	            else
	                $ratio = $heightratio;
	        }

	        if($resizewidth_tag && !$resizeheight_tag)
	            $ratio = $widthratio;
	        if($resizeheight_tag && !$resizewidth_tag)
	            $ratio = $heightratio;

	        $newwidth = $pic_width * $ratio;
	        $newheight = $pic_height * $ratio;

	        if(function_exists("imagecopyresampled"))
	        {
	            $newim = imagecreatetruecolor($newwidth,$newheight);
	           imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
	        }
	        else
	        {
	            $newim = imagecreate($newwidth,$newheight);
	           imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
	        }

	        $name = $name.$filetype;
	        imagejpeg($newim,$name);
	        imagedestroy($newim);
	    }
	    else
	    {
	        $name = $name.$filetype;
	        imagejpeg($im,$name);
	    }           
	}

	// 下个星期的星期一  
	// @$timestamp ，某个星期的某一个时间戳，默认为当前时间  
	// @is_return_timestamp ,是否返回时间戳，否则返回时间格式  
	public static function next_monday($timestamp=0,$is_return_timestamp=true){  
	    static $cache ;  
	    $id = $timestamp.$is_return_timestamp;  
	    if(!isset($cache[$id])){  
	        if(!$timestamp) $timestamp = time();  
	        $thismonday = FjjUtils::this_monday($timestamp) + /*7*86400*/604800;  
	        if($is_return_timestamp){  
	            $cache[$id] = $thismonday;  
	        }else{  
	            $cache[$id] = date('Y-m-d',$thismonday);  
	        }   
	    }  
	    return $cache[$id];  
	} 

	// 这个星期的星期一  
	// @$timestamp ，某个星期的某一个时间戳，默认为当前时间  
	// @is_return_timestamp ,是否返回时间戳，否则返回时间格式  
	public static function this_monday($timestamp=0,$is_return_timestamp=true){  
	    static $cache ;  
	    $id = $timestamp.$is_return_timestamp;  
	    if(!isset($cache[$id])){  
	        if(!$timestamp) $timestamp = time();  
	        $monday_date = date('Y-m-d', $timestamp-86400*date('w',$timestamp)+(date('w',$timestamp)>0?86400:-/*6*86400*/518400));  
	        if($is_return_timestamp){  
	            $cache[$id] = strtotime($monday_date);  
	        }else{  
	            $cache[$id] = $monday_date;  
	        }  
	    }  
	    return $cache[$id];  
	}  

	// 计算两个日期的差 
	// @$starttime ，开始时间
	// @$endtime ，结束时间
	public static function diff_day($starttime,$endtime){  
	    static $cache ;  
	    $id = $starttime.$endtime;  
	    if(!isset($cache[$id])){  
	    	$t_start=strtotime($starttime);
	    	$t_end=strtotime($endtime);
	    	$cache[$id]=round(($t_end-$t_start)/3600/24);
	    }  
	    return $cache[$id];  
	}  
}
