<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/
/***************************************************************************
 *                           usercp_viewprofile.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   Id: usercp_viewprofile.php,v 1.5.2.5 2005/07/19 20:01:16 acydburn Exp
 ***************************************************************************/
/***************************************************************************
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 ***************************************************************************/
/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.0.0       06/07/2005
-=[Mod]=-
      Attachment Mod                           v2.4.1       07/20/2005
      Advanced Username Color                  v1.0.5       06/11/2005
      Default avatar                           v1.1.0       06/30/2005
      User Administration Link on Profile      v1.0.0       07/29/2005
      XData                                    v1.0.3       02/08/2007
      YA Merge                                 v1.0.0       08/10/2005
      View Sig                                 v1.0.0       08/11/2005
      Show Groups                              v1.0.1       09/02/2005
      Remote Avatar Resize                     v2.0.0       11/19/2005
      Online/Offline/Hidden                    v2.2.7       01/24/2006
      XData Date Conversion                    v0.1.1       05/04/2006
	  Member Country Flags                     v2.0.7
	  Multiple Ranks And Staff View            v2.0.3
	  Gender                                   v1.2.6
	  Birthdays                                v3.0.0
	  MSN Profile Image                        v1.0.0
	  Facebook Profile Image                   v1.0.0
	  Admin User Notes                         v1.0.0       05/28/2009
	  Arcade                                   v3.0.2       05/29/2009
	  Admin delete users & posts               v1.0.5       05/29/2009
 ************************************************************************/
if (!defined('IN_PHPBB2'))
exit('ACCESS DENIED');

if(empty($HTTP_GET_VARS[POST_USERS_URL]) || $HTTP_GET_VARS[POST_USERS_URL] == ANONYMOUS)
message_die(GENERAL_MESSAGE, $titanium_lang['No_user_id_specified']);

$profiledata = get_userdata($HTTP_GET_VARS[POST_USERS_URL]);
/*****[BEGIN]******************************************
 [ Mod:     Show Groups                        v1.0.1 ]
 ******************************************************/
if(isset($profiledata['user_id']) && !empty($profiledata['user_id'])): 
    $sql = "SELECT group_name 
	        FROM ".GROUPS_TABLE." 
			LEFT JOIN ".USER_GROUP_TABLE." on ".USER_GROUP_TABLE.".group_id=".GROUPS_TABLE.".group_id 
			WHERE ".USER_GROUP_TABLE.".user_id=".$profiledata['user_id'];
			
    if(!($result = $titanium_db->sql_query($sql))):
    $groups = "SQL Failed to obtain last visit";
    else: 
        if($titanium_db->sql_numrows($result) == 0):
        $groups = "None";
         
		else: 
            while($row = $titanium_db->sql_fetchrow($result)):
                $groups .= $row['group_name'] . "<br />";
            endwhile;
        endif;
        $titanium_db->sql_freeresult($result);
    endif;
endif;
/*****[END]********************************************
 [ Mod:     Show Groups                        v1.0.1 ]
 ******************************************************/

if(!$profiledata)
message_die(GENERAL_MESSAGE, $titanium_lang['No_user_id_specified']);

/*****[BEGIN]******************************************
 [ Mod:    Multiple Ranks And Staff View       v2.0.3 ]
 ******************************************************/
require_once(NUKE_INCLUDE_DIR.'functions_mg_ranks.'.$phpEx);
$ranks_sql = query_ranks();
/*****[END]********************************************
 [ Mod:    Multiple Ranks And Staff View       v2.0.3 ]
 ******************************************************/

//
// Output page header and profile_view template
//
$phpbb2_template->set_filenames(array(
    'body' => 'profile_view_body.tpl')
);

if(is_active("Forums")) 
{
    make_jumpbox('viewforum.'.$phpEx);
}

//
// Calculate the number of days this user has been a member ($memberdays)
// Then calculate their posts per day
//
$regdate = $profiledata['user_regdate'];
$nukedate = strtotime($regdate);
$memberdays = max(1, round( ( time() - $nukedate ) / 86400 ));
$phpbb2_posts_per_day = $profiledata['user_posts'] / $memberdays;

// Get the users percentage of total posts
if ( $profiledata['user_posts'] != 0  )
{
    $phpbb2_total_posts = get_db_stat('postcount');
    $percentage = ( $phpbb2_total_posts ) ? min(100, ($profiledata['user_posts'] / $phpbb2_total_posts) * 100) : 0;
}
else
{
    $percentage = 0;
}

# avatar on users profile page START
$avatar_img = '';
if($profiledata['user_avatar_type'] && $profiledata['user_allowavatar']):
    switch( $profiledata['user_avatar_type']):
        case USER_AVATAR_UPLOAD:
            $avatar_img = ($phpbb2_board_config['allow_avatar_upload']) ? '<img class="rounded-corners-user-info" style="max-height: 200px; 
			max-width: 200px;" src="'.$phpbb2_board_config['avatar_path']. '/'. $profiledata['user_avatar'] . '" alt="" border="0" />' : '';
            break;
        case USER_AVATAR_REMOTE:
            $avatar_img = '<img class="rounded-corners-user-info" style="max-height: 200px; max-width: 200px;" s
			rc="' . resize_avatar($profiledata['user_avatar']) . '" alt="" border="0" />';
            break;
        case USER_AVATAR_GALLERY:
            $avatar_img = ( $phpbb2_board_config['allow_avatar_local'] ) ? '<img class="rounded-corners-user-info" style="max-height: 200px; max-width: 
			200px;" src="' . $phpbb2_board_config['avatar_gallery_path'] . '/' 
			. (($profiledata['user_avatar'] == 'blank.gif' || $profiledata['user_avatar'] == 'gallery/blank.png') ? 'blank.png' : $profiledata['user_avatar']) 
			. '" alt="" border="0" />' : '';
            break;
    endswitch;
endif;
# Mod: Default avatar v1.1.0 START
if((!$avatar_img) && (($phpbb2_board_config['default_avatar_set'] == 1) 
|| ($phpbb2_board_config['default_avatar_set'] == 2)) && ($phpbb2_board_config['default_avatar_users_url'])):
  $avatar_img = '<img class="rounded-corners-profile" style="max-height: 200px; max-width: 200px;" src="'.$phpbb2_board_config['default_avatar_users_url'].'" alt="" border="0" />';
endif;
# Mod: Default avatar v1.1.0 END
# avatar on users profile page END

/*****[BEGIN]******************************************
 [ Mod:    Multiple Ranks And Staff View       v2.0.3 ]
 ******************************************************/
	$titanium_user_ranks = generate_ranks($profiledata, $ranks_sql);

	$titanium_user_rank_01 = ($titanium_user_ranks['rank_01'] == '') ? '' : ($titanium_user_ranks['rank_01'] . '<br />');
	$titanium_user_rank_01_img = ($titanium_user_ranks['rank_01_img'] == '') ? '' : ($titanium_user_ranks['rank_01_img'] . '<br />');
	$titanium_user_rank_02 = ($titanium_user_ranks['rank_02'] == '') ? '' : ($titanium_user_ranks['rank_02'] . '<br />');
	$titanium_user_rank_02_img = ($titanium_user_ranks['rank_02_img'] == '') ? '' : ($titanium_user_ranks['rank_02_img'] . '<br />');
	$titanium_user_rank_03 = ($titanium_user_ranks['rank_03'] == '') ? '' : ($titanium_user_ranks['rank_03'] . '<br />');
	$titanium_user_rank_03_img = ($titanium_user_ranks['rank_03_img'] == '') ? '' : ($titanium_user_ranks['rank_03_img'] . '<br />');
	$titanium_user_rank_04 = ($titanium_user_ranks['rank_04'] == '') ? '' : ($titanium_user_ranks['rank_04'] . '<br />');
	$titanium_user_rank_04_img = ($titanium_user_ranks['rank_04_img'] == '') ? '' : ($titanium_user_ranks['rank_04_img'] . '<br />');
	$titanium_user_rank_05 = ($titanium_user_ranks['rank_05'] == '') ? '' : ($titanium_user_ranks['rank_05'] . '<br />');
	$titanium_user_rank_05_img = ($titanium_user_ranks['rank_05_img'] == '') ? '' : ($titanium_user_ranks['rank_05_img'] . '<br />');
/*****[END]********************************************
 [ Mod:    Multiple Ranks And Staff View       v2.0.3 ]
 ******************************************************/

$temp_url = append_titanium_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=" . $profiledata['user_id']);

if(is_active("Private_Messages")) 
{
	$pm_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $titanium_lang['Send_private_message'] . '" title="' . $titanium_lang['Send_private_message'] . '" border="0" /></a>';
	// $pm = '<a href="' . $temp_url . '">' . $titanium_lang['Send_private_message'] . '</a>';
	$pm = '<a href="' . $temp_url . '">' . sprintf($titanium_lang['Send_private_message'], $profiledata['username']) . '</a>';
}

/*****[BEGIN]******************************************
 [ Mod:     Member Country Flags               v2.0.7 ]
 ******************************************************/
$location = (!empty($profiledata['user_from_flag']) ) ? '<span class="countries '.str_replace('.png','',$profiledata['user_from_flag']).'"></span>&nbsp;' : '&nbsp;';
$location .= ($profiledata['user_from']) ? $profiledata['user_from'] : '';
/*****[END]********************************************
 [ Mod:     Member Country Flags               v2.0.7 ]
 ******************************************************/

if (!empty($profiledata['user_viewemail']) || ($profiledata['username'] == $userdata['username']) || $userdata['user_level'] == ADMIN)
{
    $email_uri = ( $phpbb2_board_config['board_email_form'] ) ? append_titanium_sid("profile.$phpEx?mode=email&amp;".POST_USERS_URL
	.'='.$profiledata['user_id']) : 'mailto:' .$profiledata['user_email'];
    $email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $titanium_lang['Send_email'] . '" title="' . $titanium_lang['Send_email'] . '" border="0" /></a>';
    $email = '<a href="' . $email_uri . '">' . sprintf($titanium_lang['Send_email'], $profiledata['username']) . '</a>';
}
else
{
    $email_img = '&nbsp;';
    $email = '';
}
if(isset($profiledata['user-website'])) 
{
    if (( $profiledata['user-website'] == "http:///") || ( $profiledata['user_website'] == "http://"))
	{
        $profiledata['user_website'] =  "";
    }
    if (($profiledata['user_website'] != "" ) && (substr($profiledata['user_website'],0, 7) != "http://")) 
	{
        $profiledata['user_website'] = "http://".$profiledata['user_website'];
    }
}

$www_img = ( $profiledata['user_website'] ) ? '<a href="' . $profiledata['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $titanium_lang['Visit_website'] . '" title="' . $titanium_lang['Visit_website'] . '" border="0" /></a>' : '&nbsp;';
$www = ( $profiledata['user_website'] ) ? '<a href="' . $profiledata['user_website'] . '" target="_userwww">' . $profiledata['user_website'] . '</a>' : '';

/*****[BEGIN]******************************************
 [ Mod:    Birthdays                           v3.0.0 ]
 ******************************************************/
$birthday = '&nbsp;';
//if ( !empty($profiledata['user_birthday']) && $profiledata['birthday_display'] != BIRTHDAY_AGE && $profiledata['birthday_display'] != BIRTHDAY_NONE )
if ( !empty($profiledata['user_birthday']) && $profiledata['birthday_display'] <> 2 && $profiledata['birthday_display'] <> 3 ) // 
{
	preg_match('/(..)(..)(....)/', sprintf('%08d',$profiledata['user_birthday']), $bday_parts);
	$bday_month = $bday_parts[1];
	$bday_day = $bday_parts[2];
	//$phpbb2_bday_year = ( $profiledata['birthday_display'] != BIRTHDAY_DATE ) ? $bday_parts[3] : 0;
	$phpbb2_bday_year = ( $profiledata['birthday_display'] <> 1 ) ? $bday_parts[3] : 0;
	$birthday_format = ($phpbb2_bday_year != 0) ? str_replace(array('y','Y'), array($phpbb2_bday_year % 100,$phpbb2_bday_year), $titanium_lang['DATE_FORMAT']) : preg_replace('#[^djFmMnYy]*[Yy]#','',$titanium_lang['DATE_FORMAT']);
	$birthday = create_date($birthday_format, gmmktime(12,0,0,$bday_month,$bday_day,2000), 0);
}
elseif( $profiledata['birthday_display'] == 2 )
{
	$phpbb2_bday_month_day = floor($profiledata['user_birthday'] / 10000);
	$phpbb2_bday_year_age = ( $profiledata['birthday_display'] != BIRTHDAY_NONE && $profiledata['birthday_display'] != BIRTHDAY_DATE ) ? $profiledata['user_birthday'] - 10000*$phpbb2_bday_month_day : 0;
	$phpbb2_fudge = ( gmdate('md') < $phpbb2_bday_month_day ) ? 1 : 0;
	$birthday = ( $phpbb2_bday_year_age ) ? gmdate('Y')-$phpbb2_bday_year_age-$phpbb2_fudge : false;
}
/*****[END]********************************************
 [ Mod:    Birthdays                           v3.0.0 ]
 ******************************************************/

$facebook_img = ( $profiledata['user_facebook'] ) ? '<a href="http://www.facebook.com/profile.php?id=' . $profiledata['user_facebook'] . '" target="_userwww"><img src="' . $images['icon_facebook'] . '" alt="' . $titanium_lang['Visit_facebook'] . '" title="' . $titanium_lang['Visit_facebook'] . '" border="0" /></a>' : '&nbsp;';
$facebook = ( $profiledata['user_facebook'] ) ? '<a href="' . $temp_url . '" target="_userwww">' . $profiledata['user_facebook'] . '</a>' : '&nbsp;';

$temp_url = append_titanium_sid("search.$phpEx?search_author=" . urlencode($profiledata['username']) . "&amp;showresults=posts");
$search_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_search'] . '" alt="' . sprintf($titanium_lang['Search_user_posts'], $profiledata['username']) . '" title="' . sprintf($titanium_lang['Search_user_posts'], $profiledata['username']) . '" border="0" /></a>';
$search = '<a href="' . $temp_url . '">' . sprintf($titanium_lang['Search_user_posts'], $profiledata['username']) . '</a>';

/*****[BEGIN]******************************************
 [ Mod:    Gender                              v1.2.6 ]
 ******************************************************/
if ( !empty($profiledata['user_gender'])) 
{ 
    switch ($profiledata['user_gender']) 
    { 
        case 1: $gender=$titanium_lang['Male'];break; 
        case 2: $gender=$titanium_lang['Female'];break; 
        default:$gender=$titanium_lang['No_gender_specify']; 
    } 
} else $gender=$titanium_lang['No_gender_specify'];
/*****[END]********************************************
 [ Mod:    Gender                              v1.2.6 ]
 ******************************************************/

/**
 * Mod: Display the users last visit date and time.
 * @since 2.0.9e
 */
if ($profiledata['user_lastvisit'] != 0)
	$titanium_user_last_visit = date($phpbb2_board_config['default_dateformat'],$profiledata['user_lastvisit']);
else
	$titanium_user_last_visit = $titanium_lang['not_available'];

//
// Generate page
//
/*****[BEGIN]******************************************
 [ Mod:    User Administration Link on Profile v1.0.0 ]
 ******************************************************/
if($userdata['user_level'] == ADMIN)
{
    $phpbb2_template->assign_vars(array(
        "L_USER_ADMIN_FOR" => $titanium_lang['User_admin_for'],
        "U_ADMIN_PROFILE" => append_titanium_sid("modules/Forums/admin/admin_users.$phpEx?mode=edit&amp;u=" . $profiledata['user_id']))
    );
    $phpbb2_template->assign_block_vars("switch_user_admin", array());
}
/*****[END]********************************************
 [ Mod:    User Administration Link on Profile v1.0.0 ]
 ******************************************************/
$phpbb2_page_title = $titanium_lang['Viewing_profile'];
include("includes/page_header.php");
/*****[BEGIN]******************************************
 [ Mod:    Online/Offline/Hidden               v2.2.7 ]
 ******************************************************/
if ($profiledata['user_session_time'] >= (time()-$phpbb2_board_config['online_time'])):

    if ($profiledata['user_allow_viewonline']):
        $online_status = '<a href="'.append_titanium_sid("viewonline.$phpEx").'" title="'.sprintf($titanium_lang['is_online'], $profiledata['username']).'"'.$online_color.'>'.$titanium_lang['Online'].'</a>';
    elseif ( $userdata['user_level'] == ADMIN || $userdata['user_id'] == $profiledata['user_id'] ):
        $online_status = '<em><a href="' . append_titanium_sid("viewonline.$phpEx") . '" title="' . sprintf($titanium_lang['is_hidden'], $profiledata['username']) . '"' . $hidden_color . '>' . $titanium_lang['Hidden'] . '</a></em>';
    else:
        $online_status = '<span title="' . sprintf($titanium_lang['is_offline'], $profiledata['username']) . '"' . $offline_color . '>' . $titanium_lang['Offline'] . '</span>';
    endif;

else:
    $online_status = '<span title="' . sprintf($titanium_lang['is_offline'], $profiledata['username']) . '"' . $offline_color . '>' . $titanium_lang['Offline'] . '</span>';
endif;
/*****[END]********************************************
 [ Mod:    Online/Offline/Hidden               v2.2.7 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:    Attachment Mod                      v2.4.1 ]
 ******************************************************/
display_upload_attach_box_limits($profiledata['user_id']);
/*****[END]********************************************
 [ Mod:    Attachment Mod                      v2.4.1 ]
 ******************************************************/
$profiledata['user_from'] = str_replace(".png", "", $profiledata['user_from']);

/*****[BEGIN]******************************************
 [ Base:    Nuke Patched                       v3.0.0 ]
 ******************************************************/
if (function_exists('get_html_translation_table'))
{
   $u_search_author = urlencode(strtr($profiledata['username'], array_flip(get_html_translation_table(HTML_ENTITIES))));
}
else
{
   $u_search_author = urlencode(str_replace(array('&', '&_#039;', '"', '<', '>'), array('&', "'", '"', '<', '>'), $profiledata['username']));
}
/*****[END]********************************************
 [ Base:    Nuke Patched                       v3.0.0 ]
 ******************************************************/

if (function_exists('get_html_translation_table'))
{
    $u_search_author = urlencode(strtr($profiledata['username'], array_flip(get_html_translation_table(HTML_ENTITIES))));
}
else
{
    $u_search_author = urlencode(str_replace(array('&amp;', '&#039;', '&quot;', '&lt;', '&gt;'), array('&', "'", '"', '<', '>'), $profiledata['username']));
}
/*****[BEGIN]******************************************
 [ Mod:     Users Reputations Systems          v1.0.0 ]
 ******************************************************/
$reputation = '';
if ($rep_config['rep_disable'] == 0)
{
  if ($profiledata['user_reputation'] == 0)
  {
    $reputation = $titanium_lang['Zero_reputation'];
  } else
  {
    if ($rep_config['graphic_version'] == 0)
    {
      // Text version
      if ($profiledata['user_reputation'] > 0)
      {
        // $reputation .= "<strong><font color=\"green\">" . round($profiledata['user_reputation'],1) . "</font></strong>";
        $reputation .= '<span style="color: green; font-weight: bold">'.round($profiledata['user_reputation'], 1).'</span>';
      } else {
        // $reputation .= "<strong><font color=\"red\">" . round($profiledata['user_reputation'],1) . "</font></strong>";
        $reputation .= '<span style="color: red; font-weight: bold">'.round($profiledata['user_reputation'], 1).'</span>';
      }
    } else {
      // Graphic version
      get_reputation_medals($profiledata['user_reputation']);
    }
  }
  $sql = "SELECT COUNT(user_id) AS count_reps
      FROM " . REPUTATION_TABLE . " AS r
      WHERE r.user_id = " . $profiledata['user_id'] . "
      GROUP BY user_id";
  if ( !($result = $titanium_db->sql_query($sql)) )
  {
    message_die(GENERAL_ERROR, "Could not obtain reputation stats for this user", '', __LINE__, __FILE__, $sql);
  }
  $row_rep = $titanium_db->sql_fetchrow($result);
  if ($row_rep)
  {
    $reputation .= " <br />(<a href=\""  . append_titanium_sid("reputation.$phpEx?a=stats&amp;" . POST_USERS_URL . "=" . $profiledata['user_id']) . "\" target=\"_blank\" onClick=\"popupWin = window.open(this.href, '" . $titanium_lang['Reputation'] . "', 'location,width=700,height=400,top=0,scrollbars=yes'); popupWin.focus(); return false;\">" . $titanium_lang['Votes'] . "</a>: " . $row_rep['count_reps'] . ")";
  }
}
/*****[END]********************************************
 [ Mod:     Users Reputations System           v1.0.0 ]
 ******************************************************/
 
/*****[BEGIN]******************************************
 [ Mod:    View Sig                            v1.0.0 ]
 ******************************************************/
$titanium_user_sig = '';
if(!empty($profiledata['user_sig'])) {
    include_once('includes/bbcode.'.$phpEx);
    include_once('includes/functions_post.'.$phpEx);

    $html_on    = ( $profiledata['user_allowhtml'] && $phpbb2_board_config['allow_html'] ) ? 1 : 0 ;
    $bbcode_on  = ( $profiledata['user_allowbbcode'] && $phpbb2_board_config['allow_bbcode']  ) ? 1 : 0 ;
    $smilies_on = ( $profiledata['user_allowsmile'] && $phpbb2_board_config['allow_smilies']  ) ? 1 : 0 ;

    $signature_bbcode_uid = $profiledata['user_sig_bbcode_uid'];
    $signature = ( $signature_bbcode_uid != '' ) ? preg_replace("/:(([a-z0-9]+:)?)$signature_bbcode_uid\]/si", ']', $profiledata['user_sig']) : $profiledata['user_sig'];
    $bbcode_uid = $profiledata['user_sig_bbcode_uid'];
    $titanium_user_sig = prepare_message($profiledata['user_sig'], $html_on, $bbcode_on, $smilies_on, $bbcode_uid);

    if( $titanium_user_sig != '' )
    {

        if ( $bbcode_on  == 1 ) { $titanium_user_sig = bbencode_second_pass($titanium_user_sig, $bbcode_uid); }
        if ( $bbcode_on  == 1 ) { $titanium_user_sig = bbencode_first_pass($titanium_user_sig, $bbcode_uid); }
        if ( $bbcode_on  == 1 ) { $titanium_user_sig = make_clickable($titanium_user_sig); }
        if ( $smilies_on == 1 ) { $titanium_user_sig = smilies_pass($titanium_user_sig); }

        $phpbb2_template->assign_block_vars('user_sig', array());

        $titanium_user_sig = nl2br($titanium_user_sig);
        $titanium_user_sig = html_entity_decode($titanium_user_sig);
    }
    else
    {
        $titanium_user_sig = '';
    }
}
/*****[END]********************************************
 [ Mod:    View Sig                            v1.0.0 ]
 ******************************************************/
/*****[BEGIN]******************************************
 [ Mod:    YA Merge                            v1.0.0 ]
 ******************************************************/
if($profiledata['bio']) {
    $phpbb2_template->assign_block_vars('user_extra', array());
}
/*****[END]********************************************
 [ Mod:    YA Merge                            v1.0.0 ]
 ******************************************************/

$phpbb2_template->assign_vars(array(
/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
    'USERNAME' => UsernameColor($profiledata['username']),
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
    'JOINED' => $profiledata['user_regdate'],
/*****[BEGIN]******************************************
 [ Mod:    Show Groups                         v1.0.1 ]
 ******************************************************/
    'GROUPS' => $groups,
/*****[END]********************************************
 [ Mod:    Show Groups                         v1.0.1 ]
 ******************************************************/
/*****[BEGIN]******************************************
 [ Mod:    Multiple Ranks And Staff View       v2.0.3 ]
 ******************************************************/
	'USER_RANK_01' => $titanium_user_rank_01,
	'USER_RANK_01_IMG' => $titanium_user_rank_01_img,
	'USER_RANK_02' => $titanium_user_rank_02,
	'USER_RANK_02_IMG' => $titanium_user_rank_02_img,
	'USER_RANK_03' => $titanium_user_rank_03,
	'USER_RANK_03_IMG' => $titanium_user_rank_03_img,
	'USER_RANK_04' => $titanium_user_rank_04,
	'USER_RANK_04_IMG' => $titanium_user_rank_04_img,
	'USER_RANK_05' => $titanium_user_rank_05,
	'USER_RANK_05_IMG' => $titanium_user_rank_05_img,
/*****[END]********************************************
 [ Mod:    Multiple Ranks And Staff View       v2.0.3 ]
 ******************************************************/
    'POSTS_PER_DAY' => $phpbb2_posts_per_day,
    'POSTS' => $profiledata['user_posts'],
    'PERCENTAGE' => $percentage . '%',
    'POST_DAY_STATS' => sprintf($titanium_lang['User_post_day_stats'], $phpbb2_posts_per_day),
    'POST_PERCENT_STATS' => sprintf($titanium_lang['User_post_pct_stats'], $percentage),
/*****[BEGIN]******************************************
 [ Mod:     Users Reputations Systems          v1.0.0 ]
 ******************************************************/
    'REPUTATION' => $reputation,
/*****[END]********************************************
 [ Mod:     Users Reputations System           v1.0.0 ]
 ******************************************************/

 /*****[Begin]******************************************
 [ Mod:     UPLOAD Switchbox Repair              v1.0.1 ]
 *******************************************************/
        // 'LCAP_IMG' => $images['voting_lcap'],
        // 'MAINBAR_IMG' => $images['voting_graphic'][0],
        // 'RCAP_IMG' => $images['voting_rcap'],
 /*****[END]********************************************
 [ Mod:     UPLOAD Switchbox Repair             v1.0.1 ]
 *******************************************************/  
    'SEARCH_IMG' => $search_img,
    'SEARCH' => $search,
    'PM_IMG' => $pm_img,
    'PM' => $pm,
    'EMAIL_IMG' => $email_img,
    'EMAIL' => $email,
    'WWW_IMG' => $www_img,
    'WWW' => $www,
	'FACEBOOK_IMG' => $facebook_img,
	'FACEBOOK' => $facebook,
/**
 * Mod: Display the users last visit date and time.
 * @since 2.0.9e
 */
	'USER_LAST_VISIT' => $titanium_user_last_visit,
  'EDIT_THIS_USER' => sprintf($titanium_lang['Edit_Forum_User_ACP'],'<a href="'.append_titanium_sid("modules/Forums/admin/admin_users.$phpEx?mode=edit&amp;u=" . $profiledata['user_id']).'">','</a>'),
  'BAN_THIS_USER_IP' => sprintf($titanium_lang['Ban_Forum_User_IP'],'<a href="'.$admin_file.'.php?op=ABBlockedIPAdd&amp;tip='.$profiledata['last_ip'].'">','</a>'),
  'SUSPEND_THIS_USER' => sprintf($titanium_lang['Suspend_This_User'],'<a href="modules.php?name=Your_Account&amp;file=admin&amp;op=suspendUser&amp;chng_uid='.$profiledata['user_id'].'">','</a>'),
  'DELETE_THIS_USER' => sprintf($titanium_lang['Delete_This_User'],'<a href="modules.php?name=Your_Account&amp;file=admin&amp;op=deleteUser&amp;chng_uid='.$profiledata['user_id'].'">','</a>'),

/*****[BEGIN]******************************************
 [ Mod:    Birthdays                           v3.0.0 ]
 ******************************************************/
	'BIRTHDAY' => $birthday,
/*****[END]********************************************
 [ Mod:    Birthdays                           v3.0.0 ]
 ******************************************************/
/*****[BEGIN]******************************************
 [ Mod:     Member Country Flags               v2.0.7 ]
 ******************************************************/
	'LOCATION' => $location,
/*****[END]********************************************
 [ Mod:     Member Country Flags               v2.0.7 ]
 ******************************************************/
    'OCCUPATION' => ( $profiledata['user_occ'] ) ? $profiledata['user_occ'] : '&nbsp;',
    'INTERESTS' => ( $profiledata['user_interests'] ) ? $profiledata['user_interests'] : '&nbsp;',
/*****[BEGIN]******************************************
 [ Mod:    Gender                              v1.2.6 ]
 ******************************************************/
    'GENDER' => $gender,
/*****[END]********************************************
 [ Mod:    Gender                              v1.2.6 ]
 ******************************************************/
/*****[BEGIN]******************************************
 [ Mod:    YA Merge                            v1.0.0 ]
 ******************************************************/
    'EXTRA_INFO' => ( $profiledata['bio'] ) ? nl2br($profiledata['bio']) : '',
/*****[END]********************************************
 [ Mod:    YA Merge                            v1.0.0 ]
 ******************************************************/
    'AVATAR_IMG' => $avatar_img,
/*****[BEGIN]******************************************
 [ Mod:    Admin User Notes                    v1.0.0 ]
 ******************************************************/
    'ADMIN_NOTES' => $profiledata['user_admin_notes'],
    'L_ADMIN_NOTES' => $titanium_lang['Admin_notes'],
/*****[END]********************************************
 [ Mod:    Admin User Notes                    v1.0.0 ]
 ******************************************************/	
/*****[BEGIN]******************************************
 [ Mod:    View Sig                            v1.0.0 ]
 ******************************************************/
    'USER_SIG' => $titanium_user_sig,
    'L_SIG' => $titanium_lang['Signature'],
/*****[END]********************************************
 [ Mod:    View Sig                            v1.0.0 ]
 ******************************************************/

 	'L_CONTACT_DETAILS' => sprintf($titanium_lang['User_contact_details'], $profiledata['username']),
 	'L_FORUM_INFO' => sprintf($titanium_lang['Forum_Info'], $profiledata['username']),
 	'L_ADDITIONAL_INFO' => sprintf($titanium_lang['Additional_info'], $profiledata['username']),
 	'L_USERS_SIGNATURE' => sprintf($titanium_lang['Users_signature'], $profiledata['username']),

/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
    'L_VIEWING_PROFILE' => sprintf($titanium_lang['Viewing_user_profile'], UsernameColor($profiledata['username'])),
    'L_ABOUT_USER' => sprintf($titanium_lang['About_user'], UsernameColor($profiledata['username'])),
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
    'L_AVATAR' => $titanium_lang['Avatar'],
    'L_POSTER_RANK' => $titanium_lang['Poster_rank'],
    'L_JOINED' => $titanium_lang['Joined'],
/*****[BEGIN]******************************************
 [ Mod:    Show Groups                         v1.0.1 ]
 ******************************************************/
    'L_GROUPS' => $titanium_lang['Groups'],
/*****[END]********************************************
 [ Mod:    Show Groups                         v1.0.1 ]
 ******************************************************/
    'L_TOTAL_POSTS' => $titanium_lang['Total_posts'],
/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
    'L_SEARCH_USER_POSTS' => sprintf($titanium_lang['Search_user_posts'], UsernameColor($profiledata['username'])),
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
    'L_CONTACT' => $titanium_lang['Contact'],
    'L_EMAIL_ADDRESS' => $titanium_lang['Email_address'],
    'L_EMAIL' => $titanium_lang['Email'],
    'L_PM' => $titanium_lang['Private_Message'],
	'L_FACEBOOK_PROFILE' => $titanium_lang['FACEBOOK_PROFILE'],
	'L_VISIT_FACEBOOK' => $titanium_lang['Visit_facebook'],

/**
 * Mod: Display the users last visit date and time.
 * @since 2.0.9e
 */
	'L_USER_LAST_VISIT' => $titanium_lang['User_last_visit'],

    'L_WEBSITE' => $titanium_lang['Website'],
/*****[BEGIN]******************************************
 [ Mod:    Birthdays                           v3.0.0 ]
 ******************************************************/
	'L_BIRTHDAY' => $titanium_lang['Birthday'],
/*****[END]********************************************
 [ Mod:    Birthdays                           v3.0.0 ]
 ******************************************************/
    'L_LOCATION' => $titanium_lang['Location'],
    'L_OCCUPATION' => $titanium_lang['Occupation'],
    'L_INTERESTS' => $titanium_lang['Interests'],
/*****[BEGIN]******************************************
 [ Mod:    Gender                              v1.2.6 ]
 ******************************************************/
    'L_GENDER' => $titanium_lang['Gender'], 
/*****[END]********************************************
 [ Mod:    Gender                              v1.2.6 ]
 ******************************************************/
/*****[BEGIN]******************************************
 [ Mod:    YA Merge                            v1.0.0 ]
 ******************************************************/
    'L_EXTRA_INFO' => $titanium_lang['Extra_Info'],
/*****[END]********************************************
 [ Mod:    YA Merge                            v1.0.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:     Arcade                             v3.0.2 ]
 ******************************************************/
    'L_ARCADE' => $titanium_lang['lib_arcade'],
    'URL_STATS' => '<a href="' . append_titanium_sid("statarcade.$phpEx?uid=" . $profiledata['user_id'] ) . '">' . $titanium_lang['statuser'] . '</a>',
/*****[END]********************************************
 [ Mod:     Arcade                             v3.0.2 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:    Online/Offline/Hidden               v2.2.7 ]
 ******************************************************/
    'ONLINE_STATUS_IMG' => $online_status_img,
    'ONLINE_STATUS' => $online_status,
    'L_ONLINE_STATUS' => $titanium_lang['Online_status'],
/*****[END]********************************************
 [ Mod:    Online/Offline/Hidden               v2.2.7 ]
 ******************************************************/

    'U_SEARCH_USER' => append_titanium_sid("search.$phpEx?search_author=" . $u_search_author),

    'S_PROFILE_ACTION' => append_titanium_sid("profile.$phpEx"))
);

/*****[BEGIN]******************************************
 [ Mod:     XData                              v1.0.3 ]
 ******************************************************/
include_once('includes/bbcode.'.$phpEx);

$xd_meta = get_xd_metadata();
$xdata = get_user_xdata($HTTP_GET_VARS[POST_USERS_URL]);
while ( list($code_name, $info) = each($xd_meta) )
{
    $value = isset($xdata[$code_name]) ? $xdata[$code_name] : null;
/*****[ANFANG]*****************************************
 [ Mod:    XData Date Conversion               v0.1.1 ]
 ******************************************************/
		if ($info['field_type'] == 'date')
		{
				$value = create_date($userdata['user_dateformat'], $value, $userdata['user_timezone']);
		}
/*****[ENDE]*******************************************
 [ Mod:    XData Date Conversion               v0.1.1 ]
 ******************************************************/
    if ( !$info['allow_html'] )
    {
        $value = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $value);
    }

    if ( $info['allow_bbcode'] && $profiledata['user_sig_bbcode_uid'] != '')
    {
        $value = bbencode_second_pass($value, $profiledata['xdata_bbcode']);
    }

    if ($info['allow_bbcode'])
    {
        $value = make_clickable($value);
    }

    if ( $info['allow_smilies'] )
    {
        $value = smilies_pass($value);
    }

    $value = str_replace("\n", "\n<br />\n", $value);

    if ( $info['display_viewprofile'] == XD_DISPLAY_NORMAL )
    {
        if ( isset($xdata[$code_name]) )
        {
            $phpbb2_template->assign_block_vars('xdata', array(
                'NAME' => $info['field_name'],
                'VALUE' => $value
                )
            );
        }
    }
    elseif ( $info['display_viewprofile'] == XD_DISPLAY_ROOT )
    {
        if ( isset($xdata[$code_name]) )
        {
            $phpbb2_template->assign_vars( array( $code_name => $value ) );
            $phpbb2_template->assign_block_vars( "switch_$code_name", array() );
        }
        else
        {
            $phpbb2_template->assign_block_vars( "switch_no_$code_name", array() );
        }
    }
}
/*****[END]********************************************
 [ Mod:     XData                              v1.0.3 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:    YA Merge                            v1.0.0 ]
 ******************************************************/
// global $cookie;
// $r_uid = intval($cookie[0]);
// if($profiledata['user_id'] == $r_uid) {
//     get_lang("Your_Account");
//     define_once('CNBYA',true);
//     include_once(NUKE_MODULES_DIR."Your_Account/navbar.php");
//     nav(1);
// }
/*****[END]********************************************
 [ Mod:    YA Merge                            v1.0.0 ]
 ******************************************************/
 
/*****[BEGIN]******************************************
 [ Mod:    Admin User Notes                    v1.0.0 ]
 ******************************************************/
if ( $userdata['user_level'] == ADMIN )
{
  $phpbb2_template->assign_block_vars('switch_admin_notes', array());
}
/*****[END]********************************************
 [ Mod:    Admin User Notes                    v1.0.0 ]
 ******************************************************/
$phpbb2_template->pparse('body');

/*****[BEGIN]******************************************
 [ Mod:    YA Merge                            v1.0.0 ]
 ******************************************************/
// global $admin;
// if (is_admin()) 
// {
//     get_lang("Your_Account");

//     echo "<center>";
//     // if($profiledata['user_lastvisit'] != 0){
//     //     echo "<center>"._LASTVISIT." <strong>".date($phpbb2_board_config['default_dateformat'],$profiledata['user_lastvisit'])."</strong><br />";
//     // } else {
//     //     echo "<center>"._LASTVISIT." <strong>"._LASTNA."</strong><br />";
//     // }

//     if ($profiledata['last_ip'] != 0) {
//         echo "<center>"._LASTIP." <strong>".$profiledata['last_ip']."</strong><br /><br />";
//         echo "[ <a href='".$admin_file.".php?op=ABBlockedIPAdd&amp;tip=".$profiledata['last_ip']."'>"._BANTHIS."</a> ]<br />";
//     }
//     echo "[ <a href=\"modules.php?name=Your_Account&amp;file=admin&amp;op=suspendUser&amp;chng_uid=".$profiledata['user_id']."\">"._SUSPENDUSER."</a> ] ";
//     echo "[ <a href=\"modules.php?name=Your_Account&amp;file=admin&amp;op=deleteUser&amp;chng_uid=".$profiledata['user_id']."\">"._DELETEUSER."</a> ]<br />";
//     echo "</center>";
// }
// global $currentlang;
// if(!isset($currentlang)) { $currentlang = $nuke_config['language']; }
// if (file_exists(NUKE_MODULES_DIR.'Your_Account/language/lang-'.$currentlang.'.php')) {
//   @include_once(NUKE_MODULES_DIR.'Your_Account/language/lang-'.$currentlang.'.php');
// } else {
//   @include_once(NUKE_MODULES_DIR.'Your_Account/language/lang-english.php');
// }
// define_once('CNBYA',true);

// $titanium_username = $profiledata['username'];
// $usrinfo = $profiledata;
// $incsdir = dir(NUKE_MODULES_DIR."Your_Account/includes");
// $incslist = '';
// while($func=$incsdir->read()) {
//     if(substr($func, 0, 3) == "ui-") {
//         $incslist .= "$func ";
//     }
// }
// closedir($incsdir->handle);
// $incslist = explode(" ", $incslist);
// sort($incslist);
// for ($i=0; $i < count($incslist); $i++) {
//     if($incslist[$i]!="") {
//         $counter = 0;
//         include($incsdir->path."/$incslist[$i]");
//     }
// }
/*****[END]********************************************
 [ Mod:    YA Merge                            v1.0.0 ]
 ******************************************************/

include("includes/page_tail.php");

?>