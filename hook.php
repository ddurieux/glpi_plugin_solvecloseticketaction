<?php

/*
   ------------------------------------------------------------------------
   Plugin Solvecloseticketaction for GLPI
   Copyright (C) 2014-2014 by the Plugin Solvecloseticketaction for David Durieux.

   https://
   ------------------------------------------------------------------------

   LICENSE

   This file is part of Plugin Solvecloseticketaction project.

   Plugin Solvecloseticketaction for GLPI is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   Plugin Solvecloseticketaction for GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Solvecloseticketaction. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Plugin Solvecloseticketaction for GLPI
   @author    David Durieux
   @co-author
   @comment
   @copyright Copyright (c) 2014-2014 Plugin Solvecloseticketaction for David Durieux
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://
   @since     2014

   ------------------------------------------------------------------------
 */

function plugin_solvecloseticketaction_install() {
   global $DB;

   $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_solvecloseticketaction_configs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `createfollowupwithsolve` varchar(255) DEFAULT NULL,
   `assigntechsolveticket` varchar(255) DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
   $DB->query($query);

   $query = "INSERT INTO `glpi_plugin_solvecloseticketaction_configs`
      (`id`, `entities_id`, `createfollowupwithsolve`, `assigntechsolveticket`)
      VALUES (1, 0, '0', '0');";
   $DB->query($query);

   return true;
}

// Uninstall process for plugin : need to return true if succeeded
function plugin_solvecloseticketaction_uninstall() {
   global $DB;

   $query = "DROP TABLE `glpi_plugin_solvecloseticketaction_configs`";
   $DB->query($query);

   return true;
}



// Define headings added by the plugin //
function plugin_get_headings_solvecloseticketaction($item,$withtemplate) {
   global $LANG;

   switch (get_class($item)) {

      case 'Entity':
         $array = array();
         if ($item->getID() > 0) {
            if (Session::haveRight("entity", 'r')) {
               $array[0] = "Action de ticket (résolution)";
            }
         }
         return $array;
         break;

   }

   return false;
}

// Define headings actions added by the plugin
function plugin_headings_actions_solvecloseticketaction($item) {

   switch (get_class($item)) {

      case 'Entity':
         $array = array();
         $array[0] = "plugin_headings_solvecloseticketaction_config";
         return $array;
         break;

   }
   return false;
}



function plugin_headings_solvecloseticketaction_config($item) {
   $prConfig = new PluginSolvecloseticketactionConfig();
   $prConfig->showForm($item->getID());
}

?>