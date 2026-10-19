<?php
require_once __DIR__.'/init.php';
$pageTitle = $pageTitle ?? 'Portfolio Showcase';
$role = $_SESSION['role'] ?? null;
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($pageTitle) ?> | Portfolio Showcase</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/style.css" rel="stylesheet"></head><body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark"><div class="container">
<a class="navbar-brand fw-bold" href="<?= $role ? home() : BASE_URL.'/login.php' ?>"><i class="bi bi-mortarboard-fill text-warning me-1"></i> Portfolio Showcase</a>
<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="mainNav"><div class="navbar-nav me-auto">
<?php if ($role === 'student'): ?>
<a class="nav-link" href="<?= BASE_URL ?>/student/dashboard.php"><i class="bi bi-house-door-fill me-1"></i> Dashboard</a>
<a class="nav-link" href="<?= BASE_URL ?>/student/submit.php"><i class="bi bi-cloud-arrow-up me-1"></i> Submit Project</a>
<a class="nav-link" href="<?= BASE_URL ?>/student/my_projects.php"><i class="bi bi-collection me-1"></i> My Portfolio</a>
<a class="nav-link" href="<?= BASE_URL ?>/student/assignments.php">Assignments</a>
<?php elseif ($role === 'admin'): ?>
<a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="bi bi-speedometer2 me-1"></i> Admin Dashboard</a>
<a class="nav-link" href="<?= BASE_URL ?>/admin/categories.php"><i class="bi bi-tags me-1"></i> Categories</a>
<a class="nav-link" href="<?= BASE_URL ?>/admin/assignments.php">Assignments</a>
<a class="nav-link" href="<?= BASE_URL ?>/admin/submissions.php"><i class="bi bi-folder2-open me-1"></i> All Submissions</a>
<?php endif; ?></div><div class="navbar-nav">
<?php if ($role): ?><span class="navbar-text me-3"><?= e($_SESSION['name']) ?> (<?= e(ucfirst($role)) ?>)</span>
<form method="post" action="<?= BASE_URL ?>/logout.php"><input type="hidden" name="csrf" value="<?= e(csrf()) ?>"><button class="btn btn-sm btn-outline-light">Logout</button></form>
<?php else: ?><a class="nav-link" href="<?= BASE_URL ?>/login.php"><i class="bi bi-box-arrow-in-right me-1"></i> Login</a><a class="nav-link" href="<?= BASE_URL ?>/register.php"><i class="bi bi-person-plus-fill me-1"></i> Register</a><?php endif; ?>
</div></div></div></nav><main class="container py-4">
<?php if (isset($_SESSION['notice'])): $n=$_SESSION['notice']; unset($_SESSION['notice']); ?>
<div class="alert alert-<?= $n['type']==='danger'?'danger':'success' ?> alert-dismissible fade show"> <?= e($n['text']) ?> <button class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
