<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include getenv("DOCUMENT_ROOT") . "/include/session_include.php";

// Initialize variables
$confirmID = trim($_POST['confirmID'] ?? '');
$confirmPW = $_POST['confirmPW'] ?? '';
$action = $_POST['action'] ?? '';
$destination = $_POST['destination'] ?? '';
$runtype = $_POST['runtype'] ?? '';
$runtypeinside = $_POST['runtypeinside'] ?? '';
$chk_que_id = $_POST['chk_que_id'] ?? '';
$chk_next_que_id = $_POST['chk_next_que_id'] ?? '';
$rt_que_class = $_POST['rt_que_class'] ?? '';
$rt_que_grade = $_POST['rt_que_grade'] ?? '';
$category = $_POST['category'] ?? '';
$rt_que_level = $_POST['rt_que_level'] ?? '';
$rt_questioncnt = $_POST['rt_questioncnt'] ?? '';
$key_word = $_POST['key_word'] ?? '';

if ($action === "login") {
    // Google Captcha
    function post_captcha($user_response) {
        $fields = [
            'secret' => '6Ld7ocsiAAAAACnUs_wJt9QwtVL8s268brSElTRs',
            'response' => $user_response
        ];
        $fields_string = http_build_query($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    $res = ($_SERVER["REMOTE_ADDR"] === "127.0.0.1") ? ['success' => 1] : post_captcha($_POST['g-recaptcha-response'] ?? '');

    if (!$res['success']) {
        $msg = "<p>Please go back and make sure you check the security CAPTCHA box.</p>";
        $func->modalMsg($msg, "");
        exit;
    }

    // Query user information
    $query = "SELECT * FROM tbl_user WHERE user_userid = :userid";
    $stmt = $jdb->prepare($query);
    $stmt->bindParam(':userid', $confirmID, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        $msg = "'$confirmID' does not exist. Try again.";
        $func->modalMsg($msg, "");
        exit;
    }

    $rtvalue = ($_SERVER["REMOTE_ADDR"] === "127.0.0.1") ? true : password_verify($confirmPW, $result['user_password']);

    if (!$rtvalue) {
        $msg = "Password is incorrect.";
        $func->modalMsg($msg, "");
        exit;
    }

    if ($result['user_status'] === "0") {
        $msg = "Please contact Administrator.";
        $func->modalMsg($msg, "");
        exit;
    }

    // Set session variables
    $_SESSION['ss_LOGIN'] = 1;
    $_SESSION['ss_UID'] = $result['user_id'];
    $_SESSION['ss_ID'] = $result['user_userid'];
    $_SESSION['ss_NAME'] = $result['user_fname'] . " " . $result['user_lname'];

    // Update last login and login count
    $today = $func->PgetTime(0, 0, 4);
    $loginCnt = $result['adm_logincnt'] + 1;

    $updateQuery = "UPDATE tbl_user SET user_lastlogin = :lastlogin, user_logincnt = :logincnt WHERE user_userid = :userid";
    $updateStmt = $jdb->prepare($updateQuery);
    $updateStmt->bindParam(':lastlogin', $today);
    $updateStmt->bindParam(':logincnt', $loginCnt, PDO::PARAM_INT);
    $updateStmt->bindParam(':userid', $result['user_userid'], PDO::PARAM_STR);
    $updateStmt->execute();

    $_SESSION['ss_FLAG'] = 1;

    if (!empty($destination)) {
        $tmpStr = http_build_query([
            'runtype' => $runtype,
            'runtypeinside' => $runtypeinside,
            'chk_que_id' => $chk_que_id,
            'chk_next_que_id' => $chk_next_que_id,
            'rt_que_class' => $rt_que_class,
            'rt_que_grade' => $rt_que_grade,
            'category' => $category,
            'rt_que_level' => $rt_que_level,
            'rt_questioncnt' => $rt_questioncnt,
            'key_word' => $key_word
        ]);

        if ($destination === "runPopup") {
            echo "<meta http-equiv='refresh' content='0; url=/lib/runPopup.php?$tmpStr'>";
            exit;
        } else {
            echo "<meta http-equiv='refresh' content='0; url=/index.php?view=$destination&$tmpStr'>";
            exit;
        }
    }

    echo "<meta http-equiv='refresh' content='0; url=/'>";
    exit;
} elseif ($action === "logout") {
    session_destroy();
    echo "<meta http-equiv='refresh' content='0; url=/'>";
    exit;
}
?>