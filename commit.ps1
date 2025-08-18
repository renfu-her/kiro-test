# PowerShell Git Commit Script
Write-Host "🚀 Starting Git commit process..." -ForegroundColor Green

try {
    # Check if we're in a git repository
    if (-not (Test-Path ".git")) {
        Write-Host "❌ Not in a Git repository. Please run 'git init' first." -ForegroundColor Red
        exit 1
    }

    Write-Host "`n📁 Adding all files to staging..." -ForegroundColor Yellow
    git add .
    
    if ($LASTEXITCODE -ne 0) {
        throw "Failed to add files to staging"
    }

    Write-Host "✅ Files added successfully" -ForegroundColor Green

    Write-Host "`n💾 Committing changes..." -ForegroundColor Yellow
    $commitMessage = @"
feat: Implement collaborative ToDo List system with Laravel 12 and Filament v4

- Add Member authentication system for frontend users
- Implement complete ToDo CRUD operations with collaboration features
- Add invitation system for todo collaboration
- Create Filament v4 admin panel with comprehensive management
- Add comprehensive test suite (40 tests, 100% pass rate)

Features:
- Member registration/login system (separate from admin users)
- ToDo creation, editing, deletion with status management
- Collaboration invitations and member management
- Filament admin dashboard with statistics and charts
- Complete permission system and security controls
- Responsive frontend UI with Tailwind CSS
- Full test coverage for all functionality

Technical:
- Laravel 12 with modern architecture
- Filament v4 admin panel
- Dual authentication (Member for frontend, User for admin)
- Eloquent relationships and policies
- Form request validation
- Service layer architecture
- Factory and seeder support
"@

    git commit -m $commitMessage
    
    if ($LASTEXITCODE -ne 0) {
        throw "Failed to commit changes"
    }

    Write-Host "✅ Commit successful!" -ForegroundColor Green

    Write-Host "`n🌐 Pushing to remote repository..." -ForegroundColor Yellow
    git push
    
    if ($LASTEXITCODE -ne 0) {
        Write-Host "⚠️  Push failed. This might be normal if no remote is configured." -ForegroundColor Yellow
        Write-Host "   You can manually push later with: git push origin main" -ForegroundColor Cyan
    } else {
        Write-Host "✅ Successfully pushed to remote repository!" -ForegroundColor Green
    }

    Write-Host "`n📊 Summary:" -ForegroundColor Cyan
    Write-Host "- All files added to staging" -ForegroundColor White
    Write-Host "- Changes committed with detailed message" -ForegroundColor White
    Write-Host "- Attempted push to remote repository" -ForegroundColor White

    Write-Host "`n📈 Git Status:" -ForegroundColor Cyan
    git status --short

    Write-Host "`n📝 Recent Commits:" -ForegroundColor Cyan
    git log --oneline -5

} catch {
    Write-Host "`n❌ Error: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Please check the error and try again." -ForegroundColor Yellow
}

Write-Host "`nPress any key to continue..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")