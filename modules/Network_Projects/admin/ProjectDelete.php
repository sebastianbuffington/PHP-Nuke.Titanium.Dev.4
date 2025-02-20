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

$project_id = intval($project_id);

$project = pjproject_info($project_id);

$titanium_db2->sql_query("DELETE FROM `".$network_prefix."_projects` WHERE `project_id`='$project_id'");
$titanium_db2->sql_query("OPTIMIZE TABLE `".$network_prefix."_projects`");
$titanium_db2->sql_query("DELETE FROM `".$network_prefix."_projects_members` WHERE `project_id`='$project_id'");
$titanium_db2->sql_query("OPTIMIZE TABLE `".$network_prefix."_projects_members`");
$taskresult = $titanium_db2->sql_query("SELECT `task_id` FROM `".$network_prefix."_tasks` WHERE `project_id`='$project_id'");

while(list($task_id) = $titanium_db2->sql_fetchrow($taskresult)) 
{
  $titanium_db2->sql_query("DELETE FROM `".$network_prefix."_tasks` WHERE `task_id`='$task_id'");
  $titanium_db2->sql_query("DELETE FROM `".$network_prefix."_tasks_members` WHERE `task_id`='$task_id'");
}

$titanium_db2->sql_query("OPTIMIZE TABLE `".$network_prefix."_tasks`");
$titanium_db2->sql_query("OPTIMIZE TABLE `".$network_prefix."_tasks_members`");
$reportresult = $titanium_db2->sql_query("SELECT `report_id` FROM `".$network_prefix."_reports` WHERE `project_id`='$project_id'");

while(list($report_id) = $titanium_db2->sql_fetchrow($reportresult)) 
{
  $titanium_db2->sql_query("DELETE FROM `".$network_prefix."_reports` WHERE `report_id`='$report_id'");
  $titanium_db2->sql_query("DELETE FROM `".$network_prefix."_reports_members` WHERE `report_id`='$report_id'");
  $titanium_db2->sql_query("DELETE FROM `".$network_prefix."_reports_comments` WHERE `report_id`='$report_id'");
}

$titanium_db2->sql_query("OPTIMIZE TABLE `".$network_prefix."_reports`");
$titanium_db2->sql_query("OPTIMIZE TABLE `".$network_prefix."_reports_members`");
$titanium_db2->sql_query("OPTIMIZE TABLE `".$network_prefix."_reports_comments`");
$requestresult = $titanium_db2->sql_query("SELECT `request_id` FROM `".$network_prefix."_requests` WHERE `project_id`='$project_id'");

while(list($request_id) = $titanium_db2->sql_fetchrow($requestresult)) 
{
  $titanium_db2->sql_query("DELETE FROM `".$network_prefix."_requests` WHERE `request_id`='$request_id'");
  $titanium_db2->sql_query("DELETE FROM `".$network_prefix."_requests_members` WHERE `request_id`='$request_id'");
  $titanium_db2->sql_query("DELETE FROM `".$network_prefix."_requests_comments` WHERE `request_id`='$request_id'");
}

$titanium_db2->sql_query("OPTIMIZE TABLE `".$network_prefix."_requests`");
$titanium_db2->sql_query("OPTIMIZE TABLE `".$network_prefix."_requests_members`");
$titanium_db2->sql_query("OPTIMIZE TABLE `".$network_prefix."_requests_comments`");
$projectresult = $titanium_db2->sql_query("SELECT `project_id`, `weight` FROM `".$network_prefix."_projects` WHERE `weight`>='".$project['weight']."'");

while(list($p_project_id, $weight) = $titanium_db2->sql_fetchrow($projectresult)) 
{
  $new_weight = $weight - 1;
  $titanium_db2->sql_query("UPDATE `".$network_prefix."_projects` SET `weight`='$new_weight' WHERE `project_id`='$p_project_id'");
}

$titanium_db2->sql_query("OPTIMIZE TABLE `".$network_prefix."_projects`");

header("Location: ".$admin_file.".php?op=ProjectList");
?>
