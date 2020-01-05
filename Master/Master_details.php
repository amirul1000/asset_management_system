<?php		  unset($info);		
		$info["table"] = "master left outer join company on(master.ComID=company.id)";
		$info["fields"] = array("master.*","company.ComName"); 
		$info["where"]   = "1 AND master.id='".$Id."'";
		$res =  $db->select($info);
?>

<table cellspacing="3" cellpadding="3" border="0" class="bodytext">
  <tr>
    <td><strong>Company:</strong></td> <td><?=$res[0]['ComName']?></td>
    <td><strong>ACode:</strong></td> <td><?=$res[0]['ACode']?></td>
    <td><strong>Pur Cost:</strong></td> <td><?=$res[0]['PurCost']?></td>
    <td><strong>Inst Cost:</strong></td><td><?=$res[0]['InstCost']?></td>
  </tr>
  <tr>
    <td><strong>Ser Date:</strong></td> <td><?=$res[0]['SerDate']?></td>
    <td><strong>Def Group:</strong></td> <td><?=$res[0]['DefGroup']?></td>
    <td><strong>Sub Group:</strong></td> <td><?=$res[0]['SubGroup']?></td>
    <td><strong>Department:</strong></td><td><?=$res[0]['Department']?></td>
  </tr>
  <tr>
    <td><strong>UserName:</strong></td> <td><?=$res[0]['UserName']?></td>
    <td><strong>Project:</strong></td> <td><?=$res[0]['Project']?></td>
    <td><strong>Rate:</strong></td><td><?=$res[0]['Rate']?></td>
    <td><strong>Life:</strong></td> <td><?=$res[0]['Life']?></td>
  </tr>
  <tr>
    <td><strong>Method</strong></td> <td><?=$res[0]['Method']?></td>
    <td><strong>Narration</strong></td> <td><?=$res[0]['Narration']?></td>
    <td><strong>Warranty</strong></td> <td><?=$res[0]['Warranty']?></td>
    <td><strong>OpAssets</strong></td><td><?=$res[0]['OpAssets']?></td>
  </tr>
  <tr>
    <td><strong>Carry Cost:</strong></td> <td><?=$res[0]['CarryCost']?></td>
    <td><strong>Supplier:</strong></td> <td><?=$res[0]['Supplier']?></td>
    <td><strong>Location</strong></td> <td><?=$res[0]['Location']?></td>
    <td>Dispose</td><td><?=$res[0]['Dispose']?></td>
  </tr>
</table>
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
				$info1["where"]   = "1 AND ComID='".$res[0]['ComID']."' AND ACode='".$res[0]['ACode']."' ORDER BY id ASC ";//AND DepDebit>0 
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
