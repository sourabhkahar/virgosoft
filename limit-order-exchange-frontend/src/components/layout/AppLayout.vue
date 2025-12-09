<script setup lang="ts">
import AppNavbar from './AppNavbar.vue';
import AppSidebar from './AppSidebar.vue';
import { ref } from 'vue';

const sidebarOpen = ref(false);

function toggleSidebar() {
    sidebarOpen.value = !sidebarOpen.value;
}
</script>

<template>
   <AppSidebar :sidebarOpen="sidebarOpen"/>
    <div class="ml-auto mb-6 lg:w-[75%] xl:w-[80%] 2xl:w-[85%]">
        <AppNavbar @toggleSidebar="toggleSidebar"/>
        <div class="px-6 pt-6 2xl:container">
             <slot />
        </div>
    </div>
</template>