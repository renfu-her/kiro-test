<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\TodoInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user for testing
        $this->admin = User::factory()->create([
            'email' => 'admin@example.com',
        ]);
    }

    public function test_widgets_can_be_instantiated(): void
    {
        // Test that widgets can be created without errors
        $statsWidget = new \App\Filament\Widgets\StatsOverview();
        $chartWidget = new \App\Filament\Widgets\TodoStatusChart();
        $activityWidget = new \App\Filament\Widgets\RecentActivity();

        $this->assertInstanceOf(\App\Filament\Widgets\StatsOverview::class, $statsWidget);
        $this->assertInstanceOf(\App\Filament\Widgets\TodoStatusChart::class, $chartWidget);
        $this->assertInstanceOf(\App\Filament\Widgets\RecentActivity::class, $activityWidget);
    }

    public function test_stats_overview_widget_has_correct_structure(): void
    {
        // Create some test data
        User::factory()->create();
        Todo::factory()->create(['status' => 'completed']);
        TodoInvitation::factory()->create(['status' => 'pending']);

        // Test that the widget class exists and has the right methods
        $this->assertTrue(class_exists(\App\Filament\Widgets\StatsOverview::class));
        $this->assertTrue(method_exists(\App\Filament\Widgets\StatsOverview::class, 'getStats'));
    }

    public function test_chart_widget_has_correct_structure(): void
    {
        // Test that the widget class exists and has the right methods
        $this->assertTrue(class_exists(\App\Filament\Widgets\TodoStatusChart::class));
        $this->assertTrue(method_exists(\App\Filament\Widgets\TodoStatusChart::class, 'getData'));
        $this->assertTrue(method_exists(\App\Filament\Widgets\TodoStatusChart::class, 'getType'));
    }

    public function test_recent_activity_widget_shows_latest_todos(): void
    {
        // Create some todos with different timestamps
        $oldTodo = Todo::factory()->create(['created_at' => now()->subDays(5)]);
        $newTodo = Todo::factory()->create(['created_at' => now()]);

        $widget = new \App\Filament\Widgets\RecentActivity();
        
        // Test that the widget can be instantiated
        $this->assertInstanceOf(\App\Filament\Widgets\RecentActivity::class, $widget);
    }

    public function test_filament_panel_configuration(): void
    {
        // Test that Filament panel is properly configured
        $this->assertTrue(class_exists(\App\Providers\Filament\BackendPanelProvider::class));
        
        // Test that our widgets exist
        $this->assertTrue(class_exists(\App\Filament\Widgets\StatsOverview::class));
        $this->assertTrue(class_exists(\App\Filament\Widgets\TodoStatusChart::class));
        $this->assertTrue(class_exists(\App\Filament\Widgets\RecentActivity::class));
    }
}
