<?php
require '../includes/init.php';requireLogin('admin');
$students=(int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn();
$categories=(int)$pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn();
$projects=(int)$pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn();
$recent=$pdo->query('SELECT p.title,p.created_at,c.category_name,u.full_name FROM projects p JOIN categories c ON c.id=p.category_id JOIN users u ON u.id=p.user_id ORDER BY p.created_at DESC LIMIT 8')->fetchAll();
$pageTitle='Admin Dashboard';require '../includes/header.php';?>
<h1>Admin Overview</h1><p class="text-muted">Lecturer & Coordinator Evaluation Portal</p>
<div class="row g-3 mb-4"><div class="col-md-4"><div class="stat blue">STUDENTS<h2 class="text-white"><?= $students ?></h2></div></div><div class="col-md-4"><div class="stat green">CATEGORIES<h2 class="text-white"><?= $categories ?></h2></div></div><div class="col-md-4"><div class="stat cyan">PROJECTS<h2 class="text-white"><?= $projects ?></h2></div></div></div>
<div class="panel"><h2>Recent Portfolio Submissions</h2><div class="table-responsive"><table class="table table-striped"><thead><tr><th>Student Name</th><th>Title</th><th>Category</th><th>Date</th></tr></thead><tbody><?php foreach($recent as $r):?><tr><td><?= e($r['full_name']) ?></td><td><?= e($r['title']) ?></td><td><?= e($r['category_name']) ?></td><td><?= e(date('d M Y',strtotime($r['created_at']))) ?></td></tr><?php endforeach; ?><?php if(!$recent):?><tr><td colspan="4">No submissions yet.</td></tr><?php endif; ?></tbody></table></div></div>
<?php require '../includes/footer.php'; ?>
