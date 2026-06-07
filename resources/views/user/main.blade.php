@extends('user.layouts.app')

@section('content')
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC]">
    <div class="flex items-center lg:justify-center p-6 lg:p-8">
        <div class="w-full lg:max-w-4xl">
            <!-- Profile Section -->
            <div class="bg-white dark:bg-[#161615] rounded-t-lg lg:rounded-tl-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] p-6 lg:p-8">
                <div class="flex flex-col lg:flex-row gap-6 mb-8">
                    <!-- Avatar & User Info -->
                    <div class="flex flex-col items-center lg:items-start">
                        <img src="{{ Auth::user()->avatar ?? 'https://via.placeholder.com/150' }}" 
                             alt="Profile" class="rounded-full mb-4" width="120">
                        <h2 class="font-medium text-lg">{{ Auth::user()->name }}</h2>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">{{ Auth::user()->email }}</p>
                        <a href="{{ route('profile.edit') }}" class="mt-4 px-5 py-1.5 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] rounded-sm text-sm hover:bg-black dark:hover:bg-white transition-colors">
                            Edit Profile
                        </a>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-3 gap-3 w-full lg:flex-1">
                        <div class="bg-[#fff2f2] dark:bg-[#1D0002] p-4 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                            <p class="text-[#706f6c] dark:text-[#A1A09A] text-xs mb-2">Matches Played</p>
                            <p class="text-2xl font-medium">{{ $matchesPlayed ?? 0 }}</p>
                        </div>
                        <div class="bg-[#fff2f2] dark:bg-[#1D0002] p-4 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                            <p class="text-[#706f6c] dark:text-[#A1A09A] text-xs mb-2">Wins</p>
                            <p class="text-2xl font-medium">{{ $wins ?? 0 }}</p>
                        </div>
                        <div class="bg-[#fff2f2] dark:bg-[#1D0002] p-4 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                            <p class="text-[#706f6c] dark:text-[#A1A09A] text-xs mb-2">Win Rate</p>
                            <p class="text-2xl font-medium">{{ $winRate ?? 0 }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Matches -->
                <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] pt-6">
                    <h3 class="font-medium mb-4">Recent Matches</h3>
                    @if($recentMatches && $recentMatches->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                                        <th class="text-left py-2 font-medium text-[#706f6c] dark:text-[#A1A09A]">Opponent</th>
                                        <th class="text-left py-2 font-medium text-[#706f6c] dark:text-[#A1A09A]">Sport</th>
                                        <th class="text-left py-2 font-medium text-[#706f6c] dark:text-[#A1A09A]">Result</th>
                                        <th class="text-left py-2 font-medium text-[#706f6c] dark:text-[#A1A09A]">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentMatches as $match)
                                    <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] hover:bg-[#FDFDFC] dark:hover:bg-[#1D0002]">
                                        <td class="py-3">{{ $match->opponent_name }}</td>
                                        <td class="py-3">{{ $match->sport }}</td>
                                        <td class="py-3">
                                            <span class="px-2 py-1 rounded-sm text-xs font-medium {{ $match->result == 'win' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-100' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-100' }}">
                                                {{ ucfirst($match->result) }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-[#706f6c] dark:text-[#A1A09A]">{{ $match->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-[#706f6c] dark:text-[#A1A09A] py-8 text-center">No matches yet. Start playing!</p>
                    @endif
                </div>

                <!-- CTA Button -->
                <div class="mt-8 pt-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <a href="{{ route('matches.find') }}" class="inline-block px-6 py-2 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] rounded-sm font-medium hover:bg-black dark:hover:bg-white transition-colors">
                        Find a Match
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
