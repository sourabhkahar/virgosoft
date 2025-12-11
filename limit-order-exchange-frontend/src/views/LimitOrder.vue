<script setup lang="ts">
import AppLayout from '@/components/layout/AppLayout.vue';
import { useForm } from 'vee-validate';
import * as yup from 'yup';
import { order } from '@/api/order';
import router from '@/router';
import { computed, ref } from 'vue';
import config from '@/config';
import ProfileCard from '@/components/ProfileCard.vue';
import { toast } from 'vue3-toastify';
const orderApi = order();
const isLoading = ref(false);
const { handleSubmit, defineField, errors, resetForm } = useForm({
    validationSchema: yup.object({
        side: yup.string().required(),
        symbol: yup.string().required(),
        price: yup.number().typeError('Please enter a valid number').required().min(1),
        amount: yup.number().typeError('Please enter a valid number').required().min(1),
    }),
});
const [ side, sideAttr ] = defineField('side')
const [ symbol, symbolAttr ] = defineField('symbol')
const [ price, priceAttr ] = defineField('price')
const [ amount, amountAttr ] = defineField('amount')
side.value = "buy";
symbol.value = "";
price.value = 0;
amount.value = 0;

const onSubmitLogin = handleSubmit(async (values) => {
    try {
        isLoading.value = true;
        const res = await orderApi.placeOrder(values);
        isLoading.value = false;
        if (res.data.status == 'success') {
            resetForm();
            toast.success('Order place successfully. !');
            router.push('/order-wallet');
        } else {
            toast.error(res.data.message);
        }
        isLoading.value = false;

    } catch (e) {
        toast.error('Something went Wrong!');
        isLoading.value = false;
    }
});

const buyVolume = computed(() => price.value * amount.value);
const buyFee = computed(() => buyVolume.value * 0.015);
const buyTotal = computed(() => buyVolume.value + buyFee.value);

const sellVolume = computed(() => price.value * amount.value);
const sellFee = computed(() => sellVolume.value * 0.015);
const sellTotal = computed(() => sellVolume.value - sellFee.value);
</script>

<template>
    <AppLayout>
        <ProfileCard />
        <div class="mt-4 container">
            <div class="flex justify-center">
                <div class="w-full max-w-sm bg-white p-8 rounded-md shadow-md m-2">
                    <form class="" @submit="onSubmitLogin">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="symbol">Symbol</label>
                            <select name="symbol" id="symbol" v-model="symbol" v-bind="symbolAttr"
                                class="block w-full px-3 py-2 text-base border border-gray-300 rounded-md  focus:outline-none  focus:border-indigo-500 sm:text-sm "
                                placeholder="Select a symbol">
                                <option value="" disabled selected>Select symbol</option>
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
                                        type="radio" id="buy" name="side" value="buy" v-model="side" v-bind="sideAttr">
                                    <span> Buy </span>
                                </label>
                                <label for="sell" class="flex items-center justify-center ">
                                    <input
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 m-2"
                                        type="radio" id="sell" name="side" value="sell" v-model="side"
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
                                type="number" id="price" name="price" v-model="price" v-bind="priceAttr">
                            <div class="text-red-500">{{ errors.price }}</div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="amount">Amount</label>
                            <input
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:m-0 [&::-webkit-outer-spin-button]:m-0"
                                type="number" id="amount" name="amount" v-model="amount" v-bind="amountAttr">
                            <div class="text-red-500">{{ errors.amount }}</div>
                        </div>
                        <button
                            :class="[ isLoading ? 'cursor-pointer-none' : 'cursor-pointer', 'w-full bg-gradient-to-r from-sky-600 to-cyan-400 text-white text-sm font-bold py-2 px-4 rounded-md hover:bg-cyan-600 transition duration-300 flex justify-center' ]"
                            type="submit" :disabled="isLoading">
                            <span v-if="!isLoading">Place Order</span>
                            <div v-else
                                class="h-5 w-5 border-2 border-white border-t-transparent rounded-full animate-spin">
                            </div>
                        </button>

                    </form>
                </div>
                <div class="w-full max-w-sm bg-white p-8 rounded-md shadow-md m-2"
                    v-if="price && amount && side === 'buy'">
                    <h1 class="text-lg font-medium text-black">Buy Volume Calculation</h1>

                    <div class="space-y-1 text-gray-700">
                        <p>Symbol: {{ symbol }}</p>
                        <p>Price: {{ price }}</p>
                        <p>Amount: {{ amount }}</p>

                        <p>Volume (USD): {{ buyVolume }}</p>
                        <p>Fees (1.5%): {{ buyFee }}</p>

                        <p class="font-semibold">
                            Total Cost: {{ buyTotal }}
                        </p>
                    </div>
                </div>


                <!-- SELL CARD -->
                <div class="w-full max-w-sm bg-white p-8 rounded-md shadow-md m-2"
                    v-if="price && amount && side === 'sell'">
                    <h1 class="text-lg font-medium text-black">Sell Volume Calculation</h1>

                    <div class="space-y-1 text-gray-700">
                        <p>Symbol: {{ symbol }}</p>
                        <p>Selling Price: {{ price }}</p>
                        <p>Amount: {{ amount }}</p>

                        <p>Total Value (USD): {{ sellVolume }}</p>
                        <p>Fees (1.5%): {{ sellFee }}</p>

                        <p class="font-semibold">
                            Final Amount You Receive: {{ sellTotal }}
                        </p>
                    </div>
                </div>


            </div>
        </div>
    </AppLayout>
</template>