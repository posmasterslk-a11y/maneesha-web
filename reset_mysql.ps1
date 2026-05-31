# MySQL Password Reset Script for Maneesha Fashion
# Run as Administrator

Write-Host "=== Maneesha Fashion - MySQL Password Reset ===" -ForegroundColor Cyan

# Stop MySQL service
Write-Host "Stopping MySQL80 service..." -ForegroundColor Yellow
net stop MySQL80
Start-Sleep -Seconds 2

# Create temp init file
$initFile = "C:\Users\Codehub\.gemini\antigravity-ide\scratch\maneesha_fashion\reset_mysql_password.sql"
$sql = @"
FLUSH PRIVILEGES;
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'ManeeshaDB@2024';
FLUSH PRIVILEGES;
"@
$sql | Out-File -FilePath $initFile -Encoding ASCII -Force

Write-Host "Starting MySQL in safe mode to reset password..." -ForegroundColor Yellow

# Start mysqld with init file (skip-grant-tables + init-file)
$mysqld = "C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqld.exe"
$dataDir = "C:\ProgramData\MySQL\MySQL Server 8.0\Data"

$proc = Start-Process -FilePath $mysqld `
    -ArgumentList "--skip-grant-tables --skip-networking --console" `
    -NoNewWindow -PassThru

Start-Sleep -Seconds 4

Write-Host "Resetting root password..." -ForegroundColor Yellow

$mysql = "C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe"

# Reset using skip-grant-tables (no password needed)
& $mysql -u root --connect-expired-password -e "FLUSH PRIVILEGES; ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'ManeeshaDB@2024'; FLUSH PRIVILEGES;" 2>&1

Start-Sleep -Seconds 2

# Kill the safe mode mysqld
Write-Host "Stopping safe mode MySQL..." -ForegroundColor Yellow
Stop-Process -Id $proc.Id -Force -ErrorAction SilentlyContinue
Start-Sleep -Seconds 3

# Restart MySQL normally
Write-Host "Restarting MySQL normally..." -ForegroundColor Yellow
net start MySQL80
Start-Sleep -Seconds 3

# Verify
Write-Host "Verifying new password..." -ForegroundColor Yellow
$result = & $mysql -u root -pManeeshaDB@2024 -e "SELECT 'Password reset SUCCESS!' AS Result;" 2>&1
Write-Host $result -ForegroundColor Green

Write-Host ""
Write-Host "=== DONE! ===" -ForegroundColor Green
Write-Host "MySQL root password is now: ManeeshaDB@2024" -ForegroundColor Green
Write-Host ""

Read-Host "Press Enter to close"
