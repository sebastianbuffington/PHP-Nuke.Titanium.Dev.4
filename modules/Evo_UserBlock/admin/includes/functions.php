<?php
/*=======================================================================
 PHP-Nuke Titanium v3.0.0 : Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

if (!defined('ADMIN_FILE')) {
   die ("Illegal File Access");
}

function evouserinfo_parse_data($data) {
  $containers = explode(":", $data);
  foreach($containers AS $container)
  {
      $container = str_replace(")", "", $container);
      $i = 0;
      $lastly = explode("(", $container);
      $values = explode(",", $lastly[1]);
      foreach($values AS $value)
      {
        if($value == '')
        {
            continue;
        }
        $final[$lastly[0]][] = $value;
        $i ++;
      }
  }
    return $final;
}

function evouserinfo_getactive () {
    global $titanium_prefix, $titanium_db, $titanium_lang_evo_userblock, $cache;
    static $active;
    if(isset($active) && is_array($active)) return $active;
    
    if ((($active = $cache->load('active', 'evouserinfo')) === false) || !isset($active)) {
        $sql = 'SELECT * FROM '.$titanium_prefix.'_evo_userinfo WHERE active=1 ORDER BY position ASC';
        $result = $titanium_db->sql_query($sql);
        while($row = $titanium_db->sql_fetchrow($result)) {
            $active[] = $row;
        }
        $titanium_db->sql_freeresult($result);
        $cache->save('active', 'evouserinfo', $active);
    }
    return $active;
}

function evouserinfo_getinactive () {
    global $titanium_prefix, $titanium_db, $titanium_lang_evo_userblock, $cache;
    static $inactive;
    if(isset($inactive) && is_array($inactive)) return $inactive;
    
    if ((($inactive = $cache->load('inactive', 'evouserinfo')) === false) || !isset($inactive)) {
        $sql = 'SELECT * FROM `'.$titanium_prefix.'_evo_userinfo` WHERE `active`=0 ORDER BY `position` ASC';
        $result = $titanium_db->sql_query($sql);
        while($row = $titanium_db->sql_fetchrow($result)) {
            $inactive[] = $row;
        }
        $titanium_db->sql_freeresult($result);
        $cache->save('inactive', 'evouserinfo', $inactive);
    }
    return $inactive;
}

function evouserinfo_write_addon ($ext, $values) {
    global $titanium_prefix, $titanium_db, $titanium_lang_evo_userblock;
    foreach ($values as $key => $value) {
        $sql = 'UPDATE `'.$titanium_prefix.'_evo_userinfo_addons` SET `value` = "'.$value.'" WHERE `name` = "'.$ext.'_'.$key.'"';
        $titanium_db->sql_query($sql);
    }
}

function evouserinfo_load_addon ($name) {
    $content = '';
    if(file_exists(NUKE_EVO_USERBLOCK_ADDONS.$name.'.php')){
        include_once(NUKE_EVO_USERBLOCK_ADDONS.$name.'.php');
        if(defined('NO_EVO_USERBLOCK_ADMIN')) {
            return '';
        }
        $output = 'evouserinfo_'.$name;
        global $$output, $evouserinfo_rank;
        $content .= $$output;
    }
    return $content;
}


/*==============================================================================================
    Function:    evouserinfo_radio()
    In:          $data
                    Array of radio button data
                 $br
                    A <br /> after the radio button
    Return:      Radio button HTML code using the passed in array
    Notes:       N/A
================================================================================================*/
function evouserinfo_radio ($data, $br=0) {
    $out = '';
    foreach ($data as $single) {
        $out .= "<input type=\"radio\" name=\"".$single['name']."\" value=\"".$single['value']."\" ".$single['help']." ".$single['checked'].">".$single['text']."\n";
        if($br) {
            $out .= "<br />";
        }
    }
    if ($br) {
        $out = substr($out, 0, strlen($out) - 6);
    }
    return $out;
}

/*==============================================================================================
    Function:    evouserinfo_text()
    In:          $name
                    Name of the text box
                 $text
                    Text to be displayed in the box
                 $size
                    Size of the text box
                 $max
                    Max characters
    Return:      Text box HTML code
    Notes:       N/A
================================================================================================*/
function evouserinfo_text ($name, $text, $size='', $max='') {
    $size = ($size) ? "size=\"".$size."\"" : '';
    $max = ($max) ? "maxlength=\"".$max."\"" : '';
    return "<input type=\"text\" name=\"".$name."\" value=\"".$text."\" ".$size." ".$max." />";
}

/*==============================================================================================
    Function:    evouserinfo_area()
    In:          $name
                    Name of the text area
                 $text
                    Text to be displayed in the area
                 $rows
                    How many rows big
                 $cols
                    How many cols big
    Return:      Text area HTML code
    Notes:       N/A
================================================================================================*/
function evouserinfo_text_area ($name, $text, $rows=5, $cols=20) {
    $size = ($size) ? "size=\"".$size."\"" : '';
    $max = ($max) ? "maxlength=\"".$max."\"" : '';
    return "<TEXTAREA name=\"".$name."\" rows=\"".$rows."\" cols=\"".$cols."\" />".$text."</TEXTAREA>";
}

/*==============================================================================================
    Function:    evouserinfo_combo()
    In:          $name
                    Name of the combo box
                 $data
                    Array of the data to put in the box
                 $default 
                    Default choice
    Return:      Combo box HTML code
    Notes:       N/A
================================================================================================*/
function evouserinfo_combo ($name, $data, $default) {
    $out = "<select name=\"".$name."\">\n";
    foreach ($data as $single) {
        $selected = ($default == $single['value']) ? 'SELECTED' : '';
        $out .= "<option value=\"".$single['value']."\" ".$selected.">".$single['text']."</option>\n";
    }
    $out .= "</select>\n";
    return $out;
}

/*==============================================================================================
    Function:    evouserinfo_help_popup()
    In:          $text
                    Popup text
                 $caption
                    Popup caption
    Return:      N/A
    Notes:       The javacode for the popup
================================================================================================*/
function evouserinfo_help_popup($text, $caption) {
    return "onmouseover=\"return overlib('".$text."', BELOW, CENTER, CAPTION, '".$caption."', WIDTH, 300, OFFSETY, 20, FGCOLOR, '#ffffff', BGCOLOR, '#000000', TEXTCOLOR, '#000000', CAPCOLOR, '#ffffff', CLOSECOLOR, '#ffffff', CAPICON, '', BORDER, '2');\" onmouseout=\"return nd();\"";
}

?>