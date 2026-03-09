<?php
require_once '../vendor/autoload.php';
include '../koneksi.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    echo "error: File tidak ditemukan atau gagal diupload.";
    exit;
}

$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
if (!in_array($ext, ['xls', 'xlsx'])) {
    echo "error: Hanya file Excel (.xls, .xlsx) yang diperbolehkan.";
    exit;
}

try {
    $sheet   = IOFactory::load($_FILES['file']['tmp_name'])->getActiveSheet();
    $rows    = $sheet->toArray(null, true, true, false);
} catch (Exception $e) {
    echo "error: Gagal membaca file Excel.";
    exit;
}

if (count($rows) <= 1) {
    echo "error: Data di file kosong atau hanya berisi header.";
    exit;
}

$headers = array_map('strtolower', array_map('trim', array_shift($rows)));
$colMap  = array_flip($headers);

foreach (['kd_aset', 'nama'] as $col) {
    if (!isset($colMap[$col])) {
        echo "error: Format file tidak sesuai. Kolom '$col' tidak ditemukan.";
        exit;
    }
}

$sql = "INSERT INTO tb_aset 
        (kd_aset, no_aset, nama, merk, kategori, tgl_peroleh, harga_peroleh,
         umur_pakai, lokasi, status, keterangan, nilai_sekarang,
         date_created, date_modified, petugas)
        VALUES 
        (:kd_aset, :no_aset, :nama, :merk, :kategori, :tgl_peroleh, :harga_peroleh,
         :umur_pakai, :lokasi, :status, :keterangan, :nilai_sekarang,
         NOW(), NOW(), :petugas)
        ON DUPLICATE KEY UPDATE
            nama           = VALUES(nama),
            merk           = VALUES(merk),
            kategori       = VALUES(kategori),
            tgl_peroleh    = VALUES(tgl_peroleh),
            harga_peroleh  = VALUES(harga_peroleh),
            umur_pakai     = VALUES(umur_pakai),
            lokasi         = VALUES(lokasi),
            status         = VALUES(status),
            keterangan     = VALUES(keterangan),
            nilai_sekarang = VALUES(nilai_sekarang),
            date_modified  = NOW(),
            petugas        = VALUES(petugas)";

$stmt   = $pdo->prepare($sql);
$sukses = 0;
$gagal  = 0;

$g = fn($row, $key, $def = '') => trim((string)($row[$colMap[$key] ?? -1] ?? $def)) ?: $def;

foreach ($rows as $row) {
    $kd_aset = $g($row, 'kd_aset');
    if (empty($kd_aset)) continue;

    try {
        $stmt->execute([
            ':kd_aset'        => $kd_aset,
            ':no_aset'        => $g($row, 'no_aset'),
            ':nama'           => $g($row, 'nama'),
            ':merk'           => $g($row, 'merk'),
            ':kategori'       => $g($row, 'kategori', '0'),
            ':tgl_peroleh'    => $g($row, 'tgl_peroleh', '0000-00-00'),
            ':harga_peroleh'  => (float) str_replace(['.', ','], ['', '.'], $g($row, 'harga_peroleh', '0')),
            ':umur_pakai'     => (int) $g($row, 'umur_pakai', '0'),
            ':lokasi'         => $g($row, 'lokasi'),
            ':status'         => $g($row, 'status', 'BAIK'),
            ':keterangan'     => $g($row, 'keterangan', '-'),
            ':nilai_sekarang' => (float) $g($row, 'nilai_sekarang', '0'),
            ':petugas'        => $g($row, 'petugas', 'Admin'),
        ]);
        $sukses++;
    } catch (PDOException $e) {
        $gagal++;
    }
}

if ($sukses === 0)     echo "error: Tidak ada data yang berhasil disimpan.";
elseif ($gagal > 0)    echo "success: $sukses data berhasil, $gagal gagal/duplikat.";
else                   echo "success: $sukses data aset berhasil diupload!";
