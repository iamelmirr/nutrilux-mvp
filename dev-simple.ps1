# NUTRILUX DEV HELPER - Simple
$watchPath = "C:\Users\Tarik Besirovic\Desktop\nutrilux mvp\theme-nutrilux"
$siteUrl = "http://nutrilux10.local"

Write-Host "=== NUTRILUX DEV HELPER ===" -ForegroundColor Yellow
Write-Host "Prati: $watchPath" -ForegroundColor Cyan
Write-Host "Site: $siteUrl" -ForegroundColor Green
Write-Host "KADA VIDIŠ PROMJENU -> Pritisni F5 u browseru" -ForegroundColor Red
Write-Host "Pritisni Ctrl+C za stop" -ForegroundColor White
Write-Host ""

# Otvori site odmah
Start-Process $siteUrl

$watcher = New-Object System.IO.FileSystemWatcher
$watcher.Path = $watchPath
$watcher.Filter = "*.*"
$watcher.IncludeSubdirectories = $true
$watcher.EnableRaisingEvents = $true

$action = {
    $name = $Event.SourceEventArgs.Name
    $changeType = $Event.SourceEventArgs.ChangeType
    
    if ($name -match "\.css$|\.js$|\.php$") {
        $time = Get-Date -Format "HH:mm:ss"
        Write-Host "[$time] PROMJENA: $changeType - $name" -ForegroundColor Yellow -BackgroundColor DarkRed
        Write-Host "         REFRESH BROWSER (F5) SADA!" -ForegroundColor White -BackgroundColor Red
        
        # Glasni signal
        [Console]::Beep(1000, 300)
        [Console]::Beep(800, 300)
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
