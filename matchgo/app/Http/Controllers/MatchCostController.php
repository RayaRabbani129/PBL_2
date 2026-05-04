<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MatchCost;
use App\Models\Matches;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchCostController extends Controller
{
    /**
     * Display a listing of all split bills.
     */
    public function index()
    {
        $costs = MatchCost::with('match')
            ->latest()
            ->paginate(10);

        return view('user.cost.index', compact('costs'));
    }

    /**
     * Show the form for creating a new split bill.
     */
    public function create()
    {
        $matches = Matches::whereDoesntHave('cost')
            ->latest()
            ->get();

        return view('user.cost.create', compact('matches'));
    }

    /**
     * Store a newly created split bill in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'match_id'           => 'required|exists:matches,id|unique:match_costs,match_id',
            'total_venue_cost'   => 'required|numeric|min:0',
            'home_team_players'  => 'required|integer|min:1|max:20',
            'away_team_players'  => 'required|integer|min:1|max:20',
            'notes'              => 'nullable|string|max:500',
        ]);

        $total = $validated['total_venue_cost'];
        $homeCount = $validated['home_team_players'];
        $awayCount = $validated['away_team_players'];

        $homeCost = $total / 2;
        $awayCost = $total / 2;
        $homeCostPerPlayer = $homeCost / $homeCount;
        $awayCostPerPlayer = $awayCost / $awayCount;

        MatchCost::create([
            'match_id'             => $validated['match_id'],
            'total_venue_cost'     => $total,
            'home_team_cost'       => $homeCost,
            'away_team_cost'       => $awayCost,
            'home_team_players'    => $homeCount,
            'away_team_players'    => $awayCount,
            'home_cost_per_player' => $homeCostPerPlayer,
            'away_cost_per_player' => $awayCostPerPlayer,
            'is_finalized'         => false,
            'notes'                => $validated['notes'] ?? null,
        ]);

        return redirect()->route('match-cost.index')
            ->with('success', 'Split bill berhasil dibuat!');
    }

    /**
     * Display the specified split bill.
     */
    public function show(MatchCost $matchCost)
    {
        $matchCost->load('match');
        return view('user.cost.show', compact('matchCost'));
    }

    /**
     * Show the form for editing the specified split bill.
     */
    public function edit(MatchCost $matchCost)
    {
        $matches = Matches::latest()->get();
        return view('user.match-cost.edit', compact('matchCost', 'matches'));
    }

    /**
     * Update the specified split bill in storage.
     */
    public function update(Request $request, MatchCost $matchCost)
    {
        $validated = $request->validate([
            'total_venue_cost'   => 'required|numeric|min:0',
            'home_team_players'  => 'required|integer|min:1|max:20',
            'away_team_players'  => 'required|integer|min:1|max:20',
            'notes'              => 'nullable|string|max:500',
            'is_finalized'       => 'sometimes|boolean',
        ]);

        $total = $validated['total_venue_cost'];
        $homeCount = $validated['home_team_players'];
        $awayCount = $validated['away_team_players'];

        $matchCost->update([
            'total_venue_cost'     => $total,
            'home_team_cost'       => $total / 2,
            'away_team_cost'       => $total / 2,
            'home_team_players'    => $homeCount,
            'away_team_players'    => $awayCount,
            'home_cost_per_player' => ($total / 2) / $homeCount,
            'away_cost_per_player' => ($total / 2) / $awayCount,
            'is_finalized'         => $request->boolean('is_finalized'),
            'notes'                => $validated['notes'] ?? null,
        ]);

        return redirect()->route('match-cost.show', $matchCost)
            ->with('success', 'Split bill berhasil diperbarui!');
    }

    /**
     * Finalize (lock) the split bill.
     */
    public function finalize(MatchCost $matchCost)
    {
        $matchCost->update(['is_finalized' => true]);

        return redirect()->route('match-cost.show', $matchCost)
            ->with('success', 'Split bill telah difinalisasi!');
    }

    /**
     * Remove the specified split bill from storage.
     */
    public function destroy(MatchCost $matchCost)
    {
        if ($matchCost->is_finalized) {
            return back()->with('error', 'Split bill yang sudah difinalisasi tidak dapat dihapus.');
        }

        $matchCost->delete();

        return redirect()->route('match-cost.index')
            ->with('success', 'Split bill berhasil dihapus.');
    }
}