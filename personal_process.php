<?php

include getenv("DOCUMENT_ROOT")."/include/session_include.php";

$func->checkAdmin("index.php");

// get parameter
if(($mode == "update") && ($actionPage == "adminInfo")) {

		// id == null
		if($adm_id == "") {
			$msg = "Invaild data. Please try again.";
			$func -> alertBack($msg);
			exit();
		}

		$hash = password_hash($adm_password, PASSWORD_DEFAULT);


		$columns = array();	
		$values = array();

		$columns[] = "adm_fname";
		$columns[] = "adm_lname";
		$columns[] = "adm_cell";
		$columns[] = "adm_phone";
		$columns[] = "adm_password";

		$values[] = str_replace("\\", "", $adm_fname);
		$values[] = str_replace("\\", "", $adm_lname);
		$values[] = $adm_cell;
		$values[] = $adm_phone;
		$values[] = $hash;

		//for ($i=0; $i < count($columns); $i++)
		//echo "[$columns[$i]][$values[$i]]<br>";
		//echo "[UID=$uid][ID=$userid][MAXUID=$maxuid]";
		//exit;	

		$jdb->uQuery("tbl_admusers", $columns, $values, " where adm_id = '$adm_id' ");
		$msg = "Updated successfully.";
		//$msg = "정상적으로 수정되었습니다.";


		echo "<script language=javascript>
			alert(\"".$msg."\");
			parent.location.reload();
			parent.adminwinpopup.hide(); 
			</script>";	

		exit();
		
}

?>