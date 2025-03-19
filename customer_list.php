<?php
// Check if the user has admin privileges
$func->checkAdmin("index.php");

// Ensure session variables are defined and sanitized
$ss_ALEVEL = $_SESSION['ss_ALEVEL'] ?? null;
$key_word = $_POST['key_word'] ?? '';
$column = $_POST['column'] ?? 'SRH_ALL';
$page = $_POST['page'] ?? 1;
$list_count = $_POST['list_count'] ?? $INIT_PAGECNT;
$page_count = $_POST['page_count'] ?? 10;
$switch = $_POST['switch'] ?? '';
$switched = $_POST['switched'] ?? '';
$sorting_type = $_POST['sorting_type'] ?? '';
$getSWHStr = htmlspecialchars($switched, ENT_QUOTES, 'UTF-8');

// Set tag based on access level
$setTag = ($ss_ALEVEL == 1) ? "" : "DISABLED";

// Build search query
$add_srchquery = "";
if (!empty($key_word)) {
    $key_wordStr = urlencode(trim($key_word));
    $add_srchquery .= " AND ((cus_userid LIKE '%$key_wordStr%') OR (cus_fname LIKE '%$key_wordStr%') OR (cus_lname LIKE '%$key_wordStr%') OR (cus_phone LIKE '%$key_wordStr%') OR (cus_cell LIKE '%$key_wordStr%'))";
}

// Build column filter query
$add_allquery = match ($column) {
    "SRH_NOTPAID" => " AND tbl_plan.pln_paid = '0' ",
    "SRH_PAID" => " AND tbl_plan.pln_paid = '1' ",
    default => " ",
};

// Build sorting query
if (!empty($switch)) {
    $switched = $func->switchOrder($switch, $switched);
    $add_query = " ORDER BY $switch $switched ";
    $switched = $switch . "^" . $switched;
} elseif (!empty($switched)) {
    $switched1 = explode("^", $switched);
    $add_query = " ORDER BY $switched1[0] $switched1[1] ";
} else {
    $add_query = " ORDER BY tbl_plan.pln_org_pln_id DESC";
}

// Total record count query
$query = "SELECT COUNT(cus_id) FROM tbl_customer 
          INNER JOIN tbl_plan ON tbl_plan.pln_cus_id = tbl_customer.cus_id 
          WHERE tbl_customer.cus_userid <> '' AND tbl_plan.pln_isold != '1' $add_allquery $add_srchquery";
$total_count = $jdb->rQuery($query, "record query error");

// Pagination variables
$list_number = $total_count - (($page - 1) * $list_count);
$start_number = $list_count * ($page - 1);
$add_query .= " LIMIT $start_number, $INIT_PAGECNT";

// Fetch customer list
$query = "SELECT * FROM tbl_customer 
          INNER JOIN tbl_plan ON tbl_plan.pln_cus_id = tbl_customer.cus_id 
          WHERE tbl_customer.cus_userid <> '' AND tbl_plan.pln_isold != '1' $add_allquery $add_srchquery $add_query";
$result = $jdb->nQuery($query, "list error");

// Build customer list
$strList = "";
while ($list = $result->fetch(PDO::FETCH_ASSOC)) {
    $cus_fnameSTR = htmlspecialchars(stripslashes($list['cus_fname']), ENT_QUOTES, 'UTF-8');
    $cus_lnameSTR = htmlspecialchars(stripslashes($list['cus_lname']), ENT_QUOTES, 'UTF-8');
    $pln_nameSTR = htmlspecialchars(stripslashes($list['pln_name']), ENT_QUOTES, 'UTF-8');
    $cus_useridSTR = ($list['cus_userid'] == "temp@gtatel.com") ? $list['cus_useridtemp'] : $list['cus_userid'];
    $pln_regdateSTR = substr($list['pln_regdate'], 0, 4) . "-" . substr($list['pln_regdate'], 4, 2) . "-" . substr($list['pln_regdate'], 6, 2);
    $pln_paidSTR = ($list['pln_paid'] == 1) ? "Paid" : "Not Paid";

    $strList .= "
        <tr onclick=\"location.href='/admin/index.php?view=customer_info&mode=update&pln_id={$list['pln_id']}&page=$page&key_word=$key_word&column=$column&switched=$getSWHStr&sorting_type=$sorting_type&switch=$switch'\" onmouseover=this.style.background='#EFF1FC' onmouseout=this.style.background='' style='cursor:pointer;'>
            <td height='25' class='tc'>$list_number</td>
            <td class='t2'>{$list['cus_title']}</td>
            <td class='t2'><a href='/admin/index.php?view=customer_info&mode=update&pln_id={$list['pln_id']}&page=$page&key_word=$key_word&column=$column&switched=$getSWHStr&sorting_type=$sorting_type&switch=$switch'><b>$cus_fnameSTR, $cus_lnameSTR</b></a></td>
            <td class='t2'>$cus_useridSTR</td>
            <td class='t2'>$pln_nameSTR</td>
            <td class='t2'>\${$list['pln_costmonth']}</td>
            <td class='t2'>{$list['cus_phone']}</td>
            <td class='t2'>{$list['cus_cell']}</td>
            <td class='tc'>$pln_regdateSTR</td>
            <td class='tc'>{$list['pln_recurringdate']}</td>
            <td class='tc'>$pln_paidSTR</td>
        </tr>
        <tr>
            <td height='1' colspan='11' style='border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;'></td>
        </tr>
    ";
    $list_number--;
}

// Handle no data case
if ($total_count < 1) {
    $TMP_LENGTH = 200; // Maintain window height for empty data
    $strList = "
        <tr height=25><td colspan='10' align=center><b>No Data</b></td></tr>
        <tr>
            <td height='1' colspan='10' style='border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;'></td>
        </tr>
    ";
}
?>

<!-- HTML Output -->
<p><img src="images/icon-list.png" align="absmiddle"><font class="title">CUSTOMERS</font></p>

<form method="post" name="form1" onsubmit="return goSearch(this)" action="<?=htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8');?>">
    <input type="hidden" name="view" value="customer_list">
    <table border="0" cellspacing="0" cellpadding="5">
        <tr>
            <td width="20">&nbsp;</td>
            <td>
                <select name="column" id="select" class="mbox" style="width:180px; height:24px;">
                    <option value="SRH_PAID" <?=($column == "SRH_PAID") ? "selected" : "";?>>Paid</option>
                    <option value="SRH_NOTPAID" <?=($column == "SRH_NOTPAID") ? "selected" : "";?>>Not Paid</option>
                    <option value="SRH_ALL" <?=($column == "SRH_ALL") ? "selected" : "";?>>All</option>
                </select>
            </td>
            <td><input type="text" name="key_word" style="width:150px;" value="<?=htmlspecialchars($key_word, ENT_QUOTES, 'UTF-8');?>" /></td>
            <td><input type="image" src="images/btn-search.png" /></td>
        </tr>
    </table>
</form>

<table width="95%" align="center" border="0" cellpadding="0" cellspacing="1" style="border-top-width:3px; border-bottom-width:3px; border-color:#dddddd; border-top-style:solid; border-bottom-style:solid;">
    <tr>
        <td height="30" class="tdthc">NO</td>
        <td class="tdthl">Salutation</td>
        <td class="tdthl">Customer Name</td>
        <td class="tdthl">Email</td>
        <td class="tdthl">Plan</td>
        <td class="tdthl">Cost</td>
        <td class="tdthl">Home Phone</td>
        <td class="tdthl">Cell</td>
        <td class="tdthc">Join Date</td>
        <td class="tdthc">Recurring Date</td>
        <td class="tdthc">Status</td>
    </tr>
    <tr>
        <td height="1" colspan="11" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
    </tr>
    <?=$strList?>
</table>