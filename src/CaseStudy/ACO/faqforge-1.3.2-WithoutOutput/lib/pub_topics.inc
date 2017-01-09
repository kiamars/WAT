<?php


//$output .= ("<center><big>$defaultwebTitle</big><br>\n");

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
    "<th width=\"200\">HOWTO's</th>\n" .
    "</tr>\n");*/

$q = "SELECT * FROM Faq WHERE parent_id = 0 ORDER BY list_order";
$result0 = mysql_query($q, $dbLink);


$this->AddBranchesID(__FILE__ . ",4");
while ($this->UPEL(__FILE__ . ",4", 2, BD::BoolCondition($faq0 = mysql_fetch_array($result0)))) //while ( $faq0 = mysql_fetch_array ($result0) )
{

    $this->AddBranchesID(__FILE__ . ",5");
    if ($this->UPEL(__FILE__ . ",5", 2, BD::Equal($faq0["title"], "")))
        //if ( $faq0["title"] == "" )
        $faq0["title"] = "???";

    $idList[] = $faq0["id"];

    /*$output .= ("<tr bgcolor=\"#eeeeee\">\n" .
        "<td width=\"200\">\n" .
        "<small><a href=\"./?context=View+Document&helpContext=" .
        urlencode($faq0["context"]) . "\">" .
        $faq0["title"] .
        "</a></td>\n" .
        "</td></tr>\n");*/

    $this->AddReachedAbstractUrlInEachRun($this->FilePath, "GET", array("context" => "View Document", "helpContext" => $faq0["context"]), null);

    $q = "SELECT * from Faq WHERE parent_id = " . $faq0["id"] . " ";
    $q .= "ORDER BY list_order";
    $result1 = mysql_query($q, $dbLink);

    $this->AddBranchesID(__FILE__ . ",6");
    while ($this->UPEL(__FILE__ . ",6", 2, BD::BoolCondition($faq1 = mysql_fetch_array($result1)))) // while ( $faq1 = mysql_fetch_array ($result1) )
    {

        $this->AddBranchesID(__FILE__ . ",7");
        if ($this->UPEL(__FILE__ . ",7", 2, BD::Equal($faq1["title"], "")))
            //if ( $faq1["title"] == "" )
            $faq1["title"] = "???";

        $idList[] = $faq1["id"];

       /* $output .= ("<tr bgcolor=\"#eeeeee\">\n" .
            "<td width=\"200\">\n" .
            //"&nbsp;&nbsp;&nbsp;&nbsp;" .
            " -> " .
            "<small><a href=\"./?context=View+Document&helpContext=" .
            urlencode($faq1["context"]) . "\">" .
            $faq1["title"] .
            "</a></td>\n" .
            "</td></tr>\n");*/
        $this->AddReachedAbstractUrlInEachRun($this->FilePath, "GET", array("context" => "View Document", "helpContext" => $faq1["context"]), null);

        $q = "SELECT * from Faq WHERE parent_id = " . $faq1["id"] . " ";
        $q .= "ORDER BY list_order";
        $result2 = mysql_query($q, $dbLink);

        $this->AddBranchesID(__FILE__ . ",8");
        while ($this->UPEL(__FILE__ . ",8", 2, BD::BoolCondition($faq2 = mysql_fetch_array($result2)))) // while ( $faq2 = mysql_fetch_array ($result2) )
        {
            $this->AddBranchesID(__FILE__ . ",9");
            if ($this->UPEL(__FILE__ . ",9", 2, BD::Equal($faq2["title"], "")))
                //if ($faq2["title"] == "")
                $faq2["title"] = "???";

            $idList[] = $faq2["id"];

          /*  $output .= ("<tr bgcolor=\"#eeeeee\">\n" .
                "<td width=\"200\">\n" .
                //"&nbsp;&nbsp;&nbsp;&nbsp;" .
                " ---> " .
                "<small><a href=\"./?context=View+Document&helpContext=" .
                urlencode($faq1["context"]) . "\">" .
                $faq2["title"] .
                "</a></td>\n" .
                "</td></tr>\n");*/
            $this->AddReachedAbstractUrlInEachRun($this->FilePath, "GET", array("context" => "View Document", "helpContext" => $faq1["context"]), null);
        }
    }
}
/*$output .= ("</table>\n\n" .
    "</td></tr>\n" .
    "</table>\n\n" .
    "<br>" .
    "<a href=\"http://www.mysql.com\" target=\"_blank\"><img border=\"0\" src=\"/images/mysql.png\" alt=\"Poweder by MySQL\"></a>" .
    "&nbsp;&nbsp;<a href=\"http://www.php.net\" target=\"_blank\"><img border=\"0\" src=\"/images/php.png\" alt=\"Powered by PHP\"></a>" .
    "\n" .
    "</body>\n");*/

