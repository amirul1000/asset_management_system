<?php
 include("../template/header.php");
 ?>
<form method="post">
<table align="center" cellspacing="3" cellpadding="3">
        <tr>
		 <td colspan="2">
		 <span align="center"><font color="#FF0000"><?=$message?></font></span>
		 </td>
		</tr>   
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
			<td><input type="text" name="userid" id="userid" value="" class="textbox" /></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" name="password" id="password" value=""  class="textbox" /></td>		
		</tr>
		<tr><td></td>
		
			<td>
			<input type="hidden" name="cmd" value="login"/> 
			<input type="submit" name="submit" value="submit" /> 
			</td>
		</tr>
</table>
</form>
<?php
 include("../template/footer.php");
?>