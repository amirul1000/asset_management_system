<?php
 include("../template/header.php");
 ?>
 <b>Department</b><br />
	  <table cellspacing="3" cellpadding="3" border="0"  width="100%" class="bdr">
	   <tr>
                <td align="right" valign="top">

                  <form name="search_frm" id="search_frm" method="post">
                    <table width="60%" border="0"  cellpadding="2" cellspacing="1" class="bodytext">

                      <TR>

                        <TD  nowrap="nowrap">
                          <?php
                          $hash    =  getTableFieldsName("dept");
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

                        <TD  nowrap="nowrap" align="right"><label for="searchbar"><img src="../media/img/admin/icon_searchbox.png" alt="Search"></label> <input type="text"    name="field_value" id="field_value" value="<?=$_SESSION["field_value"]?>" class="textbox"></TD>
                        <td nowrap="nowrap" align="right">
                          <input type="hidden" name="cmd" id="cmd" value="search_Dept" >
                          <input type="submit" name="btn_submit" id="btn_submit"  value="Search" class="button" />
                        </td>
                      </TR>
                    </table>
                  </form>
                </td>
       </tr>
	  <tr>
	   <td>  
			<a href="Dept.php?cmd=edit" class="nav3">Add a Dept</a>
			<table cellspacing="3" cellpadding="3" border="0" class="bodytext">
				<tr bgcolor="#CCCCCC">
				    <th>Company </th>
					<th>Department </th>
					
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
			 
			 
								  
					$info["table"] = "dept left outer join company on(dept.ComID=company.id)";
					$info["fields"] = array("dept.*","company.ComName"); 
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
					<?=$arr[$i]['Department']?>
				  </td>
					
				  <td nowrap >
					  <a href="Dept.php?cmd=edit&id=<?=$arr[$i]['id']?>" class="nav">Edit</a> |
					  <a href="Dept.php?cmd=delete&id=<?=$arr[$i]['id']?>" class="nav" onClick=" return confirm('Are you sure to delete this item ?');">Delete</a> 
				 </td>
			
				</tr>
			<?php
					  }
			?>
			
			<tr>
			   <td colspan="8" align="center">
				  <?php                unset($info);
				  
									  $info["table"] = "dept left outer join company on(dept.ComID=company.id)";
									  $info["fields"] = array("dept.*","company.ComName"); 
									  $info["where"]   = "1  $whrstr ORDER BY id DESC";
									  
									  $res  = $db->select($info);  
				  
				  
										$numrows = count($res);
										$maxPage = ceil($numrows/$rowsPerPage);
										$self = 'Dept.php?cmd=list';
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











