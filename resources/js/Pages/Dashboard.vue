<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

defineProps({
  stats: Object,
  recentBids: Array,
});

const { auth } = usePage().props;
const isAdmin = auth.user?.roles?.includes('admin');
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout :auth="auth">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ isAdmin ? 'Admin Dashboard' : 'My Dashboard' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Admin Dashboard -->
                <template v-if="isAdmin">
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <!-- Total Auctions -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500">Total Auctions</div>
                                <div class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.total_auctions }}</div>
                            </div>
                        </div>

                        <!-- Ongoing Auctions -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500">Ongoing Auctions</div>
                                <div class="mt-1 text-3xl font-semibold text-green-600">{{ stats.ongoing_auctions }}</div>
                            </div>
                        </div>

                        <!-- Total Users -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500">Total Users</div>
                                <div class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.total_users }}</div>
                            </div>
                        </div>

                        <!-- Total Bids -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500">Total Bids</div>
                                <div class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.total_bids }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Bid Activity</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auction</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="bid in recentBids" :key="bid.id">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ bid.user.name }}</div>
                                                <div class="text-sm text-gray-500">{{ bid.user.email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ bid.auction.title }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-green-600">${{ bid.amount }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ new Date(bid.created_at).toLocaleString() }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Bidder Dashboard -->
                <template v-else>
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <!-- Active Bids -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500">Active Bids</div>
                                <div class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.active_bids }}</div>
                            </div>
                        </div>

                        <!-- Won Auctions -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500">Won Auctions</div>
                                <div class="mt-1 text-3xl font-semibold text-green-600">{{ stats.won_auctions }}</div>
                            </div>
                        </div>

                        <!-- Total Bids -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500">Total Bids</div>
                                <div class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.total_bids }}</div>
                            </div>
                        </div>

                        <!-- Highest Bids -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500">Highest Bids</div>
                                <div class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.highest_bids }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Bids Table -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">My Active Bids</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auction</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">My Bid</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Bid</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Time</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="bid in recentBids" :key="bid.id">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <Link :href="route('auctions.show', bid.auction.id)" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                                    {{ bid.auction.title }}
                                                </Link>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">${{ bid.amount }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-green-600">${{ bid.auction.current_bid }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    :class="{
                                                        'bg-green-100 text-green-800': bid.amount === bid.auction.current_bid,
                                                        'bg-yellow-100 text-yellow-800': bid.amount !== bid.auction.current_bid
                                                    }"
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                >
                                                    {{ bid.amount === bid.auction.current_bid ? 'Highest Bid' : 'Outbid' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ new Date(bid.auction.end_time).toLocaleString() }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <Link
                            :href="route('bids.history')"
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50"
                        >
                            <h3 class="text-lg font-medium text-gray-900 mb-2">View Bid History</h3>
                            <p class="text-sm text-gray-500">See all your past bids and their status</p>
                        </Link>

                        <Link
                            :href="route('bids.won')"
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50"
                        >
                            <h3 class="text-lg font-medium text-gray-900 mb-2">View Won Auctions</h3>
                            <p class="text-sm text-gray-500">Check the auctions you've won</p>
                        </Link>
                </div>
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
