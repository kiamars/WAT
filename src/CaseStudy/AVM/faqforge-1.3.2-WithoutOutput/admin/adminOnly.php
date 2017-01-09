<?php

//session_start();

$this->AddBranchesID(__FILE__ . ",44");
if ($this->UPEL(__FILE__ . ",44", 2, BD::LOpOR(BD::BoolCondition(!isset($_SESSION['adminUser'])), BD::BoolCondition(!isset($_SESSION['adminPassword']))))) //if(   (!isset($_SESSION['adminUser'])) || (!isset($_SESSION['adminPassword'])) )
{
    //header("Location: adminLogin.php");
    $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_LogIn_php, "GET", array(), null);
    //exit;
    $exit = true;
    return;
}


//require("admin-config.php");
/* adminOnly.php
   if the session variables are not set or are incorrect values 
   then present the login screen
*/

$this->AddBranchesID(__FILE__ . ",45");
if ($this->UPEL(__FILE__ . ",45", 2, BD::LOpOR(BD::NotEqual($_SESSION['adminUser'], ADMINUSER), BD::NotEqual($_SESSION['adminPassword'], ADMINPASSWORD)))) //if (($_SESSION['adminUser'] != ADMINUSER) || ($_SESSION['adminPassword'] != ADMINPASSWORD))
{
    // header("Location: adminLogin.php");
    $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_LogIn_php, "GET", array(), null);
    // exit;
    $exit = true;
    return;
} else {

  /*  $output .= ('
    <table>
        <tr>
            <td width=32><a href="adminLogOut.php"><img src="appimage/icons/icon_logout.gif" width="32" height="32"
                                                        border=0 alt="Logout"></a></td>
            <td width=32><a href="');

    $output .= ADMINHOME;
    $output .= ('"><img src="appimage/icons/icon_admin.gif" width="32"
                                                                 height="32" border=0 alt="Admin"></a></td>
            ');*/


    $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_LogOut_php, "GET", array(), null);
    $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "GET", array(), null);
  /*  $output .= ('
    </
    tr >
    </table >');*/
}
