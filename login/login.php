<?php
       session_start();
	   include("../common/lib.php");
	   include("../lib/class.db.php");
	   include("../common/config.php");
	   	   
	   $cmd = $_REQUEST['cmd'];
	
	  switch($cmd)
	   {
	   
	     case "login":
				 $info["table"]     = "users";
				 $info["fields"]   = array("*");
				 $info["where"]    = " 1=1 AND userid='".$_REQUEST["userid"]."' AND password='".$_REQUEST["password"]."'";
				 $res  = $db->select($info);
				
				 if(count($res)>0)
					 {	    
							 $_SESSION["userid"]   = $res[0]["userid"];
							 $_SESSION["password"] = $res[0]["password"];
							 
								unset($info);
						     $info["table"]     = "company";
				             $info["fields"]   = array("*");
				             $info["where"]    = " 1=1 AND id='".$_REQUEST["ComID"]."'";
				             $res  = $db->select($info);
							 
							 $_SESSION["ComID"] = $res[0]["id"];
							 $_SESSION["opDate"] = $res[0]["opDate"];
							 $_SESSION["clDate"] = $res[0]["clDate"];
							 $_SESSION["ComName"] = $res[0]["ComName"];
							 $_SESSION["Address1"] = $res[0]["Address1"];
							 $_SESSION["Address2"] = $res[0]["Address2"];
							 Header("Location: ../login/login_enter.php");
							 
					 }							 
				 else
					 {
					            $message="Login fail,Please verify your userid or password";
								include("login_editor.php");
					 }	
			   break;	
		 case "logout":
		                session_destroy();
		                unset($_SESSION["userid"]);
						unset($_SESSION["password"]);
						unset($_SESSION["ComID"]);
						include("login_editor.php");
						break;	   	 
		 default :					 
		              session_destroy();
					  unset($_SESSION["userid"]);
					  unset($_SESSION["password"]);
					  unset($_SESSION["ComID"]);  
					include("login_editor.php");
	    }	
?>