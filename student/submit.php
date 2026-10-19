<?php
require '../includes/init.php';requireLogin('student');$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verifyCsrf();$title=trim($_POST['title']??'');$description=trim($_POST['description']??'');$tech=trim($_POST['tech_stack']??'');$category=(int)($_POST['category_id']??0);
 if(mb_strlen($title)<3||mb_strlen($title)>200||mb_strlen($description)<10||mb_strlen($tech)<2||mb_strlen($tech)>255)$error='Fill in a valid title, description (10+ characters), and tech stack.';
 else{
  $stmt=$pdo->prepare('SELECT id FROM categories WHERE id=?');$stmt->execute([$category]);
  if(!$stmt->fetch())$error='Select a valid category.';
  else try{
   [$path]=uploadDocument('document');
   try{$stmt=$pdo->prepare('INSERT INTO projects(user_id,category_id,title,description,tech_stack,file_path) VALUES(?,?,?,?,?,?)');$stmt->execute([$_SESSION['user_id'],$category,$title,$description,$tech,$path]);}
   catch(Throwable $ex){@unlink(__DIR__.'/../uploads/'.$path);throw $ex;}
   flash('Project submitted successfully.');go(BASE_URL.'/student/my_projects.php');
  }catch(RuntimeException $ex){$error=$ex->getMessage();}catch(Throwable $ex){$error='Could not save your project.';}
 }
}
$categories=$pdo->prepare('SELECT id,category_name FROM categories ORDER BY category_name');$categories->execute();
$pageTitle='Submit Project';require '../includes/header.php'; ?>
<div class="panel mx-auto" style="max-width:850px"><h1 class="text-primary">☁ Submit Portfolio Project</h1>
<?php if($error):?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="validate" novalidate><input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
<div class="mb-3"><label class="form-label">Project Title</label><input name="title" class="form-control" maxlength="200" minlength="3" required value="<?= e($_POST['title']??'') ?>"></div>
<div class="mb-3"><label class="form-label">Category</label><select name="category_id" class="form-select" required><option value="">-- Select Category --</option><?php foreach($categories as $c):?><option value="<?= (int)$c['id'] ?>" <?= (($_POST['category_id']??'')==$c['id'])?'selected':'' ?>><?= e($c['category_name']) ?></option><?php endforeach; ?></select></div>
<div class="mb-3"><label class="form-label">Tech Stack (Comma-Separated)</label><input name="tech_stack" class="form-control" required minlength="2" maxlength="255" placeholder="e.g. PHP, MySQL, Bootstrap 5, AJAX" value="<?= e($_POST['tech_stack']??'') ?>"></div>
<div class="mb-3"><label class="form-label">Project Description</label><textarea name="description" class="form-control" rows="4" required minlength="10"><?= e($_POST['description']??'') ?></textarea></div>
<div class="mb-3"><label class="form-label">Upload Documentation (PDF, DOCX, DOC or TXT - Max 5MB)</label><input name="document" type="file" class="form-control" accept=".pdf,.docx,.doc,.txt" required></div>
<button class="btn btn-primary">➤ Submit Entry</button></form></div>
<?php require '../includes/footer.php'; ?>
