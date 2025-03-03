<?php # JOHN 3:16 #
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/

/***************************************************************************
 *                               viewforum.php
 *                            -------------------
 *   update               : Fiday, May 21, 2021
 *   copyright            : (C) The 86it Developers Network
 *   email                : support@86it.us
 *
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   Id: viewforum.php,v 1.139.2.12 2004/03/13 15:08:23 acydburn Exp
 *
 ***************************************************************************/

/***************************************************************************
* phpbb2 forums port version 2.0.5 (c) 2003 - Nuke Cops (http://nukecops.com)
*
* Ported by Nuke Cops to phpbb2 standalone 2.0.5 Test
* and debugging completed by the Elite Nukers and site members.
*
* You run this package at your sole risk. Nuke Cops and affiliates cannot
* be held liable if anything goes wrong. You are advised to test this
* package on a development system. Backup everything before implementing
* in a production environment. If something goes wrong, you can always
* backout and restore your backups.
*
* Installing and running this also means you agree to the terms of the AUP
* found at Nuke Cops.
*
* This is version 2.0.5 of the phpbb2 forum port for PHP-Nuke. Work is based
* on Tom Nitzschner's forum port version 2.0.6. Tom's 2.0.6 port was based
* on the phpbb2 standalone version 2.0.3. Our version 2.0.5 from Nuke Cops is
* now reflecting phpbb2 standalone 2.0.5 that fixes some bugs and the
* invalid_session error message.
***************************************************************************/

/***************************************************************************
 *   This file is part of the phpBB2 port to Nuke 6.0 (c) copyright 2002
 *   by Tom Nitzschner (tom@toms-home.com)
 *   http://bbtonuke.sourceforge.net (or http://www.toms-home.com)
 *
 *   As always, make a backup before messing with anything. All code
 *   release by me is considered sample code only. It may be fully
 *   functual, but you use it at your own risk, if you break it,
 *   you get to fix it too. No waranty is given or implied.
 *
 *   Please post all questions/request about this port on http://bbtonuke.sourceforge.net first,
 *   then on my site. All original header code and copyright messages will be maintained
 *   to give credit where credit is due. If you modify this, the only requirement is
 *   that you also maintain all original copyright messages. All my work is released
 *   under the GNU GENERAL PUBLIC LICENSE. Please see the README for more information.
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
-=[Mod]=-
      Attachment Mod                           v2.4.1       07/20/2005
      Advanced Username Color                  v1.0.5       06/11/2005
      At a Glance                              v2.2.1       06/12/2005
      Global Announcements                     v1.2.8       06/13/2005
      Smilies in Topic Titles                  v1.0.0       06/14/2005
      Topic Cement                             v1.0.3       06/15/2005
      Topic display order                      v1.0.2       06/15/2005
      Separate Announcements & Sticky          v2.0.0a      06/24/2005
      At a Glance Options                      v1.0.0       08/17/2005
      Customized Topic Status                  v1.0.0       08/25/2005
      Smilies in Topic Titles Toggle           v1.0.0       09/10/2005
	  Forum Icons                              v1.0.4
	  Post Icons                               v1.0.1
 ************************************************************************/

if (!defined('MODULE_FILE')) 
exit("You can't access this file directly...");

if($popup != "1"):
 $titanium_module_name = basename(dirname(__FILE__));
 require("modules/".$titanium_module_name."/nukebb.php");
else:
 $phpbb2_root_path = NUKE_FORUMS_DIR;
endif;

define('IN_PHPBB2', true);
include($phpbb2_root_path . 'extension.inc');
include($phpbb2_root_path . 'common.'.$phpEx);

# Mod: Post Icons v1.0.1 START
include('includes/posting_icons.'. $phpEx);
# Mod: Post Icons v1.0.1 END

# Mod: Separate Announcements & Sticky v2.0.0a START
include('includes/functions_separate.'.$phpEx);
# Mod: Separate Announcements & Sticky v2.0.0a END

# Mod: Smilies in Topic Titles v1.0.0 START
include('includes/bbcode.' .$phpEx);
# Mod: Smilies in Topic Titles v1.0.0 END

# Start initial var setup
if(isset($HTTP_GET_VARS[POST_FORUM_URL]) || isset($HTTP_POST_VARS[POST_FORUM_URL]))
$phpbb2_forum_id = intval(isset($HTTP_GET_VARS[POST_FORUM_URL]) ? $HTTP_GET_VARS[POST_FORUM_URL] : $HTTP_POST_VARS[POST_FORUM_URL]);
else
$phpbb2_forum_id = '';

$phpbb2_start = (isset($HTTP_GET_VARS['start']) ? intval($HTTP_GET_VARS['start']) : 0);
$phpbb2_start = ($phpbb2_start < 0) ? 0 : $phpbb2_start;

if(isset($HTTP_GET_VARS['mark']) || isset($HTTP_POST_VARS['mark']))
$phpbb2_mark_read = (isset($HTTP_POST_VARS['mark'])) ? $HTTP_POST_VARS['mark'] : $HTTP_GET_VARS['mark'];
else
$phpbb2_mark_read = '';
# End initial var setup

# Check if the user has actually sent a forum ID with his/her request
# If not give them a nice error page.
if(!empty($phpbb2_forum_id)):
        $sql = "SELECT *
                FROM ".FORUMS_TABLE."
                WHERE forum_id = '$phpbb2_forum_id'";
        if(!($result = $titanium_db->sql_query($sql))):
          message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
        endif;
else:
  message_die(GENERAL_MESSAGE, 'Forum_not_exist');
endif;

# If the query doesn't return any rows this isn't a valid forum. Inform
# the user.
if(!($forum_row = $titanium_db->sql_fetchrow($result)))
message_die(GENERAL_MESSAGE, 'Forum_not_exist');

# Start session management
$userdata = titanium_session_pagestart($titanium_user_ip, $phpbb2_forum_id);
titanium_init_userprefs($userdata);
# End session management

# Start auth check
$phpbb2_is_auth = array();
$phpbb2_is_auth = auth(AUTH_ALL, $phpbb2_forum_id, $userdata, $forum_row);

if(!$phpbb2_is_auth['auth_read'] || !$phpbb2_is_auth['auth_view']):
        if (!$userdata['session_logged_in']):
                $redirect = POST_FORUM_URL . "=$phpbb2_forum_id" . ( ( isset($phpbb2_start) ) ? "&start=$phpbb2_start" : '' );
                redirect_titanium(append_titanium_sid("login.$phpEx?redirect=viewforum.$phpEx&$redirect", true));
        endif;
        # The user is not authed to read this forum ...
        $message = ( !$phpbb2_is_auth['auth_view'] ) ? $titanium_lang['Forum_not_exist'] : sprintf($titanium_lang['Sorry_auth_read'], $phpbb2_is_auth['auth_read_type']);
        message_die(GENERAL_MESSAGE, $message);
endif;
# End of auth check

# Password check
if(!$phpbb2_is_auth['auth_mod'] && $userdata['user_level'] != ADMIN):
	$redirect = str_replace("&amp;", "&", preg_replace('#.*?([a-z]+?\.' . $phpEx . '.*?)$#i', '\1', htmlspecialchars($HTTP_SERVER_VARS['REQUEST_URI'])));
	if($HTTP_POST_VARS['cancel']):
		redirect_titanium(append_titanium_sid("index.$phpEx"));
	elseif($HTTP_POST_VARS['pass_login']):
		if($forum_row['forum_password'] != '')
		password_check('forum', $phpbb2_forum_id, $HTTP_POST_VARS['password'], $redirect);
	endif;
	$passdata = (isset($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'].'_fpass'])) ? unserialize(stripslashes($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'].'_fpass'])) : '';
	if($forum_row['forum_password'] != '' && ($passdata[$phpbb2_forum_id] != md5($forum_row['forum_password'])))
	password_box('forum', $redirect);
endif;
# END: Password check

# Handle marking posts
if($phpbb2_mark_read == 'topics'):

    # Mod: Simple Subforums v1.0.1 START
	$mark_list = ( isset($HTTP_GET_VARS['mark_list']) ) ? explode(',', $HTTP_GET_VARS['mark_list']) : array($phpbb2_forum_id);
	$old_forum_id = $phpbb2_forum_id;
    # Mod: Simple Subforums v1.0.1 END

        if($userdata['session_logged_in']):
                $sql = "SELECT p.post_time AS last_post
                        FROM (" . POSTS_TABLE . " p, " . TOPICS_TABLE . " t)
                        WHERE t.forum_id = $phpbb2_forum_id
                        AND t.topic_last_post_id = p.post_id
                        ORDER BY t.topic_last_post_id DESC LIMIT 1";
                if( !($result = $titanium_db->sql_query($sql)))
                message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);

                if($row = $titanium_db->sql_fetchrow($result)):
                        $phpbb2_tracking_forums = ( isset($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'] . '_f']) ) ? unserialize($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'] . '_f']) : array();
                        $phpbb2_tracking_topics = ( isset($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'] . '_t']) ) ? unserialize($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'] . '_t']) : array();

                        if((count($phpbb2_tracking_forums) + count($phpbb2_tracking_topics)) >= 150 && empty($phpbb2_tracking_forums[$phpbb2_forum_id])):
                          asort($phpbb2_tracking_forums);
                          unset($phpbb2_tracking_forums[key($phpbb2_tracking_forums)]);
                        endif;

                        if($row['last_post'] > $userdata['user_lastvisit']):
                          $phpbb2_tracking_forums[$phpbb2_forum_id] = time();
				          # Mod: Simple Subforums v1.0.1 START
				          //setcookie($phpbb2_board_config['cookie_name'] . '_f', serialize($phpbb2_tracking_forums), 0, $phpbb2_board_config['cookie_path'], 
					      //$phpbb2_board_config['cookie_domain'], $phpbb2_board_config['cookie_secure']);
				          $set_cookie = true;
					      if( isset($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'] . '_f']) )
					      $HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'] . '_f'] = serialize($phpbb2_tracking_forums);
				          # Mod: Simple Subforums v1.0.1 END
                       endif;
                endif;

                # Mod: Simple Subforums v1.0.1 START
		        if($set_cookie)
		        setcookie($phpbb2_board_config['cookie_name'] . '_f', serialize($phpbb2_tracking_forums), 0, $phpbb2_board_config['cookie_path'], $phpbb2_board_config['cookie_domain'], $phpbb2_board_config['cookie_secure']);

		        $phpbb2_forum_id = $old_forum_id;
                # Mod: Simple Subforums v1.0.1 END

                $phpbb2_template->assign_vars(array(
                        'META' => '<meta http-equiv="refresh" content="3;url='.append_titanium_sid("viewforum.$phpEx?".POST_FORUM_URL."=$phpbb2_forum_id").'">')
                );
        endif;

        $message = $titanium_lang['Topics_marked_read'].'<br /><br />'.sprintf($titanium_lang['Click_return_forum'],'<a href="'.append_titanium_sid("viewforum.$phpEx?".POST_FORUM_URL."=$phpbb2_forum_id").'">','</a>');
        message_die(GENERAL_MESSAGE, $message);
endif;
# End handle marking posts

$phpbb2_tracking_topics = (isset($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'].'_t'])) ? unserialize($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'].'_t']) : '';
$phpbb2_tracking_forums = (isset($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'].'_f']) ) ? unserialize($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'].'_f']) : '';

# Do the forum Prune
if($phpbb2_is_auth['auth_mod'] && $phpbb2_board_config['prune_enable']):
  if($forum_row['prune_next'] < time() && $forum_row['prune_enable']):
     include("includes/prune.php");
     require("includes/functions_admin.php");
     auto_prune($phpbb2_forum_id);
  endif;
endif;
# End of forum prune

# Obtain list of moderators of each forum
# First users, then groups ... broken into two queries
$sql = "SELECT u.user_id, u.username
        FROM (" . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u)
        WHERE aa.forum_id = '$phpbb2_forum_id'
                AND aa.auth_mod = " . TRUE . "
                AND g.group_single_user = '1'
                AND ug.group_id = aa.group_id
                AND g.group_id = aa.group_id
                AND u.user_id = ug.user_id
        GROUP BY u.user_id, u.username
        ORDER BY u.user_id";

if(!($result = $titanium_db->sql_query($sql)))
{
  message_die(GENERAL_ERROR, 'Could not query forum moderator information', '', __LINE__, __FILE__, $sql);
}

$moderators = array();

while($row = $titanium_db->sql_fetchrow($result)):
    # Mod: Advanced Username Color v1.0.5 START
    $moderators[] = '<a href="'.append_titanium_sid("profile.$phpEx?mode=viewprofile&amp;".POST_USERS_URL."=".$row['user_id']).'">'.UsernameColor($row['username']).'</a>';
    # Mod: Advanced Username Color v1.0.5 START
endwhile;

$sql = "SELECT g.group_id, g.group_name
        FROM (".AUTH_ACCESS_TABLE." aa, ".USER_GROUP_TABLE." ug, ".GROUPS_TABLE." g)
        WHERE aa.forum_id = '$phpbb2_forum_id'
                AND aa.auth_mod = ".TRUE."
                AND g.group_single_user = '0'
                AND g.group_type <> ".GROUP_HIDDEN."
                AND ug.group_id = aa.group_id
                AND g.group_id = aa.group_id
        GROUP BY g.group_id, g.group_name
        ORDER BY g.group_id";

if(!($result = $titanium_db->sql_query($sql)))
message_die(GENERAL_ERROR,'Could not query forum moderator information', '', __LINE__, __FILE__, $sql);

while($row = $titanium_db->sql_fetchrow($result)):
  $moderators[] = '<a href="'.append_titanium_sid("groupcp.$phpEx?".POST_GROUPS_URL."=".$row['group_id']).'">'.GroupColor($row['group_name']).'</a>';
endwhile;

$l_phpbb2_moderators = (count($moderators) == 1) ? $titanium_lang['Moderator'] : $titanium_lang['Moderators'];
$phpbb2_forum_moderators = (count($moderators)) ? implode(', ', $moderators) : $titanium_lang['None'];
unset($moderators);

# Generate a 'Show topics in previous x days' select box. If the topicsdays var is sent
# then get it's value, find the number of topics with dates newer than it (to properly
# handle pagination) and alter the main query
$previous_days = array(0, 1, 7, 14, 30, 90, 180, 364);
$previous_days_text = array($titanium_lang['All_Topics'], $titanium_lang['1_Day'], $titanium_lang['7_Days'], $titanium_lang['2_Weeks'], $titanium_lang['1_Month'], $titanium_lang['3_Months'], $titanium_lang['6_Months'], $titanium_lang['1_Year']);

if(!empty($HTTP_POST_VARS['topicdays']) || !empty($HTTP_GET_VARS['topicdays'])):
        $topic_days = (!empty($HTTP_POST_VARS['topicdays'])) ? intval($HTTP_POST_VARS['topicdays']) : intval($HTTP_GET_VARS['topicdays']);
        $min_topic_time = time() - ($topic_days * 86400);
        $sql = "SELECT COUNT(t.topic_id) AS forum_topics
                FROM (".TOPICS_TABLE." t, ".POSTS_TABLE." p)
                WHERE t.forum_id = '$phpbb2_forum_id'
                        AND p.post_id = t.topic_last_post_id
                        AND p.post_time >= '$min_topic_time'";
        if (!($result = $titanium_db->sql_query($sql))):
        message_die(GENERAL_ERROR,'Could not obtain limited topics count information','', __LINE__, __FILE__,$sql);
        endif;
        
		$row = $titanium_db->sql_fetchrow($result);

        $phpbb2_topics_count = ($row['forum_topics']) ? $row['forum_topics'] : 1;
        $limit_topics_time = "AND p.post_time >= $min_topic_time";

        if(!empty($HTTP_POST_VARS['topicdays'])):
        $phpbb2_start = 0;
		endif;
else:
  $phpbb2_topics_count = ($forum_row['forum_topics']) ? $forum_row['forum_topics'] : 1;
  $limit_topics_time = '';
  $topic_days = 0;
endif;

$select_topic_days = '<select name="topicdays">';

for($i = 0; $i < count($previous_days); $i++):
  $selected = ($topic_days == $previous_days[$i]) ? ' selected="selected"' : '';
  $select_topic_days .= '<option value="'.$previous_days[$i].'"'.$selected.'>'.$previous_days_text[$i].'</option>';
endfor;

$select_topic_days .= '</select>';

# Mod: Global Announcements v1.2.8 START
# All GLOBAL announcement data, this keeps GLOBAL announcements
# on each viewforum page ...

 # Mod: Topic Cement v1.0.3 START
 $sql = "SELECT t.*, u.username, u.user_id, u2.username as user2, u2.user_id as id2, p.post_time, p.post_username
   FROM (".TOPICS_TABLE." t, ".USERS_TABLE." u, ".POSTS_TABLE." p, ".USERS_TABLE." u2)
   WHERE t.topic_poster = u.user_id
      AND p.post_id = t.topic_last_post_id
      AND p.poster_id = u2.user_id
      AND t.topic_type = ".POST_GLOBAL_ANNOUNCE."
   ORDER BY t.topic_priority DESC, t.topic_last_post_id DESC ";
 # Mod: Topic Cement v1.0.3 END

if(!$result = $titanium_db->sql_query($sql))
message_die(GENERAL_ERROR,"Couldn't obtain topic information","", __LINE__, __FILE__,$sql);
$topic_rowset = array();
$total_phpbb2_announcements = 0;
while($row = $titanium_db->sql_fetchrow($result)):
   $topic_rowset[] = $row;
   $total_phpbb2_announcements++;
endwhile;
$titanium_db->sql_freeresult($result);
# Mod: Global Announcements v1.2.8 END

# All announcement data, this keeps announcements
# on each viewforum page ...
$sql = "SELECT t.*, u.username, u.user_id, u2.username as user2, u2.user_id as id2, p.post_time, p.post_username
        FROM (".TOPICS_TABLE." t, ".USERS_TABLE." u, ".POSTS_TABLE." p, ".USERS_TABLE." u2)
        WHERE t.forum_id = '$phpbb2_forum_id'
                AND t.topic_poster = u.user_id
                AND p.post_id = t.topic_last_post_id
                AND p.poster_id = u2.user_id
                AND t.topic_type = ".POST_ANNOUNCE."
        ORDER BY t.topic_last_post_id DESC ";

if ( !($result = $titanium_db->sql_query($sql)) )
message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);

/*****[BEGIN]******************************************
 [ Mod:     Global Announcements               v1.2.8 ] HERE WE GO AGAIN WITH UNCOMMENTED CHANGES? WHY? WHO?
 ******************************************************/
//$topic_rowset = array();
//$total_phpbb2_announcements = 0;
/*****[END]********************************************
 [ Mod:     Global Announcements               v1.2.8 ] HERE WE GO AGAIN WITH UNCOMMENTED CHANGES? WHY? WHO?
 ******************************************************/

while($row = $titanium_db->sql_fetchrow($result)):
  $topic_rowset[] = $row;
  $total_phpbb2_announcements++;
endwhile;
$titanium_db->sql_freeresult($result);

# Grab all the basic data (all topics except announcements)
# for this forum

# Mod: Topic display order v1.0.2 START
$dft_sort = $forum_row['forum_display_sort'];
$dft_order = $forum_row['forum_display_order'];

# Sort def
$sort_value = $dft_sort;
if(isset($HTTP_GET_VARS['sort']) || isset($HTTP_POST_VARS['sort']))
$sort_value = isset($HTTP_GET_VARS['sort']) ? intval($HTTP_GET_VARS['sort']) : intval($HTTP_POST_VARS['sort']);
$sort_list = '<select name="sort">'.get_forum_display_sort_option($sort_value,'list','sort').'</select>';

# Order def
$order_value = $dft_order;
if(isset($HTTP_GET_VARS['order']) || isset($HTTP_POST_VARS['order']))
$order_value = isset($HTTP_GET_VARS['order']) ? intval($HTTP_GET_VARS['order']) : intval($HTTP_POST_VARS['order']);
$order_list = '<select name="order">'.get_forum_display_sort_option($order_value,'list','order').'</select>';

# display
$s_display_order = '&nbsp;'.$titanium_lang['Sort_by'].':&nbsp;'.$sort_list.$order_list.'&nbsp;';

# selected method
$sort_method = get_forum_display_sort_option($sort_value,'field','sort');
$order_method = get_forum_display_sort_option($order_value,'field','order');
# Mod: Topic display order v1.0.2 END


# Mod: Global Announcements v1.2.8 START
# Mod: Topic Cement v1.0.3 START
# Mod: Topic display order v1.0.2 START
$sql = "SELECT t.*, u.username, u.user_id, u2.username as user2, u2.username as user2, u2.user_id as id2, p.post_username, p2.post_username AS post_username2, p2.post_time
        FROM (".TOPICS_TABLE." t, ".USERS_TABLE." u, ".POSTS_TABLE." p, ".POSTS_TABLE." p2, ".USERS_TABLE." u2)
        WHERE t.forum_id = '$phpbb2_forum_id'
                AND t.topic_poster = u.user_id
                AND p.post_id = t.topic_first_post_id
                AND p2.post_id = t.topic_last_post_id
                AND u2.user_id = p2.poster_id
                AND t.topic_type <> ".POST_ANNOUNCE."
                AND t.topic_type <> ".POST_GLOBAL_ANNOUNCE."
                $limit_topics_time
        ORDER BY t.topic_type DESC, t.topic_priority DESC, $sort_method $order_method, t.topic_last_post_id DESC
        LIMIT $phpbb2_start, ".$phpbb2_board_config['topics_per_page'];
# Mod: Global Announcements v1.2.8 END
# Mod: Topic Cement v1.0.3 END
# Mod: Topic display order v1.0.2 END

if(!($result = $titanium_db->sql_query($sql)))
message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);

$total_phpbb2_topics = 0;
while($row = $titanium_db->sql_fetchrow($result)):
    $topic_rowset[] = $row;
    $total_phpbb2_topics++;
endwhile;
$titanium_db->sql_freeresult($result);

# Total topics ...
$total_phpbb2_topics += $total_phpbb2_announcements;

# Mod: Separate Announcements & Sticky v2.0.0a START
$dividers = get_dividers($topic_rowset);
# Mod: Separate Announcements & Sticky v2.0.0a END

# Define censored word matches
$orig_word = array();
$replacement_word = array();
obtain_word_list($orig_word, $replacement_word);

# Post URL generation for templating vars
$phpbb2_template->assign_vars(array(
    'L_DISPLAY_TOPICS' => $titanium_lang['Display_topics'],
    'U_POST_NEW_TOPIC' => append_titanium_sid("posting.$phpEx?mode=newtopic&amp;".POST_FORUM_URL."=$phpbb2_forum_id"),
    'S_SELECT_TOPIC_DAYS' => $select_topic_days,
    'S_POST_DAYS_ACTION' => append_titanium_sid("viewforum.$phpEx?".POST_FORUM_URL."=".$phpbb2_forum_id."&amp;start=$phpbb2_start"))
);

# User authorisation levels output
$s_auth_can = (($phpbb2_is_auth['auth_post']) ? $titanium_lang['Rules_post_can'] : $titanium_lang['Rules_post_cannot']).'<br />';
$s_auth_can .= (($phpbb2_is_auth['auth_reply']) ? $titanium_lang['Rules_reply_can'] : $titanium_lang['Rules_reply_cannot']).'<br />';
$s_auth_can .= (($phpbb2_is_auth['auth_edit']) ? $titanium_lang['Rules_edit_can'] : $titanium_lang['Rules_edit_cannot']).'<br />';
$s_auth_can .= (($phpbb2_is_auth['auth_delete']) ? $titanium_lang['Rules_delete_can'] : $titanium_lang['Rules_delete_cannot']).'<br />';
$s_auth_can .= (($phpbb2_is_auth['auth_vote']) ? $titanium_lang['Rules_vote_can'] : $titanium_lang['Rules_vote_cannot'] ).'<br />';

# Mod: Attachment Mod v2.4.1 START
attach_build_auth_levels($phpbb2_is_auth, $s_auth_can);
# Mod: Attachment Mod v2.4.1 END

if($phpbb2_is_auth['auth_mod'])
$s_auth_can .= sprintf($titanium_lang['Rules_moderate'], '<a href="'.append_titanium_sid("modcp.$phpEx?".POST_FORUM_URL."=$phpbb2_forum_id").'">', '</a>');

# Mozilla navigation bar
$titanium_nav_links['up'] = array(
        'url' => append_titanium_sid('index.'.$phpEx),
        'title' => sprintf($titanium_lang['Forum_Index'], $phpbb2_board_config['sitename'])
);

# Dump out the page header and load viewforum template
define('SHOW_ONLINE', true);
$phpbb2_page_title = $titanium_lang['View_forum'].' - '.$forum_row['forum_name'];
include("includes/page_header.$phpEx");
$phpbb2_template->set_filenames(array(
    'body' => 'viewforum_body.tpl') 
);

# Mod: Simple Subforums v1.0.1 START
$all_forums = array();
make_jumpbox_ref('viewforum.'.$phpEx, $phpbb2_forum_id, $all_forums);
# Mod: Simple Subforums v1.0.1 END

$look_in_themes_dir_for_forum_icons = forum_icon_img_path($forum_row['forum_icon'], 'Forums');   

$phpbb2_template->assign_vars(array(
        'FORUM_ID' => $phpbb2_forum_id,
        'FORUM_NAME' => $forum_row['forum_name'],
        
		# Mod: Forum Icons v1.0.4 START 

		'FORUM_ICON_IMG' => ($forum_row['forum_icon']) ? '<img src="'.$look_in_themes_dir_for_forum_icons.'" 
		alt="'.$forum_row['forum_name'].'" title="'.$forum_row['forum_name'].'" />&nbsp;' : '',

        'MODERATORS' => $phpbb2_forum_moderators,
        'POST_IMG' => ( $forum_row['forum_status'] == FORUM_LOCKED ) ? $images['post_locked'] : $images['post_new'],
        'FOLDER_IMG' => $images['folder'],
        'FOLDER_NEW_IMG' => $images['folder_new'],
        'FOLDER_HOT_IMG' => $images['folder_hot'],
        'FOLDER_HOT_NEW_IMG' => $images['folder_hot_new'],
        'FOLDER_LOCKED_IMG' => $images['folder_locked'],
        'FOLDER_LOCKED_NEW_IMG' => $images['folder_locked_new'],
        'FOLDER_STICKY_IMG' => $images['folder_sticky'],
        'FOLDER_STICKY_NEW_IMG' => $images['folder_sticky_new'],
        'FOLDER_ANNOUNCE_IMG' => $images['folder_announce'],
        'FOLDER_ANNOUNCE_NEW_IMG' => $images['folder_announce_new'],

        # Mod: Global Announcements v1.2.8 START
        'FOLDER_GLOBAL_ANNOUNCE_IMG' => $images['folder_global_announce'],
        'FOLDER_GLOBAL_ANNOUNCE_NEW_IMG' => $images['folder_global_announce_new'],
        # Mod: Global Announcements v1.2.8 END

        'L_TOPICS' => $titanium_lang['Topics'],
        'L_REPLIES' => $titanium_lang['Replies'],
        'L_VIEWS' => $titanium_lang['Views'],
        'L_POSTS' => $titanium_lang['Posts'],
        'L_LASTPOST' => $titanium_lang['Last_Post'],
        'L_MODERATOR' => $l_phpbb2_moderators,
        'L_MARK_TOPICS_READ' => $titanium_lang['Mark_all_topics'],
        'L_POST_NEW_TOPIC' => ( $forum_row['forum_status'] == FORUM_LOCKED ) ? $titanium_lang['Forum_locked'] : $titanium_lang['Post_new_topic'],
        'L_NO_NEW_POSTS' => $titanium_lang['No_new_posts'],
        'L_NEW_POSTS' => $titanium_lang['New_posts'],
        'L_NO_NEW_POSTS_LOCKED' => $titanium_lang['No_new_posts_locked'],
        'L_NEW_POSTS_LOCKED' => $titanium_lang['New_posts_locked'],
        'L_NO_NEW_POSTS_HOT' => $titanium_lang['No_new_posts_hot'],
        'L_NEW_POSTS_HOT' => $titanium_lang['New_posts_hot'],
        'L_ANNOUNCEMENT' => $titanium_lang['Post_Announcement'],

        # Mod: Global Announcements v1.2.8 START
        'L_GLOBAL_ANNOUNCEMENT' => $titanium_lang['Post_global_announcement'],
        # Mod: Global Announcements v1.2.8 END

        'L_STICKY' => $titanium_lang['Post_Sticky'],
        'L_POSTED' => $titanium_lang['Posted'],
        'L_JOINED' => $titanium_lang['Joined'],
        'L_AUTHOR' => $titanium_lang['Author'],
        'S_AUTH_LIST' => $s_auth_can,
        'U_VIEW_FORUM' => append_titanium_sid("viewforum.$phpEx?".POST_FORUM_URL."=$phpbb2_forum_id"),
        'U_MARK_READ' => append_titanium_sid("viewforum.$phpEx?".POST_FORUM_URL."=$phpbb2_forum_id&amp;mark=topics"))
);

# Mod: Simple Subforums v1.0.1 START
if($forum_row['forum_parent']):
	$phpbb2_parent_id = $forum_row['forum_parent'];
	for($i = 0; $i < count($all_forums); $i++):
		if($all_forums[$i]['forum_id'] == $phpbb2_parent_id):
			$phpbb2_template->assign_vars(array(
				'PARENT_FORUM'			=> 1,
				'U_VIEW_PARENT_FORUM'	=> append_titanium_sid("viewforum.$phpEx?" . POST_FORUM_URL .'=' . $all_forums[$i]['forum_id']),
				'PARENT_FORUM_NAME'		=> $all_forums[$i]['forum_name'],
				));
		endif;
	endfor;
else:
	$sub_list = array();
	for( $i = 0; $i < count($all_forums); $i++ ):
		if( $all_forums[$i]['forum_parent'] == $phpbb2_forum_id )
		$sub_list[] = $all_forums[$i]['forum_id'];
	endfor;

	if(count($sub_list)):
		$sub_list[] = $phpbb2_forum_id;
		$phpbb2_template->vars['U_MARK_READ'] .= '&amp;mark_list=' . implode(',', $sub_list);
	endif;
endif;

# assign additional variables for subforums mod
$phpbb2_template->assign_vars(array(
	'NUM_TOPICS' => $forum_row['forum_topics'],
	'CAN_POST' => $phpbb2_is_auth['auth_post'] ? 1 : 0,
	'L_FORUM' => $titanium_lang['Forum'],
));
# Mod: Simple Subforums v1.0.1 END
# End header


# Okay, lets dump out the page ...
# Mod: Topic display order v1.0.2 START
$phpbb2_template->assign_vars(array(
    'S_DISPLAY_ORDER' => $s_display_order,
    )
);
# Mod: Topic display order v1.0.2 END

if($total_phpbb2_topics):
   for($i = 0; $i < $total_phpbb2_topics; $i++):
   
     $topic_id = $topic_rowset[$i]['topic_id'];
     $topic_title = ( count($orig_word) ) ? preg_replace($orig_word, $replacement_word, $topic_rowset[$i]['topic_title']) : $topic_rowset[$i]['topic_title'];
     
	 # Mod: Smilies in Topic Titles v1.0.0 START
     # Mod: Smilies in Topic Titles Toggle v1.0.0 START
     $topic_title = ($phpbb2_board_config['smilies_in_titles']) ? smilies_pass($topic_title) : $topic_title;
     # Mod: Smilies in Topic Titles v1.0.0 END
     # Mod: Smilies in Topic Titles Toggle v1.0.0 END

     $replies = $topic_rowset[$i]['topic_replies'];

     # Mod: Post Icons v1.0.1 START
	 $type = $topic_rowset[$i]['topic_type'];

	 if($type == POST_NORMAL):
		if(!empty($topic_rowset[$i]['topic_calendar_time']))
			$type = POST_CALENDAR;
		if(!empty($topic_rowset[$i]['topic_pic_url']))
		    $type = POST_PICTURE;
	endif;

	$phpbb2_icon = get_icon_title($topic_rowset[$i]['topic_icon'], 1, $type);
	$phpbb2_icon_ID = $topic_rowset[$i]['topic_icon'];
    # Mod: Post Icons v1.0.1 END

    $topic_type = $topic_rowset[$i]['topic_type'];
    $topic_type = '';

    if($topic_rowset[$i]['topic_vote'])
    $topic_type .= $titanium_lang['Topic_Poll'].' ';

    if($topic_rowset[$i]['topic_status'] == TOPIC_MOVED):
       $topic_id = $topic_rowset[$i]['topic_moved_id'];
	   
	   # Mod: Customized Topic Status v1.0.0 START
       $topic_title = "" . $phpbb2_board_config['moved_view_open'] . " " . $topic_title . "" . $phpbb2_board_config['moved_view_close'] . "";
	   # Mod: Customized Topic Status v1.0.0 END
       
	   $phpbb2_folder_image =  $images['folder'];
       $phpbb2_folder_alt = $titanium_lang['Topics_Moved'];
       $newest_post_img = '';
    else:
       # Mod: Global Announcements v1.2.8 START
       if($topic_rowset[$i]['topic_type'] == POST_GLOBAL_ANNOUNCE):
          $folder = $images['folder_global_announce'];
          $folder_new = $images['folder_global_announce_new'];
          $topic_title = "".$phpbb2_board_config['global_view_open']." ".$topic_title."".$phpbb2_board_config['global_view_close']."";
       # Mod: Global Announcements v1.2.8 END

	   # Mod: Customized Topic Status v1.0.0 START
      elseif($topic_rowset[$i]['topic_type'] == POST_ANNOUNCE):
             $folder = $images['folder_announce'];
             $folder_new = $images['folder_announce_new'];
             $topic_title = "".$phpbb2_board_config['announce_view_open']." ".$topic_title."".$phpbb2_board_config['announce_view_close']."";
       elseif($topic_rowset[$i]['topic_type'] == POST_STICKY):
             $folder = $images['folder_sticky'];
             $folder_new = $images['folder_sticky_new'];
             $topic_title = "".$phpbb2_board_config['sticky_view_open']." ".$topic_title."".$phpbb2_board_config['sticky_view_close']."";
       elseif($topic_rowset[$i]['topic_status'] == TOPIC_LOCKED):
             $folder = $images['folder_locked'];
             $folder_new = $images['folder_locked_new'];
             $topic_title = "".$phpbb2_board_config['locked_view_open']." ".$topic_title."".$phpbb2_board_config['locked_view_close']."";
       else:
             if($replies >= $phpbb2_board_config['hot_threshold']):
               $folder = $images['folder_hot'];
               $folder_new = $images['folder_hot_new'];
             else:
               $folder = $images['folder'];
               $folder_new = $images['folder_new'];
             endif;
       endif;
       # Mod: Customized Topic Status v1.0.0 END

       $newest_post_img = '';

       if($userdata['session_logged_in']):
       
         if($topic_rowset[$i]['post_time'] > $userdata['user_lastvisit']):
            if(!empty($phpbb2_tracking_topics) || !empty($phpbb2_tracking_forums) || isset($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'] . '_f_all'])):
            
               $phpbb2_unread_topics = true;

                  if(!empty($phpbb2_tracking_topics[$topic_id])):
                     if($phpbb2_tracking_topics[$topic_id] >= $topic_rowset[$i]['post_time'])
                       $phpbb2_unread_topics = false;
                  endif;

                  if(!empty($phpbb2_tracking_forums[$phpbb2_forum_id])):
                    if($phpbb2_tracking_forums[$phpbb2_forum_id] >= $topic_rowset[$i]['post_time'])
                       $phpbb2_unread_topics = false;
                  endif;

                  if(isset($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'].'_f_all'])):
                     if($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'].'_f_all'] >= $topic_rowset[$i]['post_time'])
                       $phpbb2_unread_topics = false;
                  endif;

                  if($phpbb2_unread_topics):
                    $phpbb2_folder_image = $folder_new;
                    $phpbb2_folder_alt = $titanium_lang['New_posts'];
                    $newest_post_img = '<a href="'.append_titanium_sid("viewtopic.$phpEx?".POST_TOPIC_URL."=$topic_id&amp;view=newest").'"><img 
					src="'.$images['icon_newest_reply'].'" alt="'.$titanium_lang['View_newest_post'].'" title="'.$titanium_lang['View_newest_post'].'" border="0" /></a> ';
                  else:
                    $phpbb2_folder_image = $folder;
                    $phpbb2_folder_alt = ($topic_rowset[$i]['topic_status'] == TOPIC_LOCKED ) ? $titanium_lang['Topic_locked'] : $titanium_lang['No_new_posts'];
                    $newest_post_img = '';
                  endif;
            else:
            
               $phpbb2_folder_image = $folder_new;
               $phpbb2_folder_alt = ($topic_rowset[$i]['topic_status'] == TOPIC_LOCKED ) ? $titanium_lang['Topic_locked'] : $titanium_lang['New_posts'];
               $newest_post_img = '<a href="'.append_titanium_sid("viewtopic.$phpEx?".POST_TOPIC_URL."=$topic_id&amp;view=newest").'"><img 
			   src="'.$images['icon_newest_reply'].'" alt="'.$titanium_lang['View_newest_post'].'" title="'.$titanium_lang['View_newest_post'].'" border="0" /></a> ';
            endif;
        else:
          $phpbb2_folder_image = $folder;
          $phpbb2_folder_alt = ($topic_rowset[$i]['topic_status'] == TOPIC_LOCKED) ? $titanium_lang['Topic_locked'] : $titanium_lang['No_new_posts'];
          $newest_post_img = '';
        endif;
      else:
        $phpbb2_folder_image = $folder;
        $phpbb2_folder_alt = ($topic_rowset[$i]['topic_status'] == TOPIC_LOCKED) ? $titanium_lang['Topic_locked'] : $titanium_lang['No_new_posts'];
        $newest_post_img = '';
      endif;
    endif;

    if(($replies + 1) > $phpbb2_board_config['posts_per_page']):
    
       $total_phpbb2_pages = ceil(($replies + 1) / $phpbb2_board_config['posts_per_page']);
       $goto_page = ' [ <img src="'.$images['icon_gotopost'].'" alt="'.$titanium_lang['Goto_page'].'" title="'.$titanium_lang['Goto_page'].'" />'.$titanium_lang['Goto_page'].': ';
       $times = 1;

       for($j = 0; $j < $replies + 1; $j += $phpbb2_board_config['posts_per_page']):
         $goto_page .= '<a href="'.append_titanium_sid("viewtopic.$phpEx?".POST_TOPIC_URL."=".$topic_id."&amp;start=$j").'">'.$times.'</a>';
         if($times == 1 && $total_phpbb2_pages > 4):
            $goto_page .= ' ... ';
            $times = $total_phpbb2_pages - 3;
            $j += ($total_phpbb2_pages - 4) * $phpbb2_board_config['posts_per_page'];
         elseif($times < $total_phpbb2_pages):
           $goto_page .= ', ';
         endif;
         $times++;
	   endfor;
	   
       $goto_page .= ' ] ';
    
    else:
      $goto_page = '';
    endif;

    # Mod: Advanced Username Color v1.0.5 START
    $topic_rowset[$i]['username'] = UsernameColor($topic_rowset[$i]['username']);
    $topic_rowset[$i]['username2'] = UsernameColor($topic_rowset[$i]['username2']);
    $topic_rowset[$i]['user2'] = UsernameColor($topic_rowset[$i]['user2']);
    # Mod: Advanced Username Color v1.0.5 END

    $view_topic_url = append_titanium_sid("viewtopic.$phpEx?".POST_TOPIC_URL."=$topic_id");
    $topic_author = ($topic_rowset[$i]['user_id'] != ANONYMOUS) ? '<a href="'.append_titanium_sid("profile.$phpEx?mode=viewprofile&amp;".POST_USERS_URL.'='.$topic_rowset[$i]['user_id']).'">' : '';
    
	$topic_author .= ($topic_rowset[$i]['user_id'] != ANONYMOUS) ? $topic_rowset[$i]['username'] : (($topic_rowset[$i]['post_username'] != '') 
	? $topic_rowset[$i]['post_username'] : $titanium_lang['Guest']);
    
	$topic_author .= ($topic_rowset[$i]['user_id'] != ANONYMOUS) ? '</a>' : '';
    $first_post_time = create_date($phpbb2_board_config['default_dateformat'], $topic_rowset[$i]['topic_time'], $phpbb2_board_config['board_timezone']);
    $phpbb2_last_post_time = create_date($phpbb2_board_config['default_dateformat'], $topic_rowset[$i]['post_time'], $phpbb2_board_config['board_timezone']);
    
	$phpbb2_last_post_author = ($topic_rowset[$i]['id2'] == ANONYMOUS) ? (($topic_rowset[$i]['post_username2'] != '') 
	? $topic_rowset[$i]['post_username2'].' ' : $titanium_lang['Guest'].' ' ) : '<a 
	href="'. append_titanium_sid("profile.$phpEx?mode=viewprofile&amp;".POST_USERS_URL.'='.$topic_rowset[$i]['id2']).'">'.$topic_rowset[$i]['user2'].'</a>';
    
	$phpbb2_last_post_url = '<a href="'.append_titanium_sid("viewtopic.$phpEx?".POST_POST_URL.'='.$topic_rowset[$i]['topic_last_post_id']).'#'.$topic_rowset[$i]['topic_last_post_id'].'"><i 
	class="fa fa-arrow-right tooltip-html-side-interact" aria-hidden="true" title="'.$titanium_lang['View_latest_post'].'"></i></a>';
    
	$views = $topic_rowset[$i]['topic_views'];
    $row_color = (!($i % 2)) ? $theme['td_color1'] : $theme['td_color2'];
    $row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

    $phpbb2_template->assign_block_vars('topicrow', array(
       # Mod: Post Icons v1.0.1 START
	   'ICON' => $phpbb2_icon,
	   'ICON_ID' => $phpbb2_icon_ID,
       # Mod: Post Icons v1.0.1 END
 
       'ROW_COLOR' => $row_color,
       'ROW_CLASS' => $row_class,
       'FORUM_ID' => $phpbb2_forum_id,
       'TOPIC_ID' => $topic_id,
       'TOPIC_FOLDER_IMG' => $phpbb2_folder_image,
       'TOPIC_AUTHOR' => $topic_author,
       'GOTO_PAGE' => $goto_page,
       'REPLIES' => $replies,
       'NEWEST_POST_IMG' => $newest_post_img,
       
	   # Mod: Attachment Mod v2.4.1 START
       'TOPIC_ATTACHMENT_IMG' => topic_attachment_image($topic_rowset[$i]['topic_attachment']),
	   # Mod: Attachment Mod v2.4.1 END

       'TOPIC_TITLE' => $topic_title,
       'TOPIC_TYPE' => $topic_type,
       'VIEWS' => $views,
       'FIRST_POST_TIME' => $first_post_time,
       'LAST_POST_TIME' => $phpbb2_last_post_time,
       // 'LAST_POST_AUTHOR' => $phpbb2_last_post_author,
       'LAST_POST_AUTHOR' => sprintf(trim($titanium_lang['Recent_first_poster']),$phpbb2_last_post_author),
       'LAST_POST_IMG' => $phpbb2_last_post_url,
       'L_TOPIC_FOLDER_ALT' => $phpbb2_folder_alt,
       'U_VIEW_TOPIC' => $view_topic_url)
        );

       # Mod: Separate Announcements & Sticky v2.0.0a START
       if(array_key_exists($i, $dividers)):
          $phpbb2_template->assign_block_vars('topicrow.divider', array(
          'L_DIV_HEADERS' => $dividers[$i])
           );
       endif;
       # Mod: Separate Announcements & Sticky v2.0.0a END
     
	 endfor;
        
		$phpbb2_topics_count -= $total_phpbb2_announcements;
        $phpbb2_template->assign_vars(array(

       # Mod: Topic display order v1.0.2 START
          'PAGINATION' => generate_pagination("viewforum.$phpEx?".POST_FORUM_URL."=$phpbb2_forum_id&amp;
		  topicdays=$topic_days&amp;sort=$sort_value&amp;order=$order_value", $phpbb2_topics_count, $phpbb2_board_config['topics_per_page'], $phpbb2_start),
       # Mod: Topic display order v1.0.2 START
          'PAGE_NUMBER' => sprintf($titanium_lang['Page_of'], (floor($phpbb2_start / $phpbb2_board_config['topics_per_page']) + 1), ceil($phpbb2_topics_count / $phpbb2_board_config['topics_per_page'] )),
          'L_GOTO_PAGE' => $titanium_lang['Goto_page'])
        );

else:
    # No topics
    $no_topics_msg = ($forum_row['forum_status'] == FORUM_LOCKED) ? $titanium_lang['Forum_locked'] : $titanium_lang['No_topics_post_one'];
    $phpbb2_template->assign_vars(array(
        'L_NO_TOPICS' => $no_topics_msg)
    );
    $phpbb2_template->assign_block_vars('switch_no_topics', array());
endif;

# Mod: Simple Subforums v1.0.1 START
switch(SQL_LAYER):
	case 'postgresql':
		$sql = "SELECT f.*, p.post_time, p.post_username, u.username, u.user_id 
			FROM ".FORUMS_TABLE." f, ".POSTS_TABLE." p, ".USERS_TABLE." u
			WHERE p.post_id = f.forum_last_post_id 
				AND u.user_id = p.poster_id  
				AND f.forum_parent = '{$phpbb2_forum_id}'
				UNION (
					SELECT f.*, NULL, NULL, NULL, NULL
					FROM ".FORUMS_TABLE." f
					WHERE NOT EXISTS (
						SELECT p.post_time
						FROM ".POSTS_TABLE." p
						WHERE p.post_id = f.forum_last_post_id  
					)
				)
				ORDER BY cat_id, forum_order";
		break;
	case 'oracle':
		$sql = "SELECT f.*, p.post_time, p.post_username, u.username, u.user_id
			FROM ".FORUMS_TABLE." f, ".POSTS_TABLE." p, ".USERS_TABLE." u
			WHERE p.post_id = f.forum_last_post_id(+)
				AND u.user_id = p.poster_id(+)
				AND f.forum_parent = '{$phpbb2_forum_id}'
			ORDER BY f.cat_id, f.forum_order";
		break;
	default:
		$sql = "SELECT f.*, p.post_time, p.post_username, u.username, u.user_id
			FROM (( ".FORUMS_TABLE." f
			LEFT JOIN ".POSTS_TABLE." p ON p.post_id = f.forum_last_post_id )
			LEFT JOIN ".USERS_TABLE." u ON u.user_id = p.poster_id )
			WHERE f.forum_parent = '{$phpbb2_forum_id}'
			ORDER BY f.cat_id, f.forum_order";
		break;
endswitch;

if(!($result = $titanium_db->sql_query($sql)))
message_die(GENERAL_ERROR,'Could not query subforums information','', __LINE__, __FILE__,$sql);
$subforum_data = array();
while($row = $titanium_db->sql_fetchrow($result)):
 $subforum_data[] = $row;
endwhile;
$titanium_db->sql_freeresult($result);

if(($total_phpbb2_forums = count($subforum_data)) > 0):
	# Find which forums are visible for this user
	$phpbb2_is_auth_ary = array();
	$phpbb2_is_auth_ary = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata, $subforum_data);
	$display_forums = false;

	for($j = 0; $j < $total_phpbb2_forums; $j++):
	  if($phpbb2_is_auth_ary[$subforum_data[$j]['forum_id']]['auth_view'])
		$display_forums = true;
	endfor;	

	if(!$display_forums)
	$total_phpbb2_forums = 0;
endif;

if($total_phpbb2_forums)
{
	$phpbb2_template->assign_var('HAS_SUBFORUMS', 1);
	$phpbb2_template->assign_block_vars('catrow', array(
		'CAT_ID'	=> $phpbb2_forum_id,
		'CAT_DESC'	=> $forum_row['forum_name'],
		'U_VIEWCAT' => append_titanium_sid("viewforum.$phpEx?" . POST_FORUM_URL ."=$phpbb2_forum_id"),
		));

	
	# Obtain a list of topic ids which contain
	# posts made since user last visited
	if($userdata['session_logged_in']):
		$sql = "SELECT t.forum_id, t.topic_id, p.post_time 
			FROM ".TOPICS_TABLE." t, ".POSTS_TABLE." p 
			WHERE p.post_id = t.topic_last_post_id 
				AND p.post_time > ".$userdata['user_lastvisit']." 
				AND t.topic_moved_id = 0"; 

		if(!($result = $titanium_db->sql_query($sql)))
		message_die(GENERAL_ERROR,'Could not query new topic information','', __LINE__, __FILE__,$sql);

		$new_phpbb2_topic_data = array();

		while($phpbb2_topic_data = $titanium_db->sql_fetchrow($result)):
			$new_phpbb2_topic_data[$phpbb2_topic_data['forum_id']][$phpbb2_topic_data['topic_id']] = $phpbb2_topic_data['post_time'];
		endwhile;
		$titanium_db->sql_freeresult($result);
	endif;

	# Obtain list of moderators of each forum
	# First users, then groups ... broken into two queries
	$subforum_moderators = array();
	$sql = "SELECT aa.forum_id, u.user_id, u.username 
		FROM ".AUTH_ACCESS_TABLE." aa, ".USER_GROUP_TABLE." ug, ".GROUPS_TABLE." g, ".USERS_TABLE." u
		WHERE aa.auth_mod = ".TRUE." 
			AND g.group_single_user = 1 
			AND ug.group_id = aa.group_id 
			AND g.group_id = aa.group_id 
			AND u.user_id = ug.user_id 
		GROUP BY u.user_id, u.username, aa.forum_id 
		ORDER BY aa.forum_id, u.user_id";

	if (!($result = $titanium_db->sql_query($sql, false, true)))
	message_die(GENERAL_ERROR, 'Could not query forum moderator information', '', __LINE__, __FILE__, $sql);

	while($row = $titanium_db->sql_fetchrow($result)):
	 $subforum_moderators[$row['forum_id']][] = '<a href="'.append_titanium_sid("profile.$phpEx?mode=viewprofile&amp;".POST_USERS_URL."=".$row['user_id']).'">'.UsernameColor($row['username']).'</a>';
	endwhile;
	$titanium_db->sql_freeresult($result);	

	$sql = "SELECT aa.forum_id, g.group_id, g.group_name 
		FROM ".AUTH_ACCESS_TABLE." aa, ".USER_GROUP_TABLE." ug, ".GROUPS_TABLE." g 
		WHERE aa.auth_mod = ".TRUE." 
			AND g.group_single_user = 0 
			AND g.group_type <> ".GROUP_HIDDEN."
			AND ug.group_id = aa.group_id 
			AND g.group_id = aa.group_id 
		GROUP BY g.group_id, g.group_name, aa.forum_id 
		ORDER BY aa.forum_id, g.group_id";

	if(!($result = $titanium_db->sql_query($sql,false,true)))
	message_die(GENERAL_ERROR, 'Could not query forum moderator information', '', __LINE__, __FILE__, $sql);

	while($row = $titanium_db->sql_fetchrow($result))
		$subforum_moderators[$row['forum_id']][] = '<a href="' . append_titanium_sid("groupcp.$phpEx?" . POST_GROUPS_URL . "=" . $row['group_id']) . '">' . 	GroupColor($row['group_name']) . '</a>';
	emdwhile;
	$titanium_db->sql_freeresult($result);

	# show subforums
	for($j = 0; $j < $total_phpbb2_forums; $j++):
		$subforum_id = $subforum_data[$j]['forum_id'];
		if($phpbb2_is_auth_ary[$subforum_id]['auth_view']):
		
			$phpbb2_unread_topics = false;

			if($subforum_data[$j]['forum_status'] == FORUM_LOCKED):
				$phpbb2_folder_image = $images['forum_locked']; 
				$phpbb2_folder_alt = $titanium_lang['Forum_locked'];
			else:
				if($userdata['session_logged_in']):
					if(!empty($new_phpbb2_topic_data[$subforum_id])):
						$subforum_last_post_time = 0;

						while(list($check_phpbb2_topic_id,$check_phpbb2_post_time) = @each($new_phpbb2_topic_data[$subforum_id])):
							if(empty($phpbb2_tracking_topics[$check_phpbb2_topic_id])):
								$phpbb2_unread_topics = true;
								$subforum_last_post_time = max($check_phpbb2_post_time, $subforum_last_post_time);
							else:
								if($phpbb2_tracking_topics[$check_phpbb2_topic_id] < $check_phpbb2_post_time):
									$phpbb2_unread_topics = true;
									$subforum_last_post_time = max($check_phpbb2_post_time, $subforum_last_post_time);
								endif;
							endif;
						endwhile;

						if(!empty($phpbb2_tracking_forums[$subforum_id])):
							if ( $phpbb2_tracking_forums[$subforum_id] > $subforum_last_post_time )
							$phpbb2_unread_topics = false;
						endif;

						if(isset($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'].'_f_all'])):
							if($HTTP_COOKIE_VARS[$phpbb2_board_config['cookie_name'].'_f_all'] > $subforum_last_post_time)
							$phpbb2_unread_topics = false;
						endif;
					endif;
				endif;
				$phpbb2_folder_image = ( $phpbb2_unread_topics ) ? $images['forum_new'] : $images['forum']; 
				$phpbb2_folder_alt = ( $phpbb2_unread_topics ) ? $titanium_lang['New_posts'] : $titanium_lang['No_new_posts']; 
			endif;

			$phpbb2_posts = $subforum_data[$j]['forum_posts'];
			$phpbb2_topics = $subforum_data[$j]['forum_topics'];

			if($subforum_data[$j]['forum_last_post_id']):
				$phpbb2_last_post_time = create_date($phpbb2_board_config['default_dateformat'], $subforum_data[$j]['post_time'], $phpbb2_board_config['board_timezone']);
				$phpbb2_last_post = $phpbb2_last_post_time . '<br />';
				
				$phpbb2_last_post .= ($subforum_data[$j]['user_id'] == ANONYMOUS) ? (($subforum_data[$j]['post_username'] != '') 
				? $subforum_data[$j]['post_username'].' ' : $titanium_lang['Guest'].' ' ) : '<a 
				href="' . append_titanium_sid("profile.$phpEx?mode=viewprofile&amp;".POST_USERS_URL.'='.$subforum_data[$j]['user_id']).'">'.UsernameColor($subforum_data[$j]['username']).'</a> ';
				
				$phpbb2_last_post .= '<a href="'.append_titanium_sid("viewtopic.$phpEx?".POST_POST_URL.'='.$subforum_data[$j]['forum_last_post_id']).'#'.$subforum_data[$j]['forum_last_post_id'].'"><img 
				src="'.$images['icon_latest_reply'].'" border="0" alt="'.$titanium_lang['View_latest_post'].'" title="'.$titanium_lang['View_latest_post'].'" /></a>';
			
			else:
				$phpbb2_last_post = $titanium_lang['No_Posts'];
			endif;

			// if(count($subforum_moderators[$subforum_id]) > 0)                                                               whO? WHY?
			// {                                                                                                               whO? WHY?
			// 	$l_phpbb2_moderators = (count($subforum_moderators[$subforum_id]) == 1 ) ? $titanium_lang['Moderator'] : $titanium_lang['Moderators'];  whO? WHY?
			// 	$phpbb2_moderator_list = implode(', ', $subforum_moderators[$subforum_id]);                                           whO? WHY?
			// }                                                                                                               whO? WHY?
			// else                                                                                                            whO? WHY?
			// {                                                                                                               whO? WHY?
			// 	$l_phpbb2_moderators = '&nbsp;';                                                                                      whO? WHY?
			// 	$phpbb2_moderator_list = '';                                                                                          whO? WHY?
			// }                                                                                                               whO? WHY?

            $phpbb2_moderator_list = $phpbb2_forum_moderators;

			$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$phpbb2_template->assign_block_vars('catrow.forumrow',	array(
				'ROW_COLOR' => '#' . $row_color,
				'ROW_CLASS' => $row_class,
				'FORUM_FOLDER_IMG' => $phpbb2_folder_image, 
				'FORUM_NAME' => $subforum_data[$j]['forum_name'],
				'FORUM_DESC' => $subforum_data[$j]['forum_desc'],
				'POSTS' => $subforum_data[$j]['forum_posts'],
				'TOPICS' => $subforum_data[$j]['forum_topics'],
				'LAST_POST' => $phpbb2_last_post,
				'MODERATORS' => $phpbb2_moderator_list,
				'ID' => $subforum_data[$j]['forum_id'],
				'UNREAD' => intval($phpbb2_unread_topics),
				'LAST_POST_TIME' => $phpbb2_last_post_time,
				'L_MODERATOR' => $l_phpbb2_moderators, 
				'L_FORUM_FOLDER_ALT' => $phpbb2_folder_alt,
				'U_VIEWFORUM' => append_titanium_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$subforum_id"))
			);
		endif;
	endfor;
}
# Mod: Simple Subforums v1.0.1 END

# Base: At a Glance v2.2.1 START
# Mod: At a Glance Option v1.0.0 START
if (show_glance("forums")) 
include($phpbb2_root_path . 'glance.'.$phpEx);
# Base: At a Glance v2.2.1 END
# Mod: At a Glance Option v1.0.0 END

# Parse the page and print
$phpbb2_template->pparse('body');

# Page footer
include("includes/page_tail.$phpEx");
?>
