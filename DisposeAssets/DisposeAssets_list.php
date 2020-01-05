<?php
 include("../template/header.php");
 ?>
 <script language="javascript">
   
		function showHide(ID)
		{
		  
		  if(document.getElementById(ID).style.display == "block")
		  {
		  document.getElementById(ID).style.display = "none"
		  }
		  else if(document.getElementById(ID).style.display == "none")
		  {
		  document.getElementById(ID).style.display = "block"
		  }
		}
function toggle5(showHideDiv, switchImgTag) 
         {
		var ele = document.getElementById(showHideDiv);
		var imageEle = document.getElementById(switchImgTag);
		if(ele.style.display == "block") {
			ele.style.display = "none";
			imageEle.innerHTML = '<img src="../images/plus.png" border="0">';
		}
		else {
			ele.style.display = "block";
			imageEle.innerHTML = '<img src="../images/minus.png" border="0">';
		}
        }				
 </script>
  <b>DisposeAssets</b><br />
	  <table cellspacing="3" cellpadding="3" border="0"  width="100%" class="bdr">
	   <tr>
                <td align="right" valign="top">

                  <form name="search_frm" id="search_frm" method="post">
                    <table width="60%" border="0"  cellpadding="2" cellspacing="1" class="bodytext">

                      <TR>

                        <TD  nowrap="nowrap">
                          <?php
                          $hash    =  getTableFieldsName("disposeassets");
                          $hash    = array_diff($hash,array("id"));
                          ?>

                          Search Key:
                          <select   name="field_name" id="field_name"  class="textbox">
                            <option value="">--Select--</option>
                            <?php
                            foreach($hash as $key=>$value)
                            {
                              ?>
                            <option value="<?=$key?>" <?php if($_SESSION['field_name']==$key) echo "selected"; ?>><?=str_replace("_"," ",$value)?></option>
                            <?php
                          }
                          ?>
                          </select>

                        </TD>

                        <TD  nowrap="nowrap" align="right"><label for="searchbar"><img src="../media/img/admin/icon_searchbox.png" alt="Search"></label> 
						         <input type="text"    name="field_value" id="field_value" value="<?=$_SESSION["field_value"]?>" class="textbox"></TD>
                        <td nowrap="nowrap" align="right">
                          <input type="hidden" name="cmd" id="cmd" value="search_DisposeAssets" >
                          <input type="submit" name="btn_submit" id="btn_submit"  value="Search" class="button" />
                        </td>
                      </TR>
                    </table>
                  </form>
                </td>
       </tr>
	  <tr>
	   <td>  
			<a href="DisposeAssets.php?cmd=edit" class="nav3">Add a DisposeAssets</a>
			<table cellspacing="3" cellpadding="3" border="0" class="bodytext">
				<tr bgcolor="#CCCCCC">
				    <th>Company </th>
					<th>TDate </th>
					<th>ACode </th>
					<th>ReasonForSale </th>
					<th>NetProceeds </th>
					<th>BookValue </th>
					<th>GainLoss </th>
					<th>Action</th>
				</tr>
			 <?php
			 		
			 		if($_SESSION["search"]=="yes")
					  {
						$whrstr = " AND ".$_SESSION['field_name']." LIKE '%".$_SESSION["field_value"]."%'";
					  }
					  else
					  {
						$whrstr = "";
					  }
			 
			 
			 
					$rowsPerPage = 10;
					$pageNum = 1;
					if(isset($_REQUEST['page']))
					{
						$pageNum = $_REQUEST['page'];
					}
					$offset = ($pageNum - 1) * $rowsPerPage;  
			 
			 
								  
					$info["table"] = "disposeassets left outer join company on(disposeassets.ComID=company.id)";
					$info["fields"] = array("disposeassets.*","company.ComName"); 
					$info["where"]   = "1   $whrstr ORDER BY id DESC  LIMIT $offset, $rowsPerPage";
										
					
					$arr =  $db->select($info);
					
					for($i=0;$i<count($arr);$i++)
					{
					
					    $rowColor;
			
						if($i % 2 == 0)
						{
							
							$row="row1";
						}
						else
						{
							
							$row="row2";
						}
					
			 ?>
				<tr class="<?=$row?>" >
				    <td>
					<?=$arr[$i]['ComName']?>
				  </td>
				  <td>
					<?=date("F j, Y",strtotime($arr[$i]['TDate']))?>
				  </td>
				  <td>
					<?=$arr[$i]['ACode']?>
				  </td>
				  <td>
					<?=$arr[$i]['ReasonForSale']?>
				  </td>
				  
				     <td>
					<?=$arr[$i]['NetProceeds']?>
				  </td>
				  <td>
					<?=$arr[$i]['BookValue']?>
				  </td>
				  <td>
					<?=$arr[$i]['GainLoss']?>
				  </td>
					
				  <td nowrap >
					  <a href="DisposeAssets.php?cmd=undo&id=<?=$arr[$i]['id']?>" class="nav">Undo</a> |
					  <a href="DisposeAssets.php?cmd=delete&id=<?=$arr[$i]['id']?>" class="nav" onClick=" return confirm('Are you sure to delete this item ?');">Delete</a> 
				 </td>
			
				</tr>
				<tr>
				  <td colspan="9" align="left" >
					 <A NAME="name_show_hide_div_<?=$arr[$i]['id']?>"></A>
					<a href="#name_show_hide_div_<?=$arr[$i]['id']?>"  border="0"  title="Details">
					 	
					<div id="headerDivImg">
						<a id="imageDivLink_<?=$arr[$i]['id']?>" href="javascript:toggle5('show_hide_div_<?=$arr[$i]['id']?>', 'imageDivLink_<?=$arr[$i]['id']?>');" ><img src="../images/plus.png" border="0"></a>
					</div>
					
					 <div  id="show_hide_div_<?=$arr[$i]['id']?>" style="display: none;">
					 <?php
					     $Id=$arr[$i]['id'];
					    include("DisposeAssets_details.php");
					 ?>
				     </div>
				</td>
				</tr>
				
				
			<?php
					  }
			?>
			
			<tr>
			   <td colspan="8" align="center">
				  <?php                unset($info);
				  
									  $info["table"] = "disposeassets left outer join company on(disposeassets.ComID=company.id)";
									  $info["fields"] = array("disposeassets.*","company.ComName"); 
									  $info["where"]   = "1  $whrstr ORDER BY id DESC";
									  
									  $res  = $db->select($info);  
				  
				  
										$numrows = count($res);
										$maxPage = ceil($numrows/$rowsPerPage);
										$self = 'DisposeAssets.php?cmd=list';
										$nav  = '';
										
										$start    = ceil($pageNum/5)*5-5+1;
										$end      = ceil($pageNum/5)*5;
										
										if($maxPage<$end)
										{
										  $end  = $maxPage;
										}
										
										for($page = $start; $page <= $end; $page++)
										//for($page = 1; $page <= $maxPage; $page++)
										{
											if ($page == $pageNum)
											{
												$nav .= " $page "; 
											}
											else
											{
												$nav .= " <a href=\"$self&&page=$page\" class=\"nav\">$page</a> ";
											} 
										}
										if ($pageNum > 1)
										{
											$page  = $pageNum - 1;
											$prev  = " <a href=\"$self&&page=$page\" class=\"nav\">[Prev]</a> ";
									
										   $first = " <a href=\"$self&&page=1\" class=\"nav\">[First Page]</a> ";
										} 
										else
										{
											$prev  = '&nbsp;'; 
											$first = '&nbsp;'; 
										}
									
										if ($pageNum < $maxPage)
										{
											$page = $pageNum + 1;
											$next = " <a href=\"$self&&page=$page\" class=\"nav\">[Next]</a> ";
									
											$last = " <a href=\"$self&&page=$maxPage\" class=\"nav\">[Last Page]</a> ";
										} 
										else
										{
											$next = '&nbsp;'; 
											$last = '&nbsp;'; 
										}
										
										if($numrows>1)
										{
										  echo $first . $prev . $nav . $next . $last;
										}
										
									?>          
			   
			   </td>
			</tr>
			</table>
	
	</td>
	</tr>
	</table>
	
<?php
 include("../template/footer.php");
?>











