# Nutrilux Live Development Script
# Automatski kopira promjene u Local Sites

$sourcePath = ".\theme-nutrilux"
$destinationPath = "C:\Users\Tarik Besirovic\Local Sites\nutrilux10\app\public\wp-content\themes\theme-nutrilux"

Write-Host "🚀 Nutrilux Live Development - Watching for changes..." -ForegroundColor Green
Write-Host "Source: $sourcePath" -ForegroundColor Yellow
Write-Host "Destination: $destinationPath" -ForegroundColor Yellow
Write-Host "Press Ctrl+C to stop..." -ForegroundColor Red

# Kreiraj FileSystemWatcher
$watcher = New-Object System.IO.FileSystemWatcher
$watcher.Path = (Resolve-Path $sourcePath).Path
$watcher.IncludeSubdirectories = $true
$watcher.EnableRaisingEvents = $true

# Definiši akciju za promjene
$action = {
    $path = $Event.SourceEventArgs.FullPath
    $changeType = $Event.SourceEventArgs.ChangeType
    $fileName = Split-Path $path -Leaf
    
    # Ignoriši određene fajlove
    if ($fileName -match '\.(tmp|log|swp)$' -or $fileName -like '.*') {
        return
    }
    
    try {
        $relativePath = $path.Substring((Resolve-Path $sourcePath).Path.Length + 1)
        $destFile = Join-Path $destinationPath $relativePath
        $destDir = Split-Path $destFile -Parent
        
        # Kreiraj direktorij ako ne postoji
        if (!(Test-Path $destDir)) {
            New-Item -ItemType Directory -Path $destDir -Force | Out-Null
        }
        
        # Kopiraj fajl
        Copy-Item -Path $path -Destination $destFile -Force
        
        Write-Host "✅ Copied: $relativePath" -ForegroundColor Green
    }
    catch {
        Write-Host "❌ Error copying: $fileName - $($_.Exception.Message)" -ForegroundColor Red
    }
}

# Registruj event handler-e
Register-ObjectEvent -InputObject $watcher -EventName "Changed" -Action $action
Register-ObjectEvent -InputObject $watcher -EventName "Created" -Action $action
Register-ObjectEvent -InputObject $watcher -EventName "Renamed" -Action $action

# Drži script aktivnim
try {
    while ($true) {
        Start-Sleep 1
    }
}
finally {
    $watcher.Dispose()
    Write-Host "🛑 File watcher stopped." -ForegroundColor Yellow
}
