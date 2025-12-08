$path = 'c:\Users\Ilhamstxr\Documents\laragon\www\htdocs\web-upt-ptkk-ui-overhaul\web-upt-ptkk\backupData\insert-data-berkala.sql'
$content = Get-Content $path -Raw
# Replace table names
$content = $content -replace '`kompetensi`', '`kompetensi`'
$content = $content -replace '`kompetensi_pelatihan`', '`kompetensi_pelatihan`'
# Replace column names
$content = $content -replace '`nama_kompetensi`', '`nama_kompetensi`'
$content = $content -replace '`kompetensi_id`', '`kompetensi_id`'
$content = $content -replace '`kompetensi_keahlian`', '`kompetensi_keahlian`'
$content = $content -replace '`kode_kompetensi_pelatihan`', '`kode_kompetensi_pelatihan`'
# Replace comments/text if easy (optional, but requested implicitly)
$content = $content -replace 'table `kompetensi`', 'table `kompetensi`'
$content = $content -replace 'table `kompetensi_pelatihan`', 'table `kompetensi_pelatihan`'

Set-Content -Path $path -Value $content
