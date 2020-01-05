<?php 
session_start();
set_time_limit(0);

include("../common/lib.php");
include("../lib/class.db.php");
include("../common/config.php");
include('../fpdf/fpdf.php');
include('report_asset.lib.php');
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
    function BasicTable($header,$db)
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
		$this->Cell(0,10,"List of Asset",0,0,'C');
		$this->SetFont('Times','',11);
		$y = $this->GetY()+5;
		$this->SetY($y);
		$this->Cell(0,10,"For the period ending-date: ".date("F j, Y",strtotime($_SESSION['clDate'])),0,0,'C');
		
		
		$this->SetFont('Times','',11);
		$y = $this->GetY()+5;
		$this->SetY($y);
		$this->Cell(0,10,date("F j, Y"),0,0,'C');
		/************************************************/
       $x = 50;
		
		$info["table"] = "dgroup";
		$info["fields"] = array("*");
		$info["where"]  = "1 ORDER BY DefGroup ASC";
		$resdef = $db->select($info);
		
		$y = $this->GetY()+10;
        $this->SetXY($x,$y);
        $this->Write(6,"DefGroup");
        //Header of Table
       
        $this->SetXY($x+40,$y);
        $this->Write(6,"Rate");


        $this->SetXY($x+70,$y);
        $this->Write(6,"Method");


        $this->SetXY($x+100,$y);
        $this->Write(6,"Life");
		
		 $this->SetY($this->GetY()+5);
		 $y =$this->GetY();
		 $this->Line($x,$y,$x+110,$y);
		
		for($i=0;$i<count($resdef);$i++)
		{
		
		    $y = $this->GetY()+4;
			$this->SetXY($x,$y);
			$this->Write(6,$resdef[$i]["DefGroup"]);
			//Header of Table
		   
			$this->SetXY($x+40,$y);
			$this->Write(6,$resdef[$i]["Rate"]);
	
	
			$this->SetXY($x+70,$y);
			$this->Write(6,$resdef[$i]["Method"]);
	
	
			$this->SetXY($x+100,$y);
			$this->Write(6,$resdef[$i]["Life"]);
			
			 $this->SetY($this->GetY()+5);
			 $y =$this->GetY();
			 $this->Line($x,$y,$x+110,$y);
		    
			unset($info);
			$info["table"] = "sgroup";
			$info["fields"] = array("*");
			$info["where"]  = "1 AND DefGroup='".$resdef[$i]["DefGroup"]."' ORDER BY SubGroup ASC";
			$ressub = $db->select($info);
			
			$y = $this->GetY()+4;
			$this->SetXY($x+10,$y);
			$this->Write(6,"SubGroup");
			for($j=0;$j<count($ressub);$j++)
			{
					 $y = $this->GetY()+4;
					$this->SetXY($x+10,$y);
					$this->Write(6,$ressub[$j]["SubGroup"]);
					
					
					
						 if($y>250)
					{
					$this->SetXY($x,4);
					$this->AddPage();
					
					$y = $this->GetY()+4;
					$this->SetXY($x+10,$y);
					$this->Write(6,"SubGroup");
					
					}	
					
			
			}
			
			

					 if($y>250)
					{
					$this->SetXY($x,4);
					$this->AddPage();
					
					$y = $this->GetY()+10;
					$this->SetXY($x,$y);
					$this->Write(6,"DefGroup");
					//Header of Table
				   
					$this->SetXY($x+40,$y);
					$this->Write(6,"Rate");
			
			
					$this->SetXY($x+70,$y);
					$this->Write(6,"Method");
			
			
					$this->SetXY($x+100,$y);
					$this->Write(6,"Life");
					
					 $this->SetY($this->GetY()+5);
					 $y =$this->GetY();
					 $this->Line($x,$y,$x+110,$y);
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


    case "make_report_asset":
              


        //view pdf file
        $pdf=new PDF('P','mm','A4');
        $header = array('Time');
        $pdf->SetFont('Times','',11);
        $pdf->AddPage();
        $pdf->BasicTable($header,$db);
        $pdf->Output();
      
        break;


        case "report_asset_editor":
           

            include("report_asset_editor.php");
            break;


		default :
			include("report_asset_editor.php");

			break;



                    }

                    ?>
