<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/************************************************************************/
/*         Additional security & Abstraction layer conversion           */
/*                           2003 chatserv                              */
/*      http://www.nukefixes.com -- http://www.nukeresources.com        */
/************************************************************************/

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
-=[Mod]=-
      Extended Surveys Admin Interface         v3.0.0       11/15/2005
      Display Topic Icon                       v1.0.0       06/27/2005
      News BBCodes                             v1.0.0       08/19/2005
      Display Writes                           v1.0.0       10/14/2005
 ************************************************************************/

if (!defined('ADMIN_FILE')) {
   die('Access Denied');
}

global $titanium_prefix, $titanium_db, $admdata, $titanium_config;
$titanium_module_name = basename(dirname(dirname(__FILE__)));
if(is_mod_admin($titanium_module_name)) {

/*********************************************************/
/* Surveys Functions                                */
/*********************************************************/

/*****[BEGIN]******************************************
 [ Mod:     News BBCodes                       v1.0.0 ]
 ******************************************************/
    function shownews_home($text) {
       /* $news_bbtable = bbcode_table('hometext', 'postnews', 1);
        $smiles = smilies_table('onerow','hometext', 'postnews');
        echo "<br /><br />$news_bbtable"
            ."<textarea style=\"wrap: virtual\" cols=\"80\" rows=\"20\" name=\"hometext\">$text</textarea><br />$smiles<br /><br />";*/
       global $wysiwyg_buffer;
       $wysiwyg_buffer = 'hometext,bodytext';
       echo "<br /><br />\n";
       echo Make_TextArea('hometext', $text,'postnews');
       echo "<br />\n";
    }

    function shownews_body($text) {
       /* $news_bbtable = bbcode_table('bodytext', 'postnews', 1);
        $smiles = smilies_table('onerow','bodytext', 'postnews');
        echo "<br /><br />$news_bbtable"
            ."<textarea style=\"wrap: virtual\" cols=\"80\" rows=\"20\" name=\"bodytext\">$text</textarea><br />$smiles<br /><br />";*/
       echo "<br /><br />\n";
       echo Make_TextArea('bodytext', $text,'postnews');
       echo "<br />\n";
    }
/*****[END]********************************************
 [ Mod:     News BBCodes                       v1.0.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:    Display Topic Icon                  v1.0.0 ]
 ******************************************************/
    function topicicon($topic_icon) {
        echo "<br /><strong>"._DISPLAY_T_ICON."</strong>&nbsp;&nbsp;";
        if (($topic_icon == 0) OR (empty($topic_icon))) {
            $sel1 = "checked";
            $sel2 = "";
        }
        if ($topic_icon == 1) {
            $sel1 = "";
            $sel2 = "checked";
        }
        echo "<input type=\"radio\" name=\"topic_icon\" value=\"0\" $sel1>"._YES."&nbsp;"
            ."<input type=\"radio\" name=\"topic_icon\" value=\"1\" $sel2>"._NO;
    }
/*****[END]********************************************
 [ Mod:    Display Topic Icon                  v1.0.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:    Display Writes                      v1.0.0 ]
 ******************************************************/
    function writes($writes) {
        echo "<br /><strong>"._DISPLAY_WRITES."</strong>&nbsp;&nbsp;";
        if (($writes == 1) || (!is_int($writes))) {
            $sel1 = "";
            $sel2 = "checked";
        } else if (($writes == 0)) {
            $sel1 = "checked";
            $sel2 = "";
        }
        echo "<input type=\"radio\" name=\"writes\" value=\"0\" $sel1>"._YES."&nbsp;"
            ."<input type=\"radio\" name=\"writes\" value=\"1\" $sel2>"._NO;
    }
/*****[END]********************************************
 [ Mod:    Display Writes                      v1.0.0 ]
 ******************************************************/

    function poll_index() {
      global $admin_file;
        OpenTable();
        echo "<center><span class=\"option\"><strong>" . _POLLADMIN . "</strong></span><br />"
            ."<br />" . _POLLCHOOSE . "<br /><br />"
            ."[ <a href=\"".$admin_file.".php?op=Surveys\">" . _POLLMAIN . "</a> "
            ."| <a href=\"".$admin_file.".php?op=DeletePoll\">" . _DELETEPOLL . "</a> "
            ."| <a href=\"".$admin_file.".php?op=EditPoll\">" . _CHANGEPOLL . "</a> "
            ."| <a href=\"".$admin_file.".php?op=CreatePoll\">" . _ADDPOLL . "</a> "
            ."]</center><br /><br />";
        CloseTable();
    }

    function poll_options() {
      global $admin_file, $titanium_db, $titanium_prefix, $titanium_config;

      // Fetch random poll
      $make_random = intval($titanium_config['poll_random']);

      // Fetch number of days in between voting per user
      $number_of_days = intval($titanium_config['poll_days']);

      echo "<br />";
      OpenTable();
      echo "<center><span class='option'><strong>" . _POLL_OPTIONS . "</strong></span><br />"
          ."<br />" . _POLL_INFO . "<br /><br /></center>"
          ."<form action='".$admin_file.".php' method='post'>"
          ."<table border='0' style='margin: auto;'><tr><td>"
          ."" . _POLLDAYS . ":</td><td><input type='text' name='xnumber_of_days' value='$number_of_days' size='2' maxlength='3'>"
          ."</td></tr><tr><td>"
          ."" . _POLLRANDOM . ":</td><td>";
      if ($make_random) {
          echo "<input type='radio' name='xmake_random' value='1' checked>" . _YES . " &nbsp;"
              ."<input type='radio' name='xmake_random' value='0'>" . _NO . "";
      } else {
          echo "<input type='radio' name='xmake_random' value='1'>" . _YES . " &nbsp;"
              ."<input type='radio' name='xmake_random' value='0' checked>" . _NO . "";
      }
      echo "</table>"
          ."<input type='hidden' name='op' value='PollOptionsSave'><br />"
          ."<center><input type='submit' value='" . _SAVECHANGES . "'></center>"
          ."</form>";
      CloseTable();
    }

    // "borrowed" FROM Credits module and modified
    function LoadJS() {
      echo "<script type=\"text/javascript\">\n"
          ."<!--\n"
          ."function show(id) {\n"
          ."  if (id.style.display == \"\"){\n"
          ."      id.style.display = \"none\"\n"
          ."  } else {\n"
          ."      id.style.display = \"\"\n"
          ."  }\n"
          ."}\n"
          ."-->\n"
          ."</script>\n";
    }

    function puthome($ihome, $acomm) {
        echo "<br /><strong>"._PUBLISHINHOME."</strong>&nbsp;&nbsp;";
        if (($ihome == 0) OR (empty($ihome))) {
            $sel1 = "checked";
            $sel2 = "";
        }
        if ($ihome == 1) {
            $sel1 = "";
            $sel2 = "checked";
        }
        echo "<input type=\"radio\" name=\"ihome\" value=\"0\" $sel1>"._YES."&nbsp;"
            ."<input type=\"radio\" name=\"ihome\" value=\"1\" $sel2>"._NO.""
            ."&nbsp;&nbsp;<span class=\"content\">[ "._ONLYIFCATSELECTED." ]</span><br />";

        echo "<br /><strong>"._ACTIVATECOMMENTS."</strong>&nbsp;&nbsp;";
        if (($acomm == 0) OR (empty($acomm))) {
            $sel1 = "checked";
            $sel2 = "";
        }
        if ($acomm == 1) {
            $sel1 = "";
            $sel2 = "checked";
        }
        echo "<input type=\"radio\" name=\"acomm\" value=\"0\" $sel1>"._YES."&nbsp;"
            ."<input type=\"radio\" name=\"acomm\" value=\"1\" $sel2>"._NO."<br /><br />";
    }

    function SelectCategory($cat) {
        global $titanium_prefix, $titanium_db, $admin_file;
        $selcat = $titanium_db->sql_query("SELECT catid, title FROM ".$titanium_prefix."_stories_cat ORDER BY title");
        $a = 1;
        echo "<strong>"._CATEGORY."</strong> ";
        echo "<select name=\"catid\">";
        if ($cat == 0) {
            $sel = "selected";
        } else {
            $sel = "";
        }
        echo "<option name=\"catid\" value=\"0\" $sel>"._ARTICLES."</option>";
        while(list($catid, $title) = $titanium_db->sql_fetchrow($selcat)) {
            $catid = intval($catid);
            if ($catid == $cat) {
                $sel = "selected";
            } else {
                $sel = "";
            }
            echo "<option name=\"catid\" value=\"$catid\" $sel>$title</option>";
            $a++;
        }
        echo "</select> [ <a href=\"".$admin_file.".php?op=AddCategory\">"._ADD."</a> | <a href=\"".$admin_file.".php?op=EditCategory\">"._EDIT."</a> | <a href=\"".$admin_file.".php?op=DelCategory\">"._DELETE."</a> ]";
    }

    function poll_createPoll() {
        global $titanium_language, $admin, $multilingual, $titanium_prefix, $titanium_db, $admin_file;
        include_once(NUKE_BASE_DIR.'header.php');
        LoadJS();
        OpenTable();
	    echo "<div align=\"center\">\n<a href=\"$admin_file.php?op=Surveys\">" . _POLL_ADMIN_HEADER . "</a></div>\n";
        echo "<br /><br />";
	    echo "<div align=\"center\">\n[ <a href=\"$admin_file.php\">" . _POLL_RETURNMAIN . "</a> ]</div>\n";
	    CloseTable();
	    echo "<br />";
        poll_index();
        echo "<br />";
        OpenTable();
        echo "<center><span class=\"option\"><strong>" . _CREATEPOLL . "</strong></span></center>"
/*****[BEGIN]******************************************
 [ Mod:     News BBCodes                       v1.0.0 ]
 ******************************************************/
            ."<br /><form action=\"".$admin_file.".php\" method=\"post\" name=\"postnews\">"
/*****[END]********************************************
 [ Mod:     News BBCodes                       v1.0.0 ]
 ******************************************************/
        ."" . _POLLTITLE . ": <input type=\"text\" name=\"pollTitle\" size=\"50\" maxlength=\"100\"><br />";
        if ($multilingual == 1) {
            echo "<br />" . _LANGUAGE . ": "
                ."<select name=\"planguage\">";
            $titanium_languages = lang_list();
            echo '<option value=""'.(($titanium_language == '') ? ' selected="selected"' : '').'>'._ALL."</option>\n";
            for ($i=0, $j = count($titanium_languages); $i < $j; $i++) {
                if ($titanium_languages[$i] != '') {
                    echo '<option value="'.$titanium_languages[$i].'"'.(($titanium_language == $titanium_languages[$i]) ? ' selected="selected"' : '').'>'.ucfirst($titanium_languages[$i])."</option>\n";
                }
            }
            echo '</select><br /><br />';
        } else {
            echo "<input type=\"hidden\" name=\"planguage\" value=\"$titanium_language\"><br /><br />";
        }
        echo "<span class=\"content\"><i>" . _POLLEACHFIELD . "</i></span><br />"
        ."<table border=\"0\">";
        for($i = 1; $i <= 12; $i++)    {
        echo "<tr>"
            ."<td>" . _OPTION . " $i:</td><td><input type=\"text\" name=\"optionText[$i]\" size=\"50\" maxlength=\"50\"></td>"
            ."</tr>";
        }
        echo "</table>"
            ."<br /><span class=\"option\"><i>" . _ANNOUNCEPOLL . "</i><br />"
            ."<input name='ap' type='radio' value='1' onclick=\"show(announcepoll)\" />" . _YES . " &nbsp;"
            ."<input name='ap' type='radio' value='0' checked=\"checked\" onclick=\"show(announcepoll)\" />" . _NO . "</span><br /><br />";
        echo "<span id='announcepoll' style='display: none'>"
            ."<br /><br /><center><hr size=\"1\" noshade><span class=\"option\"><strong>" . _ANNOUNCEPOLL . "</strong></span><br />"
            ."</center>"
            ."<br /><strong>" . _TITLE . ":</strong><br />"
            ."<input type=\"text\" name=\"title\" size=\"40\"><br /><br />";
        $cat = 0;
        $ihome = 0;
        $acomm = 0;
        $writes = 0;
        $topic_icon = 1;
        SelectCategory($cat);
/*****[BEGIN]******************************************
 [ Mod:    Display Topic Icon                  v1.0.0 ]
 [ Mod:    Display Writes                      v1.0.0 ]
 ******************************************************/
        echo '<br />';
        topicicon($topic_icon);
        echo '<br />';
        writes($writes);
/*****[END]********************************************
 [ Mod:    Display Topic Icon                  v1.0.0 ]
 [ Mod:    Display Writes                      v1.0.0 ]
 ******************************************************/
        echo "<br />";
        puthome($ihome, $acomm);
        echo "<strong>" . _TOPIC . "</strong> <select name=\"topic\">";
        $toplist = $titanium_db->sql_query("SELECT topicid, topictext FROM " . $titanium_prefix . "_topics ORDER BY topictext");
        echo "<option value=\"\">" . _SELECTTOPIC . "</option>\n";
        while ($row = $titanium_db->sql_fetchrow($toplist)) {
            $topicid = intval($row['topicid']);
            $phpbb2_topics = $row['topictext'];
            echo "<option value=\"$topicid\">$phpbb2_topics</option>\n";
        }
        echo "</select>";
/*****[BEGIN]******************************************
 [ Mod:     News BBCodes                       v1.0.0 ]
 ******************************************************/
        echo "<br /><br /><strong>" . _STORYTEXT . "</strong><br />";
            //."<textarea style=\"wrap:virtual\" cols=\"50\" rows=\"7\" name=\"hometext\">$story</textarea><br /><br />"
        shownews_home($hometext);
        echo "<strong>" . _EXTENDEDTEXT . "</strong><br />";
            //."<textarea style=\"wrap:virtual\" cols=\"50\" rows=\"8\" name=\"bodytext\"></textarea><br />"
        shownews_body($bodytext);
/*****[END]********************************************
 [ Mod:     News BBCodes                       v1.0.0 ]
 ******************************************************/
        echo "<br /><br /></span>"
            ."<input type=\"hidden\" name=\"op\" value=\"CreatePosted\" />"
            ."<input type=\"submit\" value=\"" . _CREATEPOLLBUT . "\" />"
            ."</form>";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    }

/*****[BEGIN]******************************************
 [ Mod:    Display Topic Icon                  v1.0.0 ]
 [ Mod:    Display Writes                      v1.0.0 ]
 ******************************************************/
    function poll_createPosted($pollTitle, $optionText, $planguage, $title, $hometext, $topic, $bodytext, $catid, $ihome, $acomm, $topic_icon, $writes) {
/*****[END]********************************************
 [ Mod:    Display Topic Icon                  v1.0.0 ]
 [ Mod:    Display Writes                      v1.0.0 ]
 ******************************************************/
        global $titanium_prefix, $titanium_db, $aid, $admin_file;
        $SurveyStory = intval($SurveyStory);
        $timeStamp = time();
        $pollTitle = Fix_Quotes($pollTitle);
        if(!$titanium_db->sql_query("INSERT INTO ".$titanium_prefix."_poll_desc VALUES (NULL, '$pollTitle', '$timeStamp', '0', '$planguage', '0')")) {
            return;
        }
        $object = $titanium_db->sql_fetchrow($titanium_db->sql_query("SELECT pollID FROM ".$titanium_prefix."_poll_desc WHERE pollTitle='$pollTitle'"));
        $id = $object['pollID'];
        $id = intval($id);
        for($i = 1, $maxi = count($optionText); $i <= $maxi; $i++) {
            if(!empty($optionText[$i])) {
                $optionText[$i] = Fix_Quotes($optionText[$i]);
            }
            if(!$titanium_db->sql_query("INSERT INTO ".$titanium_prefix."_poll_data (pollID, optionText, optionCount, voteID) VALUES ('$id', '$optionText[$i]', '0', '$i')")) {
                return;
            }
        }
        if (!empty($title) && !empty($hometext)) {
            $title = Fix_Quotes($title);
            $hometext = Fix_Quotes($hometext);
            $bodytext = Fix_Quotes($bodytext);
/*****[BEGIN]******************************************
 [ Mod:    Display Topic Icon                  v1.0.0 ]
 [ Mod:    Display Writes                      v1.0.0 ]
 ******************************************************/
            $topic_icon = intval($topic_icon);
            $writes = intval($writes);
            $result = $titanium_db->sql_query("INSERT INTO ".$titanium_prefix."_stories VALUES (NULL, 
			                                                              '$catid', 
																		    '$aid', 
																		  '$title', 
																		     NULL,
																			 NULL, 
																	   '$hometext', 
																	   '$bodytext', 
																	           '0', 
																			   '0', 
																		  '$topic', 
																		    '$aid', 
																			    '', 
																		  '$ihome', 
																	  '$planguage', 
																	      '$acomm', 
																		       '0', 
																			   '0', 
																			   '0', 
																			   '0', 
																			    '', 
																	 '$topic_icon', 
																	     '$writes')");
/*****[END]********************************************
 [ Mod:    Display Topic Icon                  v1.0.0 ]
 [ Mod:    Display Writes                      v1.0.0 ]
 ******************************************************/
        }
        redirect_titanium($admin_file.".php?op=Surveys");
    }

    function poll_removePoll() {
        global $titanium_prefix, $titanium_db, $admin_file, $multilingual;

        include_once(NUKE_BASE_DIR.'header.php');
        OpenTable();
	    echo "<div align=\"center\">\n<a href=\"$admin_file.php?op=Surveys\">" . _POLL_ADMIN_HEADER . "</a></div>\n";
        echo "<br /><br />";
	    echo "<div align=\"center\">\n[ <a href=\"$admin_file.php\">" . _POLL_RETURNMAIN . "</a> ]</div>\n";
	    CloseTable();
	    echo "<br />";
        poll_index();
        echo "<br />";
        OpenTable();
        echo "<center><span class=\"option\"><strong>" . _REMOVEEXISTING . "</strong></span><br /><br />"
        ."" . _POLLDELWARNING . "</center><br /><br />"
        ."<i>" . _CHOOSEPOLL . "</i><br /><br />"
        ."<form action=\"".$admin_file.".php\" method=\"post\">"
        ."<input type=\"hidden\" name=\"op\" value=\"RemovePosted\">";
        $result = $titanium_db->sql_query("SELECT pollID, pollTitle, timeStamp, planguage FROM ".$titanium_prefix."_poll_desc ORDER BY timeStamp");
        if(!$result) {
            return;
        }
        /* cycle through the descriptions until everyone has been fetched */
        echo "<select name=\"id\">";
        while($object = $titanium_db->sql_fetchrow($result)) {
        $object['pollID'] = intval($object['pollID']);
            echo "<option value=\"".$object['pollID']."\">".$object['pollTitle'];
            if($multilingual == 1 && !empty($object['planguage'])) echo " - (".$object['planguage'].")";
            echo "</option>";
        }
        echo "</select>&nbsp;";
        echo "<input type=\"submit\" value=\"" . _DELETE . "\" />";
        echo "</form>";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    }

    function poll_removePosted() {
        global $id, $titanium_prefix, $titanium_db, $admin_file;

        $id = intval($id);
        $titanium_db->sql_query("DELETE FROM ".$titanium_prefix."_poll_desc WHERE pollID='$id'");
        $titanium_db->sql_query("DELETE FROM ".$titanium_prefix."_poll_data WHERE pollID='$id'");
        redirect_titanium($admin_file.".php?op=Surveys");
    }

    function polledit_select() {
        global $titanium_prefix, $titanium_db, $admin_file, $multilingual;
        include_once(NUKE_BASE_DIR.'header.php');
        OpenTable();
	    echo "<div align=\"center\">\n<a href=\"$admin_file.php?op=Surveys\">" . _POLL_ADMIN_HEADER . "</a></div>\n";
        echo "<br /><br />";
	    echo "<div align=\"center\">\n[ <a href=\"$admin_file.php\">" . _POLL_RETURNMAIN . "</a> ]</div>\n";
	    CloseTable();
	    echo "<br />";
        poll_index();
        echo "<br />";
        OpenTable();
        echo "<div style='margin: auto;'><span class=\"option\"><strong>" . _EDITPOLL . "</strong></span><br /><br />"
        ."" . _CHOOSEPOLLEDIT . "<br />"
        ."<form action=\"".$admin_file.".php\" method=\"post\">"
        ."<input type=\"hidden\" name=\"op\" value=\"PollEdit\">";
        $result = $titanium_db->sql_query("SELECT pollID, pollTitle, timeStamp, planguage FROM ".$titanium_prefix."_poll_desc ORDER BY timeStamp");
        if(!$result) {
            return;
        }
        /* cycle through the descriptions until everyone has been fetched */
        echo "<select name=\"pollID\">";
        while($object = $titanium_db->sql_fetchrow($result)) {
        $object['pollID'] = intval($object['pollID']);
            echo "<option value=\"".$object['pollID']."\">".$object['pollTitle'];
            if($multilingual == 1) echo " - (".$object['planguage'].")";
            echo "</option>";
        }
        echo "</select>&nbsp;";
        echo "<input type=\"submit\" value=\"" . _EDIT . "\" />";
        echo "</form>";
        echo '</div>';
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    }

    function polledit($pollID) {
        global $titanium_prefix, $titanium_db, $multilingual, $admin_file;

        include_once(NUKE_BASE_DIR.'header.php');
        OpenTable();
	    echo "<div align=\"center\">\n<a href=\"$admin_file.php?op=Surveys\">" . _POLL_ADMIN_HEADER . "</a></div>\n";
        echo "<br /><br />";
	    echo "<div align=\"center\">\n[ <a href=\"$admin_file.php\">" . _POLL_RETURNMAIN . "</a> ]</div>\n";
	    CloseTable();
	    echo "<br />";
        poll_index();
        $pollID = intval($pollID);
        $row = $titanium_db->sql_fetchrow($titanium_db->sql_query("SELECT pollTitle, planguage FROM ".$titanium_prefix."_poll_desc WHERE pollID='$pollID'"));
        $pollTitle = $row['pollTitle'];
        $planguage = $row['planguage'];
        echo "<br />";
        OpenTable();
        echo "<center><strong>"._POLLEDIT." $pollTitle</strong></center>";
        echo "<form action=\"".$admin_file.".php\" method=\"post\">";
        echo "<table border=\"0\" align=\"center\"><tr><td align=\"right\">";
        echo "<strong>" . _TITLE . ":</strong></td><td colspan=\"2\"><input type=\"text\" name=\"pollTitle\" value=\"$pollTitle\" size=\"40\" maxlength=\"100\"></td></tr>";
        if ($multilingual == 1) {
            echo "<tr><td><strong>" . _LANGUAGE . ":</strong></td><td>"
                ."<select name=\"planguage\">";
            $titanium_languages = lang_list();
            echo '<option value=""'.(($planguage == '') ? ' selected="selected"' : '').'>'._ALL."</option>\n";
            for ($i=0, $j = count($titanium_languages); $i < $j; $i++) {
                if ($titanium_languages[$i] != '') {
                    echo '<option value="'.$titanium_languages[$i].'"'.(($planguage == $titanium_languages[$i]) ? ' selected="selected"' : '').'>'.ucfirst($titanium_languages[$i])."</option>\n";
                }
            }
            echo '</select><br /><br />';
            echo "</td></tr>";
        } else {
            echo "<input type=\"hidden\" name=\"planguage\" value=\"$planguage\"><br /><br />";
        }
        $result2 = $titanium_db->sql_query("SELECT optionText, optionCount, voteID FROM ".$titanium_prefix."_poll_data WHERE pollID='$pollID' ORDER BY voteID");
        while ($row2 = $titanium_db->sql_fetchrow($result2)) {
            $optionText = $row2['optionText'];
            $optionCount = intval($row2['optionCount']);
            $voteID = intval($row2['voteID']);
            echo "<tr><td align=\"right\"><strong>" . _OPTION . " $voteID:</strong></td><td><input type=\"text\" name=\"optiontext$voteID\" value=\"$optionText\" size=\"40\" maxlength=\"50\"></td><td align=\"right\">$optionCount "._VOTES."</td></tr>";
        }
        $titanium_db->sql_freeresult($result2);
        echo "</table><center><input type=\"hidden\" name=\"pollID\" value=\"$pollID\"><input type=\"hidden\" name=\"op\" value=\"SavePoll\">"
        ."<strong>" . _CLEARVOTES . "</strong>&nbsp;<input type='radio' name='ClearVotes' value='1' />" . _YES . " &nbsp;"
        ."<input type='radio' name='ClearVotes' value='0' checked=\"checked\" />" . _NO . "<br />"
        ."<br /><input type=\"submit\" value=\"" . _SAVECHANGES . "\"><br /><br />" . _GOBACK . "</center><br /><br /></form>";
        CloseTable();
        include_once(NUKE_BASE_DIR.'footer.php');
    }

    function savepoll($pollID, $pollTitle, $planguage, $optiontext1, $optiontext2, $optiontext3, $optiontext4, $optiontext5, $optiontext6, $optiontext7, $optiontext8, $optiontext9, $optiontext10, $optiontext11, $optiontext12, $ClearVotes) {
        global $titanium_prefix, $titanium_db, $admin_file;
        $ClearVotes = intval($ClearVotes);
        $pollID = intval($pollID);
        $titanium_db->sql_query("UPDATE ".$titanium_prefix."_poll_desc SET pollTitle='$pollTitle', planguage='$planguage' WHERE pollID='$pollID'");
        for($i=1;$i<13;$i++) {
            $var = "optiontext$i";
            $titanium_db->sql_query("UPDATE ".$titanium_prefix."_poll_data SET optionText='".$$var."' WHERE voteID='$i' AND pollID='$pollID'");
            if($ClearVotes) {
                $titanium_db->sql_query("UPDATE ".$titanium_prefix."_poll_data SET optionCount='0' WHERE voteID='$i' AND pollID='$pollID'");
            }
        }
        redirect_titanium($admin_file.".php?op=Surveys");
    }

    switch($op) {

        case "Surveys";
            include_once(NUKE_BASE_DIR.'header.php');
            OpenTable();
	        echo "<div align=\"center\">\n<a href=\"$admin_file.php?op=Surveys\">" . _POLL_ADMIN_HEADER . "</a></div>\n";
            echo "<br /><br />";
	        echo "<div align=\"center\">\n[ <a href=\"$admin_file.php\">" . _POLL_RETURNMAIN . "</a> ]</div>\n";
	        CloseTable();
	        echo "<br />";
            poll_index();
            poll_options();
            include_once(NUKE_BASE_DIR.'footer.php');
        break;

        case "CreatePoll":
            poll_createPoll();
        break;

        case "CreatePosted":
/*****[BEGIN]******************************************
 [ Mod:    Display Topic Icon                  v1.0.0 ]
 [ Mod:    Display Writes                      v1.0.0 ]
 ******************************************************/
        poll_createPosted($pollTitle, $optionText, $planguage, $title, $hometext, $topic, $bodytext, $catid, $ihome, $acomm, $topic_icon, $writes);
/*****[END]********************************************
 [ Mod:    Display Topic Icon                  v1.0.0 ]
 [ Mod:    Display Writes                      v1.0.0 ]
 ******************************************************/
        break;

        case "ChangePoll":
            ChangePoll($pollID, $pollTitle, $optionText, $voteID);
        break;

        case "DeletePoll":
            poll_removePoll();
        break;

        case "RemovePosted":
            poll_removePosted();
        break;

        case "PollEdit":
            polledit($pollID);
        break;

        case "SavePoll":
            savepoll($pollID, $pollTitle, $planguage, $optiontext1, $optiontext2, $optiontext3, $optiontext4, $optiontext5, $optiontext6, $optiontext7, $optiontext8, $optiontext9, $optiontext10, $optiontext11, $optiontext12, $ClearVotes);
        break;

        case "EditPoll":
            polledit_select();
        break;

        case "PollOptionsSave":
            $xmake_random = intval($xmake_random);
            $xnumber_of_days = intval($xnumber_of_days);
            $titanium_db->sql_query("UPDATE ".$titanium_prefix."_evolution SET evo_value='".$xmake_random."' WHERE evo_field='poll_random'");
            $titanium_db->sql_query("UPDATE ".$titanium_prefix."_evolution SET evo_value='".$xnumber_of_days."' WHERE evo_field='poll_days'");
/*****[BEGIN]******************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
            global $cache;
            $cache->delete('titanium_config');
/*****[END]********************************************
 [ Base:    Caching System                     v3.0.0 ]
 ******************************************************/
            redirect_titanium($admin_file.".php?op=Surveys");
        break;

    }

} else {
    DisplayError("<strong>"._ERROR."</strong><br /><br />You do not have administration permission for module \"$titanium_module_name\"");
}

?>