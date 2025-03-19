<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/session_include.php";
?>

<?php

include getenv("DOCUMENT_ROOT")."/include/session_include.php";

$func->checkAdmin("/admin/index.php");


if($CTGSEQ) {
	$query = "SELECT * FROM tbl_category WHERE CTGSEQ = '$CTGSEQ' ";
	$result = $jdb->fQuery($query, "query error");

	for($i=0; $i<sizeof($result); $i++) {
		foreach ($result as $key => $value) {
			break;  // 첫 번째 요소만 가져오기 위해 `break` 사용
		}
		$$key = $value;
	}

	if($CTGDEPTH == "1") {
		$query  = " SELECT MAX(CTGSECONDCATEGORY) AS CTGSECONDCATEGORY FROM tbl_category ";
		$query .= " WHERE CTGFIRSTCATEGORY = '$CTGFIRSTCATEGORY' ";
		$result = $jdb->fQuery($query, "query error");
		$CTGSECONDCATEGORY = (int)$result['CTGSECONDCATEGORY'] + 1;
		if(strlen($CTGSECONDCATEGORY) == 1) $CTGSECONDCATEGORY = "0" . $CTGSECONDCATEGORY;
	} else if($CTGDEPTH == "2") {
		$query  = " SELECT MAX(CTGTHIRDCATEGORY) AS CTGTHIRDCATEGORY FROM tbl_category ";
		$query .= " WHERE CTGFIRSTCATEGORY = '$CTGFIRSTCATEGORY' AND CTGSECONDCATEGORY = '$CTGSECONDCATEGORY' ";
		$result = $jdb->fQuery($query, "query error");
		$CTGTHIRDCATEGORY = (int)$result['CTGTHIRDCATEGORY'] + 1;
		if(strlen($CTGTHIRDCATEGORY) == 1)      $CTGTHIRDCATEGORY = "00" . $CTGTHIRDCATEGORY;
		else if(strlen($CTGTHIRDCATEGORY) == 2) $CTGTHIRDCATEGORY = "0"  . $CTGTHIRDCATEGORY;
	}

}else {
	$query = "SELECT MAX(CTGFIRSTCATEGORY) AS CTGFIRSTCATEGORY FROM tbl_category ";
	$result = $jdb->fQuery($query, "query error");
	$CTGFIRSTCATEGORY = $result['CTGFIRSTCATEGORY'] + 1;
	if(strlen($CTGFIRSTCATEGORY) == 1) $CTGFIRSTCATEGORY = "0" . $CTGFIRSTCATEGORY;
	$CTGDEPTH = 1;
}

if($CTGSECONDCATEGORY == "" && $CTGTHIRDCATEGORY == "") $catSTR = "Create a 1st Category";
if($CTGSECONDCATEGORY != "" && $CTGTHIRDCATEGORY == "") $catSTR = "Create a 2nd Category";
if($CTGSECONDCATEGORY != "" && $CTGTHIRDCATEGORY != "") $catSTR = "Create a 3rd Category";

?>


<? include getenv("DOCUMENT_ROOT")."/admin/include/header.php"; ?>

<body topmargin='10' leftmargin='10' marginwidth='0' marginheight='0' onload=form1.CTGFIRSTCATEGORY.focus();>
  <FORM name="form1" method="post" action="create_category.php" ONSUBMIT='return validate(this)'  ENCTYPE="multipart/form-data">
  <TABLE width="580" border=0 align=center cellSpacing=0 cellPadding=0 >
   <TBODY>
      <TR HEIGHT=30>
        <TD WIDTH=630 COLSPAN=2 STYLE='PADDING:3 0 0 0' ALIGN=LEFT  BGCOLOR=#EEEEEE>
          &nbsp;<B>Category > <?=$catSTR?> </B></TD>	
      </TR>
	<input type=hidden name=CTGGRADE value='<?=$CTGGRADE?>'>
	<input type=hidden name=CTGCLASS value='<?=$CTGCLASS?>'>
	
	<input type=hidden name=CTGFIRSTCATEGORY value='<?=$CTGFIRSTCATEGORY?>'>
<? if($CTGSECONDCATEGORY) { ?>
	<input type=hidden name=CTGSECONDCATEGORY value='<?=$CTGSECONDCATEGORY?>'>
<? if($CTGTHIRDCATEGORY) { ?>
	<input type=hidden name=CTGTHIRDCATEGORY value='<?=$CTGTHIRDCATEGORY?>'>
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
			   <TD bgcolor=white valign=middle style="padding: 0 0 0 10;"><input type=text name=CTGNAME size=40 maxlength=50 required="required" class=input></TD>
			</TR>
              <TR>
                <TD HEIGHT=1 COLSPAN=2 BACKGROUND=/admin/images/dot_line01.gif></TD>
              </TR>

			<TR>
			   <TD WIDTH=200 height=30 STYLE='PADDING:3 0 0 0' ALIGN=LEFT  BGCOLOR=#F7F7F7>
				<IMG SRC=/admin/images/point.gif> <B>Priority</B>
			   </TD>
			   <TD bgcolor=white valign=middle style="padding: 0 0 0 10;">
			   	<input type=text name=CTGPRIORITY required="required"  option="number" value='999' size=10 maxlength=3 class=input>
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
<input type=button value=CLOSE ONCLICK="parent.createwinpopup.hide(); return false;">
</div>
                              
</FORM>