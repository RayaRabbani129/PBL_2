{{-- resources/views/user/matchmaking/_results.blade.php --}}

<div class="mm-results-grid">
    @foreach ($results as $item)
        @include('user.matchmaking.card', [
            'team'          => $item['team'],
            'score'         => $item['score'],
            'score_label'   => $item['score_label'],
            'score_color'   => $item['score_color'],
            'match_reasons' => $item['match_reasons'],
            'overlap_slots' => $item['overlap_slots'] ?? [],
            'rank'          => $loop->iteration,
        ])
    @endforeach
</div>