<?php
require 'includes/init.php';if(!empty($_SESSION['user_id']))go(home());$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verifyCsrf();$email=trim($_POST['email']??'');$pw=$_POST['password']??'';
 if(!filter_var($email,FILTER_VALIDATE_EMAIL)||$pw==='')$error='Enter a valid email and password.';
 else{
  $stmt=$pdo->prepare('SELECT id,full_name,password,role FROM users WHERE email=?');$stmt->execute([$email]);$user=$stmt->fetch();
  if(!$user||!password_verify($pw,$user['password']))$error='Incorrect email or password.';
  else{session_regenerate_id(true);$_SESSION['user_id']=$user['id'];$_SESSION['name']=$user['full_name'];$_SESSION['role']=$user['role'];flash('Welcome back, '.$user['full_name'].'!');go(home());}
 }
}
$pageTitle='Login';require 'includes/header.php'; ?>
<div class="panel auth-panel login-panel"><h2 class="text-center text-primary mb-4"><i class="bi bi-box-arrow-in-right"></i> Login</h2>
<?php if($error):?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="validate" novalidate><input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
<div class="mb-3"><label class="form-label">Email Address</label><input type="email" name="email" class="form-control" required value="<?= e($_POST['email']??'') ?>"></div>
<div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
<button class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right me-1"></i> Log In</button><p class="text-center small mt-3">Don't have an account? <a href="register.php">Register here</a></p></form></div>
<?php require 'includes/footer.php'; ?>
