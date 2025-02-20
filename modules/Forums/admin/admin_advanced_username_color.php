<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/***************************************************************************
 *                            admin_advanced_username_color.php
 *                           -----------------------------------
 *        Version            : 1.0.5
 *        Email            : austin@phpbb-amod.com
 *        Site            : http://phpbb-tweaks.com/
 *        Copyright        : aUsTiN-Inc 2003/4
 *
 ***************************************************************************/
 
define('IN_PHPBB2', 1);

if( !empty($setmodules) )
{
    $file = basename(__FILE__);
    $titanium_module['AUC']['Configuration']     = $file;
    return;
}

$phpbb2_root_path = "./../";
require($phpbb2_root_path . 'extension.inc');

require('./pagestart.' . $phpEx);
include($phpbb2_root_path . 'language/lang_' . $phpbb2_board_config['default_lang'] . '/lang_auc.' . $phpEx);

    if(isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']))
        {    
    $mode = (isset( $HTTP_POST_VARS['mode'])) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
        }
    else
        {
    $mode = '';
        }

    global $titanium_prefix;
    define('COLORS', $titanium_prefix .'_bbadvanced_username_color');    
    $link = append_titanium_sid("admin_advanced_username_color.". $phpEx);
    
    $q = "SELECT *
          FROM ". COLORS ."
          WHERE group_weight = '0'";
    $r = $titanium_db->sql_query($q);
    while ($rows = $titanium_db->sql_fetchrow($r))
        {
        if ($rows['group_id'])
            {
        $q1 = "UPDATE ". COLORS ."
               SET group_weight = '". $rows['group_id'] ."'
               WHERE group_id = '". $rows['group_id'] ."'";
        $titanium_db->sql_query($q1);
            }
        }
        
    if ($mode == 'move_up')
        {
    $group = ($_GET['id']) ? $_GET['id'] : $HTTP_GET_VARS['id'];
    
    #==== Get current weight & weight above it to switch them.
    $q = "SELECT *
          FROM ". COLORS ."
          WHERE group_id = '". intval($group) ."'";
    $r = $titanium_db->sql_query($q);
    $group_one = $titanium_db->sql_fetchrow($r);
    
    $above_it = $group_one['group_weight'] - 1;
    
    $q = "SELECT *
          FROM ". COLORS ."
          WHERE group_weight = '". intval($above_it) ."'";
    $r = $titanium_db->sql_query($q);
    $group_two = $titanium_db->sql_fetchrow($r);
    
    $group_one_id         = $group_one['group_id'];    
    $group_one_weight     = $group_one['group_weight'];
    $group_two_id        = $group_two['group_id'];
    $group_two_weight    = $group_two['group_weight'];
    
    #==== Set new settings for the groups
    $q = "UPDATE ". COLORS ."
          SET group_weight = '". $group_two_weight ."'
          WHERE group_id = '". $group_one_id ."'";
    $titanium_db->sql_query($q);
    
    $q = "UPDATE ". COLORS ."
          SET group_weight = '". $group_one_weight ."'
          WHERE group_id = '". $group_two_id ."'";
    $titanium_db->sql_query($q);
    
    message_die(GENERAL_MESSAGE, 'Saved');
        }
        
    if ($mode == 'move_down')
        {
    $group = ($_GET['id']) ? $_GET['id'] : $HTTP_GET_VARS['id'];
    
    #==== Get current weight & weight under it to switch them.
    $q = "SELECT *
          FROM ". COLORS ."
          WHERE group_id = '". intval($group) ."'";
    $r = $titanium_db->sql_query($q);
    $group_one = $titanium_db->sql_fetchrow($r);
    
    $below_it = $group_one['group_weight'] + 1;
    
    $q = "SELECT *
          FROM ". COLORS ."
          WHERE group_weight = '". intval($below_it) ."'";
    $r = $titanium_db->sql_query($q);
    $group_two = $titanium_db->sql_fetchrow($r);
    
    $group_one_id         = $group_one['group_id'];    
    $group_one_weight     = $group_one['group_weight'];
    $group_two_id        = $group_two['group_id'];
    $group_two_weight    = $group_two['group_weight'];
    
    #==== Set new settings for the groups
    $q = "UPDATE ". COLORS ."
          SET group_weight = '". $group_two_weight ."'
          WHERE group_id = '". $group_one_id ."'";
    $titanium_db->sql_query($q);
    
    $q = "UPDATE ". COLORS ."
          SET group_weight = '". $group_one_weight ."'
          WHERE group_id = '". $group_two_id ."'";
    $titanium_db->sql_query($q);
    
    message_die(GENERAL_MESSAGE, 'Saved');
        }
        
if($mode == "main" || !$mode)
        {
    echo "<table width='100%' border='0' class='forumline' cellspacing='2' align='center' valign='middle'>";
    echo "    <tr>";
    echo "        <th class='thHead'>";
    echo "            ". $titanium_lang['admin_main_header_c'];
    echo "        </th>";
    echo "    </tr>";
    echo "</table>";
    echo "<br />";
    echo "<table width='75%' border='0' class='forumline' cellspacing='2' align='center' valign='middle'>";
    echo "    <tr>";
    echo "        <th class='thHead' align='center' width='25%'>&nbsp;</th>";
    echo "        <th class='thHead' align='center' width='25%'>";    
    echo "            ". $titanium_lang['view_group_names'];
    echo "        </th>";
    echo "        <th class='thHead' align='center' width='25%'>";    
    echo "            ". $titanium_lang['view_group_colors'];    
    echo "        </th>";
    echo "        <th class='thHead' align='center' width='25%'>";    
    echo "            ". $titanium_lang['view_group_colors_2'];    
    echo "        </th>";        
    echo "    </tr>";

    $q = "SELECT *
          FROM ". COLORS ."
          WHERE group_id > '0'";
    $r        = $titanium_db -> sql_query($q);
    $total    = $titanium_db->sql_numrows($r);    
    
    $w = 1;
    $q = "SELECT *
          FROM ". COLORS ."
          WHERE group_id > '0'
          ORDER BY group_weight ASC";
    $r            = $titanium_db -> sql_query($q);
    while($row     = $titanium_db -> sql_fetchrow($r))
        {    
        if ($w != 1)
            $links = '&nbsp;&nbsp;<a href="admin_advanced_username_color.'. $phpEx .'?mode=move_up&amp;id='. $row['group_id'] .'&amp;sid='. $userdata['session_id'] .'">Up</a>';
        else
            $links = '&nbsp;&nbsp;<a href="admin_advanced_username_color.'. $phpEx .'?mode=move_down&amp;id='. $row['group_id'] .'&amp;sid='. $userdata['session_id'] .'">Down</a>';
            
    echo "    <tr>";
    echo "        <td align='left' width='25%' class='row2'>";
    echo "            <span class='genmed'>";    
    echo "                ". $w . $links;
    echo "            </span>";        
    echo "        </td>";    
    echo "        <td align='left' width='25%' class='row2'>";
    echo "            <span class='genmed'>";    
    echo "                ". $row['group_name'];
    echo "            </span>";        
    echo "        </td>";
    echo "        <td align='left' width='25%' class='row2'>";
    echo "            <span class='genmed'>";            
    echo "                ". $row['group_color'];
    echo "            </span>";            
    echo "        </td>";
    echo "        <td align='left' width='25%' class='row2'>";
    echo "            <span class='genmed'>";            
    echo "                <font color='#". $row['group_color'] ."'>". $titanium_lang['view_group_colors_3'] ."</font>";    
    echo "            </span>";    
    echo "        </td>";                
    echo "    </tr>";
    $w++;                    
        }
    echo "</table>";
    echo "<br /><br />";
    echo "<table border='0' align='center' valign='top' class='forumline' width='100%'>";
    echo "    <tr>";
    echo "        <td align='center' valign='top' width='100%' class='row2'>";
    echo "            <span class='genmed'>";
    echo "                ". $titanium_lang['add_new_color'];
    echo "            </span>";
    echo "        </td>";
    echo "    </tr>";        
    echo "</table>";    
    echo "<form name='add_color' action='$link' method='post'>";        
    echo "<table border='0' align='center' valign='top' class='forumline' width='100%'>";
    echo "    <tr>";
    echo "        <td align='left' valign='top' width='50%' class='row2'>";
    echo "            <span class='genmed'>";
    echo "                ". $titanium_lang['add_new_color_1'];
    echo "            </span>";
    echo "        </td>";
    echo "        <td align='center' valign='top' width='50%' class='row2'>";            
    echo "            <input type='text' name='new_name' class='post' value=''>";
    echo "        </td>";
    echo "    </tr>";
    echo "    <tr>";
    echo "        <td align='left' valign='top' width='50%' class='row2'>";
    echo "            <span class='genmed'>";
    echo "                ". $titanium_lang['add_new_color_2'];
    echo "            </span>";
    echo "        </td>";
    echo "        <td align='center' valign='top' width='50%' class='row2'>";            
    echo "            <input type='text' name='new_color' class='post' value=''>";
    echo "        </td>";
    echo "    </tr>";    
    echo "</table>";
    echo "<br />";
    echo "<table border='0' align='center' valign='top'>";    
    echo "    <tr>";    
    echo "        <td align='center' valign='middle' width='100%' class='row2'>";    
    echo "            <input type='hidden' name='mode' value='add_new_color'>";            
    echo "            <input type='submit' class='mainoption' value='". $titanium_lang['add_new_color_3'] ."' onchange='document.add_color.submit()'>";       
    echo "        </td>";
    echo "    </tr>";                    
    echo "</table>";    
    echo "</form>";
    echo "<br /><br />";    

    echo "<table border='0' align='center' valign='top' class='forumline' width='100%'>";
    echo "    <tr>";
    echo "        <td align='center' valign='top' width='100%' class='row2'>";
    echo "            <span class='genmed'>";
    echo "                ". $titanium_lang['edit_color'];
    echo "            </span>";
    echo "        </td>";
    echo "    </tr>";        
    echo "</table>";    
    echo "<form name='edit_group' action='$link' method='post'>";        
    echo "<table border='0' align='center' valign='top' class='forumline' width='100%'>";
    echo "    <tr>";
    echo "        <td align='left' valign='top' width='50%' class='row2'>";
    echo "            <span class='genmed'>";
    echo "                ". $titanium_lang['edit_color_1'];
    echo "            </span>";
    echo "        </td>";
    echo "        <td align='center' valign='top' width='50%' class='row2'>";            
    echo "            <select name='group'>";
    echo "                <option selected value=''>". $titanium_lang['edit_color_2'] ."</option>";
    
    $q = "SELECT *
          FROM ". COLORS ."
          WHERE group_id > '0'
          ORDER BY group_name ASC";
    $r            = $titanium_db -> sql_query($q);
    while($row     = $titanium_db -> sql_fetchrow($r))
        {    
    $name     = $row['group_name'];
    $id     = $row['group_id'];    
    echo "                <option value='". $id ."'>$name</option>";                    
        }
            
    echo "            </select>";
    echo "        </td>";
    echo "    </tr>";
    echo "</table>";
    echo "<br />";
    echo "<table border='0' align='center' valign='top'>";    
    echo "    <tr>";    
    echo "        <td align='center' valign='middle' width='100%' class='row2'>";    
    echo "            <input type='hidden' name='mode' value='edit_exis_group'>";            
    echo "            <input type='submit' class='mainoption' value='". $titanium_lang['edit_color_3'] ."' onchange='document.edit_group.submit()'>";       
    echo "        </td>";
    echo "    </tr>";                    
    echo "</table>";    
    echo "</form>";
    echo "<br /><br />";
    
    echo "<table border='0' align='center' valign='top' class='forumline' width='100%'>";
    echo "    <tr>";
    echo "        <td align='center' valign='top' width='100%' class='row2'>";
    echo "            <span class='genmed'>";
    echo "                ". $titanium_lang['delete_color'];
    echo "            </span>";
    echo "        </td>";
    echo "    </tr>";        
    echo "</table>";    
    echo "<form name='del_group' action='$link' method='post'>";        
    echo "<table border='0' align='center' valign='top' class='forumline' width='100%'>";
    echo "    <tr>";
    echo "        <td align='left' valign='top' width='50%' class='row2'>";
    echo "            <span class='genmed'>";
    echo "                ". $titanium_lang['delete_color_1'];
    echo "            </span>";
    echo "        </td>";
    echo "        <td align='center' valign='top' width='50%' class='row2'>";            
    echo "            <select name='group'>";
    echo "                <option selected value=''>". $titanium_lang['delete_color_2'] ."</option>";
    
    $q = "SELECT *
          FROM ". COLORS ."
          WHERE group_id > '0'
          ORDER BY group_name ASC";
    $r            = $titanium_db -> sql_query($q);
    while($row     = $titanium_db -> sql_fetchrow($r))
        {    
    $name     = $row['group_name'];
    $id     = $row['group_id'];    
    echo "                <option value='". $id ."'>$name</option>";                    
        }
            
    echo "            </select>";
    echo "        </td>";
    echo "    </tr>";
    echo "</table>";
    echo "<br />";
    echo "<table border='0' align='center' valign='top'>";    
    echo "    <tr>";    
    echo "        <td align='center' valign='middle' width='100%' class='row2'>";    
    echo "            <input type='hidden' name='mode' value='del_exis_group'>";            
    echo "            <input type='submit' class='mainoption' value='". $titanium_lang['delete_color_3'] ."' onchange='document.del_group.submit()'>";       
    echo "        </td>";
    echo "    </tr>";                    
    echo "</table>";    
    echo "</form>";
    echo "<br /><br />";
    echo "
<table width='100%' cellspacing='0' cellpadding='2' border='0' align='center' class='forumline'>
<tr><td bgcolor=#150000>150000</td><td bgcolor=#2a0000>2a0000</td><td bgcolor=#3f0000>3f0000</td><td bgcolor=#550000>550000</td><td bgcolor=#6a0000>6a0000</td><td bgcolor=#7f0000>7f0000</td><td bgcolor=#940000>940000</td><td bgcolor=#aa0000>aa0000</td><td bgcolor=#bf0000>bf0000</td><td bgcolor=#d40000>d40000</td><td bgcolor=#e90000>e90000</td><td bgcolor=#ff0000>ff0000</td><td bgcolor=#ff1515>ff1515</td><td bgcolor=#ff2a2a>ff2a2a</td><td bgcolor=#ff3f3f>ff3f3f</td><td bgcolor=#ff5555>ff5555</td><td bgcolor=#ff6a6a>ff6a6a</td><td bgcolor=#ff7f7f>ff7f7f</td><td bgcolor=#ff9494>ff9494</td><td bgcolor=#ffaaaa>ffaaaa</td><td bgcolor=#ffbfbf>ffbfbf</td><td bgcolor=#ffd4d4>ffd4d4</td><td bgcolor=#ffe9e9>ffe9e9</td></tr>
<tr><td bgcolor=#150100>150100</td><td bgcolor=#2a0200>2a0200</td><td bgcolor=#3f0400>3f0400</td><td bgcolor=#550500>550500</td><td bgcolor=#6a0700>6a0700</td><td bgcolor=#7f0800>7f0800</td><td bgcolor=#940900>940900</td><td bgcolor=#aa0b00>aa0b00</td><td bgcolor=#bf0c00>bf0c00</td><td bgcolor=#d40e00>d40e00</td><td bgcolor=#e90f00>e90f00</td><td bgcolor=#ff1100>ff1100</td><td bgcolor=#ff2415>ff2415</td><td bgcolor=#ff382a>ff382a</td><td bgcolor=#ff4c3f>ff4c3f</td><td bgcolor=#ff6055>ff6055</td><td bgcolor=#ff746a>ff746a</td><td bgcolor=#ff887f>ff887f</td><td bgcolor=#ff9b94>ff9b94</td><td bgcolor=#ffafaa>ffafaa</td><td bgcolor=#ffc3bf>ffc3bf</td><td bgcolor=#ffd7d4>ffd7d4</td><td bgcolor=#ffebe9>ffebe9</td></tr>
<tr><td bgcolor=#150200>150200</td><td bgcolor=#2a0500>2a0500</td><td bgcolor=#3f0800>3f0800</td><td bgcolor=#550b00>550b00</td><td bgcolor=#6a0e00>6a0e00</td><td bgcolor=#7f1100>7f1100</td><td bgcolor=#941300>941300</td><td bgcolor=#aa1600>aa1600</td><td bgcolor=#bf1900>bf1900</td><td bgcolor=#d41c00>d41c00</td><td bgcolor=#e91f00>e91f00</td><td bgcolor=#ff2200>ff2200</td><td bgcolor=#ff3415>ff3415</td><td bgcolor=#ff462a>ff462a</td><td bgcolor=#ff593f>ff593f</td><td bgcolor=#ff6b55>ff6b55</td><td bgcolor=#ff7e6a>ff7e6a</td><td bgcolor=#ff907f>ff907f</td><td bgcolor=#ffa294>ffa294</td><td bgcolor=#ffb5aa>ffb5aa</td><td bgcolor=#ffc7bf>ffc7bf</td><td bgcolor=#ffdad4>ffdad4</td><td bgcolor=#ffece9>ffece9</td></tr>
<tr><td bgcolor=#150400>150400</td><td bgcolor=#2a0800>2a0800</td><td bgcolor=#3f0c00>3f0c00</td><td bgcolor=#551100>551100</td><td bgcolor=#6a1500>6a1500</td><td bgcolor=#7f1900>7f1900</td><td bgcolor=#941d00>941d00</td><td bgcolor=#aa2200>aa2200</td><td bgcolor=#bf2600>bf2600</td><td bgcolor=#d42a00>d42a00</td><td bgcolor=#e92e00>e92e00</td><td bgcolor=#ff3300>ff3300</td><td bgcolor=#ff4415>ff4415</td><td bgcolor=#ff552a>ff552a</td><td bgcolor=#ff663f>ff663f</td><td bgcolor=#ff7755>ff7755</td><td bgcolor=#ff886a>ff886a</td><td bgcolor=#ff997f>ff997f</td><td bgcolor=#ffaa94>ffaa94</td><td bgcolor=#ffbbaa>ffbbaa</td><td bgcolor=#ffccbf>ffccbf</td><td bgcolor=#ffddd4>ffddd4</td><td bgcolor=#ffeee9>ffeee9</td></tr>
<tr><td bgcolor=#150500>150500</td><td bgcolor=#2a0b00>2a0b00</td><td bgcolor=#3f1100>3f1100</td><td bgcolor=#551600>551600</td><td bgcolor=#6a1c00>6a1c00</td><td bgcolor=#7f2200>7f2200</td><td bgcolor=#942700>942700</td><td bgcolor=#aa2d00>aa2d00</td><td bgcolor=#bf3300>bf3300</td><td bgcolor=#d43800>d43800</td><td bgcolor=#e93e00>e93e00</td><td bgcolor=#ff4400>ff4400</td><td bgcolor=#ff5315>ff5315</td><td bgcolor=#ff632a>ff632a</td><td bgcolor=#ff723f>ff723f</td><td bgcolor=#ff8255>ff8255</td><td bgcolor=#ff916a>ff916a</td><td bgcolor=#ffa17f>ffa17f</td><td bgcolor=#ffb194>ffb194</td><td bgcolor=#ffc0aa>ffc0aa</td><td bgcolor=#ffd0bf>ffd0bf</td><td bgcolor=#ffdfd4>ffdfd4</td><td bgcolor=#ffefe9>ffefe9</td></tr>
<tr><td bgcolor=#150700>150700</td><td bgcolor=#2a0e00>2a0e00</td><td bgcolor=#3f1500>3f1500</td><td bgcolor=#551c00>551c00</td><td bgcolor=#6a2300>6a2300</td><td bgcolor=#7f2a00>7f2a00</td><td bgcolor=#943100>943100</td><td bgcolor=#aa3800>aa3800</td><td bgcolor=#bf3f00>bf3f00</td><td bgcolor=#d44600>d44600</td><td bgcolor=#e94d00>e94d00</td><td bgcolor=#ff5500>ff5500</td><td bgcolor=#ff6315>ff6315</td><td bgcolor=#ff712a>ff712a</td><td bgcolor=#ff7f3f>ff7f3f</td><td bgcolor=#ff8d55>ff8d55</td><td bgcolor=#ff9b6a>ff9b6a</td><td bgcolor=#ffaa7f>ffaa7f</td><td bgcolor=#ffb894>ffb894</td><td bgcolor=#ffc6aa>ffc6aa</td><td bgcolor=#ffd4bf>ffd4bf</td><td bgcolor=#ffe2d4>ffe2d4</td><td bgcolor=#fff0e9>fff0e9</td></tr>
<tr><td bgcolor=#150800>150800</td><td bgcolor=#2a1000>2a1000</td><td bgcolor=#3f1900>3f1900</td><td bgcolor=#552200>552200</td><td bgcolor=#6a2a00>6a2a00</td><td bgcolor=#7f3300>7f3300</td><td bgcolor=#943b00>943b00</td><td bgcolor=#aa4400>aa4400</td><td bgcolor=#bf4c00>bf4c00</td><td bgcolor=#d45500>d45500</td><td bgcolor=#e95d00>e95d00</td><td bgcolor=#ff6600>ff6600</td><td bgcolor=#ff7215>ff7215</td><td bgcolor=#ff7f2a>ff7f2a</td><td bgcolor=#ff8c3f>ff8c3f</td><td bgcolor=#ff9955>ff9955</td><td bgcolor=#ffa56a>ffa56a</td><td bgcolor=#ffb27f>ffb27f</td><td bgcolor=#ffbf94>ffbf94</td><td bgcolor=#ffccaa>ffccaa</td><td bgcolor=#ffd8bf>ffd8bf</td><td bgcolor=#ffe5d4>ffe5d4</td><td bgcolor=#fff2e9>fff2e9</td></tr>
<tr><td bgcolor=#150900>150900</td><td bgcolor=#2a1300>2a1300</td><td bgcolor=#3f1d00>3f1d00</td><td bgcolor=#552700>552700</td><td bgcolor=#6a3100>6a3100</td><td bgcolor=#7f3b00>7f3b00</td><td bgcolor=#944500>944500</td><td bgcolor=#aa4f00>aa4f00</td><td bgcolor=#bf5900>bf5900</td><td bgcolor=#d46300>d46300</td><td bgcolor=#e96d00>e96d00</td><td bgcolor=#ff7700>ff7700</td><td bgcolor=#ff8215>ff8215</td><td bgcolor=#ff8d2a>ff8d2a</td><td bgcolor=#ff993f>ff993f</td><td bgcolor=#ffa455>ffa455</td><td bgcolor=#ffaf6a>ffaf6a</td><td bgcolor=#ffbb7f>ffbb7f</td><td bgcolor=#ffc694>ffc694</td><td bgcolor=#ffd1aa>ffd1aa</td><td bgcolor=#ffddbf>ffddbf</td><td bgcolor=#ffe8d4>ffe8d4</td><td bgcolor=#fff3e9>fff3e9</td></tr>
<tr><td bgcolor=#150b00>150b00</td><td bgcolor=#2a1600>2a1600</td><td bgcolor=#3f2200>3f2200</td><td bgcolor=#552d00>552d00</td><td bgcolor=#6a3800>6a3800</td><td bgcolor=#7f4400>7f4400</td><td bgcolor=#944f00>944f00</td><td bgcolor=#aa5a00>aa5a00</td><td bgcolor=#bf6600>bf6600</td><td bgcolor=#d47100>d47100</td><td bgcolor=#e97c00>e97c00</td><td bgcolor=#ff8800>ff8800</td><td bgcolor=#ff9115>ff9115</td><td bgcolor=#ff9b2a>ff9b2a</td><td bgcolor=#ffa53f>ffa53f</td><td bgcolor=#ffaf55>ffaf55</td><td bgcolor=#ffb96a>ffb96a</td><td bgcolor=#ffc37f>ffc37f</td><td bgcolor=#ffcd94>ffcd94</td><td bgcolor=#ffd7aa>ffd7aa</td><td bgcolor=#ffe1bf>ffe1bf</td><td bgcolor=#ffebd4>ffebd4</td><td bgcolor=#fff5e9>fff5e9</td></tr>
<tr><td bgcolor=#150c00>150c00</td><td bgcolor=#2a1900>2a1900</td><td bgcolor=#3f2600>3f2600</td><td bgcolor=#553300>553300</td><td bgcolor=#6a3f00>6a3f00</td><td bgcolor=#7f4c00>7f4c00</td><td bgcolor=#945900>945900</td><td bgcolor=#aa6600>aa6600</td><td bgcolor=#bf7200>bf7200</td><td bgcolor=#d47f00>d47f00</td><td bgcolor=#e98c00>e98c00</td><td bgcolor=#ff9900>ff9900</td><td bgcolor=#ffa115>ffa115</td><td bgcolor=#ffaa2a>ffaa2a</td><td bgcolor=#ffb23f>ffb23f</td><td bgcolor=#ffbb55>ffbb55</td><td bgcolor=#ffc36a>ffc36a</td><td bgcolor=#ffcc7f>ffcc7f</td><td bgcolor=#ffd494>ffd494</td><td bgcolor=#ffddaa>ffddaa</td><td bgcolor=#ffe5bf>ffe5bf</td><td bgcolor=#ffeed4>ffeed4</td><td bgcolor=#fff6e9>fff6e9</td></tr>
<tr><td bgcolor=#150e00>150e00</td><td bgcolor=#2a1c00>2a1c00</td><td bgcolor=#3f2a00>3f2a00</td><td bgcolor=#553800>553800</td><td bgcolor=#6a4600>6a4600</td><td bgcolor=#7f5500>7f5500</td><td bgcolor=#946300>946300</td><td bgcolor=#aa7100>aa7100</td><td bgcolor=#bf7f00>bf7f00</td><td bgcolor=#d48d00>d48d00</td><td bgcolor=#e99b00>e99b00</td><td bgcolor=#ffaa00>ffaa00</td><td bgcolor=#ffb115>ffb115</td><td bgcolor=#ffb82a>ffb82a</td><td bgcolor=#ffbf3f>ffbf3f</td><td bgcolor=#ffc655>ffc655</td><td bgcolor=#ffcd6a>ffcd6a</td><td bgcolor=#ffd47f>ffd47f</td><td bgcolor=#ffdb94>ffdb94</td><td bgcolor=#ffe2aa>ffe2aa</td><td bgcolor=#ffe9bf>ffe9bf</td><td bgcolor=#fff0d4>fff0d4</td><td bgcolor=#fff7e9>fff7e9</td></tr>
<tr><td bgcolor=#150f00>150f00</td><td bgcolor=#2a1f00>2a1f00</td><td bgcolor=#3f2e00>3f2e00</td><td bgcolor=#553e00>553e00</td><td bgcolor=#6a4d00>6a4d00</td><td bgcolor=#7f5d00>7f5d00</td><td bgcolor=#946d00>946d00</td><td bgcolor=#aa7c00>aa7c00</td><td bgcolor=#bf8c00>bf8c00</td><td bgcolor=#d49b00>d49b00</td><td bgcolor=#e9ab00>e9ab00</td><td bgcolor=#ffbb00>ffbb00</td><td bgcolor=#ffc015>ffc015</td><td bgcolor=#ffc62a>ffc62a</td><td bgcolor=#ffcb3f>ffcb3f</td><td bgcolor=#ffd155>ffd155</td><td bgcolor=#ffd76a>ffd76a</td><td bgcolor=#ffdd7f>ffdd7f</td><td bgcolor=#ffe294>ffe294</td><td bgcolor=#ffe8aa>ffe8aa</td><td bgcolor=#ffeebf>ffeebf</td><td bgcolor=#fff3d4>fff3d4</td><td bgcolor=#fff9e9>fff9e9</td></tr>
<tr><td bgcolor=#151100>151100</td><td bgcolor=#2a2100>2a2100</td><td bgcolor=#3f3300>3f3300</td><td bgcolor=#554400>554400</td><td bgcolor=#6a5500>6a5500</td><td bgcolor=#7f6600>7f6600</td><td bgcolor=#947600>947600</td><td bgcolor=#aa8800>aa8800</td><td bgcolor=#bf9900>bf9900</td><td bgcolor=#d4aa00>d4aa00</td><td bgcolor=#e9bb00>e9bb00</td><td bgcolor=#ffcc00>ffcc00</td><td bgcolor=#ffd015>ffd015</td><td bgcolor=#ffd42a>ffd42a</td><td bgcolor=#ffd83f>ffd83f</td><td bgcolor=#ffdd55>ffdd55</td><td bgcolor=#ffe16a>ffe16a</td><td bgcolor=#ffe57f>ffe57f</td><td bgcolor=#ffe994>ffe994</td><td bgcolor=#ffeeaa>ffeeaa</td><td bgcolor=#fff2bf>fff2bf</td><td bgcolor=#fff6d4>fff6d4</td><td bgcolor=#fffae9>fffae9</td></tr>
<tr><td bgcolor=#151200>151200</td><td bgcolor=#2a2400>2a2400</td><td bgcolor=#3f3700>3f3700</td><td bgcolor=#554900>554900</td><td bgcolor=#6a5c00>6a5c00</td><td bgcolor=#7f6e00>7f6e00</td><td bgcolor=#948000>948000</td><td bgcolor=#aa9300>aa9300</td><td bgcolor=#bfa500>bfa500</td><td bgcolor=#d4b800>d4b800</td><td bgcolor=#e9ca00>e9ca00</td><td bgcolor=#ffdd00>ffdd00</td><td bgcolor=#ffdf15>ffdf15</td><td bgcolor=#ffe22a>ffe22a</td><td bgcolor=#ffe53f>ffe53f</td><td bgcolor=#ffe855>ffe855</td><td bgcolor=#ffeb6a>ffeb6a</td><td bgcolor=#ffee7f>ffee7f</td><td bgcolor=#fff094>fff094</td><td bgcolor=#fff3aa>fff3aa</td><td bgcolor=#fff6bf>fff6bf</td><td bgcolor=#fff9d4>fff9d4</td><td bgcolor=#fffce9>fffce9</td></tr>
<tr><td bgcolor=#151300>151300</td><td bgcolor=#2a2700>2a2700</td><td bgcolor=#3f3b00>3f3b00</td><td bgcolor=#554f00>554f00</td><td bgcolor=#6a6300>6a6300</td><td bgcolor=#7f7700>7f7700</td><td bgcolor=#948a00>948a00</td><td bgcolor=#aa9e00>aa9e00</td><td bgcolor=#bfb200>bfb200</td><td bgcolor=#d4c600>d4c600</td><td bgcolor=#e9da00>e9da00</td><td bgcolor=#ffee00>ffee00</td><td bgcolor=#ffef15>ffef15</td><td bgcolor=#fff02a>fff02a</td><td bgcolor=#fff23f>fff23f</td><td bgcolor=#fff355>fff355</td><td bgcolor=#fff56a>fff56a</td><td bgcolor=#fff67f>fff67f</td><td bgcolor=#fff794>fff794</td><td bgcolor=#fff9aa>fff9aa</td><td bgcolor=#fffabf>fffabf</td><td bgcolor=#fffcd4>fffcd4</td><td bgcolor=#fffde9>fffde9</td></tr>
<tr><td bgcolor=#151500>151500</td><td bgcolor=#2a2a00>2a2a00</td><td bgcolor=#3f3f00>3f3f00</td><td bgcolor=#555500>555500</td><td bgcolor=#6a6a00>6a6a00</td><td bgcolor=#7f7f00>7f7f00</td><td bgcolor=#949400>949400</td><td bgcolor=#aaaa00>aaaa00</td><td bgcolor=#bfbf00>bfbf00</td><td bgcolor=#d4d400>d4d400</td><td bgcolor=#e9e900>e9e900</td><td bgcolor=#ffff00>ffff00</td><td bgcolor=#ffff15>ffff15</td><td bgcolor=#ffff2a>ffff2a</td><td bgcolor=#ffff3f>ffff3f</td><td bgcolor=#ffff55>ffff55</td><td bgcolor=#ffff6a>ffff6a</td><td bgcolor=#ffff7f>ffff7f</td><td bgcolor=#ffff94>ffff94</td><td bgcolor=#ffffaa>ffffaa</td><td bgcolor=#ffffbf>ffffbf</td><td bgcolor=#ffffd4>ffffd4</td><td bgcolor=#ffffe9>ffffe9</td></tr>
<tr><td bgcolor=#131500>131500</td><td bgcolor=#272a00>272a00</td><td bgcolor=#3b3f00>3b3f00</td><td bgcolor=#4f5500>4f5500</td><td bgcolor=#636a00>636a00</td><td bgcolor=#777f00>777f00</td><td bgcolor=#8a9400>8a9400</td><td bgcolor=#9eaa00>9eaa00</td><td bgcolor=#b2bf00>b2bf00</td><td bgcolor=#c6d400>c6d400</td><td bgcolor=#dae900>dae900</td><td bgcolor=#eeff00>eeff00</td><td bgcolor=#efff15>efff15</td><td bgcolor=#f0ff2a>f0ff2a</td><td bgcolor=#f2ff3f>f2ff3f</td><td bgcolor=#f3ff55>f3ff55</td><td bgcolor=#f5ff6a>f5ff6a</td><td bgcolor=#f6ff7f>f6ff7f</td><td bgcolor=#f7ff94>f7ff94</td><td bgcolor=#f9ffaa>f9ffaa</td><td bgcolor=#faffbf>faffbf</td><td bgcolor=#fcffd4>fcffd4</td><td bgcolor=#fdffe9>fdffe9</td></tr>
<tr><td bgcolor=#121500>121500</td><td bgcolor=#242a00>242a00</td><td bgcolor=#373f00>373f00</td><td bgcolor=#495500>495500</td><td bgcolor=#5c6a00>5c6a00</td><td bgcolor=#6e7f00>6e7f00</td><td bgcolor=#809400>809400</td><td bgcolor=#93aa00>93aa00</td><td bgcolor=#a5bf00>a5bf00</td><td bgcolor=#b8d400>b8d400</td><td bgcolor=#cae900>cae900</td><td bgcolor=#ddff00>ddff00</td><td bgcolor=#dfff15>dfff15</td><td bgcolor=#e2ff2a>e2ff2a</td><td bgcolor=#e5ff3f>e5ff3f</td><td bgcolor=#e8ff55>e8ff55</td><td bgcolor=#ebff6a>ebff6a</td><td bgcolor=#eeff7f>eeff7f</td><td bgcolor=#f0ff94>f0ff94</td><td bgcolor=#f3ffaa>f3ffaa</td><td bgcolor=#f6ffbf>f6ffbf</td><td bgcolor=#f9ffd4>f9ffd4</td><td bgcolor=#fcffe9>fcffe9</td></tr>
<tr><td bgcolor=#111500>111500</td><td bgcolor=#212a00>212a00</td><td bgcolor=#333f00>333f00</td><td bgcolor=#445500>445500</td><td bgcolor=#556a00>556a00</td><td bgcolor=#667f00>667f00</td><td bgcolor=#769400>769400</td><td bgcolor=#88aa00>88aa00</td><td bgcolor=#99bf00>99bf00</td><td bgcolor=#aad400>aad400</td><td bgcolor=#bbe900>bbe900</td><td bgcolor=#ccff00>ccff00</td><td bgcolor=#d0ff15>d0ff15</td><td bgcolor=#d4ff2a>d4ff2a</td><td bgcolor=#d8ff3f>d8ff3f</td><td bgcolor=#ddff55>ddff55</td><td bgcolor=#e1ff6a>e1ff6a</td><td bgcolor=#e5ff7f>e5ff7f</td><td bgcolor=#e9ff94>e9ff94</td><td bgcolor=#eeffaa>eeffaa</td><td bgcolor=#f2ffbf>f2ffbf</td><td bgcolor=#f6ffd4>f6ffd4</td><td bgcolor=#faffe9>faffe9</td></tr>
<tr><td bgcolor=#0f1500>0f1500</td><td bgcolor=#1f2a00>1f2a00</td><td bgcolor=#2e3f00>2e3f00</td><td bgcolor=#3e5500>3e5500</td><td bgcolor=#4d6a00>4d6a00</td><td bgcolor=#5d7f00>5d7f00</td><td bgcolor=#6d9400>6d9400</td><td bgcolor=#7caa00>7caa00</td><td bgcolor=#8cbf00>8cbf00</td><td bgcolor=#9bd400>9bd400</td><td bgcolor=#abe900>abe900</td><td bgcolor=#bbff00>bbff00</td><td bgcolor=#c0ff15>c0ff15</td><td bgcolor=#c6ff2a>c6ff2a</td><td bgcolor=#ccff3f>ccff3f</td><td bgcolor=#d1ff55>d1ff55</td><td bgcolor=#d7ff6a>d7ff6a</td><td bgcolor=#ddff7f>ddff7f</td><td bgcolor=#e2ff94>e2ff94</td><td bgcolor=#e8ffaa>e8ffaa</td><td bgcolor=#eeffbf>eeffbf</td><td bgcolor=#f3ffd4>f3ffd4</td><td bgcolor=#f9ffe9>f9ffe9</td></tr>
<tr><td bgcolor=#0e1500>0e1500</td><td bgcolor=#1c2a00>1c2a00</td><td bgcolor=#2a3f00>2a3f00</td><td bgcolor=#385500>385500</td><td bgcolor=#466a00>466a00</td><td bgcolor=#557f00>557f00</td><td bgcolor=#639400>639400</td><td bgcolor=#71aa00>71aa00</td><td bgcolor=#7fbf00>7fbf00</td><td bgcolor=#8dd400>8dd400</td><td bgcolor=#9be900>9be900</td><td bgcolor=#aaff00>aaff00</td><td bgcolor=#b1ff15>b1ff15</td><td bgcolor=#b8ff2a>b8ff2a</td><td bgcolor=#bfff3f>bfff3f</td><td bgcolor=#c6ff55>c6ff55</td><td bgcolor=#cdff6a>cdff6a</td><td bgcolor=#d4ff7f>d4ff7f</td><td bgcolor=#dbff94>dbff94</td><td bgcolor=#e2ffaa>e2ffaa</td><td bgcolor=#e9ffbf>e9ffbf</td><td bgcolor=#f0ffd4>f0ffd4</td><td bgcolor=#f7ffe9>f7ffe9</td></tr>
<tr><td bgcolor=#0c1500>0c1500</td><td bgcolor=#192a00>192a00</td><td bgcolor=#263f00>263f00</td><td bgcolor=#335500>335500</td><td bgcolor=#3f6a00>3f6a00</td><td bgcolor=#4c7f00>4c7f00</td><td bgcolor=#599400>599400</td><td bgcolor=#66aa00>66aa00</td><td bgcolor=#72bf00>72bf00</td><td bgcolor=#7fd400>7fd400</td><td bgcolor=#8ce900>8ce900</td><td bgcolor=#99ff00>99ff00</td><td bgcolor=#a1ff15>a1ff15</td><td bgcolor=#aaff2a>aaff2a</td><td bgcolor=#b2ff3f>b2ff3f</td><td bgcolor=#bbff55>bbff55</td><td bgcolor=#c3ff6a>c3ff6a</td><td bgcolor=#ccff7f>ccff7f</td><td bgcolor=#d4ff94>d4ff94</td><td bgcolor=#ddffaa>ddffaa</td><td bgcolor=#e5ffbf>e5ffbf</td><td bgcolor=#eeffd4>eeffd4</td><td bgcolor=#f6ffe9>f6ffe9</td></tr>
<tr><td bgcolor=#0b1500>0b1500</td><td bgcolor=#162a00>162a00</td><td bgcolor=#223f00>223f00</td><td bgcolor=#2d5500>2d5500</td><td bgcolor=#386a00>386a00</td><td bgcolor=#447f00>447f00</td><td bgcolor=#4f9400>4f9400</td><td bgcolor=#5aaa00>5aaa00</td><td bgcolor=#66bf00>66bf00</td><td bgcolor=#71d400>71d400</td><td bgcolor=#7ce900>7ce900</td><td bgcolor=#88ff00>88ff00</td><td bgcolor=#91ff15>91ff15</td><td bgcolor=#9bff2a>9bff2a</td><td bgcolor=#a5ff3f>a5ff3f</td><td bgcolor=#afff55>afff55</td><td bgcolor=#b9ff6a>b9ff6a</td><td bgcolor=#c3ff7f>c3ff7f</td><td bgcolor=#cdff94>cdff94</td><td bgcolor=#d7ffaa>d7ffaa</td><td bgcolor=#e1ffbf>e1ffbf</td><td bgcolor=#ebffd4>ebffd4</td><td bgcolor=#f5ffe9>f5ffe9</td></tr>
<tr><td bgcolor=#091500>091500</td><td bgcolor=#132a00>132a00</td><td bgcolor=#1d3f00>1d3f00</td><td bgcolor=#275500>275500</td><td bgcolor=#316a00>316a00</td><td bgcolor=#3b7f00>3b7f00</td><td bgcolor=#459400>459400</td><td bgcolor=#4faa00>4faa00</td><td bgcolor=#59bf00>59bf00</td><td bgcolor=#63d400>63d400</td><td bgcolor=#6de900>6de900</td><td bgcolor=#76ff00>76ff00</td><td bgcolor=#82ff15>82ff15</td><td bgcolor=#8dff2a>8dff2a</td><td bgcolor=#98ff3f>98ff3f</td><td bgcolor=#a4ff55>a4ff55</td><td bgcolor=#afff6a>afff6a</td><td bgcolor=#bbff7f>bbff7f</td><td bgcolor=#c6ff94>c6ff94</td><td bgcolor=#d1ffaa>d1ffaa</td><td bgcolor=#ddffbf>ddffbf</td><td bgcolor=#e8ffd4>e8ffd4</td><td bgcolor=#f3ffe9>f3ffe9</td></tr>
<tr><td bgcolor=#081500>081500</td><td bgcolor=#102a00>102a00</td><td bgcolor=#193f00>193f00</td><td bgcolor=#225500>225500</td><td bgcolor=#2a6a00>2a6a00</td><td bgcolor=#327f00>327f00</td><td bgcolor=#3b9400>3b9400</td><td bgcolor=#44aa00>44aa00</td><td bgcolor=#4cbf00>4cbf00</td><td bgcolor=#54d400>54d400</td><td bgcolor=#5de900>5de900</td><td bgcolor=#65ff00>65ff00</td><td bgcolor=#72ff15>72ff15</td><td bgcolor=#7fff2a>7fff2a</td><td bgcolor=#8cff3f>8cff3f</td><td bgcolor=#99ff55>99ff55</td><td bgcolor=#a5ff6a>a5ff6a</td><td bgcolor=#b2ff7f>b2ff7f</td><td bgcolor=#bfff94>bfff94</td><td bgcolor=#cbffaa>cbffaa</td><td bgcolor=#d8ffbf>d8ffbf</td><td bgcolor=#e5ffd4>e5ffd4</td><td bgcolor=#f2ffe9>f2ffe9</td></tr>
<tr><td bgcolor=#071500>071500</td><td bgcolor=#0e2a00>0e2a00</td><td bgcolor=#153f00>153f00</td><td bgcolor=#1c5500>1c5500</td><td bgcolor=#236a00>236a00</td><td bgcolor=#2a7f00>2a7f00</td><td bgcolor=#319400>319400</td><td bgcolor=#38aa00>38aa00</td><td bgcolor=#3fbf00>3fbf00</td><td bgcolor=#46d400>46d400</td><td bgcolor=#4de900>4de900</td><td bgcolor=#54ff00>54ff00</td><td bgcolor=#63ff15>63ff15</td><td bgcolor=#71ff2a>71ff2a</td><td bgcolor=#7fff3f>7fff3f</td><td bgcolor=#8dff55>8dff55</td><td bgcolor=#9bff6a>9bff6a</td><td bgcolor=#aaff7f>aaff7f</td><td bgcolor=#b8ff94>b8ff94</td><td bgcolor=#c6ffaa>c6ffaa</td><td bgcolor=#d4ffbf>d4ffbf</td><td bgcolor=#e2ffd4>e2ffd4</td><td bgcolor=#f0ffe9>f0ffe9</td></tr>
<tr><td bgcolor=#051500>051500</td><td bgcolor=#0b2a00>0b2a00</td><td bgcolor=#103f00>103f00</td><td bgcolor=#165500>165500</td><td bgcolor=#1c6a00>1c6a00</td><td bgcolor=#217f00>217f00</td><td bgcolor=#279400>279400</td><td bgcolor=#2daa00>2daa00</td><td bgcolor=#32bf00>32bf00</td><td bgcolor=#38d400>38d400</td><td bgcolor=#3ee900>3ee900</td><td bgcolor=#43ff00>43ff00</td><td bgcolor=#53ff15>53ff15</td><td bgcolor=#63ff2a>63ff2a</td><td bgcolor=#72ff3f>72ff3f</td><td bgcolor=#82ff55>82ff55</td><td bgcolor=#91ff6a>91ff6a</td><td bgcolor=#a1ff7f>a1ff7f</td><td bgcolor=#b1ff94>b1ff94</td><td bgcolor=#c0ffaa>c0ffaa</td><td bgcolor=#d0ffbf>d0ffbf</td><td bgcolor=#dfffd4>dfffd4</td><td bgcolor=#efffe9>efffe9</td></tr>
<tr><td bgcolor=#041500>041500</td><td bgcolor=#082a00>082a00</td><td bgcolor=#0c3f00>0c3f00</td><td bgcolor=#115500>115500</td><td bgcolor=#156a00>156a00</td><td bgcolor=#197f00>197f00</td><td bgcolor=#1d9400>1d9400</td><td bgcolor=#22aa00>22aa00</td><td bgcolor=#26bf00>26bf00</td><td bgcolor=#2ad400>2ad400</td><td bgcolor=#2ee900>2ee900</td><td bgcolor=#32ff00>32ff00</td><td bgcolor=#43ff15>43ff15</td><td bgcolor=#54ff2a>54ff2a</td><td bgcolor=#65ff3f>65ff3f</td><td bgcolor=#77ff55>77ff55</td><td bgcolor=#88ff6a>88ff6a</td><td bgcolor=#99ff7f>99ff7f</td><td bgcolor=#aaff94>aaff94</td><td bgcolor=#bbffaa>bbffaa</td><td bgcolor=#ccffbf>ccffbf</td><td bgcolor=#ddffd4>ddffd4</td><td bgcolor=#eeffe9>eeffe9</td></tr>
<tr><td bgcolor=#021500>021500</td><td bgcolor=#052a00>052a00</td><td bgcolor=#083f00>083f00</td><td bgcolor=#0b5500>0b5500</td><td bgcolor=#0e6a00>0e6a00</td><td bgcolor=#107f00>107f00</td><td bgcolor=#139400>139400</td><td bgcolor=#16aa00>16aa00</td><td bgcolor=#19bf00>19bf00</td><td bgcolor=#1cd400>1cd400</td><td bgcolor=#1fe900>1fe900</td><td bgcolor=#21ff00>21ff00</td><td bgcolor=#34ff15>34ff15</td><td bgcolor=#46ff2a>46ff2a</td><td bgcolor=#59ff3f>59ff3f</td><td bgcolor=#6bff55>6bff55</td><td bgcolor=#7eff6a>7eff6a</td><td bgcolor=#90ff7f>90ff7f</td><td bgcolor=#a2ff94>a2ff94</td><td bgcolor=#b5ffaa>b5ffaa</td><td bgcolor=#c7ffbf>c7ffbf</td><td bgcolor=#daffd4>daffd4</td><td bgcolor=#ecffe9>ecffe9</td></tr>
<tr><td bgcolor=#011500>011500</td><td bgcolor=#022a00>022a00</td><td bgcolor=#043f00>043f00</td><td bgcolor=#055500>055500</td><td bgcolor=#076a00>076a00</td><td bgcolor=#087f00>087f00</td><td bgcolor=#099400>099400</td><td bgcolor=#0baa00>0baa00</td><td bgcolor=#0cbf00>0cbf00</td><td bgcolor=#0ed400>0ed400</td><td bgcolor=#0fe900>0fe900</td><td bgcolor=#10ff00>10ff00</td><td bgcolor=#24ff15>24ff15</td><td bgcolor=#38ff2a>38ff2a</td><td bgcolor=#4cff3f>4cff3f</td><td bgcolor=#60ff55>60ff55</td><td bgcolor=#74ff6a>74ff6a</td><td bgcolor=#88ff7f>88ff7f</td><td bgcolor=#9bff94>9bff94</td><td bgcolor=#afffaa>afffaa</td><td bgcolor=#c3ffbf>c3ffbf</td><td bgcolor=#d7ffd4>d7ffd4</td><td bgcolor=#ebffe9>ebffe9</td></tr>
<tr><td bgcolor=#001500>001500</td><td bgcolor=#002a00>002a00</td><td bgcolor=#003f00>003f00</td><td bgcolor=#005500>005500</td><td bgcolor=#006a00>006a00</td><td bgcolor=#007f00>007f00</td><td bgcolor=#009400>009400</td><td bgcolor=#00aa00>00aa00</td><td bgcolor=#00bf00>00bf00</td><td bgcolor=#00d400>00d400</td><td bgcolor=#00e900>00e900</td><td bgcolor=#00ff00>00ff00</td><td bgcolor=#15ff15>15ff15</td><td bgcolor=#2aff2a>2aff2a</td><td bgcolor=#3fff3f>3fff3f</td><td bgcolor=#55ff55>55ff55</td><td bgcolor=#6aff6a>6aff6a</td><td bgcolor=#7fff7f>7fff7f</td><td bgcolor=#94ff94>94ff94</td><td bgcolor=#aaffaa>aaffaa</td><td bgcolor=#bfffbf>bfffbf</td><td bgcolor=#d4ffd4>d4ffd4</td><td bgcolor=#e9ffe9>e9ffe9</td></tr>
<tr><td bgcolor=#001501>001501</td><td bgcolor=#002a02>002a02</td><td bgcolor=#003f04>003f04</td><td bgcolor=#005505>005505</td><td bgcolor=#006a07>006a07</td><td bgcolor=#007f08>007f08</td><td bgcolor=#009409>009409</td><td bgcolor=#00aa0b>00aa0b</td><td bgcolor=#00bf0c>00bf0c</td><td bgcolor=#00d40e>00d40e</td><td bgcolor=#00e90f>00e90f</td><td bgcolor=#00ff11>00ff11</td><td bgcolor=#15ff24>15ff24</td><td bgcolor=#2aff38>2aff38</td><td bgcolor=#3fff4c>3fff4c</td><td bgcolor=#55ff60>55ff60</td><td bgcolor=#6aff74>6aff74</td><td bgcolor=#7fff88>7fff88</td><td bgcolor=#94ff9b>94ff9b</td><td bgcolor=#aaffaf>aaffaf</td><td bgcolor=#bfffc3>bfffc3</td><td bgcolor=#d4ffd7>d4ffd7</td><td bgcolor=#e9ffeb>e9ffeb</td></tr>
<tr><td bgcolor=#001502>001502</td><td bgcolor=#002a05>002a05</td><td bgcolor=#003f08>003f08</td><td bgcolor=#00550b>00550b</td><td bgcolor=#006a0e>006a0e</td><td bgcolor=#007f10>007f10</td><td bgcolor=#009413>009413</td><td bgcolor=#00aa16>00aa16</td><td bgcolor=#00bf19>00bf19</td><td bgcolor=#00d41c>00d41c</td><td bgcolor=#00e91f>00e91f</td><td bgcolor=#00ff21>00ff21</td><td bgcolor=#15ff34>15ff34</td><td bgcolor=#2aff46>2aff46</td><td bgcolor=#3fff59>3fff59</td><td bgcolor=#55ff6b>55ff6b</td><td bgcolor=#6aff7e>6aff7e</td><td bgcolor=#7fff90>7fff90</td><td bgcolor=#94ffa2>94ffa2</td><td bgcolor=#aaffb5>aaffb5</td><td bgcolor=#bfffc7>bfffc7</td><td bgcolor=#d4ffda>d4ffda</td><td bgcolor=#e9ffec>e9ffec</td></tr>
<tr><td bgcolor=#001504>001504</td><td bgcolor=#002a08>002a08</td><td bgcolor=#003f0c>003f0c</td><td bgcolor=#005511>005511</td><td bgcolor=#006a15>006a15</td><td bgcolor=#007f19>007f19</td><td bgcolor=#00941d>00941d</td><td bgcolor=#00aa22>00aa22</td><td bgcolor=#00bf26>00bf26</td><td bgcolor=#00d42a>00d42a</td><td bgcolor=#00e92e>00e92e</td><td bgcolor=#00ff33>00ff33</td><td bgcolor=#15ff44>15ff44</td><td bgcolor=#2aff55>2aff55</td><td bgcolor=#3fff66>3fff66</td><td bgcolor=#55ff77>55ff77</td><td bgcolor=#6aff88>6aff88</td><td bgcolor=#7fff99>7fff99</td><td bgcolor=#94ffaa>94ffaa</td><td bgcolor=#aaffbb>aaffbb</td><td bgcolor=#bfffcc>bfffcc</td><td bgcolor=#d4ffdd>d4ffdd</td><td bgcolor=#e9ffee>e9ffee</td></tr>
<tr><td bgcolor=#001505>001505</td><td bgcolor=#002a0b>002a0b</td><td bgcolor=#003f10>003f10</td><td bgcolor=#005516>005516</td><td bgcolor=#006a1c>006a1c</td><td bgcolor=#007f21>007f21</td><td bgcolor=#009427>009427</td><td bgcolor=#00aa2d>00aa2d</td><td bgcolor=#00bf32>00bf32</td><td bgcolor=#00d438>00d438</td><td bgcolor=#00e93e>00e93e</td><td bgcolor=#00ff43>00ff43</td><td bgcolor=#15ff53>15ff53</td><td bgcolor=#2aff63>2aff63</td><td bgcolor=#3fff72>3fff72</td><td bgcolor=#55ff82>55ff82</td><td bgcolor=#6aff91>6aff91</td><td bgcolor=#7fffa1>7fffa1</td><td bgcolor=#94ffb1>94ffb1</td><td bgcolor=#aaffc0>aaffc0</td><td bgcolor=#bfffd0>bfffd0</td><td bgcolor=#d4ffdf>d4ffdf</td><td bgcolor=#e9ffef>e9ffef</td></tr>
<tr><td bgcolor=#001507>001507</td><td bgcolor=#002a0e>002a0e</td><td bgcolor=#003f15>003f15</td><td bgcolor=#00551c>00551c</td><td bgcolor=#006a23>006a23</td><td bgcolor=#007f2a>007f2a</td><td bgcolor=#009431>009431</td><td bgcolor=#00aa38>00aa38</td><td bgcolor=#00bf3f>00bf3f</td><td bgcolor=#00d446>00d446</td><td bgcolor=#00e94d>00e94d</td><td bgcolor=#00ff55>00ff55</td><td bgcolor=#15ff63>15ff63</td><td bgcolor=#2aff71>2aff71</td><td bgcolor=#3fff7f>3fff7f</td><td bgcolor=#55ff8d>55ff8d</td><td bgcolor=#6aff9b>6aff9b</td><td bgcolor=#7fffaa>7fffaa</td><td bgcolor=#94ffb8>94ffb8</td><td bgcolor=#aaffc6>aaffc6</td><td bgcolor=#bfffd4>bfffd4</td><td bgcolor=#d4ffe2>d4ffe2</td><td bgcolor=#e9fff0>e9fff0</td></tr>
<tr><td bgcolor=#001508>001508</td><td bgcolor=#002a10>002a10</td><td bgcolor=#003f19>003f19</td><td bgcolor=#005522>005522</td><td bgcolor=#006a2a>006a2a</td><td bgcolor=#007f32>007f32</td><td bgcolor=#00943b>00943b</td><td bgcolor=#00aa44>00aa44</td><td bgcolor=#00bf4c>00bf4c</td><td bgcolor=#00d454>00d454</td><td bgcolor=#00e95d>00e95d</td><td bgcolor=#00ff65>00ff65</td><td bgcolor=#15ff72>15ff72</td><td bgcolor=#2aff7f>2aff7f</td><td bgcolor=#3fff8c>3fff8c</td><td bgcolor=#55ff99>55ff99</td><td bgcolor=#6affa5>6affa5</td><td bgcolor=#7fffb2>7fffb2</td><td bgcolor=#94ffbf>94ffbf</td><td bgcolor=#aaffcb>aaffcb</td><td bgcolor=#bfffd8>bfffd8</td><td bgcolor=#d4ffe5>d4ffe5</td><td bgcolor=#e9fff2>e9fff2</td></tr>
<tr><td bgcolor=#001509>001509</td><td bgcolor=#002a13>002a13</td><td bgcolor=#003f1d>003f1d</td><td bgcolor=#005527>005527</td><td bgcolor=#006a31>006a31</td><td bgcolor=#007f3b>007f3b</td><td bgcolor=#009445>009445</td><td bgcolor=#00aa4f>00aa4f</td><td bgcolor=#00bf59>00bf59</td><td bgcolor=#00d463>00d463</td><td bgcolor=#00e96d>00e96d</td><td bgcolor=#00ff77>00ff77</td><td bgcolor=#15ff82>15ff82</td><td bgcolor=#2aff8d>2aff8d</td><td bgcolor=#3fff99>3fff99</td><td bgcolor=#55ffa4>55ffa4</td><td bgcolor=#6affaf>6affaf</td><td bgcolor=#7fffbb>7fffbb</td><td bgcolor=#94ffc6>94ffc6</td><td bgcolor=#aaffd1>aaffd1</td><td bgcolor=#bfffdd>bfffdd</td><td bgcolor=#d4ffe8>d4ffe8</td><td bgcolor=#e9fff3>e9fff3</td></tr>
<tr><td bgcolor=#00150b>00150b</td><td bgcolor=#002a16>002a16</td><td bgcolor=#003f21>003f21</td><td bgcolor=#00552d>00552d</td><td bgcolor=#006a38>006a38</td><td bgcolor=#007f43>007f43</td><td bgcolor=#00944f>00944f</td><td bgcolor=#00aa5a>00aa5a</td><td bgcolor=#00bf65>00bf65</td><td bgcolor=#00d471>00d471</td><td bgcolor=#00e97c>00e97c</td><td bgcolor=#00ff87>00ff87</td><td bgcolor=#15ff91>15ff91</td><td bgcolor=#2aff9b>2aff9b</td><td bgcolor=#3fffa5>3fffa5</td><td bgcolor=#55ffaf>55ffaf</td><td bgcolor=#6affb9>6affb9</td><td bgcolor=#7fffc3>7fffc3</td><td bgcolor=#94ffcd>94ffcd</td><td bgcolor=#aaffd7>aaffd7</td><td bgcolor=#bfffe1>bfffe1</td><td bgcolor=#d4ffeb>d4ffeb</td><td bgcolor=#e9fff5>e9fff5</td></tr>
<tr><td bgcolor=#00150c>00150c</td><td bgcolor=#002a19>002a19</td><td bgcolor=#003f26>003f26</td><td bgcolor=#005533>005533</td><td bgcolor=#006a3f>006a3f</td><td bgcolor=#007f4c>007f4c</td><td bgcolor=#009459>009459</td><td bgcolor=#00aa66>00aa66</td><td bgcolor=#00bf72>00bf72</td><td bgcolor=#00d47f>00d47f</td><td bgcolor=#00e98c>00e98c</td><td bgcolor=#00ff99>00ff99</td><td bgcolor=#15ffa1>15ffa1</td><td bgcolor=#2affaa>2affaa</td><td bgcolor=#3fffb2>3fffb2</td><td bgcolor=#55ffbb>55ffbb</td><td bgcolor=#6affc3>6affc3</td><td bgcolor=#7fffcc>7fffcc</td><td bgcolor=#94ffd4>94ffd4</td><td bgcolor=#aaffdd>aaffdd</td><td bgcolor=#bfffe5>bfffe5</td><td bgcolor=#d4ffee>d4ffee</td><td bgcolor=#e9fff6>e9fff6</td></tr>
<tr><td bgcolor=#00150e>00150e</td><td bgcolor=#002a1c>002a1c</td><td bgcolor=#003f2a>003f2a</td><td bgcolor=#005538>005538</td><td bgcolor=#006a46>006a46</td><td bgcolor=#007f54>007f54</td><td bgcolor=#009463>009463</td><td bgcolor=#00aa71>00aa71</td><td bgcolor=#00bf7f>00bf7f</td><td bgcolor=#00d48d>00d48d</td><td bgcolor=#00e99b>00e99b</td><td bgcolor=#00ffa9>00ffa9</td><td bgcolor=#15ffb1>15ffb1</td><td bgcolor=#2affb8>2affb8</td><td bgcolor=#3fffbf>3fffbf</td><td bgcolor=#55ffc6>55ffc6</td><td bgcolor=#6affcd>6affcd</td><td bgcolor=#7fffd4>7fffd4</td><td bgcolor=#94ffdb>94ffdb</td><td bgcolor=#aaffe2>aaffe2</td><td bgcolor=#bfffe9>bfffe9</td><td bgcolor=#d4fff0>d4fff0</td><td bgcolor=#e9fff7>e9fff7</td></tr>
<tr><td bgcolor=#00150f>00150f</td><td bgcolor=#002a1f>002a1f</td><td bgcolor=#003f2e>003f2e</td><td bgcolor=#00553e>00553e</td><td bgcolor=#006a4d>006a4d</td><td bgcolor=#007f5d>007f5d</td><td bgcolor=#00946d>00946d</td><td bgcolor=#00aa7c>00aa7c</td><td bgcolor=#00bf8c>00bf8c</td><td bgcolor=#00d49b>00d49b</td><td bgcolor=#00e9ab>00e9ab</td><td bgcolor=#00ffbb>00ffbb</td><td bgcolor=#15ffc0>15ffc0</td><td bgcolor=#2affc6>2affc6</td><td bgcolor=#3fffcc>3fffcc</td><td bgcolor=#55ffd1>55ffd1</td><td bgcolor=#6affd7>6affd7</td><td bgcolor=#7fffdd>7fffdd</td><td bgcolor=#94ffe2>94ffe2</td><td bgcolor=#aaffe8>aaffe8</td><td bgcolor=#bfffee>bfffee</td><td bgcolor=#d4fff3>d4fff3</td><td bgcolor=#e9fff9>e9fff9</td></tr>
<tr><td bgcolor=#001511>001511</td><td bgcolor=#002a21>002a21</td><td bgcolor=#003f32>003f32</td><td bgcolor=#005544>005544</td><td bgcolor=#006a54>006a54</td><td bgcolor=#007f65>007f65</td><td bgcolor=#009476>009476</td><td bgcolor=#00aa88>00aa88</td><td bgcolor=#00bf98>00bf98</td><td bgcolor=#00d4a9>00d4a9</td><td bgcolor=#00e9ba>00e9ba</td><td bgcolor=#00ffcb>00ffcb</td><td bgcolor=#15ffd0>15ffd0</td><td bgcolor=#2affd4>2affd4</td><td bgcolor=#3fffd8>3fffd8</td><td bgcolor=#55ffdd>55ffdd</td><td bgcolor=#6affe1>6affe1</td><td bgcolor=#7fffe5>7fffe5</td><td bgcolor=#94ffe9>94ffe9</td><td bgcolor=#aaffee>aaffee</td><td bgcolor=#bffff2>bffff2</td><td bgcolor=#d4fff6>d4fff6</td><td bgcolor=#e9fffa>e9fffa</td></tr>
<tr><td bgcolor=#001512>001512</td><td bgcolor=#002a24>002a24</td><td bgcolor=#003f37>003f37</td><td bgcolor=#005549>005549</td><td bgcolor=#006a5c>006a5c</td><td bgcolor=#007f6e>007f6e</td><td bgcolor=#009480>009480</td><td bgcolor=#00aa93>00aa93</td><td bgcolor=#00bfa5>00bfa5</td><td bgcolor=#00d4b8>00d4b8</td><td bgcolor=#00e9ca>00e9ca</td><td bgcolor=#00ffdd>00ffdd</td><td bgcolor=#15ffdf>15ffdf</td><td bgcolor=#2affe2>2affe2</td><td bgcolor=#3fffe5>3fffe5</td><td bgcolor=#55ffe8>55ffe8</td><td bgcolor=#6affeb>6affeb</td><td bgcolor=#7fffee>7fffee</td><td bgcolor=#94fff0>94fff0</td><td bgcolor=#aafff3>aafff3</td><td bgcolor=#bffff6>bffff6</td><td bgcolor=#d4fff9>d4fff9</td><td bgcolor=#e9fffc>e9fffc</td></tr>
<tr><td bgcolor=#001513>001513</td><td bgcolor=#002a27>002a27</td><td bgcolor=#003f3b>003f3b</td><td bgcolor=#00554f>00554f</td><td bgcolor=#006a63>006a63</td><td bgcolor=#007f76>007f76</td><td bgcolor=#00948a>00948a</td><td bgcolor=#00aa9e>00aa9e</td><td bgcolor=#00bfb2>00bfb2</td><td bgcolor=#00d4c6>00d4c6</td><td bgcolor=#00e9da>00e9da</td><td bgcolor=#00ffed>00ffed</td><td bgcolor=#15ffef>15ffef</td><td bgcolor=#2afff0>2afff0</td><td bgcolor=#3ffff2>3ffff2</td><td bgcolor=#55fff3>55fff3</td><td bgcolor=#6afff5>6afff5</td><td bgcolor=#7ffff6>7ffff6</td><td bgcolor=#94fff7>94fff7</td><td bgcolor=#aafff9>aafff9</td><td bgcolor=#bffffa>bffffa</td><td bgcolor=#d4fffc>d4fffc</td><td bgcolor=#e9fffd>e9fffd</td></tr>
<tr><td bgcolor=#001515>001515</td><td bgcolor=#002a2a>002a2a</td><td bgcolor=#003f3f>003f3f</td><td bgcolor=#005555>005555</td><td bgcolor=#006a6a>006a6a</td><td bgcolor=#007f7f>007f7f</td><td bgcolor=#009494>009494</td><td bgcolor=#00aaaa>00aaaa</td><td bgcolor=#00bfbf>00bfbf</td><td bgcolor=#00d4d4>00d4d4</td><td bgcolor=#00e9e9>00e9e9</td><td bgcolor=#00ffff>00ffff</td><td bgcolor=#15ffff>15ffff</td><td bgcolor=#2affff>2affff</td><td bgcolor=#3fffff>3fffff</td><td bgcolor=#55ffff>55ffff</td><td bgcolor=#6affff>6affff</td><td bgcolor=#7fffff>7fffff</td><td bgcolor=#94ffff>94ffff</td><td bgcolor=#aaffff>aaffff</td><td bgcolor=#bfffff>bfffff</td><td bgcolor=#d4ffff>d4ffff</td><td bgcolor=#e9ffff>e9ffff</td></tr>
<tr><td bgcolor=#001315>001315</td><td bgcolor=#00272a>00272a</td><td bgcolor=#003b3f>003b3f</td><td bgcolor=#004f55>004f55</td><td bgcolor=#00636a>00636a</td><td bgcolor=#00767f>00767f</td><td bgcolor=#008a94>008a94</td><td bgcolor=#009eaa>009eaa</td><td bgcolor=#00b2bf>00b2bf</td><td bgcolor=#00c6d4>00c6d4</td><td bgcolor=#00dae9>00dae9</td><td bgcolor=#00edff>00edff</td><td bgcolor=#15efff>15efff</td><td bgcolor=#2af0ff>2af0ff</td><td bgcolor=#3ff2ff>3ff2ff</td><td bgcolor=#55f3ff>55f3ff</td><td bgcolor=#6af5ff>6af5ff</td><td bgcolor=#7ff6ff>7ff6ff</td><td bgcolor=#94f7ff>94f7ff</td><td bgcolor=#aaf9ff>aaf9ff</td><td bgcolor=#bffaff>bffaff</td><td bgcolor=#d4fcff>d4fcff</td><td bgcolor=#e9fdff>e9fdff</td></tr>
<tr><td bgcolor=#001215>001215</td><td bgcolor=#00242a>00242a</td><td bgcolor=#00373f>00373f</td><td bgcolor=#004955>004955</td><td bgcolor=#005c6a>005c6a</td><td bgcolor=#006e7f>006e7f</td><td bgcolor=#008094>008094</td><td bgcolor=#0093aa>0093aa</td><td bgcolor=#00a5bf>00a5bf</td><td bgcolor=#00b8d4>00b8d4</td><td bgcolor=#00cae9>00cae9</td><td bgcolor=#00ddff>00ddff</td><td bgcolor=#15dfff>15dfff</td><td bgcolor=#2ae2ff>2ae2ff</td><td bgcolor=#3fe5ff>3fe5ff</td><td bgcolor=#55e8ff>55e8ff</td><td bgcolor=#6aebff>6aebff</td><td bgcolor=#7feeff>7feeff</td><td bgcolor=#94f0ff>94f0ff</td><td bgcolor=#aaf3ff>aaf3ff</td><td bgcolor=#bff6ff>bff6ff</td><td bgcolor=#d4f9ff>d4f9ff</td><td bgcolor=#e9fcff>e9fcff</td></tr>
<tr><td bgcolor=#001115>001115</td><td bgcolor=#00212a>00212a</td><td bgcolor=#00323f>00323f</td><td bgcolor=#004455>004455</td><td bgcolor=#00546a>00546a</td><td bgcolor=#00657f>00657f</td><td bgcolor=#007694>007694</td><td bgcolor=#0088aa>0088aa</td><td bgcolor=#0098bf>0098bf</td><td bgcolor=#00a9d4>00a9d4</td><td bgcolor=#00bae9>00bae9</td><td bgcolor=#00cbff>00cbff</td><td bgcolor=#15d0ff>15d0ff</td><td bgcolor=#2ad4ff>2ad4ff</td><td bgcolor=#3fd8ff>3fd8ff</td><td bgcolor=#55ddff>55ddff</td><td bgcolor=#6ae1ff>6ae1ff</td><td bgcolor=#7fe5ff>7fe5ff</td><td bgcolor=#94e9ff>94e9ff</td><td bgcolor=#aaeeff>aaeeff</td><td bgcolor=#bff2ff>bff2ff</td><td bgcolor=#d4f6ff>d4f6ff</td><td bgcolor=#e9faff>e9faff</td></tr>
<tr><td bgcolor=#000f15>000f15</td><td bgcolor=#001f2a>001f2a</td><td bgcolor=#002e3f>002e3f</td><td bgcolor=#003e55>003e55</td><td bgcolor=#004d6a>004d6a</td><td bgcolor=#005d7f>005d7f</td><td bgcolor=#006d94>006d94</td><td bgcolor=#007caa>007caa</td><td bgcolor=#008cbf>008cbf</td><td bgcolor=#009bd4>009bd4</td><td bgcolor=#00abe9>00abe9</td><td bgcolor=#00bbff>00bbff</td><td bgcolor=#15c0ff>15c0ff</td><td bgcolor=#2ac6ff>2ac6ff</td><td bgcolor=#3fccff>3fccff</td><td bgcolor=#55d1ff>55d1ff</td><td bgcolor=#6ad7ff>6ad7ff</td><td bgcolor=#7fddff>7fddff</td><td bgcolor=#94e2ff>94e2ff</td><td bgcolor=#aae8ff>aae8ff</td><td bgcolor=#bfeeff>bfeeff</td><td bgcolor=#d4f3ff>d4f3ff</td><td bgcolor=#e9f9ff>e9f9ff</td></tr>
<tr><td bgcolor=#000e15>000e15</td><td bgcolor=#001c2a>001c2a</td><td bgcolor=#002a3f>002a3f</td><td bgcolor=#003855>003855</td><td bgcolor=#00466a>00466a</td><td bgcolor=#00547f>00547f</td><td bgcolor=#006394>006394</td><td bgcolor=#0071aa>0071aa</td><td bgcolor=#007fbf>007fbf</td><td bgcolor=#008dd4>008dd4</td><td bgcolor=#009be9>009be9</td><td bgcolor=#00a9ff>00a9ff</td><td bgcolor=#15b1ff>15b1ff</td><td bgcolor=#2ab8ff>2ab8ff</td><td bgcolor=#3fbfff>3fbfff</td><td bgcolor=#55c6ff>55c6ff</td><td bgcolor=#6acdff>6acdff</td><td bgcolor=#7fd4ff>7fd4ff</td><td bgcolor=#94dbff>94dbff</td><td bgcolor=#aae2ff>aae2ff</td><td bgcolor=#bfe9ff>bfe9ff</td><td bgcolor=#d4f0ff>d4f0ff</td><td bgcolor=#e9f7ff>e9f7ff</td></tr>
<tr><td bgcolor=#000c15>000c15</td><td bgcolor=#00192a>00192a</td><td bgcolor=#00263f>00263f</td><td bgcolor=#003355>003355</td><td bgcolor=#003f6a>003f6a</td><td bgcolor=#004c7f>004c7f</td><td bgcolor=#005994>005994</td><td bgcolor=#0066aa>0066aa</td><td bgcolor=#0072bf>0072bf</td><td bgcolor=#007fd4>007fd4</td><td bgcolor=#008ce9>008ce9</td><td bgcolor=#0099ff>0099ff</td><td bgcolor=#15a1ff>15a1ff</td><td bgcolor=#2aaaff>2aaaff</td><td bgcolor=#3fb2ff>3fb2ff</td><td bgcolor=#55bbff>55bbff</td><td bgcolor=#6ac3ff>6ac3ff</td><td bgcolor=#7fccff>7fccff</td><td bgcolor=#94d4ff>94d4ff</td><td bgcolor=#aaddff>aaddff</td><td bgcolor=#bfe5ff>bfe5ff</td><td bgcolor=#d4eeff>d4eeff</td><td bgcolor=#e9f6ff>e9f6ff</td></tr>
<tr><td bgcolor=#000b15>000b15</td><td bgcolor=#00162a>00162a</td><td bgcolor=#00213f>00213f</td><td bgcolor=#002d55>002d55</td><td bgcolor=#00386a>00386a</td><td bgcolor=#00437f>00437f</td><td bgcolor=#004f94>004f94</td><td bgcolor=#005aaa>005aaa</td><td bgcolor=#0065bf>0065bf</td><td bgcolor=#0071d4>0071d4</td><td bgcolor=#007ce9>007ce9</td><td bgcolor=#0087ff>0087ff</td><td bgcolor=#1591ff>1591ff</td><td bgcolor=#2a9bff>2a9bff</td><td bgcolor=#3fa5ff>3fa5ff</td><td bgcolor=#55afff>55afff</td><td bgcolor=#6ab9ff>6ab9ff</td><td bgcolor=#7fc3ff>7fc3ff</td><td bgcolor=#94cdff>94cdff</td><td bgcolor=#aad7ff>aad7ff</td><td bgcolor=#bfe1ff>bfe1ff</td><td bgcolor=#d4ebff>d4ebff</td><td bgcolor=#e9f5ff>e9f5ff</td></tr>
<tr><td bgcolor=#000915>000915</td><td bgcolor=#00132a>00132a</td><td bgcolor=#001d3f>001d3f</td><td bgcolor=#002755>002755</td><td bgcolor=#00316a>00316a</td><td bgcolor=#003b7f>003b7f</td><td bgcolor=#004594>004594</td><td bgcolor=#004faa>004faa</td><td bgcolor=#0059bf>0059bf</td><td bgcolor=#0063d4>0063d4</td><td bgcolor=#006de9>006de9</td><td bgcolor=#0077ff>0077ff</td><td bgcolor=#1582ff>1582ff</td><td bgcolor=#2a8dff>2a8dff</td><td bgcolor=#3f99ff>3f99ff</td><td bgcolor=#55a4ff>55a4ff</td><td bgcolor=#6aafff>6aafff</td><td bgcolor=#7fbbff>7fbbff</td><td bgcolor=#94c6ff>94c6ff</td><td bgcolor=#aad1ff>aad1ff</td><td bgcolor=#bfddff>bfddff</td><td bgcolor=#d4e8ff>d4e8ff</td><td bgcolor=#e9f3ff>e9f3ff</td></tr>
<tr><td bgcolor=#000815>000815</td><td bgcolor=#00102a>00102a</td><td bgcolor=#00193f>00193f</td><td bgcolor=#002255>002255</td><td bgcolor=#002a6a>002a6a</td><td bgcolor=#00327f>00327f</td><td bgcolor=#003b94>003b94</td><td bgcolor=#0044aa>0044aa</td><td bgcolor=#004cbf>004cbf</td><td bgcolor=#0054d4>0054d4</td><td bgcolor=#005de9>005de9</td><td bgcolor=#0065ff>0065ff</td><td bgcolor=#1572ff>1572ff</td><td bgcolor=#2a7fff>2a7fff</td><td bgcolor=#3f8cff>3f8cff</td><td bgcolor=#5599ff>5599ff</td><td bgcolor=#6aa5ff>6aa5ff</td><td bgcolor=#7fb2ff>7fb2ff</td><td bgcolor=#94bfff>94bfff</td><td bgcolor=#aacbff>aacbff</td><td bgcolor=#bfd8ff>bfd8ff</td><td bgcolor=#d4e5ff>d4e5ff</td><td bgcolor=#e9f2ff>e9f2ff</td></tr>
<tr><td bgcolor=#000715>000715</td><td bgcolor=#000e2a>000e2a</td><td bgcolor=#00153f>00153f</td><td bgcolor=#001c55>001c55</td><td bgcolor=#00236a>00236a</td><td bgcolor=#002a7f>002a7f</td><td bgcolor=#003194>003194</td><td bgcolor=#0038aa>0038aa</td><td bgcolor=#003fbf>003fbf</td><td bgcolor=#0046d4>0046d4</td><td bgcolor=#004de9>004de9</td><td bgcolor=#0055ff>0055ff</td><td bgcolor=#1563ff>1563ff</td><td bgcolor=#2a71ff>2a71ff</td><td bgcolor=#3f7fff>3f7fff</td><td bgcolor=#558dff>558dff</td><td bgcolor=#6a9bff>6a9bff</td><td bgcolor=#7faaff>7faaff</td><td bgcolor=#94b8ff>94b8ff</td><td bgcolor=#aac6ff>aac6ff</td><td bgcolor=#bfd4ff>bfd4ff</td><td bgcolor=#d4e2ff>d4e2ff</td><td bgcolor=#e9f0ff>e9f0ff</td></tr>
<tr><td bgcolor=#000515>000515</td><td bgcolor=#000b2a>000b2a</td><td bgcolor=#00103f>00103f</td><td bgcolor=#001655>001655</td><td bgcolor=#001c6a>001c6a</td><td bgcolor=#00217f>00217f</td><td bgcolor=#002794>002794</td><td bgcolor=#002daa>002daa</td><td bgcolor=#0032bf>0032bf</td><td bgcolor=#0038d4>0038d4</td><td bgcolor=#003ee9>003ee9</td><td bgcolor=#0043ff>0043ff</td><td bgcolor=#1553ff>1553ff</td><td bgcolor=#2a63ff>2a63ff</td><td bgcolor=#3f72ff>3f72ff</td><td bgcolor=#5582ff>5582ff</td><td bgcolor=#6a91ff>6a91ff</td><td bgcolor=#7fa1ff>7fa1ff</td><td bgcolor=#94b1ff>94b1ff</td><td bgcolor=#aac0ff>aac0ff</td><td bgcolor=#bfd0ff>bfd0ff</td><td bgcolor=#d4dfff>d4dfff</td><td bgcolor=#e9efff>e9efff</td></tr>
<tr><td bgcolor=#000415>000415</td><td bgcolor=#00082a>00082a</td><td bgcolor=#000c3f>000c3f</td><td bgcolor=#001155>001155</td><td bgcolor=#00156a>00156a</td><td bgcolor=#00197f>00197f</td><td bgcolor=#001d94>001d94</td><td bgcolor=#0022aa>0022aa</td><td bgcolor=#0026bf>0026bf</td><td bgcolor=#002ad4>002ad4</td><td bgcolor=#002ee9>002ee9</td><td bgcolor=#0033ff>0033ff</td><td bgcolor=#1544ff>1544ff</td><td bgcolor=#2a55ff>2a55ff</td><td bgcolor=#3f66ff>3f66ff</td><td bgcolor=#5577ff>5577ff</td><td bgcolor=#6a88ff>6a88ff</td><td bgcolor=#7f99ff>7f99ff</td><td bgcolor=#94aaff>94aaff</td><td bgcolor=#aabbff>aabbff</td><td bgcolor=#bfccff>bfccff</td><td bgcolor=#d4ddff>d4ddff</td><td bgcolor=#e9eeff>e9eeff</td></tr>
<tr><td bgcolor=#000215>000215</td><td bgcolor=#00052a>00052a</td><td bgcolor=#00083f>00083f</td><td bgcolor=#000b55>000b55</td><td bgcolor=#000e6a>000e6a</td><td bgcolor=#00107f>00107f</td><td bgcolor=#001394>001394</td><td bgcolor=#0016aa>0016aa</td><td bgcolor=#0019bf>0019bf</td><td bgcolor=#001cd4>001cd4</td><td bgcolor=#001fe9>001fe9</td><td bgcolor=#0021ff>0021ff</td><td bgcolor=#1534ff>1534ff</td><td bgcolor=#2a46ff>2a46ff</td><td bgcolor=#3f59ff>3f59ff</td><td bgcolor=#556bff>556bff</td><td bgcolor=#6a7eff>6a7eff</td><td bgcolor=#7f90ff>7f90ff</td><td bgcolor=#94a2ff>94a2ff</td><td bgcolor=#aab5ff>aab5ff</td><td bgcolor=#bfc7ff>bfc7ff</td><td bgcolor=#d4daff>d4daff</td><td bgcolor=#e9ecff>e9ecff</td></tr>
<tr><td bgcolor=#000115>000115</td><td bgcolor=#00022a>00022a</td><td bgcolor=#00043f>00043f</td><td bgcolor=#000555>000555</td><td bgcolor=#00076a>00076a</td><td bgcolor=#00087f>00087f</td><td bgcolor=#000994>000994</td><td bgcolor=#000baa>000baa</td><td bgcolor=#000cbf>000cbf</td><td bgcolor=#000ed4>000ed4</td><td bgcolor=#000fe9>000fe9</td><td bgcolor=#0011ff>0011ff</td><td bgcolor=#1524ff>1524ff</td><td bgcolor=#2a38ff>2a38ff</td><td bgcolor=#3f4cff>3f4cff</td><td bgcolor=#5560ff>5560ff</td><td bgcolor=#6a74ff>6a74ff</td><td bgcolor=#7f88ff>7f88ff</td><td bgcolor=#949bff>949bff</td><td bgcolor=#aaafff>aaafff</td><td bgcolor=#bfc3ff>bfc3ff</td><td bgcolor=#d4d7ff>d4d7ff</td><td bgcolor=#e9ebff>e9ebff</td></tr>
<tr><td bgcolor=#000015>000015</td><td bgcolor=#00002a>00002a</td><td bgcolor=#00003f>00003f</td><td bgcolor=#000055>000055</td><td bgcolor=#00006a>00006a</td><td bgcolor=#00007f>00007f</td><td bgcolor=#000094>000094</td><td bgcolor=#0000aa>0000aa</td><td bgcolor=#0000bf>0000bf</td><td bgcolor=#0000d4>0000d4</td><td bgcolor=#0000e9>0000e9</td><td bgcolor=#0000ff>0000ff</td><td bgcolor=#1515ff>1515ff</td><td bgcolor=#2a2aff>2a2aff</td><td bgcolor=#3f3fff>3f3fff</td><td bgcolor=#5555ff>5555ff</td><td bgcolor=#6a6aff>6a6aff</td><td bgcolor=#7f7fff>7f7fff</td><td bgcolor=#9494ff>9494ff</td><td bgcolor=#aaaaff>aaaaff</td><td bgcolor=#bfbfff>bfbfff</td><td bgcolor=#d4d4ff>d4d4ff</td><td bgcolor=#e9e9ff>e9e9ff</td></tr>
<tr><td bgcolor=#010015>010015</td><td bgcolor=#02002a>02002a</td><td bgcolor=#04003f>04003f</td><td bgcolor=#050055>050055</td><td bgcolor=#07006a>07006a</td><td bgcolor=#08007f>08007f</td><td bgcolor=#090094>090094</td><td bgcolor=#0b00aa>0b00aa</td><td bgcolor=#0c00bf>0c00bf</td><td bgcolor=#0e00d4>0e00d4</td><td bgcolor=#0f00e9>0f00e9</td><td bgcolor=#1000ff>1000ff</td><td bgcolor=#2415ff>2415ff</td><td bgcolor=#382aff>382aff</td><td bgcolor=#4c3fff>4c3fff</td><td bgcolor=#6055ff>6055ff</td><td bgcolor=#746aff>746aff</td><td bgcolor=#877fff>877fff</td><td bgcolor=#9b94ff>9b94ff</td><td bgcolor=#afaaff>afaaff</td><td bgcolor=#c3bfff>c3bfff</td><td bgcolor=#d7d4ff>d7d4ff</td><td bgcolor=#ebe9ff>ebe9ff</td></tr>
<tr><td bgcolor=#020015>020015</td><td bgcolor=#05002a>05002a</td><td bgcolor=#08003f>08003f</td><td bgcolor=#0b0055>0b0055</td><td bgcolor=#0e006a>0e006a</td><td bgcolor=#11007f>11007f</td><td bgcolor=#130094>130094</td><td bgcolor=#1600aa>1600aa</td><td bgcolor=#1900bf>1900bf</td><td bgcolor=#1c00d4>1c00d4</td><td bgcolor=#1f00e9>1f00e9</td><td bgcolor=#2200ff>2200ff</td><td bgcolor=#3415ff>3415ff</td><td bgcolor=#462aff>462aff</td><td bgcolor=#593fff>593fff</td><td bgcolor=#6b55ff>6b55ff</td><td bgcolor=#7e6aff>7e6aff</td><td bgcolor=#907fff>907fff</td><td bgcolor=#a294ff>a294ff</td><td bgcolor=#b5aaff>b5aaff</td><td bgcolor=#c7bfff>c7bfff</td><td bgcolor=#dad4ff>dad4ff</td><td bgcolor=#ece9ff>ece9ff</td></tr>
<tr><td bgcolor=#040015>040015</td><td bgcolor=#08002a>08002a</td><td bgcolor=#0c003f>0c003f</td><td bgcolor=#110055>110055</td><td bgcolor=#15006a>15006a</td><td bgcolor=#19007f>19007f</td><td bgcolor=#1d0094>1d0094</td><td bgcolor=#2200aa>2200aa</td><td bgcolor=#2600bf>2600bf</td><td bgcolor=#2a00d4>2a00d4</td><td bgcolor=#2e00e9>2e00e9</td><td bgcolor=#3300ff>3300ff</td><td bgcolor=#4415ff>4415ff</td><td bgcolor=#552aff>552aff</td><td bgcolor=#663fff>663fff</td><td bgcolor=#7755ff>7755ff</td><td bgcolor=#886aff>886aff</td><td bgcolor=#997fff>997fff</td><td bgcolor=#aa94ff>aa94ff</td><td bgcolor=#bbaaff>bbaaff</td><td bgcolor=#ccbfff>ccbfff</td><td bgcolor=#ddd4ff>ddd4ff</td><td bgcolor=#eee9ff>eee9ff</td></tr>
<tr><td bgcolor=#050015>050015</td><td bgcolor=#0b002a>0b002a</td><td bgcolor=#10003f>10003f</td><td bgcolor=#160055>160055</td><td bgcolor=#1c006a>1c006a</td><td bgcolor=#21007f>21007f</td><td bgcolor=#270094>270094</td><td bgcolor=#2d00aa>2d00aa</td><td bgcolor=#3200bf>3200bf</td><td bgcolor=#3800d4>3800d4</td><td bgcolor=#3e00e9>3e00e9</td><td bgcolor=#4300ff>4300ff</td><td bgcolor=#5315ff>5315ff</td><td bgcolor=#632aff>632aff</td><td bgcolor=#723fff>723fff</td><td bgcolor=#8255ff>8255ff</td><td bgcolor=#916aff>916aff</td><td bgcolor=#a17fff>a17fff</td><td bgcolor=#b194ff>b194ff</td><td bgcolor=#c0aaff>c0aaff</td><td bgcolor=#d0bfff>d0bfff</td><td bgcolor=#dfd4ff>dfd4ff</td><td bgcolor=#efe9ff>efe9ff</td></tr>
<tr><td bgcolor=#070015>070015</td><td bgcolor=#0e002a>0e002a</td><td bgcolor=#15003f>15003f</td><td bgcolor=#1c0055>1c0055</td><td bgcolor=#23006a>23006a</td><td bgcolor=#2a007f>2a007f</td><td bgcolor=#310094>310094</td><td bgcolor=#3800aa>3800aa</td><td bgcolor=#3f00bf>3f00bf</td><td bgcolor=#4600d4>4600d4</td><td bgcolor=#4d00e9>4d00e9</td><td bgcolor=#5400ff>5400ff</td><td bgcolor=#6315ff>6315ff</td><td bgcolor=#712aff>712aff</td><td bgcolor=#7f3fff>7f3fff</td><td bgcolor=#8d55ff>8d55ff</td><td bgcolor=#9b6aff>9b6aff</td><td bgcolor=#a97fff>a97fff</td><td bgcolor=#b894ff>b894ff</td><td bgcolor=#c6aaff>c6aaff</td><td bgcolor=#d4bfff>d4bfff</td><td bgcolor=#e2d4ff>e2d4ff</td><td bgcolor=#f0e9ff>f0e9ff</td></tr>
<tr><td bgcolor=#080015>080015</td><td bgcolor=#11002a>11002a</td><td bgcolor=#19003f>19003f</td><td bgcolor=#220055>220055</td><td bgcolor=#2a006a>2a006a</td><td bgcolor=#33007f>33007f</td><td bgcolor=#3b0094>3b0094</td><td bgcolor=#4400aa>4400aa</td><td bgcolor=#4c00bf>4c00bf</td><td bgcolor=#5500d4>5500d4</td><td bgcolor=#5d00e9>5d00e9</td><td bgcolor=#6600ff>6600ff</td><td bgcolor=#7215ff>7215ff</td><td bgcolor=#7f2aff>7f2aff</td><td bgcolor=#8c3fff>8c3fff</td><td bgcolor=#9955ff>9955ff</td><td bgcolor=#a56aff>a56aff</td><td bgcolor=#b27fff>b27fff</td><td bgcolor=#bf94ff>bf94ff</td><td bgcolor=#ccaaff>ccaaff</td><td bgcolor=#d8bfff>d8bfff</td><td bgcolor=#e5d4ff>e5d4ff</td><td bgcolor=#f2e9ff>f2e9ff</td></tr>
<tr><td bgcolor=#090015>090015</td><td bgcolor=#13002a>13002a</td><td bgcolor=#1d003f>1d003f</td><td bgcolor=#270055>270055</td><td bgcolor=#31006a>31006a</td><td bgcolor=#3b007f>3b007f</td><td bgcolor=#450094>450094</td><td bgcolor=#4f00aa>4f00aa</td><td bgcolor=#5900bf>5900bf</td><td bgcolor=#6300d4>6300d4</td><td bgcolor=#6d00e9>6d00e9</td><td bgcolor=#7700ff>7700ff</td><td bgcolor=#8215ff>8215ff</td><td bgcolor=#8d2aff>8d2aff</td><td bgcolor=#993fff>993fff</td><td bgcolor=#a455ff>a455ff</td><td bgcolor=#af6aff>af6aff</td><td bgcolor=#bb7fff>bb7fff</td><td bgcolor=#c694ff>c694ff</td><td bgcolor=#d1aaff>d1aaff</td><td bgcolor=#ddbfff>ddbfff</td><td bgcolor=#e8d4ff>e8d4ff</td><td bgcolor=#f3e9ff>f3e9ff</td></tr>
<tr><td bgcolor=#0b0015>0b0015</td><td bgcolor=#16002a>16002a</td><td bgcolor=#21003f>21003f</td><td bgcolor=#2d0055>2d0055</td><td bgcolor=#38006a>38006a</td><td bgcolor=#43007f>43007f</td><td bgcolor=#4f0094>4f0094</td><td bgcolor=#5a00aa>5a00aa</td><td bgcolor=#6500bf>6500bf</td><td bgcolor=#7100d4>7100d4</td><td bgcolor=#7c00e9>7c00e9</td><td bgcolor=#8700ff>8700ff</td><td bgcolor=#9115ff>9115ff</td><td bgcolor=#9b2aff>9b2aff</td><td bgcolor=#a53fff>a53fff</td><td bgcolor=#af55ff>af55ff</td><td bgcolor=#b96aff>b96aff</td><td bgcolor=#c37fff>c37fff</td><td bgcolor=#cd94ff>cd94ff</td><td bgcolor=#d7aaff>d7aaff</td><td bgcolor=#e1bfff>e1bfff</td><td bgcolor=#ebd4ff>ebd4ff</td><td bgcolor=#f5e9ff>f5e9ff</td></tr>
<tr><td bgcolor=#0c0015>0c0015</td><td bgcolor=#19002a>19002a</td><td bgcolor=#26003f>26003f</td><td bgcolor=#320055>320055</td><td bgcolor=#3f006a>3f006a</td><td bgcolor=#4c007f>4c007f</td><td bgcolor=#590094>590094</td><td bgcolor=#6500aa>6500aa</td><td bgcolor=#7200bf>7200bf</td><td bgcolor=#7f00d4>7f00d4</td><td bgcolor=#8c00e9>8c00e9</td><td bgcolor=#9800ff>9800ff</td><td bgcolor=#a115ff>a115ff</td><td bgcolor=#a92aff>a92aff</td><td bgcolor=#b23fff>b23fff</td><td bgcolor=#ba55ff>ba55ff</td><td bgcolor=#c36aff>c36aff</td><td bgcolor=#cb7fff>cb7fff</td><td bgcolor=#d494ff>d494ff</td><td bgcolor=#dcaaff>dcaaff</td><td bgcolor=#e5bfff>e5bfff</td><td bgcolor=#eed4ff>eed4ff</td><td bgcolor=#f6e9ff>f6e9ff</td></tr>
<tr><td bgcolor=#0e0015>0e0015</td><td bgcolor=#1c002a>1c002a</td><td bgcolor=#2a003f>2a003f</td><td bgcolor=#380055>380055</td><td bgcolor=#46006a>46006a</td><td bgcolor=#55007f>55007f</td><td bgcolor=#630094>630094</td><td bgcolor=#7100aa>7100aa</td><td bgcolor=#7f00bf>7f00bf</td><td bgcolor=#8d00d4>8d00d4</td><td bgcolor=#9b00e9>9b00e9</td><td bgcolor=#aa00ff>aa00ff</td><td bgcolor=#b115ff>b115ff</td><td bgcolor=#b82aff>b82aff</td><td bgcolor=#bf3fff>bf3fff</td><td bgcolor=#c655ff>c655ff</td><td bgcolor=#cd6aff>cd6aff</td><td bgcolor=#d47fff>d47fff</td><td bgcolor=#db94ff>db94ff</td><td bgcolor=#e2aaff>e2aaff</td><td bgcolor=#e9bfff>e9bfff</td><td bgcolor=#f0d4ff>f0d4ff</td><td bgcolor=#f7e9ff>f7e9ff</td></tr>
<tr><td bgcolor=#0f0015>0f0015</td><td bgcolor=#1f002a>1f002a</td><td bgcolor=#2e003f>2e003f</td><td bgcolor=#3e0055>3e0055</td><td bgcolor=#4d006a>4d006a</td><td bgcolor=#5d007f>5d007f</td><td bgcolor=#6d0094>6d0094</td><td bgcolor=#7c00aa>7c00aa</td><td bgcolor=#8c00bf>8c00bf</td><td bgcolor=#9b00d4>9b00d4</td><td bgcolor=#ab00e9>ab00e9</td><td bgcolor=#bb00ff>bb00ff</td><td bgcolor=#c015ff>c015ff</td><td bgcolor=#c62aff>c62aff</td><td bgcolor=#cc3fff>cc3fff</td><td bgcolor=#d155ff>d155ff</td><td bgcolor=#d76aff>d76aff</td><td bgcolor=#dd7fff>dd7fff</td><td bgcolor=#e294ff>e294ff</td><td bgcolor=#e8aaff>e8aaff</td><td bgcolor=#eebfff>eebfff</td><td bgcolor=#f3d4ff>f3d4ff</td><td bgcolor=#f9e9ff>f9e9ff</td></tr>
<tr><td bgcolor=#110015>110015</td><td bgcolor=#21002a>21002a</td><td bgcolor=#32003f>32003f</td><td bgcolor=#440055>440055</td><td bgcolor=#54006a>54006a</td><td bgcolor=#65007f>65007f</td><td bgcolor=#760094>760094</td><td bgcolor=#8800aa>8800aa</td><td bgcolor=#9800bf>9800bf</td><td bgcolor=#a900d4>a900d4</td><td bgcolor=#ba00e9>ba00e9</td><td bgcolor=#cb00ff>cb00ff</td><td bgcolor=#d015ff>d015ff</td><td bgcolor=#d42aff>d42aff</td><td bgcolor=#d83fff>d83fff</td><td bgcolor=#dd55ff>dd55ff</td><td bgcolor=#e16aff>e16aff</td><td bgcolor=#e57fff>e57fff</td><td bgcolor=#e994ff>e994ff</td><td bgcolor=#eeaaff>eeaaff</td><td bgcolor=#f2bfff>f2bfff</td><td bgcolor=#f6d4ff>f6d4ff</td><td bgcolor=#fae9ff>fae9ff</td></tr>
<tr><td bgcolor=#120015>120015</td><td bgcolor=#24002a>24002a</td><td bgcolor=#37003f>37003f</td><td bgcolor=#490055>490055</td><td bgcolor=#5c006a>5c006a</td><td bgcolor=#6e007f>6e007f</td><td bgcolor=#800094>800094</td><td bgcolor=#9300aa>9300aa</td><td bgcolor=#a500bf>a500bf</td><td bgcolor=#b800d4>b800d4</td><td bgcolor=#ca00e9>ca00e9</td><td bgcolor=#dc00ff>dc00ff</td><td bgcolor=#df15ff>df15ff</td><td bgcolor=#e22aff>e22aff</td><td bgcolor=#e53fff>e53fff</td><td bgcolor=#e855ff>e855ff</td><td bgcolor=#eb6aff>eb6aff</td><td bgcolor=#ed7fff>ed7fff</td><td bgcolor=#f094ff>f094ff</td><td bgcolor=#f3aaff>f3aaff</td><td bgcolor=#f6bfff>f6bfff</td><td bgcolor=#f9d4ff>f9d4ff</td><td bgcolor=#fce9ff>fce9ff</td></tr>
<tr><td bgcolor=#130015>130015</td><td bgcolor=#27002a>27002a</td><td bgcolor=#3b003f>3b003f</td><td bgcolor=#4f0055>4f0055</td><td bgcolor=#63006a>63006a</td><td bgcolor=#77007f>77007f</td><td bgcolor=#8a0094>8a0094</td><td bgcolor=#9e00aa>9e00aa</td><td bgcolor=#b200bf>b200bf</td><td bgcolor=#c600d4>c600d4</td><td bgcolor=#da00e9>da00e9</td><td bgcolor=#ee00ff>ee00ff</td><td bgcolor=#ef15ff>ef15ff</td><td bgcolor=#f02aff>f02aff</td><td bgcolor=#f23fff>f23fff</td><td bgcolor=#f355ff>f355ff</td><td bgcolor=#f56aff>f56aff</td><td bgcolor=#f67fff>f67fff</td><td bgcolor=#f794ff>f794ff</td><td bgcolor=#f9aaff>f9aaff</td><td bgcolor=#fabfff>fabfff</td><td bgcolor=#fcd4ff>fcd4ff</td><td bgcolor=#fde9ff>fde9ff</td></tr>
<tr><td bgcolor=#150015>150015</td><td bgcolor=#2a002a>2a002a</td><td bgcolor=#3f003f>3f003f</td><td bgcolor=#550055>550055</td><td bgcolor=#6a006a>6a006a</td><td bgcolor=#7f007f>7f007f</td><td bgcolor=#940094>940094</td><td bgcolor=#aa00aa>aa00aa</td><td bgcolor=#bf00bf>bf00bf</td><td bgcolor=#d400d4>d400d4</td><td bgcolor=#e900e9>e900e9</td><td bgcolor=#ff00ff>ff00ff</td><td bgcolor=#ff15ff>ff15ff</td><td bgcolor=#ff2aff>ff2aff</td><td bgcolor=#ff3fff>ff3fff</td><td bgcolor=#ff55ff>ff55ff</td><td bgcolor=#ff6aff>ff6aff</td><td bgcolor=#ff7fff>ff7fff</td><td bgcolor=#ff94ff>ff94ff</td><td bgcolor=#ffaaff>ffaaff</td><td bgcolor=#ffbfff>ffbfff</td><td bgcolor=#ffd4ff>ffd4ff</td><td bgcolor=#ffe9ff>ffe9ff</td></tr>
<tr><td bgcolor=#150013>150013</td><td bgcolor=#2a0027>2a0027</td><td bgcolor=#3f003b>3f003b</td><td bgcolor=#55004f>55004f</td><td bgcolor=#6a0063>6a0063</td><td bgcolor=#7f0077>7f0077</td><td bgcolor=#94008a>94008a</td><td bgcolor=#aa009e>aa009e</td><td bgcolor=#bf00b2>bf00b2</td><td bgcolor=#d400c6>d400c6</td><td bgcolor=#e900da>e900da</td><td bgcolor=#ff00ee>ff00ee</td><td bgcolor=#ff15ef>ff15ef</td><td bgcolor=#ff2af0>ff2af0</td><td bgcolor=#ff3ff2>ff3ff2</td><td bgcolor=#ff55f3>ff55f3</td><td bgcolor=#ff6af5>ff6af5</td><td bgcolor=#ff7ff6>ff7ff6</td><td bgcolor=#ff94f7>ff94f7</td><td bgcolor=#ffaaf9>ffaaf9</td><td bgcolor=#ffbffa>ffbffa</td><td bgcolor=#ffd4fc>ffd4fc</td><td bgcolor=#ffe9fd>ffe9fd</td></tr>
<tr><td bgcolor=#150012>150012</td><td bgcolor=#2a0024>2a0024</td><td bgcolor=#3f0037>3f0037</td><td bgcolor=#550049>550049</td><td bgcolor=#6a005c>6a005c</td><td bgcolor=#7f006e>7f006e</td><td bgcolor=#940080>940080</td><td bgcolor=#aa0093>aa0093</td><td bgcolor=#bf00a5>bf00a5</td><td bgcolor=#d400b8>d400b8</td><td bgcolor=#e900ca>e900ca</td><td bgcolor=#ff00dc>ff00dc</td><td bgcolor=#ff15df>ff15df</td><td bgcolor=#ff2ae2>ff2ae2</td><td bgcolor=#ff3fe5>ff3fe5</td><td bgcolor=#ff55e8>ff55e8</td><td bgcolor=#ff6aeb>ff6aeb</td><td bgcolor=#ff7fed>ff7fed</td><td bgcolor=#ff94f0>ff94f0</td><td bgcolor=#ffaaf3>ffaaf3</td><td bgcolor=#ffbff6>ffbff6</td><td bgcolor=#ffd4f9>ffd4f9</td><td bgcolor=#ffe9fc>ffe9fc</td></tr>
<tr><td bgcolor=#150011>150011</td><td bgcolor=#2a0021>2a0021</td><td bgcolor=#3f0032>3f0032</td><td bgcolor=#550044>550044</td><td bgcolor=#6a0054>6a0054</td><td bgcolor=#7f0065>7f0065</td><td bgcolor=#940076>940076</td><td bgcolor=#aa0088>aa0088</td><td bgcolor=#bf0098>bf0098</td><td bgcolor=#d400a9>d400a9</td><td bgcolor=#e900ba>e900ba</td><td bgcolor=#ff00cb>ff00cb</td><td bgcolor=#ff15d0>ff15d0</td><td bgcolor=#ff2ad4>ff2ad4</td><td bgcolor=#ff3fd8>ff3fd8</td><td bgcolor=#ff55dd>ff55dd</td><td bgcolor=#ff6ae1>ff6ae1</td><td bgcolor=#ff7fe5>ff7fe5</td><td bgcolor=#ff94e9>ff94e9</td><td bgcolor=#ffaaee>ffaaee</td><td bgcolor=#ffbff2>ffbff2</td><td bgcolor=#ffd4f6>ffd4f6</td><td bgcolor=#ffe9fa>ffe9fa</td></tr>
<tr><td bgcolor=#15000f>15000f</td><td bgcolor=#2a001f>2a001f</td><td bgcolor=#3f002e>3f002e</td><td bgcolor=#55003e>55003e</td><td bgcolor=#6a004d>6a004d</td><td bgcolor=#7f005d>7f005d</td><td bgcolor=#94006d>94006d</td><td bgcolor=#aa007c>aa007c</td><td bgcolor=#bf008c>bf008c</td><td bgcolor=#d4009b>d4009b</td><td bgcolor=#e900ab>e900ab</td><td bgcolor=#ff00bb>ff00bb</td><td bgcolor=#ff15c0>ff15c0</td><td bgcolor=#ff2ac6>ff2ac6</td><td bgcolor=#ff3fcc>ff3fcc</td><td bgcolor=#ff55d1>ff55d1</td><td bgcolor=#ff6ad7>ff6ad7</td><td bgcolor=#ff7fdd>ff7fdd</td><td bgcolor=#ff94e2>ff94e2</td><td bgcolor=#ffaae8>ffaae8</td><td bgcolor=#ffbfee>ffbfee</td><td bgcolor=#ffd4f3>ffd4f3</td><td bgcolor=#ffe9f9>ffe9f9</td></tr>
<tr><td bgcolor=#15000e>15000e</td><td bgcolor=#2a001c>2a001c</td><td bgcolor=#3f002a>3f002a</td><td bgcolor=#550038>550038</td><td bgcolor=#6a0046>6a0046</td><td bgcolor=#7f0055>7f0055</td><td bgcolor=#940063>940063</td><td bgcolor=#aa0071>aa0071</td><td bgcolor=#bf007f>bf007f</td><td bgcolor=#d4008d>d4008d</td><td bgcolor=#e9009b>e9009b</td><td bgcolor=#ff00aa>ff00aa</td><td bgcolor=#ff15b1>ff15b1</td><td bgcolor=#ff2ab8>ff2ab8</td><td bgcolor=#ff3fbf>ff3fbf</td><td bgcolor=#ff55c6>ff55c6</td><td bgcolor=#ff6acd>ff6acd</td><td bgcolor=#ff7fd4>ff7fd4</td><td bgcolor=#ff94db>ff94db</td><td bgcolor=#ffaae2>ffaae2</td><td bgcolor=#ffbfe9>ffbfe9</td><td bgcolor=#ffd4f0>ffd4f0</td><td bgcolor=#ffe9f7>ffe9f7</td></tr>
<tr><td bgcolor=#15000c>15000c</td><td bgcolor=#2a0019>2a0019</td><td bgcolor=#3f0026>3f0026</td><td bgcolor=#550032>550032</td><td bgcolor=#6a003f>6a003f</td><td bgcolor=#7f004c>7f004c</td><td bgcolor=#940059>940059</td><td bgcolor=#aa0065>aa0065</td><td bgcolor=#bf0072>bf0072</td><td bgcolor=#d4007f>d4007f</td><td bgcolor=#e9008c>e9008c</td><td bgcolor=#ff0098>ff0098</td><td bgcolor=#ff15a1>ff15a1</td><td bgcolor=#ff2aa9>ff2aa9</td><td bgcolor=#ff3fb2>ff3fb2</td><td bgcolor=#ff55ba>ff55ba</td><td bgcolor=#ff6ac3>ff6ac3</td><td bgcolor=#ff7fcb>ff7fcb</td><td bgcolor=#ff94d4>ff94d4</td><td bgcolor=#ffaadc>ffaadc</td><td bgcolor=#ffbfe5>ffbfe5</td><td bgcolor=#ffd4ee>ffd4ee</td><td bgcolor=#ffe9f6>ffe9f6</td></tr>
<tr><td bgcolor=#15000b>15000b</td><td bgcolor=#2a0016>2a0016</td><td bgcolor=#3f0021>3f0021</td><td bgcolor=#55002d>55002d</td><td bgcolor=#6a0038>6a0038</td><td bgcolor=#7f0043>7f0043</td><td bgcolor=#94004f>94004f</td><td bgcolor=#aa005a>aa005a</td><td bgcolor=#bf0065>bf0065</td><td bgcolor=#d40071>d40071</td><td bgcolor=#e9007c>e9007c</td><td bgcolor=#ff0087>ff0087</td><td bgcolor=#ff1591>ff1591</td><td bgcolor=#ff2a9b>ff2a9b</td><td bgcolor=#ff3fa5>ff3fa5</td><td bgcolor=#ff55af>ff55af</td><td bgcolor=#ff6ab9>ff6ab9</td><td bgcolor=#ff7fc3>ff7fc3</td><td bgcolor=#ff94cd>ff94cd</td><td bgcolor=#ffaad7>ffaad7</td><td bgcolor=#ffbfe1>ffbfe1</td><td bgcolor=#ffd4eb>ffd4eb</td><td bgcolor=#ffe9f5>ffe9f5</td></tr>
<tr><td bgcolor=#150009>150009</td><td bgcolor=#2a0013>2a0013</td><td bgcolor=#3f001d>3f001d</td><td bgcolor=#550027>550027</td><td bgcolor=#6a0031>6a0031</td><td bgcolor=#7f003b>7f003b</td><td bgcolor=#940045>940045</td><td bgcolor=#aa004f>aa004f</td><td bgcolor=#bf0059>bf0059</td><td bgcolor=#d40063>d40063</td><td bgcolor=#e9006d>e9006d</td><td bgcolor=#ff0077>ff0077</td><td bgcolor=#ff1582>ff1582</td><td bgcolor=#ff2a8d>ff2a8d</td><td bgcolor=#ff3f99>ff3f99</td><td bgcolor=#ff55a4>ff55a4</td><td bgcolor=#ff6aaf>ff6aaf</td><td bgcolor=#ff7fbb>ff7fbb</td><td bgcolor=#ff94c6>ff94c6</td><td bgcolor=#ffaad1>ffaad1</td><td bgcolor=#ffbfdd>ffbfdd</td><td bgcolor=#ffd4e8>ffd4e8</td><td bgcolor=#ffe9f3>ffe9f3</td></tr>
<tr><td bgcolor=#150008>150008</td><td bgcolor=#2a0011>2a0011</td><td bgcolor=#3f0019>3f0019</td><td bgcolor=#550022>550022</td><td bgcolor=#6a002a>6a002a</td><td bgcolor=#7f0033>7f0033</td><td bgcolor=#94003b>94003b</td><td bgcolor=#aa0044>aa0044</td><td bgcolor=#bf004c>bf004c</td><td bgcolor=#d40055>d40055</td><td bgcolor=#e9005d>e9005d</td><td bgcolor=#ff0066>ff0066</td><td bgcolor=#ff1572>ff1572</td><td bgcolor=#ff2a7f>ff2a7f</td><td bgcolor=#ff3f8c>ff3f8c</td><td bgcolor=#ff5599>ff5599</td><td bgcolor=#ff6aa5>ff6aa5</td><td bgcolor=#ff7fb2>ff7fb2</td><td bgcolor=#ff94bf>ff94bf</td><td bgcolor=#ffaacc>ffaacc</td><td bgcolor=#ffbfd8>ffbfd8</td><td bgcolor=#ffd4e5>ffd4e5</td><td bgcolor=#ffe9f2>ffe9f2</td></tr>
<tr><td bgcolor=#150007>150007</td><td bgcolor=#2a000e>2a000e</td><td bgcolor=#3f0015>3f0015</td><td bgcolor=#55001c>55001c</td><td bgcolor=#6a0023>6a0023</td><td bgcolor=#7f002a>7f002a</td><td bgcolor=#940031>940031</td><td bgcolor=#aa0038>aa0038</td><td bgcolor=#bf003f>bf003f</td><td bgcolor=#d40046>d40046</td><td bgcolor=#e9004d>e9004d</td><td bgcolor=#ff0054>ff0054</td><td bgcolor=#ff1563>ff1563</td><td bgcolor=#ff2a71>ff2a71</td><td bgcolor=#ff3f7f>ff3f7f</td><td bgcolor=#ff558d>ff558d</td><td bgcolor=#ff6a9b>ff6a9b</td><td bgcolor=#ff7fa9>ff7fa9</td><td bgcolor=#ff94b8>ff94b8</td><td bgcolor=#ffaac6>ffaac6</td><td bgcolor=#ffbfd4>ffbfd4</td><td bgcolor=#ffd4e2>ffd4e2</td><td bgcolor=#ffe9f0>ffe9f0</td></tr>
<tr><td bgcolor=#150005>150005</td><td bgcolor=#2a000b>2a000b</td><td bgcolor=#3f0010>3f0010</td><td bgcolor=#550016>550016</td><td bgcolor=#6a001c>6a001c</td><td bgcolor=#7f0021>7f0021</td><td bgcolor=#940027>940027</td><td bgcolor=#aa002d>aa002d</td><td bgcolor=#bf0032>bf0032</td><td bgcolor=#d40038>d40038</td><td bgcolor=#e9003e>e9003e</td><td bgcolor=#ff0043>ff0043</td><td bgcolor=#ff1553>ff1553</td><td bgcolor=#ff2a63>ff2a63</td><td bgcolor=#ff3f72>ff3f72</td><td bgcolor=#ff5582>ff5582</td><td bgcolor=#ff6a91>ff6a91</td><td bgcolor=#ff7fa1>ff7fa1</td><td bgcolor=#ff94b1>ff94b1</td><td bgcolor=#ffaac0>ffaac0</td><td bgcolor=#ffbfd0>ffbfd0</td><td bgcolor=#ffd4df>ffd4df</td><td bgcolor=#ffe9ef>ffe9ef</td></tr>
<tr><td bgcolor=#150004>150004</td><td bgcolor=#2a0008>2a0008</td><td bgcolor=#3f000c>3f000c</td><td bgcolor=#550011>550011</td><td bgcolor=#6a0015>6a0015</td><td bgcolor=#7f0019>7f0019</td><td bgcolor=#94001d>94001d</td><td bgcolor=#aa0022>aa0022</td><td bgcolor=#bf0026>bf0026</td><td bgcolor=#d4002a>d4002a</td><td bgcolor=#e9002e>e9002e</td><td bgcolor=#ff0033>ff0033</td><td bgcolor=#ff1544>ff1544</td><td bgcolor=#ff2a55>ff2a55</td><td bgcolor=#ff3f66>ff3f66</td><td bgcolor=#ff5577>ff5577</td><td bgcolor=#ff6a88>ff6a88</td><td bgcolor=#ff7f99>ff7f99</td><td bgcolor=#ff94aa>ff94aa</td><td bgcolor=#ffaabb>ffaabb</td><td bgcolor=#ffbfcc>ffbfcc</td><td bgcolor=#ffd4dd>ffd4dd</td><td bgcolor=#ffe9ee>ffe9ee</td></tr>
<tr><td bgcolor=#150002>150002</td><td bgcolor=#2a0005>2a0005</td><td bgcolor=#3f0008>3f0008</td><td bgcolor=#55000b>55000b</td><td bgcolor=#6a000e>6a000e</td><td bgcolor=#7f0011>7f0011</td><td bgcolor=#940013>940013</td><td bgcolor=#aa0016>aa0016</td><td bgcolor=#bf0019>bf0019</td><td bgcolor=#d4001c>d4001c</td><td bgcolor=#e9001f>e9001f</td><td bgcolor=#ff0022>ff0022</td><td bgcolor=#ff1534>ff1534</td><td bgcolor=#ff2a46>ff2a46</td><td bgcolor=#ff3f59>ff3f59</td><td bgcolor=#ff556b>ff556b</td><td bgcolor=#ff6a7e>ff6a7e</td><td bgcolor=#ff7f90>ff7f90</td><td bgcolor=#ff94a2>ff94a2</td><td bgcolor=#ffaab5>ffaab5</td><td bgcolor=#ffbfc7>ffbfc7</td><td bgcolor=#ffd4da>ffd4da</td><td bgcolor=#ffe9ec>ffe9ec</td></tr>
<tr><td bgcolor=#150001>150001</td><td bgcolor=#2a0002>2a0002</td><td bgcolor=#3f0004>3f0004</td><td bgcolor=#550005>550005</td><td bgcolor=#6a0007>6a0007</td><td bgcolor=#7f0008>7f0008</td><td bgcolor=#940009>940009</td><td bgcolor=#aa000b>aa000b</td><td bgcolor=#bf000c>bf000c</td><td bgcolor=#d4000e>d4000e</td><td bgcolor=#e9000f>e9000f</td><td bgcolor=#ff0010>ff0010</td><td bgcolor=#ff1524>ff1524</td><td bgcolor=#ff2a38>ff2a38</td><td bgcolor=#ff3f4c>ff3f4c</td><td bgcolor=#ff5560>ff5560</td><td bgcolor=#ff6a74>ff6a74</td><td bgcolor=#ff7f87>ff7f87</td><td bgcolor=#ff949b>ff949b</td><td bgcolor=#ffaaaf>ffaaaf</td><td bgcolor=#ffbfc3>ffbfc3</td><td bgcolor=#ffd4d7>ffd4d7</td><td bgcolor=#ffe9eb>ffe9eb</td></tr>
</table>";    
        }
        
    if($mode == "del_exis_group")
        {
    $to_delete = $_POST['group'];
    if(!$to_delete) 
        message_die(GENERAL_ERROR, $titanium_lang['delete_error'] . "<br /><br />" . sprintf($titanium_lang['Return_to_config'], "<a href=admin_advanced_username_color.php>", "</a>"), $titanium_lang['error']);
    
    $q = "DELETE FROM ". COLORS ."
          WHERE group_id = '". $to_delete ."'";
    $r = $titanium_db -> sql_query($q);
    
    #==== Update all users for this group
    $q = "SELECT user_color_gi, user_id
          FROM ". USERS_TABLE ."
          WHERE user_color_gi <> '0'";
    $r         = $titanium_db->sql_query($q);
    $row     = $titanium_db->sql_fetchrowset($r);
    
        for ($a = 0; $a < count($row); $a++)
            {
            if (preg_match('/--'. $to_delete .'--/i', $row[$a]['user_color_gi']))
                {
            $remove = str_replace('--'. $to_delete .'--', '', $row[$a]['user_color_gi']);
            if (!$remove)
                $new_id = '0';
            else
                $new_id = $remove;
                
                #==== If in more than 1 group, get the next color
                if ($new_id)
                    {
                $q = "SELECT group_color, group_id
                      FROM ". COLORS ."";
                $r = $titanium_db->sql_query($q);
                $groups_info = $titanium_db->sql_fetchrowset($r);
                
                    for ($b = 0; $b < count($groups_info); $b++)
                        {
                        if (preg_match('/--'. $groups_info[$b]['group_id'] .'--/i', $new_id))
                            {
                        $new_color = $groups_info[$b]['group_color'];
                        break;
                            }
                        }    
                    }
                else
                    $new_color = '';
                    
                $q1 = "UPDATE ". USERS_TABLE ."
                       SET user_color_gi = '$new_id', user_color_gc = '$new_color'
                       WHERE user_id = '". $row[$a]['user_id'] ."'";
/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
                 $cache->delete('UserColors', 'config');
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
                $titanium_db->sql_query($q1);                        
            break;
                }
            }
            
    message_die(GENERAL_MESSAGE, $titanium_lang['delete_success'] . "<br /><br />" . sprintf($titanium_lang['Return_to_config'], "<a href=admin_advanced_username_color.php>", "</a>"), $titanium_lang['success']);
        }
        
    if($mode == "edit_exis_group")
        {
    $phpbb2_color = $_POST['group'];    
    if(!$phpbb2_color) message_die(GENERAL_ERROR, $titanium_lang['edit_error'] . "<br /><br />" . sprintf($titanium_lang['Return_to_config'], "<a href=admin_advanced_username_color.php>", "</a>"), $titanium_lang['error']);
    
    $q = "SELECT *
          FROM ". COLORS ."
          WHERE group_id = '". $phpbb2_color ."'";
    $r            = $titanium_db -> sql_query($q);
    $row         = $titanium_db -> sql_fetchrow($r);
    
    echo "<table width='100%' border='0' class='forumline' cellspacing='2' align='center' valign='middle'>";
    echo "    <tr>";
    echo "        <th class='thHead' colspan='2'>";
    echo "            ". $titanium_lang['admin_main_header_c'];
    echo "        </th>";
    echo "    </tr>";
    echo "</table>";
    echo "<br /><br />";        
    echo "<table border='0' align='center' valign='top' class='forumline' width='100%'>";
    echo "    <tr>";
    echo "        <td align='center' valign='top' width='100%' class='row2'>";
    echo "            <span class='genmed'>";
    echo "                ". $titanium_lang['editing_color'];
    echo "            </span>";
    echo "        </td>";
    echo "    </tr>";        
    echo "</table>";    
    echo "<form name='save_color' action='$link' method='post'>";        
    echo "<table border='0' align='center' valign='top' class='forumline' width='100%'>";
    echo "    <tr>";
    echo "        <td align='left' valign='top' width='50%' class='row2'>";
    echo "            <span class='genmed'>";
    echo "                ". $titanium_lang['editing_color_1'];
    echo "            </span>";
    echo "        </td>";
    echo "        <td align='center' valign='top' width='50%' class='row2'>";            
    echo "            <input type='text' name='new_name' class='post' value='". $row['group_name'] ."'>";
    echo "        </td>";
    echo "    </tr>";
    echo "    <tr>";
    echo "        <td align='left' valign='top' width='50%' class='row2'>";
    echo "            <span class='genmed'>";
    echo "                ". $titanium_lang['editing_color_2'];
    echo "            </span>";
    echo "        </td>";
    echo "        <td align='center' valign='top' width='50%' class='row2'>";            
    echo "            <input type='text' name='new_color' class='post' value='". $row['group_color'] ."'>";
    echo "        </td>";
    echo "    </tr>";    
    echo "</table>";
    echo "<br />";
    echo "<table border='0' align='center' valign='top'>";    
    echo "    <tr>";    
    echo "        <td align='center' valign='middle' width='100%' class='row2'>";    
    echo "            <input type='hidden' name='mode' value='save_new_color'>"; 
    echo "            <input type='hidden' name='old_name' value='". $row['group_name'] ."'>";
    echo "            <input type='hidden' name='id' value='". $row['group_id'] ."'>";                    
    echo "            <input type='submit' class='mainoption' value='". $titanium_lang['editing_color_3'] ."' onchange='document.save_color.submit()'>";       
    echo "        </td>";
    echo "    </tr>";                    
    echo "</table>";    
    echo "</form>";
    echo "<br /><br />";                
        }
    
    if($mode == "save_new_color")
        {
    $new_name     = $_POST['new_name'];
    $new_color     = $_POST['new_color'];
    $old_name     = $_POST['old_name'];
    $id            = $_POST['id'];
    
    if(!$new_name || !$new_color) message_die(GENERAL_ERROR, $titanium_lang['save_error'] . "<br /><br />" . sprintf($titanium_lang['Return_to_config'], "<a href=admin_advanced_username_color.php>", "</a>"), $titanium_lang['error']);
    if(strlen($new_color) <> 6) message_die(GENERAL_ERROR, $titanium_lang['add_error_3'] . "<br /><br />" . sprintf($titanium_lang['Return_to_config'], "<a href=admin_advanced_username_color.php>", "</a>"), $titanium_lang['error']);

    $q = "SELECT group_name
          FROM ". COLORS ."
          WHERE group_name = '". $new_name ."'";
    $r            = $titanium_db -> sql_query($q);
    $row         = $titanium_db -> sql_fetchrow($r);
    $exists = $row['group_name'];
    
        if($new_name != $old_name)
            {
        if($exists == $new_name) message_die(GENERAL_ERROR, $titanium_lang['save_error_1'] . "<br /><br />" . sprintf($titanium_lang['Return_to_config'], "<a href=admin_advanced_username_color.php>", "</a>"), $titanium_lang['error']);
            }    
    
    $q = "SELECT *
          FROM ". COLORS ."
          WHERE group_id = '". $id ."'";
    $r            = $titanium_db -> sql_query($q);
    $row         = $titanium_db -> sql_fetchrow($r);
    $current_c     = $row['group_color'];
                    
    $q = "UPDATE ". COLORS ."
          SET group_name = '". $new_name ."', group_color = '". $new_color ."'
          WHERE group_id = '". $id ."'";
    $r = $titanium_db -> sql_query($q);
    
    $q = "UPDATE ". USERS_TABLE ."
          SET user_color_gc = '". $new_color ."'
          WHERE user_color_gc = '". $current_c ."'";
    $r = $titanium_db -> sql_query($q);    
/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
     $cache->delete('UserColors', 'config');
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
    message_die(GENERAL_MESSAGE, $titanium_lang['add_success'] . "<br /><br />" . sprintf($titanium_lang['Return_to_config'], "<a href=admin_advanced_username_color.php>", "</a>"), $titanium_lang['success']);                    
        }
        
    if($mode == "add_new_color")
        {
    $phpbb2_color_name        = $_POST['new_name'];
    $phpbb2_color_color    = $_POST['new_color'];
    
    if(!$phpbb2_color_name || !$phpbb2_color_color) message_die(GENERAL_ERROR, $titanium_lang['add_error'] . "<br /><br />" . sprintf($titanium_lang['Return_to_config'], "<a href=admin_advanced_username_color.php>", "</a>"), $titanium_lang['error']);
    if(strlen($phpbb2_color_color) <> 6) message_die(GENERAL_ERROR, $titanium_lang['add_error_3'] . "<br /><br />" . sprintf($titanium_lang['Return_to_config'], "<a href=admin_advanced_username_color.php>", "</a>"), $titanium_lang['error']);
        
    $q = "SELECT group_name
          FROM ". COLORS ."
          WHERE group_name = '". $phpbb2_color_name ."'";
    $r            = $titanium_db -> sql_query($q);
    $row         = $titanium_db -> sql_fetchrow($r);
    
    if($row['group_name']) 
        message_die(GENERAL_ERROR, $titanium_lang['add_error_2'] . "<br /><br />" . sprintf($titanium_lang['Return_to_config'], "<a href=admin_advanced_username_color.php>", "</a>"), $titanium_lang['error']);

    $q = "SELECT MAX(group_weight) AS max_weight
          FROM ". COLORS ."";
    $r        = $titanium_db -> sql_query($q);
    $next    = $titanium_db -> sql_fetchrow($r);
        
    $weight = $next['max_weight'] + 1;
    
    $q = "INSERT INTO ". COLORS ."
          VALUES (NULL, '". $phpbb2_color_name ."', '". $phpbb2_color_color ."', '". $weight ."')";
    $r = $titanium_db -> sql_query($q);
                
    message_die(GENERAL_MESSAGE, $titanium_lang['add_success'] . "<br /><br />" . sprintf($titanium_lang['Return_to_config'], "<a href=admin_advanced_username_color.php>", "</a>"), $titanium_lang['success']);        
        }

include('page_footer_admin.' . $phpEx);

?>