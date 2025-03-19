<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/session_include.php";
?>

<?

include getenv("DOCUMENT_ROOT")."/include/session_include.php";

$func->checkAdmin("/admin/index.php");

if ($mode == "") $mode = "update";

if ($mode == "create") {
	$btnName = "CREATE";
}
else if ($mode == "update") {
	$btnName = "UPDATE";
	$admTag = "DISABLED";
}

// User data query
if ($mode != "create") {
	
	if ($adm_id == "") $adm_id = $_SESSION['ss_AUID'];

	$query = "SELECT * FROM tbl_admusers WHERE adm_id = '$adm_id'";
	$result = $jdb->fQuery($query, "query error");
	
	//echo "[$query]";
	for($i=0; $i<sizeof($result); $i++) {
    foreach ($result as $key => $value) {
      break;  // 첫 번째 요소만 가져오기 위해 `break` 사용
  }
		$$key = $value;
	}
	
	$adm_fnameSTR = str_replace("\\", "", $adm_fname);
	$adm_lnameSTR = str_replace("\\", "", $adm_lname);
	

	$adm_lastloginSTR = $func -> convertFormat ($adm_lastlogin, 1);
	$adm_createddateSTR = $func -> convertFormat ($adm_createddate, 1);

	// Get Company Info
	//$qry = "SELECT * FROM tbl_company WHERE CUID = '$MCOMPANY'";
	//$rt = $jdb->fQuery($qry, "fetch query error"); 
	//$mcompanyStr = $rt[CNAME];
	
}

?>

<? include getenv("DOCUMENT_ROOT")."/admin/include/header.php"; ?>

<p><img src="/admin/images/icon-narr.png" align="absmiddle"><font class="stitle">USER INFORMATION</font></p>

<FORM NAME=memberInfo METHOD=POST ACTION=/admin/doc/personal_process.php ONSUBMIT='return validate(this);'>

                      <input type=hidden name=mode value="<?=$mode?>">
                      <input type=hidden name=actionPage value="adminInfo">
                      <input type=hidden name=adm_id value="<?=$adm_id?>">
                      <input type=hidden name=directurl value="index.php">
	 
<table width="95%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-top-width:3px; border-bottom-width:3px; border-color:#dddddd; border-top-style:solid; border-bottom-style:solid;">
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="/admin/images/icon-round.png" hspace="10" align="absmiddle"><b>User ID</b></td>
    <td class="tl">
			<INPUT TYPE='text' NAME='adm_userid' required='required' option='email' VALUE='<?=$adm_userid?>' <?=$admTag?> maxlength=60 style="width:200px; height:24px;" class="mbox">  
    </td>
  </tr>
  <tr>
    <td height="1" colspan="2" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
  </tr>
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="/admin/images/icon-round.png" hspace="10" align="absmiddle"><b>Password</b></td>
    <td class="tl">
    	<INPUT TYPE='password' NAME='adm_password' required='required' match='passwordcheck' <?=$setTag?> VALUE='' maxlength=20 style="width:200px; height:24px;" class="mbox"><br>
			(Use between 4 and 15 letters and/or numbers)
    </td>
  </tr>
  <tr>
    <td height="1" colspan="2" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
  </tr>
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="/admin/images/icon-round.png" hspace="10" align="absmiddle"><b>Re-Password</b></td>
    <td class="tl">
    	<INPUT TYPE='password' NAME='passwordcheck' required='required' <?=$setTag?> VALUE='' maxlength=20 style="width:200px; height:24px;" class="mbox">
    </td>
  </tr>
  <tr>
    <td height="1" colspan="2" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
  </tr>  
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="/admin/images/icon-round.png" hspace="10" align="absmiddle"><b>First Name</b></td>
    <td class="tl">
    	<INPUT TYPE='text' NAME='adm_fname' required='required' <?=$setTag?> VALUE='<?=htmlspecialchars($adm_fnameSTR, ENT_QUOTES)?>' maxlength=50 style="width:200px; height:24px;" class="mbox">
    </td>
  </tr>
  <tr>
    <td height="1" colspan="2" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
  </tr>
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="/admin/images/icon-round.png" hspace="10" align="absmiddle"><b>Last Name</b></td>
    <td class="tl">
    	<INPUT TYPE='text' NAME='adm_lname' required='required' <?=$setTag?> VALUE='<?=htmlspecialchars($adm_lnameSTR, ENT_QUOTES)?>' maxlength=50 style="width:200px; height:24px;" class="mbox">
    </td>
  </tr>
  <tr>
    <td height="1" colspan="2" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
  </tr>
<!--
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="images/icon-round.png" hspace="10" align="absmiddle"><b>Company</b></td>
    <td class="tl"><?=$mcompanyStr?></td>
  </tr>
  <tr>
    <td height="1" colspan="2" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
  </tr>
-->
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="/admin/images/icon-round.png" hspace="10" align="absmiddle"><b>Phone</b></td>
    <td class="tl">
    	<INPUT TYPE='text' NAME='adm_phone' <?=$setTag?> VALUE='<?=$adm_phone?>' maxlength=20 style="width:200px; height:24px;" class="mbox">
    </td>
  </tr>
  <tr>
    <td height="1" colspan="2" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
  </tr>
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="/admin/images/icon-round.png" hspace="10" align="absmiddle"><b>Cell</b></td>
    <td class="tl">
    	<INPUT TYPE='text' NAME='adm_cell' <?=$setTag?> VALUE='<?=$adm_cell?>' maxlength=20 style="width:200px; height:24px;" class="mbox">
    </td>
  </tr>
  <tr>
    <td height="1" colspan="2" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
  </tr>

</table><br />

<div align=center><input type=submit value=<?=$btnName?>>
<input type=button value=CLOSE ONCLICK="parent.adminwinpopup.hide(); return false;">
</div>
                              
</FORM>