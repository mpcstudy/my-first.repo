<?

$func->checkAdmin("/admin/index.php");

$goStr = "switched=$switched&page=$page&key_word=$key_word&column=$column&sorting_type=$sorting_type&switch=$switch";

if ($mode == "create" || $mode == "") {
	$que_answertype = "";
	$btnName = "CREATE";
}
else if ($mode == "update") {
	$btnName = "UPDATE";
	$admTag = "DISABLED";
}


if($mode == "update" && trim($que_id) == "") {
	$msg = "Invalid Question ID. Please try again.[Question ID=$que_id]";
  $func -> goUrl("/admin/index.php?view=question_list&$goStr", $msg);
  
	exit();
}

if ($mode == "update") {

		$sql="SELECT * FROM tbl_question WHERE que_id='$que_id' LIMIT 1;";
		$select=$pdo->query($sql);

		foreach ($select as $row) {
			$que_status      = $row['que_status'];
			$que_class       = $row['que_class'];
			$que_grade       = $row['que_grade'];
			$que_level       = $row['que_level'];
			$que_category1    = $row['que_category1'];
			$que_category2    = $row['que_category2'];
			$que_category3    = $row['que_category3'];
			$que_en_title    = $row['que_en_title'];
			$que_en_desc     = $row['que_en_desc'];
			$que_en_hint     = $row['que_en_hint'];
			$que_en_solution = $row['que_en_solution'];
			$que_en_answers   = $row['que_en_answers'];
			$que_en_answerm   = $row['que_en_answerm'];
			$que_answertype  = $row['que_answertype'];
			$que_en_example   = $row['que_en_example'];
			$que_en_resource = $row['que_en_resource'];
			$que_createddate = $row['que_createddate'];
			$que_modifieddate = $row['que_modifieddate'];

			$que_en_titleSTR = str_replace("\\", "", $que_en_title);
			$que_en_descSTR = str_replace("\\", "", $que_en_desc);	
			$que_en_hintSTR = str_replace("\\", "", $que_en_hint);	
			$que_en_solutionSTR = str_replace("\\", "", $que_en_solution);	
			$que_en_answersSTR = str_replace("\\", "", $que_en_answers);	
			
			if ($que_answertype == 1) {
				$que_en_example = str_replace("\\", "", $que_en_example);	
				$que_en_exampleSTR = explode("|", $que_en_example);
			}
			
			$que_en_resourceSTR = str_replace("\\", "", $que_en_resource);
			
			if ($que_modifieddate != "") $que_modifieddateSTR = "&nbsp;&nbsp;&nbsp;<font color=blue> Updated Date : ".$func -> convertFormat ($que_modifieddate, 1)."</font>";
			else $que_modifieddateSTR = "";
				
		}

}

//echo "[$que_en_titleSTR]<br>[$que_en_descSTR]<br>[$que_en_example]";exit;

$query = "SELECT * FROM tbl_category ORDER BY CTGFIRSTCATEGORY, CTGSECONDCATEGORY, CTGTHIRDCATEGORY";
$result = $jdb->nQuery($query, "option list error");

while($option=mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	$optionSelected = ($que_category1 == $option['CTGSEQ']) ? "SELECTED" : "";
	switch($option['CTGDEPTH']) {
		case "1" : 
		$optionSpace = "#&nbsp;&nbsp;";
		break;
		case "2" : 
		$optionSpace = "##&nbsp;&nbsp;";
		break;
		case "3" : 
		$optionSpace = "###&nbsp;&nbsp;";
		break;
	}
	$que_category1STR .= "
					<OPTION VALUE='$option[CTGSEQ]' $optionSelected>$optionSpace $option[CTGNAME]</OPTION>	
	";
}

$query = "SELECT * FROM tbl_category ORDER BY CTGFIRSTCATEGORY, CTGSECONDCATEGORY, CTGTHIRDCATEGORY";
$result = $jdb->nQuery($query, "option list error");

while($option=mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	$optionSelected = ($que_category2 == $option['CTGSEQ']) ? "SELECTED" : "";
	switch($option['CTGDEPTH']) {
		case "1" : 
		$optionSpace = "#&nbsp;&nbsp;";
		break;
		case "2" : 
		$optionSpace = "##&nbsp;&nbsp;";
		break;
		case "3" : 
		$optionSpace = "###&nbsp;&nbsp;";
		break;
	}
	$que_category2STR .= "
					<OPTION VALUE='$option[CTGSEQ]' $optionSelected>$optionSpace $option[CTGNAME]</OPTION>	
	";
}

$query = "SELECT * FROM tbl_category ORDER BY CTGFIRSTCATEGORY, CTGSECONDCATEGORY, CTGTHIRDCATEGORY";
$result = $jdb->nQuery($query, "option list error");

while($option=mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	$optionSelected = ($que_category3 == $option['CTGSEQ']) ? "SELECTED" : "";
	switch($option['CTGDEPTH']) {
		case "1" : 
		$optionSpace = "#&nbsp;&nbsp;";
		break;
		case "2" : 
		$optionSpace = "##&nbsp;&nbsp;";
		break;
		case "3" : 
		$optionSpace = "###&nbsp;&nbsp;";
		break;
	}
	$que_category3STR .= "
					<OPTION VALUE='$option[CTGSEQ]' $optionSelected>$optionSpace $option[CTGNAME]</OPTION>	
	";
}

foreach ($arrStatus AS $key=>$value)
{
	//echo "[$key][$value]";   => [0][Waiting][1][Confirmed][E][Declined]...
	if ($key == $que_status) $selectStr = "selected";
	else $selectStr = "";
	
	$que_statusSTR .= "
								<option value='$key' $selectStr>$value</option>";
}


foreach ($arrClass AS $key=>$value)
{
	//echo "[$key][$value]";   => [0][Waiting][1][Confirmed][E][Declined]...
	if ($key == $que_class) $selectStr = "selected";
	else $selectStr = "";
	
	$que_classSTR .= "
								<option value='$key' $selectStr>$value</option>";
}


foreach ($arrLevel AS $key=>$value)
{
	//echo "[$key][$value]";   => [0][Waiting][1][Confirmed][E][Declined]...
	if ($key == $que_level) $selectStr = "selected";
	else $selectStr = "";
	
	$que_levelSTR .= "
								<option value='$key' $selectStr>$value</option>";
}


foreach ($arrGrade AS $key=>$value)
{
	//echo "[$key][$value]";   => [0][Waiting][1][Confirmed][E][Declined]...
	if ($key == $que_grade) $selectStr = "selected";
	else $selectStr = "";
	
	$que_gradeSTR .= "
								<option value='$key' $selectStr>$value</option>";
}

	
?>

<script type="text/javascript" src="/admin/js/tinymce/tinymce.min.js"></script>
<script src="/admin/js/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image"></script>

<!--
<script type="text/javascript">
		tinymce.init({
			inline: true,
			selector: '.myeditablediv',
			language: 'en',
			directionality : 'ltr',
			menubar : false,
			plugins: 'tiny_mce_wiris',
			toolbar: 'code,|,bold,italic,underline,|,cut,copy,paste,|,search,|,undo,redo,|,forecolor,backcolor,|,justifyleft,justifycenter,justifyright,fontselect,fontsizeselect,|,tiny_mce_wiris_formulaEditor,tiny_mce_wiris_formulaEditorChemistry,|,fullscreen',
			setup : function(ed)
			{
				ed.on('init', function() 
				{
					this.getDoc().body.style.fontSize = '16px';
					this.getDoc().body.style.fontFamily = 'Arial, "Helvetica Neue", Helvetica, sans-serif';
				});
			},
			
		});
</script>
-->

<script type="text/javascript">

tinymce.init({
  forced_root_block : "",
  selector: ".myeditablediv",
  menubar: "false",
  //content_style: "margin: 4px;font-size:20pt;font-family: Arial;",
  //content_css : '/admin/js/tinymce/skins/lightgray/ebizple.css',
  //selector: 'textarea',
	//body_id: 'que_en_titleid,que_en_descid,que_en_solutionid ',
	
  // ===========================================
  // INCLUDE THE PLUGIN
  // ===========================================
  plugins: "advlist,autolink,lists,link,charmap,print,preview,anchor,searchreplace,visualblocks,code,fullscreen,insertdatetime,media,table,contextmenu,jbimages,textcolor,tiny_mce_wiris",
    
			toolbar: 'insertfile, code,|,bold,italic,underline,|,cut,copy,paste,|,search,|,table, link, image, jbimages,|,undo,redo,|,forecolor,backcolor,|,justifyleft,justifycenter,justifyright,fontselect,fontsizeselect,|,tiny_mce_wiris_formulaEditor,tiny_mce_wiris_formulaEditorChemistry,|,fullscreen',

			setup : function(ed)
			{
				ed.on('init', function() 
				{
					this.getDoc().body.style.fontSize = '12pt';
					this.getDoc().body.style.fontFamily = 'Arial, "Helvetica Neue", Helvetica, sans-serif';
				});
		
			},
  // ===========================================
  // PUT PLUGIN'S BUTTON on the toolbar
  // ===========================================
	
  //toolbar: "insertfile undo redo | styleselect | fontselect |  fontsizeselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | tiny_mce_wiris_formulaEditor | tiny_mce_wiris_formulaEditorChemistry",
	
  // ===========================================
  // SET RELATIVE_URLS to FALSE (This is required for images to display properly)
  // ===========================================
	//remove_script_host : false,
  //convert_urls : false,	
  //relative_urls: false,

	relative_urls : false,
	remove_script_host : true,
	document_base_url : "http://<?=$_SERVER['HTTP_HOST']?>/",   
	convert_urls : false,
		  
	
});

</script>

	
<script type="text/javascript"> 

function pagevalidate_questioninfo(ff) {  
	var f = document.questioninfo;
	var reg = /^((\w|[\-\.])+)@((\w|[\-\.])+)\.([A-Za-z]+)$/;

	if (f.cus_userid.value == '' || f.cus_userid.value == null)
	{
		alert("Please input User ID");
		f.cus_userid.focus();
		return false;
	} else {

		if (f.cus_userid.value.search(reg) == -1) {
			alert("Incorrect email type. Please try again.");
			f.cus_userid.value= "";
			f.cus_userid.focus();
			return false;
		}
	}

	if (f.cus_password.value == '' || f.cus_password.value == null)
	{
		alert("Please input password.");
		f.cus_password.focus();
		return false;
	}

	if (f.pln_regdate.value == '' || f.pln_regdate.value == null)
	{
		alert("Please select Join date.");
		f.pln_regdate.focus();
		return false;
	}
		
	if (f.cus_title.value == '' || f.cus_title.value == null)
	{
		alert("Please select Salutation.");
		f.cus_title.focus();
		return false;
	}
	
	if (f.cus_lname.value == '' || f.cus_lname.value == null)
	{
		alert("Please input last name.");
		f.cus_lname.focus();
		return false;
	}

	if (f.cus_fname.value == '' || f.cus_fname.value == null)
	{
		alert("Please input first name.");
		f.cus_fname.focus();
		return false;
	}

	if (f.cus_phone.value == '' || f.cus_phone.value == null)
	{
		alert("Please input Home phone.");
		f.cus_phone.focus();
		return false;
	}
	
	if (f.cus_cell.value == '' || f.cus_cell.value == null)
	{
		alert("Please input Cell.");
		f.cus_cell.focus();
		return false;
	}
		
	if (f.add_postalcode.value == '' || f.add_postalcode.value == null)
	{
		alert("Please input the postal code.");
		f.add_postalcode.focus();
		return false;
	}

  if (document.getElementById('samedataid').checked) {
  }
	else {

		if (f.add_postalcodes.value == '' || f.add_postalcodse.value == null)
		{
			alert("Please input the postal code.");
			f.add_postalcodes.focus();
			return false;
		}
	
	}
		
	//alert(index);
	return true;
}


function answerType() {

		var aa = document.getElementById('answer1id').checked;
		var bb = document.getElementById('answer2id').checked;

//document.getElementById('ifSHORT').style.display = 'none';
//document.getElementById('ifMULTIPLE').style.display = 'none';

    if (document.getElementById('answer1id').checked) {
        document.getElementById('ifSHORT').style.display = 'inline';
        document.getElementById('ifMULTIPLE').style.display = 'none';
    }
		else if (document.getElementById('answer2id').checked) {
				document.getElementById('ifSHORT').style.display = 'none';
        document.getElementById('ifMULTIPLE').style.display = 'inline';				
		}  
}

<? if ($que_answertype == 0) { ?>
$(document).ready(function(){
        document.getElementById('ifSHORT').style.display = 'inline';
        document.getElementById('ifMULTIPLE').style.display = 'none';
});
<? } else if ($que_answertype == 1) { ?>
$(document).ready(function(){
				document.getElementById('ifSHORT').style.display = 'none';
        document.getElementById('ifMULTIPLE').style.display = 'inline';				
});
<? } else { ?>
$(document).ready(function(){
				document.getElementById('ifSHORT').style.display = 'none';
        document.getElementById('ifMULTIPLE').style.display = 'none';				
});
<? } ?>


</SCRIPT>


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

<p>
	<img src="images/icon-list.png" align="absmiddle">
	<font class="title">QUESTION</font>
</p>



<div style="line-height:10px;">&nbsp;</div> 


<!-- Start of Question Information -->

<p><img src="images/icon-narr.png" align="absmiddle"><font class="stitle">QUESTION INFORMATION</font> <font color=red>(UNIQUE ID: <?=$que_id?>)</font> <?=$que_modifieddateSTR?> </p>


<FORM NAME=questioninfo METHOD=POST ACTION=/admin/doc/question_process.php ONSUBMIT='return pagevalidate_questioninfo(this); return false;'>

<input type=hidden name=mode id="modeid" value="<?=$mode?>">
<input type=hidden name=que_id value="<?=$que_id?>">
<input type=hidden name=emailValidate id='emailValidateid' value="">
<input type=hidden name=actionStr value="QUESTIONINFO">
<input type=hidden name=directurl value="index.php">

<input type=hidden name=switched value="<?=$switched?>">
<input type=hidden name=page value="<?=$page?>">
<input type=hidden name=key_word value="<?=$key_word?>">
<input type=hidden name=column value="<?=$column?>">
<input type=hidden name=sorting_type value="<?=$sorting_type?>">
<input type=hidden name=switch value="<?=$switch?>">

<table width="95%" align="center"  border="0" cellpadding="0" cellspacing="1" style="border-top-width:3px; border-left-width:3px; border-right-width:3px; border-bottom-width:3px; border-color:#dddddd; border-top-style:solid; border-left-style:solid; border-right-style:solid; border-bottom-style:solid;">
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Status</td>
	    <td width=35% class="tl">
				<SELECT NAME='que_status' id="que_statusid" style="width:200px; height:30px;" class="mbox">
		    	<option value='' >Select</option>
					<?=$que_statusSTR?>
				</SELECT>	 	    
	    </td>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Class</td>
	    <td width=35% class="tl">
				<SELECT NAME='que_class' id="que_classid" style="width:200px; height:30px;" class="mbox">
		    	<option value='' >Select</option>
					<?=$que_classSTR?>
				</SELECT>	 	    
	    </td>	    
	  </tr>
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr> 
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Category1</td>
	    <td colspan=3 class="tl">
				<SELECT NAME='que_category1' id="que_category1id" style="width:400px; height:30px;" class="mbox">
		    	<option value='' >Select</option>
					<?=$que_category1STR?>
				</SELECT>	 	    
	    </td>	  	  		  	
	  </tr>
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr> 
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Category2</td>
	    <td colspan=3 class="tl">
				<SELECT NAME='que_category2' id="que_category2id" style="width:400px; height:30px;" class="mbox">
		    	<option value='' >Select</option>
					<?=$que_category2STR?>
				</SELECT>	 	    
	    </td>	  	  		  	
	  </tr>	
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr> 
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Category3</td>
	    <td colspan=3 class="tl">
				<SELECT NAME='que_category3' id="que_category3id" style="width:400px; height:30px;" class="mbox">
		    	<option value='' >Select</option>
					<?=$que_category3STR?>
				</SELECT>	 	    
	    </td>	  	  		  	
	  </tr>		  	  
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr> 
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Grade</td>
	    <td width=35% class="tl">
				<SELECT NAME='que_grade' id="que_gradeid" style="width:200px; height:30px;" class="mbox">
		    	<option value='' >Select</option>
					<?=$que_gradeSTR?>
				</SELECT>	 	    
	    </td>	  	
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Difficulty</td>
	    <td width=35% class="tl">
				<SELECT NAME='que_level' id="que_levelid" style="width:200px; height:30px;" class="mbox">
		    	<option value='' >Select</option>
					<?=$que_levelSTR?>
				</SELECT>	 
    	</td>  		  	
	  </tr>
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>
	  <tr>
	    <td width=15% height="30" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Title</td>
	    <td colspan=3 class="tl">
	    	<textarea rows="2" cols="100" name="que_en_title" id="que_en_titleid" class="myeditablediv"><?=htmlspecialchars($que_en_titleSTR, ENT_QUOTES)?></textarea>
	    </td>	       	
	  </tr>	   
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>
	  <tr>
	    <td width=15% height="30" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Description</td>
	    <td colspan=3 class="tl"><textarea rows="20" cols="100" name="que_en_desc" id="que_en_descid" class="myeditablediv"><?=htmlspecialchars($que_en_descSTR, ENT_QUOTES)?></textarea></td>	       	
	  </tr>	   
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>

	  <tr>
	    <td width=15% height="30" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Solution</td>
	    <td colspan=3 class="tl"><textarea rows="20" cols="100" name="que_en_solution" id="que_en_solutionid" class="myeditablediv"><?=htmlspecialchars($que_en_solutionSTR, ENT_QUOTES)?></textarea></td>	       	
	  </tr>	   
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>
	  <tr>
	    <td width=15% height="30" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Hint</td>
	    <td colspan=3 class="tl"><textarea rows="20" cols="100" name="que_en_hint" id="que_en_hintid" class="myeditablediv"><?=htmlspecialchars($que_en_hintSTR, ENT_QUOTES)?></textarea></td>	       	
	  </tr>	   
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>
	  	  
	  <tr>
	    <td width=15% height="30" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Resources</td>
	    <td colspan=3 class="tl"><textarea rows="10" cols="100" name="que_en_resource" id="que_en_resourceid" class="myeditablediv"><?=htmlspecialchars($que_en_resourceSTR, ENT_QUOTES)?></textarea></td>	       	
	  </tr>	   
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>	  
	  <tr>
	    <td width=15% height="30" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Answer Type</td>
	    <td colspan=3 class="tl">
	    	<input type="radio" id="answer1id" name="que_answertype" value="0" onclick="answerType(this.form)" <?if($que_answertype=="0" || $que_answertype=="") echo"checked";?> /> Short Answer
	    	&nbsp;&nbsp;&nbsp;
	    	<input type="radio" id="answer2id" name="que_answertype" value="1" onclick="answerType(this.form)" <?if($que_answertype=="1") echo"checked";?> /> Multiple Choice
	    </td>	       	
	  </tr>	   
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>
</table>
	  
<div id="ifSHORT" style="DISPLAY:none;">
<table width="95%" align="center"  border="0" cellpadding="0" cellspacing="1" style="border-top-width:3px; border-left-width:3px; border-right-width:3px; border-bottom-width:3px; border-color:#dddddd; border-top-style:solid; border-left-style:solid; border-right-style:solid; border-bottom-style:solid;">
	  <tr>
	    <td width=15% height="30" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Answer</td>
	    <td colspan=3 class="tl"><textarea rows="20" cols="100" name="que_en_answers" id="que_en_answersid" class="myeditablediv"><?=htmlspecialchars($que_en_answersSTR, ENT_QUOTES)?></textarea></td>	       	
	  </tr>	   
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>
</table>	  
</div>	
  
<div id="ifMULTIPLE" style="DISPLAY:none;">
<table width="95%" align="center"  border="0" cellpadding="0" cellspacing="1" style="border-top-width:3px; border-left-width:3px; border-right-width:3px; border-bottom-width:3px; border-color:#dddddd; border-top-style:solid; border-left-style:solid; border-right-style:solid; border-bottom-style:solid;">
	  <tr>
	    <td width=15% height="30" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Answer</td>
	    <td colspan=3 class="tl">
	    	<input type="radio" name="que_en_answerm" value="1" <?if($que_en_answerm=="1") echo"checked";?> /> Example 1
	    	&nbsp;&nbsp;&nbsp;
	    	<input type="radio" name="que_en_answerm" value="2" <?if($que_en_answerm=="2") echo"checked";?> /> Example 2
	    	&nbsp;&nbsp;&nbsp;
	    	<input type="radio" name="que_en_answerm" value="3" <?if($que_en_answerm=="3") echo"checked";?> /> Example 3
	    	&nbsp;&nbsp;&nbsp;
	    	<input type="radio" name="que_en_answerm" value="4" <?if($que_en_answerm=="4") echo"checked";?> /> Example 4
	    	&nbsp;&nbsp;&nbsp;
	    	<input type="radio" name="que_en_answerm" value="5" <?if($que_en_answerm=="5") echo"checked";?> /> Example 5	 	    	
	    </td>	       	
	  </tr>	   
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>
	  <tr>
	    <td width=15% height="30" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Example 1</td>
	    <td colspan=3 class="tl"><textarea rows="7" cols="100" name="que_en_example1" id="que_en_example1id" class="myeditablediv"><?=htmlspecialchars($que_en_exampleSTR[0], ENT_QUOTES)?></textarea></td>	       	
	  </tr>	   
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>		
	  <tr>
	    <td width=15% height="30" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Example 2</td>
	    <td colspan=3 class="tl"><textarea rows="7" cols="100" name="que_en_example2" id="que_en_example2id" class="myeditablediv"><?=htmlspecialchars($que_en_exampleSTR[1], ENT_QUOTES)?></textarea></td>	       	
	  </tr>	   
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>	
	  <tr>
	    <td width=15% height="30" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Example 3</td>
	    <td colspan=3 class="tl"><textarea rows="7" cols="100" name="que_en_example3" id="que_en_example3id" class="myeditablediv"><?=htmlspecialchars($que_en_exampleSTR[2], ENT_QUOTES)?></textarea></td>	       	
	  </tr>	   
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>	
	  <tr>
	    <td width=15% height="30" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Example 4</td>
	    <td colspan=3 class="tl"><textarea rows="7" cols="100" name="que_en_example4" id="que_en_example4id" class="myeditablediv"><?=htmlspecialchars($que_en_exampleSTR[3], ENT_QUOTES)?></textarea></td>	       	
	  </tr>	   
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>
	  <tr>
	    <td width=15% height="30" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Example 5</td>
	    <td colspan=3 class="tl"><textarea rows="7" cols="100" name="que_en_example5" id="que_en_example5id" class="myeditablediv"><?=htmlspecialchars($que_en_exampleSTR[4], ENT_QUOTES)?></textarea></td>	       	
	  </tr>	   
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>		  
</table>
</div>

<!-- End of Question Information -->

<br><br><br>

<div align=center>
	<input type="submit" name="submit" value="<?=$btnName?>" style='cursor:pointer'/>
	&nbsp;&nbsp;&nbsp;
	<a href='/admin/index.php?view=question_list&switched=<?=$switched?>&page=<?=$page?>&key_word=<?=$key_word?>&column=<?=$column?>&sorting_type=<?=$sorting_type?>&switch=<?=$switch?>'><span class='history' style='cursor:pointer'>LIST</span></a>

</div>

</FORM>
