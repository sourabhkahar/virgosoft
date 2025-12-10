<script setup lang="ts">
import { echo } from '../plugins/echo'
import AppLayout from '@/components/layout/AppLayout.vue';
import { useAuth } from '@/api/auth';
import { order } from '@/api/order';
import { useUserStore } from '@/store';
import { ref } from 'vue';
const auth = useAuth();
const userStore = useUserStore();
const orderApi = order();
const user = ref(null);
const orders = ref(null);
// const isLoading = ref(false);
const profile = async () => {
    const res = await auth.profile()
    user.value = res.data;
};
profile();

async function getAllOrders() {
    const payload = {
        symbol: 'BTC',
    };
    const res = await orderApi.orders(payload);
    orders.value = res.data.data;
}
getAllOrders()

function getInitials(fullName) {
    if (!fullName || typeof fullName !== 'string') {
        return ''; // Handle empty or non-string input
    }

    const nameParts = fullName.split(' ');
    let initials = '';

    for (let i = 0; i < nameParts.length; i++) {
        const part = nameParts[ i ];
        if (part.length > 0) {
            initials += part[ 0 ].toUpperCase();
        }
    }
    return initials;
}

function getBadge(status) {
    if (status === 'open') {
        return 'bg-gray-50 text-gray-600 inset-ring inset-ring-gray-500/10';
    } else if (status === 'filled') {
        return 'bg-green-50 text-green-600 inset-ring inset-ring-green-500/10';
    } else {
        return 'bg-red-50 text-red-600 inset-ring inset-ring-red-500/10';
    }
}

// async function subscribeToRealtime(userId) {

// }
// subscribeToRealtime(userStore.user.id)

echo.private(`user.${userStore.user.id}`)
    .listen('.OrderMatched', () => {
        profile()
        getAllOrders()
    })
</script>

<template>
    <AppLayout>
        <div class="bg-white rounded-xl shadow-md overflow-hidden ">
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
        <div class="mt-4">
            <h2 class="text-xl font-bold mb-4">Past Orders</h2>
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-2" v-for="order in orders" :key="order.id">
                <div class="md:flex">
                    <div class="p-8">
                        <div class="uppercase tracking-wide text-sm  font-semibold text-cyan-600">
                            {{ order.symbol }} - {{ order.side }}
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
        </div>
    </AppLayout>
</template>