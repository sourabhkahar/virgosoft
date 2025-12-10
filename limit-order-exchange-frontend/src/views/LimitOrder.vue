<script setup lang="ts">
import AppLayout from '@/components/layout/AppLayout.vue';
import { useForm } from 'vee-validate';
import * as yup from 'yup';
import { order } from '@/api/order';
import router from '@/router';
import { ref } from 'vue';
import config from '@/config';
const orderApi = order();
const isLoading = ref(false);
const { handleSubmit, defineField, errors, resetForm } = useForm({
    validationSchema: yup.object({
        side: yup.string().required(),
        symbol: yup.string().required(),
        price: yup.number().required(),
        amount: yup.number().required(),
    }),
});
const [ side, sideAttr ] = defineField('side')
const [ symbol, symbolAttr ] = defineField('symbol')
const [ price, priceAttr ] = defineField('price')
const [ amount, amountAttr ] = defineField('amount')
side.value = "buy";
const onSubmitLogin = handleSubmit(async (values) => {
    try {
        
        isLoading.value = true;
        const res = await orderApi.placeOrder(values);
        isLoading.value = false;
        if (res.data.status == 'success') {
            resetForm();
            router.push('/order-wallet');
        } else {
            alert('Registration failed. Please try again.');
        }
        isLoading.value = false;

    } catch (error) {
        isLoading.value = false;
    }
});
</script>

<template>
    <AppLayout>
       <form class="w-full max-w-sm mx-auto bg-white p-8 rounded-md shadow-md" @submit="onSubmitLogin">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="symbol">Symbol</label>
                <select name="symbol" id="symbol" v-model="symbol" v-bind="symbolAttr" class="block w-full px-3 py-2 text-base border border-gray-300 rounded-md  focus:outline-none  focus:border-indigo-500 sm:text-sm " placeholder="Select a symbol">
                    <option v-for="option in config.symbols" :key="option.value" :value="option.value">
                        {{ option.label }}
                    </option>
                </select>
                <div class="text-red-500">{{ errors.symbol }}</div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="side">Side</label>
                <div class="flex gap-4 mb-2">
                    <label for="buy" class="flex items-center justify-center ">
                        <input
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 m-2"
                        type="radio" id="buy" name="side" value="buy"  v-model="side"
                        v-bind="sideAttr"> 
                        <span> Buy </span>
                    </label>
                    <label for="sell" class="flex items-center justify-center ">
                        <input
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 m-2"
                        type="radio" id="sell" name="side" value="sell"  v-model="side"
                        v-bind="sideAttr">
                        <span> Sell </span>
                    </label>
                </div>
                <div class="text-red-500">{{ errors.side }}</div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price">Price</label>
                <input
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:m-0 [&::-webkit-outer-spin-button]:m-0"
                    type="number" id="price" name="price"  v-model="price"
                    v-bind="priceAttr">
                <div class="text-red-500">{{ errors.price }}</div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="amount">Amount</label>
                <input
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:m-0 [&::-webkit-outer-spin-button]:m-0"
                    type="number" id="amount" name="amount" 
                    v-model="amount" v-bind="amountAttr">
                <div class="text-red-500">{{ errors.amount }}</div>
            </div>
            <button
                 class="w-full bg-gradient-to-r from-sky-600 to-cyan-400 text-white text-sm font-bold py-2 px-4 rounded-md hover:bg-cyan-600 transition duration-300 cursor-pointer"
                type="submit" :disabled="isLoading">Place Order</button>
        </form>
    </AppLayout>
</template>