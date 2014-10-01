<?php

define ("PLUGIN_SOLVECLOSETICKETACTION_VERSION","0.84+1.0");

// Init the hooks of adipsite
function plugin_init_solvecloseticketaction() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['solvecloseticketaction'] = true;

   $Plugin = new Plugin();
   if ($Plugin->isActivated('solvecloseticketaction')) {
      Plugin::registerClass('PluginSolvecloseticketactionConfig',
           array('addtabon' => array('Entity')));

      if (isset($_SESSION["glpiID"])) {

         // Tabs for each type
//         $PLUGIN_HOOKS['headings']['solvecloseticketaction'] = 'plugin_get_headings_solvecloseticketaction';
//         $PLUGIN_HOOKS['headings_action']['solvecloseticketaction'] = 'plugin_headings_actions_solvecloseticketaction';

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
                'author'         =>'<a href="mailto:d.durieux@siprossii.com">David DURIEUX</a>',
                'minGlpiVersion' => '0.84'
   );
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_solvecloseticketaction_check_prerequisites() {

   if (version_compare(GLPI_VERSION,'0.84','lt') || version_compare(GLPI_VERSION,'0.85','ge')) {
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
