<?php
 include("../template/header.php");
 ?>
  <script	src="../js/main.js" type="text/javascript"></script>
  <script	src="../js/prototype.js" type="text/javascript"></script>
  <script language="javascript">
  
  function getHTML(value)
    {
	 
	   document.getElementById("ADepreciation").value="";
	   document.getElementById("BookValue").value="";
	   document.getElementById("GainLoss").value="";
	 
        var url = 'DisposeAssets.php';
        var pars = 'cmd=make_master&ACode='+value;

        var myAjax = new Ajax.Updater(
        {success: 'div_master'},
        url,
        {
            method: 'post',
            parameters: pars,
            onFailure: report_speedError
        });
		
		
    }
	
	function checkDate(TDate)	
{

		ACode = document.getElementById("ACode").value 	
		var url = 'DisposeAssets.php';
		var pars =  'cmd=check_date&TDate='+TDate+'&ACode='+ACode;
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
	   if(originalRequest.responseText.length>0)
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
			
			document.getElementById("ADepreciation").value=Math.round(str,2);
			var ADepreciation=document.getElementById("ADepreciation").value;
			var PurCost=document.getElementById("PurCost").value;
			var BookValue = PurCost-ADepreciation;
			document.getElementById("BookValue").value=Math.round(BookValue,2);
			
			}
		}
	}
	
 function getSubGroup(value)
    {
	  
        var url = 'DisposeAssets.php';
        var pars = 'cmd=make_sgroup&DefGroup='+value.replace("&","%26");

        var myAjax = new Ajax.Updater(
        {success: 'div_sgroup'},
        url,
        {
            method: 'post',
            parameters: pars,
            onFailure: report_speedError
        });
		
		
    }
 function getAssetCode(value)
    {
	  
        var url = 'DisposeAssets.php';
        var pars = 'cmd=make_asset_code&SubGroup='+value.replace("&","%26");

        var myAjax = new Ajax.Updater(
        {success: 'div_asset_code'},
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

function gainloss()
{
       
		var NetProceeds=document.getElementById("NetProceeds").value;
		var BookValue=document.getElementById("BookValue").value;
		var GainLoss = NetProceeds-BookValue;
		document.getElementById("GainLoss").value=GainLoss;
}

function a()
{
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

         if(document.getElementById("NetProceeds").value=="")
        {
            alert("NetProceeds is a required field.");
            document.getElementById("NetProceeds").focus();
            return false;
        }
      if(document.getElementById("GainLoss").value=="")
        {
            alert("GainLoss is a required field.");
            document.getElementById("GainLoss").focus();
            return false;
        }

        return true;
    }


  </script>
  <b>DisposeAssets</b><br />
  <table cellspacing="3" cellpadding="3" border="0" align="center" width="98%" class="bdr">
  <tr>
   <td>  
     <a href="DisposeAssets.php?cmd=list" class="nav3">List</a>
			<form name="frm_cat" onsubmit="return checkRequired();">
			  <table cellspacing="3" cellpadding="3" border="0" align="center" class="bodytext">  
			 
				 <tr>
				     <!---Left---->
					 <td align="left" valign="top" class="bdr">
					      <b>Entry</b>
								   <table class="bdr" cellspacing="1" cellpadding="1">
								          <!--Optional---> 
								           <tr>
											 <td>Default Group</td>
											 <td> <select  name="DefGroup" id="DefGroup"  class="textbox" onchange="getSubGroup(this.value)">
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
											 <select type="text" name="SubGroup" id="SubGroup"  class="textbox" onchange="getAssetCode(this.value)">
											    <!--
											   <option value="<?=$each['DefGroup']?>" <?php if($DefGroup==$each['DefGroup']) echo "selected"; ?> ><?=$each['DefGroup']?></option>
													-->
												</select>
												</div>	 
											 
											 </td>
										 </tr>
										 <!--Optional--->
										  <tr>
											 <td>Select Asset Code</td>
											 <td> 
											 <div name="div_asset_code" id="div_asset_code">
											 <select  name="ACode" id="ACode"  class="textbox" onchange="getHTML(this.value)">
											       <option value="">--Select--</option>
											       <?php
													unset($info);
													$info["table"] = "master";
													$info["fields"] = array("*"); 
													$info["where"]   = "1    ORDER BY ACode ASC";
													$res  =  $db->select($info);
					                               
														foreach($res as  $key=>$each)
														{
														
														$info2["table"] = "details";
														$info2["fields"] = array("*"); 
														$info2["where"]   = "1 AND ComID='".$each['ComID']."' AND ACode='".$each['ACode']."' AND Credit>0 ";
														$resdetails2 =  $db->select($info2);
												   
														if(count($resdetails2)>0)
														{
														 
														}
														else
														{
														 
														
													 ?>
													<option value="<?=$each['ACode']?>" <?php if($ACode==$each['ACode']) echo "selected"; ?> ><?=$each['ACode']?></option>
													 <?php
													   }
														 }
													 ?>
											      </select>
												 </div>
											 </td>
										 </tr>
										 <tr>
											 <td>Refference</td>
											 <td> <input type="text" name="Ref" id="Ref"  value="<?=$Ref?>" class="textbox">
											 </td>
										 </tr>
										 <tr>
											 <td>Disposal Date</td>
											 <td> <input type="text" name="TDate" id="TDate"  value="<?=$TDate?>" class="textbox" onblur=" checkDate(this.value);" >
											 <a href="#" onclick="displayDatePicker('TDate');"><img src="../icons/calendar.gif" width="16" height="16" border="0" /></a>
											 </td>
										 </tr>
										  <tr>
											 <td>Reason for Disposal </td>
											 <td> <input type="text" name="ReasonForSale" id="ReasonForSale"  value="<?=$ReasonForSale?>" class="textbox">
											 </td>
										 </tr>
										  <tr>
											 <td>Net Proceeds(Tk.) </td>
											 <td> <input type="text" name="NetProceeds" id="NetProceeds"  value="<?=$NetProceeds?>" class="textbox">
											 </td>
										 </tr>
										 <tr> 
											 <td></td>
											 <td>											
												<input type="Button" name="btn_submit" id="btn_submit" value="Gain/-Loss calculation" class="button" onclick="gainloss();">
											 </td>     
										 </tr>
								 </table>
								 <br />
								 <b>Result</b>
								   <table class="bdr" cellspacing="1" cellpadding="1">
										 <tr>
											 <td>Accum. Depreciation<br />
										     Value</td>
											 <td> <input type="text" name="ADepreciation" id="ADepreciation"  value="<?=$ADepreciation?>" class="textbox">
											 </td>
										 </tr> 
										 <tr>
											 <td>BookValue</td>
											 <td> <input type="text" name="BookValue" id="BookValue"  value="<?=$BookValue?>" class="textbox">
											 </td>
										 </tr>										 
										  <tr>
											 <td>GainLoss</td>
											 <td> <input type="text" name="GainLoss" id="GainLoss"  value="<?=$GainLoss?>" class="textbox">
											 </td>
										 </tr>
								 </table>
					 </td>
					 <!---End of Left---->
					 <td align="left" valign="top" class="bdr">
					      <!---Right---->
									  <b>Group information</b>
									  <div name="div_master" id="div_master">
								   <table class="bdr" cellspacing="1" cellpadding="1">
										  
										 <tr>
											 <td>Date of Purchase</td>
											 <td> <input type="text" name="SerDate" id="SerDate"  value="<?=$SerDate?>" class="textbox">
											 
											 </td>
										 </tr>
										 <tr>
											 <td>Default Group</td>
											 <td> <input type="text" id="DefGroup"  class="textbox" onchange="getHTML(this.value)">
											     
											 </td>
										 </tr>
										 <tr>
											 <td>Sub Group</td>
											 <td> 
											 
											
											 <input type="text" name="SubGroup" id="SubGroup"  class="textbox">
											   
											 </td>
										 </tr>
										 <tr>
											 <td>Department</td>
											 <td> <input type="text" id="Department"   class="textbox">
											       
											 </td>
										 </tr>
										 <tr>
											 <td>User</td>
											 <td> <input type="text" id="UserN"   class="textbox">
											 
											 </td>
										 </tr>
										 <tr>
											 <td>Location</td>
											 <td> <input type="text" id="Location"   class="textbox">
											 		
											 </td>
										 </tr>
										 <tr>
											 <td>Supplier</td>
											
											 	<td> <input type="text" id="Supplier"   class="textbox">
											
											 </td>
											
										 </tr>
										 <tr>
											 <td>Project</td>
											 <td> <input type="text" id="Project"  class="textbox">
											 
											 </td>
										 </tr>
										 <tr>
											 <td>Warranty(Year)</td>
											 <td> <input type="text" name="Warranty" id="Warranty"  value="<?=$Warranty?>" class="textbox"></td>
										 </tr>
										
										  <tr>
												 <td>Purchase Cost</td>
												 <td> <input type="text" name="PurCost" id="PurCost"  value="<?=$PurCost?>" class="textbox" ></td>
											 </tr>
										   <tr>
											 <td>Method of Depreciation</td>
											 <td> <input type="text" name="Method" id="Method"  value="<?=$Method?>" class="textbox"></td>
										 </tr>
										 <tr>
											 <td>Rate of Depreciation %</td>
											 <td> <input type="text" name="Rate" id="Rate"  value="<?=$Rate?>" class="textbox"></td>
										 </tr>
										 <tr>
											 <td>Useful life Years</td>
											 <td> <input type="text" name="Life" id="Life"  value="<?=$Life?>" class="textbox"></td>
										 </tr>
									</table>	
									</div> 
					       <!---End of Right---->
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

