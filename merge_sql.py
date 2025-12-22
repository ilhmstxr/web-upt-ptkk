import os

server_sql_path = r'c:\Users\Ilhamstxr\Documents\laragon\www\htdocs\web-upt-ptkk\backupData\web-upt-ptkk-server.sql'
new_data_sql_path = r'c:\Users\Ilhamstxr\Documents\laragon\www\htdocs\web-upt-ptkk\backupData\data-monev-fix.sql'

def main():
    try:
        with open(server_sql_path, 'r', encoding='utf-8') as f:
            server_content = f.read()
            
        with open(new_data_sql_path, 'r', encoding='utf-8') as f:
            new_data_content = f.read()
            
    except UnicodeDecodeError:
        # Fallback to latin-1 if utf-8 fails
        try:
            with open(server_sql_path, 'r', encoding='latin-1') as f:
                server_content = f.read()
            with open(new_data_sql_path, 'r', encoding='utf-8') as f:
                new_data_content = f.read()
        except Exception as e:
            print(f"Error reading files: {e}")
            return
    except Exception as e:
        print(f"Error reading files: {e}")
        return

    # Extract distinct parts from new data
    # We know data-monev-fix.sql has INSERT INTO `pertanyaan` ... and INSERT INTO `opsi_jawaban` ...
    
    # We will try to allow appending after existing INSERTs in server_content
    # Find position of `INSERT INTO `pertanyaan``
    
    # Strategy:
    # 1. Find the last occurrence of `INSERT INTO `pertanyaan`` in server_content.
    # 2. Find the end of that statement (semicolon).
    # 3. Append new questions there.
    # 4. Repeat for `opsi_jawaban`.
    
    # Check if perturbation existing
    pertanyaan_found = False
    opsi_found = False
    
    updated_content = server_content
    
    if "INSERT INTO `pertanyaan`" in updated_content:
        print("Found existing pertanyaan table inserts. Using append strategy.")
        # Find the insertion point: search for UNLOCK TABLES after the insert block is safer for mysqldump
        # Standard block:
        # LOCK TABLES `pertanyaan` WRITE;
        # INSERT INTO ...;
        # UNLOCK TABLES;
        
        lock_str = "LOCK TABLES `pertanyaan` WRITE;"
        unlock_str = "UNLOCK TABLES;"
        
        # It's safer to just append the new INSERT statement after the existing one within the file.
        # But mysqldumps usually have one huge INSERT statement.
        # If we just append a new INSERT statement after the existing one (and before UNLOCK TABLES if present), it works.
        
        # Let's find "LOCK TABLES `pertanyaan` WRITE;"
        lock_idx = updated_content.find(lock_str)
        if lock_idx != -1:
            # Find the corresponding UNLOCK TABLES
            unlock_idx = updated_content.find(unlock_str, lock_idx)
            if unlock_idx != -1:
                # Insert BEFORE unlock
                # Get the new questions part from `new_data_content`
                # Identifying parts is hard unless we assume new_data_content structure is simple
                # Let's just dump the *whole* new content at the end of the file if structure is complex, 
                # OR, try to insert it nicely.
                
                # Given the user request "masukkan kedalam ...", appending to the file is technically "putting it in".
                # Placing it near the other data is nicer.
                
                # Let's just insert the whole new content *after* the `UNLOCK TABLES` of the last relevant table, or just at the end of the file.
                # Actually, putting it at the end of the file is safest regarding syntax errors. 
                # But if we want to be clean, we put it after `pertanyaan` block.
                pass
    
    # Simpler Approach:
    # Just append the new data to the end of the file.
    # SQL allows multiple INSERTs. Order matters for FK, but `opsi_jawaban` references `pertanyaan` which references `tes`.
    # As long as `tes` exists (which it does in server), appending at end is fine.
    # Also `pertanyaan` needs to exist before `opsi_jawaban`. The new file has them in correct order.
    
    print("Appending new data to the end of web-upt-ptkk-server.sql...")
    
    # Add a header
    append_str = "\n\n-- APPENDED DATA FROM data-monev-fix.sql --\n"
    append_str += new_data_content
    append_str += "\n-- END APPENDED DATA --\n"
    
    final_content = server_content + append_str
    
    try:
        with open(server_sql_path, 'w', encoding='utf-8') as f:
            f.write(final_content)
        print(f"Successfully appended to {server_sql_path}")
    except Exception as e:
        print(f"Error writing file: {e}")

if __name__ == '__main__':
    main()
