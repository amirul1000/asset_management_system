<?php
 include("../template/header.php");
 ?>
  <b>Company</b><br />
 <script	src="../js/main.js" type="text/javascript"></script>
  <table cellspacing="3" cellpadding="3" border="0" align="center" width="98%" class="bdr">
  <tr>
   <td>  
     <a href="company.php?cmd=list" class="nav3">List</a>
			<form name="frm_cat">
			  <table cellspacing="3" cellpadding="3" border="0" align="center" class="bodytext">  
				 
				  <tr>
					 <td>Company Name</td>
					 <td><input type="text" name="ComName" id="ComName"  value="<?=$ComName?>" class="textbox"></td>
				 </tr>
				 <tr>
					 <td>Address1</td>
					 <td><input type="text" name="Address1" id="Address1"  value="<?=$Address1?>" class="textbox"></td>
				 </tr>
				 <tr>
					 <td>Address2</td>
					 <td><input type="text" name="Address2" id="Address2"  value="<?=$Address2?>" class="textbox"></td>
				 </tr>
				 <tr>
					 <td>Country</td>
					 <td>
					 
					 <?php
						unset($info);
						$info["table"] = "country";
						$info["fields"] = array("*"); 
						$info["where"]   = "1    ORDER BY name ASC";
						$resCoun  =  $db->select($info);
						
					 ?>
					 <select  name="Country" id="Country"   class="textbox">
					 <option value="">--Select--</option>
					 
					 <?php
						foreach($resCoun as  $key=>$each)
						{
					 ?>
					<option value="<?=$each['id']?>" <?php if($Country==$each['id']) echo "selected"; ?> ><?=$each['name']?></option>
					 
					 <?php
					     }
					 ?>
					 </td>
				 </tr>
				 
				  <tr>
					 <td>tel</td>
					 <td><input type="text" name="tel" id="tel"  value="<?=$tel?>" class="textbox"></td>
				 </tr>
				 
				   <tr>
					 <td>Fax</td>
					 <td><input type="text" name="Fax" id="Fax"  value="<?=$Fax?>" class="textbox"></td>
				 </tr>
				  <tr>
					 <td>opDate</td>
					 <td><input type="text" name="opDate" id="opDate"  value="<?=$opDate?>" class="textbox">
					     <a href="#" onclick="displayDatePicker('opDate');"><img src="../icons/calendar.gif" width="16" height="16" border="0" /></a>
					 </td>
				 </tr>
				 <tr>
					 <td>clDate</td>
					 <td><input type="text" name="clDate" id="clDate"  value="<?=$clDate?>" class="textbox">
					 <a href="#" onclick="displayDatePicker('clDate');"><img src="../icons/calendar.gif" width="16" height="16" border="0" /></a>
					 </td>
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

