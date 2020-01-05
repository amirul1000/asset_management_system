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
		                   $info['table']    = "dept";
						   
						   $data['ComID'] = $_SESSION['ComID'];  
						   $data['Department'] = $_REQUEST['Department'];  

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
						   
			include("../Dept/Dept_list.php");
						   
						   break; 
						   
						   
		case "edit":       $Id               = $_REQUEST['id'];
		                    if( !empty($Id ))
							{
							   $info['table']    = "dept";
							   $info['fields']   = array("*");   	   
							   $info['where']    =  "id=".$Id;
							   
							   $res  =  $db->select($info);
							   
								$Id        = $res[0]['id']; 
								$ComID = $res[0]['ComID'];  
								$Department = $res[0]['Department'];  
								
						   }
                           	include("../Dept/Dept_editor.php");
						  
						   break;
						   
         case 'delete': 
		                   $Id               = $_REQUEST['id'];
		                   
						   $info['table']    = "dept";
						   $data['where']    = "id='$Id'";
						  
						   if($Id)
						   {
						    $db->delete($info['table'],$data['where']);
						   }
				include("../Dept/Dept_list.php");
						   
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
		                    include("../Dept/Dept_list.php");
						   break; 
          case "search_Dept":
		                    $_REQUEST['page'] = 1;  
		  					$_SESSION["search"]="yes";
							$_SESSION['field_name'] = $_REQUEST['field_name'];
							$_SESSION["field_value"] = $_REQUEST['field_value'];
		  				    include("../Dept/Dept_list.php");
						   break;  						    						   
						
	default   :    include("../Dept/Dept_list.php");		   
      
	   }

	 
	   
?>
