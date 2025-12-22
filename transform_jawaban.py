
import re
import os

INPUT_MONEV_FIX = 'backupData/data-monev-fix.sql'
INPUT_RAW_USER = 'backupData/mentah-jawaban-user-monev.sql'
OUTPUT_FILE = 'backupData/data-jawaban-user-fix.sql'

def parse_monev_metadata():
    questions = {} # tes_id -> list of (pertanyaan_id, tipe_jawaban)
    options = {}   # pertanyaan_id -> {text_lower: opsi_id}
    
    current_table = None
    
    with open(INPUT_MONEV_FIX, 'r', encoding='utf-8') as f:
        content = f.read()

    # Parse Pertanyaan
    # Syntax: (id, tes_id, nomor, kategori, teks_pertanyaan, tipe_jawaban, ...)
    # Example: (1000, 10, 1, 'pelayanan', '...', 'skala_likert', ...)
    # Regex roughly: \((\d+),\s*(\d+),\s*(\d+),\s*'[^']*',\s*'[^']*',\s*'([^']*)'
    
    pertanyaan_matches = re.finditer(r"\((\d+),\s*(\d+),\s*\d+,\s*'[^']*',\s*['\"][^'\"]*['\"],\s*'([^']*)'", content)
    
    for m in pertanyaan_matches:
        p_id = int(m.group(1))
        t_id = int(m.group(2))
        tipe = m.group(3)
        
        if t_id not in questions:
            questions[t_id] = []
        questions[t_id].append((p_id, tipe))
    
    # Sort questions by ID (assuming ID order == Question Number order)
    for t_id in questions:
        questions[t_id].sort(key=lambda x: x[0])

    # Parse Opsi Jawaban
    # Syntax: (id, pertanyaan_id, teks_opsi, ...)
    # Example: (5177, 1048, 'Kurang memuaskan', 0, ...)
    # Regex: \((\d+),\s*(\d+),\s*'([^']*)'
    
    # We need to be careful with quotes in text.
    # Assuming standard SQL dumps use ' to escape.
    
    opsi_matches = re.finditer(r"\((\d+),\s*(\d+),\s*'([^']*)'", content)
    
    for m in opsi_matches:
        o_id = int(m.group(1))
        p_id = int(m.group(2))
        text = m.group(3)
        
        if p_id not in options:
            options[p_id] = {}
        
        options[p_id][text.lower()] = o_id
        
        # Also handle mapped values like "25%" if needed, but text usually matches.
        
    print(f"Loaded {sum(len(q) for q in questions.values())} questions for {len(questions)} tests.")
    print(f"Loaded {sum(len(o) for o in options.values())} options.")
    
    return questions, options

def parse_raw_responses(questions, options):
    sql_statements = []
    
    # Start Transaction
    sql_statements.append("START TRANSACTION;")
    sql_statements.append("-- Data Jawaban User Transformasi")
    
    with open(INPUT_RAW_USER, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # Extract VALUES (...)
    # The file has INSERT INTO `data-jawaban-user` ... VALUES ...
    # We might have multiple values or one large insert.
    # The example showed VALUES (...), (...);
    
    # We can try to regex extract the values content within parens.
    # But values contain strings like 'Pesan :\n ...' which are multi-line.
    # Regex might trigger issues.
    # Safer to iterate manually or regex with DOTALL.
    
    # Pattern: \('(\d+)',\s*'(\d+)',\s*'(\d+)',\s*'(\d+)',\s*(.*)\)
    # The logic is: (pelatihan_id, tes_id, bidang_id, foreign_id, jaw1, jaw2, ... jaw37)
    
    # Let's find the VALUES part.
    values_start = content.find("VALUES")
    if values_start == -1:
        print("No VALUES clause found in raw data.")
        return []
        
    values_data = content[values_start + 6:].strip()
    if values_data.endswith(';'):
        values_data = values_data[:-1]
    
    # Split by `),(` carefully.
    # Since we have text fields that might contain parantheses, splitting by `),` is risky.
    # However, standard SQL dumps usually structure it cleanly.
    # Let's write a simple parser or use regex with verbose.
    
    # The raw format: ('val', 'val', ...), ('val', ...), ...
    # Each tuple corresponds to one user submission.
    
    # Regex to capture one row:
    # \((?:[^)(]+|(?<=\\)\)|(?<=').*(?='))*\) -- too complex.
    
    # Let's try splitting by `),\n` or `),`. 
    # But first, let's look at the structure again.
    # The answers are quoted strings `'...'`.
    
    # Best approach: Use python's string parsing to walk through content.
    
    rows = []
    buffer = ""
    in_quote = False
    quote_char = "'"
    escape = False
    
    for char in values_data:
        if escape:
            buffer += char
            escape = False
            continue
            
        if char == '\\':
            buffer += char
            escape = True
            continue
            
        if char == quote_char:
            in_quote = not in_quote
            buffer += char
            continue
            
        if char == ',' and not in_quote:
            # Check if previous char was ')' and next is '(' or just end of row tuple?
            # Wait, this splits FIELDS, not rows.
            # We want to split ROWS. Row delimiter is `),(` usually or `),\n(`.
            buffer += char
        elif char == ')' and not in_quote:
             # Potential end of row
             buffer += char
             # Look ahead?
             # Actually, just accumulating is fine.
             # If we hit `),` followed by `(` or whitespace, it's a split.
             pass
        else:
             buffer += char
             
    # This manual parsing is tedious. 
    # Robust parser using state machine
    print("Parsing rows using state machine...")
    
    rows = []
    buffer = []
    in_string = False
    escape = False
    paren_depth = 0
    
    # Skip "INSERT INTO ... VALUES " info, start from the first parenthesis
    # We already extracted values_data which starts at 'VALUES' + 6
    # It should look like: "('3', ...), ('...', ...);"
    # Stripping whitespace
    s = values_data.strip()
    
    for i, char in enumerate(s):
        if escape:
            buffer.append(char)
            escape = False
            continue
            
        if char == '\\':
            buffer.append(char)
            # escape = True # SQL strings standardly use '' to escape ', but \ might be used too.
            # In standard MySQL dump, \' is used.
            escape = True
            continue
            
        if char == "'" and not escape:
            in_string = not in_string
            buffer.append(char)
            continue
            
        if in_string:
            buffer.append(char)
            continue
            
        # Not in string
        if char == '(':
            paren_depth += 1
            buffer.append(char)
        elif char == ')':
            paren_depth -= 1
            buffer.append(char)
        elif char == ',' and paren_depth == 0:
            # We found a row separator!
            # The buffer contains something like "(val1, val2)" or "\n(val1, val2)"
            # Let's clean it and add to rows
            row_raw = "".join(buffer).strip()
            if row_raw:
                if row_raw.startswith(','): row_raw = row_raw[1:].strip() # Should not happen if logic is right
                rows.append(row_raw)
            buffer = []
        elif char == ';':
            # End of statement
             pass
        else:
            buffer.append(char)
            
    # Add last row if buffer has content
    last_row = "".join(buffer).strip()
    if last_row and last_row != ";":
        # Remove trailing ; if present
        if last_row.endswith(';'): last_row = last_row[:-1]
        rows.append(last_row)

    print(f"Found {len(rows)} rows to process.")
    
    raw_rows = rows
    
    for row_str in raw_rows:
        # Strip outer parens
        row_str = row_str.strip()
        if row_str.startswith('('): row_str = row_str[1:]
        if row_str.endswith(')'): row_str = row_str[:-1]
        
        fields = []
        # Re-use the regex for fields inside the row, because commas inside string are protected by quotes
        # But wait, regex `field_matches` logic I wrote before should be fine if row_str is ONE row.
        # But if the row contains `NULL`, my regex handles it now.
        
        field_matches = re.finditer(r"(?:'((?:[^'\\]|\\.)*)'|([0-9\.]+)|(NULL))", row_str, re.IGNORECASE)
        for fm in field_matches:
            if fm.group(1) is not None:
                fields.append(fm.group(1)) # Quoted string content
            elif fm.group(2) is not None:
                fields.append(fm.group(2)) # Number
            elif fm.group(3) is not None:
                 fields.append("NULL")

        if len(fields) < 4:
            print(f"Skipping malformed row (len={len(fields)}): {row_str[:150]}")
            continue
            
        # Validate pelatihan_id
        if not re.match(r'^\d+$', fields[0]):
             print(f"Skipping row with invalid pelatihan_id: {fields[0][:50]}...")
             continue

        try:
            pelatihan_id = fields[0]
            tes_id = int(fields[1])
            bidang_id = fields[2]
            foreign_id = fields[3] # peserta_id
        except ValueError as e:
            print(f"Error parsing fields in row index {raw_rows.index(row_str)}: {e}")
            print(f"Fields: {fields[:5]}")
            continue
        
        answers = fields[4:]
        
        # Validation
        if tes_id not in questions:
            print(f"Warning: tes_id {tes_id} not found in metadata. Skipping row.")
            continue
            
        q_list = questions[tes_id]
        
        # Generate Percobaan Insert
        # Assuming foreign_id is PESERTA_ID
        
        sql_statements.append(f"-- Processing Peserta {foreign_id} for Tes {tes_id}")
        sql_statements.append(f"INSERT INTO `percobaan` (`peserta_id`, `pelatihan_id`, `tes_id`, `tipe`, `waktu_mulai`, `waktu_selesai`, `lulus`, `is_legacy`, `created_at`, `updated_at`) VALUES ('{foreign_id}', '{pelatihan_id}', '{tes_id}', 'Monev', NOW(), NOW(), 1, 1, NOW(), NOW());")
        
        sql_statements.append("SET @last_percobaan_id = LAST_INSERT_ID();")
        
        # Generate JawabanUser Inserts
        jawaban_values = []
        
        for idx, ans_text in enumerate(answers):
            if idx >= len(q_list):
                break
                
            p_id, p_tipe = q_list[idx]
            
            opsi_id = "NULL"
            jawaban_teks = "NULL"
            
            # Logic to determine opsi vs text
            # Clean ans_text
            clean_ans = ans_text.strip()
            clean_ans_lower = clean_ans.lower()
            
            # Check options first
            if p_id in options and clean_ans_lower in options[p_id]:
                opsi_id = str(options[p_id][clean_ans_lower])
            elif p_tipe == 'teks_bebas':
                # Explicit text field
                jawaban_teks = f"'{clean_ans.replace(chr(39), chr(92)+chr(39))}'" # Escape single quotes
            else:
                # Fallback: expected option but not found.
                # Could be a slightly different spelling or actually a text answer where logic failed.
                # Or maybe user typed something else.
                # Put in text validly.
                jawaban_teks = f"'{clean_ans.replace(chr(39), chr(92)+chr(39))}'"
                
            jawaban_values.append(f"(@last_percobaan_id, {p_id}, {opsi_id}, {jawaban_teks}, 0, NOW(), NOW())")
    
        if jawaban_values:
            stmt = "INSERT INTO `jawaban_user` (`percobaan_id`, `pertanyaan_id`, `opsi_jawaban_id`, `jawaban_teks`, `nilai_jawaban`, `created_at`, `updated_at`) VALUES " + ",\n".join(jawaban_values) + ";"
            sql_statements.append(stmt)
            
    sql_statements.append("COMMIT;")
    
    with open(OUTPUT_FILE, 'w', encoding='utf-8') as f:
        f.write("\n".join(sql_statements))
        
    print(f"Done. Generated {OUTPUT_FILE}")

if __name__ == "__main__":
    if not os.path.exists(INPUT_MONEV_FIX):
        print(f"Error: {INPUT_MONEV_FIX} not found.")
    elif not os.path.exists(INPUT_RAW_USER):
        print(f"Error: {INPUT_RAW_USER} not found.")
    else:
        qs, ops = parse_monev_metadata()
        parse_raw_responses(qs, ops)
