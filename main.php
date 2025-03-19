<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
  
<link rel="stylesheet" href="/admin/css/style.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="/admin/css/style.css"> <!-- 스타일 적용 -->
</head>
<body>
    <table border="0" width="100%" align="center" cellpadding="0" bgcolor="#E2E7FA" cellspacing="0">
        <tr>
            <td>
                <br /><br /><br /><br /><br />
                <form name="loginform" method="post" action="/admin/lib/login_process.php" onsubmit="return validateLogin();">
                    <input type="hidden" name="actionPage" value="admin">
                    <input type="hidden" name="action" value="login">
                    
                    <table border="0" align="center" cellpadding="0" bgcolor="#E2E7FA" cellspacing="0">
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td><h2>WELCOME MPC SOLUTION ADMIN SYSTEM</h2></td>
                            <td width="100">&nbsp;</td>
                        </tr>
                    </table>
                    
                    <br /><br /><br />
                    
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td height="5" bgcolor="#bbbbbb"></td>
                        </tr>
                        <tr>
                            <td bgcolor="#0f216d">
                                <br /><br />
                                <div align="center" class="wt">
                                    <h3>System Login</h3>
                                </div>
                                <table border="0" cellspacing="20" cellpadding="0" align="center">
                                    <tr>
                                        <td class="wt">User ID</td>
                                        <td>
                                            <input type="text" name="confirmID" maxlength="40" required value="admin@mpcsolution.com" class="text" tabindex="1" autocomplete="off" style="width:200px; height:23px;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="wt">Password</td>
                                        <td>
                                            <input type="password" name="confirmPW" maxlength="15" required value="1111" class="text" tabindex="2" autocomplete="off" style="width:200px; height:23px;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="wt">&nbsp;</td>
                                        <td align="center">
                                            <button type="submit" id="loginBtn" style="width: 150px; height: 40px; font-size: 16px; cursor: pointer;">LOGIN</button>
                                        </td>
                                    </tr>
                                </table>
                                <br /><br />
                            </td>
                        </tr>
                        <tr>
                            <td height="5" bgcolor="#bbbbbb"></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
    </table>

    <!-- JavaScript: "LOGIN" 버튼을 클릭해야만 로그인 진행 -->
    <script>
        function validateLogin() {
            let userID = document.forms["loginform"]["confirmID"].value;
            let password = document.forms["loginform"]["confirmPW"].value;

            if (userID.trim() === "" || password.trim() === "") {
                alert("❌ User ID와 Password를 입력하세요.");
                return false;
            }

            return true; // 로그인 폼 제출 허용
        }
    </script>
</body>
</html>
