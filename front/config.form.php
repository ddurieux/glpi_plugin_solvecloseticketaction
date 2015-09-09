<?php

/*
   ------------------------------------------------------------------------
   Plugin Solvecloseticketaction for GLPI
   Copyright (C) 2014-2015 by the Plugin Solvecloseticketaction for David Durieux.

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
   @copyright Copyright (c) 2014-2015 Plugin Solvecloseticketaction for David Durieux
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://
   @since     2014

   ------------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

Session::checkRight("entity","w");

Html::header("solvecloseticketaction",$_SERVER["PHP_SELF"], "plugins", "solvecloseticketaction", "entity");
$psConfig = new PluginSolvecloseticketactionConfig();
if (        isset($_POST['createfollowupwithsolve'])
        AND $_POST['createfollowupwithsolve'] == 'NULL'
        AND isset($_POST['assigntechsolveticket'])
        AND $_POST['assigntechsolveticket'] == 'NULL'
        AND isset($_POST['deletetechsonsolve'])
        AND $_POST['deletetechsonsolve'] == 'NULL'
        AND isset($_POST['assigntechsolveticketempty'])
        AND $_POST['assigntechsolveticketempty'] == 'NULL'
        ) {
   Html::back();
}
if (isset ($_POST["add"])) {
   $psConfig->add($_POST);
   Html::back();
} else if (isset ($_POST["update"])) {
   $psConfig->update($_POST);
   Html::back();
} else if (isset ($_POST["delete"])) {
   $psConfig->delete($_POST);
   Html::back();
}

Html::footer();

?>