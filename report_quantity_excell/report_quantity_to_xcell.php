<?php
  
  
		   session_start();
		  set_time_limit(0);
			include("../lib/class.db.php");
			include("../common/config.php");
			include("../common/lib.php");
			include('../fpdf/fpdf.php');
			include('report_quantity.lib.php');

			
			
			require_once "../php_writeexcel/class.writeexcel_workbook.inc.php";
			require_once "../php_writeexcel/class.writeexcel_worksheet.inc.php";
			
			$fname = tempnam("/tmp", "convert_orderlist_to_xcell.xls");
			$workbook = &new writeexcel_workbook($fname);
			$worksheet = &$workbook->addworksheet();
			# Set the column width for columns 1, 2, 3 and 4
			$worksheet->set_column(0, 100, 25);
			
			# Create a format for the column headings
			$header =& $workbook->addformat();
			$header->set_bold();
			$header->set_size(10);
			$header->set_color('blue');
			
			
			/****************************************************************/
			  
			
			/****************************************************************/
			
			
			
			# The general syntax is write($row, $column, $token). Note that row and
			# column are zero indexed
			#
			
			# Write some text
			$row = 0;
			$col = 0;
					 
			    $worksheet->write($row, $col,stripslashes($value));
				  $col=$col+1;
						
				$row=$row+1;
			
			
			
			
			$workbook->close();
			header("Content-type: application/octet-stream");
			header("Content-Type: application/x-msexcel; name=\"showinexcell.xls\"");
			header("Content-Disposition: inline; filename=\"showinexcell.xls\"");
			header("Content-Transfer-Encoding: binary");
			$fh=fopen($fname, "rb");
			fpassthru($fh);
			unlink($fname);

?>
