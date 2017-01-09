<?php


$helpContext = "";
if (isset($_GET["helpContext"])) {
    $helpContext = $_GET["helpContext"];
}
$current = 0;
if (isset($_GET["current"])) {
    $current = $_GET["current"];
}

$this->AddBranchesID(__FILE__ . ",10");
if ($this->UPEL(__FILE__ . ",10", 2, BD::BoolCondition(!$helpContext)))
//if ( ! $helpContext )
    $helpContext = $defaultTopic;


$padding = 2;
/*$output .= ("</center><br><br>\n" .
    "<table cellpadding=\"4\" cellspacing=\"0\" border=\"0\">\n" .
    "<tr><td width=\"160\" valign=\"top\">\n" .
    "\n\n<table cellpadding=$padding cellspacing=\"0\" border=\"0\" " . ">\n");*/

/* 
 * handle context sensitive left side content. <-- OR NOT!  STATIC LOOKS GOOD.
 */

/*$output .= ("<tr><td>\n" . // "nowrap" ???
    "<br><big>TOPICS</big><br><br>\n");*/

$q = "SELECT * FROM Faq WHERE parent_id = 0 AND publish = 'y' ";
$q .= "ORDER BY list_order";
$result0 = mysql_query($q, $dbLink);

$idList = array();
$this->AddBranchesID(__FILE__ . ",11");
while ($this->UPEL(__FILE__ . ",11", 2, BD::BoolCondition($faq0 = mysql_fetch_array($result0)))) //while ($faq0 = mysql_fetch_array($result0))
{
    $this->AddBranchesID(__FILE__ . ",12");
    if ($this->UPEL(__FILE__ . ",12", 2, BD::Equal($faq0["title"], "")))
        //if ($faq0["title"] == "")
        $faq0["title"] = "???";

    $idList[] = $faq0["id"];

   /* $output .= ("<a href=\"index.php?context=View+Document&helpContext=" .
        urlencode($faq0["context"]) . "\">" .
        "<small>" . $faq0["title"] . "</small></a><br>\n");*/
    $this->AddReachedAbstractUrlInEachRun($this->FilePath, "GET", array("context" => "View Document", "helpContext" => $faq0["context"]), null);


    $q = "SELECT * from Faq WHERE parent_id = " . $faq0["id"] . " ";
    $q .= "AND publish = 'y' ORDER BY list_order";
    $result1 = mysql_query($q, $dbLink);

    $this->AddBranchesID(__FILE__ . ",13");
    while ($this->UPEL(__FILE__ . ",13", 2, BD::BoolCondition($faq1 = mysql_fetch_array($result1)))) //while ($faq1 = mysql_fetch_array($result1))
    {

        $this->AddBranchesID(__FILE__ . ",14");
        if ($this->UPEL(__FILE__ . ",14", 2, BD::Equal($faq1["title"], "")))
            //if ($faq1["title"] == "")
            $faq1["title"] = "???";

        $idList[] = $faq1["id"];

      /*  $output .= ("<a href=\"./?context=View+Document&helpContext=" .
            urlencode($faq1["context"]) . "\">" .
            "<small>&nbsp;&nbsp;&nbsp;" . $faq1["title"] .
            "</small></a><br>\n");*/
        $this->AddReachedAbstractUrlInEachRun($this->FilePath, "GET", array("context" => "View Document", "helpContext" => $faq1["context"]), null);

        $q = "SELECT * from Faq WHERE parent_id = " . $faq1["id"] . " ";
        $q .= "AND publish = 'y' ORDER BY list_order";
        $result2 = mysql_query($q, $dbLink);


        $this->AddBranchesID(__FILE__ . ",15");
        while ($this->UPEL(__FILE__ . ",15", 2, BD::BoolCondition($faq2 = mysql_fetch_array($result2)))) // while ($faq2 = mysql_fetch_array($result2))
        {
            $this->AddBranchesID(__FILE__ . ",16");
            if ($this->UPEL(__FILE__ . ",16", 2, BD::Equal($faq2["title"], "")))
                // if ($faq2["title"] == "")
                $faq2["title"] = "???";

            $idList[] = $faq2["id"];

          /*  $output .= ("<a href=\"./?context=View+Document&helpContext=" .
                urlencode($faq2["context"]) . "\">" .
                "<small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
                $faq2["title"] . "</small></a><br>\n");*/
            $this->AddReachedAbstractUrlInEachRun($this->FilePath, "GET", array("context" => "View Document", "helpContext" => $faq2["context"]), null);
        }
    }
}


/*$output .= ("</td></tr>\n" .
    "</table>\n\n" .
    "</td>\n\n" .
    "<!------------  Start right side content ------------>\n\n" .
    "<td width=\"540\" valign=\"top\">\n\n" .
    "<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n");*/

$q = "SELECT * FROM Faq WHERE context =  '$helpContext'";
$result = mysql_query($q, $dbLink);
$faq = mysql_fetch_array($result);


$list = array();
$faqTitle = array();
$helpId = $faq["id"];
$list[] = urlencode($faq["context"]);
$faqTitle[] = $faq["title"];

$this->AddBranchesID(__FILE__ . ",17");
while ($this->UPEL(__FILE__ . ",17", 2, BD::NotEqual($faq["parent_id"], 0))) //while ($faq["parent_id"] != 0)
{
    $faqId = $faq["parent_id"];
    $q = "SELECT * FROM Faq WHERE id = $faqId";
    $result = mysql_query($q, $dbLink);
    $faq = mysql_fetch_array($result);
    $list[] = urlencode($faq["context"]);
    $faqTitle[] = $faq["title"];
}

/*$output .= ("<tr><td colspan=\"3\">" .
    "<small>\n");*/

$this->AddBranchesID(__FILE__ . ",18");
for ($i = (count($list) - 1); $this->UPEL(__FILE__ . ",18", 2, BD::GreaterOrEqual($i, 0)); $i--) //for ($i = (count($list) - 1); $i >= 0; $i--)
{
    //$output .= ("<a href=\"./?context=View+Document&helpContext=" . $list[$i] . "\">" . $faqTitle[$i] . "</a>");
    $this->AddReachedAbstractUrlInEachRun($this->FilePath, "GET", array("context" => "View Document", "helpContext" =>  $list[$i]), null);

    $this->AddBranchesID(__FILE__ . ",19");
    if ($this->UPEL(__FILE__ . ",19", 2, BD::GreaterThan($i, 0))){
        //if ($i > 0)
        // $output .= (" <b>-></b>\n");
    }

}

//$output .= ("</td></tr>\n");


$this->AddBranchesID(__FILE__ . ",20");
if ($this->UPEL(__FILE__ . ",20", 2, BD::BoolCondition(!$current)))
    //if (!$current)
    $current = 0;

$q = "SELECT * FROM FaqPage WHERE owner_id = $helpId";
$result = mysql_query($q, $dbLink);
$numPages = mysql_num_rows($result);

$thisPage = 0;
$faqText = "";

$this->AddBranchesID(__FILE__ . ",21");
while ($this->UPEL(__FILE__ . ",21", 2, BD::BoolCondition($faqPage = mysql_fetch_array($result)))) //while ($faqPage = mysql_fetch_array($result))
{
    $this->AddBranchesID(__FILE__ . ",22");
    if ($this->UPEL(__FILE__ . ",22", 2, BD::Equal($current, $faqPage["page_num"]))) //if ($current == $faqPage["page_num"])
    {
        $faqText = $faqPage["faqText"];
        $thisPage = $faqPage["page_num"] + 1;
    }
}

$context = urlencode($context);

//$output .= ("<tr bgcolor=\"#dddddd\">");


$this->AddBranchesID(__FILE__ . ",23");
if ($this->UPEL(__FILE__ . ",23", 2, BD::Equal($current, $faqPage["page_num"]))) //if ($current == $faqPage["page_num"])
{
    $faqText = $faqPage["faqText"];
    $thisPage = $faqPage["page_num"] + 1;
}


$this->AddBranchesID(__FILE__ . ",24");
if ($this->UPEL(__FILE__ . ",24", 2, BD::LOpAND(BD::GreaterThan($thisPage, 1), BD::GreaterThan($numPages, 1)))) //if (($thisPage > 1) && ($numPages > 1))
{
    $last = $current - 1;
   /* $output .= ("<td align=\"center\" width=\"20%\"><small>" .
        "<a href=\"./?context=$context&helpContext=" .
        urlencode($helpContext) . "&current=$last\"><< Prev</a></td>\n");*/
    $this->AddReachedAbstractUrlInEachRun($this->FilePath, "GET", array("context" => $context, "helpContext" => $helpContext,"current"=>$last), null);

} else {
   // $output .= ("<td align=\"center\" width=\"20%\">&nbsp;</td>\n");
}

/*$output .= ("<td align=\"center\" width=\"60%\"><small>" .
    "<strong>Page $thisPage of $numPages</strong></td>\n");*/


$this->AddBranchesID(__FILE__ . ",25");
if ($this->UPEL(__FILE__ . ",25", 2, BD::LOpAND(BD::LessThan($thisPage, $numPages), BD::GreaterThan($numPages, 1)))) //if (($thisPage < $numPages) && ($numPages > 1))
{
    $next = $current + 1;
    /*$output .= ("<td align=\"center\" width=\"20%\"><small>" .
        "<a href=\"./?context=$context&helpContext=" .
        urlencode($helpContext) . "&current=$next\">Next >></a></td>\n");*/
    $this->AddReachedAbstractUrlInEachRun($this->FilePath, "GET", array("context" => $context, "helpContext" => $helpContext,"current"=>$next), null);
} else {
    //$output .= ("<td align=\"center\" width=\"20%\">&nbsp;</td>\n");
}

/*$output .= ("</tr></table>\n" .
    "<table cellpadding=\"6\" cellspacing=\"0\" border=\"0\" width=\"100%\">" .
    "<tr bgcolor=\"#eeeeee\"><td colspan=\"3\">\n" .
    "<strong><u>" . $faqTitle[0] . "</u></strong><br><br>\n" .
    "<small>" . $faqText . "</small>\n" .
    "<br></td></tr></table>\n");*/

/*$output .= ("<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n" .
    "<tr bgcolor=\"#dddddd\">");*/

$this->AddBranchesID(__FILE__ . ",26");
if ($this->UPEL(__FILE__ . ",26", 2, BD::LOpAND(BD::GreaterThan($thisPage, 1), BD::GreaterThan($numPages, 1)))) //if (($thisPage > 1) && ($numPages > 1))
{
    $last = $current - 1;
   /* $output .= ("<td align=\"center\" width=\"20%\"><small>" .
        "<a href=\"./?context=$context&helpContext=" .
        urlencode($helpContext) . "&current=$last\"><< Prev</a></td>\n");*/
    $this->AddReachedAbstractUrlInEachRun($this->FilePath, "GET", array("context" => $context, "helpContext" => $helpContext,"current"=>$last), null);
} else {
  //  $output .= ("<td align=\"center\" width=\"20%\">&nbsp;</td>\n");
}

/*$output .= ("<td align=\"center\" width=\"60%\"><small>" .
    "<strong>Page $thisPage of $numPages</strong></td>\n");*/


$this->AddBranchesID(__FILE__ . ",27");
if ($this->UPEL(__FILE__ . ",27", 2, BD::LOpAND(BD::LessThan($thisPage, $numPages), BD::GreaterThan($numPages, 1)))) //if (($thisPage < $numPages) && ($numPages > 1))
{
    $next = $current + 1;
  /*  $output .= ("<td align=\"center\" width=\"20%\"><small>" .
        "<a href=\"./?context=$context&helpContext=" .
        urlencode($helpContext) . "&current=$next\">Next >></a></td>\n");*/
    $this->AddReachedAbstractUrlInEachRun($this->FilePath, "GET", array("context" => $context, "helpContext" => $helpContext,"current"=>$next), null);
} else {
   // $output .= ("<td align=\"center\" width=\"20%\">&nbsp;</td>\n");
}

/*$output .= ("</tr>\n" .
    "</table>\n\n" .
    "</td></tr>\n" .
    "</table>\n\n" .
    "</body>\n" .
    "</html>\n");*/

