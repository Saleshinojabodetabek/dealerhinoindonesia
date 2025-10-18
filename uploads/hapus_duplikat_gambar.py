import os
import hashlib

# 📂 Folder target
folder_path = r"uploads/artikel/"

# Format gambar yang dicek
allowed_extensions = (".jpeg", ".jpg", ".webp")

# Dictionary hash → file unik
hash_dict = {}

# List hasil penghapusan
deleted_files = []

def get_file_hash(file_path):
    """Buat hash MD5 dari isi file"""
    hash_md5 = hashlib.md5()
    with open(file_path, "rb") as f:
        for chunk in iter(lambda: f.read(4096), b""):
            hash_md5.update(chunk)
    return hash_md5.hexdigest()

# Telusuri semua file dalam folder
for root, _, files in os.walk(folder_path):
    for file in files:
        if file.lower().endswith(allowed_extensions):
            full_path = os.path.join(root, file)
            try:
                file_hash = get_file_hash(full_path)
                ext = os.path.splitext(file)[1].lower()

                if file_hash in hash_dict:
                    existing_file = hash_dict[file_hash]
                    existing_ext = os.path.splitext(existing_file)[1].lower()

                    # Logika keputusan hapus
                    if ext == ".webp" and existing_ext == ".webp":
                        # Dua-duanya webp → biarkan
                        print(f"✅ Duplikat WEBP dibiarkan: {file} & {os.path.basename(existing_file)}")
                        continue
                    elif ext == ".webp" and existing_ext != ".webp":
                        # Ganti file lama (non-webp) dengan webp
                        os.remove(existing_file)
                        deleted_files.append(existing_file)
                        hash_dict[file_hash] = full_path
                        print(f"🗑️ Hapus {existing_file}, simpan {file}")
                    elif ext != ".webp" and existing_ext == ".webp":
                        # Simpan webp, hapus yang sekarang
                        os.remove(full_path)
                        deleted_files.append(full_path)
                        print(f"🗑️ Hapus {file}, simpan {os.path.basename(existing_file)}")
                    else:
                        # Keduanya non-webp → hapus duplikat baru
                        os.remove(full_path)
                        deleted_files.append(full_path)
                        print(f"🗑️ Hapus duplikat non-webp: {file}")
                else:
                    hash_dict[file_hash] = full_path

            except Exception as e:
                print(f"⚠️ Gagal memproses {full_path}: {e}")

# 🔚 Ringkasan hasil
print("\n=====================================")
if deleted_files:
    print(f"Total file duplikat yang dihapus: {len(deleted_files)}")
else:
    print("Tidak ada file duplikat ditemukan.")
print("=====================================")
