<?php

include getenv("DOCUMENT_ROOT")."/include/session_include.php";

// Delete Coupon
if ($mode == "delete") {
	
	// 접근권한이 admin, manager인 경우에만 허용
	$func -> checkAdmin("/admin/index.php");

	$jdb->nQuery("delete from tbl_pcodes where uid = '$uid'", "delete error");
	$jdb->CLOSE();
	$msg = "Deleted successfully.";	

	$func -> goUrl("/admin/index.php?view=coupon_list", $msg);		

	exit();
	
}

// Coupon 이 존재하는지 확인
if($mode == "create") {
	$query = "SELECT COUNT(uid) FROM tbl_pcodes where pcode = '$pcode' ";
	$couponCnt = $jdb->rQuery($query, "record query error");
	if ($couponCnt !=0) {
		echo" 
		<SCRIPT LANGUAGE=\"JavaScript\"> 
		//alert('이미 등록된 쿠폰번호입니다.');
		alert('Already registered coupon code!');
		history.back(-1);
		</SCRIPT>"; 
		exit; 
	}	
}

$columns = array();	
$values = array();
		
// get parameter
if($mode == "create") {
	$columns[] = "uid";
}
$columns[] = "pcode";
$columns[] = "drate";
$columns[] = "expiredate_from";
$columns[] = "expiredate_to";
$columns[] = "status";
$columns[] = "usedcount";

// get value
if($mode == "create") {
	$values[] = $uid;
}
$values[] = $pcode;
$values[] = $drate;

if ($expiredate_from == "") $expiredate_fromSTR = "00000000";
else $expiredate_fromSTR = $expiredate_from;

if ($expiredate_to == "") $expiredate_toSTR = "99999999";
else $expiredate_toSTR = $expiredate_to;
		
$values[] = $expiredate_fromSTR;
$values[] = $expiredate_toSTR;

//$values[] = $from_year.$from_month.$from_day;
//$values[] = $to_year.$to_month.$to_day;
$values[] = $status;
$values[] = $usedcount;

// Coupon registration
if ($mode == "create") {

	// get max uid
	$query = "select max(uid) from tbl_pcodes ";
	$maxuid = $jdb->rQuery($query, "max query error");

	//for ($i=0; $i < count($columns); $i++)
	//echo "[$columns[$i]][$values[$i]]<br>";
	//echo "[UID=$uid][ID=$userid][MAXUID=$maxuid]";
	//exit;

	$jdb->iQuery("tbl_pcodes", $columns, $values);
		
	$msg = "Added successfully.";
	
	echo "<script language=javascript>
		alert(\"".$msg."\");
		parent.location.reload();
		parent.createwinpopup.hide(); 
		</script>";	
	exit();
}

// Information update
else if ($mode == "modify") {
	//for ($i=0; $i < count($columns); $i++)
	//echo "[$columns[$i]][$values[$i]]<br>";
	//echo "[UID=$uid][ID=$userid][MAXUID=$maxuid]";
	//exit;	
		
	$jdb->uQuery("tbl_pcodes", $columns, $values, " where uid = '$uid' ");
	$msg = "Updated successfully.";

	echo "<script language=javascript>
		alert(\"".$msg."\");
		parent.location.reload();
		parent.revisewinpopup.hide(); 
		</script>";	

	exit;
}

?>