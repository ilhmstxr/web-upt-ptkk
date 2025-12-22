
import os

target_file = 'backupData/web-upt-ptkk-server.sql'
source_file = 'backupData/data-jawaban-user-fix.sql'

def fix_file():
    print(f"Fixing {target_file}...")
    
    # 1. Read target file as binary
    with open(target_file, 'rb') as f:
        content = f.read()
        
    # 2. Find the start of corruption
    # The corruption is the UTF-16LE version of "START TRANSACTION;" (from data-jawaban-user-fix.sql startup)
    # "S" is \x53. In UTF-16LE: \x53\x00
    # Let's search for byte sequence corresponding to "START TRANSACTION" in UTF-16LE
    
    corrupt_start_marker = "START TRANSACTION".encode('utf-16-le')
    
    # It might also have a BOM \xff\xfe before it?
    # PowerShell `type >>` usually adds BOM if it thinks it's unicode?
    # Or just `\r\x00\n\x00` (newline) before it.
    
    # Let's verify if we can find the marker.
    offset = content.find(corrupt_start_marker)
    
    if offset == -1:
        print("Could not find UTF-16LE 'START TRANSACTION' marker. Searching for BOM...")
        offset = content.find(b'\xff\xfeS\x00')
        
    if offset != -1:
        print(f"Found corruption at offset {offset}. Truncating...")
        
        # We should check if there are a few bytes before this that are also corrupt (like newline)
        # Usually `type` appends a newline.
        # Check bytes immediately preceding offset
        # content[offset-4:offset] might be \r\n in ASCII/UTF-8 or \r\x00\n\x00
        
        # Truncate content
        clean_content = content[:offset]
        
        # Strip trailing newlines from clean content to be clean
        clean_content = clean_content.rstrip(b'\r\n')
        
        # Write back clean content
        with open(target_file, 'wb') as f:
            f.write(clean_content)
            # Add a clean newline separator
            f.write(b'\n\n')
            
        print("Truncated corrupted data.")
        
        # 3. Append correct data
        print(f"Appending valid data from {source_file}...")
        with open(source_file, 'rb') as f_src:
            src_data = f_src.read()
            
        with open(target_file, 'ab') as f_dst:
            f_dst.write(src_data)
            
        print("Append successful.")
        
    else:
        print("No corruption marker found. File might not be corrupted or marker is different.")
        # Check for simple START TRANSACTION in ASCII to see if it was appended correctly?
        marker_ascii = b"START TRANSACTION"
        if marker_ascii in content:
             print("Found ASCII 'START TRANSACTION'. File might be okay or check manually.")
             # Maybe check if there's ONLY one instance (the one we want)
             count = content.count(marker_ascii)
             print(f"Found {count} instances of 'START TRANSACTION' in ASCII.")

if __name__ == "__main__":
    if os.path.exists(target_file):
        fix_file()
    else:
        print("Target file not found.")
