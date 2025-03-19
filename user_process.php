<?php

include getenv("DOCUMENT_ROOT")."/include/session_include.php";

$func->checkAdmin("index.php");

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

// Delete Coupon
if ($mode == "delete" && $actionStr == "USER") {
	
	// 접근권한이 admin, manager인 경우에만 허용
	$func -> checkAdmin("/admin/index.php");

	$jdb->nQuery("delete from tbl_user where user_id = '$user_id'", "delete error");
	$jdb->CLOSE();
	$msg = "Deleted successfully.";	

	$func -> goUrl("/admin/index.php?view=user_list", $msg);		

	exit();
	
}

if($mode == "create" && $actionStr == "USERINFO") {

			// Google Captcha

		  function post_captcha($user_response) {
		      $fields_string = '';
		      $fields = array(
		          'secret' => '6Ld7ocsiAAAAACnUs_wJt9QwtVL8s268brSElTRs',
		          'response' => $user_response
		      );
		      foreach($fields as $key=>$value)
		      $fields_string .= $key . '=' . $value . '&';
		      $fields_string = rtrim($fields_string, '&');

		      $ch = curl_init();
		      curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
		      curl_setopt($ch, CURLOPT_POST, count($fields));
		      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		      curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

		      $result = curl_exec($ch);
		      curl_close($ch);
		      
		      //echo "[$result]";exit;

		      return json_decode($result, true);
		  }

		  // Call the function post_captcha
		  $res = post_captcha($_POST['g-recaptcha-response']);

		  if (!$res['success']) {
		      // What happens when the CAPTCHA wasn't checked
		     	$msg = "Please go back and make sure you check the security CAPTCHA box.";
		     	$func -> modalMsg ($msg, "");
					//echo "<SCRIPT LANGUAGE=\"JavaScript\">alert('Please go back and make sure you check the security CAPTCHA box.');history.back(-1);</SCRIPT>";
					exit;

		  }
		  // End of Captcha
		  
		  	
			if ($emailValidate != 1) {
					$func -> modalMsg ("Please Check Email Address Again", "");
					exit;
			}
}

if ($actionStr == "USERINFO") {

			$columns = array();	
			$values = array();

			if($mode == "update") {
						if($user_id == "") {
							$msg = "Invaild data. Please try again.[user_id null]";
							//$func -> alertBack($msg);
							$func -> modalMsg ($msg, "");	
							exit();
						}
			}

			if($mode == "create") {
						$columns[] = "user_userid";
						$columns[] = "user_createddate";
			}
			
			if ($user_password != "" && $user_password_chk != "") {
						$columns[] = "user_password";
			}
			$columns[] = "user_fname";
			$columns[] = "user_lname";
			$columns[] = "user_phone";
			$columns[] = "user_cell";
			$columns[] = "user_school";
			$columns[] = "user_grade";
			$columns[] = "user_class";
			$columns[] = "user_newsletter";
			$columns[] = "user_comment";
			
			if ($actionPage == "ADMINUSERINFO") {
						$columns[] = "user_status";
						$columns[] = "user_paidstatus";
						$columns[] = "user_servicefrom";
						$columns[] = "user_serviceto";
			}

			// data
			if($mode == "create") {
						$values[] = trim($user_userid);
						$values[] = date("YmdHis");
			}
			
			
			// password 입력없으면 password update 안함
			if ($user_password != "" && $user_password_chk != "") {
						if ($_SERVER["REMOTE_ADDR"] != "127.0.0.1") $user_passwordSTR = password_hash($user_password, PASSWORD_DEFAULT);
						else $user_passwordSTR = $user_password;
						
						$values[] = $user_passwordSTR;
			}
			
			$values[] = str_replace("\\", "", trim($user_fname));
			$values[] = str_replace("\\", "", trim($user_lname));
			$values[] = trim($user_phone);
			$values[] = trim($user_cell);
			$values[] = str_replace("\\", "", trim($user_school));
			$values[] = $user_grade;

			for($i = 0; $i < count($user_class); $i ++) {
					if (trim($user_class[$i]) != "") $user_classSTR .= $user_class[$i]."|";
			}
			
			$values[] = $user_classSTR;
			if ($user_newsletter =="") $user_newsletter = 0;
			$values[] = $user_newsletter;
			$values[] = str_replace("\\", "", trim($user_comment));

			if ($actionPage == "ADMINUSERINFO") {
						$values[] = $user_status;
						$values[] = $user_paidstatus;
						$values[] = str_replace("-", "", trim($user_servicefrom));
						$values[] = str_replace("-", "", trim($user_serviceto));
			}


			//for ($i=0; $i < count($columns); $i++)
			//echo "[$columns[$i]][$values[$i]]<br>";
			//echo "[UID=$uid][ID=$userid][MAXUID=$maxuid]";
			//exit;	
			
			if($mode == "create") {
						$jdb->iQuery("tbl_user", $columns, $values);
						$msg = "Created successfully.";

						$query = "select max(user_id) from tbl_user ";
						$user_id = $jdb->rQuery($query, "max query error");
			}
			
			else if($mode == "update") {
						$jdb->uQuery("tbl_user", $columns, $values, " where user_id = '$user_id' ");
						$msg = "Updated successfully.";
			}

			if ($actionPage == "ADMINUSERINFO") {
						$func -> goUrl("/admin/index.php?view=user_info&mode=update&user_id=$user_id&$goStr", $msg);
			}
			else {
						if ($mode == "create") {
								$_SESSION['ss_LOGIN']			= 1;
								$_SESSION['ss_UID']				= $user_id;	// 1000000001,1000000002...
								$_SESSION['ss_ID']				= $user_userid;	// dustin@ebizple.com
								$_SESSION['ss_NAME']			= $user_fname." ".$user_lname;	// Dustin Choi
									
								$today = $func -> PgetTime(0,0,4);
										
								$query = " update tbl_user set user_lastlogin = '$today', user_logincnt = '1'  where user_id = '$user_id' ";
								//mysql_query($query);
								$jdb->nQuery($query, "update error");

								//echo "[".$_SESSION[ss_LOGIN]."][".$_SESSION[ss_UID]."][".$_SESSION[ss_ID]."]";exit;
								$msg = "<br>Thank you for the registration. <br>You can easily manage your informations in my pages. <br>";
								
								if ($GREETINGMAIL == 1) @include getenv("DOCUMENT_ROOT")."/lib/mail_memberreg.php";
								
								if($destination) {
									//echo"<meta http-equiv='refresh' content='0; url=/index.php?view=$destination'>";
									$func -> modalMsg ($msg, "/index.php?view=$destination");	
									exit;
								}

								//echo"<meta http-equiv='refresh' content='0; url=/'>";
								$func -> modalMsg ($msg, "/");
								exit;
						}
						else if ($mode == "update") {
								
								$msg = "Updated successfully.";
								
								if($destination != "") {
									//echo"<meta http-equiv='refresh' content='0; url=/index.php?view=$destination'>";
									$func -> modalMsg ($msg, "/index.php?view=$destination");	
									exit;
								}

								//echo"<meta http-equiv='refresh' content='0; url=/'>";
								$func -> modalMsg ($msg, "/");
								exit;
						}
			}
			  
			/*
			echo "<script language=javascript>
				alert(\"".$msg."\");
				parent.location.reload();
				parent.adminwinpopup.hide(); 
				</script>";	
			*/
			
			exit();

} else {
			$msg = "Invaild data. Please try again.[actionStr null]";
			//$func -> alertBack($msg);
			$func -> modalMsg ($msg, "");	
			exit();
}
?>