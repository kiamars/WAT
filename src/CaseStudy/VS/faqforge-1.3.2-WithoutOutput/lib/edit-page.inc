<?php

$faqText = "";
if (isset($_GET["faqText"]))
    $faqText = $_GET["faqText"];

$id = 0;
if (isset($_GET["id"]))
    $id = $_GET["id"];
if (isset($_POST["id"]))
    $id = $_POST["id"];

$pageId = 0;
if (isset($_GET["pageId"]))
    $pageId = $_GET["pageId"];


$faqText = stripslashes(stripslashes($faqText));

$idList = array();

$q = "SELECT id FROM Faq WHERE parent_id = 0 ORDER BY id";
$result0 = mysql_query($q, $dbLink);

$this->AddBranchesID(__FILE__ . ",55");
while ($this->UPEL(__FILE__ . ",55", 2, BD::BoolCondition($pidList0 = mysql_fetch_array($result0)))) //while ( $pidList0 = mysql_fetch_array($result0) )
{

    $this->AddBranchesID(__FILE__ . ",56");
    if ($this->UPEL(__FILE__ . ",56", 2, BD::LOpAND(BD::BoolCondition(!array_intersect($idList, $pidList0)), BD::BoolCondition($pidList0["id"], $id)))) //if ( ( ! array_intersect ($idList, $pidList0) ) & ( $pidList0["id"] != $id ) )
    {
        $idList[] = $pidList0["id"];

        /* The nested query and functionality allow for three tier heirarchy...*/

        $q = "SELECT id FROM Faq where parent_id = " . $pidList0["id"] . " ";
        $q .= "ORDER BY id";
        $result1 = mysql_query($q, $dbLink);

        $this->AddBranchesID(__FILE__ . ",57");
        while ($this->UPEL(__FILE__ . ",57", 2, BD::BoolCondition($pidList1 = mysql_fetch_array($result1)))) //while ( $pidList1 = mysql_fetch_array($result1) )
        {
            $this->AddBranchesID(__FILE__ . ",58");
            if ($this->UPEL(__FILE__ . ",58", 2, BD::LOpAND(BD::BoolCondition(!array_intersect($idList, $pidList1)), BD::NotEqual($pidList1["id"], $id)))) //if ((!array_intersect($idList, $pidList1)) & ($pidList1["id"] != $id))
            {
                $idList[] = $pidList1["id"];
            }
        }
    }
}

//$output .= ("<center><big>Section Editor</big><br>\n");

if (isset($message)) {
   /* $output .= ("<br>\n" .
        "<table bgcolor=\"#ff0000\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">\n" .
        "<tr><td>\n" .
        "<table bgcolor=\"#eeeeee\" cellpadding=\"20\" cellspacing=\"0\" border=\"0\">\n" .
        "<tr><td>$message</td></tr>\n" .
        "</table>\n" .
        "</td></tr>\n" .
        "</table>\n\n");*/
}


$q = "SELECT * FROM Faq WHERE id = $id";
$result = mysql_query($q, $dbLink);
$faq = mysql_fetch_array($result);
/*
$output .= ("</table>\n\n" .
    "</td></tr>\n" .
    "</table>\n\n" .
    "<br><br>\n" .
    "<center>\n\n" .
    "<table bgcolor=\"#000000\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n" .
    "<tr><td>\n\n" .
    "<table bgcolor=\"#eeeeee\" cellpadding=\"8\" cellspacing=\"0\" border=\"0\">\n" .
    "<tr><td colspan=\"2\" align=\"center\">\n" .
    "<strong>Update Topic:<strong></td></tr>\n" .
    "<form action=\"./\" method=post>\n" .
    "<input type=\"hidden\" name=\"context\" value=\"$context\">\n" .
    "<input type=\"hidden\" name=\"id\" value=\"$id\">\n" .
    "<tr><td align=\"right\"><small>Title:</td>\n" .
    "<td><small>" .
    "<input type=\"text\" name=\"topicTitle\" value=\"" . $faq["title"] .
    "\" size=\"24\" maxlength=\"32\"></td></tr>\n" .
    "<tr><td align=\"right\"><small>Context:</td>\n" .
    "<td><small>" .
    "<input type=\"text\" name=\"topicContext\" value=\"" . $faq["context"] .
    "\" size=\"24\" maxlength=\"32\"></td></tr>\n" .
    "<tr><td align=right><small>Parent:</td>\n" .
    "<td><small>" .
    "<select name=\"topicParent\">\n" .
    "<option value=\"0\">Top Level (id=0)</option>\n");*/

$selectedOption=0;
sort($idList);


$this->AddBranchesID(__FILE__ . ",59");

for ($i = 0; $this->UPEL(__FILE__ . ",59", 2, BD::LessThan($i, count($idList))); $i++) //for ($i = 0; $i < count($idList); $i++)
{
    $q = "SELECT title FROM Faq WHERE id = " . $idList[$i];
    $titleResult = mysql_query($q, $dbLink);
    $row = mysql_fetch_row($titleResult);

    $this->AddDefaultValueTo("topicParent",$idList[$i]);
  //  $output .= ("<option value=\"" . $idList[$i] . "\" ");


    $this->AddBranchesID(__FILE__ . ",60");
    if ($this->UPEL(__FILE__ . ",60", 2, BD::Equal($idList[$i], $faq["parent_id"])))
        //if ($idList[$i] == $faq["parent_id"])
    {
       // $output .= ("selected ");
        $selectedOption=$idList[$i];
    }
    //$output .= (">" . $row[0] . " (id=" . $idList[$i] . ")</option>\n");
}

/*$output .= ("</select>\n" .
    "</td></tr>\n" .
    "<tr><td align=\"right\"><small>List Order:</td>\n" .
    "<td><small>" .
    "<input type=\"text\" name=\"topicOrder\" value=\"" . $faq["list_order"] .
    "\" size=\"5\" maxlength=\"5\">" .
    "</td></tr>\n" .
    "<tr><td align=\"right\"><small>Publish:</td>\n" .
    "<td><small><input type=\"checkbox\" name=\"topicPublish\"");*/

$this->AddBranchesID(__FILE__ . ",61");
if ($this->UPEL(__FILE__ . ",61", 2, BD::Equal($faq["publish"], "y")))
//if ($faq["publish"] == "y")
{
   // $output .=  (" checked");
   }

$this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "POST",
    array("context"=>$context,
        "id"=>$id,
        "topicTitle"=> $faq["title"],
        "topicContext"=> $faq["context"],
        "topicParent"=>$selectedOption,
		"topicOrder"=>$faq["list_order"],
        "topicPublish"=>"on",
        "action"=>"Update Topic"
    ), null);


/*$output .= ("></td></tr>\n" .
    "<tr><td colspan=\"2\" align=\"center\">" .
    "<input type=\"submit\" name=\"action\" value=\"Update Topic\">\n" .
    "</td></tr>\n" .
    "</form>\n" .
    "</table>\n\n" .
    "</td></tr>\n" .
    "</table><br><br>\n\n" .
    "<a href=\"./?context=Edit+Page&id=$id&action=addPage\">" .
    "<big>Add Page</big></a><br>\n");*/
$this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "GET", array("context" => "Edit Page","id"=>$id , "action" =>"addPage"), null);

$q = "SELECT * FROM FaqPage WHERE owner_id = $id ORDER BY page_num";
$result = mysql_query($q, $dbLink);
$pageTotal = mysql_num_rows($result);

$this->AddBranchesID(__FILE__ . ",62");
while ($this->UPEL(__FILE__ . ",62", 2, BD::BoolCondition($page = mysql_fetch_array($result)))) //while ($page = mysql_fetch_array($result))
{
   /* $output .= ("<a name=" . $page["page_num"] . ">&nbsp;</a>\n" .
        "<br>\n\n" .
        "<table bgcolor=\"#000000\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n" .
        "<tr><td>\n\n" .
        "<table width=\"540\" bgcolor=\"#eeeeee\" cellpadding=\"4\" " .
        "cellspacing=\"0\" border=\"0\">\n" .
        "<tr><td valign=\"center\"><b>Page " . ($page["page_num"] + 1) .
        " of $pageTotal");*/

    $this->AddBranchesID(__FILE__ . ",63");
    if ($this->UPEL(__FILE__ . ",63", 2, BD::GreaterThan($page["page_num"], 0))) //if ($page["page_num"] > 0)
    {
       /* $output .= ("&nbsp;&nbsp;&nbsp;&nbsp;" .
            "<a href=\"./?context=Edit+Page&action=moveUp&page_num=" .
            $page["page_num"] . "&id=$id\">" .
            "<img src=\"../images/up1.gif\" border=\"0\" alt=\"Move Page Up\" " .
            "align=\"absmiddle\"></a>\n");*/
        $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "GET", array("context" => "Edit Page","action" =>"moveUp","page_num"=>$page["page_num"],"id"=>$id), null);

    } else
       // $output .= ("&nbsp;&nbsp;&nbsp;&nbsp;");


    $this->AddBranchesID(__FILE__ . ",64");
    if ($this->UPEL(__FILE__ . ",64", 2, BD::LessThan($page["page_num"], ($pageTotal - 1)))) // if ($page["page_num"] < ($pageTotal - 1))

    {
      /*  $output .= ("<a href=\"./?context=Edit+Page&action=moveDown&page_num=" .
            $page["page_num"] . "&id=$id\">" .
            "<img src=\"../images/down1.gif\" border=\"0\" alt=\"Move Page Down\" " .
            "align=\"absmiddle\"></a>\n");*/
        $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "GET", array("context" => "Edit Page","action" =>"moveDown","page_num"=>$page["page_num"],"id"=>$id), null);
    }

    /*$output .= ("</b></td>\n" .
        "<td align=\"right\">" .
        "<a href=\"./?context=Edit+Page&action=deletePage&page_num=" .
        $page["page_num"] . "&id=$id\">" .
        "<img src=\"../images/del1.gif\" border=\"0\" alt=\"Delete Page\" " .
        "align=\"absmiddle\"></a>&nbsp;&nbsp;</td>\n" .
        "</tr>\n" .
        "<form action=\"./\" method=\"post\">\n" .
        "<input type=\"hidden\" name=\"context\" value=\"Preview Page\">\n" .
        "<input type=\"hidden\" name=\"pageTitle\" value=\"" . $faq["title"] .
        "\">\n" .
        "<input type=\"hidden\" name=\"faqId\" value=\"" . $faq["id"] . "\">\n" .
        "<input type=\"hidden\" name=\"id\" value=\"" . $page["id"] . "\">\n" .
        "<input type=\"hidden\" name=\"pageNum\" value=\"" .
        $page["page_num"] . "\">\n" .
        "<tr><td colspan=\"2\">\n" .
        "<small>\n" .
        "<textarea name=\"faqText\" wrap=\"virtual\" cols=\"80\" rows=\"20\">");*/
    $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "GET", array("context" => "Edit Page","action" =>"deletePage","page_num"=>$page["page_num"],"id"=>$id), null);

    $faqTextVal="";
    $this->AddBranchesID(__FILE__ . ",65");
    if ($this->UPEL(__FILE__ . ",65", 2, BD::LOpAND(BD::BoolCondition($faqText),BD::Equal($page["page_num"],$pageId))))
    //if (($faqText) && ($page["page_num"] == $pageId))
    {
        $faqTextVal=($faqText);
      //  $output .= ($faqText);
    } else {
        $faqTextVal=($page["faqText"]);
      //  $output .= ($page["faqText"]);
    }
    $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "POST",
        array("context"=>"Preview Page",
            "pageTitle"=> $faq["title"],
            "faqId"=>$faq["id"],
            "id"=>$page["id"],
            "pageNum"=>$page["page_num"],
            "faqText"=>$faqTextVal,
            "action"=>"Preview"
        ), null);
  /*  $output .= ("</textarea>\n" .
        "</td></tr>\n" .
        "<tr><td align=\"center\" colspan=\"2\">\n" .
        "<input type=\"submit\" value=\"Preview\" name=action>\n" .
        "</form>\n" .
        "</td></tr>\n" .
        "</table>\n\n" .
        "</td></tr>\n" .
        "</table>\n\n" .
        "<br>\n");*/
}

/*$output .= ("<a href=\"./?context=Edit+Page&id=$id&action=addPage\">" .
    "<big>Add Page</big></a><br><br>\n" .
    "<a href=\"#top\">[ Top ]</a><br>\n" .
    "</center>\n" .
    "</body>\n" .
    "</html>\n");*/
$this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "GET", array("context" => "Edit Page","id"=>$id , "action" =>"addPage",), null);