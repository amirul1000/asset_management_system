<?php
       session_start();
       include("../common/lib.php");
	   include("../lib/class.db.php");
	   include("../common/config.php");
	   
	   
	   if(empty($_SESSION['userid']))
	   {
	     Header("Location: ../login/login.php");
	   }
	   
	   $cmd = $_REQUEST['cmd'];
	   switch($cmd)
	   {
	     
		  case 'add': 
		                   $info['table']    = "currency";
						   $data['from_country'] = $_REQUEST['from_country'];
						   $data['from_country_currency'] = $_REQUEST['from_country_currency'];
						   $data['to_country'] = $_REQUEST['to_country'];
						   $data['to_country_currency'] = $_REQUEST['to_country_currency'];
						   $data['alias_name'] = $_REQUEST['alias_name'];
						   
						   $info['data']     =  $data;
						  
						   if(empty($_REQUEST['id']))
						   {
						    $db->insert($info);
						   }
						   else
						   {
						    $Id            = $_REQUEST['id'];
							$info['where'] = "id=".$Id;
							
						    $db->update($info);
						   }
						   
			include("../Currency/Currency_list.php");
						   
						   break; 
						   
						   
		case "edit":       $Id               = $_REQUEST['id'];
		                    if( !empty($Id ))
							{
							   $info['table']    = "currency";
							   $info['fields']   = array("*");   	   
							   $info['where']    =  "id=".$Id;
							   $res  =  $db->select($info);
							   
		                       $Id        = $res[0]['id'];
                               $from_country = $res[0]['from_country'];
						       $from_country_currency = $res[0]['from_country_currency'];
					           $to_country = $res[0]['to_country'];
					           $to_country_currency = $res[0]['to_country_currency'];
					           $alias_name = $res[0]['alias_name'];
						   }
                           	include("../Currency/Currency_editor.php");
						  
						   break;
						   
         case 'delete': 
		                   $Id               = $_REQUEST['id'];
		                   
						   $info['table']    = "currency";
						   $data['where']    = "id='$Id'";
						  
						   if($Id)
						   {
						    $db->delete($info['table'],$data['where']);
						   }
				include("../Currency/Currency_list.php");
						   
						   break; 
						   
						   
          case "list" :    	 if(!empty($_REQUEST['page'])&&$_SESSION["search"]=="yes")
		                    {
							  $_SESSION["search"]="yes";
							 							}
							else
							{
		                       $_SESSION["search"]="no";
								unset($_SESSION["search"]);
								unset($_SESSION['field_name']);
								unset($_SESSION["field_value"]); 
							}
		                    include("../Currency/Currency_list.php");
						   break; 
          case "search_Currency":
		                    $_REQUEST['page'] = 1;  
		  					$_SESSION["search"]="yes";
							$_SESSION['field_name'] = $_REQUEST['field_name'];
							$_SESSION["field_value"] = $_REQUEST['field_value'];
		  				    include("../Currency/Currency_list.php");
						   break;  						    						   
						
	default   :    include("../Currency/Currency_list.php");
      
	   }

	 
	   
?>
