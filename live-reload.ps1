# WordPress Live Reload Script
# Prati promjene u theme-nutrilux folderu i refreshuje browser

$watchPath = "C:\Users\Tarik Besirovic\Desktop\nutrilux mvp\theme-nutrilux"
$siteUrl = "http://nutrilux10.local"

Write-Host "🔥 Live Reload pokrenuo za: $siteUrl" -ForegroundColor Green
Write-Host "📁 Prati promjene u: $watchPath" -ForegroundColor Yellow
Write-Host "⏹️  Pritisni Ctrl+C za stop" -ForegroundColor Red

# Otvori site u browseru
Start-Process $siteUrl

# File System Watcher
$watcher = New-Object System.IO.FileSystemWatcher
$watcher.Path = $watchPath
$watcher.Filter = "*.*"
$watcher.IncludeSubdirectories = $true
$watcher.EnableRaisingEvents = $true

# Event handler za promjene
$action = {
    $path = $Event.SourceEventArgs.FullPath
    $name = $Event.SourceEventArgs.Name
    $changeType = $Event.SourceEventArgs.ChangeType
    
    # Filtriraj samo relevantne file tipove
    if ($name -match '\.(css|js|php)$') {
        $time = Get-Date -Format "HH:mm:ss"
        Write-Host "[$time] 🔄 $changeType: $name" -ForegroundColor Cyan
        
        # Pošalji refresh signal browseru (ako je moguće)
        try {
            # Alternativno: otvori novi tab
            # Start-Process $siteUrl
            
            # Ili prikaži notifikaciju
            Write-Host "🔄 Promjena detektovana - refresh browser manually" -ForegroundColor Green
        }
        catch {
            Write-Host "❌ Error: $($_.Exception.Message)" -ForegroundColor Red
        }
    }
}

# Registruj event handlers
Register-ObjectEvent -InputObject $watcher -EventName "Changed" -Action $action
Register-ObjectEvent -InputObject $watcher -EventName "Created" -Action $action
Register-ObjectEvent -InputObject $watcher -EventName "Deleted" -Action $action

try {
    # Drži script živ
    while ($true) {
        Start-Sleep 1
    }
}
finally {
    # Cleanup
    $watcher.Dispose()
    Write-Host "`n🛑 Live Reload zaustavljen" -ForegroundColor Red
}
