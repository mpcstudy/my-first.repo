<?

//include getenv("DOCUMENT_ROOT")."/config/config_shopInfo.php";
include getenv("DOCUMENT_ROOT")."/include/session_include.php";

if($mode=="process") {

	$query = "select count(*) from member where m_userID='$checkID' ";
	$sameID = $jdb->rQuery($query, "fetched query error");
	$jdb->CLOSE();

	if($sameID>0) {

		echo"<meta http-equiv='refresh' content='0; url=/admin/member/id.php?mode=result&checkID=$checkID&result=fail'>";

	}else {

		echo"<meta http-equiv='refresh' content='0; url=/admin/member/id.php?mode=result&checkID=$checkID&result=ok'>";

	}



}else if($mode=="result")
{
?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<TITLE>:: EFXClub.com ::</TITLE>
<link rel="stylesheet" href="/js/view.css" type="text/css">
<link rel="stylesheet" href="/css/style.css">
<SCRIPT LANGUAGE=JAVASCRIPT SRC=/js/lib.validate.js></SCRIPT>
<script language="javascript">
<!--//
function submitForm1()
{
	document.form1.submit();
}

function checkForm(){
    var f=document.form1;

    var alphaDigit= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; 
     
     
    if (f.checkID.value == ''){ 
        alert("아이디를 입력해 주세요."); 
        f.checkID.focus();
        return false; 
    } 
    if (f.checkID.value.length < 6 || f.checkID.value.length > 12){ 
        alert("아이디는 6~12자 이내여야 합니다."); 
        f.checkID.value="";         
        f.checkID.focus();
        return false; 
    } 
    if (f.checkID.value.indexOf(" ") >=0) { 
        alert("아이디에는 공백이 들어가면 안됩니다."); 
        f.checkID.value=""; 
        f.checkID.focus();
        return false; 
    } 
    for (i=0;i< f.checkID.value.length;i++){ 
        if (alphaDigit.indexOf(f.checkID.value.substring(i, i+1)) == -1) 
        { 
            alert("아이디는 영문과 숫자의 조합만 사용할 수 있습니다."); 
            f.checkID.value=""; 
            f.checkID.focus();
            return false; 
        } 
    } 
    
}
-->
</script>
</head>

<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" bgcolor="#D6E9E8">
<CENTER>
<?
if($result=="fail")
{
?>
<!-- 사용불가능 -->

<table border="0" cellpadding="3" cellspacing="3" width="200" >
	<form name=form1 method=post action=/admin/member/id.php ONSUBMIT='return checkForm(this);'>
	<input type=hidden name=mode value=process>
    <tr>
        <td height="10"></td>
    </tr>
    <tr>
        <td height="1" background="/admin/images/pop_id_01.gif"></td>
    </tr>
    <tr>
        <td height="30" align="center" valign="bottom" background="/admin/images/pop_id_02.gif">
        <span class="blue14b">아이디 중복 확인</span>
        </td>
    </tr>    
    <tr>
        <td height="30" align="center" valign="top" background="/admin/images/pop_id_02.gif">
        <b><?=$checkID?></b> is <font color="red"> Not </font> available
      </td>
    </tr>
    <tr>
        <td height="25" background="/admin/images/pop_id_03.gif" align="center">
        Enter other User ID
        <input name="checkID" type="text" id="tid" style="width:120" size="30" maxlength=50 required="required">
        <input type=submit value="Submit">
        </td>
    </tr>
	</form>
    <tr>
        <td height="7" background="/admin/images/pop_id_02.gif"></td>
    </tr>
    <tr>
        <td height="1" background="/admin/images/pop_id_01.gif"></td>
    </tr>
    <tr>
        <td height="15"></td>
    </tr>
</table>
<!-- //사용불가능 -->

<?
}else if($result=="ok")
{
?>
<!-- 사용가능 -->
<table border="0" cellpadding="3" cellspacing="3" width="200">
<script>
function close_insertID() {
	opener.document.memberInfo.m_userID.value = "<?=$checkID?>";
	window.close();
}
</script>

    <tr>
        <td height="10"></td>
    </tr>
    <tr>
        <td height="1" background="/admin/images/pop_id_01.gif"></td>
    </tr>
    <tr>
        <td height="30" align="center" valign="bottom" background="/admin/images/pop_id_02.gif">
        <span class="blue14b">아이디 중복 확인</span>
        </td>
    </tr>     
    <tr>
        <td height="30" align="center" valign="top" background="/admin/images/pop_id_02.gif">
        <b><?=$checkID?></b> is Available
        <br><br>       
        <input type="button" value="Use this ID" size="15" class="button" onClick="close_insertID();"></td>
    </tr>
    <tr>
        <td height="7" background="/admin/images/pop_id_02.gif"></td>
    </tr>
    <tr>
        <td height="1" background="/admin/images/pop_id_01.gif"></td>
    </tr>
    <tr>
        <td height="15"></td>
    </tr>
</table>
<!-- //사용가능 -->
<?}?>
</CENTER>
</body>

</html>
<?}?>