<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/session_include.php";
?>

<?

include getenv("DOCUMENT_ROOT")."/include/session_include.php";
$func->checkAdmin("/admin/index.php");

if($CTGSEQ) {
	$query = "SELECT * FROM tbl_category WHERE CTGSEQ = '$CTGSEQ' ";
	$result = $jdb->fQuery($query, "query error");

	//echo "[$query]";
	for($i=0; $i<sizeof($result); $i++) {
		foreach ($result as $key => $value) {
			break;  // 첫 번째 요소만 가져오기 위해 `break` 사용
		}
		$$key = $value;
	}

	$CTGNAME = str_replace("\\", "", $CTGNAME);
	$CTGDESCRIPTION = str_replace("\\", "", $CTGDESCRIPTION);
	if ($CTGPRIORITY == "") $CTGPRIORITY = "999";
	
}else {
	echo"
	<script>
		alert('category number error.');
		history.back();
	</script>
	";
}

if($CTGSECONDCATEGORY == "" && $CTGTHIRDCATEGORY == "") $catSTR = "Revise a 1st Category";
if($CTGSECONDCATEGORY != "" && $CTGTHIRDCATEGORY == "") $catSTR = "Revise a 2nd Category";
if($CTGSECONDCATEGORY != "" && $CTGTHIRDCATEGORY != "") $catSTR = "Revise a 3rd Category";

?>

<? include getenv("DOCUMENT_ROOT")."/admin/include/header.php"; ?>

<body topmargin='10'  leftmargin='10' marginwidth='0' marginheight='0' onload=form1.CTGFIRSTCATEGORY.focus();>
  <FORM name="form1" method="post" action="revise_category.php" ONSUBMIT='return validate(this)' ENCTYPE="multipart/form-data">
  <INPUT TYPE=HIDDEN NAME=CTGSEQ VALUE=<?=$CTGSEQ?>>
  <INPUT TYPE=HIDDEN NAME=CTGDEPTH VALUE=<?=$CTGDEPTH?>>
  <TABLE width="580" border=0 align=center cellSpacing=0 cellPadding=0>
   <TBODY>

      <TR HEIGHT=30>
        <TD WIDTH=630 COLSPAN=2 STYLE='PADDING:3 0 0 0' ALIGN=LEFT  BGCOLOR=#EEEEEE>
          &nbsp;<B>Category > <?=$catSTR?> </B></TD>	
      </TR>

	<input type=hidden name=CTGFIRSTCATEGORY value="<?=htmlspecialchars($CTGFIRSTCATEGORY, ENT_QUOTES)?>"  size=4 maxlength=2 option="number" >
<? if($CTGSECONDCATEGORY) { ?>
	<input type=hidden name=CTGSECONDCATEGORY value="<?=htmlspecialchars($CTGSECONDCATEGORY, ENT_QUOTES)?>"  size=4 maxlength=2 option="number" >
<? if($CTGTHIRDCATEGORY) { ?>
	<input type=hidden name=CTGTHIRDCATEGORY value="<?=htmlspecialchars($CTGTHIRDCATEGORY, ENT_QUOTES)?>"  size=4 maxlength=2 option="number" >
<? }} ?>

	 <TR>
	   <TD height=20></TD>
	 </TR>
	 
	 <TR>
	   <TD class=menu>

		<TABLE cellSpacing=1 cellPadding=0 width="630" border=0>
		  <TBODY>

			<TR>
			   <TD WIDTH=200 height=30 STYLE='PADDING:3 0 0 0' ALIGN=LEFT  BGCOLOR=#F7F7F7>
				<IMG SRC=/admin/images/point.gif> <B>Name of Category</B>
			   </TD>
			   <TD bgcolor=white valign=middle style="padding: 0 0 0 10;"><input type=text name=CTGNAME size=40 required=required maxlength=50 class=input value="<?=htmlspecialchars($CTGNAME, ENT_QUOTES)?>"></TD></TR>
              <TR>
                <TD HEIGHT=1 COLSPAN=2 BACKGROUND=/admin/images/dot_line01.gif></TD>
              </TR>

			<TR>
			   <TD WIDTH=200 height=30 STYLE='PADDING:3 0 0 0' ALIGN=LEFT  BGCOLOR=#F7F7F7>
				<IMG SRC=/admin/images/point.gif> <B>Priority</B>
			   </TD>
			   <TD bgcolor=white valign=middle style="padding: 0 0 0 10;">
			   	<input type=text name=CTGPRIORITY required="required"  option="number" value='<?=$CTGPRIORITY?>' size=10 maxlength=3 class=input>
			   </TD>
			</TR>
			
              <TR>
                <TD HEIGHT=1 COLSPAN=2 BACKGROUND=/admin/images/dot_line01.gif></TD>
              </TR>		
                            
		  </TBODY>
		</TABLE>
	   </TD>
	 </TR>
</TABLE>
<br />

<div align=center><input type=submit value="SUBMIT">
<input type=button value=CLOSE ONCLICK="parent.revisewinpopup.hide(); return false;">
</div>
                              
</FORM>
