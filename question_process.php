<?php

include getenv("DOCUMENT_ROOT")."/include/session_include.php";

$func->checkAdmin("/admin/index.php");

$goStr = "switched=$switched&page=$page&key_word=$key_word&column=$column&sorting_type=$sorting_type&switch=$switch";


/*
for($i=0; $i<sizeof($_POST); $i++) {
	list($key, $value) = each($_POST);
	$$key = $value;

	if(is_array($value))
	{
		$count = 10;
		for($i = 0; $i < $count; $i ++) {
			if ($value[$i]) echo "ARRAY[$key][$value[$i]]<br>";
		}
	}
	else 	echo "[$key][$value]<br>";
	//print_r($_POST);
}

exit;
*/


// Delete member
if ($mode == "delete") {
	
	if ($modeStr == "SHIPPING") {
		$jdb->nQuery("DELETE FROM tbl_customer_shipping WHERE CUSUID = '$CUSUID'", "delete error");
		$jdb->CLOSE();
		$msg = "Deleted successfully.";	
		$func -> goUrl("/index.php?view=customer_info&mode=update&CUUID=$CUUID&$goStr", $msg);
		exit();
	}
	
}


////////////////////////
// actionStr = "BASICINFO"
////////////////////////
if ($actionStr == "QUESTIONINFO") {
			if($mode == "update" && trim($que_id) == "") {
				$msg = "Invalid Question ID. Please try again.";
				echo "<script language=javascript>
					alert(\"".$msg."\");
					window.history.go(-1);
					//parent.location.reload();
					//parent.memberwinpopup.hide(); 		
					</script>";	
			  
				exit();
			}

			$columns = array();	
			$values = array();

			// Customer Info
			if($mode == "create") {
				$columns[] = "que_createddate";					
			}
			if($mode == "update") {
				$columns[] = "que_modifieddate";					
			}			
			$columns[] = "que_status";
			$columns[] = "que_class";
			$columns[] = "que_grade";
			$columns[] = "que_level";
			$columns[] = "que_category1";
			$columns[] = "que_category2";
			$columns[] = "que_category3";
			$columns[] = "que_en_title";
			$columns[] = "que_en_desc";
			$columns[] = "que_en_hint";
			$columns[] = "que_en_solution";
			$columns[] = "que_answertype";
			$columns[] = "que_en_answers";
			$columns[] = "que_en_answerm";
			$columns[] = "que_en_example";
			$columns[] = "que_en_resource";


			if($mode == "create") {
				$values[] = date("YmdHis");
			}
			if($mode == "update") {
				$values[] = date("YmdHis");
			}
			
	/////////////// Mysql Default Value Double checking
	if($que_status==''){$que_status='1';}
	if($que_category1==''){$que_category1='0';}
	if($que_category2==''){$que_category2='0';}
	if($que_category3==''){$que_category3='0';}
	if($que_level==''){$que_level='1';}
	if($que_answertype==''){$que_answertype='0';}
			$values[] = $que_status;
			$values[] = $que_class;
			$values[] = $que_grade;
			$values[] = $que_level;
			$values[] = $que_category1;
			$values[] = $que_category2;
			$values[] = $que_category3;
			$values[] = str_replace("\\", "", $que_en_title);
			$values[] = str_replace("\\", "", $que_en_desc);
			$values[] = str_replace("\\", "", $que_en_hint);
			$values[] = str_replace("\\", "", $que_en_solution);
			$values[] = $que_answertype;	
			if ($que_answertype == 0) {
				$values[] = str_replace("\\", "", $que_en_answers);
				$values[] = "";	
				$values[] = "";	
			}		
			else {
				$values[] = "";
				$values[] = $que_en_answerm;
				$que_en_example = $que_en_example1."|".$que_en_example2."|".$que_en_example3."|".$que_en_example4."|".$que_en_example5;
				$values[] = str_replace("\\", "", $que_en_example);	
			}
			
			$values[] = str_replace("\\", "", $que_en_resource);
			if ($mode == "create") {
			
				//for ($i=0; $i < count($columns); $i++)
				//echo "[$columns[$i]][$values[$i]]<br>";
				//echo "[UID=$uid][ID=$userid][MAXUID=$maxuid]";
				//exit;

				$jdb->iQuery("tbl_question", $columns, $values);
				
				// get max uid
				$query = "select max(que_id) from tbl_question ";
				$maxuid = $jdb->rQuery($query, "max query error");
			
				$msg = "Created successfully.";

			  $func -> goUrl("/admin/index.php?view=question_info&mode=update&que_id=$maxuid&$goStr", $msg);
				exit();
			}
			
			// Information update
			else if ($mode == "update") {
				//for ($i=0; $i < count($columns); $i++)
				//echo "[$columns[$i]][$values[$i]]<br>";
				//echo "[UID=$uid][ID=$userid][MAXUID=$maxuid]";
				//echo "[$pln_recurringdate][$pln_status]";exit;
				//exit;	

				$jdb->uQuery("tbl_question", $columns, $values, " where que_id = '$que_id' ");
				
				$msg = "Updated successfully.";			
			
			  $func -> goUrl("/admin/index.php?view=question_info&mode=update&que_id=$que_id&$goStr", $msg);
				exit();
					
			}
}


else {
	
				$msg = "Invalid Parameter. Please try again.";
				echo "<script language=javascript>
					alert(\"".$msg."\");
					window.history.go(-1);
					//parent.location.reload();
					//parent.memberwinpopup.hide(); 		
					</script>";	
			  
				exit();
}

?>