
file_path = 'backupData/web-upt-ptkk-server.sql'

found_tes = False
try:
    with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
        for line in f:
            if "INSERT INTO `tes`" in line or "INSERT INTO tes" in line:
                if "(10," in line or "('10'," in line:
                    print("Found Tes 10:")
                    print(line.strip())
                    found_tes = True
                    break
    
    if not found_tes:
        print("Tes 10 NOT found.")

except Exception as e:
    print(f"Error: {e}")
