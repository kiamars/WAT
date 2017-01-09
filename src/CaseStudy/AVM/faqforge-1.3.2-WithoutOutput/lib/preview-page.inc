<?php

$pageTitle="";
if(isset($_POST["pageTitle"]))
       $pageTitle =$_POST['pageTitle'];

$faqId=0;
if(isset($_POST["faqId"]))
       $faqId =$_POST['faqId'];

$id="";
if(isset($_POST["id"]))
       $id =$_POST['id'];

$pageNum="";
if(isset($_POST["pageNum"]))
       $pageNum =$_POST['pageNum'];



$pageTitle = stripslashes ( $pageTitle );


$faqText="";
if(isset($_POST["faqText"]))
       $faqText =$_POST['faqText'];
$encText = urlencode ($faqText);
$faqText = stripslashes ($faqText);
//$faqText = ereg_replace ("\$site", $site, $faqText);

/*$output.= ("<center><big>Page Preview</big><br><br>\n\n" .
       "<table bgcolor=\"#000000\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n" .
       "<tr><td>\n\n" .
       "<table bgcolor=\"#eeeeee\" cellpadding=\"8\" cellspacing=\"0\" " .
       "width=\"540\" border=\"0\">\n" .
       "<tr><td><strong><u>$pageTitle</td></tr>\n" .
       "<tr><td>\n" . $faqText . "</td></tr>\n" .
       "</table>\n\n" .
       "</td></tr>\n" .
       "</table>\n\n" .
       "<br>\n" .
       "<a href=\"./?context=Edit+Page&id=$faqId&pageId=$pageNum" .
       "&faqText=$encText#$pageNum\">Modify Changes</a>\n" .
       "&nbsp;&nbsp;&nbsp;&nbsp;\n" .
       "<a href=\"./?context=Edit+Page&id=$faqId&pageId=$pageNum" .
       "&action=commit&faqText=$encText\">" .
       "Commit Changes</a>\n" .
       "</center>\n" .
       "</body>\n" .
       "</html>\n");*/

$this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "GET",
    array("context"=>"Edit Page","id"=>$faqId,"pageId"=>$pageNum,"faqText"=>$encText), null);
$this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php, "GET",
    array("context"=>"Edit Page","id"=>$faqId,"pageId"=>$pageNum,"action"=>"commit","faqText"=>$encText), null);