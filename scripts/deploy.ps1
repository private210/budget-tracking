param(
    [string]$OutputDir = "dist"
)

$root = Split-Path -Parent $PSScriptRoot
$dist = Join-Path $root $OutputDir

Write-Host "Preparing deployment to: $dist" -ForegroundColor Cyan

# Clean dist
if (Test-Path $dist) {
    Remove-Item -Path $dist -Recurse -Force
}

# Exclude patterns
$excludeDirs = @('.git', 'node_modules', 'public', 'tests', 'dist', 'scripts')
$excludeFiles = @('.env', '.env.example', '.gitignore',
    '.gitattributes', '.editorconfig', 'AGENTS.md', 'README.md',
    'package-lock.json', 'package.json', 'phpunit.xml', 'vite.config.js',
    '.phpunit.result.cache', '*.zip')

Write-Host "Copying files..." -ForegroundColor Cyan

# Copy all files and directories, excluding unwanted
Get-ChildItem -Path $root -Force | ForEach-Object {
    $name = $_.Name

    # Skip excluded directories
    if ($_.PSIsContainer -and ($name -in $excludeDirs)) { return }
    # Skip excluded files
    if (-not $_.PSIsContainer -and ($name -in $excludeFiles -or $name -like '*.zip')) { return }

    if ($_.PSIsContainer) {
        Copy-Item -Path $_.FullName -Destination (Join-Path $dist $name) -Recurse -Force
    } else {
        Copy-Item -Path $_.FullName -Destination $dist -Force
    }
}

# Copy public/build to root for direct asset access
if (Test-Path (Join-Path $root "public\build")) {
    $buildDist = Join-Path $dist "build"
    Copy-Item -Path (Join-Path $root "public\build") -Destination $buildDist -Recurse -Force
}

# Create modified index.php at root level
$indexContent = '<?php

use Illuminate\Http\Request;

define(' + "'LARAVEL_START'" + ', microtime(true));

if (file_exists(__DIR__.' + "'/storage/framework/maintenance.php'" + ')) {
    require __DIR__.' + "'/storage/framework/maintenance.php'" + ';
}

require __DIR__.' + "'/vendor/autoload.php'" + ';

(require_once __DIR__.' + "'/bootstrap/app.php'" + ')
    ->handleRequest(Request::capture());
'

Set-Content -Path (Join-Path $dist "index.php") -Value $indexContent -Encoding UTF8

# Copy .htaccess, favicon, robots from public/ to root
$publicRoot = Join-Path $root "public"
$publicFiles = @('.htaccess', 'favicon.ico', 'robots.txt')
foreach ($pf in $publicFiles) {
    $src = Join-Path $publicRoot $pf
    if (Test-Path $src) {
        Copy-Item -Path $src -Destination $dist -Force
    }
}

Write-Host ""
Write-Host "Deployment prepared in: $dist" -ForegroundColor Green
Write-Host ""

# Show size
$size = (Get-ChildItem -Path $dist -Recurse -File | Measure-Object -Property Length -Sum).Sum
Write-Host "Total size: $([math]::Round($size / 1MB, 1)) MB" -ForegroundColor Gray

Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "  1. Upload all files from '$dist' to InfinityFree htdocs/ via FTP or File Manager"
Write-Host "  2. Create MySQL database & user in InfinityFree cPanel"
Write-Host "  3. Rename .env.production to .env and fill in your DB credentials"
Write-Host "  4. Visit https://your-domain.com/migrate to run migrations"
Write-Host "  5. Delete the /migrate route from routes/web.php after done"
Write-Host ""
Write-Host "Note: .htaccess and index.php are now at root level - no need to set Document Root!" -ForegroundColor Cyan
Write-Host ""
