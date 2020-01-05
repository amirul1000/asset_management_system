<?PHP
//remember to change the password to whatever you set
//it to in mysql instance configuration

//first parameter is server name, 2nd username 'root', 3rd is password
$rst = @mysql_connect("localhost","root","mysql");

if (!$rst){
	 echo( "<p>Unable to connect to database manager.</p>");
       die('Could not connect: ' . mysql_error());
	 exit();
} else {
  echo("<p>Successfully Connected to MySQL Database Manager!</p>");
}

if (! @mysql_select_db("asset_management") ){
	 echo( "<p>Unable to  connect database...</p>");
	 exit();
} else {
  echo("<p>Successfully Connected to Database 'Asset Management'!</p>");
}
?>
