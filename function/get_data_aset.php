<?php
header('Content-Type: application/json');
include '../koneksi.php';

try {
    $rows = $pdo->query("SELECT * FROM tb_aset ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo json_encode(['data' => []]);
    exit;
}

$no   = 1;
$data = array_map(function($row) use (&$no) {
    $status = $row['status'] ?? '-';
    $badge  = match($status) {
        'BAIK'  => 'success',
        'RUSAK' => 'danger',
        default => 'warning'
    };
    return [
        'no'            => $no++,
        'kd_aset'       => $row['kd_aset'],
        'no_aset'       => $row['no_aset'],
        'nama'          => $row['nama'],
        'kategori'      => $row['kategori'],
        'tgl_peroleh'   => $row['tgl_peroleh'],
        'harga_peroleh' => number_format((float)$row['harga_peroleh'], 0, ',', '.'),
        'umur_pakai'    => $row['umur_pakai'] . ' bln',
        'lokasi'        => $row['lokasi'],
        'status'        => "<span class='label label-{$badge}'>{$status}</span>",
        'keterangan'    => $row['keterangan'],
        'petugas'       => $row['petugas'],
    ];
}, $rows);

echo json_encode(['data' => $data]);