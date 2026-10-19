<?php
require '../includes/init.php';requireLogin('admin');$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verifyCsrf();$name=trim($_POST['category_name']??'');$description=trim($_POST['description']??'');
 if(mb_strlen($name)<3||mb_strlen($name)>100||mb_strlen($description)<5)$error='Enter a valid category name and description (5+ characters).';
 else try{$stmt=$pdo->prepare('INSERT INTO categories(category_name,description) VALUES(?,?)');$stmt->execute([$name,$description]);flash('Category added.');go(BASE_URL.'/admin/categories.php');}
 catch(PDOException $ex){$error=$ex->getCode()==='23000'?'Category already exists.':'Could not save category.';}
}
$rows=$pdo->query('SELECT * FROM categories ORDER BY category_name')->fetchAll();$pageTitle='Categories';require '../includes/header.php';?>
<div class="row g-4"><div class="col-lg-5"><div class="panel"><h2 class="text-primary">Create Category</h2><?php if($error):?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="validate" novalidate><input type="hidden" name="csrf" value="<?= e(csrf()) ?>"><div class="mb-3"><label class="form-label">Category Name</label><input class="form-control" name="category_name" required minlength="3" maxlength="100"></div>
<div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="4" required minlength="5"></textarea></div><button class="btn btn-primary w-100">+ Add Category</button></form></div></div>
<div class="col-lg-7"><div class="panel"><h2>Existing Categories</h2><table class="table table-striped"><thead><tr><th>Category Name</th><th>Description</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><?= e($r['category_name']) ?></td><td><?= e($r['description']) ?></td></tr><?php endforeach; ?></tbody></table></div></div></div>
<?php require '../includes/footer.php'; ?>
