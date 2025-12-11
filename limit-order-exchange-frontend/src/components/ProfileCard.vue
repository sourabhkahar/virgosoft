<script setup lang="ts">
import { useAuth } from '@/api/auth';
import { ref } from 'vue';
import { getInitials } from '@/composables/common-funcs';
const isLoading = ref(false);
const auth = useAuth();
const user = ref(null);
const profile = async () => {
    isLoading.value=true
    const res = await auth.profile()
    user.value = res.data;
    isLoading.value=false
};
profile();
defineExpose({
    profile
})
</script>
<template>
    <div v-if="!isLoading" class="bg-white rounded-xl shadow-md overflow-hidden ">
        <div class="md:flex">
            <div
                class="md:shrink-1 font-bold text-9xl flex items-center justify-center bg-gradient-to-r from-sky-600 to-cyan-400 text-white p-2 ">
                {{ getInitials(user?.data.name) }}
            </div>
            <div class="p-8">
                <div class="uppercase tracking-wide text-sm text-cyan-600 font-semibold">{{ (user?.data.name) }}
                </div>
                <p class="block mt-1 text-lg leading-tight font-medium text-black ">
                    Balance: {{ user?.data.balance }} USD
                </p>

                <p class="mt-2 text-slate-500" v-for="assets in user?.data.assets" :key="assets.asset">
                    {{ assets.symbol }}: {{ assets.amount }}
                </p>
            </div>
        </div>
    </div>
    <div  v-else class="mx-auto w-full rounded-md border border-blue-300 p-4">
        <div class="flex animate-pulse space-x-4">
            <div class="size-10 rounded-full bg-gray-200"></div>
            <div class="flex-1 space-y-6 py-1">
                <div class="h-2 rounded bg-gray-200"></div>
                <div class="space-y-3">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2 h-2 rounded bg-gray-200"></div>
                        <div class="col-span-1 h-2 rounded bg-gray-200"></div>
                    </div>
                    <div class="h-2 rounded bg-gray-200"></div>
                </div>
            </div>
        </div>
    </div>
</template>