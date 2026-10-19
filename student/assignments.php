<?php
require '../includes/init.php';requireLogin('student');$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verifyCsrf();$assignment=(int)($_POST['assignment_id']??0);
 $stmt=$pdo->prepare('SELECT id FROM assignments WHERE id=?');$stmt->execute([$assignment]);
 if(!$stmt->fetch())$error='Assignment not found.';
 else{
  $stmt=$pdo->prepare('SELECT id FROM submissions WHERE assignment_id=? AND user_id=?');$stmt->execute([$assignment,$_SESSION['user_id']]);
  if($stmt->fetch())$error='You already submitted this assignment.';
  else try{
   [$path,$original]=uploadDocument('document');
   try{$stmt=$pdo->prepare('INSERT INTO submissions(assignment_id,user_id,file_path,original_name) VALUES(?,?,?,?)');$stmt->execute([$assignment,$_SESSION['user_id'],$path,$original]);}
   catch(Throwable $ex){@unlink(__DIR__.'/../uploads/'.$path);throw $ex;}
   flash('Assignment submitted successfully.');go(BASE_URL.'/student/assignments.php');
  }catch(RuntimeException $ex){$error=$ex->getMessage();}catch(PDOException $ex){$error='You already submitted this assignment.';}catch(Throwable $ex){$error='Unable to save assignment.';}
 }
}
$stmt=$pdo->prepare('SELECT a.id,a.title,a.description,a.created_at,s.id AS submission_id,s.created_at AS submitted_at FROM assignments a LEFT JOIN submissions s ON s.assignment_id=a.id AND s.user_id=? ORDER BY a.created_at DESC');$stmt->execute([$_SESSION['user_id']]);$rows=$stmt->fetchAll();
$pageTitle='Assignments';require '../includes/header.php';?>
<h1>Available Assignments</h1><p class="text-muted">Submit one document per assignment. Max 5MB.</p>
<?php if($error):?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<div class="row g-3"><?php foreach($rows as $r):?><div class="col-lg-6"><div class="card"><div class="card-body"><h5><?= e($r['title']) ?></h5><p><?= nl2br(e($r['description'])) ?></p>
<?php if($r['submission_id']):?><div class="alert alert-success py-2">Submitted <?= e(date('d M Y',strtotime($r['submitted_at']))) ?> · <a href="<?= BASE_URL ?>/download.php?type=submission&id=<?= (int)$r['submission_id'] ?>">Download</a></div>
<?php else: ?><form method="post" enctype="multipart/form-data" class="validate" novalidate><input type="hidden" name="csrf" value="<?= e(csrf()) ?>"><input type="hidden" name="assignment_id" value="<?= (int)$r['id'] ?>"><input class="form-control mb-2" type="file" name="document" accept=".pdf,.doc,.docx,.txt" required><button class="btn btn-primary btn-sm">Submit Assignment</button></form><?php endif; ?>
</div></div></div><?php endforeach; ?><?php if(!$rows):?><div class="col"><div class="alert alert-secondary">No assignments posted yet.</div></div><?php endif; ?></div>
<?php require '../includes/footer.php'; ?>
