<?php
require 'includes/init.php';requireLogin();
$type=$_GET['type']??'';$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if(!$id||!in_array($type,['project','submission'],true)){http_response_code(400);exit('Invalid request.');}
if($type==='project'){$stmt=$pdo->prepare('SELECT file_path,user_id,title AS original_name FROM projects WHERE id=?');}
else{$stmt=$pdo->prepare('SELECT file_path,user_id,original_name FROM submissions WHERE id=?');}
$stmt->execute([$id]);$item=$stmt->fetch();
if(!$item||($_SESSION['role']!=='admin' && $type==='submission' && (int)$item['user_id']!==(int)$_SESSION['user_id'])){http_response_code(404);exit('File not found.');}
// Public showcase reports can be downloaded by logged-in students; assignment files remain private.
$path=__DIR__.'/uploads/'.basename($item['file_path']);
if(!is_file($path)){http_response_code(404);exit('File missing from server.');}
$ext=pathinfo($item['file_path'],PATHINFO_EXTENSION);$name=preg_replace('/[^a-zA-Z0-9_.-]/','_',pathinfo($item['original_name'],PATHINFO_FILENAME));
header('Content-Type: application/octet-stream');header('Content-Length: '.filesize($path));
header('X-Content-Type-Options: nosniff');header('Content-Disposition: attachment; filename="'.($name?:'document').'.'.$ext.'"');
readfile($path);exit;
