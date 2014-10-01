<?php

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginSolvecloseticketactionConfig extends CommonDBTM {

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return "configuration";
   }



   static function canCreate() {
      return true;
   }



   static function canView() {
      return true;
   }



   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      $array_ret = array();
      if ($item->getID() > -1) {
         $array_ret[0] = self::createTabEntry('solvecloseticketaction');
      }
      return $array_ret;
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getID() > -1) {
         $prConfig = new PluginSolvecloseticketactionConfig();
         $prConfig->showForm($item->getID());
      }
      return true;
   }



   /**
   * Display form for service configuration
   *
   * @param $items_id integer ID
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($entities_id, $options=array(), $copy=array()) {
      global $DB,$CFG_GLPI;

      $a_configs = $this->find("`entities_id`='".$entities_id."'", "", 1);
      if (count($a_configs) == '1') {
         $a_config = current($a_configs);
         $this->getFromDB($a_config['id']);
      } else {
         $this->getEmpty();
      }

      $this->showFormHeader($options);

      echo "<tr>";
      echo "<td>";
      echo "<input type='hidden' name='entities_id' value='".$entities_id."' />";
      echo "Créer un suivi avec la solution&nbsp;:";
      echo "</td>";
      echo "<td>";
      $elements = array();
      if ($entities_id == '0') {
         $elements = array("+0" => __('No'),
                           "+1" => __('Yes')
                           );
      } else {
         $elements = array("NULL" => __('Inheritance of the parent entity'),
                           "+0" => __('No'),
                           "+1" => __('Yes')
                           );
      }
      $value = (is_null($this->fields['createfollowupwithsolve']) ? "NULL" : "+".$this->fields['createfollowupwithsolve']);
      $value = str_replace("++", "+", $value);
      Dropdown::showFromArray("createfollowupwithsolve", $elements, array('value' => $value));
      echo "</td>";
      echo "<td>Assigner le ticket au technicien qui résoud le ticket&nbsp;:</td>";
      echo "<td>";
      $elements = array();
      if ($entities_id == '0') {
         $elements = array("+0" => __('No'),
                           "+1" => __('Yes')
                           );
      } else {
         $elements = array("NULL" => __('Inheritance of the parent entity'),
                           "+0" => __('No'),
                           "+1" => __('Yes')
                           );
      }
      $value = (is_null($this->fields['assigntechsolveticket']) ? "NULL" : "+".$this->fields['assigntechsolveticket']);
      $value = str_replace("++", "+", $value);
      Dropdown::showFromArray("assigntechsolveticket", $elements, array('value' => $value));
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      return true;
   }



/**
    * Get value of config
    *
    * @global object $DB
    * @param value $name field name
    * @param integer $entities_id
    *
    * @return value of field
    */
   function getValueAncestor($name, $entities_id) {
      global $DB;

      $entities_ancestors = getAncestorsOf("glpi_entities", $entities_id);

      $nbentities = count($entities_ancestors);
      for ($i=0; $i<$nbentities; $i++) {
         $entity = array_pop($entities_ancestors);
         $query = "SELECT * FROM `".$this->getTable()."`
            WHERE `entities_id`='".$entity."'
               AND `".$name."` IS NOT NULL
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) != '0') {
            $data = $DB->fetch_assoc($result);
            return $data[$name];
         }
      }
      return $config->getField($name);
   }



   /**
    * Get the value (of this entity or parent entity or in general config
    *
    * @global object $DB
    * @param value $name field name
    * @param integet $entities_id
    *
    * @return value value of this field
    */
   function getValue($name, $entities_id) {
      global $DB;

      $query = "SELECT `".$name."` FROM `".$this->getTable()."`
         WHERE `entities_id`='".$entities_id."'
            AND `".$name."` IS NOT NULL
         LIMIT 1";
      $result = $DB->query($query);
      if ($DB->numrows($result) > 0) {
         $data = $DB->fetch_assoc($result);
         return $data[$name];
      }
      return $this->getValueAncestor($name, $entities_id);
   }



   static function updateTicket($item) {

      $psConfig = new PluginSolvecloseticketactionConfig();

      if (isset($item->input['solution'])
              OR (isset($item->input['status'])
                  AND $item->input['status'] == 'closed')) {
         $entities_id = $item->fields['entities_id'];
         if (isset($item->input['entities_id'])) {
            $entities_id = $item->input['entities_id'];
         }
         $createfollow = $psConfig->getValue("createfollowupwithsolve", $entities_id);
         $assigntech = $psConfig->getValue("assigntechsolveticket", $entities_id);
         if ($createfollow == '1'
                 && isset($item->input['solution'])) {
            $input = array();
            $input['tickets_id'] = $item->input['id'];
            $input['date'] = date('Y-m-d H:i:s');
            $input['users_id'] = $_SESSION['glpiID'];
            $input['content'] = "Solution : ".Html::clean(Html::entity_decode_deep($item->input['solution']));
            $ticketFollowup = new TicketFollowup();
            $input['_no_notif'] = True;
            $ticketFollowup->add($input);
         }
         if ($assigntech == '1') {
            $ticket_User = new Ticket_User();
            $a_users = $ticket_User->find("`tickets_id`='".$item->input['id']."'
                                           AND `type`='2'");
            $create = 1;
            foreach ($a_users as $datau) {
               if ($datau['users_id'] == $_SESSION['glpiID']) {
                  $create = 0;
               } else {
                  $ticket_User->delete($datau);
               }
            }
            if ($create == '1') {
               $input = array();
               $input['tickets_id'] = $item->input['id'];
               $input['users_id'] = $_SESSION['glpiID'];
               $input['type'] = '2';
               $ticket_User->add($input);
            }
         }
      }
   }

}

?>
