<?php
require 'config.php';
$edit_mode = false;

if(isset($_POST['simpan'])){
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $spesialisasi = $_POST['spesialisasi'];
    $no_hp = $_POST['no_hp'];
    $pengalaman = $_POST['pengalaman'];

    if($id){
        $conn->prepare("UPDATE mekanik SET nama=?, spesialisasi=?, no_hp=?, pengalaman=? WHERE id_mekanik=?")
             ->execute([$nama,$spesialisasi,$no_hp,$pengalaman,$id]);
    } else {
        $conn->prepare("INSERT INTO mekanik (nama,spesialisasi,no_hp,pengalaman) VALUES (?,?,?,?)")
             ->execute([$nama,$spesialisasi,$no_hp,$pengalaman]);
    }
    header("Location: mekanik.php");
    exit;
}

if(isset($_GET['edit'])){
    $edit_mode = true;
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM mekanik WHERE id_mekanik=?");
    $stmt->execute([$id]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    $conn->prepare("DELETE FROM mekanik WHERE id_mekanik=?")->execute([$id]);
    header("Location: mekanik.php");
    exit;
}

$mekanik = $conn->query("SELECT * FROM mekanik ORDER BY id_mekanik DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Mekanik</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<header><h1>Manajemen Mekanik</h1></header>
<div class="container">

<div class="card">
    <h3><?= $edit_mode ? "Edit Mekanik" : "Tambah Mekanik" ?></h3>
    <form method="post">
        <input type="hidden" name="id" value="<?= $edit_mode ? $edit_data['id_mekanik'] : '' ?>">
        <input type="text" name="nama" placeholder="Nama" required value="<?= $edit_mode ? $edit_data['nama'] : '' ?>">
        <input type="text" name="spesialisasi" placeholder="Spesialisasi" value="<?= $edit_mode ? $edit_data['spesialisasi'] : '' ?>">
        <input type="text" name="no_hp" placeholder="No HP" value="<?= $edit_mode ? $edit_data['no_hp'] : '' ?>">
        <input type="number" name="pengalaman" placeholder="Pengalaman (tahun)" value="<?= $edit_mode ? $edit_data['pengalaman'] : '' ?>">
        <button type="submit" name="simpan" class="btn btn-primary"><?= $edit_mode ? "Update" : "Simpan" ?></button>
        <?php if($edit_mode): ?><a href="mekanik.php" class="btn btn-secondary">Batal</a><?php endif; ?>
    </form>
</div>

<div class="card">
    <h3>Daftar Mekanik</h3>
    <table>
        <thead>
            <tr><th>ID</th><th>Nama</th><th>Spesialisasi</th><th>No HP</th><th>Pengalaman</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php foreach($mekanik as $m): ?>
            <tr>
                <td><?= $m['id_mekanik'] ?></td>
                <td><?= $m['nama'] ?></td>
                <td><?= $m['spesialisasi'] ?></td>
                <td><?= $m['no_hp'] ?></td>
                <td><?= $m['pengalaman'] ?></td>
                <td>
                    <a href="mekanik.php?edit=<?= $m['id_mekanik'] ?>" class="btn btn-success">Edit</a>
                    <a href="mekanik.php?hapus=<?= $m['id_mekanik'] ?>" class="btn btn-danger" onclick="return confirm('Yakin dihapus?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<a href="../index.html" class="btn btn-secondary">Kembali</a>
</div>
<footer>&copy; Sistem Bengkel</footer>
</body>
</html>
