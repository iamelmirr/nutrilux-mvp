# SIMPLE AUTO-COPY TO LOCAL WP
$sourcePath = "C:\Users\Tarik Besirovic\Desktop\nutrilux mvp\theme-nutrilux"
$targetPath = "C:\Users\Tarik Besirovic\Local Sites\nutrilux10\app\public\wp-content\themes\theme-nutrilux"

Write-Host "=== SIMPLE AUTO-COPY ===" -ForegroundColor Green
Write-Host "Source: $sourcePath" -ForegroundColor Cyan
Write-Host "Target: $targetPath" -ForegroundColor Yellow
Write-Host ""

# Copy all files immediately
Write-Host "Copying all files..." -ForegroundColor Yellow
robocopy $sourcePath $targetPath /MIR /XD .git /NFL /NDL /NJH /NJS
Write-Host "Initial copy done!" -ForegroundColor Green
Write-Host ""

# File watcher
$watcher = New-Object System.IO.FileSystemWatcher
$watcher.Path = $sourcePath
$watcher.Filter = "*.*"
$watcher.IncludeSubdirectories = $true
$watcher.EnableRaisingEvents = $true

$action = {
    $sourceFile = $Event.SourceEventArgs.FullPath
    $fileName = $Event.SourceEventArgs.Name
    $changeType = $Event.SourceEventArgs.ChangeType
    
    # Skip temp files
    if ($fileName -match "\.tmp$|\.swp$|~$") { return }
    
    try {
        # Simple path calculation
        $sourceLength = $sourcePath.Length + 1
        if ($sourceFile.Length -gt $sourceLength) {
            $relativePath = $sourceFile.Substring($sourceLength)
            $targetFile = Join-Path $targetPath $relativePath
            
            if ($changeType -eq "Deleted") {
                if (Test-Path $targetFile) {
                    Remove-Item $targetFile -Force
                    Write-Host "DELETED: $relativePath" -ForegroundColor Red
                }
            }
            else {
                $targetDir = Split-Path $targetFile -Parent
                if (-not (Test-Path $targetDir)) {
                    New-Item -ItemType Directory -Path $targetDir -Force | Out-Null
                }
                
                Copy-Item $sourceFile $targetFile -Force
                Write-Host "COPIED: $relativePath" -ForegroundColor Green
                [Console]::Beep(600, 100)
            }
        }
    }
    catch {
        Write-Host "ERROR: $($_.Exception.Message)" -ForegroundColor Red
    }
}

Register-ObjectEvent -InputObject $watcher -EventName "Changed" -Action $action
Register-ObjectEvent -InputObject $watcher -EventName "Created" -Action $action
Register-ObjectEvent -InputObject $watcher -EventName "Deleted" -Action $action

Write-Host "Auto-copy active! Press Ctrl+C to stop." -ForegroundColor White

try {
    while ($true) { Start-Sleep 1 }
}
finally {
    $watcher.Dispose()
}
