<?php
 include("../template/header.php");
 ?>
 <b>Sub Group</b><br />
  <table cellspacing="3" cellpadding="3" border="0" align="center" width="98%" class="bdr">
  <tr>
   <td>  
     <a href="SGroup.php?cmd=list" class="nav3">List</a>
			<form name="frm_cat">
			  <table cellspacing="3" cellpadding="3" border="0" align="center" class="bodytext">  
			  
				 <tr>
					 <td>SubGroup</td>
					 <td><input type="text" name="SubGroup" id="SubGroup"  value="<?=$SubGroup?>" class="textbox"></td>
				 </tr>
				 <tr>
					 <td>DefGroup</td>
					 <td><select name="DefGroup" id="DefGroup"   class="textbox">
					  <?php
						unset($info);
						$info["table"] = "dgroup";
						$info["fields"] = array("*"); 
						$info["where"]   = "1    ORDER BY DefGroup ASC";
						$res  =  $db->select($info);
					   
							foreach($res as  $key=>$each)
							{
						 ?>
						<option value="<?=$each['DefGroup']?>" <?php if($DefGroup==$each['DefGroup']) echo "selected"; ?> ><?=$each['DefGroup']?></option>
						 
						 <?php
							 }
						 ?>
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

