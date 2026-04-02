<?php
namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Subscriber;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ────────────────────────────────────────────
        $admin = User::create([
            'name'      => 'سهير النجار',
            'email'     => 'admin@mukhayyam.ps',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'job_title' => 'رئيسة التحرير',
            'bio'       => 'صحفية فلسطينية متخصصة في الشأن الاجتماعي.',
            'is_active' => true,
        ]);

        $editor = User::create([
            'name'      => 'خالد الرفاعي',
            'email'     => 'khalid@mukhayyam.ps',
            'password'  => Hash::make('password'),
            'role'      => 'editor',
            'job_title' => 'محرر التحقيقات',
            'is_active' => true,
        ]);

        $writers = [
            ['name'=>'رنا أبو عمر',  'email'=>'rana@mukhayyam.ps',   'job_title'=>'محررة اجتماعية'],
            ['name'=>'تمارا عيسى',   'email'=>'tamara@mukhayyam.ps', 'job_title'=>'محررة شؤون الأطفال'],
            ['name'=>'عمر القاسمي',  'email'=>'omar@mukhayyam.ps',   'job_title'=>'محرر شؤون الشباب'],
            ['name'=>'منى حداد',     'email'=>'mona@mukhayyam.ps',   'job_title'=>'محررة إلى حواء'],
        ];
        $writerModels = [];
        foreach ($writers as $w) {
            $writerModels[] = User::create(array_merge($w, [
                'password'  => Hash::make('password'),
                'role'      => 'writer',
                'is_active' => true,
            ]));
        }
        $allWriters = array_merge([$admin, $editor], $writerModels);

        // ── Categories ───────────────────────────────────────
        $cats = [
            ['name'=>'الأسرة',           'slug'=>'al-usra',     'color'=>'#b8902a', 'order'=>1, 'description'=>'قصص وتحليلات عن الأسرة الفلسطينية في مواجهة الحرب'],
            ['name'=>'الشباب',           'slug'=>'al-shabab',   'color'=>'#1e7e4a', 'order'=>2, 'description'=>'الضياع وفقدان الفرص وحلم الهجرة'],
            ['name'=>'إلى حواء',         'slug'=>'ila-hawaa',   'color'=>'#1a5fa8', 'order'=>3, 'description'=>'أعباء المرأة في الحرب والنزوح'],
            ['name'=>'الأطفال',          'slug'=>'al-atfal',    'color'=>'#c4621a', 'order'=>4, 'description'=>'التعليم والطفولة والعامل النفسي'],
            ['name'=>'المجتمع',          'slug'=>'al-mujtama',  'color'=>'#6b3a8a', 'order'=>5, 'description'=>'التكافل والنزعة القبلية والروابط الاجتماعية'],
            ['name'=>'قصص من الواقع',    'slug'=>'qisas',       'color'=>'#8b3a1a', 'order'=>6, 'description'=>'توثيق حي لقصص إنسانية حقيقية'],
        ];
        $catModels = [];
        foreach ($cats as $c) {
            $catModels[] = Category::create($c);
        }

        // ── Tags ─────────────────────────────────────────────
        $tagNames = ['النزوح','الحرب','الأسرة','المرأة','الشباب','التعليم','الهجرة','المخيم','المجتمع','الأطفال','التكافل','الهوية'];
        $tagModels = [];
        foreach ($tagNames as $t) {
            $tagModels[] = Tag::create(['name'=>$t,'slug'=>Str::slug($t)]);
        }

        // ── Articles ─────────────────────────────────────────
        $articles = [
            [
                'title'     => 'حين تفقد الأسرة سقفها.. كيف يُعاد بناء المعنى تحت خيمة واحدة؟',
                'excerpt'   => 'ثلاثة أجيال تحت سقف واحد وخيمة تسع عشرة روحاً. قصة عن التكدّس والحب والكرامة.',
                'content'   => '<p>في مخيم المواصي، جنوب قطاع غزة، تعيش أسرة النجار في خيمة مساحتها لا تتجاوز أربعة وعشرين متراً مربعاً. تسعة عشر شخصاً من ثلاثة أجيال.</p><h2>تغيّر الأدوار داخل الأسرة</h2><p>حين تنهار المؤسسات ويُرفع عن الأسرة كل غطاء، تُعاد رسم الأدوار من الصفر. الأب الذي كان يعمل بنّاءً يجد نفسه عاجزاً عن توفير وجبة.</p><blockquote>«الحرب غيّرت كل شيء إلا حب أمي» — آية، ١٧ عاماً</blockquote><p>الأبناء الأكبر باتوا يتقاسمون مسؤولية البيت مع الأبوين. لكن هذا التحول لا يسير دائماً بسلاسة.</p><h2>الخصوصية ترف</h2><p>الخصوصية ترف — هذه جملة يقولها كثيرون في المخيمات بنبرة ساخرة، لكنها تحمل ألماً عميقاً.</p>',
                'category'  => 0,
                'writer'    => 0,
                'status'    => 'published',
                'views'     => 8420,
                'tags'      => [0,1,2],
            ],
            [
                'title'     => 'المرأة تحمل الأسرة حين تتساقط الأعمدة',
                'excerpt'   => 'في غياب الرجل أو عجزه، تتحول المرأة إلى العمود الأوحد للبيت. رحلة في صمت المرأة الفلسطينية.',
                'content'   => '<p>لم تكن فاطمة تتوقع يوماً أن تصبح هي من يقرر كل شيء. منذ اللحظة التي فقدت فيها زوجها، تحولت الأدوار بشكل كامل.</p><h2>الأعباء المضاعفة</h2><p>المرأة في المخيم تحمل ما لا يُقال: العناية بالأطفال، وإدارة الموارد الشحيحة، والحفاظ على الروح المعنوية.</p><p>تقول رنا، ٣٤ عاماً: "أنا لا أنام. أفكر في الغد باستمرار."</p>',
                'category'  => 2,
                'writer'    => 5,
                'status'    => 'published',
                'views'     => 6110,
                'tags'      => [3,1,0],
            ],
            [
                'title'     => 'انعدام الخصوصية والصحة.. ما تصمت عنه المرأة في المخيم',
                'excerpt'   => 'أعباء مضاعفة تحملها المرأة في الحرب والنزوح، كثير منها لا يُقال ولا يُكتب.',
                'content'   => '<p>هناك أشياء لا تُقال في المخيم. ليس لأنها غير موجودة، بل لأن الظروف لا تسمح بالكلام.</p><p>الصحة الإنجابية، الخصوصية الجسدية، الاحتياجات اليومية — كلها تصبح تحديات ضخمة حين تكونين في خيمة مع عشرين شخصاً.</p>',
                'category'  => 2,
                'writer'    => 5,
                'status'    => 'published',
                'views'     => 4200,
                'tags'      => [3,0],
            ],
            [
                'title'     => 'حلم الهجرة بلا وعي.. أين يذهب من يهرب؟',
                'excerpt'   => 'الهجرة ليست دائماً نجاةً. أحياناً تكون وهماً آخر يُضاف إلى قائمة الأوهام.',
                'content'   => '<p>يجلس أحمد، ٢٢ عاماً، أمام شاشة هاتفه يبحث عن طرق للهجرة. لا يعرف إلى أين. فقط يريد أن يذهب.</p><h2>الهروب من الفراغ</h2><p>كثير من شباب غزة لا يهاجرون نحو شيء، بل يهربون من شيء. من الدمار، من اليأس، من صوت القصف.</p>',
                'category'  => 1,
                'writer'    => 4,
                'status'    => 'published',
                'views'     => 3120,
                'tags'      => [4,6,0],
            ],
            [
                'title'     => 'فقدان الفرص.. حين يتحول الحلم إلى عبء',
                'excerpt'   => 'شباب يملكون طموحات لكن لا يملكون مستقبلاً مرئياً.',
                'content'   => '<p>كان يريد أن يصبح مهندساً. الآن يعمل في بيع الخضروات لإعالة أسرته.</p><p>قصة محمد ليست استثناءً، بل هي القاعدة في غزة اليوم.</p>',
                'category'  => 1,
                'writer'    => 4,
                'status'    => 'review',
                'views'     => 0,
                'tags'      => [4,5],
            ],
            [
                'title'     => 'انقطاع التعليم.. جيل يكبر بلا حرف',
                'excerpt'   => 'حين تُغلق المدارس ويتحول الطفل إلى عامل، نخسر أكثر من سنة دراسية.',
                'content'   => '<p>في عام واحد، انقطع ما يزيد على ٦٠٠ ألف طفل عن التعليم في غزة. ليس لأنهم لا يريدون التعلم، بل لأن المدارس لم تعد موجودة.</p><h2>التعليم البديل</h2><p>في بعض المخيمات، يجتمع الأطفال تحت ظل الخيام ليتعلموا. معلم واحد، لا ألواح، لا كتب.</p>',
                'category'  => 3,
                'writer'    => 3,
                'status'    => 'published',
                'views'     => 3600,
                'tags'      => [9,5,0],
            ],
            [
                'title'     => 'النزعة القبلية في زمن الحرب.. حين يُقتل الجار بيد الجار',
                'excerpt'   => 'عندما تحتدم الأزمات وتنهار المؤسسات، تعود العصبيات القديمة لتملأ الفراغ.',
                'content'   => '<p>في مخيمات النزوح، حيث تتكدس العائلات وتشحّ الموارد، بدأت تظهر نزاعات لم تكن موجودة من قبل.</p><h2>جذور المشكلة</h2><p>النزعة القبلية لم تختفِ أبداً، لكنها كانت كامنة تحت طبقة من المؤسسات والقانون. حين انهارت هذه المؤسسات، خرجت إلى السطح.</p>',
                'category'  => 4,
                'writer'    => 1,
                'status'    => 'published',
                'views'     => 4880,
                'tags'      => [8,1,0],
            ],
            [
                'title'     => 'الزواج المبكر في ظل الحرب.. هروب أم مصير؟',
                'excerpt'   => 'ترتفع نسب الزواج المبكر في أوقات الحروب لأسباب تتشابك بين الاقتصادي والاجتماعي.',
                'content'   => '<p>تزوجت سلمى وهي في الخامسة عشرة من عمرها. لم تكن تريد ذلك، لكن أهلها كانوا يخشون عليها في ظل الأوضاع الأمنية المتدهورة.</p><p>قصة سلمى تتكرر كثيراً في مخيمات النزوح.</p>',
                'category'  => 0,
                'writer'    => 5,
                'status'    => 'published',
                'views'     => 2800,
                'tags'      => [2,3,0],
            ],
            [
                'title'     => 'التكافل الغائب.. لماذا تفتّت الروابط في زمن الحرب؟',
                'excerpt'   => 'الحرب تمحو بعض الروابط، لكنها تُنبت أخرى في أماكن غير متوقعة.',
                'content'   => '<p>كان التكافل الاجتماعي ركيزة أساسية في المجتمع الفلسطيني. لكن الحرب الطويلة بدأت تنخر في هذه الركيزة.</p><p>شهادات من داخل المخيمات تروي كيف تحولت العلاقات الاجتماعية تحت وطأة الضغط المستمر.</p>',
                'category'  => 4,
                'writer'    => 2,
                'status'    => 'published',
                'views'     => 2200,
                'tags'      => [8,10,0],
            ],
            [
                'title'     => 'العامل النفسي للطفل في زمن الحرب',
                'excerpt'   => 'جروح لا تُرى بالعين، لكنها تترك أثرها لسنوات بعد انتهاء الصراع.',
                'content'   => '<p>يرسم عمر، ٨ سنوات، صوراً لا يفهمها الكبار. طائرات ودماء وبيوت محترقة. هذا ما يراه في أحلامه كل ليلة.</p><p>اضطراب ما بعد الصدمة ليس حكراً على الكبار. الأطفال يعانون منه بطريقة مختلفة، وأحياناً أشد.</p>',
                'category'  => 3,
                'writer'    => 3,
                'status'    => 'draft',
                'views'     => 0,
                'tags'      => [9,1],
            ],
            [
                'title'     => '«كنت أريد أن أكون معلمة».. رسائل بنات لم يصلن',
                'excerpt'   => 'قصص حقيقية لفتيات كان لديهن أحلام، فسرقتها الحرب.',
                'content'   => '<p>جمعنا رسائل من عشر فتيات يعشن في مخيمات النزوح. طلبنا منهن أن يكتبن لأنفسهن في المستقبل.</p><p>ما وصلنا كان أعمق مما توقعنا.</p>',
                'category'  => 5,
                'writer'    => 2,
                'status'    => 'published',
                'views'     => 5100,
                'tags'      => [3,4,5,0],
            ],
        ];

        foreach ($articles as $a) {
            $article = Article::create([
                'title'          => $a['title'],
                'slug'           => Str::slug($a['title']),
                'excerpt'        => $a['excerpt'],
                'content'        => $a['content'],
                'user_id'        => $allWriters[$a['writer']]->id,
                'category_id'    => $catModels[$a['category']]->id,
                'status'         => $a['status'],
                'views'          => $a['views'],
                'published_at'   => $a['status'] === 'published' ? now()->subDays(rand(1,30)) : null,
                'meta_title'     => $a['title'],
                'meta_description' => $a['excerpt'],
            ]);
            if (!empty($a['tags'])) {
                $article->tags()->attach(
                    collect($a['tags'])->map(fn($i) => $tagModels[$i]->id)->toArray()
                );
            }
        }

        // ── Comments ─────────────────────────────────────────
        $firstArticle = Article::first();
        $cmtData = [
            ['author_name'=>'أحمد محمود',    'author_email'=>'ahmad@example.com',  'body'=>'مقال رائع ومؤثر جداً، يعكس الواقع الذي نعيشه بكل أمانة.', 'status'=>'pending'],
            ['author_name'=>'سلمى أبو شرار', 'author_email'=>'salma@example.com',  'body'=>'المرأة الفلسطينية تحمل أكثر مما يتصور أحد، وهذا المقال أنصفها.', 'status'=>'approved'],
            ['author_name'=>'محمد العمري',   'author_email'=>'mohamad@example.com','body'=>'موضوع حساس وضروري. أتمنى مزيداً من التعمق.', 'status'=>'pending'],
            ['author_name'=>'لمى خليل',      'author_email'=>'lama@example.com',   'body'=>'شكراً على هذا التوثيق القيّم.', 'status'=>'approved'],
            ['author_name'=>'يوسف حمدان',    'author_email'=>'yousef@example.com', 'body'=>'أتمنى أن يقرأ هذا المقال كل مسؤول.', 'status'=>'pending'],
        ];
        foreach ($cmtData as $c) {
            Comment::create(array_merge($c, ['article_id' => $firstArticle->id]));
        }

        // ── Subscribers ───────────────────────────────────────
        $emails = [
            'reader1@gmail.com','reader2@hotmail.com','subscriber@yahoo.com',
            'fan@outlook.com','user5@gmail.com','palestine@proton.me',
        ];
        foreach ($emails as $i => $email) {
            Subscriber::create([
                'email'    => $email,
                'source'   => ['website','telegram','twitter','website'][$i % 4],
                'is_active'=> true,
            ]);
        }
    }
}
