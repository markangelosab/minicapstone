<template>
  <Head title="Bid History" />

  <AuthenticatedLayout :auth="auth">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Bid History
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Auction
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Amount
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Time
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="bid in bids.data" :key="bid.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">
                        {{ bid.auction.title }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">
                        ${{ bid.amount }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ new Date(bid.created_at).toLocaleString() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        :class="{
                          'bg-green-100 text-green-800': bid.auction.status === 'ongoing',
                          'bg-yellow-100 text-yellow-800': bid.auction.status === 'upcoming',
                          'bg-red-100 text-red-800': bid.auction.status === 'closed'
                        }"
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      >
                        {{ bid.auction.status }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
              <Pagination :links="bids.links" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'

const { auth } = usePage().props;

defineProps({
  bids: Object,
})
</script> 