
import re

file_path = 'backupData/web-upt-ptkk-server.sql'

start_percobaan_id = 1114
start_jawaban_id = 10300

def fix_sql_final():
    print(f"Reading {file_path}...")
    with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()

    # Find the block start
    if "-- Data Jawaban User Transformasi" not in content:
        print("Could not find start marker")
        return

    parts = content.split("-- Data Jawaban User Transformasi")
    header = parts[0] + "-- Data Jawaban User Transformasi"
    body = parts[1]

    new_body_lines = []
    
    current_perc_id = start_percobaan_id
    current_jaw_id = start_jawaban_id
    
    lines = body.splitlines(keepends=True)
    
    first_percobaan = True
    jawaban_count = 0
    
    for line in lines:
        
        # 1. PERCOBAAN
        if "INSERT INTO `percobaan`" in line:
            if not first_percobaan:
                current_perc_id += 1
            first_percobaan = False
            
            if "`id`" not in line.split("VALUES")[0]:
                 line = line.replace("(`peserta_id`", "(`id`, `peserta_id`")
            
            line = re.sub(r"VALUES\s*\(\s*'?\d+'?,", f"VALUES ({current_perc_id},", line)
            
            new_body_lines.append(line)
            
        # 2. SKIP SET @last
        elif "SET @last_percobaan_id" in line:
            continue
            
        # 3. JAWABAN USER Header
        elif "INSERT INTO `jawaban_user`" in line:
            new_header = "INSERT INTO `jawaban_user` (`id`, `opsi_jawaban_id`, `pertanyaan_id`, `percobaan_id`, `nilai_jawaban`, `jawaban_teks`, `skor`, `created_at`, `updated_at`) VALUES"
            if line.strip().endswith("VALUES"):
                new_body_lines.append(new_header + "\n")
            else:
                new_body_lines.append(new_header + "\n")

        # 4. JAWABAN USER Data Rows
        # Match lines starting with a number in parens: (10234, ...
        elif line.strip().startswith("("):
            
            # Regex to capture basic structure: 
            # (perc_id, pert_id, opsi_id, ...
            # We don't care what the perc_id is, we will overwrite it with `current_perc_id`
            
            m_start = re.match(r"\s*\(([^,]+),\s*(\d+),\s*([^,]+),", line)
            
            if m_start:
                old_perc_id = m_start.group(1) 
                pert_id = m_start.group(2)
                opsi_id = m_start.group(3)
                
                rest = line[m_start.end():]
                rest = rest.rstrip().rstrip(",").rstrip(";")
                rest = rest.rstrip(")") 
                
                # split last 3 commas: text, nilai, created, updated
                r_parts = rest.rsplit(",", 3)
                
                if len(r_parts) >= 4:
                    text_val = r_parts[0].strip()
                    nilai_val = r_parts[1].strip()
                    created_val = r_parts[2].strip()
                    updated_val = r_parts[3].strip()
                    
                    # Fix escaping in text_val
                    # It might be: 'Pak A'an' -> needs to be 'Pak A\'an'
                    if text_val != "NULL":
                        # Strip outer quotes if assumed
                        if text_val.startswith("'") and text_val.endswith("'"):
                            inner_text = text_val[1:-1]
                            # Escape single quotes inside
                            inner_text = inner_text.replace("'", "\\'") 
                            # Re-quote
                            text_val = f"'{inner_text}'"
                    
                    # Construct NEW format:
                    # (id, opsi, pert, perc, nilai, text, skor, created, updated)
                    
                    new_line = f"({current_jaw_id}, {opsi_id}, {pert_id}, {current_perc_id}, {nilai_val}, {text_val}, '0.00', {created_val}, {updated_val})"
                    
                    if line.strip().endswith(");"):
                        new_line += ");\n"
                    else:
                        new_line += "),\n"
                    
                    new_body_lines.append(new_line)
                    current_jaw_id += 1
                    jawaban_count += 1
                else:
                    new_body_lines.append(line)
            else:
                new_body_lines.append(line)
        else:
             new_body_lines.append(line)

    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(header + "".join(new_body_lines))
        
    print(f"Done. Percobaan matched: {current_perc_id - start_percobaan_id + 1}")
    print(f"Total Jawaban processed: {jawaban_count}")

if __name__ == "__main__":
    fix_sql_final()
