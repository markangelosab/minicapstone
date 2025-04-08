<?php

namespace App\Console\Commands;

use App\Models\Auction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateAuctionStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auctions:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update auction statuses based on their start and end times';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Update upcoming auctions to ongoing
        $updatedToOngoing = Auction::upcoming()
            ->where('start_time', '<=', now())
            ->where('end_time', '>', now())
            ->update(['status' => 'ongoing']);

        // Update ongoing auctions to closed and set winners
        $closedAuctions = Auction::ongoing()
            ->where('end_time', '<=', now())
            ->get();

        foreach ($closedAuctions as $auction) {
            DB::transaction(function () use ($auction) {
                $winningBid = $auction->bids()
                    ->orderByDesc('amount')
                    ->first();

                $auction->update([
                    'status' => 'closed',
                    'winner_id' => $winningBid?->user_id,
                ]);
            });
        }

        $this->info('Updated ' . $updatedToOngoing . ' auctions to ongoing');
        $this->info('Closed ' . $closedAuctions->count() . ' auctions');
    }
}
