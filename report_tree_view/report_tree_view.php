<?php 
session_start();
set_time_limit(0);

include("../common/lib.php");
include("../lib/class.db.php");
include("../common/config.php");
include('../fpdf/fpdf.php');
include('report_tree_view.lib.php');
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
		$this->Cell(0,10,"Tree View of Asset",0,0,'C');
		$this->SetFont('Times','',11);
		$y = $this->GetY()+5;
		$this->SetY($y);
		$this->Cell(0,10,"For the period ending-date: ".date("F j, Y",strtotime($_SESSION['clDate'])),0,0,'C');
		
		
		$this->SetFont('Times','',11);
		$y = $this->GetY()+5;
		$this->SetY($y);
		$this->Cell(0,10,date("F j, Y"),0,0,'C');
		/************************************************/
         $x=10;
		 $this->SetY($this->GetY()+10);
		 $y =$this->GetY();
		 $this->Line(0,$y,200,$y);
		
		
		
		
		$info["table"] = "dgroup";
		$info["fields"] = array("*");
		$info["where"]  = "1 ORDER BY DefGroup ASC";
		$resdef = $db->select($info);
		
		  $def = false;
		for($i=0;$i<count($resdef);$i++)
		{		
		   $def = true;
		 	unset($info);
		$info["table"] = "sgroup";
		$info["fields"] = array("*");
		$info["where"]  = "1 AND DefGroup='".$resdef[$i]["DefGroup"]."' ORDER BY DefGroup ASC";
		$ressub = $db->select($info);	
		
			$sub = false;
		for($j=0;$j<count($ressub);$j++)
		{		
		  $sub = true;
		unset($info);		
		$info["table"] = "master";
		$info["fields"] = array("*");
		$info["where"]  = "1 AND DefGroup='".$resdef[$i]["DefGroup"]."'  AND SubGroup='".$ressub[$j]["SubGroup"]."'   ORDER BY SubGroup ASC";
	
		$resmas = $db->select($info);
		 
			
		for($k=0;$k<count($resmas);$k++)
		{
		    if($def ==true)
			{
			   $y = $this->GetY()+10;
			   $x=10;
			   $this->SetXY($x,$y);
			   $this->Write(6,"+".$resdef[$i]["DefGroup"]);
			  $def=false;
			  if($i%2!=0)
			  {
			    $x1=$x;
				$y1=$y+10;
			  }
			  else
			  {
			    $x2=$x;
				$y2=$y+10;
			  }
			  if($i%2==0)
			  {
			   //$this->Line($x1,$y1,$x2,$y2);
			  }
			} 
		   if($sub ==true)
			{
			   $y = $this->GetY()+10;
			   $this->SetXY($x+10,$y);
			   $this->Write(6,$ressub[$j]["SubGroup"]);
			   $sub=false;
			}
		
		   
			//Header of Table
			$y=$y+10;
			$path =   "../logo/cida.JPG";
			$this->Image($path,$x+40,$y);
			$this->SetXY($x+60,$y);
            $this->Cell(10,5-4,"Canadian International");
            $this->SetXY($x+60,$y);
            $this->Cell(10,5+4,"Development Agency");
			
			$y = $this->GetY()+4;			
			$this->SetXY($x+100,$y);
			$this->Write(6,$resmas[$k]["ACode"]);
			
		   
			$this->SetXY($x+140,$y);
			$this->Write(6,$resmas[$k]["Method"]);
	
	
			//$this->SetXY($x+100,$y);
			//$this->Write(6,$resmas[$k]["Rate"]."%");
	
	
			$this->SetXY($x+160,$y);
			$this->Write(6,$resmas[$k]["Life"]);
			
			
					
					 if($y>250)
					{
                       $y = 4;
			           $x=10;
			           $this->SetXY($x,$y);
					   $this->SetXY($x,4);
					   $this->AddPage();
					}	
				
			  }

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


    case "make_report_tree_view":
              


        //view pdf file
        $pdf=new PDF('P','mm','A4');
        $header = array('Time');
        $pdf->SetFont('Times','',11);
        $pdf->AddPage();
        $pdf->BasicTable($header,$db);
        $pdf->Output();
      
        break;


        case "report_tree_view_editor":
           

            include("report_tree_view_editor.php");
            break;


		default :
			include("report_tree_view_editor.php");

			break;



                    }

                    ?>
