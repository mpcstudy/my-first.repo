<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/session_include.php";

// 🔹 변수 초기화 (Undefined Variable 방지)
$mode = $_POST['mode'] ?? '';
$uid = $_POST['uid'] ?? '';
$expiredate_from = '';
$expiredate_to = '';
$status = $_POST['status'] ?? '';

// 🔹 관리자인지 확인
$func->checkAdmin("/admin/index.php");

$btnSTR = ($mode === "modify") ? "revisewinpopup" : "createwinpopup";

if ($mode === "modify") {
	if ($uid) {
		// 🔹 DB 연결 확인
		if (!$jdb->DBConn instanceof mysqli) {
			die("Database connection not established.");
		}
	
		// 🔹 SQL 인젝션 방지 (Prepared Statement 사용)
		$query = "SELECT * FROM tbl_pcodes WHERE uid = ?";
		$stmt = $jdb->DBConn->prepare($query); // 🚀 오류 수정됨!
		$stmt->bind_param("s", $uid);
		$stmt->execute();
		$result = $stmt->get_result()->fetch_assoc();

        if ($result) {
            foreach ($result as $key => $value) {
                $$key = $value;
            }

            $expiredate_fromSTR = ($expiredate_from === "00000000") ? "" : $expiredate_from;
            $expiredate_toSTR = ($expiredate_to === "99999999") ? "" : $expiredate_to;
        } else {
            echo "<script>
                alert('Coupon code error. Please check coupon code.');
                parent.createwinpopup.hide();
            </script>";
        }
    }
}
?>

<?php include getenv("DOCUMENT_ROOT") . "/admin/include/header.php"; ?>

<script>
$(function() {
    var date = new Date();
    date.setDate(date.getDate());

    $("#expiredate_fromid").datepicker({
        autoclose: true,
        startDate: date,
        dateFormat: 'yymmdd',
        daysOfWeekDisabled: [0]
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#user_servicetoid').datepicker('setStartDate', minDate);
    });

    $("#expiredate_toid").datepicker({
        autoclose: true,
        dateFormat: 'yymmdd',
        daysOfWeekDisabled: [0]
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#user_servicefromid').datepicker('setEndDate', minDate);
    });
});
</script>

<body>
<form name="form1" method="post" action="coupon_process.php" onsubmit="return validate(this)">
    <input type="hidden" name="uid" value="<?= htmlspecialchars($uid, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="mode" value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>">

    <table cellspacing="0" cellpadding="0" width="100%" border="0">
        <tbody>
            <tr height="25">
                <td width="700" height="30" colspan="2" style="padding:3px 0 0 0" align="left" bgcolor="#EEEEEE">
                    &nbsp;<b>Coupon Information</b>
                </td>
            </tr>
            <tr><td height="20"></td></tr>
            <tr>
                <td class="menu">
                    <table cellspacing="1" cellpadding="0" width="100%" border="0">
                        <tbody>
                            <tr><td height="1" colspan="2" background="/admin/images/dot_line01.gif"></td></tr>
                            <tr>
                                <td width="170" height="30" style="padding:3px 0 0 0" align="left" bgcolor="#F7F7F7">
                                    <img src="/admin/images/point.gif"> <b>Code of Coupon</b>
                                </td>
                                <td bgcolor="white" valign="middle" style="padding: 0 0 0 10px;">
                                    <input type="text" name="pcode" required value="<?= htmlspecialchars($pcode, ENT_QUOTES, 'UTF-8') ?>" size="20" maxlength="10" class="input">
                                </td>
                            </tr>
                            <tr><td height="1" colspan="2" background="/admin/images/dot_line01.gif"></td></tr>
                            <tr>
                                <td width="170" height="30" style="padding:3px 0 0 0" align="left" bgcolor="#F7F7F7">
                                    <img src="/admin/images/point.gif"> <b>Discount Rate</b>
                                </td>
                                <td bgcolor="white" valign="middle" style="padding: 0 0 0 10px;">
                                    <input type="text" name="drate" size="10" required maxlength="10" class="input" value="<?= htmlspecialchars($drate, ENT_QUOTES, 'UTF-8') ?>">&nbsp;(ex: 10% discount enter 0.1)
                                </td>
                            </tr>
                            <tr><td height="1" colspan="2" background="/admin/images/dot_line01.gif"></td></tr>
                            <tr>
                                <td width="170" height="30" style="padding:3px 0 0 0" align="left" bgcolor="#F7F7F7">
                                    <img src="/admin/images/point.gif"> <b>The term of Validity</b>
                                </td>    
                                <td bgcolor="white" valign="middle" style="padding: 0 0 0 10px;">
                                    &nbsp;<b>From</b>
                                    <input type="text" size="10" name="expiredate_from" id="expiredate_fromid" value="<?= htmlspecialchars($expiredate_fromSTR, ENT_QUOTES, 'UTF-8') ?>">

                                    &nbsp;<b>To</b>
                                    <input type="text" size="10" name="expiredate_to" id="expiredate_toid" value="<?= htmlspecialchars($expiredate_toSTR, ENT_QUOTES, 'UTF-8') ?>">
                                </td>
                            </tr>
                            <tr><td height="1" colspan="2" background="/admin/images/dot_line01.gif"></td></tr>
                            <tr>
                                <td width="170" height="30" style="padding:3px 0 0 0" align="left" bgcolor="#F7F7F7">
                                    <img src="/admin/images/point.gif"> <b>Status</b>
                                </td>
                                <td bgcolor="white" valign="middle" style="padding: 0 0 0 10px;">
                                    <input type="radio" name="status" value="Y" <?= ($status === "Y" || $status === "") ? "checked" : "" ?>>Enable
                                    <input type="radio" name="status" value="N" <?= ($status === "N") ? "checked" : "" ?>>Disable
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr><td height="20"></td></tr>
            <tr><td align="center">
                <input type="submit" value="SUBMIT">
                <input type="button" value="CLOSE" onclick="parent.<?= htmlspecialchars($btnSTR, ENT_QUOTES, 'UTF-8') ?>.hide(); return false;">
            </td></tr>
        </tbody>
    </table>
</form>
</body>
</html>
