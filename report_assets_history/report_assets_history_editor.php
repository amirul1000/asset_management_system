<?php
 include("../template/header.php");
 ?>
  <script	src="../js/main.js" type="text/javascript"></script>
  <script	src="../js/prototype.js" type="text/javascript"></script>
<script>
    function getHTML(value)
    {
        //Device
        var url = 'report_assets_history.php';
        var pars = 'cmd=make_asset_aquisition_item&Group='+value;

        var myAjax = new Ajax.Updater(
        {success: 'div_individual_item_id'},
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

    //check required fields of the form
    function checkRequired()
    {

        if(document.getElementById("group_id").value=="")
        {
            alert("Division is a required field.");
            document.getElementById("group_id").focus();
            return false;
        }
		
        //initialize date
        var DateValuef = document.getElementById("start_date").value;
        var DateValuen = document.getElementById("end_date").value;

        if(document.getElementById("start_date").value=="")
        {
            alert("Start date is a required field.");
            document.getElementById("start_date").focus();
            return false;
        }

        if(document.getElementById("end_date").value=="")
        {
            alert("End date is a required field.");
            document.getElementById("end_date").focus();
            return false;
        }
        //compare date greater
        if(IsDateGreater(DateValuef,DateValuen)==false)
        {
            alert("Start date is greater than end date");
            return false;
        }

        //compare date range
        if(IsDateRange(DateValuef, DateValuen)==false)
        {
            alert("Day range can not be greater than "+document.getElementById("range").value + " days");
            return false;
        }


        var   HHFrom1  = document.getElementById("HHFrom").value;
        var   HHTo1    = document.getElementById("HHTo").value;
        //compare time range
        if(checkTime(DateValuef, DateValuen,HHFrom1,HHTo1)==false)
        {
            alert("End time can not be less than or equal to start time");
            return false;
        }

        return true;
    }

    //Comparing date
    function IsDateGreater(DateValue1, DateValue2)
    {

        var d1  = new Array();
        var d2  = new Array();

        d1      = DateValue1.split("-");
        d2      = DateValue2.split("-");

        var DaysDiff;
        var Date1 = new Date(d1[0],d1[1],d1[2]);
        var Date2 = new Date(d2[0],d2[1],d2[2]);
        DaysDiff = Date2.getTime()-Date1.getTime();


        //Day1 is greater
        if(DaysDiff >= 0)
        {

            return true;
        }
        else
        {
            return false;
        }
    }




    //Comparing date
    function IsDateRange(DateValue1, DateValue2)
    {

        var d1  = new Array();
        var d2  = new Array();

        d1      = DateValue1.split("-");
        d2      = DateValue2.split("-");

        var DaysDiff;
        var Date1 = new Date(d1[0],d1[1],d1[2]);
        var Date2 = new Date(d2[0],d2[1],d2[2]);
        DaysDiff = Date2.getTime()-Date1.getTime();

        var totaldayrange = document.getElementById("range").value

        //Day1 is greater

        if(DaysDiff/(24*60*60*1000)>=totaldayrange)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    //comparing time
    function checkTime(DateValue1, DateValue2,HHFrom1,HHTo1)
    {
        var d1  = new Array();
        var d2  = new Array();

        d1      = DateValue1.split("-");
        d2      = DateValue2.split("-");

        var DaysDiff;
        var Date1 = new Date(d1[0],d1[1],d1[2]);
        var Date2 = new Date(d2[0],d2[1],d2[2]);
        DaysDiff = Date2.getTime()-Date1.getTime();

        var totaldayrange = document.getElementById("range").value

        //Day1 is greater

        if(DaysDiff/(24*60*60*1000)==0)
        {


            var t1  = new Array();
            var t2  = new Array();

            t1      = HHFrom1.split(":");
            t2      = HHTo1.split(":");

            var TimeDiff;
            var time1 = t1[0]*60*60+t1[1]*60+t1[2];
            var time2 = t2[0]*60*60+t2[1]*60+t2[2];

            TimeDiff  = time2-time1;

            if(TimeDiff<=0)
            {

                return false;
            }
        }

        return true;
    }


</script>

<input type="hidden" name="range" id="range" value="3">
<b> Asset History  </b><br />

<table cellspacing="3" cellpadding="3" border="0" class="bdr" width="100%">
    <tr>
        <td>
                        <table cellpadding="3" cellspacing="3" border="0" width="100%"  class="bodytext">
                            <tr>
                                <td>
                                    <!-- -->
                                    <form  name="report_assets_history_frm" id="report_assets_history_frm" method="post" action="" onsubmit="return checkRequired();">
                                      <table>
									     
										 <tr>
										   <td colspan="2">
										   <table cellspacing="3" cellpadding="3" border="0" class="bdr" width="100%">
											<tr>
												
												<td valign="top" align="left" colspan="2">
												   <table>
												     <tr><td>
												   All Assets</td><td><input type="radio" name="option" id="option"  value="AllAssets" class="textbox" checked="checked"/></td>
												    </tr>
													<tr><td>
												  
												  Individual Assets</td><td><input type="radio" name="option" id="option"  value="IndividualAssets" class="textbox"/> </td>
												  </tr>
												  </table>
												  <br />
												  <?php
												    unset($info);
													$info["table"] = "master";
													$info["fields"] = array("*"); 
													$info["where"]   = "1    ORDER BY ACode ASC";
													$res  =  $db->select($info);
													?>					
													 <select  name="ACode" id="ACode"  class="textbox" SIZE="10" MULTIPLE>
													<?php		  
													foreach($res as  $key=>$each)
													{
													?>
                                                     <option value="<?=$each['ACode']?>" ><?=$each['ACode']?></option>
													 <?php
													}
													?>
													</select>
												  
												  </td>
											</tr>
											</table>
											</td>
										 </tr>
										
										 <tr>
										 <td></td><td>
                                        <input type="hidden" name="cmd" id="cmd" value="make_report_assets_history">
                                        <input align='center' type="submit" name="sub_btn" id="sub_btn"  value="Submit" >
										</td>
										</tr> 
										</table>
                                    </form>
                                </td>
                            </tr>
                        </table>
        </td>
    </tr>
</table>
<?php
 include("../template/footer.php");
?>



