<?php
require '../includes/init.php';requireLogin('admin');$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verifyCsrf();$action=$_POST['action']??'create';
 if($action==='delete'){
  $id=(int)($_POST['id']??0);$stmt=$pdo->prepare('SELECT file_path FROM submissions WHERE assignment_id=?');$stmt->execute([$id]);$files=$stmt->fetchAll();
  $stmt=$pdo->prepare('DELETE FROM assignments WHERE id=?');$stmt->execute([$id]);
  if($stmt->rowCount()){foreach($files as $f)@unlink(__DIR__.'/../uploads/'.$f['file_path']);flash('Assignment deleted.');}
  go(BASE_URL.'/admin/assignments.php');
 }
 $title=trim($_POST['title']??'');$description=trim($_POST['description']??'');
 if(mb_strlen($title)<3||mb_strlen($title)>200||mb_strlen($description)<10)$error='Title must contain 3–200 characters and description at least 10.';
 else{$stmt=$pdo->prepare('INSERT INTO assignments(title,description,created_by) VALUES(?,?,?)');$stmt->execute([$title,$description,$_SESSION['user_id']]);flash('Assignment created.');go(BASE_URL.'/admin/assignments.php');}
}
$rows=$pdo->query('SELECT a.id,a.title,a.description,a.created_at,COUNT(s.id) AS total FROM assignments a LEFT JOIN submissions s ON s.assignment_id=a.id GROUP BY a.id,a.title,a.description,a.created_at ORDER BY a.created_at DESC')->fetchAll();
$pageTitle='Create Assignment';require '../includes/header.php';?>
<div class="row g-4"><div class="col-lg-5"><div class="panel"><h2>Create Assignment</h2><?php if($error):?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="validate" novalidate><input type="hidden" name="csrf" value="<?= e(csrf()) ?>"><div class="mb-3"><label class="form-label">Assignment Title</label><input class="form-control" name="title" required minlength="3" maxlength="200" value="<?= e($_POST['title']??'') ?>"></div><div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="5" required minlength="10"><?= e($_POST['description']??'') ?></textarea></div><button class="btn btn-primary">+ Create Assignment</button></form></div></div>
<div class="col-lg-7"><div class="panel"><h2>Available Assignments</h2><div class="table-responsive"><table class="table table-striped"><thead><tr><th>Title</th><th>Submissions</th><th>Actions</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><strong><?= e($r['title']) ?></strong><div class="small text-muted"><?= e($r['description']) ?></div></td><td><?= (int)$r['total'] ?></td><td><form method="post" onsubmit="return confirm('Delete assignment and its submissions?')"><input type="hidden" name="csrf" value="<?= e(csrf()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr><?php endforeach; ?></tbody></table></div></div></div></div>
<?php require '../includes/footer.php'; ?>
