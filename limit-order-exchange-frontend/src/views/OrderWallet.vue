<script setup lang="ts">
import { echo } from '../plugins/echo'
import AppLayout from '@/components/layout/AppLayout.vue';
import { order } from '@/api/order';
import { useUserStore } from '@/store';
import { onMounted, ref } from 'vue';
import config from '@/config';
import ProfileCard from '@/components/ProfileCard.vue';

const userStore = useUserStore();
const orderApi = order();
const orders = ref([]);
const symbol = ref("")
const isLoading = ref(false);
const proCard = ref(null)

async function getAllOrders() {
    isLoading.value = true
    const payload = {
        symbol: symbol.value,
    };
    const res = await orderApi.orders(payload);
    orders.value = res.data.data;
    isLoading.value = false
}
getAllOrders()

function getBadge(status) {
    if (status === 'open') {
        return 'bg-gray-50 text-gray-600 inset-ring inset-ring-gray-500/10';
    } else if (status === 'filled') {
        return 'bg-green-50 text-green-600 inset-ring inset-ring-green-500/10';
    } else {
        return 'bg-red-50 text-red-600 inset-ring inset-ring-red-500/10';
    }
}

async function cancelOrder(id) {
    const res = await orderApi.cancel(id);
    if (res.data.status == 'success') {
        console.log('sucess');
        getAllOrders()
    } else {
        console.log('fail');
    }
}

// Listen Broadcast Event
onMounted(() => {
    echo.private(`user.${userStore.user.id}`)
        .listen('.OrderMatched', () => {
            getAllOrders()
            proCard.value?.profile()
    })
})
</script>

<template>
    <AppLayout>
        <ProfileCard ref="proCard" />
        <div class="mt-4 container">
            <div class="flex justify-between">
                <h2 class="text-xl font-bold mb-4">Past Orders</h2>
                <div>
                    <select name="symbol" id="symbol" v-model="symbol"
                        class="block w-full px-3 py-2 text-base border border-gray-300 rounded-md  focus:outline-none  focus:border-indigo-500 sm:text-sm "
                        placeholder="Select a symbol" @change="getAllOrders">
                        <option value="" selected>Select symbol</option>
                        <option v-for="option in config.symbols" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>
                </div>
            </div>
            <template v-if="!isLoading">
                <template v-if="orders.length > 0">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-2" v-for="order in orders"
                        :key="order.id">
                        <div class="md:flex">
                            <div class="p-8 w-full">
                                <div
                                    class="uppercase tracking-wide text-sm  font-semibold text-cyan-600 flex justify-between w-full">
                                    <span>{{ order.symbol }} - {{ order.side }}</span>
                                    <a v-if="order.status == 'open'" href="#" @click="cancelOrder(order.id)"> <span
                                            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-red-50 text-red-600 inset-ring inset-ring-red-500/10">Cancel</span></a>
                                </div>
                                <p class="block mt-1 text-lg leading-tight font-medium text-black ">
                                    {{ order.amount }} @ {{ order.price }}
                                </p>
                                <p class="mt-2 text-slate-500">
                                    <span
                                        :class="`inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ${getBadge(order.status)}`">{{
                                            order.status }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </template>
                <div v-else class="bg-white rounded-xl shadow-md overflow-hidden mb-2 p-2">
                    No Records Found!
                </div>
            </template>
            <template v-else>
                <div class="mx-auto w-full rounded-md border border-blue-300 p-4">
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
        </div>
    </AppLayout>
</template>