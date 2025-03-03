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
if(!defined('SUPPORT_NETWORK')) { die("Illegal Access Detected!!!"); }
$project_id = intval($project_id);
$project = pjproject_info($project_id);
if($project['allowrequests'] > 0) {
  
  $pagetitle = _NETWORK_TITLE.' v'.$pj_config['version_number'].' - '._NETWORK_SUBMITAREQUEST;
  
  include_once(NUKE_BASE_DIR.'header.php');
  OpenTable();
  
  echo '<div align="center"><strong>'._NETWORK_TITLE.' v'.$pj_config['version_number'].' - '._NETWORK_SUBMITAREQUEST.'</strong></div>';
  
  echo '<div align="center">';
  echo '<a class="titaniumbutton" href="modules.php?name=Network_Projects">' . _NETWORK_PROJECTLIST . '</a> ';
  echo '<a class="titaniumbutton" href="modules.php?name=Network_Projects&op=TaskMap">' . _NETWORK_TASKMAP . '</a> ';
  echo '<a class="titaniumbutton" href="modules.php?name=Network_Projects&op=ReportMap">' . _NETWORK_REPORTMAP . '</a> ';
  echo '<a class="titaniumbutton" href="modules.php?name=Network_Projects&op=RequestMap">' . _NETWORK_REQUESTMAP . '</a>';
  echo '</div><br/>';
  
  echo "<table align='center' border='0' cellpadding='2' cellspacing='2'>\n";
  echo "<form action='modules.php?name=$titanium_module_name' method='post'>\n";
  echo "<input type='hidden' name='op' value='RequestInsert'>\n";
  echo "<input type='hidden' name='project_id' value='$project_id'>\n";
  echo "<tr><td align='center' colspan='2' class='title'>"._NETWORK_INPUTNOTE."</td></tr>\n";
  echo "<tr><td bgcolor='$bgcolor2'>"._NETWORK_PROJECT.":</td>\n";
  echo "<td><select name='project_id'>\n";
  $projectlist = $titanium_db2->sql_query("SELECT `project_id`, `project_name` FROM `".$network_prefix."_projects` ORDER BY `project_name`");
  while(list($s_project_id, $s_project_name) = $titanium_db2->sql_fetchrow($projectlist)){
    if($s_project_id == $project_id){ $sel = "selected"; } else { $sel = ""; }
    echo "<option value='$s_project_id' $sel>$s_project_name</option>\n";
  }
  echo "</select></td></tr>\n";
  echo "<tr><td bgcolor='$bgcolor2'>"._NETWORK_TYPE.":</td><td><select name='type_id'>\n";
  $typelist = $titanium_db2->sql_query("SELECT `type_id`, `type_name` FROM `".$network_prefix."_requests_types` ORDER BY `type_name`");
  while(list($s_type_id, $s_type_name) = $titanium_db2->sql_fetchrow($typelist)){
    if($s_type_id == $type_id){ $sel = "selected"; } else { $sel = ""; }
    echo "<option value='$s_type_id' $sel>$s_type_name</option>\n";
  }
  echo "</select></td></tr>\n";
  echo "<tr><td bgcolor='$bgcolor2'>"._NETWORK_USERNAME.":</td>\n";
  echo "<td><input type='text' name='submitter_name' size='30' value='".$titanium_userinfo['username']."'></td></tr>\n";
  echo "<tr><td bgcolor='$bgcolor2'>"._NETWORK_EMAILADDRESS.":</td>\n";
  echo "<td><input type='text' name='submitter_email' size='30' value='".$titanium_userinfo['user_email']."'></td></tr>\n";
  echo "<tr><td bgcolor='$bgcolor2'>"._NETWORK_SUMMARY.":</td>\n";
  echo "<td><input type='text' name='request_name' size='30'></td></tr>\n";
  echo "<tr><td bgcolor='$bgcolor2' valign='top'>"._NETWORK_DESCRIPTION.":</td>\n";
  echo "<td><textarea name='request_description' cols='60' rows='10'></textarea></td></tr>\n";
  echo "<tr><td align='center' colspan='2'><input type='submit' value='"._NETWORK_SUBMITREQUEST."'></td></tr>\n";
  echo "</form>\n";
  echo "</table>\n";
  CloseTable();
  include_once(NUKE_BASE_DIR.'footer.php');
} else {
  header("Location: modules.php?name=$titanium_module_name");
}

?>