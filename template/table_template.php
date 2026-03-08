<?php
include('../config/db.php');
session_start();
$table = basename($_SERVER['PHP_SELF'], '.php');
$title = ucwords(str_replace('_', ' ', $table));

// Gunakan custom query jika ada
if (isset($custom_query)) {
    $data = $pdo->query($custom_query)->fetchAll();
} else {
    $data = $pdo->query("SELECT * FROM $table")->fetchAll();
}

// Get column names
$columns = [];
if (!empty($data)) {
    $columns = array_keys($data[0]);
}

// Get primary key column
$primary_key = '';
if (!empty($columns)) {
    foreach ($columns as $col) {
        if (strpos($col, '_id') !== false) {
            $primary_key = $col;
            break;
        }
    }
    if (empty($primary_key)) {
        $primary_key = $columns[0];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title><?= $title ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../../public/style.css">
</head>
<body>
<div class="container mt-5 pt-4">
  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?= $_SESSION['msg_type'] ?> alert-dismissible fade show" role="alert">
      <?= $_SESSION['message'] ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php 
    unset($_SESSION['message']);
    unset($_SESSION['msg_type']);
    ?>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">
      <i class="bi bi-table me-2"></i><?= $title ?>
    </h2>
    <?php if (!isset($custom_query)): ?>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-circle"></i> Add New
      </button>
    <?php endif; ?>
  </div>

  <div class="card shadow">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-striped">
          <thead class="table-dark">
            <tr>
              <?php foreach ($columns as $col): ?>
                <th><?= ucwords(str_replace('_', ' ', $col)) ?></th>
              <?php endforeach; ?>
              <?php if (!isset($custom_query)): ?>
                <th>Actions</th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data as $row): ?>
              <tr>
                <?php foreach ($row as $val): ?>
                  <td><?= htmlspecialchars($val) ?></td>
                <?php endforeach; ?>
                <?php if (!isset($custom_query)): ?>
                  <td>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row[$primary_key] ?>">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href="../pages/crud.php?table=<?= $table ?>&delete=<?= $row[$primary_key] ?>&pk=<?= $primary_key ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>
                <?php endif; ?>
              </tr>

              <?php if (!isset($custom_query)): ?>
                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?= $row[$primary_key] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row[$primary_key] ?>" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel<?= $row[$primary_key] ?>">Edit <?= $title ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form action="../pages/crud.php?table=<?= $table ?>&id=<?= $row[$primary_key] ?>&pk=<?= $primary_key ?>" method="POST">
                        <div class="modal-body">
                          <?php foreach ($columns as $col): ?>
                            <?php if ($col != $primary_key): ?>
                              <div class="mb-3">
                                <label for="edit_<?= $col ?>" class="form-label"><?= ucwords(str_replace('_', ' ', $col)) ?></label>
                                <input type="text" class="form-control" id="edit_<?= $col ?>" name="<?= $col ?>" value="<?= htmlspecialchars($row[$col]) ?>">
                              </div>
                            <?php endif; ?>
                          <?php endforeach; ?>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary" name="update">Save changes</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <a href="<?= dirname(dirname($_SERVER['PHP_SELF'])) ?>/index.php" class="btn btn-secondary mt-3">
    <i class="bi bi-arrow-left"></i> Back to Dashboard
  </a>
</div>

<?php if (!isset($custom_query)): ?>
  <!-- Add Modal -->
  <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addModalLabel">Add New <?= $title ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="../pages/crud.php?table=<?= $table ?>" method="POST">
          <div class="modal-body">
            <?php foreach ($columns as $col): ?>
              <?php if ($col != $primary_key): ?>
                <div class="mb-3">
                  <label for="add_<?= $col ?>" class="form-label"><?= ucwords(str_replace('_', ' ', $col)) ?></label>
                  <input type="text" class="form-control" id="add_<?= $col ?>" name="<?= $col ?>" required>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="save">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
