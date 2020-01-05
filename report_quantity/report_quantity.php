<?php 
session_start();
set_time_limit(0);

include("../lib/class.db.php");
include("../common/config.php");
include("../common/lib.php");
include('../fpdf/fpdf.php');
include('report_quantity.lib.php');
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
		$this->Cell(0,10,"Inventory - Register Office Equipment",0,0,'C');
        $y = $this->GetY()+1;
        $this->SetY($y);
		 $this->SetFont('Times','',11);
		   $y = $this->GetY()+5;
        $this->SetY($y);
		 $this->Cell(0,10,"For the period ending-date: ".date("F j, Y",strtotime($_SESSION['clDate'])),0,0,'C');
		   $y = $this->GetY()+5;
        $this->SetY($y);
        $this->Cell(0,10,date("F j, Y",strtotime($_REQUEST["date_from"]))."-".date("F j, Y",strtotime($_REQUEST["date_to"])),0,0,'C');
	 /************************************************/
		 $this->SetY($this->GetY()+10);
		 $y =$this->GetY();
		 $this->Line(0,$y,$x+300,$y);
		$this->quantityReport($db,$_REQUEST);
		
		
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

			  

function quantityReport($db,$_req)
{

        $this->SetFont('Times','B',8);
		$x =0;
		$y = $this->GetY()+1;
		
		$this->SetXY($x,$y);
		$this->Write(6,"Group");
		$this->SetXY($x+25,$y);
		$this->Write(6,"Opening");
		$this->SetXY($x+40,$y);
		$this->Write(6,"Addition");
		$this->SetXY($x+60,$y);
		$this->Write(6,"Disposal");
		$this->SetXY($x+80,$y);
		$this->Write(6,"Total");
		$this->SetXY($x+100,$y);
		$this->Write(6,"Location");
		
		 $this->SetY($this->GetY()+5);
		 $y =$this->GetY();
		 $this->Line($x,$y,$x+300,$y);
		 //All SubGroup 
		$info["table"] = "master";
		$info["fields"] = array("distinct(SubGroup) as SubGroup");
		$info["where"]  = "1";
		$res = $db->select($info);
		
		for($j=0;$j<count($res);$j++)
		{
		    $x =0;
			if($j==0)
			{
			$y=$this->GetY()+4;
			}
			else
			{
		    $y = $this->GetY()+16;
			}
		    
			$this->SetXY($x,$y);
			$this->Write(6,$res[$j]["SubGroup"]);
			$this->SetXY($x+25,$y);
			  unset($info);
			unset($data);
			$info["table"] = "master";
			$info["fields"] = array("count(OpAssets) as OpAssetsCount");
			$info["where"]  = "1  AND SubGroup='".$res[$j]["SubGroup"]."' AND OpAssets>0";
			$resopen=$db->select($info);
			if(count($resopen)>0)
			{
				$openCount=$resopen[0]["OpAssetsCount"];
			}
			else
			{
				$openCount=0;
			}
			
			
			$this->Write(6,$openCount);
			$this->SetXY($x+40,$y);
			 unset($info);
			unset($data);
			$info["table"] = "master";
			$info["fields"] = array("count(Dispose) as AdditionCount");
			$info["where"]  = "1  AND SubGroup='".$res[$j]["SubGroup"]."' AND (Dispose=0 OR Dispose=1) AND OpAssets=0";
			$resaddition=$db->select($info);
			if(count($resaddition)>0)
			{
				$additionCount=$resaddition[0]["AdditionCount"];
			}
			else
			{
				$additionCount=0;
			}
			
			$this->Write(6,$additionCount);
			$this->SetXY($x+60,$y);
			 unset($info);
			unset($data);
			$info["table"] = "master";
			$info["fields"] = array("count(Dispose) as DisposeCount");
			$info["where"]  = "1  AND SubGroup='".$res[$j]["SubGroup"]."' AND Dispose=1";
			$resdis=$db->select($info);
			if(count($resdis)>0)
			{
				$disCount=$resdis[0]["DisposeCount"];
			}
			else
			{
				$disCount=0;
			}			
			$this->Write(6,$disCount);
			$this->SetXY($x+80,$y);
			$totalCount=$openCount+$additionCount-$disCount;
			$this->Write(6,$totalCount);
				 if($y1>150)
					{
					
					$this->SetXY($x,4);
					$this->AddPage();
					$x=0;
					$y = $this->GetY();
					}	
						unset($info);
						unset($data);
					$info["table"] = "locationin";
					$info["fields"] = array("*");
					$info["where"]  = "1";
					$resloc = $db->select($info);
		
					$count=count($resloc);
					$counter=-1;
				for($i=0;$i<$count;$i++)
				{
				  
					 $k=$i%25;
				
					if($k==0)
					{
					  //$count=$count+1;
					  if($i==0)
					   {
					    $y = $y+4;
					   }
					   else
					   {
					    $y=$y+16;		
					   }
					  $x =80;
					  $m=$k;		
					}
					else
					{
					 
					 $m=$k;		
					}	
					
		
				   $x1=$x+8+$m*8;
				   $y1=$y;
				    if($y1>150)
					{
					
					$this->SetXY($x,4);
					$this->AddPage();
					$x=80;
					$y = $this->GetY();
					 $x1=$x+8;
				     $y1=$y;
					}		   
				   
				   
				   $this->drawRect($x1,$y1-8,8,8);	
				   $this->SetXY($x1,$y1-8);
				  // if($k==0)
					//{	
					//  $this->Write(6,"Loc");			
					//}
				  // else
					//{    
					     $counter= $counter+1;
						 $this->Write(6,$resloc[$counter]["Location"]);			
					//}				
				   $this->drawRect($x1,$y1,8,8);
				   $this->SetXY($x1,$y1);
				  // if($k==0)
					//{	
					 //   $this->Write(6,"Num");
					        
					//}		
					//else
					//{
					        unset($info);
						    unset($data);
					        $info["table"] = "master";
							$info["fields"] = array("count(Location) as LocationCount");
							$info["where"]  = "1 AND Location='".$resloc[$counter]["Location"]."' AND SubGroup='".$res[$j]["SubGroup"]."' AND Dispose<>1";
					        $resnum=$db->select($info);
							$LocationCount="";
							if(count($resnum)>0)
							{
								$LocationCount=$resnum[0]["LocationCount"];
							}
							else
							{
								$LocationCount="";
							}
							$this->Write(6,$LocationCount);		
									
					//}				
					
				   	
				}//End of Room
				 $y =$this->GetY();
		         $this->Line(0,$y+8,$x+300,$y+8);
			}//End of Group		
}		
	  
	function drawRect($x1,$y1,$x2,$y2)
	{
	  $this->Rect($x1,$y1,$x2,$y2);	
	}	  
	  
	  

}

$cmd = $_REQUEST['cmd'];

switch($cmd)
{
    case "make_report_quantity":
        //view pdf file
        $pdf=new PDF('L','mm','A4');
        $header = array('Time');
        $pdf->SetFont('Times','',11);
        $pdf->AddPage();
        $pdf->BasicTable($header,$db,$_REQUEST);
        $pdf->Output();
      
        break;


	case "report_quantity_editor":
	   

		include("report_quantity_editor.php");
		break;
			
		

	default :
		include("report_quantity_editor.php");

		break;

                    }

                    ?>
