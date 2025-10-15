<?php
require 'config.php';

$pelanggan_list = $conn->query("SELECT * FROM pelanggan ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);
$mekanik_list = $conn->query("SELECT * FROM mekanik ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);

$edit_mode = false;
if(isset($_POST['simpan'])){
    $id = $_POST['id'];
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_mekanik = $_POST['id_mekanik'];
    $jenis_servis = $_POST['jenis_servis'];
    $biaya = $_POST['biaya'];
    $tanggal = $_POST['tanggal'];

    if($id){
        $stmt = $conn->prepare("UPDATE servis SET id_pelanggan=?, id_mekanik=?, jenis_servis=?, biaya=?, tanggal=? WHERE id_servis=?");
        $stmt->execute([$id_pelanggan, $id_mekanik, $jenis_servis, $biaya, $tanggal, $id]);
    } else {
        $stmt = $conn->prepare("INSERT INTO servis (id_pelanggan,id_mekanik,jenis_servis,biaya,tanggal) VALUES (?,?,?,?,?)");
        $stmt->execute([$id_pelanggan, $id_mekanik, $jenis_servis, $biaya, $tanggal]);
    }
    header("Location: servis.php");
    exit;
}

if(isset($_GET['edit'])){
    $edit_mode = true;
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM servis WHERE id_servis=?");
    $stmt->execute([$id]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    $conn->prepare("DELETE FROM servis WHERE id_servis=?")->execute([$id]);
    header("Location: servis.php");
    exit;
}

$servis = $conn->query("
    SELECT s.*, p.nama AS nama_pelanggan, m.nama AS nama_mekanik
    FROM servis s
    JOIN pelanggan p ON s.id_pelanggan=p.id_pelanggan
    JOIN mekanik m ON s.id_mekanik=m.id_mekanik
    ORDER BY s.id_servis DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Manajemen Servis</title>
        <link rel="stylesheet" href="../styles.css">
    </head>
    <body>
    <header>
        <h1>Manajemen Servis</h1>
    </header>

    <div class="container">
        <div class="card">
            <h3><?= $edit_mode ? "Edit Servis" : "Tambah Servis" ?></h3>
            <form method="post">
                <input type="hidden" name="id" value="<?= $edit_mode ? $edit_data['id_servis'] : '' ?>">

                <select name="id_pelanggan" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    <?php foreach($pelanggan_list as $p): ?>
                        <option value="<?= $p['id_pelanggan'] ?>" <?= ($edit_mode && $edit_data['id_pelanggan']==$p['id_pelanggan'])?'selected':'' ?>>
                            <?= $p['nama'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="id_mekanik" required>
                    <option value="">-- Pilih Mekanik --</option>
                    <?php foreach($mekanik_list as $m): ?>
                        <option value="<?= $m['id_mekanik'] ?>" <?= ($edit_mode && $edit_data['id_mekanik']==$m['id_mekanik'])?'selected':'' ?>>
                            <?= $m['nama'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="text" name="jenis_servis" placeholder="Jenis Servis" required value="<?= $edit_mode ? $edit_data['jenis_servis'] : '' ?>">
                <input type="number" step="0.01" name="biaya" placeholder="Biaya" required value="<?= $edit_mode ? $edit_data['biaya'] : '' ?>">
                <input type="date" name="tanggal" required value="<?= $edit_mode ? $edit_data['tanggal'] : '' ?>">

                <button type="submit" name="simpan" class="btn btn-primary"><?= $edit_mode ? "Update" : "Simpan" ?></button>
                <?php if($edit_mode): ?><a href="servis.php" class="btn btn-secondary">Batal</a><?php endif; ?>
            </form>
        </div>

        <div class="card">
            <h3>Daftar Servis</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Pelanggan</th><th>Mekanik</th><th>Jenis Servis</th><th>Biaya</th><th>Tanggal</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($servis as $s): ?>
                    <tr>
                        <td><?= $s['id_servis'] ?></td>
                        <td><?= $s['nama_pelanggan'] ?></td>
                        <td><?= $s['nama_mekanik'] ?></td>
                        <td><?= $s['jenis_servis'] ?></td>
                        <td><?= $s['biaya'] ?></td>
                        <td><?= $s['tanggal'] ?></td>
                        <td>
                            <a href="servis.php?edit=<?= $s['id_servis'] ?>" class="btn btn-success btn-sm">Edit</a>
                            <a href="servis.php?hapus=<?= $s['id_servis'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin dihapus?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <a href="../index.html" class="btn btn-secondary">Kembali</a>
    </div>

    <footer>
        &copy; Sistem Bengkel
    </footer>
    </body>
</html>