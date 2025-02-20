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

$pagetitle = _NETWORK_TITLE.' v'.$pj_config['version_number'].' - '._NETWORK_PROJECTS.': '._NETWORK_TASKLIST;

include_once(NUKE_BASE_DIR.'header.php');

$projectresult = $titanium_db2->sql_query("SELECT `project_name`, `project_description`, `status_id`, `priority_id` FROM `".$network_prefix."_projects` WHERE `project_id`='$project_id'");
list($project_name, $project_description, $status_id, $priority_id) = $titanium_db2->sql_fetchrow($projectresult);
pjadmin_menu(_NETWORK_PROJECTS.": "._NETWORK_TASKLIST);
//echo "<br />\n";
$taskresult = $titanium_db2->sql_query("SELECT `task_id`, `task_name`, `priority_id`, `status_id` FROM `".$network_prefix."_tasks` WHERE `project_id`='$project_id' ORDER BY `task_name`");
$task_total = $titanium_db2->sql_numrows($taskresult);
OpenTable();
echo "<table width='100%' border='1' cellspacing='0' cellpadding='2'>\n";
echo "<tr><td colspan='2' bgcolor='$bgcolor2' width='100%'><nobr><strong>"._NETWORK_PROJECT."</strong></nobr></td>\n";
echo "<td align='center' bgcolor='$bgcolor2'><strong>"._NETWORK_STATUS."</strong></td>\n";
echo "<td align='center' bgcolor='$bgcolor2'><strong>"._NETWORK_PRIORITY."</strong></td>\n";
echo "<td align='center' bgcolor='$bgcolor2'><strong>"._NETWORK_FUNCTIONS."</strong></td></tr>\n";
$pjimage = pjimage("project.png", $titanium_module_name);
echo "<tr><td><img src='$pjimage'></td>\n";
echo "<td width='100%'><a href='".$admin_file.".php?op=ProjectList'>"._NETWORK_PROJECTS."</a> / <strong>$project_name</strong></td>\n";
$projectstatus = pjprojectstatus_info($status_id);
if(empty($projectstatus['status_name'])) { $projectstatus['status_name'] = _NETWORK_NA; }
echo "<td align='center'><a href='".$admin_file.".php?op=ProjectStatusList'>".$projectstatus['status_name']."</a></td>\n";
$projectpriority = pjprojectpriority_info($priority_id);
if(empty($projectpriority['priority_name'])) { $projectpriority['priority_name'] = _NETWORK_NA; }
echo "<td align='center'><a href='".$admin_file.".php?op=ProjectPriorityList'>".$projectpriority['priority_name']."</a></td>\n";
echo "<td align='center'><nobr>[ <a href='".$admin_file.".php?op=ProjectEdit&amp;project_id=$project_id'>"._NETWORK_EDIT."</a> |";
echo " <a href='".$admin_file.".php?op=ProjectRemove&amp;project_id=$project_id'>"._NETWORK_DELETE."</a> ]</nobr></td></tr>\n";
echo "<tr><td colspan='5' width='100%' bgcolor='$bgcolor2'><nobr><strong>"._NETWORK_PROJECTOPTIONS."</strong></nobr></td></tr>\n";
$pjimage = pjimage("options.png", $titanium_module_name);
echo "<tr><td><img src='$pjimage'></td><td colspan='4' width='100%'><nobr><a href='".$admin_file.".php?op=TaskAdd&amp;project_id=$project_id'>"._NETWORK_TASKADD."</a></nobr></td></tr>\n";
$pjimage = pjimage("stats.png", $titanium_module_name);
echo "<tr><td><img src='$pjimage'></td><td colspan='4' width='100%'><nobr>"._NETWORK_TOTALTASKS.": <strong>$task_total</strong></nobr></td></tr>\n";
echo "</table>\n";
CloseTable();
//echo "<br />\n";
OpenTable();
echo "<table width='100%' border='1' cellspacing='0' cellpadding='2'>\n";
echo "<tr><td colspan='2' bgcolor='$bgcolor2' width='100%'><strong>"._NETWORK_PROJECTTASKS."</strong></a></td>\n";
echo "<td align='center' bgcolor='$bgcolor2'><strong>"._NETWORK_STATUS."</strong></td><td align='center' bgcolor='$bgcolor2'><strong>"._NETWORK_PRIORITY."</strong></td><td align='center' bgcolor='$bgcolor2'><strong>"._NETWORK_FUNCTIONS."</strong></td></tr>\n";
if($task_total != 0){
  while(list($task_id, $task_name, $priority_id, $status_id) = $titanium_db2->sql_fetchrow($taskresult)) {
    $pjimage = pjimage("task.png", $titanium_module_name);
    echo "<tr><td><img src='$pjimage'></td><td width='100%'>$task_name</td>\n";
    $taskstatus = pjtaskstatus_info($status_id);
    if(empty($taskstatus['status_name'])) { $taskstatus['status_name'] = _NETWORK_NA; }
    echo "<td align='center'><a href='".$admin_file.".php?op=TaskStatusList'>".$taskstatus['status_name']."</a></td>\n";
    $taskpriority = pjtaskpriority_info($priority_id);
    if(empty($taskpriority['priority_name'])) { $taskpriority['priority_name'] = _NETWORK_NA; }
    echo "<td align='center'><a href='".$admin_file.".php?op=TaskPriorityList'>".$taskpriority['priority_name']."</a></td>\n";
    echo "<td align='center'><nobr>[ <a href='".$admin_file.".php?op=TaskEdit&amp;task_id=$task_id'>"._NETWORK_EDIT."</a>";
    echo " | <a href='".$admin_file.".php?op=TaskRemove&amp;task_id=$task_id'>"._NETWORK_DELETE."</a> ]</nobr></td></tr>\n";
  }
} else {
  echo "<tr><td width='100%' colspan='4' align='center'>"._NETWORK_NOPROJECTTASKS."</td></tr>\n";
}
echo "</table>\n";
CloseTable();
pj_copy();
include_once(NUKE_BASE_DIR.'footer.php');

?>