<?php 
session_start();
set_time_limit(0);

include("../lib/class.db.php");
include("../common/config.php");
include("../common/lib.php");
include('../fpdf/fpdf.php');
include('report_fixed_asset_schedule.lib.php');
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
		  $y = $this->GetY()+2;
        $this->SetY($y);
		 $this->Cell(0,20,"FIXED ASSETS STATEMENT",0,0,'C');
		 
		 
         $info['table']    = "currency";
         $info['fields']   = array("*");
		 $info['where']    =  "id='".$_REQUEST['currency']."'";
		$res  =  $db->select($info);

        $Id        = $res[0]['id'];
        $from_country = $res[0]['from_country'];
		$from_country_currency = $res[0]['from_country_currency'];
		$to_country = $res[0]['to_country'];
		$to_country_currency = $res[0]['to_country_currency'];

        $ratio_currency = $to_country_currency /$from_country_currency;
		 
		if($res[0]['alias_name']=="BDT")
		   {
		     $currency = "Taka";
		    } 
			else
			{
			 $currency ="Dollar";
			}
		 

		  $y = $this->GetY()+5;
       $this->SetY($y);
		 $this->Cell(0,20,"( In ".$to_country." ".$currency.")",0,0,'C');
		  $this->SetFont('Times','',11);
		   $y = $this->GetY()+1;
        $this->SetY($y);
		 $this->Cell(0,30,"For the period ending-date: ".date("F j, Y",strtotime($_SESSION['clDate'])),0,0,'C');
		 
		 
        $this->SetFont('Times','',11);
        $y = $this->GetY()+9;
       $this->SetY($y);
        $this->Cell(0,20,date("F j, Y",strtotime($_REQUEST["Assetat"])),0,0,'C');
		$this->SetY($y+5);
	 /************************************************/	
		
		if($_REQUEST['option']=='Project')
		{  
			$where = " AND master.Project='".$_REQUEST['Project']."'";
			$orderby = "master.Project";
			$selected = "Project";
		}
		elseif($_REQUEST['option']=='UserName')
		{   
			$where = " AND master.UserName='".$_REQUEST['UserName']."'";
			$orderby = "master.UserName";
			$selected = "UserName";
		}
		elseif($_REQUEST['option']=='Location')
		{   
			$where = " AND master.Location='".$_REQUEST['Location']."'";
			$orderby = "master.Location";
			$selected = "Location";
		}
		elseif($_REQUEST['option']=='Department')
		{  
			$where = " AND master.Department='".$_REQUEST['Department']."'";
			$orderby = "master.Department";
			$selected = "Department";
		}
		if($_REQUEST['option']=='SubGroup')
		{  
			$where = " AND master.SubGroup='".$_REQUEST['SubGroup']."'";
			$orderby = "master.SubGroup";
			$selected = "SubGroup";
		}
		elseif($_REQUEST['option']=='DefGroup')
		{ 
			$where = " AND master.DefGroup='".$_REQUEST['DefGroup']."'";
			$orderby = "master.DefGroup";
			$selected = "DefGroup";
		}
		elseif($_REQUEST['option']=='ACode')
		{
		  
			$where = " AND master.ACode='".$_REQUEST['ACode']."'";
			$orderby = "master.ACode";
			$selected = "ACode";
		}
		
		
		$this->assetsByMethod($db,$_REQUEST,$orderby,$selected,$ratio_currency);
		
		
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

			  

function assetsByMethod($db,$_req,$orderby,$selected,$ratio_currency)
{
		 $methodList=array("NDM","SLM","RBM");
		 $headerflag=true;
		 
        $g_op_balance=0;
        $g_purchase=0;
        $g_other=0;
        $g_total_cost=0;
        $g_book_value=0;
        $g_proceeds_dispose=0;
        $g_closing_balance=0;
		 
	foreach($methodList as $method)
	{
       
        $info["table"] = "master left outer join company on(master.ComID=company.id) ";
		$info["fields"] = array("master.*","company.opDate","company.clDate");
		$info["where"]  = "1 AND master.Method='".$method."' ORDER BY $orderby ASC";
		//GROUP BY master.DefGroup
		$res = $db->select($info);
		

		$topName= true;
        $s_op_balance=0;
        $s_purchase=0;
        $s_other=0;
        $s_total_cost=0;
        $s_book_value=0;
        $s_proceeds_dispose=0;
        $s_closing_balance=0;

		for($i=0;$i<count($res);$i++)
		{
		
		      unset($info);
			  unset($data);
		   $info["table"] = "details";
		   $info["fields"] = array("sum(DepDebit) as DepDebit");
		   $info["where"]  = "1  AND details.ACode='".$res[$i]["ACode"]."' AND details.ComID='".$res[$i]["ComID"]."' AND details.TDate<='".$_REQUEST["Assetat"]."' AND TDate>='".$_SESSION['opDate']."' AND TDate<='".$_SESSION['clDate']."'";
		
		   $resdetails = $db->select($info);
		   
		     unset($info);
			  unset($data);
		   $info["table"] = "disposeassets";
		   $info["fields"] = array("*");
		   $info["where"]  = "1  AND disposeassets.ACode='".$res[$i]["ACode"]."' AND disposeassets.ComID='".$res[$i]["ComID"]."' AND TDate>='".$_SESSION['opDate']."' AND TDate<='".$_SESSION['clDate']."'";
		 
		   $resdispose = $db->select($info);
		   
		   $subflag = true;
		   $totalflag = true;
		
		   if($headerflag==true)
		{
		$headerflag=false;
		$this->SetFont('Times','',7);
		 
		 $x=1;
		 $this->SetY($this->GetY()+10);
		 $y =$this->GetY();
		 
		 //$x2=$x+205;
		 //$y2=$y+10;
		 $this->Rect($x,$y,190,20,$style='');
		 
		 $this->Line($x+30,$y,$x+30,$y+20);
		// $this->Line($x+110,$y,$x+110,$y+20);
		 //$this->Line($x+120,$y,$x+120,$y+20);
		 //$this->Line($x+190,$y,$x+190,$y+20);
		// $this->Line($x+208,$y,$x+208,$y+20);
		 //$this->Line($x+30,$y+10,$x+110,$y+10);
		 $this->Line($x+30+20,$y+10,$x+170,$y+10);
		 //$this->Line($x+120,$y,$x+120,$y+20);
         $this->Line($x+30+20,$y,$x+30+20,$y+20);
		 $this->Line($x+30+40,$y+10,$x+30+40,$y+20);
		 $this->Line($x+30+60,$y+10,$x+30+60,$y+20);
		 $this->Line($x+30+80,$y+10,$x+30+80,$y+20);
		 $this->Line($x+30+110,$y+10,$x+30+110,$y+20);
		 $this->Line($x+30+140,$y,$x+30+140,$y+20);
		
		  $this->Line($x+30+80,$y,$x+30+80,$y+10);
		//$this->Line($x+120+17,$y+10,$x+120+17,$y+20);
		//$this->Line($x+120+34,$y+10,$x+120+34,$y+20);
	//	$this->Line($x+120+51,$y+10,$x+120+51,$y+20);
		
		
        $this->SetXY($x+10,$y+5);
		$this->Cell(20,10,"CATEGORY");

		$this->SetXY($x+30+40,$y+1);
		$this->Cell(20,10,"ACQUISITIONS");
		$this->SetXY($x+130,$y+1);
		$this->Cell(20,10,"DISPOSITION");
		
		
		$this->SetXY($x+30,$y+10);
		$this->Cell(5,10,"Opening Balance");
		$this->SetXY($x+30,$y+14);
		$this->Cell(5,10," A");//$_SESSION["opDate"]
		
		$this->SetXY($x+30+20,$y+10);
		$this->Cell(5,10,"Purchase B");
		
		$this->SetXY($x+30+40,$y+10);
		$this->Cell(5,10,"Other Source C");
		
		$this->SetXY($x+30+60,$y+10);
		$this->Cell(5,10,"Total D=A+B+C");
		
		$this->SetXY($x+30+80,$y+10);
		$this->Cell(5,10,"Book Value E");
		
		$this->SetXY($x+30+110,$y+10);
		$this->Cell(5,10,"Proceeds of Disposal F");
		
		$this->SetXY($x+30+140,$y+10);
		$this->Cell(5,10,"Closing Balance");
		$this->SetXY($x+30+140,$y+14);
		$this->Cell(5,10,"D-E");//$_REQUEST["Assetat"]
		
	//	$this->SetXY($x+110,$y+5);
	//	$this->Cell(20,10,"Rate");
		/*
		$this->SetXY($x+120+30,$y+1);
		$this->Cell(20,10,"Depreciation");
		
		
		
		$this->SetXY($x+120,$y+10);
		$this->Cell(5,10,"Opening as at");
		$this->SetXY($x+120,$y+14);
		$this->Cell(5,10,$_SESSION["opDate"]);
		
		$this->SetXY($$x+120+17,$y+10);
		$this->Cell(5,10,"For Period");
		
		$this->SetXY($x+120+34,$y+10);
		$this->Cell(5,10,"Adjustment");
		
		$this->SetXY($x+120+51,$y+10);
		$this->Cell(5,10,"Closing as at");
		$this->SetXY($x+120+51,$y+14);
		$this->Cell(5,10,$_REQUEST["Assetat"]);
		
		$this->SetXY($x+190,$y+1);
		$this->Cell(20,10,"Written Down ");
		$this->SetXY($x+190,$y+4);
		$this->Cell(20,10," Value as at ");
		$this->SetXY($x+190,$y+8);
		$this->Cell(20,10,$_REQUEST["Assetat"]);*/
		
		$x=0;
		$y = $this->GetY()+1;
        $this->SetXY($x,$y);
        
        //Header of Table

		}
		
		   if($topName== true)
		   {
		     $this->SetY($this->GetY()+5);
			 $y =$this->GetY();
			 $this->Write(8,"Method:$method");
		   
			//Header of Table*/
			$topName=false;
			}
		
		    $x=0;
			$y = $this->GetY()+5;
			$this->SetXY($x,$y);
			$this->Write(6,$res[$i][$selected]);//"ACode"
			//Header of Table
		    $op_balance = $res[$i]["OpAssets"];
			/*unset($info);
			   unset($data);
			   $info["table"] = "details left outer join  master on(details.ACode=master.ACode)";
			   $info["fields"] = array("sum(DepCredit) as DepCredit");
			   $info["where"]  = "1  AND master.ACode='".$res[$i]["ACode"]."' AND details.ComID='".$res[$i]["ComID"]."' AND details.DepCredit>0 AND details.TDate<'".$_SESSION['opDate']."'";// AND details.TDate<'".$_REQUEST["Assetat"]."'";
			   $resdepcredit = $db->select($info);
			   $op_balance_dep=$resdepcredit[0]["DepCredit"];
			   $op_balance =$op_balance +$op_balance_dep;*/
			
		    $op_balance =$op_balance*$ratio_currency;

			$this->SetXY($x+30,$y);
			$this->Write(6,number_format($op_balance, 2, '.', ','));

			
		   unset($info);
		   unset($data);
		   $info["table"] = "details";
		   $info["fields"] = array("Debit");
		   $info["where"]  = "1  AND details.ACode='".$res[$i]["ACode"]."' AND details.ComID='".$res[$i]["ComID"]."' AND Debit>0 AND TDate>='".$_SESSION['opDate']."' AND TDate<='".$_SESSION['clDate']."'";// AND details.TDate<'".$_REQUEST["Assetat"]."'";
		   $resdebit = $db->select($info);
           $purchase=$resdebit[0]["Debit"];
           if(empty($purchase))
           {
           $purchase = 0;
           }
		   $this->SetXY($x+30+20,$y);
  	       //$cost=$res[$i]["PurCost"]+$res[$i]["InstCost"]+$res[$i]["CarryCost"]+$res[$i]["OtherCost"];
  	       $purchase = $purchase*$ratio_currency;

		   $this->Write(6,number_format($purchase, 2, '.', ','));
              $other=0;
              $other=$other*$ratio_currency;
		    $this->SetXY($x+30+40,$y);
	        $this->Write(6,number_format($other, 2, '.', ','));
            $total_cost=  $op_balance  + $purchase +  $other;
		    $this->SetXY($x+30+60,$y);
		    $this->Write(6,number_format($total_cost, 2, '.', ','));
             unset($info);
		   unset($data);
		   $info["table"] = "disposeassets left outer join  master on(disposeassets.ACode=master.ACode)";
		   $info["fields"] = array("sum(BookValue) as BookValue");
		   $info["where"]  = "1  AND master.ACode='".$res[$i]["ACode"]."'  AND disposeassets.TDate<='".$_REQUEST["Assetat"]."' AND TDate>='".$_SESSION['opDate']."' AND TDate<='".$_SESSION['clDate']."'";// AND details.TDate<'".$_REQUEST["Assetat"]."'";
		
		   $resbookvalue = $db->select($info);
			$book_value =  $resbookvalue[0]["BookValue"];
            $book_value=$book_value*$ratio_currency;
		    $this->SetXY($x+30+80,$y);
		    $this->Write(6,number_format($book_value, 2, '.', ','));
			
		    unset($info);
		   unset($data);
		   $info["table"] = "disposeassets left outer join  master on(disposeassets.ACode=master.ACode)";
		   $info["fields"] = array("sum(NetProceeds ) as NetProceeds");
		   $info["where"]  = "1  AND master.ACode='".$res[$i]["ACode"]."' AND disposeassets.TDate<='".$_REQUEST["Assetat"]."' AND TDate>='".$_SESSION['opDate']."' AND TDate<='".$_SESSION['clDate']."'";// AND details.TDate<'".$_REQUEST["Assetat"]."'";
		
		   $resnetproceeds = $db->select($info);					
			$proceeds_dispose=$resnetproceeds[0]["NetProceeds"];
			if(empty($proceeds_dispose))
			{
			 $proceeds_dispose=0;
			}
			$proceeds_dispose =$proceeds_dispose*$ratio_currency;;
			$this->SetXY($x+30+110,$y);
			$this->Write(6,number_format($proceeds_dispose, 2, '.', ','));
			

		     
			$closing_balance =$total_cost-$book_value;
			$this->SetXY($x+30+140,$y);
			$this->Write(6,number_format($closing_balance, 2, '.', ','));
			

			/* unset($info);
			  unset($data);
		   $info["table"] = "details";
		   $info["fields"] = array("sum(DepDebit) as DepDebit");
		   $info["where"]  = "1  AND details.ACode='".$res[$i]["ACode"]."' AND details.ComID='".$res[$i]["ComID"]."' AND DepDebit>0 AND  details.TDate<'".$_REQUEST["Assetat"]."'";
			$resdetails = $db->select($info);
			$DepDebit = $resdetails[0]["DepDebit"];
			if(empty($DepDebit))
			{
			 $DepDebit=0;
			}
			
			$OpDep =  $DepDebit;
			$this->SetXY($x+120,$y);
			$this->Write(6,$OpDep);
			
			  unset($info);
			  unset($data);
		   $info["table"] = "details";
		   $info["fields"] = array("DepDebit");
		   $info["where"]  = "1  AND details.ACode='".$res[$i]["ACode"]."' AND details.ComID='".$res[$i]["ComID"]."' AND DepDebit>0 AND TDate='".$_REQUEST["Assetat"]."'";// AND details.TDate<'".$_REQUEST["Assetat"]."'";

		   $resdetails = $db->select($info);
		   $DepCredit = $resdetails[0]["DepDebit"];
		   $DepDebit = $DepCredit;
			if(empty($DepCredit))
			{
			 $DepCredit=0;
			}
			$this->SetXY($$x+120+17,$y);
			$this->Write(6,$DepCredit);
			
		   unset($info);
		   unset($data);
		   $info["table"] = "details";
		   $info["fields"] = array("DepCredit");
		   $info["where"]  = "1  AND details.ACode='".$res[$i]["ACode"]."' AND details.ComID='".$res[$i]["ComID"]."' AND DepCredit>0";
           $resDepCredit = $db->select($info);
           $DepCredit = 0 ;
		   if($resDepCredit[0]["DepCredit"]>0)
			{
                 $DepCredit =    $resDepCredit[0]["DepCredit"];
			}
            $this->SetXY($x+120+34,$y);
			$this->Write(6,$DepCredit);
			
			$close = $OpDep+$DepDebit-$DepCredit;
			$this->SetXY($x+120+51,$y);
			$this->Write(6,$close);
			
			$this->SetXY($x+185,$y);
			$this->Write(6,$costClose-$close);
				            */
				            
        $s_op_balance=$s_op_balance+$op_balance;
        $s_purchase=$s_purchase+$purchase;
        $s_other=$s_other+$other;
        $s_total_cost=$s_total_cost+$total_cost;
        $s_book_value=$s_book_value+$book_value;
        $s_proceeds_dispose=$s_proceeds_dispose+$proceeds_dispose;
        $s_closing_balance=$s_closing_balance+$closing_balance;
        
        $g_op_balance=$g_op_balance+$op_balance;
        $g_purchase=$g_purchase+$purchase;
        $g_other=$g_other+$other;
        $g_total_cost=$g_total_cost+$total_cost;
        $g_book_value=$g_book_value+$book_value;
        $g_proceeds_dispose=$g_proceeds_dispose+$proceeds_dispose;
        $g_closing_balance=$g_closing_balance+$closing_balance;
        
				            
				            
		    /*$cost_opening=$cost_opening+$OpAssets;
			$cost_addition=$cost_addition+$cost;
			$cost_disposal=$cost_disposal+$Credit;
			$cost_closing=$cost_closing+$costClose;
			$dep_opening=$dep_opening+$OpDep;
			$dep_period=$dep_period+$DepDebit;
			$dep_adjust=$dep_adjust+$DepCredit;
			$dep_closing=$dep_closing+$close;
			$written_down=$written_down+$costClose-$close;
			  */
			

					 if($y>250)
					{
					$this->SetXY($x,4);
					$this->AddPage();
					
					$x=0;
					$y = $this->GetY()+10;
					$this->SetXY($x,$y);
					
					}
					
					
					
		      
			}//End of Method
			   if($subflag==true)
			   {
			    $this->SetY($this->GetY()+5);
				$y =$this->GetY();
				$this->Line($x,$y,$x+190,$y);
					
				$x=0;
				$y = $this->GetY()+1;
				$this->SetXY($x,$y);
				$this->Write(6,"SubTotal");
				
                 $this->SetXY($x+30,$y);
		         $this->Write(6, number_format($s_op_balance, 2, '.', ','));

		          $this->SetXY($x+30+20,$y);
		          $this->Write(6,number_format($s_purchase, 2, '.', ','));

		    $this->SetXY($x+30+40,$y);
	        $this->Write(6,number_format($s_other, 2, '.', ','));

		    $this->SetXY($x+30+60,$y);
		    $this->Write(6,number_format($s_total_cost, 2, '.', ','));

		    $this->SetXY($x+30+80,$y);
		    $this->Write(6,number_format($s_book_value, 2, '.', ','));


			$this->SetXY($x+30+110,$y);
			$this->Write(6,number_format($s_proceeds_dispose, 2, '.', ','));



			$this->SetXY($x+30+140,$y);
			$this->Write(6,number_format($s_closing_balance, 2, '.', ','));
				
				

				
				
				/*$this->SetXY($x+120,$y);
				$this->Write(6,$dep_opening);			
				
				$this->SetXY($$x+120+17,$y);
				$this->Write(6,$dep_period);			
				
				$this->SetXY($x+120+34,$y);
				$this->Write(6,$dep_adjust);
				
				$this->SetXY($x+120+51,$y);
				$this->Write(6,$dep_closing);
				
				$this->SetXY($x+185,$y);
				$this->Write(6,$written_down);     */
				
				
				$subflag=false;
				}
			  
	}
	  if($totalflag==true)
				 {
	  
	                $this->SetY($this->GetY()+5);
					$y =$this->GetY();
					$this->Line($x,$y,$x+190,$y);
						
		            $x=0;
					$y = $this->GetY()+1;
					$this->SetXY($x,$y);
					$this->Write(6,"Grand Total");
					
       $this->SetXY($x+30,$y);
		         $this->Write(6, number_format($g_op_balance, 2, '.', ','));

		          $this->SetXY($x+30+20,$y);
		          $this->Write(6,number_format($g_purchase, 2, '.', ','));

		    $this->SetXY($x+30+40,$y);
	        $this->Write(6,number_format($g_other, 2, '.', ','));

		    $this->SetXY($x+30+60,$y);
		    $this->Write(6,number_format($g_total_cost, 2, '.', ','));

		    $this->SetXY($x+30+80,$y);
		    $this->Write(6, number_format($g_book_value, 2, '.', ','));


			$this->SetXY($x+30+110,$y);
			$this->Write(6,number_format($g_proceeds_dispose, 2, '.', ','));



			$this->SetXY($x+30+140,$y);
			$this->Write(6,number_format($g_closing_balance, 2, '.', ','));
					
					/*$this->SetXY($x+120,$y);
					$this->Write(6,$total_dep_opening);			
					
					$this->SetXY($$x+120+17,$y);
					$this->Write(6,$total_dep_period);			
					
					$this->SetXY($x+120+34,$y);
					$this->Write(6,$total_dep_adjust);
					
					$this->SetXY($x+120+51,$y);
					$this->Write(6,$total_dep_closing);
					
					$this->SetXY($x+185,$y);
					$this->Write(6,$total_written_down);  */
						
					
                }

}//End of Acqusition


}

$cmd = $_REQUEST['cmd'];

switch($cmd)
{
    case "make_report_fixed_asset_schedule":
        //view pdf file
        $pdf=new PDF('P','mm','A4');
        $header = array('Time');
        $pdf->SetFont('Times','',11);
        $pdf->AddPage();
        $pdf->BasicTable($header,$db,$_REQUEST);
        $pdf->Output();
      
        break;


	case "report_fixed_asset_schedule_editor":
	   

		include("report_fixed_asset_schedule_editor.php");
		break;
			
		

	default :
		include("report_fixed_asset_schedule_editor.php");

		break;

                    }

                    ?>
