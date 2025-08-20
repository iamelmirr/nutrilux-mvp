# AUTO-COPY ZA LOCAL WP
# Kopira promijenjene fajlove direktno u Local WP folder (ako postoji)

$sourcePath = "C:\Users\Tarik Besirovic\Desktop\nutrilux mvp\theme-nutrilux"
$localWpPath = $null

# Pokušaj naći Local WP instalaciju
$possiblePaths = @(
    "C:\Users\Tarik Besirovic\Local Sites\nutrilux10\app\public\wp-content\themes\theme-nutrilux",
    "C:\Users\Tarik Besirovic\AppData\Local\Programs\Local\sites\nutrilux10\public\wp-content\themes\theme-nutrilux"
)

foreach ($path in $possiblePaths) {
    if (Test-Path $path) {
        $localWpPath = $path
        break
    }
}

if (-not $localWpPath) {
    Write-Host "❌ Local WP putanja nije pronađena!" -ForegroundColor Red
    Write-Host "Molim unesite putanju do theme-nutrilux foldera u Local WP:" -ForegroundColor Yellow
    $localWpPath = Read-Host "Putanja"
    
    if (-not (Test-Path $localWpPath)) {
        Write-Host "❌ Neispravna putanja!" -ForegroundColor Red
        exit
    }
}

Write-Host "=== AUTO-COPY ZA LOCAL WP ===" -ForegroundColor Green
Write-Host "📂 Source: $sourcePath" -ForegroundColor Cyan
Write-Host "📁 Target: $localWpPath" -ForegroundColor Yellow
Write-Host "🔄 Auto-kopiranje aktivno..." -ForegroundColor White
Write-Host ""

$watcher = New-Object System.IO.FileSystemWatcher
$watcher.Path = $sourcePath
$watcher.Filter = "*.*"
$watcher.IncludeSubdirectories = $true
$watcher.EnableRaisingEvents = $true

$action = {
    $path = $Event.SourceEventArgs.FullPath
    $name = $Event.SourceEventArgs.Name
    $changeType = $Event.SourceEventArgs.ChangeType
    
    if ($name -match '\.(css|js|php)$') {
        try {
            $relativePath = $path.Replace($sourcePath, "").TrimStart("\")
            $targetFile = Join-Path $localWpPath $relativePath
            $targetDir = Split-Path $targetFile -Parent
            
            if (-not (Test-Path $targetDir)) {
                New-Item -ItemType Directory -Path $targetDir -Force | Out-Null
            }
            
            Copy-Item $path $targetFile -Force
            
            $time = Get-Date -Format "HH:mm:ss"
            Write-Host "[$time] ✅ Kopiran: $relativePath" -ForegroundColor Green
            [Console]::Beep(800, 200)
        }
        catch {
            Write-Host "❌ Error copying $name : $($_.Exception.Message)" -ForegroundColor Red
        }
    }
}

Register-ObjectEvent -InputObject $watcher -EventName "Changed" -Action $action
Register-ObjectEvent -InputObject $watcher -EventName "Created" -Action $action

try {
    while ($true) { Start-Sleep 1 }
}
finally {
    $watcher.Dispose()
}
