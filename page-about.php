<?php
/*
 * Template Name: عن مخيّم
 * Description: صفحة التعريف بالمنصة
 */
get_header();
?>

<!-- ============================================================
     ABOUT HERO
============================================================ -->
<div class="about-hero">
    <span class="badge">تعرّف علينا</span>
    <h1>نرى ما لا تراه الكاميرات</h1>
    <p>مخيّم منصة صحفية عربية مستقلة تُعنى بالقصة الإنسانية خلف الحرب والنزوح</p>
</div>


<!-- ============================================================
     ABOUT BODY — المحتوى من WP Editor
============================================================ -->
<?php while ( have_posts() ) : the_post(); ?>

<div class="about-body">

    <!-- المحتوى المكتوب في لوحة WordPress مباشرةً -->
    <?php if ( get_the_content() ) : ?>
        <div class="about-wp-content">
            <?php the_content(); ?>
        </div>
    <?php else : ?>

    <!-- المحتوى الافتراضي لو الصفحة فارغة -->
    <h2>من نحن</h2>
    <p>مخيّم منصة محتوى مستقلة، وُلدت من رحم الحاجة إلى صوت يروي ما تصمت عنه نشرات الأخبار. لسنا هنا لنقرأ البيانات، بل لنجلس مع الإنسان ونسمعه.</p>
    <p>نؤمن بأن الكتابة شكل من أشكال المقاومة، وأن توثيق الحياة اليومية تحت الحرب هو رسالة يجب أن تُؤدَّى بأمانة وشجاعة وعمق.</p>

    <div class="about-divider"><span>✦</span></div>

    <h2>قيمنا</h2>
    <div class="values-grid">
        <div class="value-card">
            <h3>الأمانة أولاً</h3>
            <p>لا نُجمّل ولا نُهوّل. نروي ما حدث كما حدث، بكل تعقيداته وتناقضاته.</p>
        </div>
        <div class="value-card">
            <h3>الإنسان في المركز</h3>
            <p>القصة ليست الحدث، بل الإنسان الذي عاشه. هو بطلنا الدائم.</p>
        </div>
        <div class="value-card">
            <h3>العمق على الآنية</h3>
            <p>نفضّل مقالاً واحداً مدروساً على عشرة مقالات سريعة.</p>
        </div>
        <div class="value-card">
            <h3>الاستقلالية</h3>
            <p>لا أجندات سياسية ولا ممولون يُملون التوجه. صوتنا للإنسان وحده.</p>
        </div>
    </div>

    <div class="about-divider"><span>✦</span></div>

    <h2>فريق التحرير</h2>
    <div class="team-grid">
        <?php
        // جلب المستخدمين من WP (الكتّاب والمحررون)
        $authors = get_users([
            'role__in' => ['editor', 'author', 'administrator'],
            'orderby'  => 'registered',
            'order'    => 'ASC',
            'number'   => 6,
        ]);

        if ($authors) :
            foreach ($authors as $author) :
        ?>
        <div class="team-card">
            <div class="team-avatar">
                <?= get_avatar($author->user_email, 90, '', '', ['class' => 'author-img']) ?>
            </div>
            <h4><?= esc_html($author->display_name) ?></h4>
            <span><?= esc_html(get_user_meta($author->ID, 'job_title', true) ?: 'كاتب') ?></span>
        </div>
        <?php
            endforeach;
        else :
            // Placeholders لو ما في مستخدمين
            $team = [
                ['سهير النجار', 'رئيسة التحرير'],
                ['خالد الرفاعي', 'محرر التحقيقات'],
                ['رنا أبو عمر', 'محررة اجتماعية'],
            ];
            foreach ($team as [$name, $role]) :
        ?>
        <div class="team-card">
            <div class="team-avatar">
                <div class="img-placeholder g2"></div>
            </div>
            <h4><?= $name ?></h4>
            <span><?= $role ?></span>
        </div>
        <?php
            endforeach;
        endif;
        ?>
    </div>

    <div class="about-divider"><span>✦</span></div>

    <!-- CTA -->
    <div class="submit-cta">
        <h2>أرسل قصتك</h2>
        <p>هل لديك قصة تستحق أن تُروى؟ باب مخيّم مفتوح لكل كاتب يؤمن بالإنسان.</p>
        <a href="mailto:editor@mukhayyam.ps" class="btn-ink">أرسل مقالك الآن ←</a>
    </div>

    <?php endif; ?>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
