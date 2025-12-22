import re
import os

input_file = r'c:\Users\Ilhamstxr\Documents\laragon\www\htdocs\web-upt-ptkk\backupData\data-mentah-monev.sql'
output_file = r'c:\Users\Ilhamstxr\Documents\laragon\www\htdocs\web-upt-ptkk\backupData\data-monev-fix.sql'

# Configuration
START_PERTANYAAN_ID = 1000
START_OPSI_ID = 5000

# Mapping based on nomor (1-based index)
# 1-16: pelayanan
# 17-27: fasilitas
# 28-37: instruktur
def get_metadata(nomor):
    kategori = 'pelayanan'
    if 17 <= nomor <= 27:
        kategori = 'fasilitas'
    elif 28 <= nomor <= 37:
        kategori = 'instruktur'
    
    # Check for teks_bebas (Pesan Dan Kesan, Intruktur terfavorit)
    # 16: Pesan Dan Kesan
    # 27: Pesan Dan Kesan
    # 36: Intruktur terfavorit
    # 37: Pesan dan Kesan
    if nomor in [16, 27, 36, 37]:
        tipe = 'teks_bebas'
    else:
        tipe = 'skala_likert'
        
    return kategori, tipe

def parse_sql_value_string(val_str):
    # Splits by comma but respects quotes
    # This is a simple parser assuming standard SQL dump format
    # Using regex to match values: 'string' or numbers
    pattern = re.compile(r"'(?:''|[^'])*'|[^,]+")
    raw_values = pattern.findall(val_str)
    
    cleaned_values = []
    for v in raw_values:
        v = v.strip()
        if v.startswith("'") and v.endswith("'"):
            # Remove framing quotes and unescape double quotes
            v = v[1:-1].replace("''", "'")
        cleaned_values.append(v)
    return cleaned_values

def main():
    try:
        with open(input_file, 'r', encoding='utf-8') as f:
            content = f.read()
    except Exception as e:
        print(f"Error reading file: {e}")
        return

    # Extract the VALUES part
    # Look for INSERT INTO ... VALUES
    # Then split by `),` to get rows
    
    match = re.search(r"INSERT INTO `data-mentah-monev`.*?VALUES\s*(.*);", content, re.DOTALL | re.IGNORECASE)
    if not match:
        print("No INSERT statement found.")
        return
    
    values_block = match.group(1)
    
    # Split rows. Be careful about `),` inside strings? 
    # Usually dumps have `),(\n` or `), (` 
    # Let's split by `),\n    (` or similar patterns, or just use a more robust split
    rows = re.split(r"\),\s*\(", values_block)
    
    # Clean up first and last row
    if rows:
        rows[0] = rows[0].lstrip('(').strip()
        rows[-1] = rows[-1].rstrip(')').strip()
    
    parsed_rows = []
    for r in rows:
        parsed_rows.append(parse_sql_value_string(r))

    # Group by (pelatihan_id, tes_id)
    # Structure: [tipe, pelatihan_id, tes_id, p1...p37]
    groups = {}
    
    for row in parsed_rows:
        if len(row) < 40: # 3 + 37 columns
            continue
            
        tipe = row[0]
        # pelatihan_id = row[1]
        tes_id = row[2]
        
        key = tes_id
        if key not in groups:
            groups[key] = {'pertanyaan': None, 'jawaban': []}
            
        if tipe.lower() == 'pertanyaan':
            groups[key]['pertanyaan'] = row
        elif tipe.lower() == 'jawaban':
            groups[key]['jawaban'].append(row)
            
    # Generate SQL
    sql_statements = []
    
    current_p_id = START_PERTANYAAN_ID
    current_o_id = START_OPSI_ID
    
    sql_statements.append("-- Data Pertanyaan dan Opsi Jawaban Monev Transformasi")
    sql_statements.append("-- Generated from data-mentah-monev.sql")
    sql_statements.append("")
    
    # Generate SQL with Batch Inserts
    sql_statements = []
    
    current_p_id = START_PERTANYAAN_ID
    current_o_id = START_OPSI_ID
    
    sql_statements.append("-- Data Pertanyaan dan Opsi Jawaban Monev Transformasi (Batch Insert)")
    sql_statements.append("-- Generated from data-mentah-monev.sql")
    sql_statements.append("")

    pertanyaan_values = []
    opsi_values = []
    
    for tes_id, data in groups.items():
        p_row = data['pertanyaan']
        j_rows = data['jawaban']
        
        if not p_row:
            print(f"Warning: No PERTANYAAN row for tes_id {tes_id}. Skipping.")
            continue
        
        # Iterate columns 1 to 37 (indices 3 to 39 in row)
        for i in range(37):
            idx = 3 + i
            nomor = i + 1
            
            question_text = p_row[idx]
            if not question_text:
                continue # Skip empty questions if any
                
            kategori, tipe_jawaban = get_metadata(nomor)
            
            # Prepare Pertanyaan Values
            q_sql_text = question_text.replace("'", "''")
            ts = "NOW()"
            
            p_val = f"({current_p_id}, {tes_id}, {nomor}, '{kategori}', '{q_sql_text}', '{tipe_jawaban}', {ts}, {ts})"
            pertanyaan_values.append(p_val)
            
            this_p_id = current_p_id
            current_p_id += 1
            
            # Prepare Opsi Jawaban Values
            if tipe_jawaban == 'teks_bebas':
                continue
                
            for j_row in j_rows:
                if idx < len(j_row):
                    ans_text = j_row[idx]
                    if not ans_text:
                        continue
                        
                    ans_sql_text = ans_text.replace("'", "''")
                    
                    o_val = f"({current_o_id}, {this_p_id}, '{ans_sql_text}', 0, {ts}, {ts})"
                    opsi_values.append(o_val)
                    current_o_id += 1
    
    # Write Batch Inserts
    if pertanyaan_values:
        sql_statements.append("INSERT INTO `pertanyaan` (`id`, `tes_id`, `nomor`, `kategori`, `teks_pertanyaan`, `tipe_jawaban`, `created_at`, `updated_at`) VALUES")
        sql_statements.append(",\n".join(pertanyaan_values) + ";")
        sql_statements.append("")

    if opsi_values:
        sql_statements.append("INSERT INTO `opsi_jawaban` (`id`, `pertanyaan_id`, `teks_opsi`, `apakah_benar`, `created_at`, `updated_at`) VALUES")
        sql_statements.append(",\n".join(opsi_values) + ";")
        sql_statements.append("")

    try:
        with open(output_file, 'w', encoding='utf-8') as f:
            f.write("\n".join(sql_statements))
        print(f"Successfully wrote to {output_file}")
    except Exception as e:
        print(f"Error writing output: {e}")

if __name__ == '__main__':
    main()
