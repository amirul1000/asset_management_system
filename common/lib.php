<?php

 function getEnumFieldValues($tableName = null, $field = null)
   {
       // Make a DDL query
       $query = "SHOW COLUMNS FROM $tableName LIKE " . q($field);

       $result = mysql_query($query);
       $data   = mysql_fetch_array($result);

       if(eregi("('.*')", $data['Type'], $match))
       {
          $enumStr       = ereg_replace("'", '', $match[1]);
          $enumValueList = explode(',', $enumStr);
       }

       return $enumValueList;
   }
   function  getTableFieldsName($table)
{
    $sql          =  "select * from  ".$table."";
    $res          =  mysql_query($sql);
    $fields       =  mysql_num_fields($res);
    // Setup an array to store return info
      $hash = array();

      for ($i=0; $i < $fields; $i++)
      {
         $name          =  mysql_field_name($res, $i);
         $hash[$name]   = $name ;
      }
    return $hash;
}


function q($str = null)
	   {
		  return "'" . mysql_escape_string($str) . "'";
	   }

function getCategoryName($db)
{

       $info['table']    = "category";
	   $info['fields']   = array("*");   	   
	   $info['where']    =  "1";
	   
	   $res  =  $db->select($info);
	   
	  return $res;
}


function getBrandName($db,$category_id=null)
{
       $info['table']    = "brand";
	   $info['fields']   = array("*");   	   
	   $info['where']    =  "1";
	   if($category_id!=null)
	   {
	    $info['where']    = $info['where'] ." AND category_id=$category_id";
	   }
	   
	   $res  =  $db->select($info);
	   
	  return $res;
}


function getSearchKey($db)
{
       $info['table']    = "search_key";
	   $info['fields']   = array("*");   	   
	   $info['where']    =  "1";
	   
	   $res  =  $db->select($info);
	   
	  return $res;


}

function getSearchListFromRequest($_request)
{
   $str = "";
 
   foreach($_REQUEST as $key=>$value)
   {
      if(substr($key,0,strlen("checkbox_")) =="checkbox_")
	  { 
	    $str = $str.$value.",";
	  }
   }
   
   $str = substr($str,0,strlen($str)-1);
   return $str;
}


function uploadImage($db,$_files,$_request)
{

   $extention= explode(".",$_FILES['image_file']['name']);
   
   if($_FILES['image_file']['size']>0 && ($extention[1]=="jpeg"|| $extention[1]=="jpg"|| $extention[1]=="bmp"|| $extention[1]=="png"|| $extention[1]=="gif"))
   { 
	   $category_id = $_REQUEST['category_id'];      	   
	   $brand_id    = $_REQUEST['brand_id']; 
	   $model       = strtolower(str_replace(" ","_",$_REQUEST['model'])); 
	   
	   
	   
	   $info['table']    = "category";
	   $info['fields']   = array("*");   	   
	   $info['where']    =  " id='$category_id'";
	   $res  =  $db->select($info);
   	   $categoryName = strtolower( str_replace(" ","_",$res[0]['category_name']));
	   
	   $info['table']    = "brand";
	   $info['fields']   = array("*");   	   
	   $info['where']    = " id='$brand_id'";
	   $res  =  $db->select($info);
	   $brandName   =  strtolower( str_replace(" ","_",$res[0]['brand_name']));
	 
	   makeDir("../../phone",$categoryName);
	   makeDir("../../phone/$categoryName",$brandName);
	   makeDir("../../phone/$categoryName/$brandName",$model) ;
	   $file  = "../../phone/$categoryName/$brandName/$model/$model.".$extention[1];
	   move_uploaded_file($_FILES['image_file']['tmp_name'],$file);
	   $image  = $file;
	   if ($image != "" && file_exists($image))
		  {
			if(file_exists($image.".thumb.jpg"))
			{
				unlink($image.".thumb.jpg");
			}
			$dateiendung = strrchr($image, '.' );
			$dateiname = substr_replace ($image, '', -strlen($dateiendung));
			createthumb($dateiname, $dateiendung);
			chmod($image, 0666);
			
			
			if(file_exists($image.".small.jpg"))
			{
				unlink($image.".small.jpg");
			}
			createSmall($dateiname, $dateiendung);
			chmod($image, 0666);
			
			
			unlink($image);
		  }
	   
	 
   }
}
function makeFile($db,$_files,$_request)
{
       $category_id = $_REQUEST['category_id'];      	   
	   $brand_id    = $_REQUEST['brand_id']; 
	   $model       = strtolower( str_replace(" ","_",$_REQUEST['model'])); 
	   
	   
	   
	   $info['table']    = "category";
	   $info['fields']   = array("*");   	   
	   $info['where']    =  " id='$category_id'";
	   $res  =  $db->select($info);
   	   $categoryName = strtolower( str_replace(" ","_",$res[0]['category_name']));
	   
	   $info['table']    = "brand";
	   $info['fields']   = array("*");   	   
	   $info['where']    = " id='$brand_id'";
	   $res  =  $db->select($info);
	   $brandName   =  strtolower( str_replace(" ","_",$res[0]['brand_name']));
	  
	 
	 
	   makeDir("../../phone",$categoryName);
	   makeDir("../../phone/$categoryName",$brandName);
	   makeDir("../../phone/$categoryName/$brandName",$model) ;
	 
	 
	 
	   $file  = "../../phone/$categoryName/$brandName/$model/$model".".php";
	   
	   
	   if(file_exists($file))
	   {
	         unlink($file);     
	   }
	   
	   
	   $hfile = fopen($file,w);
	   
	   if($hfile)
	   {
	        fwrite($hfile,getContentsPhone($db,$_REQUEST));
	   }
	   
	   fclose($hfile);
	   

}

function makeDir($path,$dirName)
{ 
       $path = $path."/$dirName";
	   if(!is_dir($path)&&! file_exists($path))
	          mkdir($path,0777);
}

function getContentsPhone($db,$_request)
{     
       $extention= explode(".",$_FILES['image_file']['name']);
	   
	   $contents  = file_get_contents("../../phone_content/phone_model.php");
	   $contents  = str_replace('CSS','<link rel="stylesheet" href="../../../../css/style.css" >',$contents); 
       $contents  = str_replace('HEADER','<?php include("../../../../phone_content/header.php")?>',$contents);
	   $contents  = str_replace('FOOTER','<?php include("../../../../phone_content/footer.php")?>',$contents);
	   $contents  = str_replace('LEFT_MENU','<?php include("../../../../phone_content/left_menu.php")?>',$contents);
	   $contents  = str_replace('RIGHT','<?php include("../../../../phone_content/right.php")?>',$contents);
	   $image     = strtolower( str_replace(" ","_",$_REQUEST['model'])).".small.".$extention[1];
	   $contents  = str_replace('MODEL_IMAGE',$image,$contents);
	   $modellist = getModelListContents($db,$_FILES,$_REQUEST);
	   $contents  = str_replace('$model_image =',$modellist.'$model_image =',$contents);			
	   $contents  = str_replace('MODEL',$_REQUEST['model'],$contents);
	   $contents  = str_replace('SEARCH',"",$contents);
	   

	   
	   
	   $category_id = $_REQUEST['category_id'];      	   
	   $brand_id    = $_REQUEST['brand_id']; 
	   $model       = $_REQUEST['model']; 
	   
	   
	   $info['table']    = "phone";
	   $info['fields']   = array("*");   	   
	   $info['where']    =  " category_id='$category_id' AND brand_id='$brand_id' AND model='$model'";
	   $res  =  $db->select($info);
	   
	   $description = "<div id=\"specs-list\">".stripcslashes($res[0]['description'])."</div>";
	   $contents    = str_replace("DESCRIPTION",$description,$contents); 
	   $contents    = str_replace("VOTE",getStarContents(),$contents); 
	   
	  
	   
	   
	return   $contents;
}


function  makeBrandFile($db,$_request)
{
      $category_id = $_REQUEST['category_id'];      	   
	  $brand_name    = $_REQUEST['brand_name']; 
	  
	   
	   
	   
	   $info['table']    = "category";
	   $info['fields']   = array("*");   	   
	   $info['where']    =  " id='$category_id'";
	   $res  =  $db->select($info);
   	   $categoryName = strtolower( str_replace(" ","_",$res[0]['category_name']));
	   makeDir("../../phone",$categoryName);
	   
	 
	   $brandName   =  strtolower( str_replace(" ","_",$brand_name));
	   makeDir("../../phone/$categoryName",$brandName);
	   makeDir("../../phone/$categoryName/$brandName",$brandName."_file");
	   
	   $file  = "../../phone/$categoryName/$brandName/$brandName"."_file"."/$brandName".".php";
	   
	   
	   if(file_exists($file))
	   {
	         unlink($file);     
	   }
	   
	   
	   $hfile = fopen($file,w);
	   
	   if($hfile)
	   {
	        fwrite($hfile,getContentsBrand($db,$_REQUEST));
	   }
	   
	   fclose($hfile);
	   

}


function getContentsBrand($db,$_request)
{     
       
	   
	   $contents  = file_get_contents("../../phone_content/phone_model.php");
	   $contents  = str_replace('CSS','<link rel="stylesheet" href="../../../../css/style.css" >',$contents); 
       $contents  = str_replace('HEADER','<?php include("../../../../phone_content/header.php")?>',$contents);
	   $contents  = str_replace('FOOTER','<?php include("../../../../phone_content/footer.php")?>',$contents);
	   $contents  = str_replace('LEFT_MENU','<?php include("../../../../phone_content/left_menu.php")?>',$contents);
	   $contents  = str_replace('RIGHT','<?php include("../../../../phone_content/right.php")?>',$contents);
	   
	   $contents  = str_replace('MODEL_IMAGE',"",$contents);
	   
	
	   
	   
	   $category_id = $_REQUEST['category_id'];  
	   $info['table']    = "category";
	   $info['fields']   = array("*");   	   
	   $info['where']    =  " id='$category_id'";
	   $res  =  $db->select($info);
	  
	   $TITLE = strtoupper($res[0]['category_name'])." , ".strtoupper( $_REQUEST['brand_name']);
   	  
	   $categoryName = strtolower( str_replace(" ","_",$res[0]['category_name']));
	   
	   
	   $brand_name  = $_REQUEST['brand_name'];
	   
	   $contents  = str_replace('MODEL',$TITLE,$contents);
	   
	   $info['table']    = "brand";
	   $info['fields']   = array("*");   	   
	   $info['where']    =  " category_id='$category_id' AND brand_name='$brand_name'";
	   $res  =  $db->select($info);
	   $brand_id    = $res[0]['id']; 

	  $brand_name  = strtolower( str_replace(" ","_",$brand_name));
	 
	       $searchText='
		   
		   <?php
		            if($_REQUEST["searchNo"]=="no")
					  {
					     $_SESSION["search_key"] = null;
						 session_destroy();
					  }

					 if($_REQUEST["cmd"]=="search")
						{
						  $_SESSION["search_key"] = $_REQUEST["search_key"];
						}
	 
	 
		   ?>
		   
		   <form><span class=bodytext>Search by</span> <select name="search_key" id="search_key" class=textbox>
						  <option value="">--Select--</option><?php
	                
					  $cachefile = "../../../../temp/cache_search_key.php";
					  $cachetime = 30*10;
					 
					  
					 if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile))
						{
								$fp = fopen($cachefile, "r");
								$res = unserialize(fread($fp,filesize($cachefile)));
								fclose($fp);
						}
						else
						{   
						     $info["table"]  = "search_key";
							 $info["fields"] = array("*");
							 $info["where"]  = "1";
							 $res = $db->select($info);
							 
							$fp = fopen($cachefile, "w");
							fwrite($fp,serialize($res));
							fclose($fp);
							 
							 
						}
								
					
					 for($i=0;$i<count($res);$i++)
					 {
					 ?>
					 
						  <option value="<?=$res[$i][search_key_name]?>" <?php if(($_REQUEST["search_key"]==$res[$i]["search_key_name"])||($_SESSION["search_key"]==$res[$i]["search_key_name"])){ echo "selected";}?>><?=$res[$i][search_key_name]?></option>
					 
					 <?php
					    }
					 ?>
					  </select> 
					  <input type="hidden" name="category_id" id="category_id" value="'.$_REQUEST[category_id].'">
					  <input type="hidden" name="brand_id" id="brand_id" value="'.$brand_id.'">
					  <input type="hidden" name="cmd" id="cmd" value="search">
					  <input type="submit" name="submit_search" id="submit_search"  value="Ok" class="button">
					  </form>
	               ';  
	   
	   
	   $contents  = str_replace('SEARCH',"$searchText",$contents);
	   

	 $description  = '<?php  
	                      
					 
	 
	                   $cachefile = "../../../../temp/cache_'.strtolower( str_replace(" ","_",$categoryName)).'_'.strtolower( str_replace(" ","_",$brand_name)).'.php";
					   
					   if(!file_exists($cachefile))
					   {
					      $fhandle  = fopen($cachefile,"w");
						  fclose($fhandle);
						  
						  $cachetime = 0;
					   }
					   else
					   {
					     $cachetime = 30*10;
					   }
		
						
						
						
	 
	                          
							$page_name  = $_SERVER["PHP_SELF"]; //  If you use current code with a different page ( or file ) name then change current
							$start      = $_REQUEST["start"];
							$p_f        = $_REQUEST["p_f"];
							$nume       = $_REQUEST["total"];
						
							if(!isset($start))                         // current variable is set to zero for the first page
							 {
							  $start = 0;
							 }
							
							$eu = ($start -0);
							$limit = 42;                                 // No of records to be shown per page.
							$current = $eu + $limit;
							$back = $eu - $limit;
							$next = $eu + $limit;
							
							
							
							
						// Serve from the cache if it is younger than $cachetime
						if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile))
						{
						      
						
								$fp = fopen($cachefile, "r");
								$resAll = fread($fp,filesize($cachefile));
								fclose($fp);
						
						        $resArr  = explode("###",$resAll);
								
								$restotal   = unserialize($resArr[0]);
								$res        = unserialize($resArr[1]); 
								
								
						
						}
						else
						{
						
						
						  	   unset($info);
							   $info[table]    = "phone";
							   $info[fields]   = array("count(*) as total");   	   
							   $info[where]    = "category_id=\''.$category_id.'\' AND brand_id=\''.$brand_id.'\'";
							   $restotal            = $db->select($info);
						
						
						       unset($info);
							   $info[table]    = "phone";
							   $info[fields]   = array("id","model","image_file","search_key");   	   
							   $info[where]    =   "category_id=\''.$category_id.'\' AND brand_id=\''.$brand_id.'\'"; // limit $eu,$limit
							   $res            =  $db->select($info);
							   
							   $recordset  = serialize($restotal)."###".serialize($res);
							   
							   	$fp = fopen($cachefile, "w");
								fwrite($fp,$recordset);
								fclose($fp);
							
						
						}
						
						
						if(!empty($_SESSION["search_key"]))
						{
						  
						  
						   $count=0;
						   for($c=0;$c<count($res);$c++)
						   {
						   
						      if(eregi($_SESSION["search_key"],$res[$c]["search_key"],$arr))
							  {  
								  $resS[$count]["model"]       = $res[$c]["model"];
								  $resS[$count]["image_file"]  = $res[$c]["image_file"];
							      $count                       = $count+1;
							  }
						   }
						   
						   $restotal[0]["total"] = $count;
						
						}
						
						
						
						
							
							
							
							/////////////// WE have to find out the number of records in our table. We will use current to break the pages///////
							if (!isset($nume))
							{
								$nume=$restotal[0]["total"];
							}  
							  $last=$eu+$limit;
							 if($last>$restotal[0]["total"])
							 {
							  $last=$restotal[0]["total"];
							 }
							  
							   $str = "";   
							
							
							if(!empty($_SESSION["search_key"]))
							{
							
					               $html="<div id=makers><table border=0 width=100% id=table1>";
									for($i=$eu;$i<$last;$i++)
									{
									   if ($i % 6 == 0)
										{
											$html=$html."</tr><tr>";
										}
									  $modelOrginal = $resS[$i][model];
									  $model = strtolower(str_replace(" ","_",$resS[$i][model]));
									  $pathImage  = "../$model/".$resS[$i][image_file];
									   if(is_dir($pathImage)or !file_exists($pathImage))//!file_exists($file) && !is_file($file))
									 {
									   $pathImage = "../../../../images/no_photo.gif";
									 }
									  
									  $pathFile  = "../$model/$model".".php"."?id=".$resS[$i][id];
									  $html=$html."<td class=brandtdStyle><a href=".$pathFile." class=nav title=\"".$modelOrginal."\"><img src=".$pathImage." width=80 height=80 border=0 class=nav alt=\"".$modelOrginal."\" title=\"".$modelOrginal."\"></img><strong>".strtoupper($modelOrginal)."</strong></a></td>";
									}
							  
							     $html=$html."</tr></table></div>";
                                 echo $html;
							}
							else
							{
							   $html="<div id=makers><table border=0 width=100% id=table1>";
									for($i=$eu;$i<$last;$i++)
									{
									   if ($i % 6 == 0)
										{
											$html=$html."</tr><tr>";
										}
									  $modelOrginal = $res[$i][model];
									  $model = strtolower(str_replace(" ","_",$res[$i][model]));
									  $pathImage  = "../$model/".$res[$i][image_file];
								
									 if(is_dir($pathImage)or !file_exists($pathImage))//!file_exists($file) && !is_file($file))
									 {
									   $pathImage = "../../../../images/no_photo.gif";
									 }
									  
									  $pathFile  = "../$model/$model".".php"."?id=".$res[$i][id];
									  $html=$html."<td class=brandtdStyle><a href=".$pathFile." class=nav title=\"".$modelOrginal."\"><img src=".$pathImage." width=80 height=80 border=0 class=nav alt=\"".$modelOrginal."\" title=\"".$modelOrginal."\"></img><strong>".strtoupper($modelOrginal)."</strong></a></td>";
									}
							  
							     $html=$html."</tr></table></div>";
                                 echo $html;
							 }  
							   
							  ///// Variables set for advance paging///////////
							$p_limit=210; // current should be more than $limit and set to a value for whick links to be breaked
							if(!isset($p_f)){$p_f=0;}
							$p_fwd=$p_f+$p_limit;
							$p_back=$p_f-$p_limit;
							$tmp = ceil($p_limit / $limit);
							//////////// End of variables for advance paging ///////////////
							/////////////// Start the buttom links with Prev and next link with page numbers /////////////////
							echo "<div id=user-pages><font size=1 >";
							if($p_f<>0)
							{
							    $str ="$page_name?start=$p_back&p_f=$p_back&total=$nume";
								print "<a href=$str class=nav>PREV $tmp</a>&nbsp;&nbsp;&nbsp;&nbsp;";
							}
							if($back >=0 and ($back >=$p_f))
							{   $str ="$page_name?start=$back&p_f=$p_f&total=$nume";
								print "<a href=$str class=nav>PREV</a>";
							}
							echo "</font><font size=2 >";
							
							$indexnum = ceil(($p_f+1)/$limit);
							
							for($i=$p_f;$i < $nume and $i<($p_f+$p_limit);$i=$i+$limit)
							{
								if($i <> $eu)
								{
									$i2=$i+$p_f;
									 $str ="$page_name?start=$i&p_f=$p_f&total=$nume";
									echo " <a href=$str class=nav>$indexnum</a> ";
								}
								else
								{
									echo "<b>$indexnum</b>";
								}
								$indexnum = $indexnum+1;
							}
							echo "</font><font size=1 >";
							///////////// If we are not in the last page then Next link will be displayed. Here we check that /////
							if($current < $nume and $current <($p_f+$p_limit))
							{    $str ="$page_name?start=$next&p_f=$p_f&total=$nume";
								print "<a href=$str class=nav>NEXT</a>";
							}
							if($p_fwd < $nume)
							{   $str ="$page_name?start=$p_fwd&p_f=$p_fwd&total=$nume";
								print "&nbsp;&nbsp;&nbsp;&nbsp;<a href=$str class=nav>NEXT $tmp</a>";
							}
							echo "</font></div><br class=clear />"; 
														   
							  
							   
					   ?>';
	 
	   $contents    = str_replace("DESCRIPTION",$description,$contents); 
	   $contents    = str_replace("VOTE","",$contents); 
	   
	return   $contents;
}

function createthumb($thumbdateiname, $thumbdateiendung)
	{
    	$fullname = $thumbdateiname.$thumbdateiendung;
	    $fullthumbname = $thumbdateiname.".thumb.jpg";

  if ((strtolower($thumbdateiendung) == ".jpg") OR (strtolower($thumbdateiendung) == ".jpeg"))
        	{
				$src_img = imagecreatefromjpeg($fullname);
			}
			if (strtolower($thumbdateiendung) == ".gif")
            {
				$src_img = imagecreatefromgif($fullname);
			}
			if (strtolower($thumbdateiendung) == ".png")
            {
				$src_img = imagecreatefrompng($fullname);
			}

			$origx=imagesx($src_img);
  			$origy=imagesy($src_img);

			// Maximum width and height of the thumbnails
  			$max_x = 104;
  			$max_y = 74;

  			// Calc, if thumb has has to be squeezed from width or height
			if($origx >= $origy AND $origx > $max_x)
			{
        	    $faktor = $origx / $max_x;
	            $new_x = $origx / $faktor;
  				$new_y = $origy / $faktor;
			}

			elseif($origy > $origx AND $origy > $max_y)
			{
				$faktor = $origy / $max_y;
				$new_x = $origx / $faktor;
  				$new_y = $origy / $faktor;
			}

			else
			{
				$new_x = $origx;
				$new_y = $origy;
  			}

  			// Squeeze and write it into a file
			$dst_img = imagecreatetruecolor($new_x,$new_y);
  			imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_x,$new_y,imagesx($src_img),imagesy($src_img));
  			imagejpeg($dst_img, $fullthumbname, 50);
}


function createSmall($thumbdateiname, $thumbdateiendung)
	{
    	$fullname = $thumbdateiname.$thumbdateiendung;
	    $fullthumbname = $thumbdateiname.".small.jpg";

  if ((strtolower($thumbdateiendung) == ".jpg") OR (strtolower($thumbdateiendung) == ".jpeg"))
        	{
				$src_img = imagecreatefromjpeg($fullname);
			}
			if (strtolower($thumbdateiendung) == ".gif")
            {
				$src_img = imagecreatefromgif($fullname);
			}
			if (strtolower($thumbdateiendung) == ".png")
            {
				$src_img = imagecreatefrompng($fullname);
			}

			$origx=imagesx($src_img);
  			$origy=imagesy($src_img);

			// Maximum width and height of the thumbnails
  			$max_x = 208;
  			$max_y = 158;

  			// Calc, if thumb has has to be squeezed from width or height
			if($origx >= $origy AND $origx > $max_x)
			{
        	    $faktor = $origx / $max_x;
	            $new_x = $origx / $faktor;
  				$new_y = $origy / $faktor;
			}

			elseif($origy > $origx AND $origy > $max_y)
			{
				$faktor = $origy / $max_y;
				$new_x = $origx / $faktor;
  				$new_y = $origy / $faktor;
			}

			else
			{
				$new_x = $origx;
				$new_y = $origy;
  			}

  			// Squeeze and write it into a file
			$dst_img = imagecreatetruecolor($new_x,$new_y);
  			imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_x,$new_y,imagesx($src_img),imagesy($src_img));
  			imagejpeg($dst_img, $fullthumbname, 50);
}

function getStarContents()
{
  $str ='<table cellspacing="3" cellpadding="0" border="0" class="bdr">
			 <tr>
			 <td>  
        <form name="vote" action="">
			 <span class="bodytext">Vote to recognize other about the product.</span>
			
			 <table cellspacing="3" cellpadding="0" border="0" class="bodytext">
			   <tr bgcolor="#efefff" onmouseover=" this.style.background=\'#ffffff\'; " onmouseout=" this.style.background=\'#efefff\'; ">
				<td>
					<img src="../../../../image_stars/nostar.jpg" width="70" height="15"/> <td >No star</td><td><input type="radio" name="vote"  value="0" /></td>
				</td> 
				</tr>
				<tr bgcolor="#efefff" onmouseover=" this.style.background=\'#ffffff\'; " onmouseout=" this.style.background=\'#efefff\'; ">
				<td>
				   <img src="../../../../image_stars/onestar.jpg" width="70" height="15"/><td >One star</td><td><input type="radio" name="vote" value="1" /></td>
				</td> 
				</tr>
				<tr bgcolor="#efefff" onmouseover=" this.style.background=\'#ffffff\'; " onmouseout=" this.style.background=\'#efefff\'; ">
				<td>
				  <img src="../../../../image_stars/twostars.jpg" width="70" height="15"/><td >Two stars</td><td><input type="radio" name="vote" value="2" /></td>
				</td> 
				</tr>
				<tr bgcolor="#efefff" onmouseover=" this.style.background=\'#ffffff\'; " onmouseout=" this.style.background=\'#efefff\'; ">
				<td>
				  <img src="../../../../image_stars/threestars.jpg" width="70" height="15"/><td >Three stars</td><td><input type="radio" name="vote" value="3" /></td>
				</td> 
				</tr>
				<tr bgcolor="#efefff" onmouseover=" this.style.background=\'#ffffff\'; " onmouseout=" this.style.background=\'#efefff\'; ">
				<td>
				  <img src="../../../../image_stars/fourstars.jpg" width="70" height="15"/><td >Four stars</td><td><input type="radio" name="vote" value="4" /></td>
				</td> 
				</tr>
				<tr bgcolor="#efefff" onmouseover=" this.style.background=\'#ffffff\'; " onmouseout=" this.style.background=\'#efefff\'; ">
				<td>
				 <img src="../../../../image_stars/fivestars.jpg" width="70" height="15"/><td >Five stars</td><td><input type="radio" name="vote" value="5" /></td>
				</td> 
				</tr>
			</table>
		  
			<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>"  />
			<input type="submit"	 name="Submit" value="Submit" class="button" />
		 </form> 
		 </td>
		   </tr>
		  </table>
			<?php
			
			
			 
			
			   
			          error_reporting(0);
					   unset($info);
					   $info["table"]    = "phone_vote";
					   $info["fields"]   = array("*");   	   
					   $info["where"]    = " model_id=".$_REQUEST["id"]."";
					   $res              = $db->select($info);
					   
					 if($_REQUEST["Submit"]=="Submit")
			           {  
					   
						   if(count($res)==0)
						   {
							  unset($info);
							  unset($data);
							  $info["table"]       = "phone_vote";
							  $data["model_id"]    = $_REQUEST["id"]; 
							  $data["vote"]        = $_REQUEST["vote"];  	 
							  $data["click_count"] = 1;   
							  $info["data"]        = $data;
	
							  $db->insert($info);
						   }
						   else
						   {
							  unset($info);
							  unset($data);
							  $info["table"]       = "phone_vote";
							  $data["vote"]        = $res[0]["vote"]+$_REQUEST["vote"];  	 
							  $data["click_count"] = $res[0]["click_count"]+1;   
							  $info["data"]        = $data;   
							  $info["where"]       = " model_id=".$_REQUEST["id"]."";
	
							  $db->update($info);
						   }
				      }
				
			$count =  ceil($res[0]["vote"]/$res[0]["click_count"]);
				
				if($count==0)
				{
				   $file ="nostar.jpg";
				}
				else if($count==1)
				{
				   $file ="onestar.jpg";
				}
				else if($count==2)
				{
				   $file ="twostars.jpg";
				}
				else if($count==3)
				{
				   $file ="threestars.jpg";
				}
				else if($count==4)
				{
				   $file ="fourstars.jpg";
				}
				else if($count==5)
				{
				   $file ="fivestars.jpg";
				}
				
			?>
			
			<script>
			   document.getElementById("img_model").innerHTML = "<img   src=../../../../image_stars/<?=$file?> width=70 height=15>";
			</script>';
			return $str;
}


function getModelListContents($db,$_files,$_request)
{
       $category_id = $_REQUEST['category_id'];      	   
	   $brand_id    = $_REQUEST['brand_id']; 
	   $model       = strtolower( str_replace(" ","_",$_REQUEST['model'])); 
	   
	   
	   
	   $info['table']    = "category";
	   $info['fields']   = array("*");   	   
	   $info['where']    =  " id='$category_id'";
	   $res  =  $db->select($info);
   	   $categoryName = strtolower( str_replace(" ","_",$res[0]['category_name']));
	   
	   $info['table']    = "brand";
	   $info['fields']   = array("*");   	   
	   $info['where']    = " id='$brand_id'";
	   $res  =  $db->select($info);
	   $brandName   =  strtolower( str_replace(" ","_",$res[0]['brand_name']));
	  

	  $str ='$cachefile = "../../../../temp/cache_'.strtolower( str_replace(" ","_",$categoryName)).'_'.strtolower( str_replace(" ","_",$brandName)).'.php";

	   if(!file_exists($cachefile))
	   {
		  $fhandle  = fopen($cachefile,"w");
		  fclose($fhandle);
		  
		  $cachetime = 0;
	   }
	   else
	   {
		 $cachetime = 30*10;
	   }

		
		// Serve from the cache if it is younger than $cachetime
		if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile))
		{
				$fp = fopen($cachefile, "r");
				$resAll = fread($fp,filesize($cachefile));
				fclose($fp);
		
				$resArr  = explode("###",$resAll);
				
				$restotal   = unserialize($resArr[0]);
				$res        = unserialize($resArr[1]); 
		}
		else
		{
			   unset($info);
			   $info[table]    = "phone";
			   $info[fields]   = array("count(*) as total");   	   
			   $info[where]    = "category_id='.$category_id.' AND brand_id='.$brand_id.'";
			   $restotal       = $db->select($info);
		
		
			   unset($info);
			   $info[table]    = "phone";
			   $info[fields]   = array("id","model","image_file","search_key");     
			   $info[where]    = "category_id='.$category_id.' AND brand_id='.$brand_id.'"; // limit $eu,$limit
			   $res            = $db->select($info);
			   
			   $recordset  = serialize($restotal)."###".serialize($res);
			   
				$fp = fopen($cachefile, "w");
				fwrite($fp,$recordset);
				fclose($fp);
			
		
		}
		         //  $brandFile = "'.strtolower(str_replace(" ","_",$brandName)).'/'.strtolower(str_replace(" ","_",$brandName)).'.php";
		
			$html="<div style=\"float:right;overflow-y:scroll;direction:ltr;width:150;height:208;background-color:FFFFF0;\"><table border=0  id=table1>
						<tr bgcolor=#C6C6C6><td ><b>'.strtoupper($brandName).'</b></td></tr>";
					for($i=0;$i<count($res);$i++)
					{
					  $modelOrginal = $res[$i][model];
					  $model = strtolower(str_replace(" ","_",$res[$i][model]));
				
				
					  
					  $pathFile  = "../$model/$model".".php"."?id=".$res[$i][id];
					  $html=$html."<tr><td ><a href=".$pathFile." class=nav title=\"".$modelOrginal."\"><strong>".strtoupper($modelOrginal)."</strong></a></td></tr>";
					}
			  
				 $html=$html."</table></div>";';
				 
			return $str;
}


 function   debug($var)
	 {
       echo "<pre>";
	      print_r($var);
	   echo "</pre>";
     }
?>