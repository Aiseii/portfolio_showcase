<?php
require '../includes/init.php';requireLogin('student');
header('Content-Type: text/html; charset=utf-8');header('Cache-Control: no-store');
$q=mb_substr(trim($_GET['q']??''),0,100);
$sql='SELECT p.id,p.title,p.description,p.tech_stack,p.created_at,c.category_name,u.full_name FROM projects p JOIN categories c ON p.category_id=c.id JOIN users u ON p.user_id=u.id';
$params=[];
if($q!==''){$sql.=' WHERE p.title LIKE ? OR p.tech_stack LIKE ? OR u.full_name LIKE ?';$params=array_fill(0,3,'%'.$q.'%');}
$sql.=' ORDER BY p.created_at DESC, p.id DESC LIMIT 60';
$stmt=$pdo->prepare($sql);$stmt->execute($params);$rows=$stmt->fetchAll();
if(!$rows)echo '<div class="col-12"><div class="alert alert-secondary">No matching projects found.</div></div>';
foreach($rows as $row): ?>
<div class="col-md-4"><div class="card project-card"><div class="card-body"><div class="d-flex justify-content-between mb-2"><span class="tag"><?= e($row['category_name']) ?></span><small class="text-muted"><?= e(date('d/m/Y',strtotime($row['created_at']))) ?></small></div>
<h5 class="card-title"><?= e($row['title']) ?></h5><p class="description small"><?= e(mb_strimwidth($row['description'],0,180,'...')) ?></p><small class="text-muted">Tech Stack:</small><p class="small"><?= e($row['tech_stack']) ?></p>
<div class="d-flex justify-content-between align-items-center"><small>👤 <?= e($row['full_name']) ?></small><a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>/download.php?type=project&id=<?= (int)$row['id'] ?>">⬇ Report</a></div>
</div></div></div>
<?php endforeach; ?>
