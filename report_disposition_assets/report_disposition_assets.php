<?php 
session_start();
set_time_limit(0);

include("../lib/class.db.php");
include("../common/config.php");
include("../common/lib.php");
include('../fpdf/fpdf.php');
include('report_disposition_assets.lib.php');
     /*
        Check  session , if not exists log out
     */
if(empty($_SESSION['userid'])&&empty($_SESSION['password']))
{    
    session_destroy();
    Header("Location:../login/login.php");
}

class PDF extends FPDF
{
    function BasicTable($header,$db,$_req)
    {
    
		$this->SetFont('Times','B',16);
		$y = $this->GetY()+1;
		$this->SetY($y);
		$this->Cell(0,10,$_SESSION["ComName"],0,0,'C');
		$this->SetFont('Times','',11);
		$y = $this->GetY()+5;
		$this->SetY($y);
		$this->Cell(0,10,$_SESSION["Address1"],0,0,'C');
		$y = $this->GetY()+5;
		$this->SetY($y);
		$this->Cell(0,10,$_SESSION["Address2"],0,0,'C');
		$y = $this->GetY()+5;
		$this->SetFont('Times','B',11);
		$this->SetY($y);
		$this->Cell(0,10,"Disposition Asset",0,0,'C');
		$this->SetFont('Times','',11);
		$y = $this->GetY()+5;
		$this->SetY($y);
		$this->Cell(0,10,"For the period ending-date: ".date("F j, Y",strtotime($_SESSION['clDate'])),0,0,'C');
		
		
		$this->SetFont('Times','',11);
		$y = $this->GetY()+5;
		$this->SetY($y);
		$this->Cell(0,10,date("F j, Y",strtotime($_REQUEST["Assetat"])),0,0,'C');
		/************************************************/	
		
		if($_REQUEST['option']=='dispossed_asset')
		{  
			$this->dispossed_asset($db,$_REQUEST);
		}
		elseif($_REQUEST['option']=='journal_entry')
		{   
			$this->journal_entry($db,$_REQUEST);
		}
		
    }
	
    function Footer()
    {
        //Go to 1.5 cm from bottom
        $this->SetY(-15);
        //Select Arial italic 8
        $this->SetFont('Arial','I',8);
        //Print centered page number
        $this->Cell(0,10,'Copyright @2008,TVS Auto Bangladesh Ltd.',0,0,'C');
    }

			  

function dispossed_asset($db,$_req)
{  
	   $info["table"] = "disposeassets left outer join  master on (disposeassets.ACode=master.ACode) 
	                            left outer join  details on (details.ACode=master.ACode)";
	   $info["fields"] = array("disposeassets.*","master.*","details.*");
	   $info["where"]  = "1 AND disposeassets.ComID=master.ComID AND  details.ComID=master.ComID AND details.Debit>0";
	   $res= $db->select($info); 
		
		
		$topName= true;
		$Debit = 0;
		$DepDebit= 0;
		$BookValue= 0;
		$NetProceeds= 0;
		$GainLoss= 0;
		$ReasonForSale= 0;
		 
		 $headerflag=true;
		 $totalflag= true;
		for($i=0;$i<count($res);$i++)
		{
		      unset($info);
			  unset($data);
		   $info["table"] = "details";
		   $info["fields"] = array("sum(DepDebit) as DepDebit");
		   $info["where"]  = "1  AND details.ACode='".$res[$i]["ACode"]."' AND details.ComID='".$res[$i]["ComID"]."'";
		
		   $resdetails = $db->select($info);
		
		   if($headerflag==true)
		     {
					$headerflag=false;
					$this->SetFont('Times','',7);
					
					$x=0;
					$y = $this->GetY()+10;
					$this->SetXY($x,$y);
					$this->Write(6,"Code");
					//Header of Table
				   
					$this->SetXY($x+20,$y);
					$this->Write(6,"SubGroup");
			
			
					$this->SetXY($x+45,$y);
					$this->Write(6,"Ref");
			
			
					$this->SetXY($x+58,$y);
					$this->Write(6,"Dep. Date");
					
					
					
					$this->SetXY($x+72,$y);
					$this->Write(6,"Dispo.Date");
					
					$this->SetXY($x+97,$y);
					$this->Write(6,"Cost");
					
					$this->SetXY($x+107,$y);
					$this->Write(6,"Acc.Dep");
					
					$this->SetXY($x+120,$y);
					$this->Write(6,"BookValue");
					
					$this->SetXY($x+140,$y);
					$this->Write(6,"NetProceeds");
					
					$this->SetXY($x+155,$y);
					$this->Write(6,"Gain/-Loss");
					
					$this->SetXY($x+180,$y);
					$this->Write(6,"ReasonForSale");					
					
					 $this->SetY($this->GetY()+5);
					 $y =$this->GetY();
					 $this->Line($x,$y,$x+205,$y);
					 
			  }
			  
			  //********Main************
			        $x=0;
					$y = $this->GetY()+5;
					$this->SetXY($x,$y);
					$this->Write(6,$res[$i]["ACode"]);
					//Header of Table
				   
					$this->SetXY($x+20,$y);
					$this->Write(6,$res[$i]["SubGroup"]);
			
			
					$this->SetXY($x+45,$y);
					$this->Write(6,$res[$i]["Ref"]); 
			
			
					$this->SetXY($x+58,$y);
					$this->Write(6,$res[$i]["SerDate"]); 
					
					
					
					$this->SetXY($x+72,$y);
					$this->Write(6,$res[$i]["TDate"]);
					
					$this->SetXY($x+97,$y);
					$this->Write(6,$res[$i]["Debit"]);
					
					$this->SetXY($x+107,$y);
					$this->Write(6,$resdetails[0]["DepDebit"]);
					
					$this->SetXY($x+120,$y);
					$this->Write(6,$res[$i]["BookValue"]);
					
					$this->SetXY($x+140,$y);
					$this->Write(6,$res[$i]["NetProceeds"]);
					
					$this->SetXY($x+155,$y);
					$this->Write(6,$res[$i]["GainLoss"]);
					
					$this->SetXY($x+180,$y);
					$this->Write(6,$res[$i]["ReasonForSale"]);
					
					$Debit =$Debit+$res[$i]["Debit"];
					$DepDebit= $DepDebit+$resdetails[0]["DepDebit"];
					$BookValue=$BookValue+$res[$i]["BookValue"];
					$NetProceeds=$NetProceeds+$res[$i]["NetProceeds"];
					$GainLoss= $GainLoss+$res[$i]["NetProceeds"];
					
					
					//************End of main************
			  
			        if($y>200)
					{   $this->AddPage();
					    $x=0;
						$y = $this->GetY()+5;
						$this->SetXY($x,$y);
						$this->Write(6,"Code");
						//Header of Table
						
						$this->SetXY($x+20,$y);
						$this->Write(6,"SubGroup");
						
						
						$this->SetXY($x+45,$y);
						$this->Write(6,"Ref");
						
						
						$this->SetXY($x+58,$y);
						$this->Write(6,"Dep. Date");
						
						
						
						$this->SetXY($x+72,$y);
						$this->Write(6,"Dispo.Date");
						
						$this->SetXY($x+97,$y);
						$this->Write(6,"Cost");
						
						$this->SetXY($x+107,$y);
						$this->Write(6,"Acc.Dep");
						
						$this->SetXY($x+120,$y);
						$this->Write(6,"BookValue");
						
						$this->SetXY($x+140,$y);
						$this->Write(6,"NetProceeds");
						
						$this->SetXY($x+155,$y);
						$this->Write(6,"Gain/-Loss");
						
						$this->SetXY($x+180,$y);
						$this->Write(6,"ReasonForSale");
						
						$this->SetY($this->GetY()+5);
						$y =$this->GetY();
						$this->Line($x,$y,$x+205,$y);
					}
			  
			  
			 
			  
		}//End of for loop
		
			if($totalflag==true)
				 {
	  
	                 $this->SetY($this->GetY()+5);
					 $y =$this->GetY();
					 $this->Line($x,$y,$x+205,$y);
					 
					$x=0;
					$y = $this->GetY()+1;
					$this->SetXY($x,$y);
					$this->Write(6,"Grand Total");
					//Header of Table
					
					$this->SetXY($x+97,$y);
					$this->Write(6,$Debit);
					
					$this->SetXY($x+107,$y);
					$this->Write(6,$DepDebit);
					
					$this->SetXY($x+120,$y);
					$this->Write(6,$BookValue);
					
					$this->SetXY($x+140,$y);
					$this->Write(6,$NetProceeds);
					
					$this->SetXY($x+155,$y);
					$this->Write(6,$GainLoss);
				  }		
		             
               
}//End of Disposal

function journal_entry($db,$_req)
{
      $info["table"] = "disposeassets left outer join  master on (disposeassets.ACode=master.ACode) 
	                            left outer join  details on (details.ACode=master.ACode)";
	   $info["fields"] = array("disposeassets.*","master.*","details.*");
	   $info["where"]  = "1 AND disposeassets.ComID=master.ComID AND  details.ComID=master.ComID AND details.Debit>0";
	   $res= $db->select($info); 
		
		
		$topName= true;
		
		 
		 $headerflag=true;
		
		for($i=0;$i<count($res);$i++)
		{
		      unset($info);
			  unset($data);
		   $info["table"] = "details";
		   $info["fields"] = array("sum(DepDebit) as DepDebit");
		   $info["where"]  = "1  AND details.ACode='".$res[$i]["ACode"]."' AND details.ComID='".$res[$i]["ComID"]."'";
		
		   $resdetails = $db->select($info);
		
		   if($headerflag==true)
		     {
					$headerflag=false;
					$this->SetFont('Times','',7);
					
					$x=0;
					$y = $this->GetY()+10;
					$this->SetXY($x,$y);
					$this->Write(6,"Date & Refference");
					//Header of Table
					$this->SetXY($x+58,$y);
					$this->Write(6,"Particulars");
					
					$this->SetXY($x+155,$y);
					$this->Write(6,"Debit");
					
					$this->SetXY($x+180,$y);
					$this->Write(6,"Credit");					
					
					 $this->SetY($this->GetY()+5);
					 $y =$this->GetY();
					 $this->Line($x,$y,$x+205,$y);
					 
			  }
			  
			  //********Main************
			        $x=1;
					$y = $this->GetY()+11;
					$this->SetXY($x,$y);
					$this->Rect($x,$y,208,40,$style='');
					$this->Cell(10,5,$res[$i]["SerDate"]);
					$this->SetXY($x,$y+5);
					$this->Cell(10,5,$res[$i]["Ref"]);
					//Header of Table
					$this->SetXY($x+58,$y);
					$this->Cell(10,5,"Bank/Cash");
					$this->SetXY($x+58,$y+5);
					$this->Cell(10,5,"Accumulated Depreciation");
					$this->SetXY($x+58,$y+10);
					$this->Cell(10,5,$res[$i]["ACode"]." ".$res[$i]["SubGroup"]);
					$this->SetXY($x+58,$y+20);
					$this->Cell(10,5,"Gain/-Loss");
					$this->SetXY($x+58,$y+30);
					$this->Cell(10,5,"Total");
					
					$this->SetXY($x+155,$y);
					$this->Cell(10,5,$res[$i]["NetProceeds"]);
					$this->SetXY($x+155,$y+5);
					$this->Cell(10,5,$resdetails[0]["DepDebit"]);
					$total = $res[$i]["NetProceeds"]+$resdetails[0]["DepDebit"];
					$this->SetXY($x+155,$y+30);
					$this->Cell(10,5,floor($total));
					
					$this->SetXY($x+180,$y+10);
					$this->Cell(10,5,$res[$i]["Debit"]);	
					$this->SetXY($x+180,$y+20);
					$this->Cell(10,5,$res[$i]["GainLoss"]);	
					$total = $res[$i]["Debit"]+$res[$i]["GainLoss"];
					$this->SetXY($x+180,$y+30);
					$this->Cell(10,5,$total);
			  
			          
			        if($y>200)
					{   $this->AddPage();
					    $x=0;
						$y = $this->GetY()+5;
						$this->SetXY($x,$y);
				    }		
			  
			  
					
					//************End of main************
			  
			    
			  
		}//End of for loop
		
	}		
               
}

$cmd = $_REQUEST['cmd'];
switch($cmd)
{


    case "make_report_disposition_assets":
              


        //view pdf file
        $pdf=new PDF('P','mm','A4');
        $header = array('Time');
        $pdf->SetFont('Times','',11);
        $pdf->AddPage();
        $pdf->BasicTable($header,$db,$_REQUEST);
        $pdf->Output();
      
        break;


        case "report_disposition_assets_editor":
           

            include("report_disposition_assets_editor.php");
            break;
			
		case "make_asset_aquisition_item":
		        $group = $_REQUEST['Group'];
				if($group=="ACode")
				{
				   unset($info);
					$info["table"] = "master";
					$info["fields"] = array("*"); 
					$info["where"]   = "1    ORDER BY ACode ASC";
					$res  =  $db->select($info);
										
					 $str = '<select  name="ACode" id="ACode"  class="textbox" SIZE="20" MULTIPLE>';
							  
					foreach($res as  $key=>$each)
					{
						$str = $str.'<option value="'.$each['ACode'].'"  >'.$each['ACode'].'</option>';
					}
					$str = $str.'</select>';
				
				    echo  $str;
				
				
				
				}
				elseif($group=="DefGroup")
				{
					unset($info);
					$info["table"] = "dgroup";
					$info["fields"] = array("*"); 
					$info["where"]   = "1    ORDER BY DefGroup ASC";
					$res  =  $db->select($info);
										
					 $str = '<select  name="DefGroup" id="DefGroup"  class="textbox" SIZE="20" MULTIPLE>';
							  
					foreach($res as  $key=>$each)
					{
						$str = $str.'<option value="'.$each['DefGroup'].'"  >'.$each['DefGroup'].'</option>';
					}
					$str = $str.'</select>';
				
				    echo  $str;
			    }		
				elseif($group=="SubGroup")
				{
					unset($info);
					$info["table"] = "sgroup";
					$info["fields"] = array("*"); 
					$info["where"]   = "1    ORDER BY SubGroup ASC";
					$res  =  $db->select($info);
										
					 $str = '<select  name="SubGroup" id="SubGroup"  class="textbox" SIZE="20" MULTIPLE>';
							  
					foreach($res as  $key=>$each)
					{
						$str = $str.'<option value="'.$each['SubGroup'].'"  >'.$each['SubGroup'].'</option>';
					}
					$str = $str.'</select>';
				
				    echo  $str;
				
				
				
				}
				elseif($group=="Department")
				{
				   unset($info);
					$info["table"] = "dept";
					$info["fields"] = array("*"); 
					$info["where"]   = "1    ORDER BY Department ASC";
					$res  =  $db->select($info);
										
					 $str = '<select  name="Department" id="Department"  class="textbox" SIZE="20" MULTIPLE>';
							  
					foreach($res as  $key=>$each)
					{
						$str = $str.'<option value="'.$each['Department'].'"  >'.$each['Department'].'</option>';
					}
					$str = $str.'</select>';
				
				    echo  $str;
				
				
				}
				elseif($group=="Location")
				{
					unset($info);
					$info["table"] = "locationin";
					$info["fields"] = array("*"); 
					$info["where"]   = "1    ORDER BY Location ASC";
					$res  =  $db->select($info);
										
					 $str = '<select  name="Location" id="Location"  class="textbox" SIZE="20" MULTIPLE>';
							  
					foreach($res as  $key=>$each)
					{
						$str = $str.'<option value="'.$each['Location'].'"  >'.$each['Location'].'</option>';
					}
					$str = $str.'</select>';
				
				    echo  $str;
				
				}
				elseif($group=="UserName")
				{
						unset($info);
					$info["table"] = "usern";
					$info["fields"] = array("*"); 
					$info["where"]   = "1    ORDER BY UserName ASC";
					$res  =  $db->select($info);
										
					 $str = '<select  name="UserName" id="UserName"  class="textbox" SIZE="20" MULTIPLE>';
							  
					foreach($res as  $key=>$each)
					{
						$str = $str.'<option value="'.$each['UserName'].'"  >'.$each['UserName'].'</option>';
					}
					$str = $str.'</select>';
				
				    echo  $str;
				
				
				}
				elseif($group=="Project")
				{
					unset($info);
					$info["table"] = "proj";
					$info["fields"] = array("*"); 
					$info["where"]   = "1    ORDER BY Project ASC";
					$res  =  $db->select($info);
										
					 $str = '<select  name="Project" id="Project"  class="textbox" SIZE="20" MULTIPLE>';
							  
					foreach($res as  $key=>$each)
					{
						$str = $str.'<option value="'.$each['Project'].'"  >'.$each['Project'].'</option>';
					}
					$str = $str.'</select>';
				
				    echo  $str;
				
				}
				else
				{
				}
				
				
		
		      break;	


		default :
			include("report_disposition_assets_editor.php");

			break;



                    }

                    ?>
