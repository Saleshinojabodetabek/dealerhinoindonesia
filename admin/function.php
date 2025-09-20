<?php
function createSlug($judul) {
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim($judul)));
    $slug = trim($slug, '-');
    return $slug;
}

// Bisa dipakai untuk artikel maupun produk
function uniqueSlug($conn, $slug, $id=0, $table='artikel'){
    $sql = $id > 0 
        ? "SELECT id FROM $table WHERE slug='$slug' AND id<>$id"
        : "SELECT id FROM $table WHERE slug='$slug'";
    $res = $conn->query($sql);

    $original = $slug;
    $i = 1;
    while ($res && $res->num_rows > 0) {
        $slug = $original . '-' . $i;
        $sql = $id > 0 
            ? "SELECT id FROM $table WHERE slug='$slug' AND id<>$id"
            : "SELECT id FROM $table WHERE slug='$slug'";
        $res = $conn->query($sql);
        $i++;
    }
    return $slug;
}
?>
