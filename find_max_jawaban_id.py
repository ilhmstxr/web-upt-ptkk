
import re

file_path = 'backupData/web-upt-ptkk-server.sql'

def find_max_id():
    max_id = 0
    print(f"Scanning {file_path} for max jawaban_user ID...")
    
    # We look for INSERT INTO `jawaban_user` ... VALUES (id, ...)
    # The format in the file might vary, but user pointed to L10253 which is:
    # INSERT INTO `jawaban_user` (...) VALUES \n (10133, ...
    
    with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
        
    # Find all matches of numbers at the start of a value block parenthesis
    # roughly `(\d+,` inside a jawaban_user insert block.
    # This is slightly expensive to parse perfectly, let's use a regex that catches likely IDs.
    
    # Strategy: Find "INSERT INTO `jawaban_user`" blocks, then scan numbers.
    # Assuming standard dump format: `(10133,` 
    
    matches = re.findall(r"\((\d+),\s*\d+,\s*\d+,\s*\d+,", content)
    
    if matches:
        ids = [int(m) for m in matches]
        max_id = max(ids)
        print(f"Max ID found: {max_id}")
    else:
        print("No IDs found with regex pattern `(\d+, \d+, \d+, \d+,`")
        # Try finding explicit VALUES header to see column order
        match_header = re.search(r"INSERT INTO `jawaban_user`\s*\((.*?)\)", content, re.IGNORECASE)
        if match_header:
            print(f"Columns found: {match_header.group(1)}")

if __name__ == "__main__":
    find_max_id()
