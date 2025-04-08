<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;

class AuctionController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create auctions')->only(['create', 'store']);
        $this->middleware('permission:edit auctions')->only(['edit', 'update']);
        $this->middleware('permission:delete auctions')->only('destroy');
    }

    public function index()
    {
        $auctions = Auction::with('bids')
            ->withCount('bids')
            ->latest()
            ->paginate(10);

        return Inertia::render('Auctions/Index', [
            'auctions' => $auctions
        ]);
    }

    public function create()
    {
        return Inertia::render('Auctions/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_price' => 'required|numeric|min:0',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        $auction = Auction::create([
            ...$validated,
            'current_bid' => null,
            'status' => 'upcoming',
        ]);

        return redirect()->route('auctions.index')
            ->with('message', 'Auction created successfully');
    }

    public function edit(Auction $auction)
    {
        return Inertia::render('Auctions/Edit', [
            'auction' => $auction
        ]);
    }

    public function update(Request $request, Auction $auction)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_price' => 'required|numeric|min:0',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $auction->update($validated);

        return redirect()->route('auctions.index')
            ->with('message', 'Auction updated successfully');
    }

    public function destroy(Auction $auction)
    {
        $auction->delete();

        return redirect()->route('auctions.index')
            ->with('message', 'Auction deleted successfully');
    }

    public function dashboard()
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');

        if ($isAdmin) {
            $stats = [
                'total_auctions' => Auction::count(),
                'ongoing_auctions' => Auction::where('status', 'active')->count(),
                'total_users' => User::count(),
                'total_bids' => Bid::count(),
            ];

            $recentBids = Bid::with(['user', 'auction'])
                ->latest()
                ->take(5)
                ->get();
        } else {
            $stats = [
                'active_bids' => Bid::where('user_id', $user->id)
                    ->whereHas('auction', function ($query) {
                        $query->where('status', 'active');
                    })
                    ->count(),
                'won_auctions' => Auction::where('winner_id', $user->id)->count(),
                'total_bids' => Bid::where('user_id', $user->id)->count(),
                'highest_bids' => Bid::where('user_id', $user->id)
                    ->whereHas('auction', function ($query) {
                        $query->where('status', 'active');
                    })
                    ->whereRaw('amount = (SELECT MAX(amount) FROM bids WHERE auction_id = bids.auction_id)')
                    ->count(),
            ];

            $recentBids = Bid::with(['auction'])
                ->where('user_id', $user->id)
                ->whereHas('auction', function ($query) {
                    $query->where('status', 'active');
                })
                ->latest()
                ->take(5)
                ->get();
        }

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentBids' => $recentBids,
        ]);
    }

    public function show(Auction $auction)
    {
        $auction->load(['bids.user', 'winner']);

        return Inertia::render('Auctions/Show', [
            'auction' => $auction,
        ]);
    }
}
