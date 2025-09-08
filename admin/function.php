<?php
function createSlug($judul) {
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim($judul)));
    $slug = trim($slug, '-');
    return $slug;
}

// Pastikan slug unik, $id dipakai untuk edit agar tidak bertabrakan dengan ID sendiri
function uniqueSlug($conn, $slug, $id=0){
    $sql = $id > 0 
        ? "SELECT id FROM artikel WHERE slug='$slug' AND id<>$id"
        : "SELECT id FROM artikel WHERE slug='$slug'";
    $res = $conn->query($sql);
    $original = $slug;
    $i = 1;
    while($res->num_rows > 0){
        $slug = $original . '-' . $i;
        $res = $conn->query("SELECT id FROM artikel WHERE slug='$slug'" . ($id>0?" AND id<>$id":""));
        $i++;
    }
    return $slug;
}
?>
