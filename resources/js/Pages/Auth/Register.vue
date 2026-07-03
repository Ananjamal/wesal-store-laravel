<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

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
    <Head title="حساب جديد · وِصال" />

    <div class="min-h-screen flex" dir="rtl" style="font-family: 'Noto Kufi Arabic', sans-serif; background: #f8f7f4;">

        <!-- ===== RIGHT PANEL: Brand / Hero ===== -->
        <div class="hidden lg:flex lg:w-1/2 xl:w-3/5 flex-col justify-between p-12 relative overflow-hidden"
            style="background: linear-gradient(135deg, #467389 0%, #2a4e62 60%, #1d3a4a 100%);">

            <!-- Decorative circles -->
            <div style="position:absolute;top:-80px;right:-80px;width:300px;height:300px;border-radius:50%;background:rgba(255,255,255,0.05);pointer-events:none;"></div>
            <div style="position:absolute;bottom:-100px;left:-50px;width:350px;height:350px;border-radius:50%;background:rgba(255,255,255,0.04);pointer-events:none;"></div>

            <!-- Logo + Brand Name -->
            <div class="relative z-10 flex items-center gap-4">
                <div style="width:64px;height:64px;border-radius:16px;overflow:hidden;border:2px solid rgba(209,203,180,0.5);box-shadow:0 8px 20px rgba(0,0,0,0.25);">
                    <img src="/images/logo.jpg" alt="Wisal Logo" style="width:100%;height:100%;object-fit:cover;" />
                </div>
                <div>
                    <h1 style="font-size:1.75rem;font-weight:900;color:#ffffff;margin:0;line-height:1;">وِصال</h1>
                    <p style="font-size:0.75rem;color:rgba(209,203,180,0.8);margin:0;letter-spacing:0.1em;text-transform:uppercase;">Wisal Store</p>
                </div>
            </div>

            <!-- Hero text -->
            <div class="relative z-10">
                <h2 style="font-size:2.5rem;font-weight:900;color:#ffffff;line-height:1.2;margin-bottom:1rem;">
                    إبداع بلا حدود،<br/>
                    <span style="color:#D1CBB4;">ابدأ معنا</span>
                </h2>
                <p style="color:rgba(255,255,255,0.7);font-size:1rem;line-height:1.7;max-width:400px;">
                    انضم إلى عائلة وِصال واكتشف عالماً من القرطاسية والأدوات المكتبية الفاخرة التي تزيد من إنتاجيتك وإبداعك.
                </p>

                <!-- Feature pills -->
                <div class="flex flex-wrap gap-3 mt-6">
                    <span style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);color:#fff;border-radius:9999px;padding:0.5rem 1rem;font-size:0.8rem;font-weight:600;">✦ عروض حصرية</span>
                    <span style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);color:#fff;border-radius:9999px;padding:0.5rem 1rem;font-size:0.8rem;font-weight:600;">✦ شحن سريع</span>
                    <span style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);color:#fff;border-radius:9999px;padding:0.5rem 1rem;font-size:0.8rem;font-weight:600;">✦ خدمة عملاء 24/7</span>
                </div>
            </div>

            <!-- Bottom caption -->
            <p class="relative z-10" style="color:rgba(255,255,255,0.4);font-size:0.75rem;">
                © {{ new Date().getFullYear() }} متجر وِصال · جميع الحقوق محفوظة
            </p>
        </div>

        <!-- ===== LEFT PANEL: Register Form ===== -->
        <div class="flex-1 flex flex-col justify-center px-6 sm:px-12 lg:px-16 xl:px-24 py-12 bg-white overflow-y-auto">

            <!-- Mobile logo -->
            <div class="flex justify-center mb-8 lg:hidden">
                <div style="width:80px;height:80px;border-radius:20px;overflow:hidden;border:2px solid #D1CBB4;box-shadow:0 8px 20px rgba(70,115,137,0.2);">
                    <img src="/images/logo.jpg" alt="Wisal" style="width:100%;height:100%;object-fit:cover;" />
                </div>
            </div>

            <div class="w-full max-w-md mx-auto">

                <!-- Heading -->
                <div class="mb-8">
                    <h2 style="font-size:1.875rem;font-weight:900;color:#1d3a4a;margin-bottom:0.5rem;">إنشاء حساب جديد</h2>
                    <p style="color:#6b7280;font-size:0.9rem;">انضم إلينا الآن واستمتع بتجربة تسوق فريدة</p>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-4">

                    <!-- Name -->
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;color:#374151;margin-bottom:0.5rem;">الاسم الكامل</label>
                        <input
                            id="name"
                            type="text"
                            v-model="form.name"
                            autocomplete="name"
                            required autofocus
                            placeholder="الاسم الكامل"
                            style="width:100%;padding:0.75rem 1rem;border:1.5px solid #e5e7eb;border-radius:0.75rem;font-size:0.95rem;color:#1f2937;outline:none;transition:border-color 0.2s;box-sizing:border-box;"
                            @focus="$event.target.style.borderColor='#467389'"
                            @blur="$event.target.style.borderColor='#e5e7eb'"
                        />
                        <div v-if="form.errors.name" style="color:#dc2626;font-size:0.8rem;margin-top:0.25rem;">{{ form.errors.name }}</div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;color:#374151;margin-bottom:0.5rem;">البريد الإلكتروني</label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            autocomplete="username"
                            required
                            placeholder="your@email.com"
                            style="width:100%;padding:0.75rem 1rem;border:1.5px solid #e5e7eb;border-radius:0.75rem;font-size:0.95rem;color:#1f2937;outline:none;transition:border-color 0.2s;box-sizing:border-box;"
                            @focus="$event.target.style.borderColor='#467389'"
                            @blur="$event.target.style.borderColor='#e5e7eb'"
                        />
                        <div v-if="form.errors.email" style="color:#dc2626;font-size:0.8rem;margin-top:0.25rem;">{{ form.errors.email }}</div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;color:#374151;margin-bottom:0.5rem;">رقم الهاتف</label>
                        <input
                            id="phone"
                            type="text"
                            v-model="form.phone"
                            autocomplete="tel"
                            placeholder="05xxxxxxxx"
                            style="width:100%;padding:0.75rem 1rem;border:1.5px solid #e5e7eb;border-radius:0.75rem;font-size:0.95rem;color:#1f2937;outline:none;transition:border-color 0.2s;box-sizing:border-box;"
                            @focus="$event.target.style.borderColor='#467389'"
                            @blur="$event.target.style.borderColor='#e5e7eb'"
                        />
                        <div v-if="form.errors.phone" style="color:#dc2626;font-size:0.8rem;margin-top:0.25rem;">{{ form.errors.phone }}</div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;color:#374151;margin-bottom:0.5rem;">كلمة المرور</label>
                        <input
                            id="password"
                            type="password"
                            v-model="form.password"
                            autocomplete="new-password"
                            required
                            placeholder="••••••••"
                            style="width:100%;padding:0.75rem 1rem;border:1.5px solid #e5e7eb;border-radius:0.75rem;font-size:0.95rem;color:#1f2937;outline:none;transition:border-color 0.2s;box-sizing:border-box;"
                            @focus="$event.target.style.borderColor='#467389'"
                            @blur="$event.target.style.borderColor='#e5e7eb'"
                        />
                        <div v-if="form.errors.password" style="color:#dc2626;font-size:0.8rem;margin-top:0.25rem;">{{ form.errors.password }}</div>
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;color:#374151;margin-bottom:0.5rem;">تأكيد كلمة المرور</label>
                        <input
                            id="password_confirmation"
                            type="password"
                            v-model="form.password_confirmation"
                            autocomplete="new-password"
                            required
                            placeholder="••••••••"
                            style="width:100%;padding:0.75rem 1rem;border:1.5px solid #e5e7eb;border-radius:0.75rem;font-size:0.95rem;color:#1f2937;outline:none;transition:border-color 0.2s;box-sizing:border-box;"
                            @focus="$event.target.style.borderColor='#467389'"
                            @blur="$event.target.style.borderColor='#e5e7eb'"
                        />
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        style="width:100%;padding:0.875rem;background:linear-gradient(135deg,#467389,#2a4e62);color:#fff;font-size:1rem;font-weight:700;border:none;border-radius:0.875rem;cursor:pointer;transition:opacity 0.2s,transform 0.1s;box-shadow:0 4px 14px rgba(70,115,137,0.35);"
                        :style="form.processing ? 'opacity:0.6;cursor:not-allowed;' : ''"
                    >
                        {{ form.processing ? 'جارٍ تسجيل الحساب...' : 'إنشاء حساب جديد' }}
                    </button>

                    <!-- Divider -->
                    <div style="display:flex;align-items:center;gap:1rem;">
                        <div style="flex:1;height:1px;background:#e5e7eb;"></div>
                        <span style="font-size:0.8rem;color:#9ca3af;">أو</span>
                        <div style="flex:1;height:1px;background:#e5e7eb;"></div>
                    </div>

                    <!-- Google -->
                    <a :href="route('auth.google.redirect')"
                        style="display:flex;align-items:center;justify-content:center;gap:0.75rem;width:100%;padding:0.875rem;background:#fff;border:1.5px solid #e5e7eb;border-radius:0.875rem;font-size:0.9rem;font-weight:600;color:#374151;text-decoration:none;transition:background 0.2s,border-color 0.2s;box-sizing:border-box;"
                        @mouseover="$event.currentTarget.style.borderColor='#467389'"
                        @mouseleave="$event.currentTarget.style.borderColor='#e5e7eb'"
                    >
                        <svg style="width:1.25rem;height:1.25rem;" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z" />
                        </svg>
                        المتابعة باستخدام جوجل
                    </a>

                    <!-- Login link -->
                    <p style="text-align:center;font-size:0.875rem;color:#6b7280;margin-top:1rem;">
                        لديك حساب بالفعل؟
                        <Link :href="route('login')" style="color:#467389;font-weight:700;text-decoration:none;margin-right:0.25rem;">تسجيل الدخول</Link>
                    </p>

                </form>
            </div>
        </div>
    </div>
</template>
