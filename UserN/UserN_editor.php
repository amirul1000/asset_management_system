<?php
 include("../template/header.php");
 ?>
  <b>Users</b><br />
  <table cellspacing="3" cellpadding="3" border="0" align="center" width="98%" class="bdr">
  <tr>
   <td>  
     <a href="UserN.php?cmd=list" class="nav3">List</a>
			<form name="frm_cat">
			  <table cellspacing="3" cellpadding="3" border="0" align="center" class="bodytext">  
			  
				 <tr>
					 <td>UserName</td>
					 <td><input type="text" name="UserName" id="UserName"  value="<?=$UserName?>" class="textbox"></td>
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

