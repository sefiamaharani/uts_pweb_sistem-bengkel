<?php
require 'config.php';
$edit_mode = false;

if(isset($_POST['simpan'])){
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $kendaraan = $_POST['kendaraan'];
    $nopol = $_POST['nopol'];

    if($id){
        $conn->prepare("UPDATE pelanggan SET nama=?, no_hp=?, alamat=?, kendaraan=?, nopol=? WHERE id_pelanggan=?")
             ->execute([$nama,$no_hp,$alamat,$kendaraan,$nopol,$id]);
    } else {
        $conn->prepare("INSERT INTO pelanggan (nama,no_hp,alamat,kendaraan,nopol) VALUES (?,?,?,?,?)")
             ->execute([$nama,$no_hp,$alamat,$kendaraan,$nopol]);
    }
    header("Location: pelanggan.php");
    exit;
}

if(isset($_GET['edit'])){
    $edit_mode = true;
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM pelanggan WHERE id_pelanggan=?");
    $stmt->execute([$id]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    $conn->prepare("DELETE FROM pelanggan WHERE id_pelanggan=?")->execute([$id]);
    header("Location: pelanggan.php");
    exit;
}

$pelanggan = $conn->query("SELECT * FROM pelanggan ORDER BY id_pelanggan DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pelanggan</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<header><h1>Manajemen Pelanggan</h1></header>
<div class="container">

<div class="card">
    <h3><?= $edit_mode ? "Edit Pelanggan" : "Tambah Pelanggan" ?></h3>
    <form method="post">
        <input type="hidden" name="id" value="<?= $edit_mode ? $edit_data['id_pelanggan'] : '' ?>">
        <input type="text" name="nama" placeholder="Nama" required value="<?= $edit_mode ? $edit_data['nama'] : '' ?>">
        <input type="text" name="no_hp" placeholder="No HP" value="<?= $edit_mode ? $edit_data['no_hp'] : '' ?>">
        <input type="text" name="alamat" placeholder="Alamat" value="<?= $edit_mode ? $edit_data['alamat'] : '' ?>">
        <input type="text" name="kendaraan" placeholder="Kendaraan" value="<?= $edit_mode ? $edit_data['kendaraan'] : '' ?>">
        <input type="text" name="nopol" placeholder="No Polisi" value="<?= $edit_mode ? $edit_data['nopol'] : '' ?>">
        <button type="submit" name="simpan" class="btn btn-primary"><?= $edit_mode ? "Update" : "Simpan" ?></button>
        <?php if($edit_mode): ?><a href="pelanggan.php" class="btn btn-secondary">Batal</a><?php endif; ?>
    </form>
</div>

<div class="card">
    <h3>Daftar Pelanggan</h3>
    <table>
        <thead>
            <tr><th>ID</th><th>Nama</th><th>No HP</th><th>Alamat</th><th>Kendaraan</th><th>No Polisi</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php foreach($pelanggan as $p): ?>
            <tr>
                <td><?= $p['id_pelanggan'] ?></td>
                <td><?= $p['nama'] ?></td>
                <td><?= $p['no_hp'] ?></td>
                <td><?= $p['alamat'] ?></td>
                <td><?= $p['kendaraan'] ?></td>
                <td><?= $p['nopol'] ?></td>
                <td>
                    <a href="pelanggan.php?edit=<?= $p['id_pelanggan'] ?>" class="btn btn-success">Edit</a>
                    <a href="pelanggan.php?hapus=<?= $p['id_pelanggan'] ?>" class="btn btn-danger" onclick="return confirm('Yakin dihapus?')">Hapus</a>
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