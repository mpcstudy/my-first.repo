<?

include getenv("DOCUMENT_ROOT")."/include/session_include.php";

$func->checkAdmin("/admin/index.php");

if($CTGTHIRDCATEGORY) {
	$CTGDEPTH = "3";
}else if($CTGSECONDCATEGORY) {
	$CTGDEPTH = "2";
}else if($CTGFIRSTCATEGORY) {
	$CTGDEPTH = "1";
}

// 1st category 에서만 Image Upload
if ($_FILES['userFile']['tmp_name'] != "") {
	// Image Upload	
	$saveDir = getenv("DOCUMENT_ROOT").'/images/category/';
	
	for($i=0; $i<sizeof($_FILES['userFile']['tmp_name']); $i++){
		if(is_uploaded_file($_FILES['userFile']['tmp_name'][$i])){ 
			$fileName    = $_FILES['userFile']['name'][$i]; 
			$fileType    = $_FILES['userFile']['type'][$i]; 
			$fileSize    = $_FILES['userFile']['size'][$i]; 
			
			$fileExt    = strtolower(substr($fileName , strrpos($fileName, ".") - strlen($fileName) + 1));      
	
			$savefileName = date("YmdHis") . sprintf("%06d", mt_rand(0, 999999)).".".$fileExt; 
	
	/*
			$MicroTsmp = split(" ",microtime());
			$newFileName = str_replace(".", "", $MicroTsmp[0]);
			if($userFile[$i]!="none" && $userFile[$i]){
				$extention = strrchr($file_name[$i], ".");
				$file_name[$i]=time().$extention;
				if (file_exists("$saveDir.$file_name[$i]")) {
					$file_name[$i] = time()."_".$newFileName.$extention;
				}
	
	//			$get_size = $func->_getimagesize($userFile[$i], "500", "4:4"); 
	//			$result = $func->resize_image($saveDir.$file_name[$i], $userFile[$i], $get_size, "80", "4:4"); 
	
				//$func->resizeimage("500","500",$saveDir.$file_name[$i],$userFile[$i]);
	//			$tmp = $saveDir.$file_name[$i];
				$getFileName[$i] = $func->thumnail($userFile[$i], $file_name[$i], $saveDir, 500, 500);
	*/
			// Image resizing 시 사용
			$getFileName[$i] = $func->thumnail($_FILES['userFile']['tmp_name'][$i], $savefileName, $saveDir, 500, 500);		
			//echo "[FN=$fileName][NFN=$saveDir$savefileName][TP=$fileType][Sz=$fileSize]<br>";       
			$tmp = $saveDir.$getFileName[$i];
			
			/*
			$tmp = $saveDir.$savefileName;	
			move_uploaded_file ($_FILES['userFile']['tmp_name'][$i], $tmp);
			*/
			
			//echo "[$tmp]";
			$rt = chmod ($tmp, 0755);
		}
		//$CTGIMAGE .= $getFileName[$i]."|";
		$CTGIMAGE = $savefileName;
	}
}


// 1st category 에서만 Sub Image Upload
if ($_FILES['catFile']['tmp_name'] != "") {
	// Image Upload	
	$saveDir = getenv("DOCUMENT_ROOT").'/images/sub/';
	
	for($i=0; $i<sizeof($_FILES['catFile']['tmp_name']); $i++){
		if(is_uploaded_file($_FILES['catFile']['tmp_name'][$i])){ 
			$fileName    = $_FILES['catFile']['name'][$i]; 
			$fileType    = $_FILES['catFile']['type'][$i]; 
			$fileSize    = $_FILES['catFile']['size'][$i]; 
			
			$fileExt    = strtolower(substr($fileName , strrpos($fileName, ".") - strlen($fileName) + 1));      
	
			$savefileName = date("YmdHis") . sprintf("%06d", mt_rand(0, 999999)).".".$fileExt; 
	
	/*
			$MicroTsmp = split(" ",microtime());
			$newFileName = str_replace(".", "", $MicroTsmp[0]);
			if($catFile[$i]!="none" && $catFile[$i]){
				$extention = strrchr($file_name[$i], ".");
				$file_name[$i]=time().$extention;
				if (file_exists("$saveDir.$file_name[$i]")) {
					$file_name[$i] = time()."_".$newFileName.$extention;
				}
	
	//			$get_size = $func->_getimagesize($catFile[$i], "500", "4:4"); 
	//			$result = $func->resize_image($saveDir.$file_name[$i], $catFile[$i], $get_size, "80", "4:4"); 
	
				//$func->resizeimage("500","500",$saveDir.$file_name[$i],$catFile[$i]);
	//			$tmp = $saveDir.$file_name[$i];
				$getFileName[$i] = $func->thumnail($catFile[$i], $file_name[$i], $saveDir, 500, 500);
	*/
			// Image resizing 시 사용
			$getFileName[$i] = $func->thumnail($_FILES['catFile']['tmp_name'][$i], $savefileName, $saveDir, 1024, 768);		
			//echo "[FN=$fileName][NFN=$saveDir$savefileName][TP=$fileType][Sz=$fileSize]<br>";       
			$tmp = $saveDir.$getFileName[$i];
			
			/*
			$tmp = $saveDir.$savefileName;	
			move_uploaded_file ($_FILES['catFile']['tmp_name'][$i], $tmp);
			*/
			
			//echo "[$tmp]";
			$rt = chmod ($tmp, 0755);
		}
		//$CTGIMAGE .= $getFileName[$i]."|";
		$CTGSUBIMAGE = $savefileName;
	}
}

$columns = array();	
$values = array();
		
//$columns[] = "CTGSEQ";
$columns[] = "CTGNAME";
$columns[] = "CTGFIRSTCATEGORY";
$columns[] = "CTGSECONDCATEGORY";
$columns[] = "CTGTHIRDCATEGORY";
$columns[] = "CTGDEPTH";
$columns[] = "CTGDESCRIPTION";
$columns[] = "CTGIMAGE";
$columns[] = "CTGSUBIMAGE";
$columns[] = "CTGDISPLAY";
$columns[] = "CTGPRIORITY";

$columns[] = "CTGGRADE";
$columns[] = "CTGCLASS";

//$values[] = "";
$values[] = str_replace("\\", "", trim($CTGNAME));
$values[] = $CTGFIRSTCATEGORY;
$values[] = $CTGSECONDCATEGORY;
$values[] = $CTGTHIRDCATEGORY;
$values[] = $CTGDEPTH;
$values[] = str_replace("\\", "", trim($CTGDESCRIPTION));
$values[] = $CTGIMAGE;
$values[] = $CTGSUBIMAGE;
$values[] = $CTGDISPLAY;
$values[] = $CTGPRIORITY;

$values[] = $CTGGRADE;
$values[] = $CTGCLASS;

$jdb->iQuery("tbl_category", $columns, $values);
$msg = "Created successfully.";

echo "<script language=javascript>
	alert(\"".$msg."\");
	parent.location.reload();
	parent.createwinpopup.hide(); 
	</script>";	

exit;

?>
