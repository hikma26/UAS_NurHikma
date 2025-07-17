<?php
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=laporan-donor-dan-permintaan-" . date('Ymd') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

include '../koneksi.php';

// Ambil data pendonor
$pendonor = mysqli_query($conn, "
  SELECT d.name, b.type AS blood_type, d.gender, d.phone, CONCAT(d.district, ', ', d.village) AS wilayah, d.weight, d.medical_history, s.name AS status_name, d.created_at
  FROM donors d
  JOIN blood_types b ON d.blood_type_id = b.id
  JOIN statuses s ON d.status_id = s.id
  ORDER BY d.created_at DESC
");

// Ambil data permintaan darah
$permintaan = mysqli_query($conn, "
  SELECT r.nama_pasien, b.type AS blood_type, r.jumlah, r.kontak, r.keterangan, s.name AS status_name, r.created_at
  FROM requests r
  JOIN blood_types b ON r.blood_type_id = b.id
  JOIN statuses s ON r.status_id = s.id
  ORDER BY r.created_at DESC
");

// Style inline untuk th dan td
$th_style = "border:1px solid black; background-color:#8B0000; color:white; padding:5px; text-align:center;";
$td_style = "border:1px solid black; padding:5px;";
?>

<h2>Data Pendonor</h2>
<table border="0" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th style="<?= $th_style ?>">No</th>
      <th style="<?= $th_style ?>">Nama</th>
      <th style="<?= $th_style ?>">Golongan Darah</th>
      <th style="<?= $th_style ?>">Gender</th>
      <th style="<?= $th_style ?>">HP</th>
      <th style="<?= $th_style ?>">Wilayah</th>
      <th style="<?= $th_style ?>">Berat Badan (kg)</th>
      <th style="<?= $th_style ?>">Riwayat Medis</th>
      <th style="<?= $th_style ?>">Status</th>
      <th style="<?= $th_style ?>">Waktu Daftar</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if (mysqli_num_rows($pendonor) > 0) {
      $no = 1;
      while ($d = mysqli_fetch_assoc($pendonor)) {
        echo "<tr>";
        echo "<td style=\"$td_style text-align:center;\">{$no}</td>";
        echo "<td style=\"$td_style\">" . htmlspecialchars($d['name']) . "</td>";
        echo "<td style=\"$td_style text-align:center;\">" . $d['blood_type'] . "</td>";
        echo "<td style=\"$td_style text-align:center;\">" . $d['gender'] . "</td>";
        echo "<td style=\"$td_style\">" . $d['phone'] . "</td>";
        echo "<td style=\"$td_style\">" . $d['wilayah'] . "</td>";
        echo "<td style=\"$td_style text-align:center;\">" . $d['weight'] . "</td>";
        echo "<td style=\"$td_style\">" . htmlspecialchars($d['medical_history']) . "</td>";
        echo "<td style=\"$td_style text-align:center;\">" . $d['status_name'] . "</td>";
        echo "<td style=\"$td_style text-align:center;\">" . date('d/m/Y H:i', strtotime($d['created_at'])) . "</td>";
        echo "</tr>";
        $no++;
      }
    } else {
      echo "<tr><td colspan='10' style=\"$td_style text-align:center;\">Tidak ada data pendonor.</td></tr>";
    }
    ?>
  </tbody>
</table>

<br><br>

<h2>Data Permintaan Darah</h2>
<table border="0" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th style="<?= $th_style ?>">No</th>
      <th style="<?= $th_style ?>">Nama Pasien</th>
      <th style="<?= $th_style ?>">Golongan Darah</th>
      <th style="<?= $th_style ?>">Jumlah</th>
      <th style="<?= $th_style ?>">Kontak</th>
      <th style="<?= $th_style ?>">Keterangan</th>
      <th style="<?= $th_style ?>">Status</th>
      <th style="<?= $th_style ?>">Waktu Permintaan</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if (mysqli_num_rows($permintaan) > 0) {
      $no = 1;
      while ($r = mysqli_fetch_assoc($permintaan)) {
        echo "<tr>";
        echo "<td style=\"$td_style text-align:center;\">{$no}</td>";
        echo "<td style=\"$td_style\">" . htmlspecialchars($r['nama_pasien']) . "</td>";
        echo "<td style=\"$td_style text-align:center;\">" . $r['blood_type'] . "</td>";
        echo "<td style=\"$td_style text-align:center;\">" . $r['jumlah'] . "</td>";
        echo "<td style=\"$td_style\">" . htmlspecialchars($r['kontak']) . "</td>";
        echo "<td style=\"$td_style\">" . htmlspecialchars($r['keterangan']) . "</td>";
        echo "<td style=\"$td_style text-align:center;\">" . $r['status_name'] . "</td>";
        echo "<td style=\"$td_style text-align:center;\">" . date('d/m/Y H:i', strtotime($r['created_at'])) . "</td>";
        echo "</tr>";
        $no++;
      }
    } else {
      echo "<tr><td colspan='8' style=\"$td_style text-align:center;\">Tidak ada data permintaan darah.</td></tr>";
    }
    ?>
  </tbody>
</table>

