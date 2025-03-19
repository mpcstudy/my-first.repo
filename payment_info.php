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
if ($mode == "view" && $UID != "") {
	
	$query = "SELECT * FROM tbl_order WHERE UID = '$UID'";
	$result = $jdb->fQuery($query, "query error");
	
	//echo "[$query]";
	for($i=0; $i<sizeof($result); $i++) {
    foreach ($result as $key => $value) {
      break;  // 첫 번째 요소만 가져오기 위해 `break` 사용
  }
		$$key = $value;
	}
	
	$ORDNMSstr = str_replace("\\", "", $ORDNMS);
	$C_FIRSTNAMEstr = str_replace("\\", "", $C_FIRSTNAME);
	$C_LASTNAMEstr = str_replace("\\", "", $C_LASTNAME);
	
	$ORDCREATEDATEstr = $func -> convertFormat ($ORDCREATEDATE, 1);
	$ORDPAYDATEstr = $func -> convertFormat ($ORDPAYDATE, 1);
	
	if ($ORDSTATUS == '1') $ORDSTATUSstr = "Paid &nbsp;&nbsp;&nbsp;(Approved No: ".$OTID.")";
	else $ORDSTATUSstr = "Failed &nbsp;&nbsp;&nbsp;(Msg: ".$OERRNO."-".$OERRMSG.")";

	// Get Company Info
	//$qry = "SELECT * FROM tbl_company WHERE CUID = '$MCOMPANY'";
	//$rt = $jdb->fQuery($qry, "fetch query error"); 
	//$mcompanyStr = $rt[CNAME];
	
} else {
	echo"
	<script>
		alert('Payment Information Error.');
		parent.viewwinpopup.hide(); 
	</script>
	";
}


?>

<? include getenv("DOCUMENT_ROOT")."/admin/include/header.php"; ?>

<p><img src="/admin/images/icon-narr.png" align="absmiddle"><font class="stitle">PAYMENT INFORMATION</font></p>

	 
<table width="95%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-top-width:3px; border-bottom-width:3px; border-color:#dddddd; border-top-style:solid; border-bottom-style:solid;">
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="/admin/images/icon-round.png" hspace="10" align="absmiddle"><b>User ID</b></td>
    <td class="tl">
			<?=$USERID?>
    </td>
  </tr>
  <tr>
    <td height="1" colspan="2" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
  </tr>
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="/admin/images/icon-round.png" hspace="10" align="absmiddle"><b>Created Date</b></td>
    <td class="tl">
    	<?=$ORDCREATEDATEstr?>
    </td>
  </tr>  
  <tr>
    <td height="1" colspan="2" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
  </tr>
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="/admin/images/icon-round.png" hspace="10" align="absmiddle"><b>Paid Date</b></td>
    <td class="tl">
    	<?=$ORDPAYDATEstr?>
    </td>
  </tr>
  <tr>
    <td height="1" colspan="2" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
  </tr>
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="/admin/images/icon-round.png" hspace="10" align="absmiddle"><b>Status</b></td>
    <td class="tl">
    	<font color=red><?=$ORDSTATUSstr?></font>
    </td>
  </tr>   
  <tr>
    <td height="1" colspan="2" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
  </tr>
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="/admin/images/icon-round.png" hspace="10" align="absmiddle"><b>First Name</b></td>
    <td class="tl">
    	<?=$C_FIRSTNAMEstr?>
    </td>
  </tr>
  <tr>
    <td height="1" colspan="2" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
  </tr>
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="/admin/images/icon-round.png" hspace="10" align="absmiddle"><b>Last Name</b></td>
    <td class="tl">
    	<?=$C_LASTNAMEstr?>
    </td>
  </tr>  
  <tr>
    <td height="1" colspan="2" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
  </tr>
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="/admin/images/icon-round.png" hspace="10" align="absmiddle"><b>Service Name</b></td>
    <td class="tl">
    	<?=$ORDNMSstr?>
    </td>
  </tr>  
  <tr>
    <td height="1" colspan="2" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
  </tr>
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"><img src="/admin/images/icon-round.png" hspace="10" align="absmiddle"><b>Service Fee</b></td>
    <td class="tl">
    	$ <?=$ORDTOTAL?> (Total)
    </td>
  </tr>  

<? if ($DISCOUNTPRICE) { ?>
  <tr>
    <td width="25%" height="30" bgcolor="#eeeeee"></td>
    <td class="tl">
    	<font color=red>-$ <?=$DISCOUNTPRICE?></font> (Coupon Discount)
    </td>
  </tr>
<? } ?>
  
  <!--tr>
    <td width="25%" height="30" bgcolor="#eeeeee"></td>
    <td class="tl">
    	$ <?=$ORDHST?> (HST)
    </td>
  </tr-->  
      
</table><br />

<div align=center>
<input type=button value=CLOSE ONCLICK="parent.viewwinpopup.hide(); return false;">
</div>
