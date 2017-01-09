<?php


/*$output .= ("<center><big>Topics List</big><br>\n");*/

if (isset($message)) {
   /* $output .= ("<br>\n" .
        "<table bgcolor=\"#ff0000\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">\n" .
        "<tr><td>\n" .
        "<table bgcolor=\"#eeeeee\" cellpadding=\"20\" cellspacing=\"0\" border=\"0\">\n" .
        "<tr><td>$message</td></tr>\n" .
        "</table>\n" .
        "</td></tr>\n" .
        "</table><br>\n\n");*/
}

/*$output .= ("<br>\n\n" .
    "<table bgcolor=\"#000000\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n" .
    "<tr><td>\n\n" .
    "<table bgcolor=\"#eeeeee\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\">\n" .
    "<tr bgcolor=\"#dddddd\">\n" .
    "<th width=\"200\">Title</th>\n" .
    "<th width=\"60\">ID</th>\n" .
    "<th width=\"80\">Parent ID</th>\n" .
    "<th width=\"80\">List Order</th>\n" .
    "<th width=\"60\">Publish</th>\n" .
    "<th width=\"60\">Delete</th>\n" .
    "</tr>\n");*/

$q = "SELECT * FROM Faq WHERE parent_id = 0 ORDER BY list_order";
$result0 = mysql_query($q, $dbLink);

$idList=array();
$this->AddBranchesID(__FILE__ . ",68");
while ($this->UPEL(__FILE__ . ",68", 2, BD::BoolCondition($faq0 = mysql_fetch_array($result0)))) //while ( $faq0 = mysql_fetch_array ($result0) )
{

    $this->AddBranchesID(__FILE__ . ",69");
    if ($this->UPEL(__FILE__ . ",69", 2, BD::Equal($faq0["title"], "")))
        // if ( $faq0["title"] == "" )
        $faq0["title"] = "???";

    $idList[] = $faq0["id"];

  /*  $output .= ("<tr bgcolor=\"#eeeeee\">\n" .
        "<td width=\"200\">\n" .
        "<small><a href=\"./?context=Edit+Page&id=" .
        $faq0["id"] . "\">" .
        $faq0["title"] .
        "</a></td>\n" .
        "<td width=\"60\" align=\"center\">" .
        "<small>" . $faq0["id"] . "</small></td>\n" .
        "<td width=\"80\" align=\"center\"><small>Top</small></td>\n" .
        "<td width=\"80\" align=\"right\"><small>" .
        $faq0["list_order"] . "&nbsp;&nbsp;</td>\n" .
        "<td width=\"60\" align=\"center\"><small>" .
        strtoupper($faq0["publish"]) . "</td>\n" .
        "<td width=\"60\" align=\"center\"><small>" .
        "<a href=\"./?action=deleteTopic&id=" . $faq0["id"] . "\">" .
        "<img src=\"../images/del1.gif\" border=\"0\" alt=\"Delete Topic\" " .
        "align=\"absmiddle\"></a>\n" .
        "</td></tr>\n");*/

    $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php,"GET", array("context"=>"Edit Page","id"=>$faq0["id"]),null);
    $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php,"GET", array("action"=>"deleteTopic","id"=> $faq0["id"]),null);

    $q = "SELECT * from Faq WHERE parent_id = " . $faq0["id"] . " ";
    $q .= "ORDER BY list_order";
    $result1 = mysql_query($q, $dbLink);

    $this->AddBranchesID(__FILE__ . ",70");
    while ($this->UPEL(__FILE__ . ",70", 2, BD::BoolCondition($faq1 = mysql_fetch_array($result1)))) //while ($faq1 = mysql_fetch_array($result1))
    {
        $this->AddBranchesID(__FILE__ . ",71");
        if ($this->UPEL(__FILE__ . ",71", 2, BD::Equal($faq1["title"], "")))
            // if ($faq1["title"] == "")
            $faq1["title"] = "???";

        $idList[] = $faq1["id"];

       /* $output .= ("<tr bgcolor=\"#eeeeee\">\n" .
            "<td width=\"200\">\n" .
            //"&nbsp;&nbsp;&nbsp;&nbsp;" .
            " -> " .
            "<small><a href=\"./?context=Edit+Page&id=" .
            $faq1["id"] . "\">" .
            $faq1["title"] .
            "</a></td>\n" .
            "<td width=\"60\" align=\"center\">" .
            "<small>" . $faq1["id"] . "</small></td>\n" .
            "<td width=\"80\" align=\"center\"><small>" . $faq1["parent_id"] .
            "</small></td>\n" .
            "<td width=\"80\" align=\"right\"><small>" .
            $faq1["list_order"] . "&nbsp;&nbsp;</td>\n" .
            "<td width=\"60\" align=\"center\"><small>" .
            strtoupper($faq1["publish"]) . "</td>\n" .
            "<td width=\"60\" align=\"center\"><small>" .
            "<a href=\"./?action=deleteTopic&id=" . $faq1["id"] . "\">" .
            "<img src=\"../images/del1.gif\" border=\"0\" alt=\"Delete Topic\" " .
            "align=\"absmiddle\"></a>\n" .
            "</td></tr>\n");*/
        $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php,"GET", array("context"=>"Edit Page","id"=>$faq1["id"]),null);
        $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php,"GET", array("action"=>"deleteTopic","id"=> $faq1["id"]),null);


        $q = "SELECT * from Faq WHERE parent_id = " . $faq1["id"] . " ";
        $q .= "ORDER BY list_order";
        $result2 = mysql_query($q, $dbLink);


        $this->AddBranchesID(__FILE__ . ",72");
        while ($this->UPEL(__FILE__ . ",72", 2, BD::BoolCondition($faq2 = mysql_fetch_array($result2)))) //while ($faq2 = mysql_fetch_array($result2))
        {

            $this->AddBranchesID(__FILE__ . ",73");
            if ($this->UPEL(__FILE__ . ",73", 2, BD::Equal($faq2["title"],"")))
            //if ($faq2["title"] == "")
                $faq2["title"] = "???";

            $idList[] = $faq2["id"];

         /*   $output .= ("<tr bgcolor=\"#eeeeee\">\n" .
                "<td width=\"200\">\n" .
                //"&nbsp;&nbsp;&nbsp;&nbsp;" .
                " ---> " .
                "<small><a href=\"./?context=Edit+Page&id=" .
                $faq2["id"] . "\">" .
                $faq2["title"] .
                "</a></td>\n" .
                "<td width=\"60\" align=\"center\">" .
                "<small>" . $faq2["id"] . "</small></td>\n" .
                "<td width=\"80\" align=\"center\"><small>" . $faq2["parent_id"] .
                "</small></td>\n" .
                "<td width=\"80\" align=\"right\"><small>" .
                $faq2["list_order"] . "&nbsp;&nbsp;</td>\n" .
                "<td width=\"60\" align=\"center\"><small>" .
                strtoupper($faq2["publish"]) . "</td>\n" .
                "<td width=\"60\" align=\"center\"><small>" .
                "<a href=\"./?action=deleteTopic&id=" . $faq2["id"] . "\">" .
                "<img src=\"../images/del1.gif\" border=\"0\" alt=\"Delete Topic\" " .
                "align=\"absmiddle\"></a>\n" .
                "</td></tr>\n");*/
            $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php,"GET", array("context"=>"Edit Page","id"=>$faq2["id"]),null);
            $this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php,"GET", array("action"=>"deleteTopic","id"=> $faq2["id"]),null);
        }
    }
}

/*$output .= ("</table>\n\n" .
    "</td></tr>\n" .
    "</table>\n\n" .
    "<br><br>\n" .
    "<center>\n\n" .
    "<table bgcolor=\"#000000\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n" .
    "<tr><td>\n\n" .
    "<table bgcolor=\"#eeeeee\" cellpadding=\"8\" cellspacing=\"0\" border=\"0\">\n" .
    "<tr><td colspan=\"2\" align=\"center\">" .
    "<strong>Add New Topic:<strong></td></tr>\n" .
    "<form action=\"./\" method=\"post\">\n" .
    "<input type=\"hidden\" name=\"context\" value=\"$context\">\n" .
    "<input type=\"hidden\" name=\"action\" value=\"addNewTopic\">\n" .
    "<tr><td align=\"right\"><small>Title:</td>\n" .
    "<td><small>" .
    "<input type=\"text\" name=\"newTitle\" size=\"24\" maxlength=\"32\"></td></tr>\n" .
    "<tr><td align=\"right\"><small>Context:</td>\n" .
    "<td><small>" .
    "<input type=\"text\" name=\"newContext\" size=\"24\" maxlength=\"32\"></td></tr>\n" .
    "<tr><td align=\"right\"><small>Parent:</td>\n" .
    "<td><small>" .
    "<select name=\"newParent\">\n" .
    "<option value=\"0\">Top Level (id=0)</option>\n");*/

$this->AddBranchesID(__FILE__ . ",74");
if ($this->UPEL(__FILE__ . ",74", 2, BD::GreaterThan(count($idList),0)))
//if (count($idList) > 0)
    sort($idList);

$this->AddBranchesID(__FILE__ . ",75");
for ($i = 0; $this->UPEL(__FILE__ . ",75", 2, BD::LessThan($i ,count($idList))); $i++)
//for ($i = 0; $i < count($idList); $i++)
{
    $q = "SELECT title FROM Faq WHERE id = " . $idList[$i];
    $titleResult = mysql_query($q, $dbLink);
    $row = mysql_fetch_row($titleResult);

   /* $output .= ("<option value=\"" . $idList[$i] . "\">" . $row[0] . " (id=" .
        $idList[$i] . ")</option>\n");*/
    $this->AddDefaultValueTo("newParent",$idList[$i] );//add select option to it

}

$this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php,"POST",
    array("context"=>$context,
        "action"=>"addNewTopic",
        "newTitle"=>"",
        "newContext"=>"",
        "newParent"=>0,
        "newOrder"=>1000
        ),null);
/*$output .= ("</select>\n" .
    "</td></tr>\n" .
    "<tr><td align=\"right\"><small>List Order:</td>\n" .
    "<td><small>" .
    "<input type=\"text\" name=\"newOrder\" value=\"10000\" size=\"5\" maxlength=\"5\">" .
    "</td></tr>\n" .
    "<tr><td colspan=\"2\" align=\"center\">" .
    "<input type=\"submit\" value=\"Add Topic\">\n" .
    "</td></tr>\n" .
    "</form>\n" .
    "</table>\n\n" .
    "</td></tr>\n" .
    "</table>\n\n" .
    "</body>\n");*/
