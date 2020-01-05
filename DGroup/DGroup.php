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
		                   $info['table']    = "dgroup";
						   
						   $data['ComID'] = $_SESSION['ComID'];  
						   $data['DefGroup'] = $_REQUEST['DefGroup'];  
						   $data['Rate'] = $_REQUEST['Rate'];  
						   $data['Method'] = $_REQUEST['Method']; 
						   
						   if($_REQUEST["Method"]=="SLM")
						   {
						   $data['Life'] = 100/$data['Rate'];  
						   }
						   elseif($_REQUEST["Method"]=="RBM")
						   {
						   $data['Life'] =100/$data['Rate'];
						   }
						   elseif($_REQUEST["Method"]=="NDM")
						   {
						    $data['Rate'] =0;
						   	$data['Life'] =  0;
						   }
						   
						   
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
						   
			include("../DGroup/DGroup_list.php");
						   
						   break; 
						   
						   
		case "edit":       $Id               = $_REQUEST['id'];
		                    if( !empty($Id ))
							{
							   $info['table']    = "dgroup";
							   $info['fields']   = array("*");   	   
							   $info['where']    =  "id=".$Id;
							   
							   $res  =  $db->select($info);
							   
								$Id        = $res[0]['id']; 
								$ComID = $res[0]['ComID'];  
								$DefGroup = $res[0]['DefGroup'];  
								$Rate = $res[0]['Rate'];  
								$Method = $res[0]['Method'];      	   
								$Life =$res[0]['Life'];  
						   }
                           	include("../DGroup/DGroup_editor.php");
						  
						   break;
						   
         case 'delete': 
		                   $Id               = $_REQUEST['id'];
		                   
						   $info['table']    = "dgroup";
						   $data['where']    = "id='$Id'";
						  
						   if($Id)
						   {
						    $db->delete($info['table'],$data['where']);
						   }
				include("../DGroup/DGroup_list.php");
						   
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
		                    include("../DGroup/DGroup_list.php");
						   break; 
          case "search_DGroup":
		                    $_REQUEST['page'] = 1;  
		  					$_SESSION["search"]="yes";
							$_SESSION['field_name'] = $_REQUEST['field_name'];
							$_SESSION["field_value"] = $_REQUEST['field_value'];
		  				    include("../DGroup/DGroup_list.php");
						   break;  						    						   
						
	default   :    include("../DGroup/DGroup_list.php");		   
      
	   }

	 
	   
?>
