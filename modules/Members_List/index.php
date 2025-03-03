<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/

/***************************************************************************
 *                              memberlist.php
 *                            -------------------
 *   update               : Monday, May 17, 2021
 *   copyright            : (C) 2001 Ernest Allen Buffington
 *   email                : ernest.buffington@gmail.com
 *	 version              : 2.0
 *
 *
 *   begin                : Friday, May 11, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: memberlist.php,v 1.36.2.10 2004/07/11 16:46:15 acydburn Exp $
 *
 *	Module Description: Display's all registered user's.
 *	Module Name: Members List	
 *	Module Version: 1.36.2.10
 *	Original Modifications: Lonestar (http://lonestar-modules.com)	
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
      Advanced Username Color                  v1.0.5       06/11/2005
      Memberlist Find User                     v1.0.0       07/06/2005
      Online/Offline/Hidden (Selection Order)  v1.0.0       08/21/2005
      Online/Offline/Hidden                    v2.2.7       01/24/2006
	  Member Country Flags                     v2.0.7
	  Birthdays                                v3.0.0
 ************************************************************************/
if (!defined('MODULE_FILE'))die('You can\'t access this file directly...');

$titanium_module_name = basename(dirname(__FILE__));
require(NUKE_FORUMS_DIR.'/nukebb.php');

define('IN_PHPBB2', true);
include($phpbb2_root_path.'extension.inc');
include($phpbb2_root_path.'common.'.$phpEx);

# Start session management
$userdata = titanium_session_pagestart($titanium_user_ip, PAGE_VIEWMEMBERS);
titanium_init_userprefs($userdata);

$pageroot = (!empty($HTTP_GET_VARS['page'])) ? $HTTP_GET_VARS['page'] : 1;
$page = (isset($pageroot)) ? intval($pageroot) : 1;

$calc = $phpbb2_board_config['topics_per_page'] * $page;
$phpbb2_start = $calc - $phpbb2_board_config['topics_per_page'];

# just another instance where code is changed without explanation START

# it appears as if a new function was created called get_query_var and
# was used to replace the original code.
// if(isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']))
// 	$mode = (isset($HTTP_POST_VARS['mode'])) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
// else
// 	$mode = 'joined';

# just another instance where code is changed without explanation END

$mode = get_query_var('mode', '_REQUEST', 'string', 'joined');
$sort_order = get_query_var('order', '_REQUEST', 'string');
$sort_order = ($sort_order == 'DESC') ? $sort_order : 'ASC';

$phpbb2_page_title = $titanium_lang['Memberlist'];
include(NUKE_INCLUDE_DIR.'page_header.php');

$phpbb2_template->set_filenames(array(
	'body' => 'memberlist_body.tpl')
);

$phpbb2_template->assign_vars(array(
	'L_PAGE_TITLE' => $titanium_lang['Memberlist'],
	'L_SELECT_SORT_METHOD' => $titanium_lang['Select_sort_method'],
	'L_EMAIL' => $titanium_lang['Email'],
	'L_WEBSITE' => $titanium_lang['Website'],
	'L_FROM' => $titanium_lang['Location'],
	'L_ORDER' => $titanium_lang['Order'],
	'L_LOOK_UP' => $titanium_lang['Look_up_User'],
	'L_FIND_USERNAME' => $titanium_lang['Find_username'],
	'U_SEARCH_USER' => "modules.php?name=Forums&amp;file=search&amp;mode=searchuser&amp;popup=1", 
	'U_SEARCH_EXPLAIN' => $titanium_lang['Search_author_explain'],
	'L_GO' => $titanium_lang['Sort_Go'],
	'L_JOINED' => $titanium_lang['Joined'],
	'L_AGE' => $titanium_lang['Sort_Age'],
	'L_POSTS' => $titanium_lang['Posts'],
	'L_ONLINE_STATUS' => $titanium_lang['Online_status'],
	'L_LAST_VISIT' => $titanium_lang['User_last_visit'],
    
	# Mod: Selection Order v1.0.0 START
    # Mod: Birthdays v3.0.0 START
	'S_MODE_SELECT' => select_box('mode',$mode,array('joined' => 
	                          $titanium_lang['Sort_Joined'],'username' => 
						   $titanium_lang['Sort_Username'], 'location' => 
						      $titanium_lang['Sort_Location'], 'posts' => 
							       $titanium_lang['Sort_Posts'], 'age' => 
								   $titanium_lang['Sort_Age'], 'email' => 
							   $titanium_lang['Sort_Email'], 'website' => 
							  $titanium_lang['Sort_Website'], 'topten' => 
							  $titanium_lang['Sort_Top_Ten'], 'online' => 
							           $titanium_lang['Current_status'])),
	# Mod: Selection Order v1.0.0 END
    # Mod: Birthdays v3.0.0 END

	'S_ORDER_SELECT' 		=> select_box('order',$sort_order,array('ASC' => $titanium_lang['Sort_Ascending'], 'DESC' => $titanium_lang['Sort_Descending'])),
	'S_MODE_ACTION' 		=> append_titanium_sid("memberlist.$phpEx"))
);

# SEARCH FOR USERS VIA THE ALPHABET LISTING - START
$alpha_range = array();
$alpha_letters = array();
$alpha_letters = range('A','Z');
$alpha_start = array('All','#');
$alpha_range = array_merge($alpha_start, $alpha_letters);
$i = 0;
while($i < count($alpha_range)):
	if ($alpha_range[$i] != 'All'): 
		$temp = ($alpha_range[$i] != '#') ? strtolower($alpha_range[$i]) : 'num';
		$alphanum_search_url = 'modules.php?name='.basename(dirname(__FILE__)).'&amp;mode=letter&amp;alphanum='.$temp;
	else: 
		$alphanum_search_url = 'modules.php?name='.basename(dirname(__FILE__));
	endif;
	$phpbb2_template->assign_block_vars('alphanumsearch', array(
		'SEARCH_SIZE' 	=> floor(100/count($alpha_range)) . '%',
		'SEARCH_TERM' 	=> $alpha_range[$i],
		'SEARCH_LINK' 	=> $alphanum_search_url)
	);
	$i++;
endwhile;
# SEARCH FOR USERS VIA THE ALPHABET LISTING - END

# search switch START
switch($mode):
	case 'letter':
	$alphanum = (isset($HTTP_POST_VARS['alphanum'])) ? htmlspecialchars($HTTP_POST_VARS['alphanum']) : htmlspecialchars($HTTP_GET_VARS['alphanum']);
	$alphanum = str_replace("\'", "''",$alphanum);
	$where = ($alphanum == 'num') ? " AND `username` NOT RLIKE '^[A-Z]' " : " AND `username` LIKE '".$alphanum."%' ";
	$order_by = 'user_id '.$sort_order.' LIMIT '.$phpbb2_start.', '.$phpbb2_board_config['topics_per_page']; break;
	break;
	case 'age':
	$phpbb2_age_order = $sort_order == 'ASC' ? 'DESC' : 'ASC';
	$order_by = 'coalesce(user_birthday2,';
	$order_by.= ($phpbb2_age_order == 'ASC') ? '99999999' : '0';
	$order_by.= ") $phpbb2_age_order LIMIT $phpbb2_start, ".$phpbb2_board_config['topics_per_page'];
	break;
	case 'joined': 		
	$order_by = 'user_id '.$sort_order.' LIMIT '.$phpbb2_start.', '.$phpbb2_board_config['topics_per_page']; 
	break;
	case 'username': 	
	$order_by = 'username '.$sort_order.' LIMIT '.$phpbb2_start.', '.$phpbb2_board_config['topics_per_page']; 
	break;
	case 'location': 	
	$order_by = 'user_from '.$sort_order.' LIMIT '.$phpbb2_start.', '.$phpbb2_board_config['topics_per_page']; 
	break;
	case 'posts': 		
	$order_by = 'user_posts '.$sort_order.' LIMIT '.$phpbb2_start.', '.$phpbb2_board_config['topics_per_page']; 
	break;
	case 'email': 		
	$order_by = 'user_email '.$sort_order.' LIMIT '.$phpbb2_start.', '.$phpbb2_board_config['topics_per_page']; 
	break;
	case 'website': 	
	$order_by = 'user_website '.$sort_order.' LIMIT '.$phpbb2_start.', '.$phpbb2_board_config['topics_per_page']; 
	break;
	case 'topten': 		
	$order_by = 'user_posts '.$sort_order.' LIMIT 10'; 
	break;
	case 'online': 		
	$order_by = 'user_session_time '.$sort_order.' LIMIT '.$phpbb2_start.', '.$phpbb2_board_config['topics_per_page']; 
	break;
	default: 			
	$order_by = 'user_id '.$sort_order.' LIMIT '.$phpbb2_start.', '.$phpbb2_board_config['topics_per_page']; break;
endswitch;
# search switch END

$titanium_username = (!empty($HTTP_POST_VARS['username'])) ? $HTTP_POST_VARS['username'] : '';
if ($titanium_username && isset($HTTP_POST_VARS['submituser'])):
    # search for users with a wildcard
	$search_author = str_replace('*', '%', trim($titanium_username));
	if((strpos($search_author, '%') !== false) && (strlen(str_replace('%', '',$search_author)) < $phpbb2_board_config['search_min_chars']))
	$search_author = '';

	$sql = "SELECT username,
	                   name, 
	  	        user_avatar, 
	       user_avatar_type, 
	       user_allowavatar, 
	                user_id, 
				 user_posts, 
				user_gender, 
			  user_facebook, 
			  user_birthday, 
		   birthday_display, 
		       user_regdate, 
			      user_from, 
			 user_from_flag, 
			   user_website, 
	  user_allow_viewonline, 
	      user_session_time, 
		     user_lastvisit 
	
	FROM ".USERS_TABLE." 
	WHERE username LIKE '".str_replace("\'", "''",$search_author)."' 
	AND user_id <> ".ANONYMOUS." LIMIT 1";
    
	# this is the original SQL queery START
	$deprecated_sql = "SELECT username, 
	        		              name, 
						   user_avatar, 
	                  user_avatar_type, 
	                  user_allowavatar, 
	                           user_id, 
							user_posts, 
						   user_gender, 
						 user_facebook, 
						 user_birthday, 
					  birthday_display, 
					      user_regdate, 
						     user_from, 
					    user_from_flag, 
						  user_website, 
				 user_allow_viewonline, 
				     user_session_time, 
					    user_lastvisit 
						
	FROM ".USERS_TABLE." 
	WHERE username = '$titanium_username' 
	AND user_id <> ".ANONYMOUS." LIMIT 1";
	# this is the original SQL queery END


else:
	$sql = "SELECT username,
	                   name, 
                user_avatar, 
	       user_avatar_type, 
	       user_allowavatar, 
	                user_id, 
				 user_posts, 
				user_gender, 
			  user_facebook, 
			  user_birthday, 
		   birthday_display, 
		       user_regdate, 
			      user_from, 
			 user_from_flag, 
			   user_website, 
	  user_allow_viewonline, 
	      user_session_time, 
		     user_lastvisit 
			 
    FROM ".USERS_TABLE." WHERE user_id <> ".ANONYMOUS."".$where." ORDER BY $order_by";
endif;

if(!($result = $titanium_db->sql_query($sql)))
message_die(GENERAL_ERROR, 'Could not query users', '', __LINE__, __FILE__, $sql);

global $textcolor1;
$theme_name = get_theme();

if($row = $titanium_db->sql_fetchrow($result)):

	$i = 0;
	do
	{
		$realname = $row['name'];
		$titanium_username = $row['username'];
		$titanium_user_id = intval($row['user_id']);
		
		# Get the users location and flag
		$titanium_user_from = (!empty($row['user_from'])) ? $row['user_from'] : '&nbsp;';

		$titanium_user_flag = (!empty($row['user_from_flag'])) ? 
		'&nbsp;'.get_evo_icon('countries '.str_replace('.png','',$row['user_from_flag'])).'&nbsp;' : '&nbsp;'.get_evo_icon('countries unknown').'&nbsp;';
		 
		# Calculate the users age.
		$phpbb2_bday_month_day = floor($row['user_birthday'] / 10000);
		$phpbb2_bday_year_age = ($row['birthday_display'] != BIRTHDAY_NONE && $row['birthday_display'] != BIRTHDAY_DATE) ? $row['user_birthday'] - 10000*$phpbb2_bday_month_day : 0;
		$phpbb2_fudge = (gmdate('md') < $phpbb2_bday_month_day) ? 1 : 0;
		$phpbb2_age = ($phpbb2_bday_year_age) ? gmdate('Y')-$phpbb2_bday_year_age-$phpbb2_fudge : false;
		
		if(empty($phpbb2_age))
		$phpbb2_age = 'Hidden';
		else
		$phpbb2_age .= ' yrs';
		
		# Website URL
		if(!empty($row['user_website']))
		$www = '<a href="'.$row['user_website'].'" target="_blank"><img class="tooltip-html copyright" alt="Male" title="Visit '.$titanium_username.'\'s Web Portal" width="30"alt="online" src="themes/'.$theme_name.'/forums/images/status/icons8-website-512.png" /></a>';
		else
		$www = '';
		
		# Date Joined
		$joined = $row['user_regdate'];
		
        /*****[BEGIN]******************************************
        [ Mod:    Forum Index Avatar Mod                 v1.0]
        ******************************************************/
        switch($row['user_avatar_type'])
        {
           case USER_AVATAR_UPLOAD:
           $current_avatar = $phpbb2_board_config['avatar_path'] . '/' . $row['user_avatar'];
           break;
           case USER_AVATAR_REMOTE:
           $current_avatar = resize_avatar($row['user_avatar']);
           break;
           case USER_AVATAR_GALLERY:
           $current_avatar = $phpbb2_board_config['avatar_gallery_path'] . '/' . (($row['user_avatar'] 
			== 'blank.gif' || $row['user_avatar'] == 'gallery/blank.png') ? 'blank.png' : $row['user_avatar']);
           break;
		}
        /*****[END]********************************************
        [ Mod:    Forum Index Avatar Mod                 v1.0]
         ******************************************************/
		
		# Number of Posts
		$phpbb2_posts = ($row['user_posts']) ? '<a href="modules.php?name=Forums&file=search&search_author='.$titanium_username.'">'.$row['user_posts'].'</a>' : 0;
		
		# Private message link
		$pm = '<a href="'.append_titanium_sid("privmsg.$phpEx?mode=post&amp;".POST_USERS_URL."=$titanium_user_id").'"><img class="tooltip-html copyright" alt="Male" title="Send A Private Message To '.$titanium_username.'" width="30"alt="online" src="themes/'.$theme_name.'/forums/images/status/icons8-send-80.png" /></a>';
		
		# does the person have a dick START
		if($row['user_gender'] ==1)
		$gender = '<img class="tooltip-html copyright" alt="Male" title="Male" width="30"alt="online" src="themes/'.$theme_name.'/forums/images/status/icons8-person-male-skin-type-5-80.png" />';
		elseif($row['user_gender'] == 2)
		$gender = '<img class="tooltip-html copyright" alt="Female" title="Female" width="30"alt="online" src="themes/'.$theme_name.'/forums/images/status/icons8-person-female-80.png" />';
		else // show an invisble picel when the person does not specify sex
		$gender = '<img class="tooltip-html copyright" alt="Undecided" title="Undecided" width="1" src="themes/'.$theme_name.'/images/invisible_pixel.gif" />';
		
		# does the person have a dick END
		
		# facebook mod v1.0 START
		if(!empty($row['user_facebook']))
		$facebook = '<a href="https://www.facebook.com/'.$row['user_facebook'].'" target="_blank"><img class="tooltip-html copyright" alt="Male" title="View '.$titanium_username.'\'s Facebook Page" width="30"alt="online" src="themes/'.$theme_name.'/forums/images/status/icons8-facebook-80.png" /></a>';
		else
		$facebook = '';
		# facebook mod v1.0 END
		
		# USers last visit
		$last_visit = ($row['user_lastvisit'] == 0) ? '' : formatTimestamp($row['user_lastvisit'],'M d, Y');

       # This is broken in UK version
	   # Mod: Online/Offline/Hidden v2.2.7 START
	   if(!$row['user_allow_viewonline']):
	   $online_status = '<img class="tooltip-html copyright" alt="Hidden" title="Hidden" alt="Hidden" width="30" height="30" 
	   src="themes/'.$theme_name.'/forums/images/status/icons8-invisible-512.png" />';
   
	   elseif($row['user_session_time'] >= (time()-$phpbb2_board_config['online_time'])):
	   $theme_name = get_theme();
	   $online_status = '<a class="tooltip-html copyright" href="'.append_titanium_sid("viewonline.$phpEx").'" title="'.sprintf($titanium_lang['is_online'],$row['username']).'"'.$online_color.'><img 
	   alt="online" src="themes/'.$theme_name.'/forums/images/status/online_bgcolor_one.gif" /></a>';
	   else:
       $online_status = '<span class="tooltip-html copyright" title="'.sprintf($titanium_lang['is_offline'],$row['username']).'"'.$offline_color.'><img 
	   alt="online" src="themes/'.$theme_name.'/forums/images/status/offline_bgcolor_one.gif" /></span>';
       endif;
       # Mod: Online/Offline/Hidden v2.2.7 END
        
		if(strlen($titanium_user_from) == 6)
		$titanium_user_from = 'The InterWebs';

        if (!is_admin())
        if(!$row['user_allow_viewonline'])
		continue;
		
        # Alternate the row class
        $row_class = ( !($i % 2) ) ? 'row2' : 'row3';
		$phpbb2_template->assign_block_vars('memberrow', array(
			'ROW_NUMBER' => $i + ( $phpbb2_start + 1 ),
			'ROW_CLASS' => $row_class,
			'USERNAME' => UsernameColor($row['username']),
			'FROM' => $titanium_user_from,
			'FLAG' => $titanium_user_flag,
			'JOINED' => $joined,
			'AGE' => $phpbb2_age,
			'POSTS' => $phpbb2_posts,
			'PM' => $pm,
			'WWW' => $www,
			'GENDER' => $pm.' '.$www.' '.$facebook.' '.$gender,
			'LAST_ACTIVE' => $last_visit,
			'FACEBOOK' => $facebook,
			'STATUS' => $online_status,
			'CURRENT_AVATAR' => '<img class="rounded-corners-header" height="auto" width="30" src="'.$current_avatar.'">&nbsp;',
			'U_VIEWPROFILE' => "modules.php?name=Profile&mode=viewprofile&amp;" . POST_USERS_URL . "=$titanium_user_id")
		);
		$i++;
	} 
	while ( $row = $titanium_db->sql_fetchrow($result) );
	$titanium_db->sql_freeresult($result);

else:
	$phpbb2_template->assign_block_vars('no_username', array(
		'NO_USER_ID_SPECIFIED' => $titanium_lang['No_user_id_specified'])
	);
endif;

$total_phpbb2_found = $titanium_db->sql_unumrows($sql);

# Generate the page numbers
$alphanum 	= ( isset($HTTP_POST_VARS['alphanum']) ) ? htmlspecialchars($HTTP_POST_VARS['alphanum']) : htmlspecialchars($HTTP_GET_VARS['alphanum']);
$where 		= ( $alphanum == 'num' ) ? " AND `username` NOT RLIKE '^[A-Z]' " : " AND `username` LIKE '".$alphanum."%' ";
$sql1 		= "SELECT count(*) AS total FROM " . USERS_TABLE . " WHERE user_id <> " . ANONYMOUS.$where;
$result1 	= $titanium_db->sql_query($sql1);
$total 		= $titanium_db->sql_fetchrow($result1);

if($total['total'] > $phpbb2_board_config['topics_per_page'] && $mode != 'topten' || $phpbb2_board_config['topics_per_page'] < 10):
	if(isset($pageroot))
	$page = intval($pageroot);
	else
	$page = 1;
	$pagination = '';
	$redirect = 'modules.php?name=Members_List'.(($HTTP_GET_VARS['mode']) ? '&mode=letter&alphanum='.$HTTP_GET_VARS['alphanum'] : '');
	if(isset($page)):
		$totalPages = ceil($total['total'] / $phpbb2_board_config['topics_per_page']);
		if($totalPages == 1)
		return '';
		$on_page = floor($phpbb2_start / $phpbb2_board_config['topics_per_page']) + 1;
		if($totalPages > 10):
			$init_page_max = ( $totalPages > 3 ) ? 3 : $totalPages;
			for($i = 1; $i < $init_page_max + 1; $i++):
				$pagination .= ( $i == $on_page ) ? '<span style="font-weight:bold; font-size:13px;">'.$i.'</span>' : '<a href="'.$redirect.'&amp;page='.$i.'"><span>'.$i.'</span></a>';
				if ( $i <  $init_page_max )
				$pagination .= "&nbsp;";
			endfor;
			 if($totalPages > 3):
				if($on_page > 1 && $on_page < $totalPages):
					$pagination .= ( $on_page > 5 ) ? ' ... ' : '&nbsp;';
					$init_page_min = ( $on_page > 4 ) ? $on_page : 5;
					$init_page_max = ( $on_page < $totalPages - 4 ) ? $on_page : $totalPages - 4;
					for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++):
						$pagination .= ($i == $on_page) ? '<span style="font-weight:bold; font-size:13px;">'.$i.'</span>' : '<a href="'.$redirect.'&amp;page='.$i.'"><span>'.$i.'</span></a>';
						if ( $i <  $init_page_max + 1 )
							$pagination .= '&nbsp;';
					endfor;
					$pagination .= ( $on_page < $totalPages - 4 ) ? ' ... ' : '&nbsp;';
				else:
					$pagination .= ' ... ';
				endif;
				for($i = $totalPages - 2; $i < $totalPages + 1; $i++):
					$pagination .= ( $i == $on_page ) ? '<span style="font-weight:bold; font-size:13px;">'.$i.'</span>'  : '<a href="'.$redirect.'&amp;page='.$i.'"><span>'.$i.'</span></a>';
					if( $i <  $totalPages )
						$pagination .= "&nbsp;";
				endfor;		
			endif;
		else:
			for($i = 1; $i < $totalPages + 1; $i++):
				$pagination .= ( $i == $on_page ) ? '<span style="font-weight:bold; font-size:13px;">'.$i.'</span>' : '<a href="'.$redirect.'&amp;page='.$i.'"><span>'.$i.'</span></a>';
				if ( $i <  $totalPages )
			    $pagination .= '&nbsp;';
			endfor;
		endif;
		if($page <= 1):
			$pagination = '<span>'.$titanium_lang['Goto_page_prev'].'</span>&nbsp;'.$pagination.'&nbsp';
		else:
			$j = $page - 1;
			$pagination = '<span><a href="'.$redirect.'&amp;page='.$j.'">'.$titanium_lang['Goto_page_prev'].'</a></span>&nbsp;'.$pagination.'&nbsp;';
		endif;
		if($page == $totalPages):
			$pagination .= '<span>'.$titanium_lang['Goto_page_next'].'</span>';
		else:
			$j = $page + 1;
			$pagination .= '<a href="'.$redirect.'&amp;page='.$j.'">'.$titanium_lang['Goto_page_next'].'</a>';
		endif;
	endif;
	$phpbb2_template->assign_block_vars('pagination', array(
		'PAGINATION'	=> $pagination,
		'TOTAL' 		=> $total_phpbb2_found,
		'PERPAGE'		=> $phpbb2_board_config['topics_per_page'])
	);
endif;
$phpbb2_template->pparse('body');
//echo '<span style="float:right; padding-right:5px;"><a class="font-family" href="#module-copyright-popup" rel="modal:open">'.str_replace('_',' ',$name).' &#169;</a></span><br />';
include(NUKE_INCLUDE_DIR.'page_tail.php');
?>
