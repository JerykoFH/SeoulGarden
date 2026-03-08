<?php
include('../config/db.php');
session_start();

if (!isset($joinQuery, $formFields, $primaryKeys)) {
    die("Variabel \$joinQuery, \$formFields, atau \$primaryKeys belum didefinisikan.");
}

$title = $title ?? ucwords(str_replace('_', ' ', $table));
$data = $pdo->query($joinQuery)->fetchAll();
$columns = !empty($data) ? array_keys($data[0]) : [];
?>
<!DOCTYPE html>
<html>
<head>
  <title><?= $title ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-5 pt-4">

<?php if (isset($_SESSION['message'])): ?>
  <div class="alert alert-<?= $_SESSION['msg_type'] ?> alert-dismissible fade show" role="alert">
    <?= $_SESSION['message'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  <?php unset($_SESSION['message'], $_SESSION['msg_type']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between mb-4">
  <h2><?= $title ?></h2>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus-circle"></i> Tambah
  </button>
</div>

<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <?php foreach ($columns as $col): ?>
            <th><?= ucwords(str_replace('_', ' ', $col)) ?></th>
          <?php endforeach; ?>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $row): ?>
          <tr>
            <?php foreach ($row as $val): ?>
              <td><?= htmlspecialchars($val) ?></td>
            <?php endforeach; ?>
            <td>
              <?php
              $keyString = '';
              foreach ($primaryKeys as $pk) {
                $keyString .= "&$pk=" . urlencode($row[$pk]);
              }
              $modalId = implode('_', array_map(fn($k) => $row[$k], $primaryKeys));
              ?>
              <a class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $modalId ?>">
                <i class="bi bi-pencil-square"></i>
              </a>
              <a href="../pages/crud_join.php?table=<?= $table ?>&delete=1<?= $keyString ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>

          <!-- Edit Modal -->
          <div class="modal fade" id="editModal<?= $modalId ?>" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="POST" action="../pages/crud_join.php?table=<?= $table ?>&edit=1<?= $keyString ?>">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit <?= $title ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <?php foreach ($formFields as $name => $info): ?>
                      <div class="mb-3">
                        <label class="form-label"><?= $info['label'] ?></label>
                        <?php if ($info['type'] === 'select'): ?>
                          <select name="<?= $name ?>" class="form-select" <?= $info['required'] ? 'required' : '' ?> <?= in_array($name, $primaryKeys) ? 'readonly disabled' : '' ?>>
                            <?php if (!empty($info['allow_empty'])): ?>
                              <option value="">(Kosong)</option>
                            <?php else: ?>
                              <option value="" disabled>Pilih <?= $info['label'] ?></option>
                            <?php endif; ?>
                            <?php foreach ($info['options'] as $opt): ?>
                              <?php $value = $opt[array_keys($opt)[0]]; ?>
                              <option value="<?= $value ?>" <?= $value == $row[$name] ? 'selected' : '' ?>>
                                <?= $info['option_label']($opt) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        <?php elseif ($info['type'] === 'textarea'): ?>
                          <textarea name="<?= $name ?>" class="form-control" <?= in_array($name, $primaryKeys) ? 'readonly' : '' ?>><?= htmlspecialchars($row[$name]) ?></textarea>
                        <?php else: ?>
                          <input type="<?= $info['type'] ?>" name="<?= $name ?>" value="<?= htmlspecialchars($row[$name]) ?>" class="form-control" <?= $info['required'] ? 'required' : '' ?> <?= in_array($name, $primaryKeys) ? 'readonly' : '' ?>>
                        <?php endif; ?>
                      </div>
                    <?php endforeach; ?>
                    <?php foreach ($primaryKeys as $pk): ?>
                      <input type="hidden" name="primary_keys[]" value="<?= $pk ?>">
                      <input type="hidden" name="<?= $pk ?>" value="<?= $row[$pk] ?>">
                    <?php endforeach; ?>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" name="update">Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../pages/crud_join.php?table=<?= $table ?>" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Tambah <?= $title ?></h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <?php foreach ($formFields as $name => $info): ?>
            <div class="mb-3">
              <label class="form-label"><?= $info['label'] ?></label>
              <?php if ($info['type'] === 'select'): ?>
                <select name="<?= $name ?>" class="form-select" <?= $info['required'] ? 'required' : '' ?> <?= in_array($name, $primaryKeys) ? '' : '' ?> >
                  <?php if (!empty($info['allow_empty'])): ?>
                    <option value="">(Kosong)</option>
                  <?php else: ?>
                    <option value="" disabled selected>Pilih <?= $info['label'] ?></option>
                  <?php endif; ?>
                  <?php foreach ($info['options'] as $opt): ?>
                    <option value="<?= $opt[array_keys($opt)[0]] ?>">
                      <?= $info['option_label']($opt) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              <?php elseif ($info['type'] === 'textarea'): ?>
                <textarea name="<?= $name ?>" class="form-control"></textarea>
              <?php else: ?>
                <input type="<?= $info['type'] ?>" name="<?= $name ?>" class="form-control" <?= $info['required'] ? 'required' : '' ?>>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button class="btn btn-primary" name="save">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<a href="../index.php" class="btn btn-secondary mt-3"><i class="bi bi-arrow-left"></i> Kembali</a>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
