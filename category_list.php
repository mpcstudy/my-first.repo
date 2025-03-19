<?php

$func->checkAdmin("/admin/index.php");


foreach ($arrClass AS $key=>$value)
{
	//echo "[$key][$value]";   => [0][Waiting][1][Confirmed][E][Declined]...
	if ($key == $ctg_class) $selectStr = "selected";
	else $selectStr = "";
	
	if ($key == "M" or $key == "P" or $key == "C" or $key == "B" or $key == "S") {
		$ctg_classSTR .= "
								<option value='$key' $selectStr>$value</option>";
	}
}

foreach ($arrGrade AS $key=>$value)
{
	//echo "[$key][$value]";   => [0][Waiting][1][Confirmed][E][Declined]...
	if ($key == $ctg_grade) $selectStr = "selected";
	else $selectStr = "";
	
	//if (($key >='G06' && $key <= 'G12') || $key == 'U01') {
		$ctg_gradeSTR .= "
									<option value='$key' $selectStr>$value</option>";
	//}
}

// searching
if($mode == "search") {
		$add_query = " AND ((CTGGRADE = '$ctg_grade') AND (CTGCLASS = '$ctg_class'))";

		$query = "SELECT COUNT(CTGSEQ) FROM tbl_category WHERE CTGNAME != '' AND CTGDEPTH = '1' ".$add_query;
		$total_count=$jdb->rQuery($query, "record query error");


		$qry1 = "SELECT * FROM tbl_category WHERE CTGNAME != '' AND CTGDEPTH = '1' ".$add_query." ORDER BY CTGPRIORITY ASC, CTGNAME ASC";
		//echo"$query";
		$rt1=$jdb->nQuery($qry1, "list error");

		$strList = "";

		while($lt1=mysqli_fetch_array($rt1, MYSQLI_ASSOC)) {
			
					$lt1['CTGNAME'] = str_replace("\\", "", $lt1['CTGNAME']);
					
					//$tmpStr .= "aaaa[".$lt1[CTGNAME]."][".$lt1[CTGDEPTH]."][".$lt1[CTGFIRSTCATEGORY]."-".$lt1[CTGSECONDCATEGORY]."-".$lt1[CTGTHIRDCATEGORY]."][".$lt1[CTGPRIORITY]."]\n";

					for($i=0; $i<sizeof($lt1); $i++) {
						list($key, $value) = each($lt1);
						$$key = $value;
					}

					$delete_button = "<a href=# onclick=\"javascript:open_page('delete', '$CTGSEQ');\"><img src='/admin/images/icon_del.png' border=0 align='absmiddle'></a>";
					$revise_button = "<a href=# onclick=\"revisewin=dhtmlwindow.open('revisewinpopup', 'iframe', '/admin/doc/revise_popup.php?CTGSEQ=$CTGSEQ', 'Revise Category Information', 'width=650px,height=350px,left=300px, top=150px, center=0, resize=0,scrolling=1'); return false\"><img src='/admin/images/icon_update.png' border=0 align='absmiddle'></a>";
					$create_button = "<a href=# onclick=\"createwin=dhtmlwindow.open('createwinpopup', 'iframe', '/admin/doc/create_popup.php?CTGSEQ=$CTGSEQ', 'Create Category Information', 'width=650px,height=350px,left=300px, top=150px, center=0, resize=0,scrolling=1'); return false\"><img src='/admin/images/icon_add.png' border=0 align='absmiddle'></a>";

					switch($CTGDEPTH) {
						case "1" : 
						$arrow = "red_arrow.gif";
						$category = $CTGFIRSTCATEGORY;
						$button = $create_button . "&nbsp;&nbsp;" . $revise_button . "&nbsp;&nbsp;" . $delete_button;
						$empty_space = "&nbsp;&nbsp;";
						break;
						
						case "2" : 
						$arrow = "green_arrow.gif";
						$category = $CTGFIRSTCATEGORY . "-" . $CTGSECONDCATEGORY;
						$button = $create_button . "&nbsp;&nbsp;" . $revise_button . "&nbsp;&nbsp;" . $delete_button;
						//$button = $revise_button . "&nbsp;" . $delete_button;
						$empty_space = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						break;
						
						case "3" : 
						$arrow = "yellow_arrow.gif";
						$category = $CTGFIRSTCATEGORY . "-" . $CTGSECONDCATEGORY . "-" . $CTGTHIRDCATEGORY;
						$button = $revise_button . "&nbsp;&nbsp" . $delete_button;
						$empty_space = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						break;
					}

					$CTGNAME = str_replace("\\", "", $CTGNAME);
					$CTGDESCRIPTION = str_replace("\\", "", $CTGDESCRIPTION);
					
					
					$strList .= "

						<!--tr onmouseover=this.style.background='#EFF1FC' onmouseout=this.style.background='' style='cursor:pointer;cursor:hand;' -->
						<tr onclick=\"#\" onmouseover=this.style.background='#EFF1FC' onmouseout=this.style.background='' style='cursor:pointer;cursor:hand;' >
							<td height='25' class='t2'>".$empty_space."<img src=/admin/images/$arrow>$CTGNAME<font size=1>($category)&nbsp;&nbsp;</font></td>
						  <td class='tc'>".$CTGPRIORITY."</td>
						  <td class='t2'>".$button."</td>
						</tr>
						<tr>
							<td height='1' colspan='3' style='border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;'>
							</td>
						</tr>

					";

			

					$qry2 = "SELECT * FROM tbl_category WHERE CTGNAME != '' AND CTGFIRSTCATEGORY = $lt1[CTGFIRSTCATEGORY] AND CTGDEPTH = '2' 
									 ".$add_query." ORDER BY CTGPRIORITY ASC, CTGNAME ASC";
					$rt2=$jdb->nQuery($qry2, "list error");

					while($lt2=mysqli_fetch_array($rt2, MYSQLI_ASSOC)) {

								$lt2['CTGNAME'] = str_replace("\\", "", $lt2['CTGNAME']);
					
								//$tmpStr .= "[".$lt2[CTGNAME]."][".$lt2[CTGDEPTH]."][".$lt2[CTGFIRSTCATEGORY]."-".$lt2[CTGSECONDCATEGORY]."-".$lt2[CTGTHIRDCATEGORY]."][".$lt2[CTGPRIORITY]."]\n";

								for($i=0; $i<sizeof($lt2); $i++) {
									list($key, $value) = each($lt2);
									$$key = $value;
								}

								$delete_button = "<a href=# onclick=\"javascript:open_page('delete', '$CTGSEQ');\"><img src='/admin/images/icon_del.png' border=0 align='absmiddle'></a>";
								$revise_button = "<a href=# onclick=\"revisewin=dhtmlwindow.open('revisewinpopup', 'iframe', '/admin/doc/revise_popup.php?CTGSEQ=$CTGSEQ', 'Revise Category Information', 'width=650px,height=350px,left=300px, top=150px, center=0, resize=0,scrolling=1'); return false\"><img src='/admin/images/icon_update.png' border=0 align='absmiddle'></a>";
								$create_button = "<a href=# onclick=\"createwin=dhtmlwindow.open('createwinpopup', 'iframe', '/admin/doc/create_popup.php?CTGSEQ=$CTGSEQ', 'Create Category Information', 'width=650px,height=350px,left=300px, top=150px, center=0, resize=0,scrolling=1'); return false\"><img src='/admin/images/icon_add.png' border=0 align='absmiddle'></a>";

								switch($CTGDEPTH) {
									case "1" : 
									$arrow = "red_arrow.gif";
									$category = $CTGFIRSTCATEGORY;
									$button = $create_button . "&nbsp;&nbsp;" . $revise_button . "&nbsp;&nbsp;" . $delete_button;
									$empty_space = "&nbsp;&nbsp;";
									break;
									
									case "2" : 
									$arrow = "green_arrow.gif";
									$category = $CTGFIRSTCATEGORY . "-" . $CTGSECONDCATEGORY;
									$button = $create_button . "&nbsp;&nbsp;" . $revise_button . "&nbsp;&nbsp;" . $delete_button;
									//$button = $revise_button . "&nbsp;" . $delete_button;
									$empty_space = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									break;
									
									case "3" : 
									$arrow = "yellow_arrow.gif";
									$category = $CTGFIRSTCATEGORY . "-" . $CTGSECONDCATEGORY . "-" . $CTGTHIRDCATEGORY;
									$button = $revise_button . "&nbsp;&nbsp" . $delete_button;
									$empty_space = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									break;
								}

								$CTGNAME = str_replace("\\", "", $CTGNAME);
								$CTGDESCRIPTION = str_replace("\\", "", $CTGDESCRIPTION);

								// Count Data
								$cat_cnt = 0;
								
								$qry_cnt = "SELECT COUNT(que_id) FROM tbl_question 
													WHERE que_category1 = '$CTGSEQ' ";

								$cat_cnt = $jdb->rQuery($qry_cnt, "record query error");


								$strList .= "

									<!--tr onmouseover=this.style.background='#EFF1FC' onmouseout=this.style.background='' style='cursor:pointer;cursor:hand;' -->
									<tr onclick=\"#\" onmouseover=this.style.background='#EFF1FC' onmouseout=this.style.background='' style='cursor:pointer;cursor:hand;' >
										<td height='25' class='t2'>".$empty_space."<img src=/admin/images/$arrow>$CTGNAME<font size=1>($category)&nbsp;&nbsp;</font><font color=red>($cat_cnt)</font></td>
									  <td class='tc'>".$CTGPRIORITY."</td>
									  <td class='t2'>".$button."</td>
									</tr>
									<tr>
										<td height='1' colspan='3' style='border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;'>
										</td>
									</tr>

								";
								


								$qry3 = "SELECT * FROM tbl_category WHERE CTGNAME != '' AND CTGFIRSTCATEGORY = $lt1[CTGFIRSTCATEGORY] 
													AND CTGSECONDCATEGORY = $lt2[CTGSECONDCATEGORY] AND CTGDEPTH = '3' ".$add_query." 
													ORDER BY CTGPRIORITY ASC, CTGNAME ASC";
								//echo"$query";
								$rt3=$jdb->nQuery($qry3, "list error");

								while($lt3=mysqli_fetch_array($rt3, MYSQLI_ASSOC)) {
											$lt3['CTGNAME'] = str_replace("\\", "", $lt3['CTGNAME']);
											//$tmpStr .= "[".$lt3[CTGNAME]."][".$lt3[CTGDEPTH]."][".$lt3[CTGFIRSTCATEGORY]."-".$lt3[CTGSECONDCATEGORY]."-".$lt3[CTGTHIRDCATEGORY]."][".$lt3[CTGPRIORITY]."]\n";

											for($i=0; $i<sizeof($lt3); $i++) {
												list($key, $value) = each($lt3);
												$$key = $value;
											}

											$delete_button = "<a href=# onclick=\"javascript:open_page('delete', '$CTGSEQ');\"><img src='/admin/images/icon_del.png' border=0 align='absmiddle'></a>";
											$revise_button = "<a href=# onclick=\"revisewin=dhtmlwindow.open('revisewinpopup', 'iframe', '/admin/doc/revise_popup.php?CTGSEQ=$CTGSEQ', 'Revise Category Information', 'width=650px,height=350px,left=300px, top=150px, center=0, resize=0,scrolling=1'); return false\"><img src='/admin/images/icon_update.png' border=0 align='absmiddle'></a>";
											$create_button = "<a href=# onclick=\"createwin=dhtmlwindow.open('createwinpopup', 'iframe', '/admin/doc/create_popup.php?CTGSEQ=$CTGSEQ', 'Create Category Information', 'width=650px,height=350px,left=300px, top=150px, center=0, resize=0,scrolling=1'); return false\"><img src='/admin/images/icon_add.png' border=0 align='absmiddle'></a>";

											switch($CTGDEPTH) {
												case "1" : 
												$arrow = "red_arrow.gif";
												$category = $CTGFIRSTCATEGORY;
												$button = $create_button . "&nbsp;&nbsp;" . $revise_button . "&nbsp;&nbsp;" . $delete_button;
												$empty_space = "&nbsp;&nbsp;";
												break;
												
												case "2" : 
												$arrow = "green_arrow.gif";
												$category = $CTGFIRSTCATEGORY . "-" . $CTGSECONDCATEGORY;
												$button = $create_button . "&nbsp;&nbsp;" . $revise_button . "&nbsp;&nbsp;" . $delete_button;
												//$button = $revise_button . "&nbsp;" . $delete_button;
												$empty_space = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												break;
												
												case "3" : 
												$arrow = "yellow_arrow.gif";
												$category = $CTGFIRSTCATEGORY . "-" . $CTGSECONDCATEGORY . "-" . $CTGTHIRDCATEGORY;
												$button = $revise_button . "&nbsp;&nbsp" . $delete_button;
												$empty_space = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												break;
											}

											$CTGNAME = str_replace("\\", "", $CTGNAME);
											$CTGDESCRIPTION = str_replace("\\", "", $CTGDESCRIPTION);

											// Count Data
											$cat_cnt = 0;
											
											$qry_cnt = "SELECT COUNT(que_id) FROM tbl_question 
																WHERE que_category1 = '$CTGSEQ' ";

											$cat_cnt = $jdb->rQuery($qry_cnt, "record query error");

											$strList .= "

												<!--tr onmouseover=this.style.background='#EFF1FC' onmouseout=this.style.background='' style='cursor:pointer;cursor:hand;' -->
												<tr onclick=\"#\" onmouseover=this.style.background='#EFF1FC' onmouseout=this.style.background='' style='cursor:pointer;cursor:hand;' >
													<td height='25' class='t2'>".$empty_space."<img src=/admin/images/$arrow>$CTGNAME<font size=1>($category)&nbsp;&nbsp;</font><font color=red>($cat_cnt)</font></td>
												  <td class='tc'>".$CTGPRIORITY."</td>
												  <td class='t2'>".$button."</td>
												</tr>
												<tr>
													<td height='1' colspan='3' style='border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;'>
													</td>
												</tr>

											";
											

								}

					}
				
		}


		if( $total_count < 1 ) {
			$TMP_LENGTH = 200;		// Data 없는 경우 Window Height 좁아지면 이상하게 보이므로 일정 크기 유지
			$strList = "
				<tr height=25><td colspan='3' align=center><B>No Data</B></td></tr>
				<tr>
					<td height='1' colspan='3' style='border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;'>
					</td>
				</tr>		
				";
		}



}


?>

<SCRIPT LANGUAGE=JAVASCRIPT>
<!--
function open_page(mode, CTGSEQ) {

	if(mode == "delete") {
		if(confirm("Are you sure to delete it?")) {
			form1.CTGSEQ.value = CTGSEQ;
			form1.submit();
		}
	}else {
		var destination = "/admin/doc/" + mode + "_popup.php?CTGSEQ=" + CTGSEQ;

		if (mode == 'headline') 
			window.open(destination, 'SAME', 'width=600,height=450,resizable=0,scrollbars=0');
		else if (mode == 'favorites') 
			window.open(destination, 'SAME', 'width=600,height=450,resizable=0,scrollbars=0');
		else 
			window.open(destination, 'SAME', 'width=650,height=330,resizable=0,scrollbars=0');
	}

}
//-->
</SCRIPT>


<SCRIPT LANGUAGE=JAVASCRIPT>
<!--
function goSearch(f){
	var str_class = document.getElementById("ctg_classid").value;
	var str_grade = document.getElementById('ctg_gradeid').value

  if (str_class=="") {
    alert("Please select the class.");
		document.getElementById("ctg_classid").focus();
		return false;
  } 

  if (str_grade=="") {
    alert("Please select the grade.");
		document.getElementById("ctg_gradeid").focus();
		return false;
  } 
}
//-->
</SCRIPT>

<FORM name=form1 method=post action=/admin/doc/delete_category.php>
<INPUT type=hidden name=CTGSEQ value="">
<INPUT type=hidden name=ctg_class value="<?=$ctg_class?>">
<INPUT type=hidden name=ctg_grade value="<?=$ctg_grade?>">
</FORM>

<p>
	<img src="images/icon-list.png" align="absmiddle">
	<font class="title">CATEGORY</font>
</p>



<div style="line-height:10px;">&nbsp;</div> 


<!-- Start of Question Information -->

<!--p><img src="images/icon-narr.png" align="absmiddle"><font class="stitle">CATEGORY INFORMATION</font></p-->

		<FORM METHOD=POST NAME=form_search ONSUBMIT='return goSearch(this)' action='<?=$_SERVER["PHP_SELF"]?>'>
		<INPUT TYPE=HIDDEN NAME=view VALUE='category_list'>
		<INPUT TYPE=HIDDEN NAME=mode VALUE='search'>
		<INPUT TYPE=HIDDEN NAME=key_word VALUE='<?=$key_word?>'>
		
<table width="95%" align="center"  border="0" align="center" cellpadding="0" cellspacing="2">
	<tr>
		<TD width=40%>
								
				CLASS
				<SELECT NAME='ctg_class' id="ctg_classid" style="width:200px; height:30px;" class="mbox">													
		    	<option value='' >Select</option>
					<?=$ctg_classSTR?>
				</SELECT>	
				&nbsp;&nbsp;

				GRADE
				<SELECT NAME='ctg_grade' id="ctg_gradeid" style="width:200px; height:30px;" class="mbox">													
		    	<option value='' >Select</option>
					<?=$ctg_gradeSTR?>
				</SELECT>	
				&nbsp;&nbsp;
				
				<input align="absmiddle" type=image src="images/btn-search.png" />
		</TD>						
	</tr>
</table>

</FORM>

<br>

<? if($mode == "search") { ?>

<!--table width="95%" align="center"  border="0" align="center" cellpadding="0" cellspacing="2">
	<tr>
		<td>
			<div align=right>
			<INPUT TYPE=BUTTON VALUE='CREATE A CATEGORY' ONCLICK="newwin=dhtmlwindow.open('createwinpopup', 'iframe', '/admin/doc/create_popup.php', 'Create Category Information', 'width=650px,height=300px,left=300px, top=150px, center=0, resize=0,scrolling=1'); return false">
			</div>
		</td>
	</tr>
</table-->


<table width="95%" align="center"  border="0" cellpadding="0" cellspacing="1" style="border-top-width:3px; border-bottom-width:3px; border-color:#dddddd; border-top-style:solid; border-bottom-style:solid;">
<tr>
<td height="30" class="tdthl">Category Name</td>
<td class="tdthc">Priority</td>	
<td class="tdthl">Option</td>
</tr>
<tr>
	<td height="1" colspan="3" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;">
	</td>
</tr>

<?=$strList?>

<!--
<tr onmouseover=this.style.background="#dcdcdc" onmouseout=this.style.background="" style="cursor:pointer;cursor:hand;" >
	<td height="25" class="tc"><a href='index.php?view=info'>123</a></td>
  <td class="tc">123456789012345</td>
  <td class="tc">123456789012345</td>
  <td class="tc">123456789012345</td>
  <td class="tc">ABCDEFGH ABCDEFGH</td>
  <td class="tc">ABCDEFGH ABCDEFGH</td>
  <td class="tc">ON ROUTE ABCDEFGH</td>
  <td class="tc">2014-03-01</td>
</tr>
<tr>
	<td height="1" colspan="8" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;">
	</td>
</tr>
-->

</table>

<? } ?>