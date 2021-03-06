<?php 
session_start();
set_time_limit(0);
include("../lib/class.db.php");
include("../common/config.php");
include("../common/lib.php");
include('../fpdf/fpdf.php');
include('report_asset_aquisition.lib.php');
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
      function Header()
    {
       
					
			
    }


    function BasicTable($header,$db,$_req)
    {
        $this->SetFont('Times','B',11);        
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
		$this->SetFont('Times','B',11);
		$y = $this->GetY()+5;
		$this->SetY($y);
		$this->Cell(0,10,"Asset Aquisition",0,0,'C');
		$this->SetFont('Times','',11);
		$y = $this->GetY()+5;
		$this->SetY($y);
		$this->Cell(0,10,"For the period ending-date: ".date("F j, Y",strtotime($_SESSION['clDate'])),0,0,'C');
		$this->SetFont('Times','',11);
		$y = $this->GetY()+1;
		$this->SetY($y);
		$this->Cell(0,20,date("F j, Y",strtotime($_REQUEST["Assetat"])),0,0,'C');
		/************************************************/	
	
	
		
		if($_REQUEST['Group']=='Project')
		{   if(isset($_REQUEST['Project']))
			$where = " AND master.Project='".$_REQUEST['Project']."'";
			$orderby = "master.Project";
			$selected = "Project";
		}
		elseif($_REQUEST['Group']=='UserName')
		{   if(isset($_REQUEST['UserName']))
			$where = " AND master.UserName='".$_REQUEST['UserName']."'";
			$orderby = "master.UserName";
			$selected = "UserName";
		}
		elseif($_REQUEST['Group']=='Location')
		{   if(isset($_REQUEST['Location']))
			$where = " AND master.Location='".$_REQUEST['Location']."'";
			$orderby = "master.Location";
			$selected = "Location";
		}
		elseif($_REQUEST['Group']=='Department')
		{   if(isset($_REQUEST['Department']))
			$where = " AND master.Department='".$_REQUEST['Department']."'";
			$orderby = "master.Department";
			$selected = "Department";
		}
		if($_REQUEST['Group']=='SubGroup')
		{   if(isset($_REQUEST['SubGroup']))
			$where = " AND master.SubGroup='".$_REQUEST['SubGroup']."'";
			$orderby = "master.SubGroup";
			$selected = "SubGroup";
		}
		elseif($_REQUEST['Group']=='DefGroup')
		{  if(isset($_REQUEST['DefGroup']))
			$where = " AND master.DefGroup='".$_REQUEST['DefGroup']."'";
			$orderby = "master.DefGroup";
			$selected = "DefGroup";
		}
		elseif($_REQUEST['Group']=='ACode')
		{
		   if(isset($_REQUEST['ACode']))
			$where = " AND master.ACode='".$_REQUEST['ACode']."'";
			$orderby = "master.ACode";
			$selected = "ACode";
		}
		
		$this->assetsAcquisitionInfo($db,$_REQUEST,$where,$orderby,$selected);
		$this->assetsDisposeInfo($db,$_REQUEST,$where,$orderby,$selected);
		
    }
	
    function Footer()
    {
        //Go to 1.5 cm from bottom
        $this->SetY(-15);
        //Select Arial italic 8
        $this->SetFont('Arial','I',8);
        //Print centered page number
         $this->Cell(0,10,'Copyright @'.$_SESSION["ComName"] .'.',0,0,'C');
    }

			  

function assetsAcquisitionInfo($db,$_req,$where,$orderby,$selected)
{
         $groupName=$_REQUEST["Group"];
		
		$info["table"] = "master left outer join company on(master.ComID=company.id) ";
		$info["fields"] = array("master.*","company.opDate","company.clDate");
		$info["where"]  = "1 $where GROUP BY master.$groupName ORDER BY $orderby DESC";
		
		$resgroup = $db->select($info);
		$headerflag=true;
		  $totalopening=0;
		  $totalcost=0;
		  $totalqnty=0;
		  $totalbookvalue = 0;
		  $totaldepvalue = 0;
		
       for($k=0;$k<count($resgroup);$k++)
	   {
         unset($info);
        $info["table"] = "master left outer join company on(master.ComID=company.id) ";
		$info["fields"] = array("master.*","company.opDate","company.clDate");
		$info["where"]  = "1 $where AND master.$groupName='".$resgroup[$k][$selected]."' ORDER BY $orderby ASC";
		//GROUP BY master.DefGroup
		$res = $db->select($info);
		
		
		$topName= true;
		$subtotalopening=0;
		$subtotalcost=0;
		$subtotalqnty=0;
		$subtotalbookvalue = 0; 
		$subtotaldepvalue = 0;
		 
		for($i=0;$i<count($res);$i++)
		{
		
		      unset($info);
			  unset($data);
		   $info["table"] = "details";
		   $info["fields"] = array("*");
		   $info["where"]  = "1  AND details.ACode='".$res[$i]["ACode"]."' AND details.ComID='".$res[$i]["ComID"]."' AND details.Debit>0";
		
		   $resdetails = $db->select($info);
		   
		     unset($info);
			  unset($data);
		   $info["table"] = "disposeassets";
		   $info["fields"] = array("*");
		   $info["where"]  = "1  AND disposeassets.ACode='".$res[$i]["ACode"]."' AND disposeassets.ComID='".$res[$i]["ComID"]."'";
		 
		   $resdispose = $db->select($info);
		   if(count($resdispose)==0)
		   {
		   $subflag = true;
		   $totalflag = true;
		
		   if($headerflag==true)
		{
		$headerflag=false;
		$this->SetFont('Times','',7);
		$x=0;
		$y = $this->GetY()+15;
        $this->SetXY($x,$y);
        $this->Write(6,"Group");
        //Header of Table
       
       $this->SetXY($x+25,$y-2);
        $this->Cell(10,5,"Date of");
         $this->SetXY($x+25,$y);
        $this->Cell(10,5,"Purchase");



        $this->SetXY($x+42,$y-2);
        $this->Cell(10,5,"Year Ending");
        $this->SetXY($x+42,$y);
        $this->Cell(10,5,"Date");


        $this->SetXY($x+57,$y-2);
        $this->Cell(10,5,"Opening");
        $this->SetXY($x+57,$y);
        $this->Cell(10,5,"Balance");
		
		$this->SetXY($x+75,$y-2);
        $this->Cell(10,5,"Acquisition");
       
		
		$this->SetXY($x+90,$y-2);
        $this->Cell(10,5,"Acc. Depres");
		$this->SetXY($x+90,$y+1);
        $this->Cell(10,5,$_REQUEST["Assetat"]);
		
		$this->SetXY($x+108,$y);
        $this->Write(6,"Bk Value");
		
		$this->SetXY($x+120,$y);
        $this->Write(6,"Rate");
		
		$this->SetXY($x+127,$y);
        $this->Write(6,"LifeYrs");
		
		$this->SetXY($x+136,$y);
        $this->Write(6,"Method");
		
		$this->SetXY($x+146,$y);
        $this->Write(6,"Qty.");
		
		$this->SetXY($x+152,$y);
        $this->Write(6,"W.P.Yrs.");
		
		
		$this->SetXY($x+166,$y);
        $this->Write(6,"W.E.Date");
		
		$this->SetXY($x+180,$y);
        $this->Write(6,"W.Rem./(O)Mon");
		
		 $this->SetY($this->GetY()+5);
		 $y =$this->GetY();
		 $this->Line($x,$y,$x+205,$y);
		 //********************************Asset Aquired***************************/
	      $this->SetY($this->GetY()+5);
		 $y =$this->GetY();
		 $this->Write(8,"Asset Aquired");
		}
		
		   if($topName== true)
		   {
		    $x=0;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i][$selected]);
			//Header of Table
			$topName=false;
			}
		
		    $x=0;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["ACode"]);
			//Header of Table
		   
			$this->SetXY($x+25,$y);
			$this->Write(6,$resdetails[0]["TDate"]);
	
	
			$this->SetXY($x+42,$y);
			$this->Write(6,$res[$i]["SerDate"]);
	   
	        $this->SetXY($x+57,$y);
			$opening=$res[$i]["OpAssets"]; 
			$this->Write(6,number_format($opening, 2, '.', ','));
	        
	
			$this->SetXY($x+75,$y);
			$cost=$res[$i]["PurCost"]+$res[$i]["InstCost"]+$res[$i]["CarryCost"]+$res[$i]["OtherCost"];
			if($opening>0)
			{
			  $cost = 0;
			  $costfordepre= $opening;
			}
			else
			{
			   $costfordepre=$cost;
			}
			$this->Write(6,number_format($cost, 2, '.', ','));
			
			
			
			unset($info);
			unset($data);
		   $info["table"] = "details";
		   $info["fields"] = array("sum(DepDebit) as DepDebit");
		   $info["where"]  = "1  AND details.ACode='".$res[$i]["ACode"]."' AND details.ComID='".$res[$i]["ComID"]."' AND TDate<='".$_REQUEST["Assetat"]."'";
		
		   $resdetails1 = $db->select($info);
		    if(empty($resdetails1[0]["DepDebit"]))
		   { 
		    $resdetails1[0]["DepDebit"]=0;
		   }
			
		   $this->SetXY($x+90,$y);
           $this->Write(6,number_format($resdetails1[0]["DepDebit"], 2, '.', ','));
			
			$this->SetXY($x+106,$y);
            $this->Write(6,number_format($costfordepre-$resdetails1[0]["DepDebit"], 2, '.', ','));
			
			$subtotalopening = $subtotalopening+$opening;
			$subtotalcost=$subtotalcost+$cost;
			$subtotalqnty=$subtotalqnty+1;
			$subtotalbookvalue = $subtotalbookvalue +$costfordepre-$resdetails1[0]["DepDebit"];
			$subtotaldepvalue =$subtotaldepvalue+$resdetails1[0]["DepDebit"];
			
			$totalopening = $totalopening+$opening;
			$totalcost=$totalcost+$cost;
			$totalqnty=$totalqnty+1;
			$totalbookvalue = $totalbookvalue +$costfordepre-$resdetails1[0]["DepDebit"];
			$totaldepvalue =$totaldepvalue+$resdetails1[0]["DepDebit"];
			
			
			$this->SetXY($x+120,$y);
			$this->Write(6,$res[$i]["Rate"]."%");
			
			/*if($res[$i]["Method"]=="NDM")
			{
			$this->SetXY($x+127,$y);
			$this->Write(6,"0");
			}
			else
			{
			 $this->SetXY($x+127,$y);
			$this->Write(6,floor(100/$res[$i]["Rate"]));
			
			}*/
			
			$this->SetXY($x+127,$y);
			$this->Write(6,$res[$i]["Life"]);
			
			$this->SetXY($x+136,$y);
			$this->Write(6,$res[$i]["Method"]);
			
			$this->SetXY($x+146,$y);
			$this->Write(6,"1");
			
			$this->SetXY($x+152,$y);
			$this->Write(6,$res[$i]["Warranty"]);
			  $wy=0;
			  unset($arrdate);
			
			  if(count($resdetails)>0)
			  {
			  $arrdate  = explode("-",$resdetails[0]["TDate"]);
			   
			  //$arrdate1  =$res[$i]["SerDate"]
			  if($res[$i]["Warranty"]>0)
			  {
			   $wy= trim($res[$i]["Warranty"]);
			   
			   $mon =$wy*12;
			   //$wyear   = date("Y-m-d",mktime(0, 0, 0,$arrdate[1],$arrdate[2],$arrdate[0]+$wy));
			   $wyear   = $arrdate[0]+$wy."-".$arrdate[1]."-".$arrdate[2];

			  }
			  else
			  {
			   $mon =0;
			   //$wyear   = date("Y-m-d",mktime(0, 0, 0,$arrdate[1],$arrdate[2],$arrdate[0]));
			    $wyear   = $arrdate[0]."-".$arrdate[1]."-".$arrdate[2];
			  }
			  }
			$this->SetXY($x+166,$y);
			$this->Write(6,$wyear);
			
			$a = explode("-",$wyear);
			$b = explode("-",$_REQUEST["Assetat"]);
			$t = floor(($a[0]-$b[0])*12+($a[1]-$b[1])+(($a[2]-$b[2])/30)); 
			$this->SetXY($x+180,$y);
			$this->Write(6,$t);
		    
			

					 if($y>250)
					{
					$this->SetXY($x,4);
					$this->AddPage();
					
					$x=0;
					$y = $this->GetY()+10;
					$this->SetXY($x,$y);
					$this->Write(6,"Group");
					//Header of Table
				   
				   $this->SetXY($x+25,$y-2);
					$this->Cell(10,5,"Date of");
					 $this->SetXY($x+25,$y);
					$this->Cell(10,5,"Purchase");
			
			
			
					$this->SetXY($x+42,$y-2);
					$this->Cell(10,5,"Year Ending");
					$this->SetXY($x+42,$y);
					$this->Cell(10,5,"Date");
			
			
					$this->SetXY($x+57,$y-2);
					$this->Cell(10,5,"Opening");
					$this->SetXY($x+57,$y);
					$this->Cell(10,5,"Balance");
					
					$this->SetXY($x+75,$y-2);
					$this->Cell(10,5,"Acquisition");
				   
					
					$this->SetXY($x+90,$y-2);
					$this->Cell(10,5,"Acc. Depres");
					$this->SetXY($x+90,$y+1);
					$this->Cell(10,5,$_REQUEST["Assetat"]);
					
					$this->SetXY($x+108,$y);
					$this->Write(6,"Bk Value");
					
					$this->SetXY($x+120,$y);
					$this->Write(6,"Rate");
					
					$this->SetXY($x+127,$y);
					$this->Write(6,"LifeYrs");
					
					$this->SetXY($x+136,$y);
					$this->Write(6,"Method");
					
					$this->SetXY($x+146,$y);
					$this->Write(6,"Qty.");
					
					$this->SetXY($x+152,$y);
					$this->Write(6,"W.P.Yrs.");
					
					
					$this->SetXY($x+166,$y);
					$this->Write(6,"W.E.Date");
					
					$this->SetXY($x+180,$y);
					$this->Write(6,"W.Rem./(O)Mon");
					
					}
					
					
					
		      }//End of Depration Book value check
			}//End of Group
			   if($subflag==true)
			   {
			    $this->SetY($this->GetY()+5);
				$y =$this->GetY();
				$this->Line($x,$y,$x+205,$y);
					
				$x=0;
				$y = $this->GetY()+1;
				$this->SetXY($x,$y);
				$this->Write(6,"SubTotal");
				
				$this->SetXY($x+57,$y);
				$this->Write(6, number_format($subtotalopening, 2, '.', ','));
				
				$this->SetXY($x+75,$y);
				$this->Write(6, number_format($subtotalcost, 2, '.', ','));
				
				$this->SetXY($x+90,$y);
                $this->Write(6,number_format($subtotaldepvalue, 2, '.', ','));
				
				$this->SetXY($x+106,$y);
				$this->Write(6,number_format($subtotalbookvalue, 2, '.', ','));
				
				$this->SetXY($x+146,$y);
				$this->Write(6, number_format($subtotalqnty, 2, '.', ','));
				$subflag=false;
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
					$this->Write(6,"Total");
					
					$this->SetXY($x+57,$y);
					$this->Write(6, number_format($totalopening, 2, '.', ','));
					
					$this->SetXY($x+75,$y);
					$this->Write(6, number_format($totalcost, 2, '.', ','));
					
					$this->SetXY($x+90,$y);
                    $this->Write(6,number_format($totaldepvalue, 2, '.', ','));
					
					$this->SetXY($x+106,$y);
					$this->Write(6,number_format($totalbookvalue, 2, '.', ','));
					
					$this->SetXY($x+146,$y);
					$this->Write(6, number_format($totalqnty, 2, '.', ','));
                }

}//End of Acqusition

function assetsDisposeInfo($db,$_req,$where,$orderby,$selected)
{
         $groupName=$_REQUEST["Group"];
		
		$info["table"] = "master left outer join company on(master.ComID=company.id) ";
		$info["fields"] = array("master.*","company.opDate","company.clDate");
		$info["where"]  = "1 $where GROUP BY master.$groupName ORDER BY $orderby DESC";
		
		$resgroup = $db->select($info);
		 $headerflag=true;
		  $totalopening=0;
		  $totalcost=0;
		  $totalqnty=0;
		  $totalbookvalue = 0;
		$totaldepvalue = 0;
       for($k=0;$k<count($resgroup);$k++)
	   {
         unset($info);
        $info["table"] = "master left outer join company on(master.ComID=company.id) ";
		$info["fields"] = array("master.*","company.opDate","company.clDate");
		$info["where"]  = "1 $where AND master.$groupName='".$resgroup[$k][$selected]."' ORDER BY $orderby ASC";
		//GROUP BY master.DefGroup
		$res = $db->select($info);
		$topName=true;
		$subtotalopening=0;
		$subtotalcost=0;
		$subtotalqnty=0;
		$subtotalbookvalue = 0;
		 $subtotaldepvalue = 0;
		for($i=0;$i<count($res);$i++)
		{
		
		      unset($info);
			  unset($data);
		   $info["table"] = "details";
		   $info["fields"] = array("*");
		   $info["where"]  = "1  AND details.ACode='".$res[$i]["ACode"]."' AND details.ComID='".$res[$i]["ComID"]."' AND details.Debit>0";
		
		   $resdetails = $db->select($info);
		   
		     unset($info);
			  unset($data);
		   $info["table"] = "disposeassets";
		   $info["fields"] = array("*");
		   $info["where"]  = "1  AND disposeassets.ACode='".$res[$i]["ACode"]."' AND disposeassets.ComID='".$res[$i]["ComID"]."'";
		 
		   $resdispose = $db->select($info);
		   if(count($resdispose)>0)
		   {
		   $subflag = true;
		   $totalflag = true;
            
		   if($headerflag==true)
		{
		$headerflag= false;
		   		$this->SetFont('Times','',7);
		$x=0;
		$y = $this->GetY()+10;
         $this->SetXY($x,$y);
        $this->Write(6,"Group");
        //Header of Table
       
       $this->SetXY($x+25,$y-2);
        $this->Cell(10,5,"Date of");
         $this->SetXY($x+25,$y);
        $this->Cell(10,5,"Purchase");



        $this->SetXY($x+42,$y-2);
        $this->Cell(10,5,"Year Ending");
        $this->SetXY($x+42,$y);
        $this->Cell(10,5,"Date");


        $this->SetXY($x+57,$y-2);
        $this->Cell(10,5,"Opening");
        $this->SetXY($x+57,$y);
        $this->Cell(10,5,"Balance");
		
		$this->SetXY($x+75,$y-2);
        $this->Cell(10,5,"Acquisition");
       
		
		$this->SetXY($x+90,$y-2);
        $this->Cell(10,5,"Acc. Depres");
		$this->SetXY($x+90,$y+1);
        $this->Cell(10,5,$_REQUEST["Assetat"]);
		
		$this->SetXY($x+108,$y);
        $this->Write(6,"Bk Value");
		
		$this->SetXY($x+120,$y);
        $this->Write(6,"Rate");
		
		$this->SetXY($x+127,$y);
        $this->Write(6,"LifeYrs");
		
		$this->SetXY($x+136,$y);
        $this->Write(6,"Method");
		
		$this->SetXY($x+146,$y);
        $this->Write(6,"Qty.");
		
		$this->SetXY($x+152,$y);
        $this->Write(6,"W.P.Yrs.");
		
		
		$this->SetXY($x+166,$y);
        $this->Write(6,"W.E.Date");
		
		$this->SetXY($x+180,$y);
        $this->Write(6,"W.Rem./(O)Mon");
		
		 $this->SetY($this->GetY()+5);
		 $y =$this->GetY();
		 $this->Line($x,$y,$x+205,$y);
		 //********************************Asset Aquired***************************/
	      $this->SetY($this->GetY()+5);
		 $y =$this->GetY();
		 $this->Write(8,"Asset Dispose");
		
		}	 		   
		    if($topName== true)
		   {
		    $x=0;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i][$selected]);
			//Header of Table
			$topName=false;
		    }
		    $x=0;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["ACode"]);
			//Header of Table
		   
  	$this->SetXY($x+25,$y);
			$this->Write(6,$resdetails[0]["TDate"]);


			$this->SetXY($x+42,$y);
			$this->Write(6,$res[$i]["SerDate"]);
	
	
			 $this->SetXY($x+57,$y);
			$opening=$res[$i]["OpAssets"]; 
			$this->Write(6,number_format($opening, 2, '.', ','));
	        
	
			$this->SetXY($x+75,$y);
			$cost=$res[$i]["PurCost"]+$res[$i]["InstCost"]+$res[$i]["CarryCost"]+$res[$i]["OtherCost"];
			if($opening>0)
			{
			  $cost = 0;
			  $costfordepre= $opening;
			}
			else
			{
			   $costfordepre=$cost;
			}
			$this->Write(6,number_format($cost, 2, '.', ','));
			
			
			
			unset($info);
			unset($data);
		   $info["table"] = "details";
		   $info["fields"] = array("sum(DepDebit) as DepDebit");
		   $info["where"]  = "1  AND details.ACode='".$res[$i]["ACode"]."' AND details.ComID='".$res[$i]["ComID"]."' AND TDate<'".$_REQUEST["Assetat"]."'";
		
		   $resdetails1 = $db->select($info);
		    if(empty($resdetails1[0]["DepDebit"]))
		   { 
		    $resdetails1[0]["DepDebit"]=0;
		   }
			
		   $this->SetXY($x+90,$y);
           $this->Write(6,number_format($resdetails1[0]["DepDebit"], 2, '.', ','));
			
			$this->SetXY($x+106,$y);
            $this->Write(6,number_format($costfordepre-$resdetails1[0]["DepDebit"], 2, '', ','));
			
			$subtotalopening = $subtotalopening+$opening;
			$subtotalcost=$subtotalcost+$cost;
			$subtotalqnty=$subtotalqnty+1;
			$subtotalbookvalue = $subtotalbookvalue +$costfordepre-$resdetails1[0]["DepDebit"];
			$subtotaldepvalue =$subtotaldepvalue+$resdetails1[0]["DepDebit"];
			
			$totalopening = $totalopening+$opening;
			$totalcost=$totalcost+$cost;
			$totalqnty=$totalqnty+1;
			$totalbookvalue = $totalbookvalue +$costfordepre-$resdetails1[0]["DepDebit"];
			$totaldepvalue =$totaldepvalue+$resdetails1[0]["DepDebit"];
			
			
			$this->SetXY($x+120,$y);
			$this->Write(6,$res[$i]["Rate"]."%");
			
			/*if($res[$i]["Method"]=="NDM")
			{
			$this->SetXY($x+127,$y);
			$this->Write(6,"0");
			}
			else
			{
			 $this->SetXY($x+127,$y);
			$this->Write(6,floor(100/$res[$i]["Rate"]));
			
			}*/
			
			$this->SetXY($x+127,$y);
			$this->Write(6,$res[$i]["Life"]);
			
			$this->SetXY($x+136,$y);
			$this->Write(6,$res[$i]["Method"]);
			
			$this->SetXY($x+146,$y);
			$this->Write(6,"1");
			
			$this->SetXY($x+152,$y);
			$this->Write(6,$res[$i]["Warranty"]);
			  $wy=0;
			  unset($arrdate);
			
			  if(count($resdetails)>0)
			  {
			  $arrdate  = explode("-",$resdetails[0]["TDate"]);
			   
			  //$arrdate1  =$res[$i]["SerDate"]
			  if($res[$i]["Warranty"]>0)
			  {
			   $wy= trim($res[$i]["Warranty"]);
			   
			   $mon =$wy*12;
			   //$wyear   = date("Y-m-d",mktime(0, 0, 0,$arrdate[1],$arrdate[2],$arrdate[0]+$wy));
			   $wyear   = $arrdate[0]+$wy."-".$arrdate[1]."-".$arrdate[2];

			  }
			  else
			  {
			   $mon =0;
			   //$wyear   = date("Y-m-d",mktime(0, 0, 0,$arrdate[1],$arrdate[2],$arrdate[0]));
			    $wyear   = $arrdate[0]."-".$arrdate[1]."-".$arrdate[2];
			  }
			  }
			$this->SetXY($x+166,$y);
			$this->Write(6,$wyear);
			
			$a = explode("-",$wyear);
			$b = explode("-",$_REQUEST["Assetat"]);
			$t = floor(($a[0]-$b[0])*12+($a[1]-$b[1])+(($a[2]-$b[2])/30)); 
			$this->SetXY($x+180,$y);
			$this->Write(6,$t);
		    
			

					 if($y>250)
					{
					$this->SetXY($x,4);
					$this->AddPage();
					
					$x=0;
					$y = $this->GetY()+10;
					$this->SetXY($x,$y);
					$this->Write(6,"Group");
					//Header of Table
				   
				   $this->SetXY($x+25,$y-2);
					$this->Cell(10,5,"Date of");
					 $this->SetXY($x+25,$y);
					$this->Cell(10,5,"Purchase");
			
			
			
					$this->SetXY($x+42,$y-2);
					$this->Cell(10,5,"Year Ending");
					$this->SetXY($x+42,$y);
					$this->Cell(10,5,"Date");
			
			
					$this->SetXY($x+57,$y-2);
					$this->Cell(10,5,"Opening");
					$this->SetXY($x+57,$y);
					$this->Cell(10,5,"Balance");
					
					$this->SetXY($x+75,$y-2);
					$this->Cell(10,5,"Acquisition");
				   
					
					$this->SetXY($x+90,$y-2);
					$this->Cell(10,5,"Acc. Depres");
					$this->SetXY($x+90,$y+1);
					$this->Cell(10,5,$_REQUEST["Assetat"]);
					
					$this->SetXY($x+108,$y);
					$this->Write(6,"Bk Value");
					
					$this->SetXY($x+120,$y);
					$this->Write(6,"Rate");
					
					$this->SetXY($x+127,$y);
					$this->Write(6,"LifeYrs");
					
					$this->SetXY($x+136,$y);
					$this->Write(6,"Method");
					
					$this->SetXY($x+146,$y);
					$this->Write(6,"Qty.");
					
					$this->SetXY($x+152,$y);
					$this->Write(6,"W.P.Yrs.");
					
					
					$this->SetXY($x+166,$y);
					$this->Write(6,"W.E.Date");
					
					$this->SetXY($x+180,$y);
					$this->Write(6,"W.Rem./(O)Mon");
					
					}
					
					
					
		      }//End of Depration Book value check
			}//End of Group
			   if($subflag==true)
			   {
			    $this->SetY($this->GetY()+5);
				$y =$this->GetY();
				$this->Line($x,$y,$x+205,$y);
					
				$x=0;
				$y = $this->GetY()+1;
				$this->SetXY($x,$y);
				$this->Write(6,"SubTotal");
				
				$this->SetXY($x+57,$y);
				$this->Write(6,number_format($subtotalopening, 2, '.', ','));
				
				$this->SetXY($x+75,$y);
				$this->Write(6,number_format($subtotalcost, 2, '.', ','));
				
				$this->SetXY($x+90,$y);
                $this->Write(6,number_format($subtotaldepvalue, 2, '.', ','));
				
				$this->SetXY($x+106,$y);
				$this->Write(6,number_format($subtotalbookvalue, 2, '.', ','));
				
				$this->SetXY($x+146,$y);
				$this->Write(6,number_format($subtotalqnty, 2, '.', ','));
				$subflag=false;
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
					$this->Write(6,"Total");
					
					$this->SetXY($x+57,$y);
					$this->Write(6, number_format($totalopening, 2, '.', ','));
					
					$this->SetXY($x+75,$y);
					$this->Write(6, number_format($totalcost, 2, '.', ','));
					
					$this->SetXY($x+90,$y);
                    $this->Write(6,number_format($totaldepvalue, 2, '.', ','));
					
					$this->SetXY($x+106,$y);
					$this->Write(6,number_format($totalbookvalue, 2, '.', ','));
					
					$this->SetXY($x+146,$y);
					$this->Write(6, number_format($totalqnty, 2, '.', ','));
                }

   
}//End of Dispose

}

$cmd = $_REQUEST['cmd'];
switch($cmd)
{


    case "make_report_asset_aquisition":
              


        //view pdf file
        $pdf=new PDF('P','mm','A4');
        $header = array('Time');
        $pdf->SetFont('Times','',11);
        $pdf->AddPage();
        $pdf->BasicTable($header,$db,$_REQUEST);
        $pdf->Output();
      
        break;


        case "report_asset_aquisition_editor":
           

            include("report_asset_aquisition_editor.php");
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
			include("report_asset_aquisition_editor.php");

			break;



                    }

                    ?>
