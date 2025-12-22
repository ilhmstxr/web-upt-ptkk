
import re

file_path = 'backupData/web-upt-ptkk-server.sql'
start_id = 10234

def fix_percobaan_ids():
    print(f"Reading {file_path}...")
    with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()

    if "-- Data Jawaban User Transformasi" not in content:
        print("Could not find start marker '-- Data Jawaban User Transformasi'")
        return

    # Split into parts to only edit the end
    parts = content.split("-- Data Jawaban User Transformasi")
    header = parts[0] + "-- Data Jawaban User Transformasi"
    body = parts[1]

    current_id = start_id
    new_body_lines = []
    
    # We will process line by line
    lines = body.splitlines(keepends=True)
    
    for line in lines:
        # 1. Handle Percobaan Insert
        if "INSERT INTO `percobaan`" in line:
            # Add `id` to column list
            # Look for (`peserta_id`
            line = line.replace("(`peserta_id`", "(`id`, `peserta_id`")
            
            # Add explicit ID to values
            # VALUES ('1',
            # Careful with regex, assume standard format from my previous transformation
            # Replace VALUES ( with VALUES (10234, 
            line = re.sub(r"VALUES\s*\(", f"VALUES ({current_id}, ", line)
            
            new_body_lines.append(line)
            # Don't increment yet, we need this ID for subsequent jawaban_user rows
            
        # 2. Handle SET @last...
        elif "SET @last_percobaan_id = LAST_INSERT_ID();" in line:
            # Skip this line entirely
            continue
            
        # 3. Handle Jawaban User Insert
        elif "INSERT INTO `jawaban_user`" in line:
            # Replace @last_percobaan_id with current_id
            line = line.replace("@last_percobaan_id", str(current_id))
            new_body_lines.append(line)
            
        # 4. Handle continuation lines for multi-value inserts? 
        # My generator made separate INSERTs or lines starting with ( ? 
        # In step 450 view: 
        # 17250: INSERT INTO ... VALUES (@last_percobaan_id, ...),
        # 17251: (@last_percobaan_id, ...),
        
        elif "@last_percobaan_id" in line:
             line = line.replace("@last_percobaan_id", str(current_id))
             new_body_lines.append(line)
             
        # 5. Detect end of a block?
        # My previous script might have used one user per block?
        # The IDs need to increment for the NEXT percobaan insert.
        # So we increment AFTER processing a full block?
        # Actually simplest is to increment whenever we see the PERCOBAAN insert? 
        # No, we set `current_id` when we see `INSERT INTO percobaan`, and verify it's used until the next one.
        
        else:
            new_body_lines.append(line)

        # Increment logic: 
        # If we just processed the `INSERT INTO percobaan` line, we used `current_id`.
        # When do we switch to `current_id + 1`?
        # Only when we encounter the NEXT `INSERT INTO percobaan`.
        # So wait, I should increment current_id at the START of processing a `percobaan` line?
        # Yes.
    
    # Re-logic the loop to be safer
    final_lines = []
    
    # Reset to start_id - 1 so first increment hits start_id
    current_cnt = start_id 
    
    for line in lines:
        if "INSERT INTO `percobaan`" in line:
            # Found a new header, use this ID
            # Modify line
            line = line.replace("(`peserta_id`", "(`id`, `peserta_id`")
            line = re.sub(r"VALUES\s*\(", f"VALUES ({current_cnt}, ", line)
            final_lines.append(line)
            
        elif "SET @last_percobaan_id = LAST_INSERT_ID();" in line:
            continue
            
        elif "@last_percobaan_id" in line:
            line = line.replace("@last_percobaan_id", str(current_cnt))
            final_lines.append(line)
            
            # Check if this line ENDS the statement with semicolon?
            # If so, we are done with this user, prepare for next
            if line.strip().endswith(");"):
                 # But wait, does the next block start immediately?
                 # Yes. 
                 # Wait, looking at file content Step 450:
                 # 17296:  Kesan : Mas Aan the best!!', 0, NOW(), NOW());
                 # 17297: -- Processing Peserta 2 for Tes 10
                 # 17298: INSERT INTO `percobaan` ...
                 
                 # The 'percobaan' insert is the trigger.
                 # So actually, I need to increment *after* the previous block finishes?
                 # Or just increment *implicitly* by just using a counter when I hit `INSERT INTO percobaan`
                 pass
        
        else:
            final_lines.append(line)
            
        # Optimization: verify if we need to pre-increment or post-increment
        # If I see "INSERT INTO percobaan", that STARTS a new ID.
        if "INSERT INTO `percobaan`" in line:
            # Prepare next ID for the *next* loop iteration? 
            # No, the current one is already used.
            current_cnt += 1 

    # Wait, my logic above:
    # 1. Init current_cnt = 10234
    # 2. Line 1: INSERT INTO percobaan... -> changes VALUES to (10234, ...
    # 3. Line 1: current_cnt += 1 -> becomes 10235
    # 4. Line 2: SET ... -> skipped
    # 5. Line 3: INSERT INTO jawaban ... values (@last...) -> replaced with 10235 ?? WRONG.
    
    # Fix logic:
    
    final_lines = []
    current_cnt = start_id
    
    for line in lines:
        if "INSERT INTO `percobaan`" in line:
            # Apply ID
            line = line.replace("(`peserta_id`", "(`id`, `peserta_id`")
            line = re.sub(r"VALUES\s*\(", f"VALUES ({current_cnt}, ", line)
            final_lines.append(line)
            # Do NOT increment yet
            
        elif "SET @last_percobaan_id = LAST_INSERT_ID();" in line:
            continue
            
        elif "@last_percobaan_id" in line:
            # Use SAME ID
            line = line.replace("@last_percobaan_id", str(current_cnt))
            final_lines.append(line)
            
            # If this is the end of the block (semi-colon at end of statement), THEN we are ready for next?
            # Actually, simply checking if the Next line is the `INSERT INTO percobaan` is cleaner?
            # But we process line by line.
            
            # Better: Just increment `current_cnt` *AT THE END* of the `percobaan` insert processing IF AND ONLY IF we know we are done? 
            # No, `jawaban_user` depend on it.
            
            # Correct Logic:
            # When we see `INSERT INTO percobaan`, we use `current_cnt`.
            # We CONTINUE using `current_cnt` for all subsequent lines until we see ANOTHER `INSERT INTO percobaan`.
            # So, only increment `current_cnt` right BEFORE processing a *new* `percobaan` line (except the first one).
            pass
            
        else:
            final_lines.append(line)

    # Re-Re-Logic
    
    lines = body.splitlines(keepends=True)
    final_lines = []
    current_cnt = start_id
    first_percobaan = True
    
    for line in lines:
        if "INSERT INTO `percobaan`" in line:
            if not first_percobaan:
                current_cnt += 1
            first_percobaan = False
            
            line = line.replace("(`peserta_id`", "(`id`, `peserta_id`")
            line = re.sub(r"VALUES\s*\(", f"VALUES ({current_cnt}, ", line)
            final_lines.append(line)
            
        elif "SET @last_percobaan_id = LAST_INSERT_ID();" in line:
            continue
            
        elif "@last_percobaan_id" in line:
             line = line.replace("@last_percobaan_id", str(current_cnt))
             final_lines.append(line)
        else:
            final_lines.append(line)
            
    print(f"Processed {current_cnt - start_id + 1} users.")
    
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(header + "".join(final_lines))
        
    print("Done rewriting IDs.")

if __name__ == "__main__":
    fix_percobaan_ids()
