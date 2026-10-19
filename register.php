<?php
require 'includes/init.php';
if (!empty($_SESSION['user_id'])) go(home());
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 verifyCsrf();
 $name=trim($_POST['full_name']??''); $email=trim($_POST['email']??'');
 $pw=$_POST['password']??''; $confirm=$_POST['confirm_password']??'';
 if(mb_strlen($name)<3 || mb_strlen($name)>100) $error='Full name must contain 3–100 characters.';
 elseif(!filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($email)>100) $error='Please enter a valid email.';
 elseif(strlen($pw)<8) $error='Password must have at least 8 characters.';
 elseif($pw!==$confirm) $error='Passwords do not match.';
 else {
  try {
   $stmt=$pdo->prepare('INSERT INTO users(full_name,email,password,role) VALUES(?,?,?,?)');
   $stmt->execute([$name,$email,password_hash($pw,PASSWORD_DEFAULT),'student']);
   flash('Account created. Please log in.');go(BASE_URL.'/login.php');
  } catch(PDOException $ex) { $error=$ex->getCode()==='23000'?'Email is already registered.':'Registration failed.'; }
 }
}
$pageTitle='Register'; require 'includes/header.php'; ?>
<div class="panel auth-panel"><h2 class="text-center text-primary mb-4"><i class="bi bi-person-plus-fill"></i> Create Account</h2>
<?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="validate" novalidate><input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
<div class="mb-3"><label class="form-label">Full Name</label><input class="form-control" name="full_name" required minlength="3" maxlength="100" value="<?= e($_POST['full_name']??'') ?>"></div>
<div class="mb-3"><label class="form-label">Email Address</label><input class="form-control" type="email" name="email" required maxlength="100" value="<?= e($_POST['email']??'') ?>"></div>
<div class="mb-3"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required minlength="8"></div>
<div class="mb-3"><label class="form-label">Confirm Password</label><input class="form-control" type="password" name="confirm_password" required minlength="8"></div>
<button class="btn btn-primary w-100"><i class="bi bi-person-plus-fill me-1"></i> Register</button><p class="text-center small mt-3">Already have an account? <a href="login.php">Log in here</a></p>
</form></div><?php require 'includes/footer.php'; ?>
