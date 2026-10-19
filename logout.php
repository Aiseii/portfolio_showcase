<?php
require 'includes/init.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit('Method not allowed');}
verifyCsrf();$_SESSION=[];session_regenerate_id(true);session_destroy();go(BASE_URL.'/login.php');
