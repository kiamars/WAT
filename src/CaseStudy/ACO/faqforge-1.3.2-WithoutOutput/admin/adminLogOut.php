<?php
//session_start();

function session_clear()
{
// if session exists, unregister all variables that exist and destroy session
    $exists = "no";
    $session_array = explode(";", session_encode());
    // print_r(session_array);
    for ($x = 0; $x < count($session_array); $x++) {
        $name = substr($session_array[$x], 0, strpos($session_array[$x], "|"));
        if (isset($_SESSION[$name])) {
            $_SESSION[$name] = "";
            $exists = "yes";
        }
    }
    if ($exists != "no") {
        session_destroy();
    }
}

require "BaseDirectory.php";
require_once $BASEDIROFPROJECT . "/CaseStudy/PSO/faqforge-1.3.2-WithoutOutput/FilePath.php";
require_once $BASEDIROFPROJECT."/TestDataGenerationAlgorithm/ACO/ACO_PageTDG.php";

class ACO_Admin_LogOut_php extends ACO_PageTDG
{

    public function InitializeInputListType()
    {
        //$helpContext=>string
        //$context:string
        //current:int
        $this->FilePath = FilePath::Admin_LogOut_php;

    }

    public function PageUnderTest()
    {
        $output = "";
        session_clear();

        $this->AddBranchesID(__FILE__ . ",54");
        if ($this->UPEL(__FILE__ . ",54", 2, BD::BoolCondition(!isset($_SESSION[session_name()])))) //        if (!isset($_SESSION[session_name()]))
        {

          //  $output .= "<html><head>";
        //    $output .= "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"5; URL=./\">";
         //   $output .= "</head><body>";
         //   $output .= "<center>";
        //    $output .= "<br><br><h2>Successfully logged out<br>of FaqForge Admin Center</h2>";
         //   $output .= "</center></body></html>";
            $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "GET", array(), null);
        } else {
          //  $output .= "<h1 style=\"color:red;\">NOT Logged Out</h1>";
           // $output .= "Please contact the system administrator.";
        }

    }
}
