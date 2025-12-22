
file_path = 'backupData/web-upt-ptkk-server.sql'
search_term = 'CREATE TABLE `percobaan`'

try:
    with open(file_path, 'r', encoding='utf-8') as f:
        for i, line in enumerate(f):
            if search_term in line:
                print(f"Found at line {i+1}:")
                print(line.strip())
                # Print next few lines to see the enum
                for j in range(20):
                    print(f.readline().strip())
                break
except Exception as e:
    print(f"Error: {e}")
