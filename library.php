<?php
function h($value) {
  return htmlspecialchars($value, ENT_QUOTES);
}

function dbconnect() {
  $db = new mysqli('localhost:8889', 'root', 'root', 'php_chat');
  if (!$db) {
    die($db->error);
  }
  return $db;
}