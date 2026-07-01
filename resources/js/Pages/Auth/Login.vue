<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import WCard from '@/Components/ui/WCard.vue';
import WInput from '@/Components/ui/WInput.vue';
import WButton from '@/Components/ui/WButton.vue';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="تسجيل الدخول" />

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            
            <div class="mb-4 text-center">
                <h1 class="text-2xl font-bold text-wisal-charcoal">تسجيل الدخول</h1>
                <p class="text-gray-600 mt-2">تسجيل الدخول لمتابعة التسوق في متجر وِصال</p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <WInput
                    id="email"
                    type="email"
                    label="البريد الإلكتروني"
                    v-model="form.email"
                    :error="form.errors.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <WInput
                    id="password"
                    type="password"
                    label="كلمة المرور"
                    v-model="form.password"
                    :error="form.errors.password"
                    required
                    autocomplete="current-password"
                />

                <div class="block mt-4">
                    <label class="flex items-center">
                        <input type="checkbox" v-model="form.remember" class="rounded border-gray-300 text-wisal-aqua shadow-sm focus:ring-wisal-aqua" name="remember" />
                        <span class="ms-2 text-sm text-gray-600">تذكرني</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <Link
                        v-if="$page.props.canResetPassword"
                        :href="route('password.request')"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wisal-aqua mr-4"
                    >
                        نسيت كلمة المرور؟
                    </Link>

                    <WButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                        تسجيل الدخول
                    </WButton>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">أو أكمل باستخدام</span>
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

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    لست عضواً؟
                    <Link :href="route('register')" class="font-medium text-wisal-aqua hover:text-wisal-aqua/80">
                        حساب جديد
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>
