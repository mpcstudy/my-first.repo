<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/session_include.php";
?>

<?

include getenv("DOCUMENT_ROOT")."/include/session_include.php";
$func->checkAdmin("/admin/index.php");

if($CTGSEQ) {

	$columns = array();	
	$values = array();
	
	$columns[] = "CTGNAME";
	$columns[] = "CTGFIRSTCATEGORY";
	$columns[] = "CTGSECONDCATEGORY";
	$columns[] = "CTGTHIRDCATEGORY";
	$columns[] = "CTGDESCRIPTION";
	
	$columns[] = "CTGDISPLAY";
	$columns[] = "CTGPRIORITY";	

	//$columns[] = "CTGGRADE";
	//$columns[] = "CTGCLASS";

	$values[] = str_replace("\\", "", trim($CTGNAME));
	$values[] = $CTGFIRSTCATEGORY;
	$values[] = $CTGSECONDCATEGORY;
	$values[] = $CTGTHIRDCATEGORY;
	$values[] = str_replace("\\", "", trim($CTGDESCRIPTION));

	$values[] = $CTGDISPLAY;
	$values[] = $CTGPRIORITY;

	//$values[] = $CTGGRADE;
	//$values[] = $CTGCLASS;

	// Category Image Upload
	$saveDir = getenv("DOCUMENT_ROOT").'/images/category/';
	
	$query = "SELECT * FROM tbl_category WHERE CTGSEQ = '$CTGSEQ'";
	$result = $jdb->fQuery($query, "query error");
	//echo "[$query]";
	
	//for($j=0; $j<sizeof($result); $j++) {
	//	list($key, $value) = each($result);
	//	$$key = $value;
	//}
	//echo "[".$result[PRDBIGIMG]."]";
	
	$delPic = explode("|", $result['CTGIMAGE']);	

	unset($result['CTGIMAGE']);

	for ($i=0; $i<4; $i++) {
		if ($delFlag[$i] == "on") {
			$setPic[$i] = "";
			if (is_file($saveDir.$delPic[$i])) {
				unlink($saveDir.$delPic[$i]);
			}
		}

		else if(is_uploaded_file($_FILES['userFile']['tmp_name'][$i])){     		
        		
			$fileName    = $_FILES['userFile']['name'][$i]; 
			$fileType    = $_FILES['userFile']['type'][$i]; 
			$fileSize    = $_FILES['userFile']['size'][$i]; 
			
			$fileExt    = strtolower(substr($fileName , strrpos($fileName, ".") - strlen($fileName) + 1));      
			
			//if ($fileExt !=  ){ 
			//echo "
			//	<script>
			//	alert('Please Check the file type. You can upload gif/jpg.');
			//	history.back();
			//	</script>
			//	";
			//} 
			
			$savefileName = date("YmdHis") . sprintf("%06d", mt_rand(0, 999999)).".".$fileExt; 
			
			//echo "[FN=$fileName][NFN=$saveDir$savefileName][TP=$fileType][Sz=$fileSize]<br>";       

// For Sizing
			$getFileName[$i] = $func->thumnail($_FILES['userFile']['tmp_name'][$i], $savefileName, $saveDir, 500, 500);		
			//echo "[".$savefileName."]"."[".$getFileName[$i]."]<br>";	
			$tmp = $saveDir.$getFileName[$i];
			$rt = chmod ($tmp, 0755);
			$setPic[$i] = $getFileName[$i];
				
			if($userFile[$i]!="none" && $userFile[$i]){}
			
			if (is_file($saveDir.$delPic[$i])) {	
				unlink($saveDir.$delPic[$i]);
			}


/*
// Just change name
			//$getFileName[$i] = $func->thumnail($_FILES['userFile']['tmp_name'][$i], $savefileName, $saveDir, 500, 500);		
			//echo "[".$savefileName."]"."[".$getFileName[$i]."]<br>";	
			$tmp = $saveDir.$savefileName;
			
			move_uploaded_file ($_FILES['userFile']['tmp_name'][$i], $tmp);
			
			$rt = chmod ($tmp, 0755);
			$setPic[$i] = $savefileName;
				
			if($userFile[$i]!="none" && $userFile[$i]){}
			
			if (is_file($saveDir.$delPic[$i])) {	
				unlink($saveDir.$delPic[$i]);
			}
*/

		} 
		else $setPic[$i] = $delPic[$i];


		if ($setPic[$i] != "") 
			$result['CTGIMAGE'] .= $setPic[$i];
		//echo "[cnt=$i][file_name=$setPic[$i]]<br>";
	
	}
		$columns[] = "CTGIMAGE";
		$values[] = $result['CTGIMAGE'];

	// Sub Category Image Upload
	$saveDir = getenv("DOCUMENT_ROOT").'/images/sub/';
	
	$query = "SELECT * FROM tbl_category WHERE CTGSEQ = '$CTGSEQ'";
	$result = $jdb->fQuery($query, "query error");
	//echo "[$query]";
	
	//for($j=0; $j<sizeof($result); $j++) {
	//	list($key, $value) = each($result);
	//	$$key = $value;
	//}
	//echo "[".$result[PRDBIGIMG]."]";
	
	$delPic = explode("|", $result['CTGSUBIMAGE']);	

	unset($result['CTGSUBIMAGE']);

	for ($i=0; $i<4; $i++) {
		if ($rmFlag[$i] == "on") {
			$setPic[$i] = "";
			if (is_file($saveDir.$delPic[$i])) {
				unlink($saveDir.$delPic[$i]);
			}
		}

		else if(is_uploaded_file($_FILES['catFile']['tmp_name'][$i])){     		
        		
			$fileName    = $_FILES['catFile']['name'][$i]; 
			$fileType    = $_FILES['catFile']['type'][$i]; 
			$fileSize    = $_FILES['catFile']['size'][$i]; 
			
			$fileExt    = strtolower(substr($fileName , strrpos($fileName, ".") - strlen($fileName) + 1));      
			
			//if ($fileExt !=  ){ 
			//echo "
			//	<script>
			//	alert('Please Check the file type. You can upload gif/jpg.');
			//	history.back();
			//	</script>
			//	";
			//} 
			
			$savefileName = date("YmdHis") . sprintf("%06d", mt_rand(0, 999999)).".".$fileExt; 
			
			//echo "[FN=$fileName][NFN=$saveDir$savefileName][TP=$fileType][Sz=$fileSize]<br>";       


// For Sizing
			$getFileName[$i] = $func->thumnail($_FILES['catFile']['tmp_name'][$i], $savefileName, $saveDir, 1024, 768);		
			//echo "[".$savefileName."]"."[".$getFileName[$i]."]<br>";	
			$tmp = $saveDir.$getFileName[$i];
			$rt = chmod ($tmp, 0755);
			$setPic[$i] = $getFileName[$i];
				
			if($catFile[$i]!="none" && $catFile[$i]){}
			
			if (is_file($saveDir.$delPic[$i])) {	
				unlink($saveDir.$delPic[$i]);
			}


/*
// Just change name
			//$getFileName[$i] = $func->thumnail($_FILES['userFile']['tmp_name'][$i], $savefileName, $saveDir, 500, 500);		
			//echo "[".$savefileName."]"."[".$getFileName[$i]."]<br>";	
			$tmp = $saveDir.$savefileName;
			
			move_uploaded_file ($_FILES['userFile']['tmp_name'][$i], $tmp);
			
			$rt = chmod ($tmp, 0755);
			$setPic[$i] = $savefileName;
				
			if($userFile[$i]!="none" && $userFile[$i]){}
			
			if (is_file($saveDir.$delPic[$i])) {	
				unlink($saveDir.$delPic[$i]);
			}
*/

		} 
		else $setPic[$i] = $delPic[$i];


		if ($setPic[$i] != "") 
			$result['CTGSUBIMAGE'] .= $setPic[$i];
		//echo "[cnt=$i][file_name=$setPic[$i]]<br>";
	
	}
		$columns[] = "CTGSUBIMAGE";
		$values[] = $result['CTGSUBIMAGE'];

	$jdb->uQuery("tbl_category", $columns, $values, " WHERE CTGSEQ = '$CTGSEQ' ");
} else {
	$func->alertBack('category error');
}

$msg = "Updated successfully.";

echo "<script language=javascript>
	alert(\"".$msg."\");
	parent.location.reload();
	parent.revisewinpopup.hide(); 
	</script>";	

exit;

?>
