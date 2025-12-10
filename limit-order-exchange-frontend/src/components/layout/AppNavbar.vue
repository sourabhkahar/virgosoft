<script setup>
import router from '@/router';
import { useUserStore } from '@/store/index';
const storeUser = useUserStore();
function logoutUser() {
    storeUser.logout();
    router.push('/login');
}
</script>       
<template>
    <div class="sticky z-10 top-0 h-16 md:border-b bg-white lg:py-2.5 flex items-center justify-between">
        <div class="px-6 flex items-center justify-between space-x-4 2xl:container lg:hidden">
            <h5 hidden class="text-2xl text-gray-600 font-medium lg:block">Dashboard</h5>
            <button class="w-12 h-16 -mr-2 md:border-r lg:hidden cursor-pointer" @click="$emit('toggleSidebar')">
                <svg  class="h-6 w-6 my-auto" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div class="flex justify-center items-center m-4">
            <h5  class="text-sm lg:text-2xl text-gray-600 font-medium  ">{{  router.currentRoute.value.meta.name }}</h5>
        </div>
        
        <div class=" flex justify-end items-center ">
            <button class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group cursor-pointer" @click="logoutUser">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="group-hover:text-gray-700">Logout</span>
            </button>
        </div>
    </div>
</template>