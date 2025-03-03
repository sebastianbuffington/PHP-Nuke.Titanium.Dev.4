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
$pagetitle = _NETWORK_TITLE.' v'.$pj_config['version_number'].' - '._NETWORK_REQUESTS.': '._NETWORK_REQUESTLIST;
if(!$page) $page = 1;
if(!$per_page) $per_page = 25;
if(!$column) $column = "project_id";
if(!$direction) $direction = "desc";
include_once(NUKE_BASE_DIR.'header.php');

pjadmin_menu(_NETWORK_REQUESTS.': '._NETWORK_REQUESTLIST);
//echo "<br />\n";
OpenTable();
echo "<table width='100%' border='1' cellspacing='0' cellpadding='2'>\n";
echo "<tr><td colspan='3' bgcolor='$bgcolor2'><nobr><strong>"._NETWORK_REQUESTOPTIONS."</strong></nobr></td></tr>\n";
$requestrows = $titanium_db2->sql_numrows($titanium_db2->sql_query("SELECT `request_id` FROM `".$network_prefix."_requests`"));
$pjimage = pjimage("stats.png", $titanium_module_name);
echo "<tr><td><img src='$pjimage'></td><td colspan='2' width='100%'><nobr>"._NETWORK_TOTALREQUESTS.": <strong>$requestrows</strong></nobr></td></tr>\n";
echo "</table>\n";
//CloseTable();
//echo "<br />\n";
$total_phpbb2_pages = ($requestrows / $per_page);
$total_phpbb2_pages_quotient = ($requestrows % $per_page);
if($total_phpbb2_pages_quotient != 0){ $total_phpbb2_pages = ceil($total_phpbb2_pages); }
$phpbb2_start_list = ($per_page * ($page-1));
$phpbb2_end_list = $per_page;
//OpenTable();
echo "<table width='100%' border='1' cellspacing='0' cellpadding='2'>\n";
echo "<tr><td bgcolor='$bgcolor2' width='100%' colspan='2'><nobr><strong>"._NETWORK_REQUESTLIST."</strong></nobr></td>\n";
echo "<td align='center' bgcolor='$bgcolor2'><strong>"._NETWORK_PROJECT."</strong></td>\n";
echo "<td align='center' bgcolor='$bgcolor2'><strong>"._NETWORK_STATUS."</strong></td>\n";
echo "<td align='center' bgcolor='$bgcolor2'><strong>"._NETWORK_TYPE."</strong></td>\n";
echo "<td align='center' bgcolor='$bgcolor2'><strong>"._NETWORK_FUNCTIONS."</strong></td></tr>\n";
if($requestrows > 0){
    $reviewresult = $titanium_db2->sql_query("SELECT `request_id`, `request_name`, `project_id`, `type_id`, `status_id` FROM `".$network_prefix."_requests` ORDER BY `$column` $direction LIMIT $phpbb2_start_list, $phpbb2_end_list");
    while(list($request_id, $request_name, $project_id, $type_id, $status_id) = $titanium_db2->sql_fetchrow($reviewresult)){
    $status = pjrequeststatus_info($status_id);
    $project = pjproject_info($project_id);
    $type = pjrequesttype_info($type_id);
    $members = $titanium_db2->sql_numrows($titanium_db2->sql_query("SELECT `member_id` FROM `".$network_prefix."_requests_members` WHERE `request_id`='$request_id'"));
    $pjimage = pjimage("request.png", $titanium_module_name);
    if(empty($request_name)) { $request_name = "----------"; }
    echo "<tr><td><img src='$pjimage'></td><td width='100%'>$request_name</td>\n";
        echo "<td align='center'><nobr><a href='".$admin_file.".php?op=ProjectList'>".$project['project_name']."</a></nobr></td>\n";
    if(empty($status['status_name'])){ $status['status_name'] = _NETWORK_NA; }
    echo "<td align=center><a href='".$admin_file.".php?op=RequestStatusList'>".$status['status_name']."</a></td>\n";
    if(empty($type['type_name'])){ $type['type_name'] = _NETWORK_NA; }
    echo "<td align=center><nobr><a href='".$admin_file.".php?op=RequestTypeList'>".$type['type_name']."</a></td>\n";
    echo "<td align=center><nobr>[ <a href='".$admin_file.".php?op=RequestEdit&amp;request_id=$request_id'>"._NETWORK_EDIT."</a>";
    echo " | <a href='".$admin_file.".php?op=RequestRemove&amp;request_id=$request_id'>"._NETWORK_DELETE."</a> ]</td></tr>\n";
    }
    echo "<form method='post' action='".$admin_file.".php'>\n";
    echo "<input type='hidden' name='op' value='RequestList'>\n";
    echo "<input type='hidden' name='column' value='$column'>\n";
    echo "<input type='hidden' name='direction' value='$direction'>\n";
    echo "<input type='hidden' name='per_page' value='$per_page'>\n";
    echo "<tr><td colspan='6' width='100%' bgcolor='$bgcolor2'>\n";

    echo "<table width='100%'><tr><td bgcolor='$bgcolor2'><strong>"._NETWORK_PAGE."</strong> <select name='page' onChange='submit()'>\n";
    for($i=1; $i<=$total_phpbb2_pages; $i++){
    if($i==$page){ $sel = "selected"; } else { $sel = ""; }
    echo "<option value='$i' $sel>$i</option>\n";
    }
    echo "</select> <strong>"._NETWORK_OF." $total_phpbb2_pages</strong></td>\n";
    echo "</form>\n";

    echo "<form method='post' action='".$admin_file.".php'>\n";
    echo "<input type='hidden' name='op' value='RequestList'>\n";
    echo "<td align='right' bgcolor='$bgcolor2'><strong>"._NETWORK_SORT.":</strong> <select name='column'>\n";
    if($column == "request_id") $selcolumn1 = "selected";
    echo "<option value='request_id' $selcolumn1>"._NETWORK_REQUESTID."</option>\n";
    if($column == "project_id") $selcolumn2 = "selected";
    echo "<option value='project_id' $selcolumn2>"._NETWORK_PROJECTID."</option>\n";
    if($column == "status_id") $selcolumn3 = "selected";
    echo "<option value='status_id' $selcolumn3>"._NETWORK_STATUSID."</option>\n";
    if($column == "priority_id") $selcolumn4 = "selected";
    echo "<option value='type_id' $selcolumn4>"._NETWORK_TYPEID."</option>\n";
    echo "</select> <select name='direction'>\n";
    if($direction == "asc") $seldirection1 = "selected";
    echo "<option value='asc' $seldirection1>"._NETWORK_ASC."</option>\n";
    if($direction == "desc") $seldirection2 = "selected";
    echo "<option value='desc' $seldirection2>"._NETWORK_DESC."</option>\n";
    echo "</select> <select name='per_page'>\n";
    if($per_page == 5) $selperpage1 = "selected";
    echo "<option value='5' $selperpage1>5</option>\n";
    if($per_page == 10) $selperpage2 = "selected";
    echo "<option value='10' $selperpage2>10</option>\n";
    if($per_page == 25) $selperpage3 = "selected";
    echo "<option value='25' $selperpage3>25</option>\n";
    if($per_page == 50) $selperpage4 = "selected";
    echo "<option value='50' $selperpage4>50</option>\n";
    if($per_page == 100) $selperpage5 = "selected";
    echo "<option value='100' $selperpage5>100</option>\n";
    if($per_page == 200) $selperpage6 = "selected";
    echo "<option value='200' $selperpage6>200</option>\n";
    echo "</select> <input type='submit' value='"._NETWORK_SORT."'></td>\n";
    echo "</form>\n";
    echo "</tr></table>\n";

    echo "</td></tr>\n";
} else {
    echo "<tr><td colspan=6 width='100%' align='center'>"._NETWORK_NOPROJECTREQUESTS."</td></tr>\n";
}
echo "</table>\n";
CloseTable();
pj_copy();
include_once(NUKE_BASE_DIR.'footer.php');

?>