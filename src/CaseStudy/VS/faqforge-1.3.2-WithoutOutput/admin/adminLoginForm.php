<?php
/*$output.=('<!-- doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN"-->
<html> <head>
    <TITLE>FaqForge Admin Center Login</TITLE>
    <link rel=stylesheet type="text/css" href="admin-Login-Only.css">
    <meta name="robots" content="noindex,nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>


<body onload="window.document.adminLoginForm.formuser.focus()">
<!--START OF adminLoginForm.php -->
<blockquote>
    <p><br></p>
    <center>
        <!--  -->
        <form method="post" name="adminLoginForm" action="adminLogin.php">
');
*/

            $this->AddBranchesID(__FILE__ . ",53");
            $loginAttempts = 0;
            if ($this->UPEL(__FILE__ . ",53", 2, BD::BoolCondition(!isset($_POST['loginAttempts'])))) // $loginAttempts = !isset($_POST['loginAttempts']) ? 1 : $_POST['loginAttempts'] + 1;
            {
                $loginAttempts = 1;
            } else {
                $loginAttempts = $_POST['loginAttempts'] + 1;
            }

/*
$output .=('<input type="hidden" name="loginAttempts" value="');
$output .= $loginAttempts;
$output .=('<table border="0" cellpadding="5">
                <tr>
                    <th colspan=2>
                        <center>Login to FaqForge Admin Center</center>
                    </th>
                </tr>

                <tr>
                    <td align="left">Admin :</td>
                    <td>
                        <input type="text" name="formuser" value="');

$output .= $formuser;
$output .=('
                    </td>
                </tr>

                <tr>
                    <td>Admin Password :</td>
                    <td>
                        <input type="password" name="formpassword" value="<?php echo $formpassword; ?>">
                    </td>
                </tr>

                <tr>
                    <td colspan=2>
                        <input class="submit" type="submit" name="submit" value="Login to FaqForge Admin Center">
                      ');*/

                        $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_LogIn_php, "POST", array("loginAttempts"=>$loginAttempts,"formpassword"=>$formpassword,"formuser"=>$formuser), null);
/*$output .=('
                    </td>
                </tr>

                <tr>
                    <td align="center" colspan=2>Back to <a href="../">FaqForge</td>
                    ');*/
                    $this->AddReachedAbstractUrlInEachRun(FilePath::Index_php, "GET", array(), null);
/*$output .=('
                </tr>
            </table>
        </form>

    </center>
</blockquote>
<!--END of adminLoginForm.php -->
</body>
</html>
');*/