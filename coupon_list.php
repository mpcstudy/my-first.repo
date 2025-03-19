<?php

// 접근권한이 운영자인 경우에만 허용
$func -> checkAdmin("/admin/index.php");

if (($_SESSION[ss_LEVEL] > 6)) {
	$setTag = "";
}
else {
	$setTag = "DISABLED";	
}

// searching
if($key_word) {
	$add_query .= " and  ($column LIKE '%$key_word%' ) ";
}


// sorting
if($switch) {
	$switched = $func -> switchOrder($switch, $switched);
	$add_query .= " ORDER BY $switch $switched ";
	$switched = $switch . "^" . $switched;
}else if($switched) {
	$switched1 = explode("^", $switched);
	$add_query .= " ORDER BY $switched1[0] $switched1[1] ";

} else {
	$add_query .= " ORDER BY uid DESC ";
}

$getSWHStr = $switched;

//total record
$query = "SELECT COUNT(uid) FROM tbl_pcodes where pcode != '' " . $add_query;
$total_count=$jdb->rQuery($query, "record query error");

//페이징변수설정
if(!$page) $page = 1;
if(!$list_count) $list_count = 20; //출력리스트 갯수
if(!$page_count) $page_count = 10; //출력페이지 갯수
$list_number = $total_count - (($page-1)*$list_count);
$start_number = $list_count * ($page-1);


$add_query .= " LIMIT $start_number, 20";
$query = "SELECT *  FROM tbl_pcodes where pcode != '' " . $add_query;
//echo"$query";
$result=$jdb->nQuery($query, "list error");

while($list=mysqli_fetch_array($result, MYSQLI_ASSOC)) {

	for($i=0; $i<sizeof($list); $i++) {
		list($key, $value) = each($list);
		$$key = $value;
	}
	
	if ($expiredate_from == "00000000")	$expiredate_from_str = "-";
	else $expiredate_from_str = $func -> convertFormat ($expiredate_from, 3);
	if ($expiredate_to == "99999999") $expiredate_to_str = "-";
	else $expiredate_to_str = $func -> convertFormat ($expiredate_to, 3);
	
	if ($drate != 'F')
		$drate_str = ($drate * 100)." %";
	else
		$drate_str = "Free Shipping";
		
	$strList .= "
		<!--tr onmouseover=this.style.background='#EFF1FC' onmouseout=this.style.background='' style='cursor:pointer;cursor:hand;' -->
		<tr onclick=\"#\" onmouseover=this.style.background='#EFF1FC' onmouseout=this.style.background='' style='cursor:pointer;cursor:hand;' >
			<td height='25' class='tc'>$list_number</td>
			<td class='tc'><a href=# onclick=\"revisewin=dhtmlwindow.open('revisewinpopup', 'iframe', '/admin/doc/coupon_popup.php?mode=modify&uid=$uid', 'Coupon Information', 'width=650px,height=350px,left=300px, top=150px, center=0, resize=0,scrolling=1'); return false\"><B>$pcode</B></a></td>
			<td class='tc'>".$drate_str."</td>
		  <td class='tc'>".$expiredate_from_str."</td>
		  <td class='tc'>".$expiredate_to_str."</td>
		  <td class='tc'>".$status."</td>
		  <td class='tc'>
					<a href=# onclick=\"revisewin=dhtmlwindow.open('revisewinpopup', 'iframe', '/admin/doc/coupon_popup.php?mode=modify&uid=$uid', 'Coupon Information', 'width=650px,height=350px,left=300px, top=150px, center=0, resize=0,scrolling=1'); return false\"><img src='/admin/images/icon_update.png' border=0 align='absmiddle'></a>
					<a href=# onclick=\"javascript:goDelete('$uid');\"><img src='/admin/images/icon_del.png' border=0 align='absmiddle'></a>
		  </td>
		</tr>
		<tr>
			<td height='1' colspan='10' style='border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;'>
			</td>
		</tr>	

	";
	$list_number--;
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

?>



<SCRIPT LANGUAGE='JAVASCRIPT'> 
function popup(str){ 
    window.open(str, 'SAME', 'width=600,height=300,resizable=1,scrollbars=1'); 
} 
</SCRIPT> 

<SCRIPT LANGUAGE=JAVASCRIPT>
<!--
function goSearch(f){
    var f = document.form1;
    if(trim(f.key_word.value).length < 1){
        alert('Enter keyword.');
        f.key_word.focus();
        return false;
    }
}

function goDelete(uid) {
	if(confirm("Are you sure to delete?")) {
		document.form2.uid.value=uid;
		document.form2.submit();
	}
}
	
//-->
</SCRIPT>

		<FORM name=form2 method=post action=doc/coupon_process.php>
		<input type=hidden name=uid value="">
		<input type=hidden name=mode value="delete">
		</FORM>

<p>
	<img src="images/icon-list.png" align="absmiddle">
	<font class="title">COUPON</font>
</p>



<div style="line-height:10px;">&nbsp;</div> 


<table width="95%" align="center"  border="0" align="center" cellpadding="0" cellspacing="2">
	<tr>
		<td>
			<div align=right>
			<INPUT TYPE=BUTTON VALUE='CREATE A NEW COUPON' ONCLICK="newwin=dhtmlwindow.open('createwinpopup', 'iframe', '/admin/doc/coupon_popup.php?mode=create', 'Coupon Information', 'width=650px,height=300px,left=300px, top=150px, center=0, resize=0,scrolling=1'); return false">
			</div>
		</td>
	</tr>
</table>

<table width="95%" align="center"  border="0" cellpadding="0" cellspacing="1" style="border-top-width:3px; border-bottom-width:3px; border-color:#dddddd; border-top-style:solid; border-bottom-style:solid;">
<tr>
<td height="30" class="tdthc">No</td>
<td class="tdthc">Code</td>	
<td class="tdthc">Discount Rate</td>
<td class="tdthc">From</td>	
<td class="tdthc">To</td>	
<td class="tdthc">Status</td>	
<td class="tdthc">Option</td>	
</tr>
<tr>
	<td height="1" colspan="8" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;">
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


<br />

<div align="center">
					<?
		
					$page_string = "view=$view&key_word=$key_word&key_CTGSEQ=$key_CTGSEQ&column=$column&mode=$mode&switched=$getSWHStr";
					$paging = new PAGE_ADM('page',$total_count,$list_count,$page_count,$page,$page_string,'red','','','336699','');
		
					?>

</div>

