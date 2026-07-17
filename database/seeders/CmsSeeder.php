<?php

namespace Database\Seeders;

use App\Models\ArticleCategory;
use App\Models\Banner;
use App\Models\HomepageSection;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Post;
use App\Models\Slider;
use App\Models\User;
use App\Enums\HomepageSectionType;
use App\Enums\PostStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. التأكد من وجود مستخدم كاتب للمقالات
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'إدارة متجر وصال',
                'email' => 'admin@wesal.com',
                'password' => bcrypt('password'),
            ]);
        }

        // 2. تصنيفات المقالات
        $categoriesData = [
            ['name' => 'أفكار الهدايا', 'slug' => 'gift-ideas', 'description' => 'أفكار ملهمة لتنسيق واختيار الهدايا لكل المناسبات.'],
            ['name' => 'أخبار وعروض', 'slug' => 'news-offers', 'description' => 'آخر عروض المتجر ومنتجاتنا الجديدة.'],
            ['name' => 'تنسيق المناسبات', 'slug' => 'event-planning', 'description' => 'نصائح لتجهيز وتزيين الحفلات والمناسبات السعيدة.'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[] = ArticleCategory::firstOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['is_active' => true, 'sort_order' => count($categories) + 1])
            );
        }

        // 3. مقالات نموذجية
        $postsData = [
            [
                'title' => 'كيف تختار الهدية المثالية لأحبائك؟ دليل مبسط',
                'slug' => 'how-to-choose-the-perfect-gift',
                'summary' => 'تعرف على الخطوات والنصائح الذهبية لاختيار هدية تلامس قلوب أحبائك وتدوم كذكرى جميلة.',
                'content' => '<h2>مقدمة في فن اختيار الهدايا</h2><p>الهدية ليست مجرد قيمة مادية بل هي تعبير عن المشاعر والاهتمام والوصل بين القلوب. إليك كيف تبدأ:</p><ul><li>حدد اهتمامات الشخص المفضلة.</li><li>اختر شيئاً ذا طابع شخصي أو يحمل رسالة خاصة.</li><li>اهتم بالتفاصيل وتغليف الهدية بشكل أنيق.</li></ul>',
                'article_category_id' => $categories[0]->id,
                'is_featured' => true,
            ],
            [
                'title' => 'أفضل 5 أفكار لتنسيق الهدايا الرمضانية',
                'slug' => 'top-5-ramadan-gift-ideas',
                'summary' => 'أفكار مميزة لتنسيق الهدايا والمصاحف وسجادات الصلاة الأنيقة خلال شهر الخير والرحمة.',
                'content' => '<p>مع اقتراب شهر رمضان المبارك، يسعى الكثير لمشاركة التهنئة من خلال هدايا رمضانية أنيقة تجمع بين الروحانية والجمال الفني كالمصاحف الفاخرة وسجادات الصلاة المنسقة يدوياً.</p>',
                'article_category_id' => $categories[0]->id,
                'is_featured' => false,
            ],
            [
                'title' => 'أحدث تشكيلة من دفاتر التخطيط والأجندات لعام جديد منظم',
                'slug' => 'new-planners-collection',
                'summary' => 'اكتشف مجموعتنا الجديدة من منظمات الوقت والأجندات الفاخرة التي تساعدك على تحقيق أهدافك.',
                'content' => '<p>يسعدنا إطلاق تشكيلة حصرية من الأجندات والمنظمات الفاخرة والمصممة خصيصاً لمساعدتكم في تنظيم مهامكم اليومية وأهدافكم السنوية بطريقة مبدعة وجذابة.</p>',
                'article_category_id' => $categories[1]->id,
                'is_featured' => false,
            ],
        ];

        foreach ($postsData as $post) {
            Post::firstOrCreate(
                ['slug' => $post['slug']],
                array_merge($post, [
                    'user_id' => $user->id,
                    'status' => PostStatus::Published,
                    'published_at' => now(),
                    'meta_title' => $post['title'],
                    'meta_description' => Str::limit($post['summary'], 150),
                    'keywords' => 'هدايا, تنسيق, وصال, أفكار',
                ])
            );
        }

        // 4. شرائح السلايدر (Sliders)
        $slidersData = [
            [
                'title' => 'بين كل هدية وذكرى — وِصال',
                'subtitle' => 'مجموعة حصرية من الهدايا المنسقة بحب وعناية',
                'description' => 'تصفح تشكيلة واسعة من المصاحف الملونة، سجادات الصلاة الفاخرة، والإكسسوارات المتميزة لتصنعوا أجمل الذكريات مع من تحبون.',
                'image' => 'https://images.unsplash.com/photo-1608755728617-aefab37d2edd?auto=format&fit=crop&w=1200&q=80',
                'button_text' => 'تسوق الآن',
                'button_link' => '/products',
                'sort_order' => 1,
            ],
            [
                'title' => 'جديد متجرنا: باقة التخطيط الفاخرة',
                'subtitle' => 'خطط لأهدافك بأناقة لا تضاهى',
                'description' => 'أجندات ودفاتر تخطيط مصممة بأجود خامات الورق والتجليد الفاخر، متوفرة الآن بألوان وتصاميم متنوعة تناسب ذوقك الرفيع.',
                'image' => 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?auto=format&fit=crop&w=1200&q=80',
                'button_text' => 'اكتشف التشكيلة',
                'button_link' => '/products',
                'sort_order' => 2,
            ],
        ];

        foreach ($slidersData as $slider) {
            Slider::updateOrCreate(
                ['title' => $slider['title']],
                array_merge($slider, ['is_active' => true])
            );
        }

        // 5. البنرات (Banners)
        $bannersData = [
            [
                'title' => 'شحن مجاني للطلبات فوق 299 شيكل',
                'description' => 'استمتع بشحن سريع ومجاني لجميع مناطق فلسطين عند الشراء بقيمة 299 شيكل أو أكثر.',
                'link' => '/products',
                'starts_at' => null,
                'ends_at' => null,
                'sort_order' => 1,
            ],
            [
                'title' => 'خصم 15% بمناسبة الافتتاح',
                'description' => 'استخدم كود الخصم WESAL15 عند الدفع، العرض سارٍ على جميع المنتجات لفترة محدودة.',
                'link' => '/products',
                'starts_at' => now(),
                'ends_at' => now()->addMonths(2),
                'sort_order' => 2,
            ],
        ];

        foreach ($bannersData as $banner) {
            Banner::updateOrCreate(
                ['title' => $banner['title']],
                array_merge($banner, ['is_active' => true])
            );
        }

        // 6. أقسام الصفحة الرئيسية (Homepage Sections)
        $sectionsData = [
            [
                'type' => HomepageSectionType::HeroSection->value,
                'title' => 'أهلاً بكم في متجر وصال',
                'subtitle' => 'وجهتكم الأولى للهدايا الأنيقة والمنسقة يدوياً',
                'description' => 'نصنع من تفاصيل هداياكم لحظات لا تُنسى تدوم في الذاكرة.',
                'items_count' => 1,
                'sort_order' => 10,
            ],
            [
                'type' => HomepageSectionType::Categories->value,
                'title' => 'تسوق حسب الأقسام',
                'subtitle' => 'تصفح أقسامنا المميزة',
                'description' => 'نوفر لكم أقساماً منوعة تسهل عليكم الوصول للمنتج المناسب.',
                'items_count' => 6,
                'sort_order' => 20,
            ],
            [
                'type' => HomepageSectionType::Offers->value,
                'title' => 'أقوى العروض الحالية',
                'subtitle' => 'لا تفوت فرصة التوفير والخصومات الحصرية',
                'description' => 'عروض مميزة وحصرية لفترة محدودة.',
                'items_count' => 4,
                'sort_order' => 30,
            ],
            [
                'type' => HomepageSectionType::LatestProducts->value,
                'title' => 'أحدث المنتجات',
                'subtitle' => 'وصل حديثاً',
                'description' => 'اكتشف آخر الهدايا والمنسقات التي أضيفت لمتجرنا حديثاً.',
                'items_count' => 8,
                'sort_order' => 40,
            ],
            [
                'type' => HomepageSectionType::FeaturedProducts->value,
                'title' => 'منتجاتنا المميزة',
                'subtitle' => 'مختارات وصال الأكثر طلباً',
                'description' => 'مجموعة من أرقى المنتجات التي يفضلها عملاؤنا.',
                'items_count' => 8,
                'sort_order' => 50,
            ],
            [
                'type' => HomepageSectionType::Testimonials->value,
                'title' => 'آراء عملائنا',
                'subtitle' => 'قصص سعيدة وكلمات نعتز بها من عائلة وصال',
                'description' => 'نفخر بثقة عملائنا ونسعى دوماً لتقديم الأفضل لهم.',
                'items_count' => 5,
                'sort_order' => 60,
            ],
            [
                'type' => HomepageSectionType::Articles->value,
                'title' => 'من مدونتنا',
                'subtitle' => 'أفكار ونصائح مميزة لاختيار وتنسيق الهدايا والمنظمات',
                'description' => 'نشارككم الإلهام من خلال مقالات تثري معلوماتكم.',
                'items_count' => 3,
                'sort_order' => 70,
            ],
            [
                'type' => HomepageSectionType::FAQ->value,
                'title' => 'الأسئلة الشائعة',
                'subtitle' => 'استفسارات متكررة',
                'description' => 'إجابات سريعة ومبسطة لأهم الأسئلة التي قد تراودك حول الشراء والتوصيل.',
                'items_count' => 4,
                'sort_order' => 80,
            ],
            [
                'type' => HomepageSectionType::Partners->value,
                'title' => 'شركاء النجاح',
                'subtitle' => 'نفتخر بالعمل معهم',
                'description' => 'شركات ومؤسسات ساهمت معنا في تقديم أفضل جودة.',
                'items_count' => 6,
                'sort_order' => 90,
            ],
            [
                'type' => HomepageSectionType::Newsletter->value,
                'title' => 'النشرة البريدية',
                'subtitle' => 'كن أول من يعلم',
                'description' => 'اشترك بنشرتنا البريدية لتصلك أحدث العروض والمنتجات الحصرية مباشرة في بريدك.',
                'items_count' => 1,
                'sort_order' => 100,
            ],
            [
                'type' => HomepageSectionType::ContactUs->value,
                'title' => 'تواصل معنا',
                'subtitle' => 'يسعدنا خدمتك في أي وقت',
                'description' => 'إذا كان لديك أي استفسار أو ترغب في تنسيق خاص، لا تتردد بالاتصال بنا.',
                'items_count' => 1,
                'sort_order' => 110,
            ],
        ];

        foreach ($sectionsData as $section) {
            HomepageSection::firstOrCreate(
                ['type' => $section['type']],
                array_merge($section, ['is_active' => true])
            );
        }

        // 7. القوائم وعناصرها (Menus & Menu Items)
        // قائمة الهيدر الرئيسية
        $headerMenu = Menu::firstOrCreate(
            ['location' => 'header'],
            ['name' => 'القائمة الرئيسية (الهيدر)', 'is_active' => true]
        );

        $headerItems = [
            ['title' => 'الرئيسية', 'url' => '/', 'sort_order' => 1],
            ['title' => 'المتجر', 'url' => '/products', 'sort_order' => 2],
            ['title' => 'الأقسام', 'url' => '/categories', 'sort_order' => 3],
            ['title' => 'المدونة', 'url' => '/blog', 'sort_order' => 4],
            ['title' => 'تواصل معنا', 'url' => '/contact', 'sort_order' => 5],
        ];

        foreach ($headerItems as $item) {
            MenuItem::firstOrCreate(
                ['menu_id' => $headerMenu->id, 'url' => $item['url']],
                array_merge($item, ['is_active' => true])
            );
        }

        // قائمة الفوتر
        $footerMenu = Menu::firstOrCreate(
            ['location' => 'footer'],
            ['name' => 'قائمة الفوتر (روابط سريعة)', 'is_active' => true]
        );

        $footerItems = [
            ['title' => 'من نحن', 'url' => '/about-us', 'sort_order' => 1],
            ['title' => 'سياسة الخصوصية', 'url' => '/privacy-policy', 'sort_order' => 2],
            ['title' => 'شروط الاستخدام', 'url' => '/terms', 'sort_order' => 3],
            ['title' => 'الأسئلة الشائعة', 'url' => '/faq', 'sort_order' => 4],
        ];

        foreach ($footerItems as $item) {
            MenuItem::firstOrCreate(
                ['menu_id' => $footerMenu->id, 'url' => $item['url']],
                array_merge($item, ['is_active' => true])
            );
        }
    }
}
