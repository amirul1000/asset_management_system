<?php		  unset($info);		
		$info["table"] = "disposeassets left outer join company on(disposeassets.ComID=company.id)";
		$info["fields"] = array("disposeassets.*","company.ComName"); 
		$info["where"]   = "1 AND disposeassets.id='".$Id."'";
		$res =  $db->select($info);
?>


<table cellspacing="3" cellpadding="3" border="0" class="bodytext">
				<tr bgcolor="#CCCCCC">
				    <th>ACode  </th>
				    <th>TDate  </th>
					<th>Debit  </th>
					<th>Credit  </th>
					<th>DepDebit  </th>
					<th>DepCredit  </th>
				</tr>
				
				<?php
				  unset($info);		
				$info1["table"] = "details";
				$info1["fields"] = array("*"); 
				$info1["where"]   = "1 AND ComID='".$res[0]['ComID']."' AND ACode='".$res[0]['ACode']."' ORDER BY id ASC ";// AND DepDebit>0
				$resdetails =  $db->select($info1);
				
				
				for($l=0;$l<count($resdetails);$l++)
					{
					
					   $rowColor;
			
						if($l % 2 == 0)
						{
							$rowColor = "#F5F3F4";
						}
						else
						{
							$rowColor = "#ECE5B6";
						}
					
			 ?>
				<tr bgcolor="<?=$rowColor?>"  >
				   <td>
					<?=$resdetails[$l]['ACode']?>
				  </td>
				    <td>
					<?=date("F j, Y",strtotime($resdetails[$l]['TDate']))?>
				  </td>
				  <td>
					<?=$resdetails[$l]['Debit']?>
				  </td>
				  <td>
					<?=$resdetails[$l]['Credit']?>
				  </td>
				  <td>
					<?=$resdetails[$l]['DepDebit']?>
				  </td>
				  
				     <td>
					<?=$resdetails[$l]['DepCredit']?>
				  </td>
				</tr>
				
				
			<?php
			     
					  }
			?>		
</table>					