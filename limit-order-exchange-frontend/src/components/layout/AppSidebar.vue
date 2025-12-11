<script setup lang="ts">
import router from '@/router';
import { useUserStore } from '@/store/index';
import { getInitials } from '@/composables/common-funcs';
const storeUser = useUserStore();
defineProps({
    sidebarOpen: Boolean
});

</script>
<template>
    <aside
        :class="[ sidebarOpen ? 'ml-0 top-16 transition duration' : 'ml-[-100%]', 'fixed z-10 top-0 pb-3 px-6 w-full flex flex-col justify-between h-screen border-r bg-white transition duration-300 md:w-4/12 lg:ml-0 lg:w-[25%] xl:w-[20%] 2xl:w-[15%]' ]">
        <div>
            <div class="mt-8 text-center">
                <div class="font-bold text-4xl flex items-center justify-center bg-gradient-to-r from-sky-600 to-cyan-400 text-white w-24 h-24 rounded-full m-auto">
                    {{ getInitials(storeUser.user.name) }}
                </div>
                <h5 class="hidden mt-4 text-xl font-semibold text-gray-600 lg:block">{{ storeUser.user.name }}</h5>
            </div>

            <ul class="space-y-2 tracking-wide mt-8">
                <li>
                    <a href="#" @click.prevent="router.push('order-wallet')"
                        :class="[ router.currentRoute.value.name === 'order-wallet' ? 'bg-gradient-to-r from-sky-600 to-cyan-400 text-white' : '', 'px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group text-sm' ]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path class="fill-current text-gray-300 group-hover:text-cyan-300" fill-rule="evenodd"
                                d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z"
                                clip-rule="evenodd" />
                            <path class="fill-current text-gray-600 group-hover:text-cyan-600"
                                d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z" />
                        </svg>
                        <span class="group-hover:text-gray-700">Orders & Wallet</span>
                    </a>
                </li>
                <li>
                    <a href="#" aria-label="dashboard" @click.prevent="router.push('limit-order')"
                        :class="[ router.currentRoute.value.name === 'limit-order' ? 'bg-gradient-to-r from-sky-600 to-cyan-400 text-white' : '', ' px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group text-sm' ]">
                        <svg class="-ml-1 h-6 w-6" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M6 8a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V8ZM6 15a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2v-1Z"
                                class="fill-current text-cyan-400 dark:fill-slate-600"></path>
                            <path d="M13 8a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2V8Z"
                                class="fill-current text-cyan-200 group-hover:text-cyan-300"></path>
                            <path d="M13 15a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-1Z"
                                class="fill-current group-hover:text-sky-300"></path>
                        </svg>
                        <span class="-mr-1 font-medium">Limit Orders</span>
                    </a>
                </li>

            </ul>
        </div>
    </aside>
</template>