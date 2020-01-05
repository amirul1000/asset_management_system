<?php
 include("../template/header.php");
 ?>
  <b>Currency</b><br />
  <table cellspacing="3" cellpadding="3" border="0" align="center" width="98%" class="bdr">
  <tr>
   <td>  
     <a href="Currency.php?cmd=list" class="nav3">List</a>
			<form name="frm_cat">
			  <table cellspacing="3" cellpadding="3" border="0" align="center" class="bodytext">  
			  
				 <tr>
					 <td>From Country</td>
					 <td><input type="text" name="from_country" id="from_country"  value="<?=$from_country?>" class="textbox"></td>
				 </tr>
				 
				  <tr>
					 <td>From Country Currency</td>
					 <td><input type="text" name="from_country_currency" id="from_country_currency"  value="<?=$from_country_currency?>" class="textbox"></td>
				 </tr>
				 
				  <tr>
					 <td>To Country</td>
					 <td><input type="text" name="to_country" id="to_country"  value="<?=$to_country?>" class="textbox"></td>
				 </tr>
				 
				  <tr>
					 <td>To Country Currency</td>
					 <td><input type="text" name="to_country_currency" id="to_country_currency"  value="<?=$to_country_currency?>" class="textbox"></td>
				 </tr>
				
				 <tr> 
					 <td></td>
					 <td>
						<input type="hidden" name="cmd" value="add">
						<input type="hidden" name="id" value="<?=$Id?>">			
						<input type="submit" name="btn_submit" id="btn_submit" value="submit" class="button">
					 </td>     
				 </tr>
			  </table>
			</form>
</td>
</tr>
</table>
<?php
 include("../template/footer.php");
?>

