
import re

file_path = 'backupData/web-upt-ptkk-server.sql'

try:
    with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
        
    # Search for create table percobaan
    # Case insensitive, flexible whitespace
    match = re.search(r'CREATE\s+TABLE\s+`?percobaan`?', content, re.IGNORECASE)
    
    if match:
        print(f"Found match at character offset {match.start()}")
        # Find line number
        line_num = content.count('\n', 0, match.start()) + 1
        print(f"Line number: {line_num}")
        
        # Extract the definition
        start = match.start()
        end = content.find(';', start)
        definition = content[start:end+1]
        print("Definition snippet:")
        print(definition[:200]) 
        
        # Find the enum line
        enum_match = re.search(r"enum\([^)]+\)", definition, re.IGNORECASE)
        if enum_match:
             print("Found enum definition:")
             print(enum_match.group(0))
    else:
        print("CREATE TABLE `percobaan` not found.")

except Exception as e:
    print(f"Error: {e}")
