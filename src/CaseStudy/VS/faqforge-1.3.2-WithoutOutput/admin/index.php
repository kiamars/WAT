<?php
require "BaseDirectory.php";
require_once $BASEDIROFPROJECT . "/CaseStudy/PSO/faqforge-1.3.2-WithoutOutput/FilePath.php";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/VS/VS_PageTDG.php";

class VS_Admin_Index_php extends VS_PageTDG
{

    public function InitializeInputListType()
    {
        $this->FilePath = FilePath::Admin_Index_php;
        $this->InputsType["context"] = new ITOS(0, "context", "");
        $this->InputsType["action"] = new ITOS(1, "action", "");

        $this->InputsType["id"] = new ITOI(2, "id",null, -100, 100);
        $this->InputsType["newParent"] = new ITOI(3, "newParent",null, -100, 100);
        $this->InputsType["pageId"] = new ITOI(4, "pageId",null, -100, 100);
        $this->InputsType["topicParent"] = new ITOI(5, "topicParent",null, -100, 100);
        $this->InputsType["topicOrder"] = new ITOI(6, "topicOrder",null, -1000, 1000);
        $this->InputsType["page_num"] = new ITOI(7, "page_num",null, -100, 100);
        $this->InputsType["faqId"] = new ITOI(8, "faqId",null, -100, 100);
        $this->InputsType["pageNum"] = new ITOI(9, "pageNum",null, -100, 100);


        $this->InputsType["newTitle"] = new ITOS(10, "newTitle", "");
        $this->InputsType["newContext"] = new ITOS(11, "newContext", "");
        $this->InputsType["newOrder"] = new ITOS(12, "newOrder", "");
        $this->InputsType["topicTitle"] = new ITOS(13, "topicTitle", "");
        $this->InputsType["topicContext"] = new ITOS(14, "topicContext", "");
        $this->InputsType["topicPublish"] = new ITOS(15, "topicPublish", "");
        $this->InputsType["pageTitle"] = new ITOS(16, "pageTitle", "");
        $this->InputsType["faqText"] = new ITOS(17, "faqText", "");

        $this->InputsType["helpContext"] = new ITOS(18, "helpContext", "");
        $this->InputsType["current"] = new ITOI(19, "current",null, -100, 100);

        /*
        id:int
        context:str
        newTitle :str
        newParent  :int
        newContext :str
        newOrder:str
        pageId :inttopic
        Title :str
        topicContext:str
        topicParent :int
        topicOrder :int
        topicPublish:str  //$topicPublish = ($topicPublish == "on") ? "y" : "n";
        page_num::int
        pageTitle:str
        faqId:int
        pageNum:int
        faqText:str*/
    }

    public function PageUnderTest()
    {
        $output = "";
        $BASEDIROFPROJECT = 'C:\xampp\htdocs\GenerateTestSequenceForWebApplication\CaseStudy\VS\faqforge-1.3.2-WithoutOutput';
        $libPath = $BASEDIROFPROJECT . "/lib/";

        require($libPath . "faqforge-config.inc");

        $exit = false;
          require("adminOnly.php");
        if (!$exit) {


            require_once($libPath . "functions.inc");


            $context = "";
            if (isset($_GET["context"]))
                $context = $_GET["context"];
            if (isset($_POST["context"]))
                $context = $_POST["context"];

            $this->AddBranchesID(__FILE__ . ",29");
            if ($this->UPEL(__FILE__ . ",29", 2, BD::BoolCondition(!$context))) //if (!$context)
            {
                $context = "Topics List";
            }

            $title = "FaqForge - $context";

            $dbLink = mysql_connect($dbServer, $dbUser, $dbPass);
            mysql_select_db($dbName);


            $action = "";
            if (isset($_GET["action"]))
                $action = $_GET["action"];
            if (isset($_POST["action"]))
                $action = $_POST["action"];

            $this->AddBranchesID(__FILE__ . ",30");
            $this->AddBranchesID(__FILE__ . ",31");
            $this->AddBranchesID(__FILE__ . ",32");
            $this->AddBranchesID(__FILE__ . ",33");
            $this->AddBranchesID(__FILE__ . ",34");
            $this->AddBranchesID(__FILE__ . ",35");
            $this->AddBranchesID(__FILE__ . ",36");
            $this->AddBranchesID(__FILE__ . ",37");
            $this->AddBranchesID(__FILE__ . ",38");
            $this->AddBranchesID(__FILE__ . ",39");
            if ($this->UPEL(__FILE__ . ",30", 2, BD::Identical($action, "deleteTopic"))) {
                $id = "";
                if (isset($_GET["id"]))
                    $id = $_GET["id"];

                $message = delete_topic_test($id, $dbLink);
            } else if ($this->UPEL(__FILE__ . ",31", 2, BD::Identical($action, "DELETETopic"))) {
                $id = "";
                if (isset($_GET["id"]))
                    $id = $_GET["id"];

                delete_topic($id, $dbLink);
            } else if ($this->UPEL(__FILE__ . ",32", 2, BD::Identical($action, "addNewTopic"))) {
                $newTitle = "";
                if (isset($_POST["newTitle"]))
                    $newTitle = $_POST["newTitle"];

                $newParent = 0;
                if (isset($_POST["newParent"]))
                    $newParent = $_POST["newParent"];

                $newContext = "";
                if (isset($_POST["newContext"]))
                    $newContext = $_POST["newContext"];

                $newOrder = "";
                if (isset($_POST["newOrder"]))
                    $newOrder = $_POST["newOrder"];

                add_new_topic($newTitle, $newParent, $newContext, $newOrder, $dbLink);
                //add_new_topic ( "newTitle1", 14, "newContext1",100, $dbLink );

            } else if ($this->UPEL(__FILE__ . ",33", 2, BD::Identical($action, "commit"))) {
                $id = "";
                if (isset($_GET["id"]))
                    $id = $_GET["id"];

                $pageId = "";
                if (isset($_GET["pageId"]))
                    $pageId = $_GET["pageId"];

                $faqText = "";
                if (isset($_GET["faqText"]))
                    $faqText = $_GET["faqText"];

                update_page(stripslashes($faqText), $pageId, $id, $dbLink);

            } else if ($this->UPEL(__FILE__ . ",34", 2, BD::Identical($action, "Update Topic"))) {
                $topicTitle = "";
                if (isset($_POST["topicTitle"]))
                    $topicTitle = $_POST["topicTitle"];

                $topicContext = "";
                if (isset($_POST["topicContext"]))
                    $topicContext = $_POST["topicContext"];

                $topicParent = "";
                if (isset($_POST["topicParent"]))
                    $topicParent = $_POST["topicParent"];

                $topicOrder = "";
                if (isset($_POST["topicOrder"]))
                    $topicOrder = $_POST["topicOrder"];


                $topicPublish = "";
                if (isset($_POST["topicPublish"]))
                    $topicPublish = $_POST["topicPublish"];
                $topicPublish = ($topicPublish == "on") ? "y" : "n";

                $id = "";
                if (isset($_POST["id"]))
                    $id = $_POST["id"];

                update_topic($topicTitle, $topicContext, $topicParent,
                    $topicOrder, $topicPublish, $id, $dbLink);
            } else if ($this->UPEL(__FILE__ . ",35", 2, BD::Identical($action, "deletePage"))) {
                $id = "";
                if (isset($_GET["id"]))
                    $id = $_GET["id"];

                $page_num = "";
                if (isset($_GET["page_num"]))
                    $page_num = $_GET["page_num"];

                $message = delete_page_test($page_num, $id, $dbLink);

            } else if ($this->UPEL(__FILE__ . ",36", 2, BD::Identical($action, "DELETEPage"))) {
                $id = "";
                if (isset($_GET["id"]))
                    $id = $_GET["id"];

                $page_num = "";
                if (isset($_GET["page_num"]))
                    $page_num = $_GET["page_num"];

                delete_page($id, $page_num, $dbLink);

                mysql_close($dbLink);
                //header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?context=Edit+Page&id=$id");
                //exit;
                $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "GET", array("context" => "Edit Page", "id" => $id), null);
                return;
            } else if ($this->UPEL(__FILE__ . ",37", 2, BD::Identical($action, "addPage"))) {
                $id = "";
                if (isset($_GET["id"]))
                    $id = $_GET["id"];
                add_new_page($id, $dbLink);

                mysql_close($dbLink);
                //header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] ."?context=Edit+Page&id=$id");
                //exit;
                $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "GET", array("context" => "Edit Page", "id" => $id), null);
                return;
            } else if ($this->UPEL(__FILE__ . ",38", 2, BD::Identical($action, "moveUp"))) {
                $id = "";
                if (isset($_GET["id"]))
                    $id = $_GET["id"];

                $page_num = "";
                if (isset($_GET["page_num"]))
                    $page_num = $_GET["page_num"];

                swap_page_position($page_num, $page_num - 1, $id, $dbLink);

                mysql_close($dbLink);
                //header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?context=Edit+Page&id=$id#" . $newNum);
                //exit;
                $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "GET", array("context" => "Edit Page", "id" => $id), null);
                return;

            } else if ($this->UPEL(__FILE__ . ",39", 2, BD::Identical($action, "moveDown"))) {
                $id = "";
                if (isset($_GET["id"]))
                    $id = $_GET["id"];

                $page_num = "";
                if (isset($_GET["page_num"]))
                    $page_num = $_GET["page_num"];


                swap_page_position($page_num, $page_num++, $id, $dbLink);

                mysql_close($dbLink);
                // header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?context=Edit+Page&id=$id#" . ($newNum + 1));
                // exit;
                $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "GET", array("context" => "Edit Page", "id" => $id), null);
                return;

            }

            /*     switch ($action) {
                     case "deleteTopic": {

                         $id = "";
                         if (isset($_GET["id"]))
                             $id = $_GET["id"];

                         $message = delete_topic_test($id, $dbLink);
                         break;
                     }
                     case "DELETETopic": {

                         $id = "";
                         if (isset($_GET["id"]))
                             $id = $_GET["id"];

                         delete_topic($id, $dbLink);
                         break;
                     }
                     case "addNewTopic": {
                         $newTitle = "";
                         if (isset($_POST["newTitle"]))
                             $newTitle = $_POST["newTitle"];

                         $newParent = "";
                         if (isset($_POST["newParent"]))
                             $newParent = $_POST["newParent"];

                         $newContext = "";
                         if (isset($_POST["newContext"]))
                             $newContext = $_POST["newContext"];

                         $newOrder = "";
                         if (isset($_POST["newOrder"]))
                             $newOrder = $_POST["newOrder"];

                         add_new_topic($newTitle, $newParent, $newContext, $newOrder, $dbLink);
                         //add_new_topic ( "newTitle1", 14, "newContext1",100, $dbLink );
                         break;
                     }
                     case "commit": {
                         $id = "";
                         if (isset($_GET["id"]))
                             $id = $_GET["id"];

                         $pageId = "";
                         if (isset($_GET["pageId"]))
                             $pageId = $_GET["pageId"];

                         $faqText = "";
                         if (isset($_GET["faqText"]))
                             $faqText = $_GET["faqText"];

                         update_page(stripslashes($faqText), $pageId, $id, $dbLink);
                         break;
                     }
                     case "Update Topic": {
                         $topicTitle = "";
                         if (isset($_POST["topicTitle"]))
                             $topicTitle = $_POST["topicTitle"];

                         $topicContext = "";
                         if (isset($_POST["topicContext"]))
                             $topicContext = $_POST["topicContext"];

                         $topicParent = "";
                         if (isset($_POST["topicParent"]))
                             $topicParent = $_POST["topicParent"];

                         $topicOrder = "";
                         if (isset($_POST["topicOrder"]))
                             $topicOrder = $_POST["topicOrder"];


                         $topicPublish = "";
                         if (isset($_POST["topicPublish"]))
                             $topicPublish = $_POST["topicPublish"];
                         $topicPublish = ($topicPublish == "on") ? "y" : "n";

                         $id = "";
                         if (isset($_POST["id"]))
                             $id = $_POST["id"];

                         update_topic($topicTitle, $topicContext, $topicParent,
                             $topicOrder, $topicPublish, $id, $dbLink);
                         break;
                     }
                     case "deletePage": {
                         $id = "";
                         if (isset($_GET["id"]))
                             $id = $_GET["id"];

                         $page_num = "";
                         if (isset($_GET["page_num"]))
                             $page_num = $_GET["page_num"];

                         $message = delete_page_test($page_num, $id, $dbLink);
                         break;
                     }
                     case "DELETEPage": {

                         $id = "";
                         if (isset($_GET["id"]))
                             $id = $_GET["id"];

                         $page_num = "";
                         if (isset($_GET["page_num"]))
                             $page_num = $_GET["page_num"];

                         delete_page($id, $page_num, $dbLink);

                         mysql_close($dbLink);
                         header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] .
                             "?context=Edit+Page&id=$id");
                         exit;
                     }
                     case "addPage": {

                         $id = "";
                         if (isset($_GET["id"]))
                             $id = $_GET["id"];
                         add_new_page($id, $dbLink);

                         mysql_close($dbLink);
                         header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] .
                             "?context=Edit+Page&id=$id");
                         exit;
                     }
                     case "moveUp": {
                         $id = "";
                         if (isset($_GET["id"]))
                             $id = $_GET["id"];

                         $page_num = "";
                         if (isset($_GET["page_num"]))
                             $page_num = $_GET["page_num"];

                         swap_page_position($page_num, $page_num - 1, $id, $dbLink);

                         mysql_close($dbLink);
                         header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] .
                             "?context=Edit+Page&id=$id#" . $newNum);
                         exit;
                     }
                     case "moveDown": {
                         $id = "";
                         if (isset($_GET["id"]))
                             $id = $_GET["id"];

                         $page_num = "";
                         if (isset($_GET["page_num"]))
                             $page_num = $_GET["page_num"];


                         swap_page_position($page_num, $page_num++, $id, $dbLink);

                         mysql_close($dbLink);
                         header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] .
                             "?context=Edit+Page&id=$id#" . ($newNum + 1));
                         exit;
                     }
                 }*/


            require($libPath . "header.inc");

            $this->AddBranchesID(__FILE__ . ",40");
            $this->AddBranchesID(__FILE__ . ",41");
            $this->AddBranchesID(__FILE__ . ",42");
            $this->AddBranchesID(__FILE__ . ",43");
            if ($this->UPEL(__FILE__ . ",40", 2, BD::Identical($context, "Topics List"))) {
                require($libPath . "topics.inc");

            } else if ($this->UPEL(__FILE__ . ",41", 2, BD::Identical($context, "Edit Page"))) {
                require($libPath . "edit-page.inc");
            } else if ($this->UPEL(__FILE__ . ",42", 2, BD::Identical($context, "Preview Page"))) {
                require($libPath . "preview-page.inc");
            } else if ($this->UPEL(__FILE__ . ",43", 2, BD::Identical($context, "View Document"))) {
                include($libPath . "view-doc.inc");
            } else {
                require($libPath . "topics.inc");
            }

            /*
            switch ($context) {
                case "Topics List": {
                    require($libPath . "topics.inc");
                    break;
                }

                case "Edit Page": {
                    require($libPath . "edit-page.inc");
                    break;
                }
                case "Preview Page": {
                    require($libPath . "preview-page.inc");
                    break;
                }
                case "View Document": {
                    include($libPath . "view-doc.inc");
                    break;
                }
                default: {
                    require($libPath . "topics.inc");
                    break;
                }
            }
    */
            mysql_close($dbLink);

        }
        // $this->OutPutInEachRun[] = $output;
    }
}
