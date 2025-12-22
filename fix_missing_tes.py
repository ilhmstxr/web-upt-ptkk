
import os
import re

file_path = 'backupData/web-upt-ptkk-server.sql'

def fix_missing_tes():
    print(f"Scanning {file_path}...")
    
    with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
        
    # 1. Find existing Tes IDs
    existing_tes_ids = set()
    # Matches INSERT INTO `tes` VALUES (1, ...), (2, ...)
    # This is rough parsing, assuming standard mysqldump format
    tes_inserts = re.findall(r"INSERT INTO `?tes`?.*VALUES\s*(.*);", content, re.IGNORECASE | re.DOTALL)
    
    for block in tes_inserts:
        # Simple extraction of IDs (first number in parentheses)
        ids = re.findall(r"\((\d+),", block)
        existing_tes_ids.update(ids)
        
    print(f"Found {len(existing_tes_ids)} existing Tes IDs: {sorted(list(existing_tes_ids))}")
    
    # 2. Find referenced Tes IDs in 'percobaan' (specifically looking at the appended data or all data)
    # Pattern: INSERT INTO `percobaan` ... VALUES (peserta_id, pelatihan_id, tes_id, ...)
    # The structure in dump: `peserta_id`, `pelatihan_id`, `tes_id`
    # VALUES ('1', '3', '10', ...
    
    # Let's find the appended block first to avoid false positives or scanning huge file
    if "-- Data Jawaban User Transformasi" in content:
        appended_part = content.split("-- Data Jawaban User Transformasi")[1]
    else:
        print("Could not find appended block marker. Scanning whole file for percobaan inserts.")
        appended_part = content

    # Regex to capture pelatihan_id and tes_id from percobaan VALUES
    # VALUES ('1', '3', '10', ...
    # We need groups for pelatihan_id and tes_id.
    # Pattern depends on exact columns. In step 450:
    # (`peserta_id`, `pelatihan_id`, `tes_id`, ...) VALUES ('1', '3', '10', ...)
    
    referenced_tes = {} # tes_id -> pelatihan_id
    
    # Matches ('1', '3', '10',
    matches = re.findall(r"\('\d+',\s*'(\d+)',\s*'(\d+)',", appended_part)
    for pid, tid in matches:
        referenced_tes[tid] = pid
        
    print(f"Found {len(referenced_tes)} referenced Tes IDs in appended data: {referenced_tes.keys()}")
    
    # 3. Identify missing
    missing_tes = []
    for tid, pid in referenced_tes.items():
        if tid not in existing_tes_ids:
            missing_tes.append((tid, pid))
            
    if not missing_tes:
        print("No missing Tes IDs found.")
        return

    print(f"Found {len(missing_tes)} missing Tes IDs: {missing_tes}")
    
    # 4. Generate INSERTs
    new_inserts = []
    new_inserts.append("\n-- Injecting Missing Tes Records for Monev --\n")
    new_inserts.append("INSERT INTO `tes` (`id`, `judul`, `deskripsi`, `tipe`, `kompetensi_pelatihan_id`, `pelatihan_id`, `durasi_menit`, `created_at`, `updated_at`) VALUES")
    
    values_list = []
    for tid, pid in missing_tes:
        # id, judul, deskripsi, tipe, komp_id, pel_id, durasi, dates
        val = f"({tid}, 'Monev Import {tid}', 'Imported from legacy data', 'survei', NULL, {pid}, 0, NOW(), NOW())"
        values_list.append(val)
        
    new_inserts.append(",\n".join(values_list) + ";\n")
    
    insert_block = "".join(new_inserts)
    print("Generated Insert Block:")
    print(insert_block)
    
    # 5. Inject into file
    # We need to insert this BEFORE the foreign key usage. 
    # The easiest place is likely just before "-- Data Jawaban User Transformasi"
    
    if "-- Data Jawaban User Transformasi" in content:
        parts = content.split("-- Data Jawaban User Transformasi")
        new_content = parts[0] + insert_block + "-- Data Jawaban User Transformasi" + parts[1]
        
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print("Successfully injected missing Tes records.")
    else:
        print("Cannot find injection point ('-- Data Jawaban User Transformasi').")

if __name__ == "__main__":
    fix_missing_tes()
