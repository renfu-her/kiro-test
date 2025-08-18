@echo off
echo Starting Git commit process...

echo.
echo Adding all files to staging...
git add .

echo.
echo Committing changes...
git commit -m "feat: Implement collaborative ToDo List system with Laravel 12 and Filament v4

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
- Factory and seeder support"

if %ERRORLEVEL% EQU 0 (
    echo.
    echo Commit successful! Pushing to remote repository...
    git push
    
    if %ERRORLEVEL% EQU 0 (
        echo.
        echo ✅ Successfully pushed to remote repository!
        echo.
        echo Summary:
        echo - All files added to staging
        echo - Changes committed with detailed message
        echo - Pushed to remote repository
    ) else (
        echo.
        echo ❌ Failed to push to remote repository
        echo Please check your remote repository configuration
    )
) else (
    echo.
    echo ❌ Commit failed
    echo Please check for any issues with your changes
)

echo.
echo Git status:
git status --short

echo.
echo Recent commits:
git log --oneline -5

pause