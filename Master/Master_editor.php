<?php
 include("../template/header.php");
 ?>
  <script	src="../js/main.js" type="text/javascript"></script>
  <script	src="../js/prototype.js" type="text/javascript"></script>
  <script language="javascript">
  function add()
  {
    var PurCost;
    var InstCost;
    var CarryCost;
    var OtherCost;
    var TCost;
  
    PurCost    = document.getElementById("PurCost").value
	if(isNaN(PurCost)||PurCost=="")
	{
	 PurCost="0.0";	
	}	
    InstCost   = document.getElementById("InstCost").value
	if(isNaN(InstCost)||InstCost=="")
	{
	 InstCost="0.0";
	}
    CarryCost  = document.getElementById("CarryCost").value
	if(isNaN(CarryCost)||CarryCost=="")
	{
	 CarryCost="0.0";
	}
    OtherCost  = document.getElementById("OtherCost").value
	if(isNaN(OtherCost)||OtherCost=="")
	{
	 OtherCost="0.0";
	}
    TCost      = document.getElementById("TCost").value
	if(isNaN(TCost)||TCost=="")
	{
	 TCost="0.0";
	}
  
    TCost =parseFloat(PurCost.toString())+parseFloat(InstCost.toString())+parseFloat(CarryCost.toString())+parseFloat(OtherCost.toString());
	document.getElementById("TCost").value  = TCost;
  }

  function getHTML(value)
    {
	  
        var url = 'Master.php';
        var pars = 'cmd=make_sgroup&DefGroup='+value.replace("&","%26");

        var myAjax = new Ajax.Updater(
        {success: 'div_sgroup'},
        url,
        {
            method: 'post',
            parameters: pars,
            onFailure: report_speedError
        });
		
		 var pars = 'cmd=make_depreciation&DefGroup='+value.replace("&","%26");
		 var myAjax = new Ajax.Updater(
        {success: 'div_depreciation'},
        url,
        {
            method: 'post',
            parameters: pars,
            onFailure: report_speedError
        });
		
		
    }
	
function report_speedError(request)
  {
    alert('Sorry. There was an error.');
  }
	
/*************************************************
function checkDate(TDate)	
{

		var url = 'Master.php';
		var pars =  'cmd=check_date&TDate='+TDate;
         if(TDate.length>0)
		 {		
		var myAjax = new Ajax.Request(
			url, 
			{
				method: 'get', 
				parameters: pars, 
				onComplete: showResponse
			});
		 }
}
function showResponse(originalRequest)
	{
		var str=originalRequest.responseText;
		if(str.length>14)
		{
		  alert(str); 		  
		  document.getElementById("TDate").focus();
		  setTimeout("a()",5000);
		}
		else
		{
		 document.getElementById("SerDate").value = str;
		}
		 
	}
***************************************************/	

function setmethodvalue(Rate)
{
   
  var Method= document.getElementById("Method").value;
  if(Method=="SLM")
  {
    document.getElementById("Life").value = Math.floor(100/Rate);
  }									
  if(Method=="RBM")
  {
 	 document.getElementById("Life").value = Math.floor(100/Rate);
  }									
   if(Method=="NDM")
  {
   document.getElementById("Life").value =0;
  }	
  
}
function a()
{
}

//Comparing date
function IsDateRange(DateValue)
{
	var DateValue1=document.getElementById("openingdate").value;
	var DateValue2=document.getElementById("closingdate").value;		
	var d1  = new Array();
	var d2  = new Array();
	var d3  = new Array();

	d1      = DateValue1.split("-");
	d2      = DateValue2.split("-");
	d3      = DateValue.split("-");		

	
	var Date1 = new Date(d1[0],d1[1],d1[2]);
	var Date2 = new Date(d2[0],d2[1],d2[2]);
	var Date3 =new Date(d3[0],d3[1],d3[2]);
	var openDate =  Date1.getTime();
	var closeDate=  Date2.getTime();
	var curDate=  Date3.getTime();

	if(curDate>=openDate&&curDate<=closeDate)
	{
	 document.getElementById("SerDate").value = document.getElementById("closingdate").value;
	}
	else
	{
		if(DateValue.length>0)
		  {
		  alert("Date must be within accounting period");
		  }
	  document.getElementById("TDate").focus();
	}
}

 //check required fields of the form
    function checkRequired()
    {
	
      
        if(document.getElementById("TDate").value=="")
        {
            alert("TDate is a required field.");
            document.getElementById("TDate").focus();
            return false;
        }
		   
		 if(document.getElementById("SerDate").value=="")
        {
            alert("SerDate is a required field.");
            document.getElementById("SerDate").focus();
            return false;
        }
		
		  if(document.getElementById("Warranty").value=="")
        {
            alert("Warranty is a required field.");
            document.getElementById("Warranty").focus();
            return false;
        }
		
		 if(document.getElementById("TCost").value=="")
        {
            alert("TCost is a required field.");
            document.getElementById("TCost").focus();
            return false;
        }

		 if(document.getElementById("Method").value=="")
        {
            alert("Method is a required field.");
            document.getElementById("Method").focus();
            return false;
        }
		
		if(document.getElementById("Rate").value=="")
        {
            alert("Rate is a required field.");
            document.getElementById("Rate").focus();
            return false;
        }
		
		if(document.getElementById("Life").value=="")
        {
            alert("Life is a required field.");
            document.getElementById("Life").focus();
            return false;
        }


        return true;
    }
</script>

<input type="hidden" name="openingdate" id="openingdate" value="<?=$_SESSION["opDate"]?>">
<input type="hidden" name="closingdate" id="closingdate" value="<?=$_SESSION["clDate"]?>">



</script>
<b> Assets Addition</b><br />
  <table cellspacing="3" cellpadding="3" border="0" align="center" width="98%" class="bdr">
  <tr>
   <td>  
     <a href="Master.php?cmd=list" class="nav3">Assets List</a>
			<form name="frm_cat" method="post" action="" onsubmit=" return checkRequired();">
			  <table cellspacing="3" cellpadding="3" border="0" align="center" class="bodytext">  
			 
				 <tr>
				     <!---Left---->
					 <td align="left" valign="top" class="bdr">
					      <b>Group information</b>
								   <table class="bdr" cellspacing="1" cellpadding="1">
										  <tr>
											 <td>Reference</td>
											 <td> <input type="text" name="Ref" id="Ref"  value="<?=$Ref?>" class="textbox"></td>
										 </tr>
										 <tr>
											 <td>Date of Purchase</td>
											 <td> <input type="text" name="TDate" id="TDate"  value="<?=$TDate?>" class="textbox" onblur=" IsDateRange(this.value);">
											 <a href="#" onclick="displayDatePicker('TDate');"><img src="../icons/calendar.gif" width="16" height="16" border="0" /></a>
											 </td>
										 </tr>
										 <tr>
											 <td>End Date of Fiscal Year</td>
											 <td> <input type="text" name="SerDate" id="SerDate"  value="<?=$SerDate?>" class="textbox">
											 <!--<a href="#" onclick="displayDatePicker('SerDate');"><img src="../icons/calendar.gif" width="16" height="16" border="0" /></a>-->
											 </td>
										 </tr>
										 
										 <tr>
											 <td>Default Group</td>
											 <td> <select  name="DefGroup" id="DefGroup"  class="textbox" onchange="getHTML(this.value)">
											      <option value="">--Select--</option>
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
													 </select>
											 </td>
										 </tr>
										 <tr>
											 <td>Sub Group</td>
											 <td> 
											 
											  <div name="div_sgroup" id="div_sgroup">
											 <select type="text" name="SubGroup" id="SubGroup"  class="textbox">
											   <?php
											     if(!empty($Id))
												 {   
											   ?>
											   <option value="<?=$SubGroup?>"  selected="selected"><?=$SubGroup?></option>
												<?php
												 }
												?>
												</select>
												</div>	 
											 
											 </td>
										 </tr>
										 <tr>
											 <td>Department</td>
											 <td> <select name="Department" id="Department"   class="textbox">
											          <?php
													   $info['table']    = "dept";
													   $info['fields']   = array("*");   	   
													   $info['where']    =  "1 Order by Department asc";
							   
													   $res  =  $db->select($info);
													 	foreach($res as  $key=>$each)
														{
													 ?>
													<option value="<?=$each['Department']?>" <?php if($Department==$each['Department']) echo "selected"; ?> ><?=$each['Department']?></option>
													 
													 <?php
														 }
													 ?>
													 </select>
											 </td>
										 </tr>
										 <tr>
											 <td>User</td>
											 <td> <select name="UserName" id="UserName"   class="textbox">
											 <?php
													   $info['table']    = "usern";
													   $info['fields']   = array("*");   	   
													   $info['where']    =  "1 Order by UserName asc";
							   
													   $res  =  $db->select($info);
													 	foreach($res as  $key=>$each)
														{
													 ?>
													<option value="<?=$each['UserName']?>" <?php if($UserName==$each['UserName']) echo "selected"; ?> ><?=$each['UserName']?></option>
													 
													 <?php
														 }
													 ?>
													 </select>
											 </td>
										 </tr>
										 <tr>
											 <td>Location</td>
											 <td> <select name="Location" id="Location"   class="textbox">
											 		<?php
													   $info['table']    = "locationin";
													   $info['fields']   = array("*");   	   
													   $info['where']    =  "1 Order by Location asc";
							   
													   $res  =  $db->select($info);
													 	foreach($res as  $key=>$each)
														{
													 ?>
													<option value="<?=$each['Location']?>" <?php if($Location==$each['Location']) echo "selected"; ?> ><?=$each['Location']?></option>
													 
													 <?php
														 }
													 ?>
													 </select>
											 </td>
										 </tr>
										 <tr>
											 <td>Supplier</td>
											
											 	<td> <select name="Supplier" id="Supplier"   class="textbox">
											 <?php
													   $info['table']    = "supplieinfo";
													   $info['fields']   = array("*");   	   
													   $info['where']    =  "1 Order by Supplier asc";
							   
													   $res  =  $db->select($info);
													 	foreach($res as  $key=>$each)
														{
													 ?>
													<option value="<?=$each['Supplier']?>" <?php if($Supplier==$each['Supplier']) echo "selected"; ?> ><?=$each['Supplier']?></option>
													 
													 <?php
														 }
													 ?>
													 </select>
											 </td>
											
										 </tr>
										 <tr>
											 <td>Project</td>
											 <td> <select name="Project" id="Project"  class="textbox">
											  <?php
													   $info['table']    = "proj";
													   $info['fields']   = array("*");   	   
													   $info['where']    =  "1 Order by Project asc";
							   
													   $res  =  $db->select($info);
													 	foreach($res as  $key=>$each)
														{
													 ?>
													<option value="<?=$each['Project']?>" <?php if($Project==$each['Project']) echo "selected"; ?> ><?=$each['Project']?></option>
													 
													 <?php
														 }
													 ?>
													 </select>
											 </td>
										 </tr>
										 <tr>
											 <td>Warranty(Year)</td>
											 <td> <input type="text" name="Warranty" id="Warranty"  value="<?=$Warranty?>" class="textbox"></td>
										 </tr>
										 <tr>
											 <td>Asset Code</td>
											 <td> <input type="text" name="ACode" id="ACode"  value="Auto Number" class="textbox"></td>
										 </tr>
								 </table>
					 </td>
					 <!---End of Left---->
					 <td align="left" valign="top" class="bdr">
					      <!---Right---->
									  <table class="bdr" cellspacing="1" cellpadding="1">
											  <tr>
												 <td align="left" valign="top" class="bdr"> <b>Cost</b>
												 <table>
											  <tr>
												 <td>Purchase Cost</td>
												 <td> <input type="text" name="PurCost" id="PurCost"  value="<?=$PurCost?>" class="textbox" onkeyup=" add() "></td>
											 </tr>
											 <tr>
												 <td>Installation Cost</td>
												 <td> <input type="text" name="InstCost" id="InstCost"  value="<?=$InstCost?>" class="textbox" onkeyup=" add() "></td>
											 </tr>
											 <tr>
												 <td>Carrying Cost</td>
												 <td> <input type="text" name="CarryCost" id="CarryCost"  value="<?=$CarryCost?>" class="textbox" onkeyup=" add() "></td>
											 </tr>
											  <tr>
												 <td>Other Cost</td>
												 <td> <input type="text" name="OtherCost" id="OtherCost"  value="<?=$OtherCost?>" class="textbox" onkeyup=" add() "></td>
											 </tr>
											  <tr>
												 <td>Total Cost</td>
												 <td> <input type="text" name="TCost" id="TCost"  value="<?=$TCost?>" class="textbox"></td>
											 </tr>
									 </table>
								</td>
						             
								 </tr>
								 <tr>
						             <td align="left" valign="top" class="bdr"> <b>Calculation</b>
									         <div  name="div_depreciation" id="div_depreciation">
											 <table class="bdr" cellspacing="1" cellpadding="1">
													  <tr>
														 <td>Method of Depreciation</td>
														 <td> <select type="text" name="Method" id="Method"  class="textbox"  >
														 		<option value="SLM" <?php if($Method=="SLM") echo "selected";?>>Straight Line Method</option>
																<option value="RBM"  <?php if($Method=="RBM") echo "selected";?>>Reducing Balance Method</option>
																<option value="NDM" <?php if($Method=="NDM") echo "selected";?>>No Depreciation</option>
														 	  </select>	
														 </td>
													 </tr>
													 <tr>
														 <td>Rate of Depreciation %</td>
														 <td> <input type="text" name="Rate" id="Rate"  value="<?=$Rate?>" class="textbox" onkeyup="setmethodvalue(this.value);"></td>
													 </tr>
													 <tr>
														 <td>Useful life Years</td>
														 <td> <input type="text" name="Life" id="Life"  value="<?=$Life?>" class="textbox"></td>
													 </tr>
											 </table>
											 </div>
									 </td>
						             
								 </tr>
								 <tr>
						             <td align="left" valign="top" class="bdr"> <b>Remarks </b>
											<table class="bdr" cellspacing="1" cellpadding="1">
													  <tr>
														 <td></td>
														 <td> <input type="text" name="Narration" id="Narration"  value="<?=$Narration?>" class="textbox"  size="40"></td>
													 </tr>
											 </table>
									 </td>
						             
								 </tr>
						 </table>
					       <!---End of Right---->
					 </td>
				 </tr>				 
				 <tr> 
					 <td></td>
					 <td>
						<input type="hidden" name="cmd" value="add">
						<input type="hidden" name="id" value="<?=$Id?>">
                        <?php
                          if(isset($_REQUEST['page']))
                          {
                        ?>
                        <input type="hidden" name="page" value="<?=$_REQUEST['page']?>">
                        <?php
                          }
                        ?>
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

