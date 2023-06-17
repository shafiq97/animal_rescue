<?php
session_start();
session_unset();
session_destroy();
header('Location: /animal_rescue');
exit;
?>
