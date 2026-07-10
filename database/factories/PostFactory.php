<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $arabicTitles = [
            'كيف تختار الدفتر المناسب لتنظيم يومك؟',
            'أهمية كتابة الملاحظات اليومية لزيادة الإنتاجية',
            'أفكار إبداعية لاستخدام الملصقات في تزيين مكتبك',
            'دليلك الشامل لاختيار الهدايا اليدوية المميزة',
            'خطوات بسيطة لتنظيم وقتك والتخلص من الفوضى',
        ];

        $arabicSummaries = [
            'في هذه المقالة، نستعرض أهم النصائح والأدوات التي تساعدك على تنظيم مهامك اليومية واختيار الدفتر الملائم.',
            'تعرف على الأثر النفسي والعملي لتدوين اليوميات وكيف يسهم في تصفية ذهنك وزيادة تركيزك.',
            'الملصقات ليست للأطفال فقط! إليك طرق مبتكرة لاستخدامها كعنصر جمالي في مساحتك الخاصة.',
        ];

        $arabicContents = [
            "الكتابة والتدوين هي أحد أفضل الطرق التي تساعد في تنظيم الأفكار وترتيب الأولويات. سواء كنت طالباً أو موظفاً، فإن امتلاك دفتر ملاحظات مناسب يمكن أن يغير من روتينك اليومي ويزيد من إنتاجيتك بشكل ملحوظ.\n\nأولاً، يجب عليك تحديد الهدف من الدفتر: هل هو لكتابة المهام اليومية، أم لتدوين الأفكار والمشاريع الكبيرة؟ اختيار الحجم المناسب يلعب دوراً هاماً في سهولة حمله والتنقل به.",
            "يعتقد الكثيرون أن التخطيط اليومي يأخذ الكثير من الوقت، ولكن في الحقيقة هو يوفر عليك ساعات من الضياع والتشتت أثناء اليوم. كتابة قائمة المهام (To-Do List) في الليلة السابقة تمنحك وضوحاً تاماً وتجعلك تبدأ يومك بنشاط وتركيز كامل.\n\nتأكد من تقسيم المهام الكبيرة إلى مهام فرعية صغيرة لتسهيل إنجازها.",
        ];

        $title = $this->faker->randomElement($arabicTitles) . ' ' . $this->faker->unique()->numberBetween(1, 1000);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'summary' => $this->faker->randomElement($arabicSummaries),
            'content' => $this->faker->randomElement($arabicContents),
            'featured_image' => 'posts/default-post.jpg',
            'status' => PostStatus::Published,
            'published_at' => now(),
        ];
    }
}
