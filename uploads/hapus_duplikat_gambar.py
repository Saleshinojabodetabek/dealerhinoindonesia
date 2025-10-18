import os
import hashlib
import re

# 📂 Folder target
folder_path = r"uploads/artikel/"

# Format gambar yang dicek
allowed_extensions = (".jpeg", ".jpg", ".webp")

# Dictionary untuk hash unik
hash_dict = {}

# Dictionary untuk prefix nama file
prefix_dict = {}

# List hasil penghapusan
deleted_files = []

def get_file_hash(file_path):
    """Buat hash MD5 dari isi file"""
    hash_md5 = hashlib.md5()
    with open(file_path, "rb") as f:
        for chunk in iter(lambda: f.read(4096), b""):
            hash_md5.update(chunk)
    return hash_md5.hexdigest()

def get_prefix(filename):
    """Ambil kode depan sebelum tanda '-' atau '_' atau '.'"""
    match = re.match(r"^([0-9a-zA-Z]+)", filename)
    return match.group(1) if match else filename

# 🔍 Telusuri semua file
for root, _, files in os.walk(folder_path):
    for file in files:
        if file.lower().endswith(allowed_extensions):
            full_path = os.path.join(root, file)
            ext = os.path.splitext(file)[1].lower()
            try:
                # 1️⃣ Screening berdasarkan isi file
                file_hash = get_file_hash(full_path)
                if file_hash in hash_dict:
                    existing_file = hash_dict[file_hash]
                    existing_ext = os.path.splitext(existing_file)[1].lower()

                    # Prioritas simpan webp
                    if ext == ".webp" and existing_ext != ".webp":
                        os.remove(existing_file)
                        deleted_files.append(existing_file)
                        hash_dict[file_hash] = full_path
                        print(f"🗑️ Duplikat isi (hash) → Hapus {existing_file}, simpan {file}")
                    elif ext != ".webp" and existing_ext == ".webp":
                        os.remove(full_path)
                        deleted_files.append(full_path)
                        print(f"🗑️ Duplikat isi (hash) → Hapus {file}, simpan {os.path.basename(existing_file)}")
                    elif ext == ".webp" and existing_ext == ".webp":
                        print(f"✅ Dua WEBP sama isi → biarkan {file} & {os.path.basename(existing_file)}")
                    else:
                        os.remove(full_path)
                        deleted_files.append(full_path)
                        print(f"🗑️ Duplikat isi (non-webp) → hapus {file}")
                    continue

                # 2️⃣ Screening berdasarkan prefix nama
                prefix = get_prefix(file)
                if prefix in prefix_dict:
                    existing_file = prefix_dict[prefix]
                    existing_ext = os.path.splitext(existing_file)[1].lower()

                    # Prioritas webp disimpan
                    if ext == ".webp" and existing_ext != ".webp":
                        os.remove(existing_file)
                        deleted_files.append(existing_file)
                        prefix_dict[prefix] = full_path
                        print(f"🗑️ Duplikat nama prefix → Hapus {existing_file}, simpan {file}")
                    elif ext != ".webp" and existing_ext == ".webp":
                        os.remove(full_path)
                        deleted_files.append(full_path)
                        print(f"🗑️ Duplikat nama prefix → Hapus {file}, simpan {os.path.basename(existing_file)}")
                    elif ext == ".webp" and existing_ext == ".webp":
                        print(f"✅ Dua WEBP prefix sama → biarkan {file} & {os.path.basename(existing_file)}")
                    else:
                        os.remove(full_path)
                        deleted_files.append(full_path)
                        print(f"🗑️ Duplikat nama prefix (non-webp) → hapus {file}")
                    continue

                # Kalau belum ada, simpan ke dict
                hash_dict[file_hash] = full_path
                prefix_dict[prefix] = full_path

            except Exception as e:
                print(f"⚠️ Gagal memproses {full_path}: {e}")

# 🔚 Ringkasan hasil
print("\n=====================================")
if deleted_files:
    print(f"Total file duplikat yang dihapus: {len(deleted_files)}")
else:
    print("Tidak ada file duplikat ditemukan.")
print("=====================================")
