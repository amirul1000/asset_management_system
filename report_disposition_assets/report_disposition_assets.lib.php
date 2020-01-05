<?php
/*
  makeSecondFromTime convert to second
*/
function makeSecondFromTime($time)
{
	if(ereg("days",$time,$arr) || ereg("day",$time,$arr))
	{
		
		$dt     = explode($arr[0],$time);
		$day    = $dt[0];
		
		$stime  = explode(":",$dt[1]);
		
		$hour   = $stime[0];
		$min    = $stime[1];
		$sec    = $stime[2];
		//convert to sec
		$ttime   = $day*24*60*60+$hour*60*60+$min*60+$sec;
	}
	else
	{
	
		$stime  = explode(":",$time);
		
		$hour   = $stime[0];
		$min    = $stime[1];
		$sec    = $stime[2];
		//convert to sec
		$ttime   = $hour*60*60+$min*60+$sec;
	} 
		  
		  return $ttime;
}
/*
  convertToDays 
  conver to day 
*/
function convertToDays($ttime)
{

	$day    = floor($ttime/(24*60*60));
	$ttime  = $ttime%(24*60*60);
	
	$hour   = floor($ttime/(60*60));  
	$ttime  = $ttime%(60*60);
	
	$min   = floor($ttime/60);
	$sec   = $ttime%60;
	
	if($day==1)
	{
	  $day = " 1 day ";
	}
	else if($day>1)
	{
	  $day = $day ." days ";
	}
	else
	{
	 $day = "";
	}
	
	return  $day. $hour.":".$min.":".$sec;
}




?>