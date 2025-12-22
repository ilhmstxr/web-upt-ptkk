
source = 'backupData/mentah-jawaban-user-monev.sql'
search_term = 'Saya sangat terkesan'

with open(source, 'r', encoding='utf-8', errors='replace') as f:
    lines = f.readlines()

for i, line in enumerate(lines):
    if search_term in line:
        print(f"Found at line {i+1}:")
        # Print context
        start = max(0, i - 5)
        end = min(len(lines), i + 5)
        for j in range(start, end):
            print(f"{j+1}: {lines[j].strip().encode('utf-8')}")
        print("-" * 20)
