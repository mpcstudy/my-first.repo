<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/session_include.php";
?>

<?

include getenv("DOCUMENT_ROOT")."/include/session_include.php";

$func->checkAdmin("index.php");

if ($mode == "create" || $mode == "") {
	$btnName = "CREATE";
	$mode = "create";
}
else if ($mode == "update") {
	$btnName = "UPDATE";
	$admTag = "DISABLED";
}

// User data query
if ($mode == "update") {
	
	if ($user_id == "") $user_id = $_SESSION['ss_UID'];

	$query = "SELECT * FROM tbl_user WHERE user_id = '$user_id'";
	$result = $jdb->fQuery($query, "query error");
	
	//echo "[$query]";
	for($i=0; $i<sizeof($result); $i++) {
		foreach ($result as $key => $value) {
			break;  // 첫 번째 요소만 가져오기 위해 `break` 사용
		}
		$$key = $value;
	}
	
	$user_fnameSTR = str_replace("\\", "", $user_fname);
	$user_lnameSTR = str_replace("\\", "", $user_lname);
	$user_schoolSTR = str_replace("\\", "", $user_school);
	$user_commentSTR = str_replace("\\", "", $user_comment);
	

	$user_lastloginSTR = $func -> convertFormat ($user_lastlogin, 1);
	$user_createddateSTR = $func -> convertFormat ($user_createddate, 1);

	$user_servicefromSTR = $func -> convertFormat ($user_servicefrom, 3);
	$user_servicetoSTR = $func -> convertFormat ($user_serviceto, 3);

	$user_classSTR = explode("|", $user_class);
	
	for($i = 0; $i < count($user_classSTR); $i ++) {
			if ($user_classSTR[$i] == "E") $caseFlagE = "E";
			if ($user_classSTR[$i] == "B") $caseFlagB = "B";			
			if ($user_classSTR[$i] == "M") $caseFlagM = "M";
			if ($user_classSTR[$i] == "C") $caseFlagC = "C";
			if ($user_classSTR[$i] == "P") $caseFlagP = "P";		
	}
	
	// Get Company Info
	//$qry = "SELECT * FROM tbl_company WHERE CUID = '$MCOMPANY'";
	//$rt = $jdb->fQuery($qry, "fetch query error"); 
	//$mcompanyStr = $rt[CNAME];
	
}

foreach ($arrGrade AS $key=>$value)
{
	//echo "[$key][$value]";   => [0][Waiting][1][Confirmed][E][Declined]...
	if ($key == $user_grade) $selectStr = "selected";
	else $selectStr = "";
	
	$que_gradeSTR .= "
								<option value='$key' $selectStr>$value</option>";
}

foreach ($arrStatus AS $key=>$value)
{
	//echo "[$key][$value]";   => [0][Waiting][1][Confirmed][E][Declined]...
	if ($key == $user_status) $selectStr = "selected";
	else $selectStr = "";
	
	$user_statusSTR .= "
								<option value='$key' $selectStr>$value</option>";
}


foreach ($arrPaidStatus AS $key=>$value)
{
	//echo "[$key][$value]";   => [0][Waiting][1][Confirmed][E][Declined]...
	if ($key == $user_paidstatus) $selectStr = "selected";
	else $selectStr = "";
	
	$user_paidstatusSTR .= "
								<option value='$key' $selectStr>$value</option>";
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

.history {
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  border-radius: 0px;
  font-family: open-sans, sans-serif;
  color: #ffffff;
  font-size: 14px;
  background: #598C17;
  padding: 8px 18px 8px 18px;
  text-decoration: none;
}

.history:hover {
  background: #75B51E;
  color: #ffffff;
  text-decoration: none;
}
</style>


<SCRIPT>
	
function checkEmail() {
	var str = document.getElementById('email').value;
	//alert(str);

	str = str.toLowerCase();

  if (str=="") {
    //document.getElementById("PPMUNITPRICEid").value="";
    //return;
  } 

  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
    	var rt = xmlhttp.responseText;
    	//alert(rt);
    	   	
 		if (rt.trim() >= 1) {

    		//alert("Existing no. Please change the no.");
    		//document.getElementById("emailcheckid").innerHTML= "<font color='#FF0000'>(Existing Email. Please input other Email.)</font>";
    		//document.getElementById('PPPPONOid').value="";
    		document.getElementById('emailValidateid').value = 0;
				alert('This email is already in use. Please enter a different email.');
				document.getElementById('email').value = "";
				document.getElementById('email').focus();
    		return false;
    	}
    	else {
    		//alert("Available no.");
    		//document.getElementById("emailcheckid").innerHTML= "<font color='#0000FF'>(Available no.)</font>";
    		document.getElementById('emailValidateid').value = 1;
    		document.getElementById('email').value = str;
    		return true;
    	}
    	
      //document.getElementById('ORGCPTYPEid').value=sellunit.trim();
    }
  }
  xmlhttp.open("GET","/lib/checkemaillib.php?s="+str,true);
  xmlhttp.send();
}

	
function idCheck(tid) {

	var reg = /^((\w|[\-\.])+)@((\w|[\-\.])+)\.([A-Za-z]+)$/;
	
	if(tid=="" || tid==null) {

		//alert("이메일 주소를 입력하세요.");
		alert("Please input your email.");
		document.userinfo.user_userid.focus();
		return false;

	}else {

		if (tid.search(reg) == -1) {
			//alert("이메일 주소가 올바르지 않습니다. 다시 입력해 주세요.");
			alert("Please input correct email.");
			document.userinfo.user_userid.focus();
			return false;
		}


		document.id_form.checkID.value=tid;
		window.open('', 'SAME', 'width=350,height=150,resizable=0,scrollbars=0');
		document.id_form.submit();

	}
}


function pagevalidate(f) {  
	//var fo = document.regform;
	var reg = /^((\w|[\-\.])+)@((\w|[\-\.])+)\.([A-Za-z]+)$/;
	var reg_number = /^[0-9]*$/

	if (f.user_userid.value == '' || f.user_userid.value == null)
	{
		alert("Please input your email.");
		f.user_userid.focus();
		return false;
	} else {

		if (f.user_userid.value.search(reg) == -1) {
			//alert("이메일 주소가 올바르지 않습니다. 다시 입력해 주세요.");
			alert("Incorrect email type. Please try again.");
			f.user_userid.focus();
			return false;
		}
	}

<? if ($mode == "create") { ?>
	if (f.user_password.value == '' || f.user_password.value == null)
	{
		alert("Please input password.");
		f.user_password.focus();
		return false;
	}

	if (f.user_password_chk.value == '' || f.user_password_chk.value == null)
	{
		alert("Please input confirm password.");
		f.user_password_chk.focus();
		return false;
	}
<? } ?>
	
	if (f.user_password.value != f.user_password_chk.value)
	{
		alert("Please check input password.");
		f.user_password.focus();
		return false;
	}	

	if (f.user_fname.value == '' || f.user_fname.value == null)
	{
		alert("Please input first name.");
		f.user_fname.focus();
		return false;
	}
	
	if (f.user_lname.value == '' || f.user_lname.value == null)
	{
		alert("Please input last name.");
		f.user_lname.focus();
		return false;
	}

	if (f.user_phone.value == '' || f.user_phone.value == null)
	{
		alert("Please input phone number.");
		f.user_phone.focus();
		return false;
	}
			
	//alert(index);
	return true;
}

</SCRIPT>


<form name=id_form method=post action=/admin/doc/member/id_email.php target=SAME>
<input type=hidden name=mode value="process">
<input type=hidden name=checkID value="">
</form>


<p>
	<img src="images/icon-list.png" align="absmiddle">
	<font class="title">CUSTOMER</font>
</p>



<div style="line-height:10px;">&nbsp;</div> 


<!-- Start of Question Information -->

<p><img src="images/icon-narr.png" align="absmiddle"><font class="stitle">CUSTOMER INFORMATION</font></p>


<FORM NAME=userinfo METHOD=POST ACTION=/admin/doc/user_process.php ONSUBMIT='return pagevalidate(this); return false;'>

<input type=hidden name=mode value="<?=$mode?>">
<input type=hidden name=actionStr value="USERINFO">
<input type=hidden name=actionPage value="ADMINUSERINFO">
<input type=hidden name=user_id value="<?=$user_id?>">
<input type=hidden name=directurl value="index.php">
<input type=hidden name=emailValidate id='emailValidateid' value="">

<input type=hidden name=switched value="<?=$switched?>">
<input type=hidden name=page value="<?=$page?>">
<input type=hidden name=key_word value="<?=$key_word?>">
<input type=hidden name=column value="<?=$column?>">
<input type=hidden name=sorting_type value="<?=$sorting_type?>">
<input type=hidden name=switch value="<?=$switch?>">

<table width="95%" align="center"  border="0" cellpadding="0" cellspacing="1" style="border-top-width:3px; border-left-width:3px; border-right-width:3px; border-bottom-width:3px; border-color:#dddddd; border-top-style:solid; border-left-style:solid; border-right-style:solid; border-bottom-style:solid;">
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">User ID</td>
	    <td width=75% colspan=3 class="tl">
				<INPUT TYPE='text' NAME='user_userid' id="email" required='required' option='email' onBlur="checkEmail();" VALUE='<?=$user_userid?>' <?=$admTag?> maxlength=60 style="width:200px; height:24px;" class="mbox"> 
				<!--input type=BUTTON value='Check Availability' <?=$admTag?> onClick='idCheck(document.userinfo.user_userid.value);' class=button ><br-->        
	    </td>    
	  </tr>
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr> 
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Password</td>
	    <td width=35% class="tl">
	    	<INPUT TYPE='password' NAME='user_password' <?=$setTag?> VALUE='' maxlength=20 style="width:200px; height:24px;" class="mbox"><br>
				(Use between 4 and 15 letters and/or numbers) 	    
	    </td>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Re-Password</td>
	    <td width=35% class="tl">
    		<INPUT TYPE='password' NAME='user_password_chk' <?=$setTag?> VALUE='' maxlength=20 style="width:200px; height:24px;" class="mbox">
	    </td>		    
	  </tr>
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr> 	  
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">First Name</td>
	    <td width=35%  class="tl">
				<INPUT TYPE='text' NAME='user_fname' required='required' <?=$setTag?> VALUE='<?=htmlspecialchars($user_fnameSTR, ENT_QUOTES)?>' maxlength=50 style="width:200px; height:24px;" class="mbox"> 	    
	    </td>	  	  		  	
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Last Name</td>
	    <td width=35%  class="tl">
				<INPUT TYPE='text' NAME='user_lname' required='required' <?=$setTag?> VALUE='<?=htmlspecialchars($user_lnameSTR, ENT_QUOTES)?>' maxlength=50 style="width:200px; height:24px;" class="mbox"> 	    
	    </td>	
	  </tr>
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr> 
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Phone</td>
	    <td width=35%  class="tl">
				<INPUT TYPE='text' NAME='user_phone' <?=$setTag?> VALUE='<?=$user_phone?>' maxlength=20 style="width:200px; height:24px;" class="mbox">
	    </td>	  	  		  	
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">News Letter</td>
	    <td width=35%  class="tl">
				<input type="checkbox" id="" name="user_newsletter" value="1" <? if ($user_newsletter==1 || $user_newsletter =="") echo "checked" ?>/>&nbsp;Yes
	    </td>	
	  </tr>		  
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>
</table>

<div style="line-height:10px;">&nbsp;</div> 
<p><img src="images/icon-narr.png" align="absmiddle"><font class="stitle">CLASS INFORMATION</font></p>

<table width="95%" align="center"  border="0" cellpadding="0" cellspacing="1" style="border-top-width:3px; border-left-width:3px; border-right-width:3px; border-bottom-width:3px; border-color:#dddddd; border-top-style:solid; border-left-style:solid; border-right-style:solid; border-bottom-style:solid;">
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">School</td>
	    <td width=35%  class="tl">
				<INPUT TYPE='text' NAME='user_school' <?=$setTag?> VALUE='<?=htmlspecialchars($user_schoolSTR, ENT_QUOTES)?>' maxlength=100 style="width:200px; height:24px;" class="mbox">
	    </td>	  	  		  	
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Grade</td>
	    <td width=35%  class="tl">
				<SELECT NAME='user_grade' id="user_gradeid" style="width:100px; height:30px;" tabindex="1" class="mbox"/>
		    	<option value='' >Select</option>
					<?=$que_gradeSTR?>
				</SELECT>	
	    </td>	
	  </tr>		  
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Interested Class</td>
	    <td colspan=3 class="tl">
              	<input type="checkbox" id="" name="user_class[]" value="M" <?=($caseFlagM == "M")?"checked":"";?> />&nbsp;Math
              	<input type="checkbox" id="" name="user_class[]" value="P" <?=($caseFlagP == "P")?"checked":"";?> />&nbsp;Physics
              	<input type="checkbox" id="" name="user_class[]" value="C" <?=($caseFlagC == "C")?"checked":"";?> />&nbsp;Chemistry              	
              	<input type="checkbox" id="" name="user_class[]" value="B" <?=($caseFlagB == "B")?"checked":"";?>/>&nbsp;Biology

	    </td>
	  </tr>		  
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>
	  <tr>
	    <td width=15% height="30" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Comment</td>
	    <td colspan=3 class="tl">
	    	<textarea rows="3" name="user_comment" id="user_commentid" class="mbox" style="width:90%;"><?=$user_commentSTR?></textarea>
	    </td>	       	
	  </tr>	   
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>
</table>


<script>
 $(function() {

			var date = new Date();
			date.setDate(date.getDate());

	    $("#user_servicefromid").datepicker({
	        	autoclose: true,
	        	startDate: date,
	        	dateFormat: 'yy-mm-dd',
	        	daysOfWeekDisabled: [0],
	    }).on('changeDate', function (selected) {
		        var minDate = new Date(selected.date.valueOf());
		        var minDate1 = minDate.setDate(minDate.getDate() + 1);
	        	$('#user_servicetoid').datepicker('setStartDate', minDate);
	    });

	    $("#user_servicetoid").datepicker({
	        	autoclose: true,
	        	dateFormat: 'yy-mm-dd',
						daysOfWeekDisabled: [0],
	    }).on('changeDate', function (selected) {
						var minDate = new Date(selected.date.valueOf());
						$('#user_servicefromid').datepicker('setEndDate', minDate);
			});	
	
 });
</script>

<div style="line-height:10px;">&nbsp;</div> 
<p><img src="images/icon-narr.png" align="absmiddle"><font class="stitle">SERVICE INFORMATION</font></p>

<table width="95%" align="center"  border="0" cellpadding="0" cellspacing="1" style="border-top-width:3px; border-left-width:3px; border-right-width:3px; border-bottom-width:3px; border-color:#dddddd; border-top-style:solid; border-left-style:solid; border-right-style:solid; border-bottom-style:solid;">
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Paid Status</td>
	    <td width=35%  class="tl">
				<SELECT NAME='user_paidstatus' id="user_paidstatusid" style="width:100px; height:30px;" tabindex="1" class="mbox"/>
		    	<option value='' >Select</option>
					<?=$user_paidstatusSTR?>
				</SELECT>	
	    </td>	  	  		  	
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Service Period</td>
	    <td width=35%  class="tl">
	    	From:<INPUT TYPE='text' NAME='user_servicefrom' id="user_servicefromid" <?=$setTag?> VALUE='<?=$user_servicefromSTR?>' maxlength=14 style="width:100px; height:24px;" class="mbox">
	    	 ~ 
	    	To:<INPUT TYPE='text' NAME='user_serviceto' id="user_servicetoid" <?=$setTag?> VALUE='<?=$user_servicetoSTR?>' maxlength=14 style="width:100px; height:24px;" class="mbox">
	    </td>	
	  </tr>		  
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>  
</table>

<div style="line-height:10px;">&nbsp;</div> 
<p><img src="images/icon-narr.png" align="absmiddle"><font class="stitle">OTHER INFORMATION</font></p>

<table width="95%" align="center"  border="0" cellpadding="0" cellspacing="1" style="border-top-width:3px; border-left-width:3px; border-right-width:3px; border-bottom-width:3px; border-color:#dddddd; border-top-style:solid; border-left-style:solid; border-right-style:solid; border-bottom-style:solid;">
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Status</td>
	    <td width=35%  class="tl">
				<SELECT NAME='user_status' id="user_statusid" style="width:100px; height:30px;" tabindex="1" class="mbox"/>
		    	<option value='' >Select</option>
					<?=$user_statusSTR?>
				</SELECT>	
	    </td>	  	  		  	
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Signup Date</td>
	    <td width=35%  class="tl"><?=$user_createddateSTR?></td>	
	  </tr>		  
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>

	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Last Login Date</td>
	    <td width=35%  class="tl"><?=$user_lastloginSTR?></td>		  	  		  	
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Login Number</td>
	    <td width=35%  class="tl"><?=$user_logincnt?></td>		
	  </tr>		  
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>	  
</table>

<!-- End of User Information -->

<br><br><br>

<div align=center>
	<input type="submit" name="submit" value="<?=$btnName?>" style='cursor:pointer'/>
	&nbsp;&nbsp;&nbsp;
	<a href='/admin/index.php?view=user_list&switched=<?=$switched?>&page=<?=$page?>&key_word=<?=$key_word?>&column=<?=$column?>&sorting_type=<?=$sorting_type?>&switch=<?=$switch?>'><span class='history' style='cursor:pointer'>LIST</span></a>

</div>

</FORM>

