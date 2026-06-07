<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Team;
use App\Models\Venue;
use App\Models\Booking;
use Filament\Widgets\Widget;

class SuperAdminHeroWidget extends Widget
{
    protected string $view = 'filament.super-admin.widgets.super-admin-hero-widget';

    protected int|string|array $columnSpan = 'full';

    public string $userName;

    public int $totalUsers;
    public int $totalTeams;
    public int $totalVenues;
    public int $totalBookings;
    public int $pendingBookings;
    public int $completedBookings;

    public function mount(): void
    {
        $this->userName = auth()->user()?->name ?? 'Super Admin';

        $this->totalUsers = User::count();
        $this->totalTeams = Team::count();
        $this->totalVenues = Venue::count();
        $this->totalBookings = Booking::count();

        $this->pendingBookings = Booking::where('status', 'pending')->count();
        $this->completedBookings = Booking::where('status', 'completed')->count();
    }
}