<?

//include getenv("DOCUMENT_ROOT")."/config/config_shopInfo.php";
include getenv("DOCUMENT_ROOT")."/include/session_include.php";

if($mode=="process") {

	$query = "select count(*) from tbl_user where MID = '$checkID' ";
	$sameID = $jdb->rQuery($query, "fetched query error");
	$jdb->CLOSE();

	if($sameID>0) {

		echo"<meta http-equiv='refresh' content='0; url=/doc/member/id.php?mode=result&checkID=$checkID&result=fail'>";

	}else {

		echo"<meta http-equiv='refresh' content='0; url=/doc/member/id.php?mode=result&checkID=$checkID&result=ok'>";

	}



}else if($mode=="result")
{
?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>:: ID Check ::</TITLE>
<link rel="stylesheet" href="/js/view.css" type="text/css">
<link rel="stylesheet" href="/css/style.css">
<SCRIPT LANGUAGE=JAVASCRIPT SRC=/js/lib.validate.js></SCRIPT>
<script language="javascript">
<!--//
function submitForm1()
{
	document.form1.submit();
}

function trimStr(str) {
  return str.replace(/^\s+|\s+$/g, '');
}

function checkForm(){
    var f=document.form1;

    var alphaDigit= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; 
     
     
    if (trimStr(f.checkID.value) == ''){ 
        //alert("아이디를 입력해 주세요."); 
        alert("Please input user ID.");
        f.checkID.value="";
        f.checkID.focus();
        return false; 
    } 
    if (f.checkID.value=="admin" || f.checkID.value=="root"){ 
        //alert("아이디를 입력해 주세요."); 
        alert("Please input other user ID");
        f.checkID.value="";   
        f.checkID.focus();
        return false; 
    }     
    if (f.checkID.value.length < 3 || f.checkID.value.length > 12){ 
        //alert("아이디는 3~12자 이내여야 합니다."); 
        alert(" Must be 3~12 characters."); 
        f.checkID.value="";         
        f.checkID.focus();
        return false; 
    } 
    if (f.checkID.value.indexOf(" ") >=0) { 
        //alert("아이디에는 공백이 들어가면 안됩니다."); 
        alert("We are not accept the space."); 
        f.checkID.value=""; 
        f.checkID.focus();
        return false; 
    } 
    for (i=0;i< f.checkID.value.length;i++){ 
        if (alphaDigit.indexOf(f.checkID.value.substring(i, i+1)) == -1) 
        { 
            //alert("아이디는 영문과 숫자의 조합만 사용할 수 있습니다."); 
            alert("You can only use letters and numbers."); 
            f.checkID.value=""; 
            f.checkID.focus();
            return false; 
        } 
    } 
    
}
-->
</script>
</head>

<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" >
<CENTER>
<?
if($result=="fail")
{
?>
<!-- 사용불가능 -->
<BR>

	<TABLE BORDER="0" width="300" height=120 cellpadding="0" cellspacing="1" bgcolor="#e4e4e4">
	<form name=form1 method=post action=/doc/member/id.php ONSUBMIT='return checkForm(this);'>
	<input type=hidden name=mode value=process>

<!--
		<TR>
			<TD height="15" align="center" bgcolor="#DDDDDD"><b><font color=black> 아이디 중복 확인 </font></b> </TD>
		</TR>
    
		<tr>
			<td align="center" height="30" bgcolor="white">&nbsp;&nbsp;&nbsp;
        <b><?=$checkID?></b> 은 이미 <font color="red">사용중</font>입니다.
        <br>       
         다른 아이디를 입력하세요.<br><br>
        <input name="checkID" type="text" id="tid" style="width:150" size="30" maxlength=50 required="required">
        <input type=submit value=" 확 인 ">
        </td>
			</td>
    </tr> 
-->


		<TR>
			<TD height="15" align="center" bgcolor="#0f216c"><b><font color=white> ID CHECK </font></b> </TD>
		</TR>
    
		<tr>
			<td align="center" height="30" bgcolor="white">&nbsp;&nbsp;&nbsp;
        <b><?=$checkID?></b> is <font color="red">unavailable</font>.
        <br>       
         Please input other ID.<br><br>
        <input name="checkID" type="text" id="tid" style="width:150" size="30" maxlength=50 required="required">
        <input type=submit value=" SUBMIT ">
        </td>
			</td>
    </tr> 
    
    
    </form>  
</table>

<!-- //사용불가능 -->

<?
}else if($result=="ok")
{
?>
<!-- 사용가능 -->
<script>
function close_insertID() {
	opener.document.memberInfo.MID.value = "<?=$checkID?>";
	window.close();
}
</script>
<BR>

<!--
	<TABLE BORDER="0" width="300" height=120 cellpadding="0" cellspacing="1" bgcolor="#e4e4e4">
		<TR>
			<TD height="15" align="center" bgcolor="#DDDDDD"><b><font color=black> 아이디 중복 확인 </font></b> </TD>
		</TR>
    
		<tr>
			<td align="center" height="30" bgcolor="white">&nbsp;&nbsp;&nbsp;
        <b><?=$checkID?></b> 은 사용하실수 있습니다.
        <br><br>       
        <input type="button" value=" 사 용 " size="15" class="button"  STYLE='border:2 groove; background-color:#EEEEEE;cursor:hand;' onClick="close_insertID();"></td>
    </tr>   
</table>
-->

	<TABLE BORDER="0" width="300" height=120 cellpadding="0" cellspacing="1" bgcolor="#e4e4e4">
		<TR>
			<TD height="15" align="center" bgcolor="#0f216c"><b><font color=white> ID CHECK </font></b> </TD>
		</TR>
    
		<tr>
			<td align="center" height="30" bgcolor="white">&nbsp;&nbsp;&nbsp;
        <b><?=$checkID?></b> is available.
        <br><br>       
        <input type="button" value=" USE THIS ID " size="15" class="button"  STYLE='border:2 groove; background-color:#EEEEEE;cursor:hand;' onClick="close_insertID();"></td>
    </tr>   
</table>

<!-- //사용가능 -->
<?}?>
</CENTER>
</body>

</html>
<?}?>