
file_path = 'backupData/web-upt-ptkk-server.sql'
with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
    lines = f.readlines()
    
# Find index of insert into jawaban
start_idx = -1
for i, line in enumerate(lines):
    if "INSERT INTO `jawaban_user`" in line:
        start_idx = i
        break
        
if start_idx != -1:
    print(f"Found START at line {start_idx+1}")
    for j in range(start_idx, min(len(lines), start_idx + 10)):
        print(f"{j+1}: {lines[j].strip()}")
else:
    print("Not found.")
