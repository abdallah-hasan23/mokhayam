<?php get_header(); ?>

<div class="wrap">

    <!-- ============================================================
         HERO MOSAIC — أحدث ٥ مقالات
    ============================================================ -->
    <?php
    $hero_query = new WP_Query([
        'posts_per_page' => 5,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
    ?>

    <?php if ( $hero_query->have_posts() ) : ?>
    <section class="hero">
        <div class="hero-mosaic">

            <?php
            $hero_posts = [];
            while ( $hero_query->have_posts() ) {
                $hero_query->the_post();
                $hero_posts[] = get_post();
            }
            wp_reset_postdata();
            ?>

            <!-- MAIN FEATURED -->
            <?php if ( isset($hero_posts[0]) ) : setup_postdata($hero_posts[0]); ?>
            <div class="hm-main" onclick="location.href='<?= get_permalink($hero_posts[0]) ?>'">
                <div class="thumb-full">
                    <?php mukhayyam_post_thumbnail('mukhayyam-hero', 'img-placeholder', $hero_posts[0]->ID); ?>
                </div>
                <div class="hm-main-info">
                    <?php mukhayyam_category_badge($hero_posts[0]->ID); ?>
                    <h1><?= get_the_title($hero_posts[0]) ?></h1>
                    <p><?= get_the_excerpt($hero_posts[0]) ?></p>
                    <a href="<?= get_permalink($hero_posts[0]) ?>" class="btn-read">اقرأ المقال كاملاً</a>
                </div>
            </div>
            <?php endif; ?>

            <!-- SIDE CARDS (مقالات 2-4) -->
            <div class="hm-side">
                <?php for ($i = 1; $i <= 3; $i++) :
                    if ( ! isset($hero_posts[$i]) ) continue;
                    setup_postdata($hero_posts[$i]);
                ?>
                <div class="hm-side-card" onclick="location.href='<?= get_permalink($hero_posts[$i]) ?>'">
                    <div class="thumb-full">
                        <?php mukhayyam_post_thumbnail('mukhayyam-side', 'img-placeholder', $hero_posts[$i]->ID); ?>
                    </div>
                    <div class="hm-side-info">
                        <?php mukhayyam_category_badge($hero_posts[$i]->ID); ?>
                        <h3><?= get_the_title($hero_posts[$i]) ?></h3>
                    </div>
                </div>
                <?php endfor; ?>
            </div>

            <!-- BOTTOM STRIP (مقال 5 + آخر مقالتين من أقسام مختلفة) -->
            <div class="hm-strip">
                <?php
                // المقال الخامس
                if ( isset($hero_posts[4]) ) :
                    setup_postdata($hero_posts[4]);
                ?>
                <div class="hm-strip-card" onclick="location.href='<?= get_permalink($hero_posts[4]) ?>'">
                    <?php mukhayyam_category_badge($hero_posts[4]->ID, 'dark'); ?>
                    <h4><?= get_the_title($hero_posts[4]) ?></h4>
                    <div class="meta-sm">
                        <?= get_the_author_meta('display_name', $hero_posts[4]->post_author) ?>
                        · <?= mukhayyam_arabic_date('j M', $hero_posts[4]->ID) ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php
                // مقالتان إضافيتان
                $strip_query = new WP_Query([
                    'posts_per_page' => 2,
                    'post_status'    => 'publish',
                    'offset'         => 5,
                ]);
                while ($strip_query->have_posts()) : $strip_query->the_post();
                ?>
                <div class="hm-strip-card" onclick="location.href='<?= get_permalink() ?>'">
                    <?php mukhayyam_category_badge(null, 'dark'); ?>
                    <h4><?= get_the_title() ?></h4>
                    <div class="meta-sm">
                        <?= get_the_author() ?> · <?= get_the_date('j M') ?>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>

        </div>
    </section>
    <?php endif; ?>


    <!-- ============================================================
         LATEST ARTICLES GRID — أحدث ٦ مقالات
    ============================================================ -->
    <div class="sec-head">
        <h2>آخر المقالات</h2>
        <div class="line"></div>
        <a href="<?= get_post_type_archive_link('post') ?>">عرض الكل →</a>
    </div>

    <?php
    $latest_query = new WP_Query([
        'posts_per_page' => 6,
        'post_status'    => 'publish',
        'offset'         => 5, // بعد الـ Hero
    ]);
    ?>

    <?php if ( $latest_query->have_posts() ) : ?>
    <div class="cards-grid">
        <?php while ( $latest_query->have_posts() ) : $latest_query->the_post(); ?>
        <article class="art-card">
            <a href="<?= get_permalink() ?>" class="thumb">
                <?php mukhayyam_post_thumbnail('mukhayyam-card'); ?>
            </a>
            <div class="card-body">
                <?php mukhayyam_category_badge(null, 'dark'); ?>
                <h3><a href="<?= get_permalink() ?>"><?= get_the_title() ?></a></h3>
                <p><?= get_the_excerpt() ?></p>
                <div class="card-foot">
                    <span><?= get_the_author() ?> · <?= get_the_date('j F') ?></span>
                    <?php mukhayyam_share_buttons(); ?>
                </div>
            </div>
        </article>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <?php endif; ?>


    <!-- ============================================================
         LONG READ BANNER — مقال مميز (يدوي أو أكثر قراءة)
    ============================================================ -->
    <?php
    // جلب مقال من تصنيف "قراءة معمّقة" لو موجود، وإلا أقدم مقال
    $longread_args = [
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'category_name'  => 'تحقيقات', // غيّر لاسم التصنيف اللي تريده
    ];
    // fallback لو التصنيف ما موجود
    $longread_query = new WP_Query($longread_args);
    if ( ! $longread_query->have_posts() ) {
        $longread_query = new WP_Query(['posts_per_page'=>1,'offset'=>11]);
    }
    if ( $longread_query->have_posts() ) : $longread_query->the_post();
    ?>
    <div class="longread mt-52">
        <div class="lr-label">قراءة معمّقة</div>
        <h2><?= get_the_title() ?></h2>
        <p><?= get_the_excerpt() ?></p>
        <a href="<?= get_permalink() ?>" class="btn-gold">اقرأ التحقيق الكامل ←</a>
    </div>
    <?php endif; wp_reset_postdata(); ?>


    <!-- ============================================================
         TWO COLUMN: قسم مميز + سايدبار
    ============================================================ -->
    <?php
    // جلب قسم الشباب (أو أي قسم آخر) للعمود الأيمن
    $featured_cat = get_category_by_slug('الشباب');
    $section_name = $featured_cat ? $featured_cat->name : 'آخر المقالات';
    $section_link = $featured_cat ? get_category_link($featured_cat->term_id) : '#';

    $section_query = new WP_Query([
        'posts_per_page' => 3,
        'post_status'    => 'publish',
        'category_name'  => 'الشباب',
    ]);
    // fallback
    if ( ! $section_query->have_posts() ) {
        $section_query = new WP_Query(['posts_per_page'=>3,'offset'=>12]);
    }
    ?>

    <div class="two-col mt-52">
        <div>
            <div class="sec-head" style="margin-top:0">
                <h2><?= esc_html($section_name) ?></h2>
                <div class="line"></div>
                <a href="<?= esc_url($section_link) ?>">كل المقالات →</a>
            </div>

            <div class="list-feed">
                <?php while ( $section_query->have_posts() ) : $section_query->the_post(); ?>
                <div class="list-item-row">
                    <div class="lbody">
                        <?php mukhayyam_category_badge(null, 'dark'); ?>
                        <h3><a href="<?= get_permalink() ?>"><?= get_the_title() ?></a></h3>
                        <p><?= get_the_excerpt() ?></p>
                        <div class="lmeta"><?= get_the_author() ?> · <?= get_the_date('j F Y') ?></div>
                    </div>
                    <div class="list-thumb-sm">
                        <a href="<?= get_permalink() ?>">
                            <?php mukhayyam_post_thumbnail('mukhayyam-thumb'); ?>
                        </a>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>

        <!-- SIDEBAR -->
        <?php get_template_part('template-parts/sidebar'); ?>
    </div>

</div><!-- /wrap -->

<?php get_footer(); ?>
