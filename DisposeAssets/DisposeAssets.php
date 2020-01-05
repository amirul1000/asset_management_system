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
		                   $info['table']    = "disposeassets";
						   
						$data['ComID'] = $_SESSION['ComID'];  						
						$data['TDate'] = $_REQUEST['TDate'];  
						$data['ACode'] = $_REQUEST['ACode'];  
						$data['ReasonForSale'] = $_REQUEST['ReasonForSale'];  
						$data['NetProceeds'] = $_REQUEST['NetProceeds'];  
						$data['BookValue'] = $_REQUEST['BookValue'];
						$data['GainLoss'] = $_REQUEST['GainLoss'];  
						
							$info['data']     =  $data;
						 
						   if(empty($_REQUEST['id']))
						   {
						    $db->insert($info);
							
							
								$ACode = $data['ACode'];
								$ComID=$data['ComID'];
								
								
								unset($info);
								unset($data);
								$info['table'] = "master";
								$data['Dispose']="1";
								$info['data']=$data;
								$info['where'] = " 1 AND ACode='".$ACode."' AND ComID='".$ComID."'";
								
								$db->update($info); 
								//Save Details Table
								unset($info);
								unset($data);
								$info['table']    = "details";
								$data['ComID'] = $_SESSION['ComID'];  
								$data['Ref'] = $_REQUEST['Ref'];  
								$data['TDate'] = $_REQUEST['TDate'];  
								$data['ACode'] = $ACode ;
								$data['Credit'] =$_REQUEST['PurCost'];  
								$data['DepCredit'] =$_REQUEST['ADepreciation'];  
								
								$info['data']     =  $data;
								$db->insert($info);	
								
								//Delete from Details
								unset($info);
								unset($data);
								$info['table']    = "details";
						        $data['where']    = " ComID='".$_SESSION["ComID"]."'  AND  DepDebit>0  AND  TDate>'".$_REQUEST["TDate"]."' AND ACode='".$_REQUEST["ACode"]."'";
								
						        $db->delete($info['table'],$data['where']);
								
							
							
						   }
						   else
						   {
						    $Id            = $_REQUEST['id'];
							$info['where'] = "id=".$Id;
							
						    $db->update($info);
						   }
						   
			include("../DisposeAssets/DisposeAssets_list.php");
						   
						   break; 
						   
			case "edit":       $Id               = $_REQUEST['id'];
		                    if( !empty($Id ))
							{
							   $info['table']    = "disposeassets";
							   $info['fields']   = array("*");   	   
							   $info['where']    =  "id=".$Id;
							   
							   $res  =  $db->select($info);
							   
								$Id        = $res[0]['id']; 
								$ComID = $res[0]['ComID'];  
                               
						$ACode = $res[0]['ACode'];  
						$TDate =$res[0]['TDate'];  
						$ACode = $res[0]['ACode'];  
						$ReasonForSale = $res[0]['ReasonForSale'];  
						$NetProceeds = $res[0]['NetProceeds'];  
						$BookValue= $res[0]['BookValue'];
						$GainLoss= $res[0]['GainLoss'];  
								
						   }
                           	include("../DisposeAssets/DisposeAssets_editor.php");
						  
						   break;				   
						   
						   
		case "undo":        $Id            = $_REQUEST['id'];
							 /********Generate Code****************/
							    
							 $info['table']    = "disposeassets";
							 $info["fields"]   = array("*");
							 $info['where']    = "id='$Id'";
							 $res = $db->select($info);
								
							   
							 
							 $info1['table']    = "master";
							 $info1["fields"]   = array("*");
							 $info1['where']    = "ACode='".$res[0]["ACode"]."'";
							 $res1 = $db->select($info1);
								
															 
							 /**********************************************/
							    $info2['table']    = "master";
								$data2['ComID'] = $res1[0]['ComID'];  
								$data2['PurCost'] = $res1[0]['PurCost'];  
								$data2['InstCost'] = $res1[0]['InstCost'];  
								$data2['CarryCost'] = $res1[0]['CarryCost'];  
								$data2['OtherCost'] = $res1[0]['OtherCost'];  
								$data2['SerDate'] = $res1[0]['SerDate'];
								$data2['DefGroup'] = $res1[0]['DefGroup'];  
								$data2['SubGroup'] = $res1[0]['SubGroup'];  
								$data2['Department'] = $res1[0]['Department'];  
								$data2['Supplier'] = $res1[0]['Supplier'];  
								$data2['Location'] = $res1[0]['Location'];  
								$data2['UserName'] = $res1[0]['UserName'];  
								$data2['Project'] = $res1[0]['Project'];  
								$data2['Rate'] = $res1[0]['Rate'];  
								$data2['Life'] = $res1[0]['Life'];  
								$data2['Method'] = $res1[0]['Method'];  
								$data2['Narration'] = $res1[0]['Narration'];  
								$data2['Warranty'] = $res1[0]['Warranty'];  						
								$data2['OpAssets'] =$res1[0]['OpAssets'];  
								$data2['OpDep'] = $res1[0]['OpDep'];  
								$data2['Dispose'] = 0;//$_REQUEST['Dispose'];  
							    $data2['ACode'] = $res1[0]['ACode']; 
								$info2['data']     =  $data2;
								$info2['where'] = "id='".$res1[0]["id"]."'";							
								 $db->update($info2);
							 
							     $Acode =$res1[0]['ACode'];
							 
						 /******************Delete*********************************/
		                  
							 unset($info);
							 unset($data);
							 $info3['table']    = "details";	
							 $data3["fields"]= array("*");						
						     $info3['where']    = " ComID='".$res1[0]["ComID"]."' AND Debit>0 AND  ACode='".$res1[0]["ACode"]."'";
							 $info3[$data] = $data3;
							
							 $res3 = $db->select($info3);
							
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
						     $data['where']    = " ComID='".$res1[0]["ComID"]."' AND  ACode='".$res1[0]["ACode"]."'";							
						     $db->delete($info['table'],$data['where']);
							 
							 
							 //Delete from Disposeassets
							    unset($info);
								unset($data);
							 $info['table']    = "disposeassets";
							 $data['where']    = " ComID='".$res1[0]["ComID"]."' AND  ACode='".$res1[0]["ACode"]."'";							
							 $db->delete($info['table'],$data['where']);
						/*******************End of Delete********************************/
								
								//Debit-Save Details Table
								unset($info);
								unset($data);
								$info['table']    = "details";
								$data['ComID'] = $res1[0]['ComID'];  
								$data['Ref'] = $res1[0]['Ref'];  
								$data['TDate'] = $res3[0]['TDate'];  
								$data['ACode'] = $Acode ;
								$TCost =$res1[0]['PurCost']+$res1[0]['InstCost']+$res1[0]['CarryCost']+$res1[0]['OtherCost'];
								$data['Debit'] =$TCost;  
								
								$info['data']     =  $data;
								$db->insert($info);		
								
								
								//DebDep-Save Details Table							
								$year = date("Y", strtotime($res1[0]["SerDate"]));
		                        $mon_date= date("m-d",strtotime($res1[0]["SerDate"]));
								
								 if($res1[0]["Method"]=="SLM")
								   {
								   
								    for($life=1;$life<=$res1[0]["Life"];$life++)
									{
										unset($info);
								        unset($data);
										$info['table']    = "details";
										$data['ComID'] = $res1[0]['ComID'];  
										$data['Ref'] = $res1[0]['Ref'];  
										$data['TDate'] =$year+($life-1)."-".$mon_date;//$_REQUEST["SerDate"];//-$life;  
										$data['ACode'] = $Acode ;
										$data['DepDebit'] =round($TCost/$res1[0]["Life"],2);  
										$info['data']     =  $data;
										$db->insert($info);	
									}	
								   }
								   
								   
								    if($res1[0]["Method"]=="RBM")
								   {
								      $DepDebit =0;
									  $inc = 0;
									  $tlife= $res1[0]["Life"];
								    for($life=1;$life<=$tlife;$life++)
									{
											unset($info);
								            unset($data);
										$info['table']    = "details";
										$data['ComID'] = $res1[0]['ComID'];  
										$data['Ref'] = $res1[0]['Ref'];  
										$data['TDate'] =$year+($life-1)."-".$mon_date;//$_REQUEST["SerDate"];//+$tlife-$life;  
										$data['ACode'] = $Acode ;
										$DepDebit = ($TCost-$inc)*$res1[0]["Rate"]/100;  
										$inc =$inc+$DepDebit;
										
										$data['DepDebit'] =round($DepDebit,2);
										$info['data']     =  $data;
										$db->insert($info);	
									}	
								   }
								   
							
                           	include("../DisposeAssets/DisposeAssets_list.php");
						  
						   break;
						   
         case 'delete': 
		 
		 				 	$Id               = $_REQUEST['id'];
		                   //Extract ACode before delete
						 	 $info['table']    = "disposeassets";
							 $data["fields"]= array("*");
							 $info["data"]= $data;
						     $info['where']    = "id='$Id'";
							
							 $res = $db->select($info);
		 
		 
		 
						   $info['table']    = "disposeassets";
						   $data['where']    = "id='$Id'";
						  
						   if($Id)
						   {
						    $db->delete($info['table'],$data['where']);
						   }
						   
						   
						    //Delete from details
								unset($info);
								unset($data);
							 $info['table']    = "details";							
						     $data['where']    = " ComID='".$_SESSION["ComID"]."' AND  ACode='".$res[0]["ACode"]."'";							
						     $db->delete($info['table'],$data['where']);
							 
							 //Delete from master
							    unset($info);
								unset($data);
							 $info['table']    = "master";
							 $data['where']    = " ComID='".$_SESSION["ComID"]."' AND  ACode='".$res[0]["ACode"]."'";							
							 $db->delete($info['table'],$data['where']);
							 
						   
						   
				include("../DisposeAssets/DisposeAssets_list.php");
						   
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
		                    include("../DisposeAssets/DisposeAssets_list.php");
						   break; 
          case "search_DisposeAssets":
		                    $_REQUEST['page'] = 1;  
		  					$_SESSION["search"]="yes";
							$_SESSION['field_name'] = $_REQUEST['field_name'];
							$_SESSION["field_value"] = $_REQUEST['field_value'];
		  				    include("../DisposeAssets/DisposeAssets_list.php");
						   break; 
      
		  
							   
		  case "make_master":		
						$info["table"] = "master";
						$info["fields"] = array("*"); 
						$info["where"]   = "1 AND ACode='".$_REQUEST['ACode']."' limit 1";
						
						$res  =  $db->select($info);
						$res[0]['PurCost'] = $res[0]['PurCost']+$res[0]['InstCost']+$res[0]['CarryCost']+$res[0]['OtherCost'];
							
							
					    //Service date		
							 unset($info);
							 unset($data);
						 $info['table']    = "company";
						 $data["fields"]= array("*");
						 $info["data"]= $data;
						 $info['where']    = " id='".$_SESSION["ComID"]."'";
						 $res1 = $db->select($info);
						
						
						
						 $str='<table class="bdr" cellspacing="1" cellpadding="1">										  
						 <tr>
							 <td>Purchase Date</td>
							 <td> <input type="text" name="TDate1" id="TDate1"  value="'.$res[0]['SerDate'].'" class="textbox">
							 </td>
						 </tr>
						 <tr>
							 <td>Service Date</td>
							 <td> <input type="text" name="SerDate" id="SerDate"  value="'.$res1[0]['clDate'].'" class="textbox">
							 </td>
						 </tr>
						 <tr>
							 <td>Default Group</td>
							 <td> <input type="text" id="DefGroup"  value="'.$res[0]['DefGroup'].'" class="textbox" >
							 </td>
						 </tr>
						 <tr>
							 <td>Sub Group</td>
							 <td> 
							 <input type="text" name="SubGroup" id="SubGroup"  value="'.$res[0]['SubGroup'].'" class="textbox">
							 </td>
						 </tr>
						 <tr>
							 <td>Department</td>
							 <td> <input type="text" id="Department"  value="'.$res[0]['Department'].'"  class="textbox">
							 </td>
						 </tr>
						 <tr>
							 <td>User</td>
							 <td> <input type="text" id="UserName"  value="'.$res[0]['UserName'].'"  class="textbox">
							 </td>
						 </tr>
						 <tr>
							 <td>Location</td>
							 <td> <input type="text" id="Location"  value="'.$res[0]['Location'].'"  class="textbox">
							 </td>
						 </tr>
						 <tr>
							 <td>Supplier</td>
								<td> <input type="text" id="Supplier"  value="'.$res[0]['Supplier'].'"  class="textbox">
							 </td>
						 </tr>
						 <tr>
							 <td>Project</td>
							 <td> <input type="text" id="Project"  value="'.$res[0]['Project'].'" class="textbox">
							 </td>
						 </tr>
						 <tr>
							 <td>Warranty(Year)</td>
							 <td> <input type="text" name="Warranty" id="Warranty"  value="'.$res[0]['Warranty'].'" class="textbox"></td>
						 </tr>
						  <tr> <td>Purchase Cost</td>
								 <td> <input type="text" name="PurCost" id="PurCost" value="'.$res[0]['PurCost'].'" class="textbox" ></td>
							 </tr>
						   <tr>
							 <td>Method of Depreciation</td>
							 <td> <input type="text" name="Method" id="Method"  value="'.$res[0]['Method'].'" class="textbox"></td>
						 </tr>
						 <tr>
							 <td>Rate of Depreciation %</td>
							 <td> <input type="text" name="Rate" id="Rate" value="'.$res[0]['Rate'].'" class="textbox"></td>
						 </tr>
						 <tr>
							 <td>Useful life Years</td>
							 <td> <input type="text" name="Life" id="Life"  value="'.$res[0]['Life'].'" class="textbox"></td>
						 </tr>
					</table>';
							
				echo $str;
				break;
	 case "check_date":
	                             unset($info);
								 unset($data);
		   					 $info['table']    = "details";
							 $info["fields"]= array("DepDebit");							
						     $info['where']    = " ComID='".$_SESSION["ComID"]."' AND  '".$_REQUEST["TDate"]."'>=TDate AND ACode='".$_REQUEST["ACode"]."'";// AND DepDebit>0
							
							 $res = $db->select($info);
							
							
							 
							 if(count($res)>0)
							  {
							     $total = 0;
							     for($k=0;$k<count($res);$k++)
								 {
								    $total = $total+$res[$k]["DepDebit"];
								 }
							    echo  $total;  
							  }
							  else
							  {
							    echo "Disposal date must be greater than Purchase date";
							  } 	
							  break;	
  case "make_sgroup":   
				
						$info["table"] = "sgroup";
						$info["fields"] = array("*"); 
						$info["where"]   = "1 AND DefGroup='".$_REQUEST['DefGroup']."'   ORDER BY SubGroup ASC";
						$res  =  $db->select($info);
						$str='<select type="text" name="SubGroup" id="SubGroup"  class="textbox" onchange="getAssetCode(this.value)">
							  <option value="">--Select--</option>';								    
								foreach($res as  $key=>$each)
								{
								$sel="";
							 if($SubGroup==$each['SubGroup']) $sel="selected";
						$str=$str.'<option value="'.$each['SubGroup'].'" '.$sel.'>'.$each['SubGroup'].'</option>';
							 
								 }							 
						$str=$str.'</select>';
						echo $str;
  
					   break; 		
 case "make_asset_code":
 							$str='<select  name="ACode" id="ACode"  class="textbox" onchange="getHTML(this.value)">
							        <option value="">--Select--</option>';
							   
								unset($info);
								$info["table"] = "master";
								$info["fields"] = array("*"); 
								$info["where"]   = "1 AND SubGroup='".$_REQUEST['SubGroup']."'   ORDER BY ACode ASC";
								$res  =  $db->select($info);
							   
									foreach($res as  $key=>$each)
									{
									
									$info2["table"] = "details";
									$info2["fields"] = array("*"); 
									$info2["where"]   = "1 AND ComID='".$each['ComID']."' AND ACode='".$each['ACode']."' AND Credit>0 ";
									$resdetails2 =  $db->select($info2);
							   
									if(count($resdetails2)>0)
									{
									 
									}
									else
									{
									 
									
								
								$str=$str.'<option value="'.$each['ACode'].'" >'.$each['ACode'].'</option>';
								
								   }
									 }
								
							  $str=$str.'</select>';
							  
                             echo $str;
 
                       break; 					   					  				 
							    						    						    						   
						
	default   :    include("../DisposeAssets/DisposeAssets_list.php");		   
      
	   }
	   
?>
