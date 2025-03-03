<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/

/*********************************************
  CPG-NUKE: Advanced Content Management System
  ********************************************
  A highly modified version of PHP-Nuke 6.5
  which is Copyright (c) 2002 by Francisco Burzi
  http://phpnuke.org

  Under the GNU General Public License version 2
  
  Website Document Mod v1.0
  Copyright (c) 2002 by Shawn Archer
  http://www.nukestyles.com
  
  CPG-Nuke Port
  Copyright (c) 2004 by Trevor E
  from http://www.cpgnuke.com

***********************************************/

define('CP_INCLUDE_DIR', dirname(dirname(dirname(__FILE__))));

require_once(CP_INCLUDE_DIR.'/includes/showcp.php');

# To have the Copyright window work in your module just fill the following
# required information and then copy the file "copyright.php" into your
# module's directory. It's all, as easy as it sounds ;)
# NOTE: in $download_location PLEASE give the direct download link to the file!!!

$author_name = "Shawn Archer";
$author_email = "Shawn@NukeStyles.com";
$author_homepage = "http://www.NukeStyles.com";
$license = "GNU/GPL";
$download_location = "http://www.NukeStyles.com/modules.php?name=Downloads";
$titanium_module_version = "1.0";
$titanium_module_description = "Module to have your Website documents in one place. You have a custom About Us page, a site Privacy Statement, and a site Disclaimer statement. The only configuration is typing in what you want the About Us statement to say.<br />";

# DO NOT TOUCH THE FOLLOWING COPYRIGHT CODE. YOU'RE JUST ALLOWED TO CHANGE YOUR "OWN"
# MODULE'S DATA (SEE ABOVE) SO THE SYSTEM CAN BE ABLE TO SHOW THE COPYRIGHT NOTICE
# FOR YOUR MODULE/ADDON. PLAY FAIR WITH THE PEOPLE THAT WORKED CODING WHAT YOU USE!!
# YOU ARE NOT ALLOWED TO MODIFY ANYTHING ELSE THAN THE ABOVE REQUIRED INFORMATION.
# AND YOU ARE NOT ALLOWED TO DELETE THIS FILE NOR TO CHANGE ANYTHING FROM THIS FILE IF
# YOU'RE NOT THIS MODULE'S AUTHOR.

show_copyright($author_name, $author_email, $author_homepage, $license, $download_location, $titanium_module_version, $titanium_module_description);
?>
