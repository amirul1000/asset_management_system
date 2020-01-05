<?php
 include("../template/header.php");
 ?>
  <b>Supplier</b><br />
  <table cellspacing="3" cellpadding="3" border="0" align="center" width="98%" class="bdr">
  <tr>
   <td>  
     <a href="SupplieInfo.php?cmd=list" class="nav3">List</a>
			<form name="frm_cat">
			  <table cellspacing="3" cellpadding="3" border="0" align="center" class="bodytext">  
			  
				 <tr>
					 <td>Supplier</td>
					 <td><input type="text" name="Supplier" id="Supplier"  value="<?=$Supplier?>" class="textbox"></td>
				 </tr>
				  <tr>
					 <td>SAddress</td>
					 <td><input type="text" name="SAddress" id="SAddress"  value="<?=$SAddress?>" class="textbox"></td>
				 </tr>
				  <tr>
					 <td>SCont</td>
					 <td><input type="text" name="SCont" id="SCont"  value="<?=$SCont?>" class="textbox"></td>
				 </tr>
				
				 <tr> 
					 <td></td>
					 <td>
						<input type="hidden" name="cmd" value="add">
						<input type="hidden" name="id" value="<?=$Id?>">			
						<input type="submit" name="btn_submit" id="btn_submit" value="submit" >
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

