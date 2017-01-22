<?php
require "BaseDirectory.php";
require_once $BASEDIROFPROJECT . "/CaseStudy/PSO/faqforge-1.3.2-WithoutOutput/FilePath.php";
require_once $BASEDIROFPROJECT."/TestDataGenerationAlgorithm/AVMTDG/AVM_PageTDG.php";


class AVM_Index_PhP extends AVM_PageTDG
{

    public function InitializeInputListType()
    {
        //$helpContext=>string
        //$context:string
        //current:int
        $this->FilePath = FilePath::Index_php;
        $this->InputsType["context"] = new ITOS(0, "context", "");
        $this->InputsType["helpContext"] = new ITOS(1, "helpContext", "");
        $this->InputsType["current"] = new ITOI(2, "current",null, -100, 100);
    }

    public function PageUnderTest()
    {

        //echo <a herf="index.php">link1</a>
        // $this->AddReachedAbstractUrlInEachRun("Index.php","GET",array(),null);

        if (!isset($output))
            $output = "";

        $BASEDIROFPROJECT = 'C:\xampp\htdocs\GenerateTestSequenceForWebApplication\CaseStudy\AVM\faqforge-1.3.2-WithoutOutput';
        $libPath = $BASEDIROFPROJECT . "/lib/";

        require($libPath . "faqforge-config.inc");
        require_once($libPath . "functions.inc");

        $helpContext = "";

        //for add branches ID
        if (isset($_GET["helpContext"]))
            $helpContext = $_GET["helpContext"];

        $context = "";
        if (isset($_GET["context"]))
            $context = $_GET["context"];


        $this->AddBranchesID(__FILE__ . ",1");
        if ($this->UPEL(__FILE__ . ",1", 2, BD::BoolCondition(!$helpContext))) //if ( ! $helpContext )
        {
            $helpContext = $defaultwebTitle;
        }

        $title = "FaqForge - $helpContext";


        $dbLink = mysql_connect($dbServer, $dbUser, $dbPass);
        mysql_select_db($dbName);

        require($libPath . "pub_header.inc");

        $this->AddBranchesID(__FILE__ . ",2");
        $this->AddBranchesID(__FILE__ . ",3");

        if ($this->UPEL(__FILE__ . ",2", 2, BD::Identical($context, "Topics List"))) {
            require($libPath . "pub_topics.inc");
        } else if ($this->UPEL(__FILE__ . ",3", 2, BD::Identical($context, "View Document"))) {
            require($libPath . "view-doc.inc");
        } else {
            require($libPath . "pub_topics.inc");
        }


        /*switch ( $context )
        {
          case "Topics List":
          {
            require ( $libPath . "pub_topics.inc" );
            break;
          }
          case "View Document":
          {
            require ( $libPath . "view-doc.inc" );
            break;
          }
          default:
          {
            require ( $libPath . "pub_topics.inc" );
            break;
          }
        }*/


        mysql_close($dbLink);

//        $this->OutPutInEachRun[] = $output;
    }
}




/*
$i=new Index();
$i->PageUnderTest();
/*$i->PageUnderTest();
$i->PageUnderTest();

$i->PageUnderTest();
$i->PageUnderTest();
$i->PageUnderTest();
print_r($i->OutPutInEachRun);
*/