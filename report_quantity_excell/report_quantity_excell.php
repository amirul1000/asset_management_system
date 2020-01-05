<?php 
session_start();
set_time_limit(0);

include("../lib/class.db.php");
include("../common/config.php");
include("../common/lib.php");
include('../fpdf/fpdf.php');
include('report_quantity_excell.lib.php');
     /*
        Check  session , if not exists log out
     */
if(empty($_SESSION['userid'])&&empty($_SESSION['password']))
{    
    session_destroy();
    Header("Location:../login/login.php");
}

function subgroup($db,$_req)
{
   	        require_once "../php_writeexcel/class.writeexcel_workbook.inc.php";
			require_once "../php_writeexcel/class.writeexcel_worksheet.inc.php";

			$fname = tempnam("/tmp", "convert_orderlist_to_xcell.xls");
			$workbook = &new writeexcel_workbook($fname);
			
			////////////////////////////////////////
			 //All DefGroup
			$info3["table"] = "master";
			$info3["fields"] = array("distinct(DefGroup) as DefGroup");
			$info3["where"]  = "1";
			$res3 = $db->select($info3);
			
		for($d=0;$d<count($res3);$d++)
		{	
		
			$worksheet = "worksheet$d";
			$$worksheet = &$workbook->addworksheet();
			# Set the column width for columns 1, 2, 3 and 4
		//	$worksheet->set_column(0, 100, 25);


           # Create a format for the column headings
            $header =& $workbook->addformat();
            $header->set_bold();
            $header->set_font("Courier New");
            #$header->set_align('center');
            $header->set_align('vcenter');

            # Create a "vertical justification" format
            $format1 =& $workbook->addformat();
            $format1->set_align('center');

            # Create a "text wrap" format
            $format2 =& $workbook->addformat();
            $format2->set_text_wrap();

			$row = 0;
			$col = 4;
			/****************************************************************/
			     $$worksheet->write($row, $col,$_SESSION["ComName"]);
				 $row = $row +1;
				 $$worksheet->write($row, $col,$_SESSION["Address1"]);
				 $row = $row +1;
				 $$worksheet->write($row, $col,$_SESSION["Address2"]);
				 $row = $row +1;
				 $$worksheet->write($row, $col,"Inventory - ".$res3[$d]["DefGroup"], $header);
				 $row = $row +1;
				 $$worksheet->write($row, $col,"For the period ending-date: ".date("F j, Y",strtotime($_SESSION['clDate'])));
				 $row = $row +1;
				 $$worksheet->write(0, $col+10,"Print date:".date("F j, Y"));
				 $row = $row +1;
				 $storeforendrow=$row;
			/****************************************************************/
			  $col =0;
			$$worksheet->write($row, $col,"Name of Items",$format2);
			$$worksheet->write($row+1, $col+1,"Room->", $format2);
			$col = $col +1;
			$$worksheet->write($row, $col,"Opening", $format2);
			
			$col = $col +1;
			$$worksheet->write($row, $col,"Addition", $format2);
			//$col = $col +1;
			//$worksheet->write($row, $col,"Disposal", $header);
			$col = $col +1;
			$$worksheet->write($row, $col,"Total", $format2);
			$$worksheet->write($row, $col+8,"Location", $header);
			$col = $col +1;
		//	$worksheet->write($row, $col,"Location", $header);

             	        unset($info);
						unset($data);
					$info["table"] = "locationin";
					$info["fields"] = array("*");
					$info["where"]  = "1";
					$resloc = $db->select($info);

					$count=count($resloc);
                      $row=$row+1;
				for($i=0;$i<$count;$i++)
				{


			           $$worksheet->write($row, $col,$resloc[$i]["Location"],$format1);
                       $col=$col+1;
				}



		 //All SubGroup
		$info["table"] = "master";
		$info["fields"] = array("distinct(SubGroup) as SubGroup");
		$info["where"]  = "1 AND DefGroup='".$res3[$d]["DefGroup"]."' ";
		$res = $db->select($info);

		for($j=0;$j<count($res);$j++)
		{
		   $row=$row+1;
		   $col=0;

			$$worksheet->write($row, $col,$res[$j]["SubGroup"],$format2);

			  unset($info);
			unset($data);
			$info["table"] = "master";
			$info["fields"] = array("count(OpAssets) as OpAssetsCount");
			$info["where"]  = "1  AND SubGroup='".$res[$j]["SubGroup"]."' AND DefGroup='".$res3[$d]["DefGroup"]."' AND OpAssets>0";
			$resopen=$db->select($info);
			if(count($resopen)>0)
			{
				$openCount=$resopen[0]["OpAssetsCount"];
			}
			else
			{
				$openCount=0;
			}

			$col=$col+1;
			$$worksheet->write($row, $col,$openCount,$format1);

			 unset($info);
			unset($data);
			$info["table"] = "master";
			$info["fields"] = array("count(Dispose) as AdditionCount");
			$info["where"]  = "1  AND SubGroup='".$res[$j]["SubGroup"]."' AND DefGroup='".$res3[$d]["DefGroup"]."' AND (Dispose=0 OR Dispose=1) AND OpAssets=0";
			$resaddition=$db->select($info);
			if(count($resaddition)>0)
			{
				$additionCount=$resaddition[0]["AdditionCount"];
			}
			else
			{
				$additionCount=0;
			}

			$col=$col+1;
			$$worksheet->write($row, $col,$additionCount,$format1);

			 unset($info);
			unset($data);
			$info["table"] = "master";
			$info["fields"] = array("count(Dispose) as DisposeCount");
			$info["where"]  = "1  AND SubGroup='".$res[$j]["SubGroup"]."' AND DefGroup='".$res3[$d]["DefGroup"]."' AND Dispose=1";
			$resdis=$db->select($info);
			if(count($resdis)>0)
			{
				$disCount=$resdis[0]["DisposeCount"];
			}
			else
			{
				$disCount=0;
			}
			//$col=$col+1;
			//$worksheet->write($row, $col,$disCount,$format1);

			$totalCount=$openCount+$additionCount;
			$col=$col+1;
			$$worksheet->write($row, $col,$totalCount,$format1);

						unset($info);
						unset($data);
					$info["table"] = "locationin";
					$info["fields"] = array("*");
					$info["where"]  = "1";
					$resloc = $db->select($info);

					$count=count($resloc);

				for($i=0;$i<$count;$i++)
				{

                       $col=$col+1;
			          //  $worksheet->write($row, $col,$resloc[$i]["Location"],$format1);

					        unset($info);
						    unset($data);
					        $info["table"] = "master";
							$info["fields"] = array("count(Location) as LocationCount");
							$info["where"]  = "1 AND Location='".$resloc[$i]["Location"]."' AND SubGroup='".$res[$j]["SubGroup"]."' AND DefGroup='".$res3[$d]["DefGroup"]."' AND Dispose<>1";
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

			               $$worksheet->write($row, $col,$LocationCount,$format1);

					//}

				}//End of Room
				    $lastcol=$col;
				    $col = $col +1;
					$$worksheet->write($row, $col,$totalCount);
					$col = $col +1;
					$$worksheet->write($row, $col,"");
					$col = $col +1;
					$$worksheet->write($row, $col,$disCount);    
					$col = $col +1;
					$$worksheet->write($row, $col,$totalCount-$disCount);   
			}//End of Group
             
            $col = $lastcol +1;
			$$worksheet->write($storeforendrow, $col,"Gross Total",$format2);
			$col = $col +1;
			$$worksheet->write($storeforendrow, $col,"Recom'd for Disposal",$format2);
            $col = $col +1;
			$$worksheet->write($storeforendrow, $col,"Disposed Off",$format2);    
			$col = $col +1;
			$$worksheet->write($storeforendrow, $col,"Closing Balance",$format2);    
			/*****************************************************************/

			# The general syntax is write($row, $column, $token). Note that row and
			# column are zero indexed
			#
			# Write some text
        } 
			$workbook->close();
			header("Content-type: application/octet-stream");
			header("Content-Type: application/x-msexcel; name=\"showinexcell.xls\"");
			header("Content-Disposition: inline; filename=\"showinexcell.xls\"");
			header("Content-Transfer-Encoding: binary");
			$fh=fopen($fname, "rb");
			fpassthru($fh);
			unlink($fname);
}
function defgroup($db,$_req)
{
   	       	        require_once "../php_writeexcel/class.writeexcel_workbook.inc.php";
			require_once "../php_writeexcel/class.writeexcel_worksheet.inc.php";

			$fname = tempnam("/tmp", "convert_orderlist_to_xcell.xls");
			$workbook = &new writeexcel_workbook($fname);
			$worksheet = &$workbook->addworksheet();
			# Set the column width for columns 1, 2, 3 and 4
		//	$worksheet->set_column(0, 100, 25);


           # Create a format for the column headings
            $header =& $workbook->addformat();
            $header->set_bold();
            $header->set_font("Courier New");
            #$header->set_align('center');
            $header->set_align('vcenter');

            # Create a "vertical justification" format
            $format1 =& $workbook->addformat();
            $format1->set_align('center');

            # Create a "text wrap" format
            $format2 =& $workbook->addformat();
            $format2->set_text_wrap();

			$row = 0;
			$col = 4;
			/****************************************************************/
			     $worksheet->write($row, $col,$_SESSION["ComName"]);
				 $row = $row +1;
				 $worksheet->write($row, $col,$_SESSION["Address1"]);
				 $row = $row +1;
				 $worksheet->write($row, $col,$_SESSION["Address2"]);
				 $row = $row +1;
				 $worksheet->write($row, $col,"Inventory -  Default Group", $header);
				 $row = $row +1;
				 $worksheet->write($row, $col,"For the period ending-date: ".date("F j, Y",strtotime($_SESSION['clDate'])));
				 $row = $row +1;
				 $worksheet->write(0, $col+10,"Print date:".date("F j, Y"));
				 $row = $row +1;
				 $storeforendrow=$row;
			/****************************************************************/
			  $col =0;
			$worksheet->write($row, $col,"Name of Items",$format2);
			$worksheet->write($row+1, $col+1,"Room->", $format2);
			$col = $col +1;
			$worksheet->write($row, $col,"Opening", $format2);
			
			$col = $col +1;
			$worksheet->write($row, $col,"Addition", $format2);
			//$col = $col +1;
			//$worksheet->write($row, $col,"Disposal", $header);
			$col = $col +1;
			$worksheet->write($row, $col,"Total", $format2);
			$worksheet->write($row, $col+8,"Location", $header);
			$col = $col +1;
		//	$worksheet->write($row, $col,"Location", $header);

             	        unset($info);
						unset($data);
					$info["table"] = "locationin";
					$info["fields"] = array("*");
					$info["where"]  = "1";
					$resloc = $db->select($info);

					$count=count($resloc);
                      $row=$row+1;
				for($i=0;$i<$count;$i++)
				{


			           $worksheet->write($row, $col,$resloc[$i]["Location"],$format1);
                       $col=$col+1;
				}



		 //All SubGroup
		$info["table"] = "master";
		$info["fields"] = array("distinct(DefGroup) as DefGroup");
		$info["where"]  = "1";
		$res = $db->select($info);

		for($j=0;$j<count($res);$j++)
		{
		   $row=$row+1;
		   $col=0;

			$worksheet->write($row, $col,$res[$j]["DefGroup"],$format2);

			  unset($info);
			unset($data);
			$info["table"] = "master";
			$info["fields"] = array("count(OpAssets) as OpAssetsCount");
			$info["where"]  = "1  AND DefGroup='".$res[$j]["DefGroup"]."' AND OpAssets>0";
			$resopen=$db->select($info);
			if(count($resopen)>0)
			{
				$openCount=$resopen[0]["OpAssetsCount"];
			}
			else
			{
				$openCount=0;
			}

			$col=$col+1;
			$worksheet->write($row, $col,$openCount,$format1);

			 unset($info);
			unset($data);
			$info["table"] = "master";
			$info["fields"] = array("count(Dispose) as AdditionCount");
			$info["where"]  = "1  AND DefGroup='".$res[$j]["DefGroup"]."' AND (Dispose=0 OR Dispose=1) AND OpAssets=0";
			$resaddition=$db->select($info);
			if(count($resaddition)>0)
			{
				$additionCount=$resaddition[0]["AdditionCount"];
			}
			else
			{
				$additionCount=0;
			}

			$col=$col+1;
			$worksheet->write($row, $col,$additionCount,$format1);

			 unset($info);
			unset($data);
			$info["table"] = "master";
			$info["fields"] = array("count(Dispose) as DisposeCount");
			$info["where"]  = "1  AND DefGroup='".$res[$j]["DefGroup"]."' AND Dispose=1";
			$resdis=$db->select($info);
			if(count($resdis)>0)
			{
				$disCount=$resdis[0]["DisposeCount"];
			}
			else
			{
				$disCount=0;
			}
			//$col=$col+1;
			//$worksheet->write($row, $col,$disCount,$format1);

			$totalCount=$openCount+$additionCount;
			$col=$col+1;
			$worksheet->write($row, $col,$totalCount,$format1);

						unset($info);
						unset($data);
					$info["table"] = "locationin";
					$info["fields"] = array("*");
					$info["where"]  = "1";
					$resloc = $db->select($info);

					$count=count($resloc);

				for($i=0;$i<$count;$i++)
				{

                       $col=$col+1;
			          //  $worksheet->write($row, $col,$resloc[$i]["Location"],$format1);

					        unset($info);
						    unset($data);
					        $info["table"] = "master";
							$info["fields"] = array("count(Location) as LocationCount");
							$info["where"]  = "1 AND Location='".$resloc[$i]["Location"]."' AND DefGroup='".$res[$j]["DefGroup"]."' AND Dispose<>1";
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

			               $worksheet->write($row, $col,$LocationCount,$format1);

					//}

				}//End of Room
				    $lastcol=$col;
				    $col = $col +1;
					$worksheet->write($row, $col,$totalCount);
					$col = $col +1;
					$worksheet->write($row, $col,"");
					$col = $col +1;
					$worksheet->write($row, $col,$disCount);    
					$col = $col +1;
					$worksheet->write($row, $col,$totalCount-$disCount);   
			}//End of Group
             
            $col = $lastcol +1;
			$worksheet->write($storeforendrow, $col,"Gross Total",$format2);
			$col = $col +1;
			$worksheet->write($storeforendrow, $col,"Recom'd for Disposal",$format2);
            $col = $col +1;
			$worksheet->write($storeforendrow, $col,"Disposed Off",$format2);    
			$col = $col +1;
			$worksheet->write($storeforendrow, $col,"Closing Balance",$format2);    
			/*****************************************************************/

			# The general syntax is write($row, $column, $token). Note that row and
			# column are zero indexed
			#
			# Write some text

			$workbook->close();
			header("Content-type: application/octet-stream");
			header("Content-Type: application/x-msexcel; name=\"showinexcell.xls\"");
			header("Content-Disposition: inline; filename=\"showinexcell.xls\"");
			header("Content-Transfer-Encoding: binary");
			$fh=fopen($fname, "rb");
			fpassthru($fh);
			unlink($fname);
}
function acode($db,$_req)
{
       require_once "../php_writeexcel/class.writeexcel_workbook.inc.php";
			require_once "../php_writeexcel/class.writeexcel_worksheet.inc.php";

			$fname = tempnam("/tmp", "convert_orderlist_to_xcell.xls");
			$workbook = &new writeexcel_workbook($fname);
			$worksheet = &$workbook->addworksheet();
			# Set the column width for columns 1, 2, 3 and 4
		//	$worksheet->set_column(0, 100, 25);


           # Create a format for the column headings
            $header =& $workbook->addformat();
            $header->set_bold();
            $header->set_font("Courier New");
            #$header->set_align('center');
            $header->set_align('vcenter');

            # Create a "vertical justification" format
            $format1 =& $workbook->addformat();
            $format1->set_align('center');

            # Create a "text wrap" format
            $format2 =& $workbook->addformat();
            $format2->set_text_wrap();

			$row = 0;
			$col = 4;
			/****************************************************************/
			     $worksheet->write($row, $col,$_SESSION["ComName"]);
				 $row = $row +1;
				 $worksheet->write($row, $col,$_SESSION["Address1"]);
				 $row = $row +1;
				 $worksheet->write($row, $col,$_SESSION["Address2"]);
				 $row = $row +1;
				 $worksheet->write($row, $col,"Inventory - Asset Code", $header);
				 $row = $row +1;
				 $worksheet->write($row, $col,"For the period ending-date: ".date("F j, Y",strtotime($_SESSION['clDate'])));
				 $row = $row +1;
				 $worksheet->write(0, $col+10,"Print date:".date("F j, Y"));
				 $row = $row +1;
				 $storeforendrow=$row;
			/****************************************************************/
			  $col =0;
			$worksheet->write($row, $col,"Name of Items",$format2);
			$worksheet->write($row+1, $col+1,"Room->", $format2);
			$col = $col +1;
			$worksheet->write($row, $col,"Opening", $format2);
			
			$col = $col +1;
			$worksheet->write($row, $col,"Addition", $format2);
			//$col = $col +1;
			//$worksheet->write($row, $col,"Disposal", $header);
			$col = $col +1;
			$worksheet->write($row, $col,"Total", $format2);
			$worksheet->write($row, $col+8,"Location", $header);
			$col = $col +1;
		//	$worksheet->write($row, $col,"Location", $header);

             	        unset($info);
						unset($data);
					$info["table"] = "locationin";
					$info["fields"] = array("*");
					$info["where"]  = "1";
					$resloc = $db->select($info);

					$count=count($resloc);
                      $row=$row+1;
				for($i=0;$i<$count;$i++)
				{


			           $worksheet->write($row, $col,$resloc[$i]["Location"],$format1);
                       $col=$col+1;
				}



		 //All SubGroup
		$info["table"] = "master";
		$info["fields"] = array("distinct(ACode) as ACode");
		$info["where"]  = "1";
		$res = $db->select($info);

		for($j=0;$j<count($res);$j++)
		{
		   $row=$row+1;
		   $col=0;

			$worksheet->write($row, $col,$res[$j]["ACode"],$format2);

			  unset($info);
			unset($data);
			$info["table"] = "master";
			$info["fields"] = array("count(OpAssets) as OpAssetsCount");
			$info["where"]  = "1  AND  ACode='".$res[$j]["ACode"]."' AND OpAssets>0";
			$resopen=$db->select($info);
			if(count($resopen)>0)
			{
				$openCount=$resopen[0]["OpAssetsCount"];
			}
			else
			{
				$openCount=0;
			}

			$col=$col+1;
			$worksheet->write($row, $col,$openCount,$format1);

			 unset($info);
			unset($data);
			$info["table"] = "master";
			$info["fields"] = array("count(Dispose) as AdditionCount");
			$info["where"]  = "1  AND  ACode='".$res[$j]["ACode"]."' AND (Dispose=0 OR Dispose=1) AND OpAssets=0";
			$resaddition=$db->select($info);
			if(count($resaddition)>0)
			{
				$additionCount=$resaddition[0]["AdditionCount"];
			}
			else
			{
				$additionCount=0;
			}

			$col=$col+1;
			$worksheet->write($row, $col,$additionCount,$format1);

			 unset($info);
			unset($data);
			$info["table"] = "master";
			$info["fields"] = array("count(Dispose) as DisposeCount");
			$info["where"]  = "1  AND  ACode='".$res[$j]["ACode"]."' AND Dispose=1";
			$resdis=$db->select($info);
			if(count($resdis)>0)
			{
				$disCount=$resdis[0]["DisposeCount"];
			}
			else
			{
				$disCount=0;
			}
			//$col=$col+1;
			//$worksheet->write($row, $col,$disCount,$format1);

			$totalCount=$openCount+$additionCount;
			$col=$col+1;
			$worksheet->write($row, $col,$totalCount,$format1);

						unset($info);
						unset($data);
					$info["table"] = "locationin";
					$info["fields"] = array("*");
					$info["where"]  = "1";
					$resloc = $db->select($info);

					$count=count($resloc);

				for($i=0;$i<$count;$i++)
				{

                       $col=$col+1;
			          //  $worksheet->write($row, $col,$resloc[$i]["Location"],$format1);

					        unset($info);
						    unset($data);
					        $info["table"] = "master";
							$info["fields"] = array("count(Location) as LocationCount");
							$info["where"]  = "1 AND Location='".$resloc[$i]["Location"]."' AND ACode='".$res[$j]["ACode"]."' AND Dispose<>1";
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

			               $worksheet->write($row, $col,$LocationCount,$format1);

					//}

				}//End of Room
				    $lastcol=$col;
				    $col = $col +1;
					$worksheet->write($row, $col,$totalCount);
					$col = $col +1;
					$worksheet->write($row, $col,"");
					$col = $col +1;
					$worksheet->write($row, $col,$disCount);    
					$col = $col +1;
					$worksheet->write($row, $col,$totalCount-$disCount);   
			}//End of Group
             
            $col = $lastcol +1;
			$worksheet->write($storeforendrow, $col,"Gross Total",$format2);
			$col = $col +1;
			$worksheet->write($storeforendrow, $col,"Recom'd for Disposal",$format2);
            $col = $col +1;
			$worksheet->write($storeforendrow, $col,"Disposed Off",$format2);    
			$col = $col +1;
			$worksheet->write($storeforendrow, $col,"Closing Balance",$format2);    
			/*****************************************************************/

			# The general syntax is write($row, $column, $token). Note that row and
			# column are zero indexed
			#
			# Write some text

			$workbook->close();
			header("Content-type: application/octet-stream");
			header("Content-Type: application/x-msexcel; name=\"showinexcell.xls\"");
			header("Content-Disposition: inline; filename=\"showinexcell.xls\"");
			header("Content-Transfer-Encoding: binary");
			$fh=fopen($fname, "rb");
			fpassthru($fh);
			unlink($fname);
 
}

$cmd = $_REQUEST['cmd'];

switch($cmd)
{
    case "make_report_quantity_excell":
       
                if($_REQUEST['option1']=="SubGroup")
                {
                  subgroup($db,$_REQUEST);
                }
                  if($_REQUEST['option1']=="DefGroup")
                {
                 defgroup($db,$_REQUEST);
                }
                  if($_REQUEST['option1']=="ACode")
                {
                 acode($db,$_REQUEST);
                }
      
        break;


	case "report_quantity_excell_editor":
	   

		include("report_quantity_excell_editor.php");
		break;
			
		

	default :
		include("report_quantity_excell_editor.php");

		break;

                    }

 ?>
