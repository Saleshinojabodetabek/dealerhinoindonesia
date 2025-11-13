<?php
/**
 * 🌐 WEBP CONVERTER FINAL (Full Recursive + Clean)
 * -------------------------------------------------
 * ✅ Konversi semua gambar JPG/JPEG/PNG ke WEBP
 * ✅ Hapus duplikat WEBP
 * ✅ Hapus file sumber lama
 * ✅ Update referensi di semua PHP/HTML (seluruh proyek)
 */

@set_time_limit(300);
@ini_set('memory_limit', '1G');

$root = __DIR__;
$folder = $_GET['folder'] ?? '';
$updateRefs = isset($_GET['update_refs']);

echo "<style>
body{font-family:Arial;padding:20px;line-height:1.6;}
h2{color:#0077cc;}
button{padding:10px 16px;background:#0077cc;color:white;border:none;border-radius:6px;cursor:pointer;}
button:hover{background:#005fa3;}
.ok{color:green;} .skip{color:orange;}
</style>";

function convertToWebP($source, $quality=85){
    $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
    $dest = preg_replace('/\.(jpe?g|png)$/i','.webp',$source);
    if(file_exists($dest)) return false;

    if($ext=='jpg'||$ext=='jpeg') $img=@imagecreatefromjpeg($source);
    elseif($ext=='png'){
        $img=@imagecreatefrompng($source);
        imagepalettetotruecolor($img);
        imagealphablending($img,true);
        imagesavealpha($img,true);
    } else return false;

    if(!$img) return false;
    $res=imagewebp($img,$dest,$quality);
    imagedestroy($img);
    if($res) unlink($source); // hapus file lama
    return $res;
}

function listFolders($dir, $base=''){
    $list = [];
    foreach(glob($dir.'/*') as $f){
        if(is_dir($f)){
            $rel = ltrim($base.'/'.basename($f),'/');
            $list[] = $rel;
            $list = array_merge($list, listFolders($f, $rel));
        }
    }
    return $list;
}

if(!$folder){
    echo "<h2>📂 Pilih Folder untuk Konversi WebP</h2>";
    $dirs = listFolders($root);
    echo "<form method='get'>";
    echo "<select name='folder'>";
    echo "<option value=''>-- pilih folder --</option>";
    foreach($dirs as $d){
        echo "<option value='$d'>$d/</option>";
    }
    echo "</select> ";
    echo "<button>Mulai Konversi</button>";
    echo "</form>";
    exit;
}

$target = $root.'/'.$folder;
if(!is_dir($target)){
    echo "<p>❌ Folder tidak ditemukan.</p>";
    exit;
}

echo "<h2>🚀 Proses Konversi Folder: <code>$folder</code></h2>";

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($target));
$files = [];
foreach($rii as $file){
    if($file->isFile() && preg_match('/\.(jpe?g|png)$/i',$file->getFilename())){
        $files[] = $file->getPathname();
    }
}

$total = count($files);
if($total === 0){
    echo "<p>📁 Tidak ada file JPG/PNG ditemukan di folder ini.</p>";
    echo "<a href='?'>🔙 Kembali</a>";
    exit;
}

$count = 0;
foreach($files as $file){
    if(convertToWebP($file)){
        echo "<span class='ok'>✅ ".basename($file)." dikonversi → .webp</span><br>";
        $count++;
    } else {
        echo "<span class='skip'>⏭️ ".basename($file)." dilewati</span><br>";
    }
    flush(); ob_flush();
}

echo "<p><b>Hasil:</b> $count dari $total file berhasil dikonversi.</p>";

echo "<hr><h3>🧹 Hapus Duplikat WebP</h3>";
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($target));
$seen = [];
foreach($rii as $file){
    if($file->isFile() && preg_match('/\.webp$/i',$file->getFilename())){
        $name = $file->getFilename();
        $path = $file->getPathname();
        if(isset($seen[$name])){
            unlink($path);
            echo "<span class='skip'>🗑️ Duplikat dihapus: $name</span><br>";
        } else {
            $seen[$name] = true;
        }
    }
}

echo "<hr><form method='get'>
<input type='hidden' name='folder' value='$folder'>
<input type='hidden' name='update_refs' value='1'>
<button>🔄 Update Referensi .webp di Semua File PHP/HTML</button>
</form>";

if($updateRefs){
    echo "<h3>🔍 Update Referensi Gambar di Semua File...</h3>";
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
    foreach($rii as $file){
        if($file->isFile() && preg_match('/\.(php|html)$/i',$file->getFilename())){
            $path = $file->getPathname();
            $content = file_get_contents($path);
            $new = preg_replace('/\.(jpg|jpeg|png)/i','.webp',$content);
            if($content !== $new){
                file_put_contents($path, $new);
                echo "<span class='ok'>✏️ Update: ".$file->getFilename()."</span><br>";
            }
        }
    }
    echo "<p><b>✅ Semua referensi diperbarui!</b></p>";
    echo "<a href='?'>🔙 Kembali</a>";
}
?>
