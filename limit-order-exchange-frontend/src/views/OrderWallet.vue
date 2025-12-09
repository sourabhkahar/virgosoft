<script setup lang="ts">
import AppLayout from '@/components/layout/AppLayout.vue';
import { useForm } from 'vee-validate';
import * as yup from 'yup';
import { useAuth } from '@/api/auth';
import { useUserStore } from '@/store/index';
import router from '@/router';
import { ref } from 'vue';
const auth = useAuth();
const storeUser = useUserStore();
const isLoading = ref(false);
const { handleSubmit, defineField, errors, resetForm } = useForm({
    validationSchema: yup.object({
        name: yup.string().required(),
        email: yup.string().email().required(),
        password: yup.string().min(6).required(),
        password_confirmation: yup.string()
            .oneOf([ yup.ref('password') ], 'Passwords must match')
            .required(),
    }),
});
const [ email, emailAttr ] = defineField('email')
const [ name, nameAttr ] = defineField('name')
const [ password, passwordAttr ] = defineField('password')
const [ password_confirmation, password_confirmationAttr ] = defineField('password_confirmation')
const onSubmitLogin = handleSubmit(async (values) => {
    isLoading.value = true;
    const res = await auth.register(values);
    isLoading.value = false;
    if (res.data.status == 'success') {
        storeUser.setUser(res.data);
        resetForm();
        router.push('/profile');
    } else {
        alert('Registration failed. Please try again.');
    }
});
</script>

<template>
    <AppLayout>
       <form class="w-full max-w-sm mx-auto bg-white p-8 rounded-md shadow-md" @submit="onSubmitLogin">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
                <input
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500"
                    type="text" id="name" name="name" placeholder="John Doe" v-model="name" v-bind="nameAttr">
                <div class="text-red-500">{{ errors.name }}</div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                <input
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500"
                    type="email" id="email" name="email" placeholder="john@example.com" v-model="email"
                    v-bind="emailAttr">
                <div class="text-red-500">{{ errors.email }}</div>

            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500"
                    type="password" id="password" name="password" placeholder="********" v-model="password"
                    v-bind="passwordAttr">
                <div class="text-red-500">{{ errors.password }}</div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm-password">Confirm
                    Password</label>
                <input
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500"
                    type="password" id="confirm-password" name="confirm-password" placeholder="********"
                    v-model="password_confirmation" v-bind="password_confirmationAttr">
                <div class="text-red-500">{{ errors.password_confirmation }}</div>
            </div>
            <button
                 class="w-full bg-gradient-to-r from-sky-600 to-cyan-400 text-white text-sm font-bold py-2 px-4 rounded-md hover:bg-cyan-600 transition duration-300 cursor-pointer"
                type="submit" :disabled="isLoading">Register</button>
            <a href="/login"
                class="text-indigo-500 hover:text-indigo-700 text-sm font-bold ml-4 flex justify-center">Already have an
                account? Login</a>
        </form>
    </AppLayout>
</template>