# MySQL database backup
#
# Generated: Monday 7. November 2016 22:09 CET
# Hostname: localhost
# Database: `faqforge`
# --------------------------------------------------------
# --------------------------------------------------------
# Table: `faq`
# --------------------------------------------------------


#
# Delete any existing table `faq`
#

DROP TABLE IF EXISTS `faq`;


#
# Table structure of table `faq`
#

CREATE TABLE `faq` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '',
  `parent_id` int(10) unsigned DEFAULT '0',
  `context` varchar(32) NOT NULL DEFAULT '',
  `list_order` int(10) unsigned NOT NULL DEFAULT '10000',
  `publish` enum('y','n') DEFAULT 'n',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1 ;

#
# Data contents of table faq (13 records)
#
 
INSERT INTO `faq` VALUES (13, 'tioic2', 0, 'View Document', 10000, 'y') ; 
INSERT INTO `faq` VALUES (14, 'tioic1', 0, 'Topics List-Topic1', 98, 'n') ; 
INSERT INTO `faq` VALUES (23, 'tioicl5-1', 25, 'View Document', 10000, 'y') ; 
INSERT INTO `faq` VALUES (24, 'tioic2-1', 13, 'Topics List', 0, 'n') ; 
INSERT INTO `faq` VALUES (25, '', 0, 'View Document', 10000, 'y') ; 
INSERT INTO `faq` VALUES (26, '', 25, 'Topics List', 98, 'n') ; 
INSERT INTO `faq` VALUES (29, 'tioicl2-1-1', 24, 'Topics List', 98, 'n') ; 
INSERT INTO `faq` VALUES (30, 'tioicl5-1-1-0', 23, '', 10000, 'y') ; 
INSERT INTO `faq` VALUES (31, 'tioicl5-1-1', 23, 'Topics List', 98, 'y') ; 
INSERT INTO `faq` VALUES (34, 'NewTopic', 0, 'new Context', 10000, 'y') ; 
INSERT INTO `faq` VALUES (35, 'NewTopic1-1', 34, 'new Context1', 10000, 'y') ; 
INSERT INTO `faq` VALUES (36, 'NewTopic1-1-1', 35, 'new Context1-1-1', 10000, 'y') ; 
INSERT INTO `faq` VALUES (37, 'NewTopic1-1-1-1', 35, 'new Context1-1-1-1', 10000, 'y') ;
#
# End of data contents of table faq
# --------------------------------------------------------

# MySQL database backup
#
# Generated: Monday 7. November 2016 22:09 CET
# Hostname: localhost
# Database: `faqforge`
# --------------------------------------------------------
# --------------------------------------------------------
# Table: `faq`
# --------------------------------------------------------
# --------------------------------------------------------
# Table: `faqpage`
# --------------------------------------------------------


#
# Delete any existing table `faqpage`
#

DROP TABLE IF EXISTS `faqpage`;


#
# Table structure of table `faqpage`
#

CREATE TABLE `faqpage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `faqText` text,
  `page_num` int(10) unsigned NOT NULL DEFAULT '0',
  `owner_id` int(10) unsigned NOT NULL DEFAULT '0',
  `publish` enum('y','n') DEFAULT 'n',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=latin1 ;

#
# Data contents of table faqpage (17 records)
#
 
INSERT INTO `faqpage` VALUES (1, 'CONTEXTDid you mean: download FAQ Forge php codeSearch ResultsFaqForge download | SourceForge.nethttps://sourceforge.net/projects/faqforge/Free - ?Linux, Mac OS, WindowsApr 18, 2013 - FaqForge 2013-04-18 21:42:55.042000 free download. ... Can be used on any OS running a webserver capable of running PHP/MySQL.faqText', 10, 14, 'y') ; 
INSERT INTO `faqpage` VALUES (24, 'CONTEXTDid you mean: download FAQ Forge php codeSearch ResultsFaqForge download | SourceForge.nethttps://sourceforge.net/projects/faqforge/Free - ?Linux, Mac OS, WindowsApr 18, 2013 - FaqForge 2013-04-18 21:42:55.042000 free download. ... Can be used on any OS running a webserver capable of running PHP/MySQL.faqText', 1, 13, 'y') ; 
INSERT INTO `faqpage` VALUES (25, 'No matches were found for "topic0".No matches were found for "topic0".No matches were found for "topic0".No matches were found for "topic0".No matches were found for "topic0".No matches were found for "topic0".No matches were found for "topic0".', 20, 14, 'n') ; 
INSERT INTO `faqpage` VALUES (26, 'No matches were found for "topic0". No matches were found for "topic0". No matches were found for "topic0". No matches were found for "topic0". No matches were found for "topic0". No matches were found for "topic0". No matches were found for "topic0". ', 40, 23, 'n') ; 
INSERT INTO `faqpage` VALUES (27, 'No matches were found for "topic0". No matches were found for "topic0". No matches were found for "topic0". No matches were found for "topic0". No matches were found for "topic0". No matches were found for "topic0". No matches were found for "topic0". ', 3, 14, 'n') ; 
INSERT INTO `faqpage` VALUES (28, 'No matches were found for "topic0". No matches were found for "topic0". No matches were found for "topic0". No matches were found for "topic0". No matches were found for "topic0". No matches were found for "topic0". No matches were found for "topic0". ', 2, 23, 'y') ; 
INSERT INTO `faqpage` VALUES (39, '', 0, 23, 'y') ; 
INSERT INTO `faqpage` VALUES (40, '', 0, 23, 'n') ; 
INSERT INTO `faqpage` VALUES (57, 'page 1 of NewTopic', 0, 34, 'n') ; 
INSERT INTO `faqpage` VALUES (58, 'page 2 of NewTopic', 1, 34, 'n') ; 
INSERT INTO `faqpage` VALUES (59, 'page3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rulespage3.Crawljax allows its users to control navigation throughthe webpages by specifying exploration depth, time budget,and action rules', 2, 34, 'n') ; 
INSERT INTO `faqpage` VALUES (60, 'page 1  of NewTopic1-1', 0, 35, 'n') ; 
INSERT INTO `faqpage` VALUES (61, 'page2 of NewTopic1-1', 1, 35, 'n') ; 
INSERT INTO `faqpage` VALUES (64, 'page 1 of NewTopic1-1-1', 1, 36, 'n') ; 
INSERT INTO `faqpage` VALUES (65, 'page 2 of NewTopic1-1-1', 0, 36, 'n') ; 
INSERT INTO `faqpage` VALUES (66, 'page 3 of NewTopic1-1-1', 2, 36, 'n') ; 
INSERT INTO `faqpage` VALUES (67, 'page1c', 0, 37, 'n') ;
#
# End of data contents of table faqpage
# --------------------------------------------------------

