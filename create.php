<?php
require_once 'Database.php';

$db = Database::getInstance()->getConnection();
$error = '';

$stmt_kategori = $db->query("SELECT * FROM kategori");
$kategori_list = $stmt_kategori->fetchAll();

$stmt_supplier = $db->query("SELECT * FROM supplier");
$supplier_list = $stmt_supplier->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama_produk']);
    $kategori = $_POST['kategori_id'];
    $supplier = $_POST['supplier_id'];
    $stok = (int)$_POST['stok'];
    $harga = (float)$_POST['harga'];

    if (empty($nama) || empty($kategori) || empty($supplier)) {
        $error = "Nama, Kategori, dan Supplier wajib diisi!";
    } else {
        $query = "INSERT INTO produk (nama_produk, kategori_id, supplier_id, stok, harga) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        
        if ($stmt->execute([$nama, $kategori, $supplier, $stok, $harga])) {
            header("Location: index.php?msg=success_create");
            exit;
        } else {
            $error = "Gagal menyimpan data ke database.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - Tugas 8</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-8 font-sans">
    <main class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-slate-200">
        
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h2 class="text-2xl font-extrabold text-slate-800">Tambah Produk Baru</h2>
            <a href="index.php" class="text-indigo-600 font-semibold hover:underline">← Kembali</a>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-slate-700 font-semibold mb-2">Nama Produk</label>
                <input type="text" name="nama_produk" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Kategori</label>
                    <select name="kategori_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($kategori_list as $kat): ?>
                            <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Supplier</label>
                    <select name="supplier_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white">
                        <option value="">-- Pilih Supplier --</option>
                        <?php foreach ($supplier_list as $sup): ?>
                            <option value="<?= $sup['id'] ?>"><?= htmlspecialchars($sup['nama_supplier']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-8">
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Stok Awal</label>
                    <input type="number" name="stok" min="0" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-2">Harga (Rp)</label>
                    <input type="number" name="harga" min="0" step="1000" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition">
                Simpan Produk
            </button>
        </form>
    </main>
</body>
</html>