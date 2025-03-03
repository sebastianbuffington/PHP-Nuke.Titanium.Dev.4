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
if(!defined('NETWORK_SUPPORT_ADMIN')) { die("Illegal Access Detected!!!"); }
include_once(NUKE_INCLUDE_DIR."counter.php");
$request = pjrequest_info($request_id);
$Theme_Sel = get_theme();
echo "<html>\n";
echo "<head>\n";
echo "<title>"._NETWORK_REQUESTVIEW.": ".$request['request_name']."</title>\n";
echo "</head>\n";
echo "<body>\n";
require_once("themes/$Theme_Sel/theme.php");
echo "<center><h3>"._NETWORK_REQUESTVIEW.": ".$request['request_name']."</h3></center>\n";
echo "<br />\n";
$project = pjproject_info($request['project_id']);
$requeststatus = pjrequeststatus_info($request['status_id']);
$requesttype = pjrequesttype_info($request['type_id']);
if(empty($requeststatus['status_name'])){ $requeststatus['status_name'] = _NETWORK_NA; }
if(empty($requesttype['type_name'])){ $requesttype['type_name'] = _NETWORK_NA; }
echo "<center><table width='100%' border='1' cellspacing='0' cellpadding='2'>\n";
echo "<tr><td colspan='4' width='100%'><nobr><strong>"._NETWORK_PROJECTNAME."</strong></nobr></td></tr>\n";
$pjimage = pjimage("project.png", $titanium_module_name);
echo "<tr><td align='center'><img src='$pjimage'></td>\n";
echo "<td colspan='3' width='100%'><nobr>".$project['project_name']." (".$request['project_id'].")</nobr></td></tr>\n";
echo "<tr><td colspan='2' width='100%'><nobr><strong>"._NETWORK_REQUESTINFO."</strong></nobr></td>\n";
echo "<td align='center'><strong>"._NETWORK_STATUS."</strong></td>\n";
echo "<td align='center'><strong>"._NETWORK_TYPE."</strong></td></tr>\n";
$pjimage = pjimage("request.png", $titanium_module_name);
echo "<tr><td align='center'><img src='$pjimage'></td><td width='100%'><nobr>".$request['request_name']."</nobr></td>\n";
echo "<td align='center'><nobr>".$requeststatus['status_name']."</nobr></td>\n";
echo "<td align='center'><nobr>".$requesttype['type_name']."</nobr></td></tr>\n";
if($request['request_description'] != ""){
  $pjimage = pjimage("description.png", $titanium_module_name);
  echo "<tr><td align='center' valign='top'><img src='$pjimage'></td>\n";
  echo "<td colspan='3' width='100%'>".nl2br($request['request_description'])."</td></tr>";
}
$pjimage = pjimage("requester.png", $titanium_module_name);
echo "<tr><td align='center'><img src='$pjimage'></td>\n";
echo "<td colspan='3' width='100%'><nobr>"._NETWORK_REQUESTEDBY.": <strong>".$request['submitter_email']."</strong></nobr></td></tr>\n";
if($request['date_submitted'] != '0'){
  $submit_date = date($pj_config['request_date_format'], $request['date_submitted']);
  $pjimage = pjimage("date.png", $titanium_module_name);
  echo "<tr><td align='center'><img src='$pjimage'></td>\n";
  echo "<td colspan='3' width=100%><nobr>"._NETWORK_SUBMITTED.": <strong>$submit_date</strong></nobr></td></tr>\n";
}
if($request['date_modified'] != '0'){
  $modify_date = date($pj_config['request_date_format'], $request['date_modified']);
  $pjimage = pjimage("date.png", $titanium_module_name);
  echo "<tr><td align='center'><img src='$pjimage'></td>\n";
  echo "<td colspan='3' width='100%'><nobr>"._NETWORK_MODIFIED.": <strong>$modify_date</strong></nobr></td></tr>\n";
}
$memberresult = $titanium_db2->sql_query("SELECT `member_id` FROM `".$network_prefix."_requests_members` WHERE `request_id`='$request_id' ORDER BY `member_id`");
$member_total = $titanium_db2->sql_numrows($memberresult);
echo "<tr><td colspan='4' width='100%'><nobr><strong>"._NETWORK_REQUESTMEMBERS."</strong></nobr></td></tr>\n";
if($member_total != 0){
  while(list($member_id) = $titanium_db2->sql_fetchrow($memberresult)) {
    $pjimage = pjimage("member.png", $titanium_module_name);
    $member = pjmember_info($member_id);
    echo "<tr><td><img src='$pjimage'></td><td colspan='3' width='100%'>".$member['member_name']." (".$member['member_email'].")</td></tr>\n";
  }
} else {
  echo "<tr><td align='center' colspan='4' width='100%'><nobr>"._NETWORK_NOREQUESTMEMBERS."</nobr></td></tr>\n";
}
echo "</table>\n";
echo "<br />\n";
$commentresult = $titanium_db2->sql_query("SELECT `comment_id` FROM `".$network_prefix."_requests_comments` WHERE `request_id`='$request_id' ORDER BY `date_commented` asc");
$comment_total = $titanium_db2->sql_numrows($commentresult);
echo "<table border='1' cellpadding='2' cellspacing='0' width='100%'>\n";
echo "<tr><td width='100%'><nobr><strong>"._NETWORK_COMMENTS."</strong></nobr></td><tr>\n";
if($comment_total > 0){
  while(list($comment_id) = $titanium_db2->sql_fetchrow($commentresult)) {
    $comment = pjrequestcomment_info($comment_id);
    $comment_date = date($pj_config['request_date_format'], $comment_date);
    echo "<tr><td><nobr><strong>".$comment['commenter_email']." @ $comment_date</strong>";
    echo "</nobr></td></tr>\n";
    echo "<tr><td>".nl2br($comment['comment_description'])."</td></tr>\n";
  }
} else {
  echo "<tr><td align='center'><nobr>"._NETWORK_NOREQUESTCOMMENTS."</nobr></td></tr>\n";
}
echo "</table>\n";
echo "</body>\n";
echo "</html>\n";

?>