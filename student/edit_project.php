<?php
require '../includes/init.php';requireLogin('student');$id=(int)($_GET['id']??$_POST['id']??0);
$stmt=$pdo->prepare('SELECT * FROM projects WHERE id=? AND user_id=?');$stmt->execute([$id,$_SESSION['user_id']]);$project=$stmt->fetch();
if(!$project){http_response_code(404);exit('Project not found.');}$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verifyCsrf();$title=trim($_POST['title']??'');$description=trim($_POST['description']??'');$tech=trim($_POST['tech_stack']??'');$category=(int)($_POST['category_id']??0);
 $stmt=$pdo->prepare('SELECT id FROM categories WHERE id=?');$stmt->execute([$category]);
 if(mb_strlen($title)<3||mb_strlen($title)>200||mb_strlen($description)<10||mb_strlen($tech)<2||mb_strlen($tech)>255||!$stmt->fetch())$error='Please check all project fields.';
 else{
  $stmt=$pdo->prepare('UPDATE projects SET title=?,description=?,tech_stack=?,category_id=? WHERE id=? AND user_id=?');$stmt->execute([$title,$description,$tech,$category,$id,$_SESSION['user_id']]);flash('Project updated.');go(BASE_URL.'/student/my_projects.php');
 }
}
$categories=$pdo->prepare('SELECT id,category_name FROM categories ORDER BY category_name');$categories->execute();$pageTitle='Edit Project';require '../includes/header.php'; ?>
<div class="panel mx-auto" style="max-width:850px"><h1>Edit Project</h1><?php if($error):?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="validate" novalidate><input type="hidden" name="csrf" value="<?= e(csrf()) ?>"><input type="hidden" name="id" value="<?= $id ?>">
<div class="mb-3"><label class="form-label">Project Title</label><input name="title" class="form-control" required minlength="3" maxlength="200" value="<?= e($_POST['title']??$project['title']) ?>"></div>
<div class="mb-3"><label class="form-label">Category</label><select class="form-select" name="category_id" required><?php foreach($categories as $c):?><option value="<?= $c['id'] ?>" <?= (($_POST['category_id']??$project['category_id'])==$c['id'])?'selected':'' ?>><?= e($c['category_name']) ?></option><?php endforeach; ?></select></div>
<div class="mb-3"><label class="form-label">Tech Stack</label><input class="form-control" name="tech_stack" required maxlength="255" value="<?= e($_POST['tech_stack']??$project['tech_stack']) ?>"></div>
<div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="description" required minlength="10" rows="4"><?= e($_POST['description']??$project['description']) ?></textarea></div>
<button class="btn btn-primary">Save Changes</button> <a class="btn btn-secondary" href="my_projects.php">Back</a></form></div>
<?php require '../includes/footer.php'; ?>
