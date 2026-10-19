<?php require '../includes/init.php';requireLogin('student');$pageTitle='Student Dashboard';require '../includes/header.php'; ?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2"><div><h1>Project Showcase Directory</h1><p class="text-muted small">Explore student portfolio submissions and FYP projects.</p></div><a class="btn btn-primary" href="submit.php">+ Submit New Project</a></div>
<input id="liveSearch" data-url="<?= BASE_URL ?>/api/projects.php" class="form-control mb-3" placeholder="Live search by project title, tech stack, or student name..." aria-label="Live search projects">
<div id="projectResults" class="row g-3" aria-live="polite">Loading projects...</div>
<?php require '../includes/footer.php'; ?>
