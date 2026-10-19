<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    session_start();
}
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function go($url) { header('Location: ' . $url); exit; }
function requireLogin($role = null) {
    if (empty($_SESSION['user_id'])) go(BASE_URL . '/login.php');
    if ($role !== null && ($_SESSION['role'] ?? '') !== $role) {
        http_response_code(403); exit('Access denied.');
    }
}
function csrf() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verifyCsrf() {
    if (!hash_equals(csrf(), (string)($_POST['csrf'] ?? ''))) {
        http_response_code(419); exit('Invalid form token. Please reload and try again.');
    }
}
function flash($message, $type='success') { $_SESSION['notice']=['text'=>$message,'type'=>$type]; }
function home() { return BASE_URL . (($_SESSION['role'] ?? '') === 'admin' ? '/admin/dashboard.php' : '/student/dashboard.php'); }
function uploadDocument($input) {
    if (!isset($_FILES[$input]) || $_FILES[$input]['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Please select a valid file.');
    $file = $_FILES[$input];
    if ($file['size'] < 1 || $file['size'] > 5*1024*1024) throw new RuntimeException('File must be no larger than 5MB.');
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $allowed = [
       'pdf'=>['application/pdf'],
       'docx'=>['application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/zip'],
       'txt'=>['text/plain'],
       'doc'=>['application/msword','application/x-ole-storage']
    ];
    if (!isset($allowed[$ext]) || !in_array($mime,$allowed[$ext],true)) throw new RuntimeException('Allowed files: PDF, DOC, DOCX or TXT only.');
    if ($ext === 'pdf' && file_get_contents($file['tmp_name'],false,null,0,5) !== '%PDF-') throw new RuntimeException('Invalid PDF file.');
    if ($ext === 'docx' && !class_exists('ZipArchive')) throw new RuntimeException('Enable the PHP ZIP extension to upload DOCX.');
    if ($ext === 'docx') {
       $zip=new ZipArchive();
       if ($zip->open($file['tmp_name'])!==true) throw new RuntimeException('Invalid DOCX file.');
       $valid=$zip->locateName('[Content_Types].xml')!==false && $zip->locateName('word/document.xml')!==false;
       $zip->close();
       if (!$valid) throw new RuntimeException('Invalid DOCX document.');
    }
    $filename = bin2hex(random_bytes(16)).'.'.$ext;
    if (!move_uploaded_file($file['tmp_name'], __DIR__.'/../uploads/'.$filename)) throw new RuntimeException('Unable to store file.');
    return [$filename, mb_substr(basename($file['name']),0,200)];
}
