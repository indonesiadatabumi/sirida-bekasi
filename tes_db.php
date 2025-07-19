<?php
$conn = pg_connect("host=localhost port=5432 dbname=DBSIMPATDA user=postgres password=admin");

if ($conn) {
    echo "✅ Koneksi PostgreSQL lokal berhasil.";
} else {
    echo "❌ Koneksi gagal: " . pg_last_error();
}
