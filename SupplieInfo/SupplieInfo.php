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
		                   $info['table']    = "supplieinfo";
						   
						   $data['ComID'] = $_SESSION['ComID'];  
						   $data['Supplier'] = $_REQUEST['Supplier'];  
						   $data['SAddress'] = $_REQUEST['SAddress'];  
						   $data['SCont'] = $_REQUEST['SCont'];  

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
						   
			include("../SupplieInfo/SupplieInfo_list.php");
						   
						   break; 
						   
						   
		case "edit":       $Id               = $_REQUEST['id'];
		                    if( !empty($Id ))
							{
							   $info['table']    = "supplieinfo";
							   $info['fields']   = array("*");   	   
							   $info['where']    =  "id=".$Id;
							   
							   $res  =  $db->select($info);
							   
								$Id        = $res[0]['id']; 
								$ComID = $res[0]['ComID'];  
                                $Supplier =$res[0]['Supplier'];  
						        $SAddress =$res[0]['SAddress'];  
						        $SCont = $res[0]['SCont'];  
								
						   }
                           	include("../SupplieInfo/SupplieInfo_editor.php");
						  
						   break;
						   
         case 'delete': 
		                   $Id               = $_REQUEST['id'];
		                   
						   $info['table']    = "supplieinfo";
						   $data['where']    = "id='$Id'";
						  
						   if($Id)
						   {
						    $db->delete($info['table'],$data['where']);
						   }
				include("../SupplieInfo/SupplieInfo_list.php");
						   
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
		                    include("../SupplieInfo/SupplieInfo_list.php");
						   break; 
          case "search_SupplieInfo":
		                    $_REQUEST['page'] = 1;  
		  					$_SESSION["search"]="yes";
							$_SESSION['field_name'] = $_REQUEST['field_name'];
							$_SESSION["field_value"] = $_REQUEST['field_value'];
		  				    include("../SupplieInfo/SupplieInfo_list.php");
						   break;  						    						   
						
	default   :    include("../SupplieInfo/SupplieInfo_list.php");		   
      
	   }

	 
	   
?>
