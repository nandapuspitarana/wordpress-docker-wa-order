<?php
// Script untuk replace URL dalam serialized PHP data di database WordPress
$host = 'db';
$user = 'wpuser';
$pass = 'wppassword';
$db = 'wordpress';

$old_url = 'http://localhost:8080';
$new_url = 'https://wp-order.hello-marketer.com';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

echo "Koneksi berhasil.\n";

// Fungsi untuk replace URL di dalam serialized data
function replace_serialized($data, $old, $new)
{
    // Kalau bukan serialized, langsung replace
    if (!preg_match('/^(a|O|s|i|b|d|N)[:;]/', $data)) {
        return str_replace($old, $new, $data);
    }
    // Replace dulu string-nya
    $replaced = str_replace($old, $new, $data);
    // Perbaiki panjang string yang berubah karena URL berbeda panjangnya
    $replaced = preg_replace_callback('/s:(\d+):"(.*?)";/s', function ($matches) {
        $len = strlen($matches[2]);
        return 's:' . $len . ':"' . $matches[2] . '";';
    }, $replaced);
    return $replaced;
}

// Tabel dan kolom yang perlu dicek
$targets = [
    ['table' => 'wp_options', 'id_col' => 'option_id', 'val_col' => 'option_value', 'where' => "option_value LIKE '%localhost%'"],
    ['table' => 'wp_postmeta', 'id_col' => 'meta_id', 'val_col' => 'meta_value', 'where' => "meta_value LIKE '%localhost%'"],
    ['table' => 'wp_posts', 'id_col' => 'ID', 'val_col' => 'post_content', 'where' => "post_content LIKE '%localhost%'"],
    ['table' => 'wp_posts', 'id_col' => 'ID', 'val_col' => 'guid', 'where' => "guid LIKE '%localhost%'"],
];

foreach ($targets as $t) {
    $result = $conn->query("SELECT {$t['id_col']}, {$t['val_col']} FROM {$t['table']} WHERE {$t['where']}");
    if (!$result)
        continue;

    $updated = 0;
    while ($row = $result->fetch_assoc()) {
        $id = $row[$t['id_col']];
        $val = $row[$t['val_col']];
        $new_val = replace_serialized($val, $old_url, $new_url);

        if ($new_val !== $val) {
            $escaped = $conn->real_escape_string($new_val);
            $conn->query("UPDATE {$t['table']} SET {$t['val_col']} = '{$escaped}' WHERE {$t['id_col']} = {$id}");
            $updated++;
        }
    }
    echo "{$t['table']}.{$t['val_col']}: {$updated} baris diupdate\n";
}

echo "\nSelesai! Semua URL localhost:8080 -> wp-order.hello-marketer.com\n";
$conn->close();
