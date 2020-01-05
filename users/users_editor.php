<?php
 include("../template/header.php");
 ?><b>User Login</b><br />
  <table cellspacing="3" cellpadding="3" border="0" align="center" width="98%" class="bdr">
  <tr>
   <td>  
   
     <a href="users.php?cmd=list" class="nav3">List</a>
			<form name="frm_cat">			
			  <table cellspacing="3" cellpadding="3" border="0" align="center" class="bodytext">  
			  
			     <tr>
					 <td>Company</td>
					 <td>
					      <select name="ComID" id="ComID" class="textbox">
						  <option value="">--Select--</option>
						   <?php
						unset($info);
						$info["table"] = "company";
						$info["fields"] = array("*"); 
						$info["where"]   = "1    ORDER BY ComName ASC";
						$resCom  =  $db->select($info);
					
						foreach($resCom as  $key=>$each)
						{
					 ?>
					<option value="<?=$each['id']?>" <?php if($ComID==$each['id']) echo "selected"; ?> ><?=$each['ComName']?></option>
					 
					 <?php
					     }
					 ?>
				      </select>  		  
					 </td>
				 </tr>
			  
				 <tr>
					 <td>Userid</td>
					 <td><input type="text" name="userid" id="userid"  value="<?=$userid?>" class="textbox"></td>
				 </tr>
				 				 <tr>
					 <td>Password</td>
					 <td><input type="text" name="password" id="password"  value="" class="textbox"></td>
				 </tr>

				 <tr>
					 <td>Name</td>
					 <td><input type="text" name="name" id="name"  value="<?=$name?>" class="textbox"></td>
				 </tr>

				 <tr>
					 <td>Designation</td>
					 <td><input type="text" name="designation" id="designation"  value="<?=$designation?>" class="textbox"></td>
				 </tr>

				 <tr>
					 <td>Address</td>
					 <td><input type="text" name="address" id="address"  value="<?=$address?>" class="textbox"></td>
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

