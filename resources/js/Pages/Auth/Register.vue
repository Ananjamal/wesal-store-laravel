<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import WInput from '@/Components/ui/WInput.vue';
import WButton from '@/Components/ui/WButton.vue';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="حساب جديد" />

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            
            <div class="mb-4 text-center">
                <h1 class="text-2xl font-bold text-wisal-charcoal">طوّر تجربتك</h1>
                <p class="text-gray-600 mt-2">إنشاء حساب جديد في متجر وِصال</p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <WInput
                    id="name"
                    type="text"
                    label="الاسم الكامل"
                    v-model="form.name"
                    :error="form.errors.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <WInput
                    id="email"
                    type="email"
                    label="البريد الإلكتروني"
                    v-model="form.email"
                    :error="form.errors.email"
                    required
                    autocomplete="username"
                />

                <WInput
                    id="phone"
                    type="text"
                    label="رقم الهاتف"
                    v-model="form.phone"
                    :error="form.errors.phone"
                    autocomplete="tel"
                />

                <WInput
                    id="password"
                    type="password"
                    label="كلمة المرور"
                    v-model="form.password"
                    :error="form.errors.password"
                    required
                    autocomplete="new-password"
                />

                <WInput
                    id="password_confirmation"
                    type="password"
                    label="تأكيد كلمة المرور"
                    v-model="form.password_confirmation"
                    :error="form.errors.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <div class="flex items-center justify-end mt-4">
                    <Link
                        :href="route('login')"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wisal-aqua mr-4"
                    >
                        لدي حساب بالفعل؟
                    </Link>

                    <WButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                        إنشاء حساب
                    </WButton>
                </div>
            </form>
            
             <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">أو سجل باستخدام</span>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a :href="route('auth.google.redirect')" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Sign in with Google</span>
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z" />
                        </svg>
                        <span class="ml-2">جوجل</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
