<?php 
session_start();
set_time_limit(0);

include("../lib/class.db.php");
include("../common/config.php");
include("../common/lib.php");
include('../fpdf/fpdf.php');
include('report_assets_history.lib.php');
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
		$this->Cell(0,10,"Asset History",0,0,'C');
		$this->SetFont('Times','',11);
		$y = $this->GetY()+5;
		$this->SetY($y);
		$this->Cell(0,10,"For the period ending-date: ".date("F j, Y",strtotime($_SESSION['clDate'])),0,0,'C');
		
		
		$this->SetFont('Times','',11);
		$y = $this->GetY()+5;
		$this->SetY($y);
		$this->Cell(0,10,date("F j, Y"),0,0,'C');
		/************************************************/


	
		$option = $_REQUEST['option'];
		if($option =="IndividualAssets")
		{		
			$info["table"] = "master left outer join company on(master.ComID=company.id) 
			                      left outer join details on(master.ACode=details.ACode )";
		    $info["fields"] = array("master.*","company.opDate","company.clDate","details.Ref","details.TDate");
			$info["where"]  = "1 AND master.ComID=details.ComID   AND master.ACode='".$_REQUEST["ACode"]."' AND details.Debit>0 ORDER BY DefGroup ASC";		
			$res = $db->select($info);
			
		}
		if($option =="AllAssets")
		{
			$info["table"] = "master left outer join company on(master.ComID=company.id) 
			                      left outer join details on(master.ACode=details.ACode )";
		    $info["fields"] = array("master.*","company.opDate","company.clDate","details.Ref","details.TDate");
			$info["where"]  = "1 AND master.ComID=details.ComID   AND details.Debit>0 ORDER BY DefGroup ASC";				
			$res = $db->select($info);
		}
		
		$this->SetFont('Times','',7);
		
		
		 $this->SetY($this->GetY()+12);
		 $y =$this->GetY();
		 $this->Line(0,$y,$x+205,$y);
		
		for($i=0;$i<count($res);$i++)
		{
		
		    $x=0;
			$y = $this->GetY()+5;
			$y2=$y;
			$this->SetXY($x,$y);
			$this->Write(6,"Assets Code:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["ACode"]);
			
		
		    $x=0;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Referance:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["Ref"]);
			
			
			$x=0;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Accquire Date:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["TDate"]);
		   
			$x=0;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Service Date:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["SerDate"]);
	
	
			$x=0;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Default Group:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["DefGroup"]);
	
	         $x=0;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Sub Group:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["SubGroup"]);
		
		    $x=0;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Department:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["Department"]);
			
			$x=0;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Supplier:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["Supplier"]);
		   
			$x=0;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"User Name:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["UserName"]);
	
	
			$x=0;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Location:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["Location"]);
	
	        $x=0;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Project:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["Project"]);
	         /**************************/
			 
			$x=100;
			$y = $y2;
			$this->SetXY($x,$y);
			$this->Write(6,"Purchases Cost:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["PurCost"]);
			
		
		    $x=100;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"InstallationCost:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["InstCost"]);
			
			
			$x=100;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Carrying Cost:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["CarryCost"]);
		   
			$x=100;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Other Cost:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["OtherCost"]);
	
	       $tcost=$res[$i]["PurCost"]+$res[$i]["InstCost"]+$res[$i]["CarryCost"]+$res[$i]["OtherCost"];
			$x=100;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Total Cost:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$tcost);
	
	         $x=100;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Rate%:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["Rate"]);
		
		    $x=100;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Life-Years:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["Life"]);
			
			$x=100;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Method:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["Method"]);
		   
			$x=100;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Warranty-Years:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["Warranty"]);
	
	
			$x=100;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,"Narration:");
			$x=$x+40;
			$y = $this->GetY();
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i]["Narration"]);
	
	        
			$this->SetY($this->GetY()+12);
			$y =$this->GetY();
			$this->Line(0,$y,$x+205,$y); 
			 
			

					 if($y>200)
					{
					$this->SetXY($x,4);
					$this->AddPage();
					
					$x=0;
					$y = $this->GetY()+10;
					$this->SetXY($x,$y);
					
					}	
		
		
		}
	  
	  
     /************************************************/



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

}				  


$cmd = $_REQUEST['cmd'];

switch($cmd)
{
    case "make_report_assets_history":
        //view pdf file
        $pdf=new PDF('P','mm','A4');
        $header = array('Time');
        $pdf->SetFont('Times','',11);
        $pdf->AddPage();
        $pdf->BasicTable($header,$db,$_REQUEST);
        $pdf->Output();
      
        break;


	case "report_assets_history_editor":
	   

		include("report_assets_history_editor.php");
		break;
			
		

	default :
		include("report_assets_history_editor.php");

		break;

                    }

                    ?>
