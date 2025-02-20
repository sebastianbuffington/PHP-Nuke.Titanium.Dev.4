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
$report_id = intval($report_id);
$report = pjreport_info($report_id);
$project = pjproject_info($report['project_id']);
if($project['allowreports'] > 0) {
  $pagetitle = "::: "._NETWORK_TITLE." ".$pj_config['version_number']." ::: "._NETWORK_COMMENTADD." ::: ";
  include_once(NUKE_BASE_DIR.'header.php');
  OpenTable();
  echo '<div align="center"><strong>'._NETWORK_TITLE." v".$pj_config['version_number']." ::: "._NETWORK_COMMENTADD." ::: ".'</strong></div><br />';
  echo "<table align='center' border='0' cellpadding='2' cellspacing='2'>\n";
  echo "<form action='modules.php?name=$titanium_module_name' method='post'>\n";
  echo "<input type='hidden' name='op' value='ReportCommentInsert'>\n";
  echo "<input type='hidden' name='report_id' value='$report_id'>\n";
  echo "<tr><td align='center' colspan='2' class='title'>"._NETWORK_INPUTNOTE."</td></tr>\n";
  echo "<tr><td bgcolor='$bgcolor2'>"._NETWORK_USERNAME.":</td>\n";
  echo "<td><input type='text' name='commenter_name' size='30' value='".$titanium_userinfo['username']."'></td></tr>\n";
  echo "<tr><td bgcolor='$bgcolor2'>"._NETWORK_EMAILADDRESS.":</td>\n";
  echo "<td><input type='text' name='commenter_email' size='30' value='".$titanium_userinfo['user_email']."'></td></tr>\n";
  echo "<tr><td bgcolor='$bgcolor2' valign='top'>"._NETWORK_COMMENT.":</td>\n";
  echo "<td><textarea name='comment_description' cols='60' rows='10' wrap='virtual'></textarea></td></tr>\n";
  echo "<tr><td align='center' colspan='2'><input type='submit' value='"._NETWORK_COMMENTADD."'></td></tr>\n";
  echo "</form>\n";
  echo "</table>\n";
  CloseTable();
  include_once(NUKE_BASE_DIR.'footer.php');
} else {
  header("Location: modules.php?name=$titanium_module_name");
}

?>