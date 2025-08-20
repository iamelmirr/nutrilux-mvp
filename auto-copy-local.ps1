# AUTO-COPY TO LOCAL WP
# Kopira promjene iz development foldera direktno u Local WP

$sourcePath = "C:\Users\Tarik Besirovic\Desktop\nutrilux mvp\theme-nutrilux"
$targetPath = "C:\Users\Tarik Besirovic\Local Sites\nutrilux10\app\public\wp-content\themes\theme-nutrilux"

Write-Host "=== AUTO-COPY TO LOCAL WP ===" -ForegroundColor Green
Write-Host "Source: $sourcePath" -ForegroundColor Cyan
Write-Host "Target: $targetPath" -ForegroundColor Yellow
Write-Host ""

# Provjeri da li putanje postoje
if (-not (Test-Path $sourcePath)) {
    Write-Host "ERROR: Source folder ne postoji!" -ForegroundColor Red
    exit
}

if (-not (Test-Path $targetPath)) {
    Write-Host "ERROR: Target folder ne postoji!" -ForegroundColor Red
    exit
}

Write-Host "Auto-kopiranje pokrenuto..." -ForegroundColor White
Write-Host "Pritisni Ctrl+C za stop" -ForegroundColor Red
Write-Host ""

# Kopiraj sve odmah na početak
Write-Host "Inicijalko kopiranje..." -ForegroundColor Yellow
try {
    robocopy $sourcePath $targetPath /MIR /XD .git node_modules /NFL /NDL /NJH /NJS /nc /ns /np
    Write-Host "Inicijalno kopiranje završeno!" -ForegroundColor Green
    Write-Host ""
}
catch {
    Write-Host "ERROR tokom inicijalnog kopiranja: $($_.Exception.Message)" -ForegroundColor Red
}

# File watcher za live kopiranje
$watcher = New-Object System.IO.FileSystemWatcher
$watcher.Path = $sourcePath
$watcher.Filter = "*.*"
$watcher.IncludeSubdirectories = $true
$watcher.EnableRaisingEvents = $true

$action = {
    $path = $Event.SourceEventArgs.FullPath
    $name = $Event.SourceEventArgs.Name
    $changeType = $Event.SourceEventArgs.ChangeType
    
    # Skip temporary files
    if ($name -match "\.tmp$|\.swp$|~$") { return }
    
    try {
        $relativePath = $path.Replace($sourcePath, "").TrimStart("\\").TrimStart("/")
        $targetFile = Join-Path $targetPath $relativePath
        $targetDir = Split-Path $targetFile -Parent
        
        if ($changeType -eq "Deleted") {
            if (Test-Path $targetFile) {
                Remove-Item $targetFile -Force
                $time = Get-Date -Format "HH:mm:ss"
                Write-Host "[$time] DELETED: $relativePath" -ForegroundColor Red
            }
        }
        else {
            # Create directory if needed
            if (-not (Test-Path $targetDir)) {
                New-Item -ItemType Directory -Path $targetDir -Force | Out-Null
            }
            
            # Copy file
            Copy-Item $path $targetFile -Force
            
            $time = Get-Date -Format "HH:mm:ss"
            Write-Host "[$time] COPIED: $relativePath" -ForegroundColor Green
            
            # Sound notification
            [Console]::Beep(600, 150)
        }
    }
    catch {
        Write-Host "ERROR copying $name : $($_.Exception.Message)" -ForegroundColor Red
    }
}

Register-ObjectEvent -InputObject $watcher -EventName "Changed" -Action $action
Register-ObjectEvent -InputObject $watcher -EventName "Created" -Action $action
Register-ObjectEvent -InputObject $watcher -EventName "Deleted" -Action $action
Register-ObjectEvent -InputObject $watcher -EventName "Renamed" -Action $action

try {
    while ($true) { 
        Start-Sleep 1 
    }
}
finally {
    $watcher.Dispose()
    Write-Host "Auto-copy zaustavljen!" -ForegroundColor Red
}
