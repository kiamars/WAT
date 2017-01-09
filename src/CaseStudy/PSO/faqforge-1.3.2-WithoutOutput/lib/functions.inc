<?php



/***************************************************************************
 * function: delete_topic_test
 * author: Drew
 *
 * Test for existing child topics beneath the topic to be deleted.  If
 * so, return advisory to remove children first.  If no associated
 * child topics, return message with delete confirmation link.
 ***************************************************************************/

function delete_topic_test ( $id, $dbLink )
{
  $q = "SELECT * FROM Faq WHERE parent_id = $id";
  $result = mysql_query ($q, $dbLink);

  if ( mysql_num_rows ($result) > 0 )
    {
      $message  = "<strong>Error: The topic you want to delete has child ";
      $message .= "topics associated with it.  You must delete all child ";
      $message .= "topics before deleting their parent topic.</strong>\n";
    }
  else
    {
      $message  = "<big>To confirm delete, click ";
      $message .= "<a href=\"./?context=Topics+List&action=DELETETopic";
      $message .= "&id=$id\">HERE</a>.</big>";
    }

  return ( $message );
}

/***************************************************************************
 * function: delete_topic
 * author: Drew
 *
 * Delete topic identified by $id.  No return value.
 ***************************************************************************/

function delete_topic ( $id, $dbLink )
{
  $q = "DELETE FROM FaqPage WHERE owner_id = $id";
  mysql_query ($q, $dbLink);

  $q = "DELETE FROM Faq WHERE id = $id";
  mysql_query ($q, $dbLink);
}

/***************************************************************************
 * function: delete_page_test
 * author: Drew
 *
 * Return a message with a confirmation link for the delete request.
 ***************************************************************************/

function delete_page_test ( $page_num, $id, $dbLink )
{
  $message  = "<big>To confirm delete, click ";
  $message .= "<a href=\"./?context=Edit+Page&action=DELETEPage";
  $message .= "&page_num=$page_num&id=$id\">HERE</a>.</big>\n";

  return ( $message );
}

/***************************************************************************
 * function: delete_page
 * author: Drew
 *
 * Delete Page identified by topic id and page num.  No Return value.
 ***************************************************************************/

function delete_page ( $id, $page_num, $dbLink )
{
  $q = "DELETE FROM FaqPage WHERE owner_id = $id AND page_num = $page_num";
  mysql_query ($q, $dbLink);

  $q = "SELECT id FROM FaqPage WHERE owner_id = $id AND page_num > $page_num";
  $result = mysql_query ($q, $dbLink);

  while ( $row = mysql_fetch_row ($result) )
    {
      $q = "UPDATE FaqPage SET page_num = (page_num - 1) WHERE id = $row[0]";
      mysql_query ($q, $dbLink);
    }
}

/***************************************************************************
 * function: add_new_topic
 * author: Drew
 *
 * Add a topic.  Initialize Topic by adding a page for it.  No return
 * value.
 ***************************************************************************/

function add_new_topic ( $title, $parent, $context, $order, $dbLink )
{
  $q = "INSERT INTO Faq  VALUES ('','$title','$parent','$context',$order,'n')";
  $result = mysql_query ($q, $dbLink);

  $newId = mysql_insert_id();
  
  $q  = "INSERT INTO FaqPage VALUES ('', '', 0, $newId,'n')";
  mysql_query ($q, $dbLink);
}

/***************************************************************************
 * function: add_new_page
 * author: Drew
 *
 * Add a page to a topic.  No return value.
 ***************************************************************************/

function add_new_page ( $id, $dbLink )
{
  $q  = "SELECT id FROM FaqPage WHERE owner_id = $id";
  $result = mysql_query ($q, $dbLink);
  $count = mysql_num_rows ($result);

  $q  = "INSERT INTO FaqPage VALUES ('', '', $count, $id, 'n')";
  mysql_query ($q, $dbLink);
}

/***************************************************************************
 * function: update_topic
 * author: Drew
 *
 * Update a topic based on data from form.
 ***************************************************************************/

function update_topic ( $title, $context, $parent, $order, 
			$publish, $id, $dbLink )
{

  $q  = "UPDATE Faq SET ";
  $q .= "title = '$title', ";
  $q .= "context = '$context', ";
  $q .= "parent_id = $parent, ";
  $q .= "list_order = $order, ";
  $q .= "publish = '$publish' ";
  $q .= "WHERE id = $id";

  mysql_query ($q, $dbLink);
}

/***************************************************************************
 * function: swap_page_position
 * author: Drew
 *
 * Swap position of page identified by $page_num with that identified
 * by $new_num.  Requires that pages are consecutive.  No return
 * value.
 ***************************************************************************/

function swap_page_position ( $page_num, $new_num, $id, $dbLink )
{
  $q = "SELECT * FROM FaqPage WHERE page_num = $new_num AND owner_id = $id";
  $result = mysql_query ($q, $dbLink);
  $upper = mysql_fetch_array ($result);

  $q  = "UPDATE FaqPage SET page_num = $new_num WHERE ";
  $q .= "page_num = $page_num AND owner_id = $id";
  $result = mysql_query ($q, $dbLink);

  $q  = "UPDATE FaqPage SET page_num = $page_num WHERE id = ".$upper["id"]." ";
  $q .= "AND owner_id = $id";
  $result = mysql_query ($q, $dbLink);
}

/***************************************************************************
 * function: update_page
 * author: Drew
 *
 * Update contents of page identified by topic id and page_num.  No
 * return value.
 ***************************************************************************/

function update_page ( $text, $page_num, $id, $dbLink )
{
  $q  = "UPDATE FaqPage SET faqText = '$text' WHERE owner_id = $id AND ";
  $q .= "page_num = $page_num";

  mysql_query ($q, $dbLink);
}

?>
