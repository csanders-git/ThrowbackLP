<?php

ob_start();
require_once('./includes/lock.php');
require_once('./includes/conf.php');
require_once('./tb-config.php');

$id = $_GET['id'];
$time = $_GET['time'];
$name = $_GET['name'];

$count = $tbdb->query("DELETE FROM tasks where `id`=? AND `opentime`=?", array($id, $time));
// We must cast to int first so that this won't be an open redirect
header("Location: index.php?action=show_history&id=" . intval($id));
exit;
//print "<META HTTP-EQUIV='Refresh' CONTENT='.1; URL=history.php?name=" . urlencode($name) . "&id=" . $id . "'>";

?>