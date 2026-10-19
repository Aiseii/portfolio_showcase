<?php require 'includes/init.php'; go(empty($_SESSION['user_id']) ? BASE_URL.'/login.php' : home());
