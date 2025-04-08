<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    auction: Object,
});

const { auth } = usePage().props;

const bidAmount = ref(props.auction.current_bid ? props.auction.current_bid + 0.01 : props.auction.start_price);
</script>

<template>
    <Head :title="auction.title" />

    <AuthenticatedLayout :auth="auth">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ auction.title }}
                </h2>
                <Link
                    v-if="$page.props.auth.user.roles.includes('admin')"
                    :href="route('auctions.edit', auction.id)"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                >
                    Edit Auction
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Auction Details -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Auction Details</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ auction.description }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <span
                                            :class="{
                                                'bg-green-100 text-green-800': auction.status === 'ongoing',
                                                'bg-yellow-100 text-yellow-800': auction.status === 'upcoming',
                                                'bg-red-100 text-red-800': auction.status === 'closed'
                                            }"
                                            class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        >
                                            {{ auction.status }}
                                        </span>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Start Time</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ new Date(auction.start_time).toLocaleString() }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">End Time</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ new Date(auction.end_time).toLocaleString() }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Current Bid</label>
                                        <p class="mt-1 text-sm text-gray-900">${{ auction.current_bid || auction.start_price }}</p>
                                    </div>
                                    <div v-if="auction.winner">
                                        <label class="block text-sm font-medium text-gray-700">Winner</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ auction.winner.name }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Bidding Section -->
                            <div v-if="auction.status === 'ongoing' && $page.props.auth.user && $page.props.auth.user.roles && !$page.props.auth.user.roles.includes('admin')">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Place a Bid</h3>
                                <form @submit.prevent="submitBid" class="space-y-4">
                                    <div>
                                        <label for="bid_amount" class="block text-sm font-medium text-gray-700">Bid Amount</label>
                                        <div class="mt-1">
                                            <input
                                                type="number"
                                                id="bid_amount"
                                                v-model="bidAmount"
                                                :min="auction.current_bid ? auction.current_bid + 0.01 : auction.start_price"
                                                step="0.01"
                                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            />
                                        </div>
                                    </div>
                                    <div>
                                        <button
                                            type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                                        >
                                            Place Bid
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Bid History -->
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Bid History</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="bid in auction.bids" :key="bid.id">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ bid.user.name }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">${{ bid.amount }}</div>
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
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 