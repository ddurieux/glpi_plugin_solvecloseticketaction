<?php

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

Session::checkRight("entity","w");

Html::header("solvecloseticketaction",$_SERVER["PHP_SELF"], "plugins", "solvecloseticketaction", "entity");

$psConfig = new PluginSolvecloseticketactionConfig();
if (        isset($_POST['createfollowupwithsolve'])
        AND $_POST['createfollowupwithsolve'] == 'NULL'
        AND isset($_POST['assigntechsolveticket'])
        AND $_POST['assigntechsolveticket'] == 'NULL'
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