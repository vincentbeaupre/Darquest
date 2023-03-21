<?php
require_once("bd.php");

if(Database::connect()) {
  echo "fuck yeah";
}
else{
  echo "fuck...";
}