<?php
session_start();  
?>
<link href="../media/css/base.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="../media/css/forms.css" />


<link rel="stylesheet" href="../css/style.css" >
<link rel="stylesheet" href="../css/datepicker.css" >

<table  width="100%" cellspacing="3" cellpadding="3">
<tr id="header" align="center"><td align="center" class="bdr">
	<div >
				<b><?=$_SESSION["ComName"]?></b><br />
				<?php if(!empty($_SESSION["Address1"])) echo "<b>".$_SESSION["Address1"]."</b><br />"; ?>
				<?php if(!empty($_SESSION["Address2"])) echo "<b>".$_SESSION["Address2"]."</b><br />"; ?>
				<b>Asset Management System</b><br />
				<?php
				   if(!empty($_SESSION['userid']))
	   		      {
			 	?>
               Opening Date:<?=$_SESSION["opDate"]?>&nbsp;&nbsp;&nbsp;&nbsp;Closing Date:<?=$_SESSION["clDate"]?> 										
			   <?php
			      }
			   ?></div>
	</td>
</tr>
<tr>
  <td align="left">
    
	
	<table align="center" cellspacing="3" cellpadding="3" width="100%">
		<tr>
		    <?php
			   if(!empty($_SESSION["userid"]))
			   {
			?>
			<td width="15%" class="bdr" valign="top" align="left"><?php
							 include("../template/left_menu.php");
							 ?>
           </td>
		    <?php
			   }
			?>
			<td  align="left" valign="top">
		       <!---->