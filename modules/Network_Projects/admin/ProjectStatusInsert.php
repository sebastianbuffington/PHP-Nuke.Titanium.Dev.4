<?php
/*=======================================================================
 PHP-Nuke Titanium: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

/********************************************************/
/* NukeProject(tm)                                      */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://nukescripts.86it.us                           */
/* Copyright (c) 2000-2005 by NukeScripts Network       */
/********************************************************/

global $titanium_db2;

get_lang('Network_Projects');

if(!defined('NETWORK_SUPPORT_ADMIN')) { die("Illegal Access Detected!!!"); }

$status_name = htmlentities($status_name, ENT_QUOTES);

$result = $titanium_db2->sql_query("SELECT `status_weight` FROM `".$network_prefix."_projects_status` ORDER BY `status_weight` DESC");

list($lweight) = $titanium_db2->sql_fetchrow($result);

$weight = $lweight + 1;

if($weight < 1) { $weight = 1; }

$titanium_db2->sql_query("INSERT INTO `".$network_prefix."_projects_status` VALUES (NULL, '$status_name', '$weight')");

header("Location: ".$admin_file.".php?op=ProjectStatusList");
?>
