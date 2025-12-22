
file_path = 'backupData/web-upt-ptkk-server.sql'

with open(file_path, 'rb') as f:
    f.seek(0, 2)
    size = f.tell()
    # Read last 500 bytes
    f.seek(max(0, size - 500))
    content = f.read()

print(f"File size: {size}")
print("Last 500 bytes (repr):")
print(content)

# Check specifically for null bytes which indicate encoding mix-up
if b'\x00' in content:
    print("WARNING: Null bytes detected. Possible encoding corruption (UTF-16 vs UTF-8 mixed).")
else:
    print("Encoding looks clean (no null bytes in tail).")
