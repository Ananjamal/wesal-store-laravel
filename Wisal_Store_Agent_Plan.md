# متجر وِصال — خطة الإنجاز الكاملة خلال 3 أسابيع + استراتيجية Git + التسطيب من الصفر

**✦ وِصال ✦**
**W I S A L S T O R E**

**خطة إنجاز المشروع الكاملة خلال 3 أسابيع — واجهة Vue.js + استراتيجية Git/GitHub**
*3-Week Full-Scope Plan — Vue.js Frontend & Git Branching Strategy*

---

## ⚠️ تنبيه إلزامي للـ Agent — الهوية البصرية (Brand Identity) — يُطبَّق حرفياً ولا يُستبدل

الألوان أدناه هي **الألوان الرسمية المعتمدة والنهائية** لهوية "وِصال" لعام 2025 (غاية وأثر). **يُمنع استخدام أي ألوان بديلة أو تخمينية** في أي واجهة، مكوّن، أو شاشة ضمن هذا المشروع. كل عمل تصميمي (Tailwind config، Design Tokens، Filament theme، CSS variables) يجب أن يُبنى على هذه القيم الأربع حصراً:

| اسم اللون | الاستخدام المقترح | HEX |
|---|---|---|
| **Beige Heritage** | لون تراثي دافئ — خلفيات ثانوية / تفاصيل تراثية | `#D1CBB4` |
| **Aqua Blue** | اللون الأساسي للهوية (Primary) — أزرار، روابط، عناصر تفاعلية رئيسية | `#467389` |
| **Ivory** | خلفية أساسية فاتحة (Background) — نظيفة وهادئة | `#FFFBF5` |
| **Charcoal** | نص أساسي / عناصر داكنة (Text & Dark UI) | `#323232` |

> هذه الألوان تخلق مزيجاً بين الشباب والهوية، وبين الحداثة والتقاليد، وهو جوهر فلسفة وِصال.

**تعليمات إلزامية للـ Agent:**
1. لا تُنشئ أي لون Primary/Secondary/Background/Text خارج هذه القائمة الأربعة إلا كدرجات (Shades/Tints) مشتقة رياضياً منها (فاتح/غامق) عند الحاجة لتباين (Contrast) أو حالات Hover/Active/Disabled.
2. عند إنشاء `tailwind.config.ts`، `Design Tokens`، أو `Filament Panel Theme`، استخدم أسماء المتغيرات التالية حرفياً حتى تبقى قابلة للتتبع في كل الفروع:
   - `wisal-beige` → `#D1CBB4`
   - `wisal-aqua` → `#467389`
   - `wisal-ivory` → `#FFFBF5`
   - `wisal-charcoal` → `#323232`
3. أي اقتراح لتغيير أو إضافة لون جديد يجب أن يُطرح كسؤال صريح قبل التنفيذ، ولا يُفترض أو يُخمَّن.
4. **جميع الموديولز يجب أن تبدو وكأن شخصاً واحداً كتبها بنفس الأسلوب من أول تاسك إلى آخر تاسك**، حتى لو تم تنفيذها في أيام مختلفة أو ضمن فروع منفصلة. هذا يعني الالتزام الحرفي بما يلي في كل ملف يُكتب طوال الـ21 يوماً:
   - **تسمية الملفات والمجلدات**: نمط واحد ثابت (مثال: PascalCase لأسماء الـ Models والـ Components، kebab-case لأسماء ملفات الصفحات في Nuxt، snake_case لأعمدة قاعدة البيانات) — لا تبديل بين الأنماط بين تاسك وآخر.
   - **تسمية المتغيرات والدوال**: نفس القواعد النحوية (camelCase في JS/TS، snake_case في PHP) بلا استثناء، وأسماء معبّرة لا اختصارات غامضة.
   - **بنية المجلدات**: كل Feature جديد يتبع نفس الهيكل بالضبط (مثال: كل Filament Resource يحتوي نفس ترتيب الأقسام: Form → Table → Relations → Pages؛ كل صفحة Nuxt تتبع نفس ترتيب `<script setup> → <template> → <style>`).
   - **أسلوب معالجة الأخطاء**: نمط try/catch أو Form Requests أو Exception Handling موحّد في كل الباك-إند، ونمط موحّد لعرض رسائل الخطأ في الفرونت (Toast واحد بنفس الشكل، لا حلول متفرقة).
   - **أسلوب التعليقات (Comments)**: إما بالعربية أو بالإنجليزية بشكل ثابت طوال المشروع (يُفضّل الإنجليزية للكود مع تعليقات توضيحية عربية عند الحاجة فقط)، وبنفس الكثافة (لا ملف مليء بالتعليقات وآخر خالٍ تماماً).
   - **إعادة استخدام المكوّنات المشتركة**: قبل إنشاء أي مكوّن جديد (زر، حقل، بطاقة، Modal)، يجب التحقق أولاً من مكتبة `WButton, WCard, WInput, WBadge, WSkeleton, WModal` (فرع features/frontend/design-system) وإعادة استخدامها بدل إعادة كتابتها من الصفر في كل صفحة.
   - **صيغة الـ API Responses**: كل الـ API Resources في Laravel تُعيد نفس بنية JSON الموحّدة (success, data, message, errors) بلا استثناء عبر كل الـ Controllers.
   - **قبل بدء أي تاسك جديد**: يجب على الـ Agent مراجعة ملف أو تاسك سابق مشابه (نفس النوع: Filament Resource آخر، أو صفحة Nuxt أخرى) والاقتداء بنفس نمط الكتابة فيه حرفياً، بدل البدء من الصفر بأسلوب مختلف.

---

## المعلومات الأساسية

| البند | التفاصيل |
|---|---|
| **المدة** | 21 يوماً متتالياً (3 أسابيع — 7 أيام عمل أسبوعياً) |
| **النطاق** | كامل — جميع الميزات في الوثيقة التقنية الأصلية دون استثناء (لا يوجد تأجيل ميزات) |
| **الفرونت-إند** | Vue.js عبر Nuxt 3 — SSR للصفحات العامة + SPA للوحة العميل |
| **الباك-إند** | Laravel 11 API + Sanctum — Filament v3 للوحة التحكم |
| **حجم الفريق المطلوب** | 5-6 مطورين متخصصين بحد أدنى، يعملون بالتوازي الكامل يومياً على فروع مستقلة |
| **إدارة المصدر** | GitHub — استراتيجية فروع ثلاثية (main / staging / develop) + فروع Features منظمة |
| **الهوية البصرية** | 4 ألوان معتمدة رسمياً (انظر الجدول أعلاه) — إلزامية التطبيق |

---

## ملاحظة صريحة قبل البدء *(Reality Check)*

تغطية النطاق الكامل لمتجر بهذا الحجم (24 جدول بيانات، لوحتا تحكم، 4 بوابات/طرق دفع، نظام ولاء وإحالة، بحث ذكي، إشعارات فورية...) خلال 3 أسابيع فقط **ممكن**، لكن بشرط:

- فريق من 5-6 مطورين متخصصين (Backend, Frontend Vue, Admin/Filament, DevOps) يعملون بالتوازي الكامل كل يوم على فروع مستقلة يتم دمجها باستمرار.
- العمل 7 أيام أسبوعياً (سباق مكثف) والدمج المستمر (Continuous Integration) اليومي لتفادي تعارضات الفروع المتراكمة.
- مراجعة يومية سريعة (Daily Standup) بين كل المسارات لضمان تزامن تكامل الـAPI مع Nuxt فور جاهزية كل جزء.

---

## استراتيجية Git / GitHub *(Branching Strategy)*

يعتمد المشروع نموذج فروع ثلاثي المستوى (Three-Tier Branching) مطابق لتدفق النشر: تطوير → اختبار → إنتاج، مع فروع Features منظمة حسب الوحدة والميزة.

### الفروع الرئيسية الثابتة

| الفرع | الغرض | قواعد الحماية والدمج |
|---|---|---|
| **main** | فرع الإنتاج (Production) — الكود الفعلي المنشور للعملاء دائماً قابل للنشر | محمي بالكامل — لا Push مباشر — يُدمج فقط عبر Pull Request من staging بعد موافقة ≥1 مراجع واجتياز كل الاختبارات — كل دمج يُوسَم بإصدار (v1.0.0, v1.1.0...) |
| **staging** | بيئة ما قبل الإنتاج — نسخة طبق الأصل من الإعدادات الحقيقية لاختبار القبول (UAT) قبل الإطلاق | محمي — يُدمج عبر Pull Request من develop فقط — يتطلب اجتياز CI (اختبارات + Lint) |
| **develop** | فرع التكامل — كل الفروع المكتملة (features/*) تُدمج هنا أولاً | يتطلب Pull Request + اجتياز CI — لا حاجة لموافقة مراجع في الفريق الصغير، لكن يُفضّل مراجعة سريعة |

### فروع الميزات (Feature Branches)

تُبنى كل ميزة أو شاشة في فرع منفصل يتفرّع من develop، بتسمية موحدة تعكس القسم والميزة بدقة:

```
features/<القسم>/<اسم-الميزة>
```

| القسم | أمثلة فروع |
|---|---|
| **admin** | features/admin/categories — features/admin/products — features/admin/orders — features/admin/coupons — features/admin/flash-sales — features/admin/reports |
| **backend** | features/backend/auth — features/backend/cart — features/backend/payment-stripe — features/backend/payment-paypal — features/backend/search — features/backend/notifications |
| **frontend** | features/frontend/home-page — features/frontend/shop-page — features/frontend/product-detail — features/frontend/checkout — features/frontend/customer-dashboard |

### فروع الطوارئ والإصدارات

| الفرع | الاستخدام |
|---|---|
| **hotfix/<اسم-المشكلة>** | لإصلاح عاجل في الإنتاج — يتفرّع من main مباشرة، ويُدمج في main وdevelop معاً بعد الإصلاح |
| **release/vX.Y.Z** | اختياري عند الحاجة لتجميد ميزات إصدار معيّن أثناء استمرار تطوير التالي — يتفرّع من develop قبل الدمج في staging |

### تدفق العمل (Workflow)

```
feature/* → Pull Request → develop → Pull Request → staging → اختبار قبول → main (production)
```

**قواعد إضافية:**
- رسائل الـCommit تتبع نمط Conventional Commits (feat:, fix:, chore:, docs:, refactor:, test:)
- كل Pull Request يجب أن يمر عبر GitHub Actions (تشغيل الاختبارات + ESLint/Pint) قبل السماح بالدمج
- لا يُحذف أي فرع feature إلا بعد التأكد من دمجه بنجاح في develop

---

## تقنيات السلاسة *(Fluidity, built-in from day one)*

مدمجة في كل مرحلة، وليست خطوة لاحقة:

- Skeleton Loaders بدل شاشات التحميل
- Optimistic UI عند إضافة عناصر للسلة والمفضلة
- View Transitions ناعمة بين الصفحات
- Prefetching عند التحويم
- Virtual Scrolling للقوائم الطويلة
- تحسين الصور تلقائياً عبر Nuxt Image

---

# قسم تنفيذي — تسطيب المشروع من الصفر *(Full Project Installation — From Zero)*

يُطبَّق هذا القسم في **اليوم 1** قبل بدء أي فرع feature.

## المتطلبات الأساسية *(Prerequisites)*

| الأداة | الإصدار المطلوب | التحقق |
|---|---|---|
| PHP | 8.3 أو أحدث | `php -v` |
| Composer | 2.x | `composer -V` |
| Node.js | 20 LTS أو أحدث | `node -v` |
| npm / pnpm | أحدث إصدار (يُفضّل pnpm) | `pnpm -v` |
| MySQL / PostgreSQL | MySQL 8+ أو PostgreSQL 15+ | `mysql --version` |
| Redis | 7.x | `redis-server -v` |
| Git | أحدث إصدار | `git -v` |
| Meilisearch | أحدث إصدار (للبحث الذكي) | `meilisearch --version` |

## الخطوة 1 — تجهيز مستودع GitHub واستراتيجية الفروع

```bash
mkdir wisal-store && cd wisal-store
git init

git remote add origin https://github.com/<org>/wisal-store.git

git checkout -b develop
git push -u origin develop

git checkout -b staging
git push -u origin staging

git checkout main 2>/dev/null || git checkout -b main
git push -u origin main
```

**تفعيل قواعد الحماية (Branch Protection) من إعدادات GitHub:**
- `main`: "Require pull request before merging" + "Require approvals (1)" + "Require status checks to pass" + منع الـ Push المباشر
- `staging`: نفس القواعد لكن بدون اشتراط موافقة مراجع إن رغب الفريق
- `develop`: "Require status checks to pass" فقط (CI)

**بنية المستودع المقترحة (Monorepo اختياري):**
```
wisal-store/
├── backend/     ← Laravel 11
├── frontend/    ← Nuxt 3
└── .github/workflows/
```

## الخطوة 2 — تسطيب الباك-إند (Laravel 11 + Filament v3 + Sanctum)

```bash
cd backend
composer create-project laravel/laravel . "^11.0"

cp .env.example .env
php artisan key:generate
```

**تعديل `.env` الأساسي:**
```env
APP_NAME="Wisal Store"
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_DATABASE=wisal_store
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DOMAIN=localhost

REDIS_HOST=127.0.0.1
CACHE_STORE=redis
QUEUE_CONNECTION=redis

SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://127.0.0.1:7700
```

**تثبيت الحزم الأساسية:**
```bash
# Sanctum (مصادقة SPA)
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Filament v3 (لوحة التحكم)
composer require filament/filament:"^3.0"
php artisan filament:install --panels

# Spatie Roles & Permissions
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# Spatie Media Library (رفع الصور)
composer require spatie/laravel-medialibrary
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"

# Laravel Scout + Meilisearch (البحث الذكي)
composer require laravel/scout meilisearch/meilisearch-php http-interop/http-factory-guzzle

# Laravel Horizon (مراقبة الطوابير)
composer require laravel/horizon
php artisan horizon:install

# Pusher (إشعارات فورية)
composer require pusher/pusher-php-server

# توليد PDF (فواتير)
composer require barryvdh/laravel-dompdf

# QR Code
composer require simplesoftwareio/simple-qrcode

# Socialite (تسجيل دخول Google)
composer require laravel/socialite
```

**إعداد CORS للتواصل مع Nuxt** — تعديل `config/cors.php`:
```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['http://localhost:3000'],
'supports_credentials' => true,
```

**تشغيل قاعدة البيانات والسيرفر:**
```bash
php artisan migrate
php artisan db:seed
php artisan serve
```

**Commit:**
```bash
git add .
git commit -m "chore: initial Laravel 11 + Filament v3 + Sanctum setup"
git push -u origin develop
```

## الخطوة 3 — تسطيب الفرونت-إند (Nuxt 3 + TypeScript + Tailwind + Pinia + VueUse)

```bash
cd frontend
npx nuxi@latest init . --package-manager pnpm --gitInit false
```

**تثبيت الحزم الأساسية (اختيار TypeScript عند السؤال):**
```bash
pnpm add -D @nuxtjs/tailwindcss
pnpm add @pinia/nuxt pinia
pnpm add @vueuse/nuxt @vueuse/core
pnpm add @nuxt/image
pnpm add nuxt-icon
```

**تعديل `nuxt.config.ts`:**
```typescript
export default defineNuxtConfig({
  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
    '@vueuse/nuxt',
    '@nuxt/image',
  ],
  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000',
    },
  },
  ssr: true, // SSR للصفحات العامة
  typescript: { strict: true },
})
```

**تهيئة Tailwind بالألوان الرسمية المعتمدة لهوية وِصال (إلزامي — لا يُستبدل):**

`tailwind.config.ts`:
```typescript
export default {
  theme: {
    extend: {
      colors: {
        'wisal-beige':    '#D1CBB4', // Beige Heritage
        'wisal-aqua':     '#467389', // Aqua Blue — Primary
        'wisal-ivory':    '#FFFBF5', // Ivory — Background
        'wisal-charcoal': '#323232', // Charcoal — Text & Dark UI
      },
    },
  },
}
```

**إعداد ملف البيئة:**
```bash
cp .env.example .env 2>/dev/null || touch .env
echo "NUXT_PUBLIC_API_BASE=http://localhost:8000" >> .env
```

**تشغيل السيرفر محلياً:**
```bash
pnpm dev
```

**Commit:**
```bash
git add .
git commit -m "chore: initial Nuxt 3 + Tailwind (Wisal brand colors) + Pinia + VueUse setup"
git push -u origin develop
```

## الخطوة 4 — إعداد GitHub Actions (CI/CD أولي)

`.github/workflows/backend-ci.yml`:
```yaml
name: Backend CI
on:
  pull_request:
    paths: ['backend/**']
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
      - run: cd backend && composer install
      - run: cd backend && ./vendor/bin/pint --test
      - run: cd backend && php artisan test
```

`.github/workflows/frontend-ci.yml`:
```yaml
name: Frontend CI
on:
  pull_request:
    paths: ['frontend/**']
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: pnpm/action-setup@v3
      - uses: actions/setup-node@v4
        with:
          node-version: 20
      - run: cd frontend && pnpm install
      - run: cd frontend && pnpm lint
      - run: cd frontend && pnpm vitest run
```

## الخطوة 5 — قائمة تحقق نهائية قبل بدء العمل بالفروع

- [ ] فروع main / staging / develop منشأة ومحمية على GitHub
- [ ] Laravel 11 يعمل محلياً ويتصل بقاعدة البيانات
- [ ] Filament v3 مثبت ولوحة الأدمن الافتراضية تفتح على `/admin`
- [ ] Sanctum مُعد وCORS يسمح بطلبات من `localhost:3000`
- [ ] Nuxt 3 يعمل محلياً على `localhost:3000` ويتصل بالـ API
- [ ] الألوان الأربعة الرسمية (`wisal-beige`, `wisal-aqua`, `wisal-ivory`, `wisal-charcoal`) مضافة في `tailwind.config.ts` وتُستخدم فعلياً في المكونات الأولى
- [ ] GitHub Actions يعمل تلقائياً عند فتح أي Pull Request
- [ ] كل عضو فريق قادر على استنساخ المستودع وتشغيل المشروع محلياً بنفس الخطوات

---

# منهجية تنفيذ التاسكات — دليل عمل الـ Agent *(Task Execution Protocol)*

هذا القسم يشرح **آلية موحّدة** يجب على الـ Agent اتباعها حرفياً مع كل تاسك في الخطة، بغض النظر عن اليوم أو القسم (admin / backend / frontend). الهدف أن يتحول كل سطر مهمة في الخطة إلى دورة عمل واضحة ومتكررة بلا غموض.

## الخطوة 0 — قبل البدء بأي تاسك

1. تأكد أنك على فرع `develop` محدَّث بآخر تغييرات:
   ```bash
   git checkout develop
   git pull origin develop
   ```
2. أنشئ فرع الميزة بالاسم الدقيق المذكور في الخطة (لا تُغيّر التسمية ولا تختصرها):
   ```bash
   git checkout -b features/<القسم>/<اسم-الميزة>
   ```
3. اقرأ سطر التاسك في الخطة بالكامل، وحدّد:
   - هل هو Backend فقط، Frontend فقط، أم مشترك (يتطلب فرعين منفصلين يعملان بالتوازي)؟
   - هل يعتمد على تاسك سابق لم يُدمج بعد في develop؟ إن كان كذلك، انتظر دمجه أو نسّق معه قبل البدء.

## الخطوة 1 — تفكيك التاسك إلى خطوات فرعية (Sub-tasks)

كل سطر في الخطة (مثل "Filament Resource كامل للمنتجات + رفع صور متعددة + إدارة المخزون") **ليس تاسكاً واحداً** بل حزمة صغيرة. يجب على الـ Agent تفكيكه داخلياً قبل الكتابة وفق **قالب ثابت حسب نوع التاسك**، لضمان أن كل تاسك من نفس النوع يُنفَّذ بنفس الترتيب والأسلوب تماماً (اتساقاً مع تعليمة "شخص واحد كتب كل شيء" أعلاه).

### النوع 1 — تاسك Backend API (مثال: features/backend/reviews)

```
1. Migration: تعريف الجدول (إن لم يكن موجوداً من اليوم 2) — snake_case لكل الأعمدة
2. Model: العلاقات (relationships) + fillable + casts
3. Form Request: كلاس Validation منفصل (StoreReviewRequest / UpdateReviewRequest) — لا تحقق داخل الـ Controller مباشرة
4. Policy: صلاحيات الوصول (من يملك حق الإنشاء/التعديل/الحذف)
5. API Resource: تنسيق الاستجابة بصيغة JSON الموحّدة (success, data, message, errors)
6. Controller: دوال RESTful قياسية فقط (index, store, show, update, destroy) — لا دوال عشوائية بأسماء حرة
7. Route: تسجيله ضمن api.php بنفس نمط تجميع الـ Routes الموجود (Route::apiResource أو مجموعة مسمّاة)
8. Test: اختبار Pest واحد على الأقل يغطي الحالة الناجحة وحالة فشل Validation
9. Commit منفصل لكل بند من 1 إلى 8 (لا commit واحد ضخم)
```

### النوع 2 — تاسك Filament Admin Resource (مثال: features/admin/products)

```
1. php artisan make:filament-resource <Model> --generate
2. Form Schema: نفس ترتيب الحقول المستخدم في كل الـ Resources السابقة (معلومات أساسية → علاقات → وسائط → حالة/تفعيل)
3. Table Schema: نفس أعمدة الحالة القياسية (badge للحالة، تاريخ الإنشاء، إجراءات) بنفس تنسيق الألوان الرسمية
4. Relations (إن وُجدت): RelationManager بنفس نمط الموجود في Resources مشابهة
5. Media/Uploads (إن وُجدت): SpatieMediaLibraryFileUpload بنفس الإعدادات (الحجم، الصيغ المسموحة) المستخدمة في باقي اللوحة
6. Filters وBulk Actions: بنفس الأسلوب المستخدم سابقاً (لا اختراع طريقة جديدة لكل Resource)
7. Test: اختبار Pest للتأكد من ظهور الصفحة وإمكانية الإنشاء/التعديل عبر اللوحة
8. Commit منفصل لكل بند
```

### النوع 3 — تاسك Frontend صفحة/شاشة كاملة (مثال: features/frontend/shop-page)

```
1. تعريف الـ Route/الصفحة ضمن نظام Nuxt File-based Routing (اسم الملف بنمط kebab-case)
2. Composable/Store: أي منطق بيانات يُستخرج لـ composable أو Pinia store منفصل (لا منطق أعمال داخل <script setup> مباشرة إن كان قابلاً لإعادة الاستخدام)
3. هيكل الملف: <script setup lang="ts"> أولاً → <template> ثانياً → <style> أخيراً (ثابت في كل الصفحات)
4. إعادة استخدام المكوّنات من design-system (WButton, WCard...) بدل إنشاء نسخة جديدة
5. Skeleton Loader أثناء التحميل (إلزامي وفق قسم "تقنيات السلاسة")
6. الألوان: wisal-beige / wisal-aqua / wisal-ivory / wisal-charcoal فقط عبر classes Tailwind المعرّفة
7. معالجة حالة الخطأ (Error State) وحالة عدم وجود بيانات (Empty State) بنفس المكوّن المستخدم في باقي الصفحات
8. Test: اختبار Vitest لأي منطق منطقي داخل composable/store (وليس بالضرورة لكل عنصر واجهة)
9. Commit منفصل لكل بند

مثال تطبيقي فعلي للتاسك "features/admin/products":
├── 1. إنشاء Migration/Model إن لم تكن موجودة (أو التأكد من جاهزيتها من اليوم 2)
├── 2. إنشاء Filament Resource (php artisan make:filament-resource Product)
├── 3. إعداد الحقول (Form Schema): اسم، وصف، سعر، تصنيف، حالة
├── 4. دمج Spatie Media Library لرفع صور متعددة (Repeater/SpatieMediaLibraryFileUpload)
├── 5. إضافة إدارة المخزون (quantity, low-stock alert)
├── 6. كتابة اختبار Pest بسيط للتأكد من إنشاء/تعديل منتج عبر اللوحة
└── 7. Commit لكل خطوة فرعية بشكل منفصل ومنطقي (لا commit ضخم واحد)
```

### النوع 4 — تاسك مكوّن واجهة صغير قابل لإعادة الاستخدام (مثال: WBadge، Cart Drawer)

```
1. تحديد الـ Props والـ Events بوضوح (TypeScript interface صريح، لا any)
2. بناء المكوّن بأصغر مسؤولية ممكنة (Single Responsibility) — لا يحتوي منطق صفحة كاملة
3. تطبيق الألوان الرسمية عبر Props قابلة للتخصيص (variant) بدل تكرار Tailwind classes يدوياً في كل استخدام
4. توثيق طريقة الاستخدام بمثال قصير في تعليق أعلى الملف
5. إضافته إلى مكتبة design-system المركزية فوراً (لا تركه محلياً داخل صفحة واحدة إن كان قابلاً لإعادة الاستخدام)
6. Commit واحد أو اثنين (المكوّن صغير بطبيعته)
```

**القاعدة الذهبية لكل الأنواع الأربعة**: قبل كتابة أي سطر كود، يبحث الـ Agent عن أقرب تاسك مشابه سبق تنفيذه في المشروع (نفس النوع)، ويقرأ بنيته، ثم يتبع نفس الترتيب والتسمية والأسلوب حرفياً في التاسك الجديد.

هذا التفكيك ينطبق على **كل تاسك في الخطة** دون استثناء — الهدف تسهيل المراجعة (Code Review) وتتبع التقدم يومياً، وضمان اتساق الأسلوب عبر كل الموديولز.

## الخطوة 2 — قواعد الكتابة أثناء التنفيذ

- **Commits صغيرة ومتكررة** بنمط Conventional Commits:
  ```
  feat(admin): add product filament resource with media upload
  feat(admin): add stock management fields to product resource
  test(admin): add pest test for product creation
  ```
- **لا تُدمج مهمتين غير مرتبطتين في نفس الفرع** — إن اكتشفت أثناء العمل حاجة لتاسك غير مذكور في الخطة (مثل إصلاح باگ في مكان آخر)، افتح له فرعاً منفصلاً بنفس نمط التسمية `hotfix/<اسم-المشكلة>` أو `features/<القسم>/<اسم>`.
- **التزم بألوان الهوية الرسمية** في أي عنصر واجهة (راجع القسم الخاص بالهوية البصرية أعلاه) — لا اجتهاد في الألوان.
- **اكتب اختباراً واحداً على الأقل** لكل تاسك خلفي (Pest) أو واجهي (Vitest) قبل فتح الـ Pull Request — تاسك بلا اختبار لا يُعتبر مكتملاً.
- **وثّق أي قرار تقني غير بديهي** في وصف الـ Commit أو الـ Pull Request (مثال: "استخدمت Cursor Pagination بدل Offset لتحسين الأداء مع 24 جدول").

## الخطوة 3 — معايير "التاسك مكتمل" *(Definition of Done)*

لا يُعتبر أي تاسك مكتملاً إلا إذا تحققت كل النقاط التالية:

- [ ] الكود يعمل محلياً بدون أخطاء (`php artisan serve` / `pnpm dev` يعملان بلا Exceptions)
- [ ] الاختبارات المرتبطة بالتاسك تمر بنجاح محلياً (`php artisan test` أو `pnpm vitest run`)
- [ ] الـ Lint نظيف (`./vendor/bin/pint` للباك-إند، `pnpm lint` للفرونت)
- [ ] الألوان والخطوط والمكوّنات تطابق Design System ووِصال الرسمية
- [ ] لا يوجد أي `console.log`, `dd()`, `dump()` أو كود تجريبي متروك
- [ ] تمت كتابة أو تحديث الاختبار المناسب
- [ ] تم فتح Pull Request إلى `develop` مع وصف واضح لما تم إنجازه

## الخطوة 4 — فتح الـ Pull Request

قالب موحّد لعنوان ووصف كل Pull Request (يُستخدم حرفياً):

```markdown
## العنوان
feat(<القسم>): <وصف مختصر للتاسك كما ورد في الخطة>

## الوصف
- ما الذي أُنجز؟ (نقاط مختصرة)
- هل يعتمد على فرع/تاسك آخر؟ اذكره
- هل يغيّر أي API أو Schema موجود؟ اشرح التأثير

## الاختبار
- كيف تم اختبار التاسك محلياً؟
- أي Edge Cases تم تغطيتها؟

## Checklist
- [ ] الاختبارات تمر
- [ ] Lint نظيف
- [ ] الألوان مطابقة للهوية الرسمية
- [ ] لا كود تجريبي متروك
```

بعد الفتح: ينتظر الـ Agent اجتياز GitHub Actions (CI) تلقائياً، ثم مراجعة سريعة (حتى لو من عضو واحد في الفريق الصغير)، ثم الدمج في `develop`.

## الخطوة 5 — بعد الدمج

```bash
git checkout develop
git pull origin develop
git branch -d features/<القسم>/<اسم-الميزة>   # حذف الفرع محلياً بعد التأكد من الدمج
```

ثم الانتقال مباشرة للتاسك التالي في نفس اليوم بنفس الدورة (الخطوة 0 → 5).

## الخطوة 6 — عند نهاية كل أسبوع (اليوم 7 / 14 / 21)

هذه الأيام مخصصة "للفريق كامل" في الخطة، وتشمل بروتوكولاً إضافياً:

1. التأكد أن كل فروع الأسبوع مدموجة في `develop` (لا فروع معلّقة).
2. نشر `develop` إلى `staging`:
   ```bash
   git checkout staging
   git pull origin staging
   git merge develop
   git push origin staging
   ```
3. تشغيل اختبار قبول شامل (Bug Bash) يدوياً على بيئة staging، وتسجيل أي خلل كـ Issue على GitHub بعنوان واضح (`bug: <وصف>`).
4. إصلاح الأخطاء المكتشفة كل واحد في فرع `hotfix/<اسم-المشكلة>` مستقل، ثم دمجه في `develop` (وفي `staging` بعد الدمج).
5. فقط في اليوم 21: بعد اجتياز كل الاختبارات (Pest + Vitest + E2E)، يُفتح Pull Request من `staging` إلى `main`، ويُدمج بعد الموافقة، ويُوسَم بـ `v1.0.0`.

## ملخص سريع لدورة كل تاسك (مرجع بصري)

```
develop → git pull
   ↓
إنشاء features/<قسم>/<ميزة>
   ↓
تفكيك التاسك لخطوات فرعية
   ↓
تنفيذ + commit صغير لكل خطوة (بالألوان الرسمية دائماً)
   ↓
كتابة اختبار
   ↓
تشغيل Lint + Test محلياً
   ↓
Pull Request → develop (بالقالب أعلاه)
   ↓
CI يعمل تلقائياً → مراجعة → دمج
   ↓
حذف الفرع → الانتقال للتاسك التالي
```

هذه الدورة تنطبق على **كل تاسك من اليوم 1 حتى اليوم 20** دون استثناء، وتضمن أن عمل 5-6 مطورين (أو Agent يعمل بالنيابة عنهم) يبقى منظماً وقابلاً للدمج المستمر دون تعارضات متراكمة.

---

# خارطة الطريق — 21 يوماً *(Day-by-Day Full-Scope Plan)*

## الأسبوع 1 — الأساس الكامل + لوحة تحكم الأدمن *(الأيام 1-7)*

**هدف الأسبوع:** بناء البنية التحتية الكاملة (Git + Backend + Nuxt) وإنجاز لوحة تحكم Filament بكل شاشاتها دون استثناء.

**اليوم 1 — الفريق كامل**
- إنشاء مستودع GitHub وتطبيق استراتيجية الفروع كاملة: main / staging / develop + قواعد الحماية (Branch Protection)
- تنفيذ قسم "تسطيب المشروع من الصفر" أعلاه بالكامل: Laravel 11 + Filament v3 + Sanctum على فرع develop + إعداد CORS
- تنصيب Nuxt 3 (TypeScript) + Tailwind **بالألوان الرسمية الأربعة لوِصال** + Pinia + VueUse
- إعداد GitHub Actions أولي: تشغيل الاختبارات والـLint تلقائياً عند كل Pull Request

**اليوم 2 — Backend / API**
- فرع features/backend/database-schema: كتابة كامل الـ24 Migration (users, categories, products, product_images, orders, order_items, carts, cart_items, addresses, coupons, reviews, wishlists, posts, notifications, loyalty_points, referrals, gift_cards, flash_sales, audit_logs...)
- بناء Eloquent Models كاملة مع العلاقات + Factories + Seeders لبيانات تجريبية واقعية
- Pull Request إلى develop بعد مراجعة زميل + اجتياز الاختبارات الآلية

**اليوم 3 — Backend + Frontend**
- فرع features/backend/auth: Sanctum SPA auth كامل + Spatie Roles (Admin/Manager/Customer) + Policies + Social Login (Google)
- فرع features/frontend/design-system: مكتبة المكونات الأساسية (WButton, WCard, WInput, WBadge, WSkeleton, WModal) + Design Tokens **مبنية على ألوان وِصال الرسمية (Beige Heritage #D1CBB4 / Aqua Blue #467389 / Ivory #FFFBF5 / Charcoal #323232)**
- فرع features/frontend/auth-pages: صفحات تسجيل الدخول/التسجيل + Pinia auth store + route middleware

**اليوم 4 — Admin (Filament)**
- فرع features/admin/categories: Filament Resource كامل للأقسام مع الأقسام الفرعية parent_id
- فرع features/admin/products: Filament Resource للمنتجات + رفع صور متعددة (Spatie Media Library) + إدارة المخزون
- فرع features/backend/api-catalog: API Resources + Controllers: /api/categories, /api/products مع فلاتر وفرز وCursor Pagination

**اليوم 5 — Admin (Filament)**
- فرع features/admin/orders: Filament Resource للطلبات مع تحديث الحالة وTimeline مرئي
- فرع features/admin/coupons: كوبونات بجميع الأنواع (نسبة/ثابت/شحن مجاني/اشتر واحصل)
- فرع features/admin/flash-sales: شاشة عروض محدودة الوقت بمؤقت عد تنازلي
- فرع features/admin/users: إدارة المستخدمين (عرض/تعديل/حظر/تعيين دور)

**اليوم 6 — Admin (Filament)**
- فرع features/admin/blog: كتابة/تعديل/نشر/أرشفة المقالات بمحرر نصوص غني
- فرع features/admin/reports: تقارير المبيعات والإيرادات والعملاء + تصدير PDF/Excel
- فرع features/admin/settings: إعدادات المتجر العامة + Audit Log + Dashboard إحصائي كامل (رسوم بيانية + تنبيهات فورية) **بألوان الهوية المعتمدة**

**اليوم 7 — الفريق كامل**
- دمج جميع فروع الأسبوع في develop عبر Pull Requests مراجَعة، ثم نشر develop إلى بيئة staging للاختبار
- مراجعة شاملة لمنتصف السباق (Mid-Sprint Review): اختبار لوحة الأدمن كاملة ببيانات حقيقية
- إصلاح الأخطاء المتراكمة (Bug Bash) وتجهيز خطة أسبوع الفرونت-إند بدقة

---

---

## أمثلة كود مرجعية — مستوى الجودة المطلوب لتاسكات الأسبوع الأول

هذا القسم **إلزامي القراءة قبل تنفيذ أي تاسك في الخطة كاملة**، وليس فقط الأسبوع الأول. الكود أدناه ليس مثالاً توضيحياً عابراً، بل هو **مستوى الجودة المرجعي (Quality Bar)** الذي يجب أن يطابقه كل ملف يكتبه الـ Agent طوال الـ21 يوماً: Typed بالكامل، بدون `any`، بدون منطق داخل الـ Controller مباشرة، Validation منفصل، علاقات Eloquent صريحة، أسماء معبّرة، وتعليقات موجزة عند الضرورة فقط. أي كود يُكتب لاحقاً بجودة أقل من هذا المستوى يُعتبر غير مقبول ويجب إعادة كتابته.

### اليوم 2 — Migration + Model + Factory (features/backend/database-schema)

**Migration** (`database/migrations/2026_07_01_000002_create_products_table.php`):
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('price_cents');
            $table->unsignedInteger('compare_at_price_cents')->nullable();
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->unsignedInteger('low_stock_threshold')->default(5);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```
> ملاحظة إلزامية: الأسعار تُخزَّن دائماً بالـ cents (`unsignedInteger`) وليس كـ `decimal` لتفادي أخطاء التقريب — يُطبَّق هذا النمط في كل الجداول المالية (orders, coupons, gift_cards) بلا استثناء.

**Model** (`app/Models/Product.php`):
```php
<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price_cents', 'compare_at_price_cents',
        'stock_quantity', 'low_stock_threshold', 'status',
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'compare_at_price_cents' => 'integer',
        'stock_quantity' => 'integer',
        'status' => ProductStatus::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            $product->slug ??= Str::slug($product->name);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function getPriceAttribute(): float
    {
        return $this->price_cents / 100;
    }
}
```
> ملاحظة إلزامية: يُستخدم `Enum` (مثل `ProductStatus`) بدل السلاسل النصية الحرة لأي عمود Status في كامل المشروع — لا `'published'` مكتوبة يدوياً في الكود.

**Factory** (`database/factories/ProductFactory.php`):
```php
<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => \Str::slug($name).'-'.fake()->unique()->numberBetween(1, 99999),
            'description' => fake()->paragraph(),
            'price_cents' => fake()->numberBetween(1000, 50000),
            'stock_quantity' => fake()->numberBetween(0, 200),
            'status' => ProductStatus::Published,
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock_quantity' => 0]);
    }
}
```

**اختبار Pest مرافق** (`tests/Feature/ProductTest.php`):
```php
<?php

use App\Models\Product;

it('marks a product as low stock correctly', function () {
    $product = Product::factory()->create([
        'stock_quantity' => 3,
        'low_stock_threshold' => 5,
    ]);

    expect($product->isLowStock())->toBeTrue();
});
```

---

### اليوم 3 — Form Request + Policy + Auth Controller (features/backend/auth)

**Form Request** (`app/Http/Requests/Auth/RegisterRequest.php`):
```php
<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ];
    }
}
```

**Policy** (`app/Policies/ProductPolicy.php`):
```php
<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function update(User $user, Product $product): bool
    {
        return $user->hasRole(['Admin', 'Manager']);
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->hasRole('Admin');
    }
}
```

**Auth Controller** (`app/Http/Controllers/Api/Auth/RegisterController.php`) — يوضّح صيغة الـ JSON الموحّدة الإلزامية لكل الـ API في المشروع:
```php
<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        $user->assignRole('Customer');

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
            'message' => 'تم إنشاء الحساب بنجاح.',
            'errors' => null,
        ], 201);
    }
}
```
> **قاعدة إلزامية لكل استجابات API في المشروع:** بنية ثابتة `{ success, data, message, errors }` — أي Controller يُعيد شكلاً مختلفاً يُعتبر غير مطابق ويجب تصحيحه.

**مكوّن Design System بـ TypeScript** (`components/design-system/WButton.vue`) — المستوى المرجعي لكل مكوّنات الفرونت:
```vue
<script setup lang="ts">
interface Props {
  variant?: 'primary' | 'secondary' | 'ghost' | 'danger'
  size?: 'sm' | 'md' | 'lg'
  loading?: boolean
  disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'primary',
  size: 'md',
  loading: false,
  disabled: false,
})

const emit = defineEmits<{ click: [event: MouseEvent] }>()

const variantClasses: Record<NonNullable<Props['variant']>, string> = {
  primary: 'bg-wisal-aqua text-wisal-ivory hover:bg-wisal-aqua/90',
  secondary: 'bg-wisal-beige text-wisal-charcoal hover:bg-wisal-beige/80',
  ghost: 'bg-transparent text-wisal-charcoal hover:bg-wisal-beige/30',
  danger: 'bg-red-600 text-white hover:bg-red-700',
}

const sizeClasses: Record<NonNullable<Props['size']>, string> = {
  sm: 'px-3 py-1.5 text-sm',
  md: 'px-4 py-2 text-base',
  lg: 'px-6 py-3 text-lg',
}

function handleClick(event: MouseEvent) {
  if (props.disabled || props.loading) return
  emit('click', event)
}
</script>

<template>
  <button
    :disabled="disabled || loading"
    :class="[
      'rounded-lg font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed',
      variantClasses[variant],
      sizeClasses[size],
    ]"
    @click="handleClick"
  >
    <span v-if="loading" class="inline-block animate-spin mr-2">⏳</span>
    <slot />
  </button>
</template>
```
> ملاحظة إلزامية: كل مكوّنات design-system تُبنى بنفس النمط (Props مُعرَّفة بـ `interface`، `withDefaults`، `defineEmits` صريح، خرائط Classes بدل شروط `v-if/else` متكررة).

---

### اليوم 4 — Filament Resource كامل + API Controller (features/admin/categories + features/backend/api-catalog)

**Filament Resource** (`app/Filament/Resources/CategoryResource.php`) — النموذج المرجعي لكل Filament Resources في المشروع:
```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'إدارة الكتالوج';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Str::slug($state))),

            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true),

            Select::make('parent_id')
                ->label('القسم الأب')
                ->relationship('parent', 'name')
                ->searchable()
                ->preload(),

            Textarea::make('description')
                ->maxLength(1000)
                ->columnSpanFull(),

            Toggle::make('is_active')
                ->label('مفعّل')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('parent.name')->label('القسم الأب')->placeholder('—'),
                IconColumn::make('is_active')->boolean()->label('الحالة'),
                TextColumn::make('created_at')->dateTime('d/m/Y')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
```

**API Resource** (`app/Http/Resources/ProductResource.php`):
```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price_cents / 100,
            'compare_at_price' => $this->when(
                $this->compare_at_price_cents,
                fn () => $this->compare_at_price_cents / 100
            ),
            'in_stock' => $this->stock_quantity > 0,
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'images' => $this->getMedia('images')->map(fn ($media) => $media->getUrl()),
        ];
    }
}
```

**Controller مع Cursor Pagination وفلاتر** (`app/Http/Controllers/Api/ProductController.php`):
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request)
    {
        $products = Product::query()
            ->published()
            ->with(['category', 'media'])
            ->when($request->validated('category_id'), fn ($q, $id) => $q->where('category_id', $id))
            ->when($request->validated('search'), fn ($q, $term) => $q->where('name', 'like', "%{$term}%"))
            ->when($request->validated('sort') === 'price_asc', fn ($q) => $q->orderBy('price_cents'))
            ->when($request->validated('sort') === 'price_desc', fn ($q) => $q->orderByDesc('price_cents'))
            ->cursorPaginate(20);

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products),
            'message' => null,
            'errors' => null,
        ]);
    }
}
```
> ملاحظة: `published()` هنا Local Scope مُعرَّف في الموديل (`scopePublished`) — أي فلترة متكررة تُبنى كـ Scope وليست شرطاً مكرراً في كل Controller.

---

**تطبيق نفس المستوى على بقية أيام الأسبوع الأول (5 و6):** كل تاسك في اليوم 5 (orders, coupons, flash-sales, users) واليوم 6 (blog, reports, settings) يجب أن يتبع **نفس البنية بالضبط** الموضحة أعلاه: Migration بأعمدة cents وEnum للحالات، Model بعلاقات صريحة وScopes، Form Request منفصل لكل عملية كتابة، Filament Resource بنفس ترتيب الأقسام (Form → Table → Filters → Actions)، API Resource بصيغة JSON الموحّدة، واختبار Pest واحد على الأقل لكل تاسك. لا يُسمح بأي انحراف عن هذا المستوى تحت أي ظرف.

---

## الأسبوع 2 — واجهة المتجر الكاملة بتقنية Vue.js *(الأيام 8-14)*

**هدف الأسبوع:** بناء كل صفحات التصفح والعرض في Nuxt (الرئيسية، المتجر، الأقسام، المنتج، البحث الذكي، الإهداء، المدونة) بجميع الميزات الإضافية (Dark Mode، Flash Sale، QR Code).

**اليوم 8 — Frontend (Nuxt/Vue)**
- فرع features/frontend/home-page: Header/Footer بهوية وِصال (الألوان الرسمية الأربعة) + Hero Slider + أقسام مميزة + شهادات عملاء
- فرع features/frontend/products-store: Pinia products store مع تخزين مؤقت للنتائج + Skeleton Loaders + Nuxt Image لتحسين الصور
- فرع features/frontend/page-transitions: View Transitions API بين الصفحات + Prefetching عند التحويم

**اليوم 9 — Frontend (Nuxt/Vue)**
- فرع features/frontend/shop-page: صفحة /shop (شبكة منتجات + فلاتر جانبية فورية مرتبطة بالـURL + فرز + Pagination)
- فرع features/frontend/category-pages: صفحات الأقسام الديناميكية (مفكرات، ملصقات، بطاقات، فواصل) بفلاتر خاصة بكل قسم

**اليوم 10 — Backend + Frontend**
- فرع features/backend/search: دمج Laravel Scout + Meilisearch لبحث سريع وذكي ثنائي اللغة
- فرع features/frontend/smart-search: شريط بحث فوري (debounced) مع اقتراحات + صفحة نتائج البحث وبدائل عند عدم وجود نتائج

**اليوم 11 — Frontend (Nuxt/Vue)**
- فرع features/frontend/product-detail: صفحة المنتج (معرض صور بحركة zoom/swipe + تفاصيل + إضافة للسلة + مراجعات + منتجات مشابهة بتحميل كسول)
- فرع features/backend/reviews: API لإضافة وعرض المراجعات والتقييمات

**اليوم 12 — Frontend (Nuxt/Vue)**
- فرع features/frontend/gift-bundle-builder: منشئ باقة الإهداء التفاعلي (Stepper) + فرع features/backend/gift-bundles لحساب السعر الديناميكي
- فرع features/frontend/blog-pages: صفحة المدونة + صفحة المقال + صفحة من نحن

**اليوم 13 — Frontend (Nuxt/Vue)**
- فرع features/frontend/dark-mode: تفعيل الوضع الليلي عبر Tailwind + Pinia preference store (يجب أن يحافظ الوضع الليلي على هوية وِصال — Charcoal #323232 كخلفية أساسية بدل الأسود الخام)
- فرع features/frontend/flash-sale-widget: عرض Flash Sale بمؤقت عد تنازلي حي على الصفحة الرئيسية وصفحات المنتج
- فرع features/backend/qr-codes: توليد QR Code لكل منتج (لعرضه في لوحة الأدمن وطباعته)

**اليوم 14 — الفريق كامل**
- دمج جميع فروع الفرونت في develop → نشر إلى staging + اختبار تصفح كامل على بيانات staging الحقيقية
- فرع features/frontend/performance-pass: تدقيق Virtual Scrolling للقوائم الطويلة وCode Splitting
- مراجعة نهاية الأسبوع الثاني (Sprint Review) + Bug Bash شامل لكل صفحات التصفح والعرض + **تدقيق التزام كل الشاشات بالألوان الرسمية الأربعة**

---

## الأسبوع 3 — السلة، الدفع، الحساب، الإشعارات، والإطلاق *(الأيام 15-21)*

**هدف الأسبوع:** استكمال دورة الشراء الكاملة بجميع بوابات الدفع، لوحة العميل، نظام الولاء والإحالة، الإشعارات الفورية، ثم الاختبار والنشر والإطلاق الرسمي.

**اليوم 15 — Backend + Frontend**
- فرع features/backend/cart: CartService (Session للزوار + Database للمسجلين مع دمج تلقائي عند تسجيل الدخول) + API كامل للسلة
- فرع features/frontend/cart: Pinia cart store بتحديثات فورية (Optimistic UI) + Cart Drawer بحركة انزلاق سلسة + حركة Flying Add-to-Cart

**اليوم 16 — Backend + Frontend**
- فرع features/frontend/checkout: صفحة /cart الكاملة + Checkout متعدد الخطوات (عنوان → شحن → دفع → مراجعة) بمكوّن Stepper
- فرع features/backend/coupons-engine: تطبيق الكوبونات على السلة + التحقق الفوري من الصلاحية والحد الأدنى
- فرع features/backend/create-order: بناء CreateOrderAction كاملة

**اليوم 17 — Backend / API**
- فرع features/backend/payment-stripe: دمج Stripe (Payment Intents + Webhook للتأكيد الفوري)
- فرع features/backend/payment-paypal: دمج PayPal Checkout
- فرع features/backend/payment-cod-giftcard: تفعيل الدفع عند الاستلام + استبدال بطاقة الهدية الرقمية كرصيد
- فرع features/frontend/payment-ui: دمج واجهات الدفع الثلاث داخل الـCheckout دون إعادة تحميل الصفحة + معالجة الأخطاء بأناقة

**اليوم 18 — Backend + Frontend**
- فرع features/backend/customer-api: API endpoints للوحة العميل (orders, order details, wishlist, addresses, profile, reviews)
- فرع features/frontend/customer-dashboard: لوحة العميل كاملة (dashboard, orders مع Timeline تتبع, wishlist بتحديث فوري, addresses, profile, reviews)
- فرع features/frontend/order-success: صفحة تأكيد الطلب + توليد فاتورة PDF (DomPDF/Snappy) من الباك-إند

**اليوم 19 — Backend + Frontend**
- فرع features/backend/notifications: إشعارات Email + Database لكل الأحداث (تأكيد، شحن، تسليم، إلغاء، استرداد) + تذكير السلة المتروكة + كوبون عيد الميلاد + نشرة أسبوعية
- فرع features/backend/realtime: Laravel Echo + Pusher/WebSockets للإشعارات الفورية + فرع features/frontend/notifications-ui لجرس الإشعارات بحركة Toast سلسة
- فرع features/backend/loyalty-referral: نظام نقاط الولاء الكامل + برنامج الإحالة (Referral) + عرضهما في لوحة العميل

**اليوم 20 — DevOps/QA**
- فرع features/backend/performance: Redis Caching شامل (Cache Tags) + Horizon لمراقبة الطوابير + php artisan optimize ضمن CI/CD
- فرع features/backend/security: مراجعة CSRF/XSS/SQL Injection + Rate Limiting + Security Headers + تفعيل 2FA للأدمن
- فرع features/frontend/seo-ssr: ضبط Nuxt SSR للصفحات العامة + Meta Tags وJSON-LD ديناميكية + قياس Core Web Vitals

**اليوم 21 — الفريق كامل**
- دمج جميع الفروع المتبقية في develop → staging → اختبار شامل لدورة الشراء الكاملة (تصفح → سلة → دفع بكل الطرق → تأكيد → لوحة العميل → إشعارات)
- فتح Pull Request من staging إلى main (production) بعد موافقة الفريق واجتياز جميع الاختبارات (Pest + Vitest + E2E)
- تجهيز VPS (Nginx + SSL + Supervisor + PM2 لتشغيل Nuxt SSR) + دمج staging → main + وسم الإصدار v1.0.0 + الإطلاق الرسمي لمتجر وِصال 🎉

---

## ✦ وِصال ✦
### *"بين كل هدية وذكرى — وِصال"*

هذه خطة سباق مكثف (Sprint) لإطلاق النسخة الكاملة من متجر وِصال بكل ميزاتها خلال 3 أسابيع، بواجهة Vue.js (Nuxt 3) وباك-إند Laravel API، معتمدة على استراتيجية Git/GitHub منظمة (main / staging / develop + فروع Features)، وملتزمة حرفياً بالهوية البصرية الرسمية:

- Beige Heritage `#D1CBB4`
- Aqua Blue `#467389`
- Ivory `#FFFBF5`
- Charcoal `#323232`
