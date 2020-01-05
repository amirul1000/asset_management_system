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
		                   $info['table']    = "users";
						   
						   $data['ComID'] = $_REQUEST['ComID'];  
						   $data['userid'] = $_REQUEST['userid'];  
						   $data['password'] = $_REQUEST['password'];  
						   $data['name'] = $_REQUEST['name'];      	   
						   $data['designation'] = $_REQUEST['designation'];  
						   $data['address'] = $_REQUEST['address'];  
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
						   
			include("../users/users_list.php");
						   
						   break; 
						   
						   
		case "edit":       $Id               = $_REQUEST['id'];
		                    if( !empty($Id ))
							{
							   $info['table']    = "users";
							   $info['fields']   = array("*");   	   
							   $info['where']    =  "id=".$Id;
							   
							   $res  =  $db->select($info);
							   
							   $Id        = $res[0]['id'];  
							   $ComID = $res[0]['ComID'];  
							   $name  = $res[0]['name'];
							   $userid = $res[0]['userid'];  
							   $name = $res[0]['name'];      	   
							   $designation = $res[0]['designation'];  
							   $address = $res[0]['address'];
							   
						   }
                           	include("../users/users_editor.php");
						  
						   break;
						   
         case 'delete': 
		                   $Id               = $_REQUEST['id'];
		                   
						   $info['table']    = "users";
						   $data['where']    = "id='$Id'";
						  
						   if($Id)
						   {
						    $db->delete($info['table'],$data['where']);
						   }
				include("../users/users_list.php");
						   
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
		                    include("../users/users_list.php");
						   break; 
          case "search_users":
		                    $_REQUEST['page'] = 1;  
		  					$_SESSION["search"]="yes";
							$_SESSION['field_name'] = $_REQUEST['field_name'];
							$_SESSION["field_value"] = $_REQUEST['field_value'];
		  				    include("../users/users_list.php");
						   break;  						    						   
						
	default   :    include("../users/users_list.php");		   
      
	   }

	 
	   
?>
