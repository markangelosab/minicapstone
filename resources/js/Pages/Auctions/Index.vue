<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const { auth } = usePage().props;

defineProps({
    auctions: Object,
});
</script>

<template>
    <Head title="Auctions" />

    <AuthenticatedLayout :auth="auth">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Auctions
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900">All Auctions</h3>
                            <Link
                                v-if="$page.props.auth.user.roles.includes('admin')"
                                :href="route('auctions.create')"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                            >
                                Create Auction
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Bid</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bids</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="auction in auctions.data" :key="auction.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ auction.title }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                :class="{
                                                    'bg-green-100 text-green-800': auction.status === 'ongoing',
                                                    'bg-yellow-100 text-yellow-800': auction.status === 'upcoming',
                                                    'bg-red-100 text-red-800': auction.status === 'closed'
                                                }"
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            >
                                                {{ auction.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">${{ auction.current_bid || auction.start_price }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ auction.bids_count }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ new Date(auction.end_time).toLocaleString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <Link
                                                :href="route('auctions.edit', auction.id)"
                                                v-if="$page.props.auth.user.roles.includes('admin')"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3"
                                            >
                                                Edit
                                            </Link>
                                            <Link
                                                :href="route('auctions.show', auction.id)"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                View
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <nav class="flex items-center justify-between">
                                <div class="flex-1 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-700">
                                            Showing
                                            <span class="font-medium">{{ auctions.from }}</span>
                                            to
                                            <span class="font-medium">{{ auctions.to }}</span>
                                            of
                                            <span class="font-medium">{{ auctions.total }}</span>
                                            results
                                        </p>
                                    </div>
                                    <div>
                                        <Link
                                            v-if="auctions.prev_page_url"
                                            :href="auctions.prev_page_url"
                                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                        >
                                            Previous
                                        </Link>
                                        <Link
                                            v-if="auctions.next_page_url"
                                            :href="auctions.next_page_url"
                                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                        >
                                            Next
                                        </Link>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 