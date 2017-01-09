<?php
//session_start();


require "BaseDirectory.php";
require_once $BASEDIROFPROJECT . "/CaseStudy/PSO/faqforge-1.3.2-WithoutOutput/FilePath.php";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/VS/VS_PageTDG.php";

class VS_Admin_LogIn_php extends VS_PageTDG
{

    public function InitializeInputListType()
    {
        $this->RMax=10;
        //$_POST['loginAttempts']:int
        //$formuser:$_POST :stringLogInDiscovery
        //$formpassword = $_POST['formpassword'];

        $this->FilePath = FilePath::Admin_LogIn_php;
        $this->InputsType["formuser"] = new ITOS(0, "formuser", "admin",null,array("admin"));
        $this->InputsType["formpassword"] = new ITOS(1, "formpassword","changeME",null,array("changeME"));
        $this->InputsType["loginAttempts"] = new ITOI(2, "loginAttempts",null,-100, 100);
    }

    public function PageUnderTest()
    {
        $output = "";
        require_once("admin-config.php");


// begin SECURITY - DO NOT CHANGE!
// initialize or retrieve the current values for the login variables

        $loginAttempts="0";
        $this->AddBranchesID(__FILE__ . ",46");
        if ($this->UPEL(__FILE__ . ",46", 2, BD::BoolCondition(!isset($_POST['loginAttempts'])))) //  $loginAttempts = !isset($_POST['loginAttempts']) ? 1 : $_POST['loginAttempts'];
        {
            $loginAttempts = 1;

        } else {
            $loginAttempts = $_POST['loginAttempts'];
        }


        $formuser=NUll;
        $this->AddBranchesID(__FILE__ . ",47");
        // $formuser = !isset($_POST['formuser']) ? NULL : $_POST['formuser'];
        if ($this->UPEL(__FILE__ . ",47", 2, BD::BoolCondition(!isset($_POST['formuser'])))) {
            $formuser = NULL;

        } else {
            $formuser = $_POST['formuser'];
        }

        $formpassword="";
        $this->AddBranchesID(__FILE__ . ",48");
        //$formpassword = !isset($_POST['formpassword']) ? NULL : $_POST['formpassword'];
        if ($this->UPEL(__FILE__ . ",48", 2, BD::BoolCondition(!isset($_POST['formuser'])))) {
            $formpassword = NULL;

        } else {
            $formpassword = $_POST['formpassword'];
        }


        $this->AddBranchesID(__FILE__ . ",49");
        if ($this->UPEL(__FILE__ . ",49", 2, BD::LOpOR(BD::NotEqual($formuser, ADMINUSER), BD::NotEqual($formpassword, ADMINPASSWORD)))) // if (($formuser != ADMINUSER) || ($formpassword != ADMINPASSWORD))
        {
            $this->AddBranchesID(__FILE__ . ",50");
            if ($this->UPEL(__FILE__ . ",50", 2, BD::Equal($loginAttempts,0)))
            //if ($loginAttempts == 0)
            { /* 3 strikes and they're out */
                $_POST['loginAttempts'] = 1;
                include("adminLoginForm.php");
                return;
               // exit;
            } else {
                $this->AddBranchesID(__FILE__ . ",51");
                if ($this->UPEL(__FILE__ . ",51", 2, BD::GreaterThan($loginAttempts,3)))
                //if ($loginAttempts >= 3)
                {
                    $output.= "<blink><p align='center' style=\"font-weight:bold;font-size:170px;color:red;font-family:sans-serif;\">Log In<br>Failed.</p></blink>";
                    //exit;
                    return;
                } else {
                    include("adminLoginForm.php");
                    //exit;
                    return;
                }
            }
        }
        /* test for valid username and password
           if valid then initialize the session
            register the username and password variables
            and redirect to the ADMINHOME page
        */

        $this->AddBranchesID(__FILE__ . ",52");
        if ($this->UPEL(__FILE__ . ",52", 2, BD::LOpAND(BD::Equal($formuser ,ADMINUSER),BD::Equal($formpassword,ADMINPASSWORD))))
//        if (($formuser == ADMINUSER) && ($formpassword == ADMINPASSWORD))
        {    // test for valid username and password

            $_SESSION['adminUser'] = ADMINUSER;
            $_SESSION['adminPassword'] = ADMINPASSWORD;
            //$SID = session_id();

            //$adminHome = ADMINHOME;
            //include($adminHome);
           // header("Location: index.php");
            $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "GET", array(), null);

        }
       // $this->OutPutInEachRun[] = $output;
    }

    public function LoginDiscovery()
    {
        if(count($this->CurrentInterface)>0)
            if(mt_rand() / mt_getrandmax()>.3) {
            $CorrectUserNameAndPassword = array();
            $CorrectUserNameAndPassword["formuser"] = "admin";
            $CorrectUserNameAndPassword["formpassword"] = "changeME";
            $this->SetInputValueInCurrentSolution($CorrectUserNameAndPassword);
        }
    }
}
