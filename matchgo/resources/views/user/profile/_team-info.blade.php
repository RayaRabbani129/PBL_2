<div class="mg-card">

    <p class="text-gray-500 mb-4 text-xs">TIM INFO</p>

    @if($team)
        <div class="flex justify-between mb-2">
            <span>Nama Tim</span>
            <span>{{ $team->team_name }}</span>
        </div>

        <div class="flex justify-between mb-2">
            <span>Jenis</span>
            <span>{{ $team->position }}</span>
        </div>

        <div class="flex justify-between mb-2">
            <span>Pemain</span>
            <span>{{ $team->member_count }}</span>
        </div>

        <div class="flex justify-between mb-2">
            <span>Kota</span>
            <span>{{ $team->city }}</span>
        </div>

        <div class="flex justify-between">
            <span>Rating</span>
            <span>⭐ {{ $team->rating ?? '0.0' }}</span>
        </div>
    @else
        <p class="text-gray-400">Belum ada tim</p>
    @endif

</div>