<?php
require_once 'Database.php';

$db = Database::getInstance()->getConnection();

if (isset($_GET['delete_id'])) {
    $id_to_delete = $_GET['delete_id'];
    
    $stmt = $db->prepare("DELETE FROM produk WHERE id = ?");
    
    if ($stmt->execute([$id_to_delete])) {
        header("Location: index.php?msg=success_delete");
        exit;
    } else {
        header("Location: index.php?msg=error_delete");
        exit;
    }
}

$query = "
    SELECT 
        produk.id, 
        produk.nama_produk, 
        kategori.nama_kategori, 
        supplier.nama_supplier, 
        produk.stok, 
        produk.harga 
    FROM produk 
    JOIN kategori ON produk.kategori_id = kategori.id 
    JOIN supplier ON produk.supplier_id = supplier.id
    ORDER BY produk.id DESC
";
$stmt = $db->prepare($query);
$stmt->execute();
$produk_list = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CRUD Inventaris - Tugas 8</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-8 font-sans">
    
    <main class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800">Sistem Manajemen Inventaris</h1>
                <p class="text-slate-500 mt-1">Tugas Rutin 8 - Pemrograman Web</p>
            </div>
            <!-- Tombol Tambah Data -->
            <a href="create.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition">
                + Tambah Produk
            </a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] == 'success_create'): ?>
                <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 shadow-sm">Data produk berhasil ditambahkan!</div>
            <?php elseif ($_GET['msg'] == 'success_update'): ?>
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 shadow-sm">Data produk berhasil diperbarui!</div>
            <?php elseif ($_GET['msg'] == 'success_delete'): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm">Data produk berhasil dihapus!</div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800 text-white">
                        <th class="py-4 px-6 font-semibold text-sm uppercase">ID</th>
                        <th class="py-4 px-6 font-semibold text-sm uppercase">Nama Produk</th>
                        <th class="py-4 px-6 font-semibold text-sm uppercase">Kategori</th>
                        <th class="py-4 px-6 font-semibold text-sm uppercase">Supplier</th>
                        <th class="py-4 px-6 font-semibold text-sm uppercase text-right">Stok</th>
                        <th class="py-4 px-6 font-semibold text-sm uppercase text-right">Harga</th>
                        <th class="py-4 px-6 font-semibold text-sm uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($produk_list as $row): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-6 text-slate-500 font-medium">#<?= $row['id'] ?></td>
                            <td class="py-3 px-6 text-slate-800 font-bold"><?= htmlspecialchars($row['nama_produk']) ?></td>
                            <td class="py-3 px-6">
                                <span class="bg-indigo-100 text-indigo-700 py-1 px-3 rounded-full text-xs font-semibold">
                                    <?= htmlspecialchars($row['nama_kategori']) ?>
                                </span>
                            </td>
                            <td class="py-3 px-6 text-slate-600"><?= htmlspecialchars($row['nama_supplier']) ?></td>
                            <td class="py-3 px-6 text-right font-medium <?= $row['stok'] < 20 ? 'text-red-500' : 'text-slate-700' ?>">
                                <?= number_format($row['stok']) ?>
                            </td>
                            <td class="py-3 px-6 text-right text-slate-700 font-medium">
                                Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                            </td>
                            <td class="py-3 px-6 text-center space-x-2">
                                <a href="update.php?id=<?= $row['id'] ?>" class="inline-block bg-amber-400 hover:bg-amber-500 text-amber-900 font-semibold py-1 px-3 rounded text-sm transition">
                                    Edit
                                </a>
                                <a href="index.php?delete_id=<?= $row['id'] ?>" 
                                   onclick="return confirm('Yakin ingin menghapus produk <?= htmlspecialchars($row['nama_produk']) ?>?');" 
                                   class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded text-sm transition">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php if (count($produk_list) == 0): ?>
                <div class="p-8 text-center text-slate-500">Belum ada data produk.</div>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>