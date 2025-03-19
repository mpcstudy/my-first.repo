<?php

$func->checkAdmin("index.php");


//echo "[$_SESSION[ss_LEVEL]]";

// 접근권한이 운영자인 경우에만 허용
//$func -> checkAdmin("/index.php");

if (($_SESSION[ss_ALEVEL] == 1)) {
	$setTag = "";
}
else {
	$setTag = "DISABLED";	
}

//$add_query .= "tbl_groups   INNER JOIN naloxca_bbs.tbl_customer ON (tbl_groups.g_uid = tbl_customer.m_gid) ";

$add_srchquery = "";
$add_query = "";

// searching
if($key_word) {
	$key_wordStr = urlencode(trim($key_word));
	$add_srchquery .= " AND  ((user_userid LIKE '%$key_wordStr%') OR (user_fname LIKE '%$key_wordStr%') OR (user_lname LIKE '%$key_wordStr%') OR (user_phone LIKE '%$key_wordStr%') OR (user_cell LIKE '%$key_wordStr%'))";
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
	$add_query .= " ORDER BY user_id ASC";
}

$getSWHStr = $switched;

//total record
$query = "SELECT COUNT(user_id) FROM tbl_user 
					WHERE user_id <> '' " . $add_allquery . $add_srchquery . $add_query;

$total_count=$jdb->rQuery($query, "record query error");
//echo "[$total_count][$query]<br>";
//페이징변수설정
if(!$page) $page = 1;
if(!$list_count) $list_count = $INIT_PAGECNT; //출력리스트 갯수
if(!$page_count) $page_count = 10; //출력페이지 갯수
$list_number = $total_count - (($page-1)*$list_count);
$start_number = $list_count * ($page-1);

$add_query .= " LIMIT $start_number, $INIT_PAGECNT";

$query = "SELECT * FROM tbl_user 
					WHERE user_id <> '' " . $add_allquery . $add_srchquery . $add_query;

//echo "[$query]";
$result=$jdb->nQuery($query, "list error");

while($list=mysqli_fetch_array($result, MYSQLI_ASSOC)) {

	for($i=0; $i<sizeof($list); $i++) {
		list($key, $value) = each($list);
		$$key = $value;
	}

	//$logindateStr = $func -> convertFormat ($MLOGINDATE, 1);
	//$signupdateStr = $func -> convertFormat ($MSIGNUPDATE, 1);
		
	//$qry = "SELECT CNAME  FROM tbl_company WHERE CUID = '$MCOMPANY' ";
	//$rtd=$jdb->fQuery($qry, "fetch query error"); 
	//if ($MCOMPANY == "0" || $MCOMPANY == "") $companyStr = "-";
	//else $companyStr = $rtd[CNAME];

	
	$user_fnameSTR = str_replace("\\", "", $user_fname);
	$user_lnameSTR = str_replace("\\", "", $user_lname);
	
	if ($user_servicefrom)	$user_servicefromSTR = substr($user_servicefrom,0,4)."-".substr($user_servicefrom,4,2)."-".substr($user_servicefrom,6,2);
	else $user_servicefromSTR = "";
	
	if ($user_serviceto)	$user_servicetoSTR = substr($user_serviceto,0,4)."-".substr($user_serviceto,4,2)."-".substr($user_serviceto,6,2);
	else $user_servicettoSTR = "";
	
	if ($_SESSION['ss_ALEVEL'] == 1) {
		$OPTIONstr = "<a href=# onclick=\"javascript:goDelete('$user_id');\" $setTag><img src='/admin/images/icon_del.png' border=0 align='absmiddle'></a>";
		$OPTIONTITLEstr = "Option";
	}
	else {
		$OPTIONstr = "";
		$OPTIONTITLEstr = "";
	}

	$strList .= "
	
		<!--tr onmouseover=this.style.background='#EFF1FC' onmouseout=this.style.background='' style='cursor:pointer;cursor:hand;' -->
		<tr onclick=\"#\" onmouseover=this.style.background='#EFF1FC' onmouseout=this.style.background='' style='cursor:pointer;cursor:hand;' >
			<td height='25' class='tc'>$list_number</td>
			<td class='t2'><a href='/admin/index.php?view=user_info&mode=update&user_id=$user_id&page=$page&key_word=$key_word&column=$column&switched=$getSWHStr&sorting_type=$sorting_type&switch=$switch'>$user_userid</a></td>
		  <td class='t2'><a href='/admin/index.php?view=user_info&mode=update&user_id=$user_id&page=$page&key_word=$key_word&column=$column&switched=$getSWHStr&sorting_type=$sorting_type&switch=$switch'><b>$user_fnameSTR, $user_lnameSTR</b></a></td>
		  <td class='t2'>$user_phone</td>
		  <td class='t2'>$arrGrade[$user_grade]</td>
		  <td class='t2'>$user_servicefromSTR ~ $user_servicetoSTR</td>
		  <td class='tc'>$arrStatus[$user_status]</td>
		  <td class='tc'>$OPTIONstr</td>
		</tr>
		<tr>
			<td height='1' colspan='8' style='border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;'>
			</td>
		</tr>

	";
	$list_number--;
}

if( $total_count < 1 ) {
	$TMP_LENGTH = 200;		// Data 없는 경우 Window Height 좁아지면 이상하게 보이므로 일정 크기 유지
	$strList = "
		<tr height=25><td colspan='8' align=center><B>No Data</B></td></tr>
		<tr>
			<td height='1' colspan='8' style='border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;'>
			</td>
		</tr>		
		";
}

?>

<style type="text/css">
input[type=submit] {
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  border-radius: 0px;
  border:none;
  font-family: open-sans, sans-serif;
  color: #ffffff;
  font-size: 14px;
  background: #0F216C;
  padding: 8px 18px 8px 18px;
  text-decoration: none;
}

input[type=submit]:hover {
  background: #1734A8;
  color: #ffffff;
  text-decoration: none;
}

.update {
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  border-radius: 0px;
  font-family: open-sans, sans-serif;
  color: #ffffff;
  font-size: 14px;
  background: #0F216C;
  padding: 8px 18px 8px 18px;
  text-decoration: none;
}

.update:hover {
  background: #1734A8;
  color: #ffffff;
  text-decoration: none;
}

</style>
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

function goDelete(user_id) {
	if(confirm("Are you sure to delete?")) {
		document.form2.user_id.value=user_id;
		document.form2.submit();
	}
}
	
//-->
</SCRIPT>

<FORM name=form2 method=post action=doc/user_process.php>
<input type=hidden name=user_id value="">
<input type=hidden name=mode value="delete">
<input type=hidden name=actionStr value="USER">

<input type=hidden name=switched value="<?=$getSWHStr?>">
<input type=hidden name=page value="<?=$page?>">
<input type=hidden name=key_word value="<?=$key_word?>">
<input type=hidden name=column value="<?=$column?>">
<input type=hidden name=sorting_type value="<?=$sorting_type?>">
<input type=hidden name=switch value="<?=$switch?>">
</FORM>
		

<p><img src="images/icon-list.png" align="absmiddle"><font class="title">CUSTOMER</font></p>

<FORM METHOD=POST NAME=form1 ONSUBMIT='return goSearch(this)' action='<?=$_SERVER["PHP_SELF"]?>'>
<INPUT TYPE=HIDDEN NAME=view VALUE='user_list'>

<table width=95% align="center" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="20">&nbsp;</td>  
    <td width=40%><input type=text name=key_word style='width:150px' value='<?=$key_word?>' style="width:180px; height:40px;" >&nbsp;&nbsp;<input type=image src="images/btn-search.png" align="absmiddle"/></td>
    <td width=60%><div align=right><a href="/admin/index.php?view=user_info&mode=create&page=<?=$page?>&key_word=<?=$key_word?>&column=<?=$column?>&switched=<?=$getSWHStr?>&sorting_type=<?=$sorting_type?>&switch=<?=$switch?>" ><span class='update' style='cursor:pointer'>CREATE</span></a></div></td>
  </tr>
</table>

</FORM>

<!--
<table width="95%" align="center"  border="0" align="center" cellpadding="0" cellspacing="2">
	<tr>
		<td>
			<div align=right>
-->
			<!--a href=# ONCLICK="memberwin=dhtmlwindow.open('customerwinpopup', 'iframe', '/doc/customer_info.php?mode=create', 'Create Customer Information', 'width=650px,height=500px,left=300px, top=150px, center=0, resize=0,scrolling=1'); return false"><img src=/images/sbtn-create.png border=0></a-->
<!--			<a href='/index.php?view=customer_info&mode=create&switched=<?=$getSWHStr?>&page=<?=$page?>&key_word=<?=$key_word?>&column=<?=$column?>&sorting_type=<?=$sorting_type?>&switch=<?=$switch?>'><img src=/images/sbtn-create.png border=0></a>
			</div>
		</td>
	</tr>
</table>
-->

<table width="95%" align="center"  border="0" cellpadding="0" cellspacing="1" style="border-top-width:3px; border-bottom-width:3px; border-color:#dddddd; border-top-style:solid; border-bottom-style:solid;">
<tr>
<td height="30" class="tdthc">NO</td>
<td class="tdthl"><a href=/admin/index.php?view=user_info&switched=<?=$getSWHStr?>&page=<?=$page?>&key_word=<?=$key_word?>&column=<?=$column?>&sorting_type=<?=$sorting_type?>&switch=user_userid>User ID</a></td>
<td class="tdthl"><a href=/admin/index.php?view=user_info&switched=<?=$getSWHStr?>&page=<?=$page?>&key_word=<?=$key_word?>&column=<?=$column?>&sorting_type=<?=$sorting_type?>&switch=user_fname>User Name</a></td>
<td class="tdthl"><a href=/admin/index.php?view=user_info&switched=<?=$getSWHStr?>&page=<?=$page?>&key_word=<?=$key_word?>&column=<?=$column?>&sorting_type=<?=$sorting_type?>&switch=user_phone>Phone</a></td>
<td class="tdthl"><a href=/admin/index.php?view=user_info&switched=<?=$getSWHStr?>&page=<?=$page?>&key_word=<?=$key_word?>&column=<?=$column?>&sorting_type=<?=$sorting_type?>&switch=user_grade>Grade</a></td>	
<td class="tdthl">Service</td>		
<td class="tdthc"><a href=/admin/index.php?view=user_info&switched=<?=$getSWHStr?>&page=<?=$page?>&key_word=<?=$key_word?>&column=<?=$column?>&sorting_type=<?=$sorting_type?>&switch=user_status>Status</a></td>	
<td class="tdthc"><?=$OPTIONTITLEstr?></td>


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



<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width=150 height="<?=$TMP_LENGTH?>">&nbsp;</td>
  </tr>
</table>
