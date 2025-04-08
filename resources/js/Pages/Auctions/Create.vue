<template>
  <AuthenticatedLayout :auth="auth">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Auction</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form @submit.prevent="form.post(route('auctions.store'))" class="space-y-6">
              <div>
                <InputLabel for="title" value="Title" />
                <TextInput
                  id="title"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.title"
                  required
                />
                <InputError :message="form.errors.title" class="mt-2" />
              </div>

              <div>
                <InputLabel for="description" value="Description" />
                <textarea
                  id="description"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  v-model="form.description"
                  rows="4"
                  required
                ></textarea>
                <InputError :message="form.errors.description" class="mt-2" />
              </div>

              <div>
                <InputLabel for="start_price" value="Starting Price" />
                <TextInput
                  id="start_price"
                  type="number"
                  step="0.01"
                  class="mt-1 block w-full"
                  v-model="form.start_price"
                  required
                />
                <InputError :message="form.errors.start_price" class="mt-2" />
              </div>

              <div>
                <InputLabel for="start_time" value="Start Time" />
                <TextInput
                  id="start_time"
                  type="datetime-local"
                  class="mt-1 block w-full"
                  v-model="form.start_time"
                  required
                />
                <InputError :message="form.errors.start_time" class="mt-2" />
              </div>

              <div>
                <InputLabel for="end_time" value="End Time" />
                <TextInput
                  id="end_time"
                  type="datetime-local"
                  class="mt-1 block w-full"
                  v-model="form.end_time"
                  required
                />
                <InputError :message="form.errors.end_time" class="mt-2" />
              </div>

              <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Create Auction</PrimaryButton>
                <Link
                  :href="route('auctions.index')"
                  class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                  Cancel
                </Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Link, useForm, usePage } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  description: '',
  start_price: '',
  start_time: '',
  end_time: '',
})

const { auth } = usePage().props;
</script> 