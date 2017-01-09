<?php

/*
$output.= ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n");
$output.=  ("<!-- Copyright 2000 Andrew C. Bertola, All Rights Reserved -->\n");
$output.=  ("<!-- Copyright 2004-2006 Scott Grayban, All Rights Reserved -->\n");
$output.=  ("<html>\n" .
       "<head>\n" .
       "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n" .
       "<style type=\"text/css\">\n" .
       "<!--\n" .
       "body  { font-face: verdana, arial, helvetica, times }\n" .
       "td    { font-face: verdana, arial, helvetica, times ; }\n" .
       "a     { color: #0000ff ;\n" .
       "        text-decoration: none }\n" .
       "-->\n" .
       "</style>\n" .
       "<title>$title</title>\n" .
       "<meta http-equiv=\"pragma\" content=\"no-cache\">\n" .
       "</head>\n" .
       "<body bgcolor=\"white\">\n" .
       "<center>\n\n" .
       "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n" .
       "<tr><td width=\"75%\" align=left>\n" .
       "<a href=\"http://sourceforge.net/projects/faqforge/\" target=\"_blank\"><img src=\"/images/faqforge.png\" alt=\"FaqForge\" align=\"top\" border=\"0\"></a> v $version\n" .
       "<a name=\"top\">&nbsp;</a>\n" .
       "</td>\n" .
       "<td width=\"25%\" align=\"right\">\n");*/



$this->AddBranchesID(__FILE__ . ",28");
if ($this->UPEL(__FILE__ . ",28", 2, BD::NotEqual($context,"Topics List")))
//if ( $context != "Topics List" )
{
      // $output.=  ("<a href=\"./?context=Topics+List\">Back to Topics List</a><br>\n");
       $this->AddReachedAbstractUrlInEachRun($this->FilePath,"GET",array("context"=>"Topics List"),null);
}

//$output.=  ("<a href=\"./admin/\">Administration</a><br>\n");
$this->AddReachedAbstractUrlInEachRun(FilePath::Admin_Index_php,"GET",array("context"=>"Topics List"),null);
/*
$output.= ("</td></tr>\n" .
       "</table>\n\n" .
       "<br>\n");*/

