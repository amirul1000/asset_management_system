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
		                $info['table']    = "master";
						$data['ComID'] = $_SESSION['ComID'];  
						$data['PurCost'] = $_REQUEST['PurCost'];  
						$data['InstCost'] = $_REQUEST['InstCost'];  
						$data['CarryCost'] = $_REQUEST['CarryCost'];  
						$data['OtherCost'] = $_REQUEST['OtherCost'];  
						$data['SerDate'] = $_REQUEST['SerDate'];
						$data['DefGroup'] = $_REQUEST['DefGroup'];  
						$data['SubGroup'] = $_REQUEST['SubGroup'];  
						$data['Department'] = $_REQUEST['Department'];  
						$data['Supplier'] = $_REQUEST['Supplier'];  
						$data['Location'] = $_REQUEST['Location'];  
						$data['UserName'] = $_REQUEST['UserName'];  
						$data['Project'] = $_REQUEST['Project'];  
						$data['Rate'] = $_REQUEST['Rate'];  
						$data['Life'] = $_REQUEST['Life'];  
						$data['Method'] = $_REQUEST['Method'];  
						$data['Narration'] = $_REQUEST['Narration'];  
						$data['Warranty'] = $_REQUEST['Warranty'];  						
						$data['OpAssets'] =0;// $_REQUEST['OpAssets'];  
						$data['OpDep'] = 0;//$_REQUEST['OpDep'];  
						$data['Dispose'] = 0;//$_REQUEST['Dispose'];  
						$data['ACode'] = makeACode($db,$data['DefGroup'],$data['SubGroup'], $_REQUEST['TDate']);  
						
						
						 
						   if(empty($_REQUEST['id']))
						   {   $info['data']     =  $data;
								$db->insert($info);
								$Acode = $data['ACode'];
								
								//Debit-Save Details Table
								unset($info);
								unset($data);
								$info['table']    = "details";
								$data['ComID'] = $_SESSION['ComID'];  
								$data['Ref'] = $_REQUEST['Ref'];  
								$data['TDate'] = $_REQUEST['TDate'];  
								$data['ACode'] = $Acode ;
								$data['Debit'] =$_REQUEST['TCost'];  
								$info['data']     =  $data;
								$db->insert($info);		
								
								
								//DebDep-Save Details Table							
								$year = date("Y", strtotime($_REQUEST["SerDate"]));
		                        $mon_date= date("m-d",strtotime($_REQUEST["SerDate"]));
								
								 if($_REQUEST["Method"]=="SLM")
								   {
								   
								    for($life=1;$life<=$_REQUEST["Life"];$life++)
									{
										unset($info);
								        unset($data);
										$info['table']    = "details";
										$data['ComID'] = $_SESSION['ComID'];  
										$data['Ref'] = $_REQUEST['Ref'];  
										$data['TDate'] =$year+($life-1)."-".$mon_date;//$_REQUEST["SerDate"];//-$life;  
										$data['ACode'] = $Acode ;
										$data['DepDebit'] =round($_REQUEST['TCost']/$_REQUEST["Life"],2);  
										$info['data']     =  $data;
										$db->insert($info);	
									}	
								   }
								   
								   
								    if($_REQUEST["Method"]=="RBM")
								   {
								      $DepDebit =0;
									  $inc = 0;
									  $tlife= $_REQUEST["Life"];
								    for($life=1;$life<=$tlife;$life++)
									{
											unset($info);
								            unset($data);
										$info['table']    = "details";
										$data['ComID'] = $_SESSION['ComID'];  
										$data['Ref'] = $_REQUEST['Ref'];  
										$data['TDate'] =$year+($life-1)."-".$mon_date;//$_REQUEST["SerDate"];//+$tlife-$life;  
										$data['ACode'] = $Acode ;
										$DepDebit = ($_REQUEST['TCost']-$inc)*$_REQUEST["Rate"]/100;  
										$inc =$inc+$DepDebit;
										
										$data['DepDebit'] =round($DepDebit,2);
										$info['data']     =  $data;
										$db->insert($info);	
									}	
								   }
								   
													
						   }
						   else
						   { 
						     $Id            = $_REQUEST['id'];
							 /********Generate Code****************/
								 $info1['table']    = "master";
								 $info1["fields"]   = array("*");
								 $info1['where']    = "id='$Id'";
								 $res1 = $db->select($info1);
								 $PreviousACodeArr = explode("-",$res1[0]['ACode']);
								 $GenACodeArr      = explode("-",$data['ACode']);
								 $data['ACode']    = $GenACodeArr[0]."-".$PreviousACodeArr[1]; 								 
							 /**********************************************/
							 $info['data']     =  $data;
							 $info['where'] = "id=".$Id;							
						     $db->update($info);
							 
							  $Acode = $data['ACode'];
							 
						 /******************Delete*********************************/
		                   //Extract ACode before delete
						 	 $info['table']    = "master";
							 $info["fields"]= array("*");
						     $info['where']    = "id='$Id'";
							 $res = $db->select($info);
							
						     //Delete from Master
							   // unset($info);
							//	unset($data);
							// $info['table']    = "master";
							// $data['where']    = "id='$Id'";
							// $db->delete($info['table'],$data['where']);
								 
							 //Delete from details
								unset($info);
								unset($data);
							 $info['table']    = "details";							
						     $data['where']    = " ComID='".$_SESSION["ComID"]."' AND  ACode='".$res[0]["ACode"]."'";							
						     $db->delete($info['table'],$data['where']);
							 
							 
							 //Delete from Disposeassets
							    unset($info);
								unset($data);
							 $info['table']    = "disposeassets";
							 $data['where']    = " ComID='".$_SESSION["ComID"]."' AND  ACode='".$res[0]["ACode"]."'";							
							 $db->delete($info['table'],$data['where']);
						/*******************End of Delete********************************/
								
								//Debit-Save Details Table
								unset($info);
								unset($data);
								$info['table']    = "details";
								$data['ComID'] = $_SESSION['ComID'];  
								$data['Ref'] = $_REQUEST['Ref'];  
								$data['TDate'] = $_REQUEST['TDate'];  
								$data['ACode'] = $Acode ;
								$data['Debit'] =$_REQUEST['TCost'];  
								$info['data']     =  $data;
								$db->insert($info);		
								
								
								//DebDep-Save Details Table							
								$year = date("Y", strtotime($_REQUEST["SerDate"]));
		                        $mon_date= date("m-d",strtotime($_REQUEST["SerDate"]));
								
								 if($_REQUEST["Method"]=="SLM")
								   {
								   
								    for($life=1;$life<=$_REQUEST["Life"];$life++)
									{
										unset($info);
								        unset($data);
										$info['table']    = "details";
										$data['ComID'] = $_SESSION['ComID'];  
										$data['Ref'] = $_REQUEST['Ref'];  
										$data['TDate'] =$year+($life-1)."-".$mon_date;//$_REQUEST["SerDate"];//-$life;  
										$data['ACode'] = $Acode ;
										$data['DepDebit'] =round($_REQUEST['TCost']/$_REQUEST["Life"],2);  
										$info['data']     =  $data;
										$db->insert($info);	
									}	
								   }
								   
								   
								    if($_REQUEST["Method"]=="RBM")
								   {
								      $DepDebit =0;
									  $inc = 0;
									  $tlife= $_REQUEST["Life"];
								    for($life=1;$life<=$tlife;$life++)
									{
											unset($info);
								            unset($data);
										$info['table']    = "details";
										$data['ComID'] = $_SESSION['ComID'];  
										$data['Ref'] = $_REQUEST['Ref'];  
										$data['TDate'] =$year+($life-1)."-".$mon_date;//$_REQUEST["SerDate"];//+$tlife-$life;  
										$data['ACode'] = $Acode ;
										$DepDebit = ($_REQUEST['TCost']-$inc)*$_REQUEST["Rate"]/100;  
										$inc =$inc+$DepDebit;
										
										$data['DepDebit'] =round($DepDebit,2);
										$info['data']     =  $data;
										$db->insert($info);	
									}	
								   }
								   
							
						   }
						   
			include("../Master/Master_list.php");
						   
						   break; 
						   
						   
		case "edit":   
					$Id               = $_REQUEST['id'];
					if( !empty($Id ))
					{
					   $info['table']    = "master left outer join details on(master.ACode=details.ACode)";
					   $info['fields']   = array("master.*","details.Ref","details.TDate");   	   
					   $info['where']    =  "master.id='".$Id."' AND details.Debit>0";
					   $res  =  $db->select($info);
						
						$Id        = $res[0]['id']; 
						$ComID = $res[0]['ComID'];  
                               
						$ACode = $res[0]['ACode'];  
						$PurCost = $res[0]['PurCost'];  
						$InstCost = $res[0]['InstCost'];  
						$CarryCost = $res[0]['CarryCost'];  
						$OtherCost = $res[0]['OtherCost']; 
						$TCost =$PurCost+$InstCost+ $CarryCost+ $OtherCost;
						$SerDate = $res[0]['SerDate'];
						$DefGroup = $res[0]['DefGroup'];  
						$SubGroup = $res[0]['SubGroup'];  
						$Department = $res[0]['Department'];  
						$Supplier = $res[0]['Supplier'];  
						$Location = $res[0]['Location'];  
						$UserName = $res[0]['UserName'];  
						$Project = $res[0]['Project'];  
						$Rate = $res[0]['Rate'];  
						$Life = $res[0]['Life'];  
						$Method = $res[0]['Method'];  
						$Narration = $res[0]['Narration'];  
						$Warranty = $res[0]['Warranty'];  						
						$OpAssets = $res[0]['OpAssets'];  
						$OpDep= $res[0]['OpDep'];  
						$Dispose = $res[0]['Dispose']; 
						$Ref    = $res[0]['Ref']; 
						$TDate  = $res[0]['TDate']; 
								
						   }
                           	include("../Master/Master_editor.php");
						  
						   break;
						   
         case 'delete': 
		                   $Id               = $_REQUEST['id'];
		                   //Extract ACode before delete
						 	 $info['table']    = "master";
							 $data["fields"]= array("*");
							 $info["data"]= $data;
						     $info['where']    = "id='$Id'";
							
							 $res = $db->select($info);
							 
							
						  
						   if($Id)
						   {
						     //Delete from Master
							    unset($info);
								unset($data);
							 $info['table']    = "master";
							 $data['where']    = "id='$Id'";
							 $db->delete($info['table'],$data['where']);
								 
							 //Delete from details
								unset($info);
								unset($data);
							 $info['table']    = "details";							
						     $data['where']    = " ComID='".$_SESSION["ComID"]."' AND  ACode='".$res[0]["ACode"]."'";							
						     $db->delete($info['table'],$data['where']);
							 
							 
							 //Delete from Disposeassets
							    unset($info);
								unset($data);
							 $info['table']    = "disposeassets";
							 $data['where']    = " ComID='".$_SESSION["ComID"]."' AND  ACode='".$res[0]["ACode"]."'";							
							 $db->delete($info['table'],$data['where']);
								 
						   }
				include("../Master/Master_list.php");
						   
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
		                    include("../Master/Master_list.php");
						   break; 
          case "search_Master":
		                    $_REQUEST['page'] = 1;  
		  					$_SESSION["search"]="yes";
							$_SESSION['field_name'] = $_REQUEST['field_name'];
							$_SESSION["field_value"] = $_REQUEST['field_value'];
		  				    include("../Master/Master_list.php");
						   break; 
          case "make_sgroup":   
				
								$info["table"] = "sgroup";
								$info["fields"] = array("*"); 
								$info["where"]   = "1 AND DefGroup='".$_REQUEST['DefGroup']."'   ORDER BY SubGroup ASC";
								$res  =  $db->select($info);
								$str='<select type="text" name="SubGroup" id="SubGroup"  class="textbox">';								    
										foreach($res as  $key=>$each)
										{
										$sel="";
									 if($SubGroup==$each['SubGroup']) $sel="selected";
								$str=$str.'<option value="'.$each['SubGroup'].'" '.$sel.'>'.$each['SubGroup'].'</option>';
									 
									 	 }
									 									 
								$str=$str.'</select>';

		  						echo $str;
		  
		  
		                       break; 
							   
			case "make_depreciation":		
										$info["table"] = "dgroup";
										$info["fields"] = array("*"); 
										$info["where"]   = "1 AND DefGroup='".$_REQUEST['DefGroup']."' limit 1";
										$res  =  $db->select($info);
										
										
										 if($res[0]['Method']=="SLM")
										 {
										   $SLM = "selected";
										 }
										if($res[0]['Method']=="RBM")
										 {
										  	$RBM = "selected";
										 }
										 if($res[0]['Method']=="NDM") 
										 {
										 	$NDM= "selected";
										 }
										
										 $str='<table class="bdr" cellspacing="1" cellpadding="1">
												  <tr>
													 <td>Method of Depreciation</td>
													 <td> 
													 	  <select type="text" name="Method" id="Method"  class="textbox" >
														 		<option value="SLM" '.$SLM.' >Straight Line Method</option>
																<option value="RBM"  '.$RBM.'>Reducing Balance Method</option>
																<option value="NDM" '.$NDM.'>No Depreciation </option>
														  </select>	
													  </td>
												 </tr>
												 <tr>
													 <td>Rate of Depreciation %</td>
													 <td> <input type="text" name="Rate" id="Rate"  value="'.$res[0]['Rate'].'" class="textbox" onkeyup="setmethodvalue(this.value);"></td>
												 </tr>
												 <tr>
													 <td>Useful life Years</td>
													 <td> <input type="text" name="Life" id="Life"  value="'.$res[0]['Life'].'" class="textbox"></td>
												 </tr>
										 </table>';				   
							                  echo $str;
		  
		  
		                                         break; 
           case "check_date":
		   					 $info['table']    = "company";
							 $info["fields"]= array("*");							 
						     $info['where']    = " id='".$_SESSION["ComID"]."' AND  opDate<='".$_REQUEST["TDate"]."' AND  clDate>='".$_REQUEST["TDate"]."'";
							
							 $res = $db->select($info);
							
							 if(count($res)>0)
							  {
							     echo $res[0]["clDate"];
							  }
							  else
							  {
							    echo "Date must be within accounting period";
							  } 	
							  break;												 
							    						    						    						   
						
	default   :    include("../Master/Master_list.php");		   
      
	   }
function makeACode($db,$DefGroup,$SubGroup,$TDate)
{
   $ACode="";
   
   for($i=0;$i<strlen($DefGroup);$i++)
   {
      $eachCharacter  =  substr($DefGroup,$i,1);
	  if($eachCharacter>='A'&&$eachCharacter<='Z')
	  {
        $ACode=$ACode.$eachCharacter;
	  }
   }
   
    
  for($i=0;$i<strlen($SubGroup);$i++)
   {
      $eachCharacter  =  substr($SubGroup,$i,1);
	  if($eachCharacter>='A'&&$eachCharacter<='Z')
	  {
        $ACode=$ACode.$eachCharacter;
	  }
   }
	  

	  
	  
    $info['table']    = "master";
	$info['fields']   = array("MAX(CAST(SUBSTRING_INDEX(ACode, '-', -1 ) AS  UNSIGNED INTEGER)) as auto");
	$info['where']    = "1";
	$res2 = $db->select($info); 
     						   
    $ACode=$ACode.substr($TDate,2,2);
	if($res2[0]["auto"]>0)
	{
	   $res2[0]["auto"] = $res2[0]["auto"]+1;
	}
	else
	{
		$res2[0]["auto"] = 1;
	}
	$ACode=$ACode."-".$res2[0]["auto"];
	
	 
	 return  $ACode;
}
	 
	   
?>
