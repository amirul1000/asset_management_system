<?php
 include("../template/header.php");
 ?>
  <b>Default Group</b><br />
  <table cellspacing="3" cellpadding="3" border="0" align="center" width="98%" class="bdr">
  <tr>
   <td>  
     <a href="DGroup.php?cmd=list" class="nav3">List</a>
			<form name="frm_cat">
			  <table cellspacing="3" cellpadding="3" border="0" align="center" class="bodytext">  
			  
				 <tr>
					 <td>DefGroup</td>
					 <td><input type="text" name="DefGroup" id="DefGroup"  value="<?=$DefGroup?>" class="textbox"></td>
				 </tr>
				 <tr>
					 <td>Rate</td>
					 <td><input type="text" name="Rate" id="Rate"  value="<?=$Rate?>" class="textbox"></td>
				 </tr>

				 <tr>
					 <td>Method</td>
					 <td><input type="radio" name="Method" id="Method"  value="SLM" <?php if($Method=="SLM") echo "checked";?>  class="textbox">Straight Line Method
					 <input type="radio" name="Method" id="Method"  value="RBM" <?php if($Method=="RBM") echo "checked";?> class="textbox">Reducing Balance Method
					 <input type="radio" name="Method" id="Method"  value="NDM" <?php if($Method=="NDM") echo "checked";?> class="textbox">No Depreciation Method
					 </td>
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

