<?php
require '../includes/init.php';requireLogin('student');
if($_SERVER['REQUEST_METHOD']==='POST'){
 verifyCsrf();$id=(int)($_POST['id']??0);
 if(($_POST['action']??'')==='delete'){
  $stmt=$pdo->prepare('SELECT file_path FROM projects WHERE id=? AND user_id=?');$stmt->execute([$id,$_SESSION['user_id']]);$item=$stmt->fetch();
  if($item){$del=$pdo->prepare('DELETE FROM projects WHERE id=? AND user_id=?');$del->execute([$id,$_SESSION['user_id']]);@unlink(__DIR__.'/../uploads/'.$item['file_path']);flash('Project deleted.');}
  go(BASE_URL.'/student/my_projects.php');
 }
}
$stmt=$pdo->prepare('SELECT p.*,c.category_name FROM projects p JOIN categories c ON c.id=p.category_id WHERE p.user_id=? ORDER BY p.created_at DESC');$stmt->execute([$_SESSION['user_id']]);$rows=$stmt->fetchAll();
$pageTitle='My Portfolio';require '../includes/header.php'; ?>
<div class="d-flex justify-content-between mb-3"><h1>💼 My Portfolio Submissions</h1><a class="btn btn-primary" href="submit.php">+ Add Submission</a></div>
<div class="panel table-responsive"><table class="table table-striped"><thead><tr><th>Project Title</th><th>Category</th><th>Tech Stack</th><th>File</th><th>Date</th><th>Actions</th></tr></thead><tbody>
<?php foreach($rows as $r):?><tr><td><?= e($r['title']) ?></td><td><span class="badge bg-secondary"><?= e($r['category_name']) ?></span></td><td><?= e($r['tech_stack']) ?></td><td><a class="btn btn-sm btn-outline-secondary" href="<?= BASE_URL ?>/download.php?type=project&id=<?= (int)$r['id'] ?>">Download</a></td><td><?= e(date('d M Y',strtotime($r['created_at']))) ?></td><td><a class="btn btn-sm btn-warning" href="edit_project.php?id=<?= (int)$r['id'] ?>">Edit</a> <form class="d-inline" method="post" onsubmit="return confirm('Delete this project?')"><input type="hidden" name="csrf" value="<?= e(csrf()) ?>"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><input type="hidden" name="action" value="delete"><button class="btn btn-sm btn-danger">Delete</button></form></td></tr><?php endforeach; ?>
<?php if(!$rows):?><tr><td colspan="6" class="text-center text-muted">No projects submitted yet.</td></tr><?php endif; ?></tbody></table></div>
<?php require '../includes/footer.php'; ?>
