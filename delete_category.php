<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/session_include.php";
?>

<?

include getenv("DOCUMENT_ROOT")."/include/session_include.php";

$func->checkAdmin("/admin/index.php");

$query = " SELECT * FROM tbl_category WHERE CTGSEQ = '$CTGSEQ' ";
$result = $jdb->fQuery($query, "query error");

if($result['CTGDEPTH'] == "1") {
	$jdb->nQuery(" DELETE FROM tbl_category WHERE CTGFIRSTCATEGORY = '$result[CTGFIRSTCATEGORY]' ", "query error");
	if ($result['CTGIMAGE'] != "") {
		$saveDir = getenv("DOCUMENT_ROOT").'/images/category/';	
		if (is_file($saveDir.$result['CTGIMAGE'])) {
			unlink($saveDir.$result['CTGIMAGE']);
		}		
	}
}else if($result['CTGDEPTH'] == "2") {
	$jdb->nQuery(" DELETE FROM tbl_category WHERE CTGFIRSTCATEGORY = '$result[CTGFIRSTCATEGORY]' AND CTGSECONDCATEGORY = '$result[CTGSECONDCATEGORY]' ", "query error");
	if ($result['CTGIMAGE'] != "") {
		$saveDir = getenv("DOCUMENT_ROOT").'/images/category/';	
		if (is_file($saveDir.$result['CTGIMAGE'])) {
			unlink($saveDir.$result['CTGIMAGE']);
		}		
	}
}else if($result['CTGDEPTH'] == "3") {
	$jdb->nQuery(" DELETE FROM tbl_category WHERE CTGSEQ = '$CTGSEQ' ", "query error");
}

$msg = "Deleted successfully.";	
$func -> goUrl("/admin/index.php?view=category_list&mode=search&ctg_grade=$ctg_grade&ctg_class=$ctg_class", $msg);
?>

<!--
<script>
location.href='/admin/index.php?view=category_list';
</script>
-->