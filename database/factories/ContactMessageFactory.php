<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arabicSubjects = [
            'استفسار عن توافر كميات كبيرة',
            'طلب تعاون تجاري مع المتجر',
            'مشكلة في الدفع الإلكتروني',
            'استفسار حول مدة الشحن الدولي',
            'اقتراح لتطوير الموقع',
        ];

        $arabicMessages = [
            'مرحباً، أود الاستفسار عن إمكانية طلب كمية كبيرة من الدفاتر المخصصة لمؤسستنا وهل يوجد خصم؟ وشكراً.',
            'أواجه مشكلة عند محاولة الدفع ببطاقة مدى، تظهر لي رسالة خطأ بعد إدخال رمز التحقق. يرجى المساعدة.',
            'أقترح إتاحة خيار تغليف الهدايا وتخصيص كروت التهنئة قبل إتمام الطلب، سيكون ذلك رائعاً جداً.',
            'مرحباً، أود معرفة ما إذا كنتم تشحنون إلى مصر وكم تستغرق الشحنة للوصول بالعادة؟',
        ];

        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'subject' => fake()->randomElement($arabicSubjects),
            'message' => fake()->randomElement($arabicMessages),
            'is_read' => fake()->boolean(20),
        ];
    }
}
