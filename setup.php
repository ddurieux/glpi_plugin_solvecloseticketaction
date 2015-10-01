<?php

/*
   ------------------------------------------------------------------------
   Plugin Solvecloseticketaction for GLPI
   Copyright (C) 2014-2015 by the Plugin Solvecloseticketaction for David Durieux.

   https://github.com/ddurieux/glpi_plugin_solvecloseticketaction
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
   @copyright Copyright (c) 2014-2015 Plugin Solvecloseticketaction for David Durieux
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://github.com/ddurieux/glpi_plugin_solvecloseticketaction
   @since     2014

   ------------------------------------------------------------------------
 */

define ("PLUGIN_SOLVECLOSETICKETACTION_VERSION","0.85+1.0");

// Init the hooks
function plugin_init_solvecloseticketaction() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['solvecloseticketaction'] = true;

   $Plugin = new Plugin();
   if ($Plugin->isActivated('solvecloseticketaction')) {
      Plugin::registerClass('PluginSolvecloseticketactionConfig',
           array('addtabon' => array('Entity')));

      if (isset($_SESSION["glpiID"])) {

         $PLUGIN_HOOKS['pre_item_update']['solvecloseticketaction'] = array(
            'Ticket'           => array('PluginSolvecloseticketactionConfig', 'updateTicket')
         );
      }
   }
   return $PLUGIN_HOOKS;
}

// Name and Version of the plugin
function plugin_version_solvecloseticketaction() {
   return array('name'           => 'solvecloseticketaction',
                'shortname'      => 'solvecloseticketaction',
                'version'        => PLUGIN_SOLVECLOSETICKETACTION_VERSION,
                'license'        => 'AGPLv3+',
                'author'         =>'<a href="mailto:d.durieux@siprossii.com">David DURIEUX</a>',
                'homepage'       =>'https://github.com/ddurieux/glpi_plugin_solvecloseticketaction',
                'minGlpiVersion' => '0.85'
   );
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_solvecloseticketaction_check_prerequisites() {

   if (version_compare(GLPI_VERSION,'0.85','lt') || version_compare(GLPI_VERSION,'0.91','ge')) {
      echo "error";
   } else {
      return true;
   }
}

function plugin_solvecloseticketaction_check_config() {
   return true;
}

function plugin_solvecloseticketaction_haveTypeRight($type,$right) {
   return true;
}

?>
