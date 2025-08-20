# Simple File Watcher - Notifikacija kada se file promijeni
# Koristi sa browser extension za auto-refresh

$watchPath = "C:\Users\Tarik Besirovic\Desktop\nutrilux mvp\theme-nutrilux"

Write-Host "WordPress Theme Watcher" -ForegroundColor Green
Write-Host "Prati: $watchPath" -ForegroundColor Yellow
Write-Host "Tip: Live reload je aktivan u WordPress-u" -ForegroundColor Cyan
Write-Host "Pritisni Ctrl+C za stop" -ForegroundColor Red
Write-Host ""

$watcher = New-Object System.IO.FileSystemWatcher
$watcher.Path = $watchPath
$watcher.Filter = "*.*"
$watcher.IncludeSubdirectories = $true
$watcher.EnableRaisingEvents = $true

$action = {
    $name = $Event.SourceEventArgs.Name
    $changeType = $Event.SourceEventArgs.ChangeType
    
    if ($name -match '\.(css|js|php)$') {
        $time = Get-Date -Format "HH:mm:ss"
        Write-Host "[$time] Promjena: $changeType - $name" -ForegroundColor Green
        
        # Zvukovni signal
        [Console]::Beep(800, 200)
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
