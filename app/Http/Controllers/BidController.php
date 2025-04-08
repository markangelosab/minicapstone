<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    public function store(Request $request, Auction $auction)
    {
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:' . ($auction->current_bid ?? $auction->start_price + 0.01),
            ],
        ]);

        $bid = $auction->bids()->create([
            'user_id' => Auth::id(),
            'amount' => $validated['amount'],
        ]);

        $auction->update([
            'current_bid' => $validated['amount'],
        ]);

        return back()->with('message', 'Bid placed successfully');
    }

    public function history()
    {
        $bids = Auth::user()
            ->bids()
            ->with('auction')
            ->latest()
            ->paginate(10);

        return Inertia::render('Bids/History', [
            'bids' => $bids
        ]);
    }

    public function wonAuctions()
    {
        $auctions = Auth::user()
            ->wonAuctions()
            ->with('bids')
            ->latest()
            ->paginate(10);

        return Inertia::render('Bids/WonAuctions', [
            'auctions' => $auctions
        ]);
    }

    public function activity()
    {
        $bids = Bid::with(['user', 'auction'])
            ->latest()
            ->paginate(15);

        return Inertia::render('Bids/Activity', [
            'bids' => $bids
        ]);
    }
}
