<?php get_header(); ?>

<?php
$current_cat  = get_queried_object();
$cat_name     = $current_cat->name ?? 'المقالات';
$cat_desc     = $current_cat->description ?? '';
$total_posts  = $current_cat->count ?? 0;
?>

<!-- ============================================================
     CATEGORY HERO
============================================================ -->
<div class="cat-hero">
    <div class="cat-hero-label">القسم</div>
    <h1><?= esc_html($cat_name) ?></h1>
    <?php if ($cat_desc) : ?>
        <p><?= esc_html($cat_desc) ?></p>
    <?php endif; ?>
</div>


<div class="wrap">

    <!-- ============================================================
         FEATURED — أول ٤ مقالات في هذا التصنيف
    ============================================================ -->
    <?php
    $featured_query = new WP_Query([
        'posts_per_page' => 4,
        'post_status'    => 'publish',
        'cat'            => $current_cat->term_id ?? 0,
    ]);
    $featured_posts = [];
    if ($featured_query->have_posts()) {
        while ($featured_query->have_posts()) {
            $featured_query->the_post();
            $featured_posts[] = get_post();
        }
        wp_reset_postdata();
    }
    ?>

    <?php if (count($featured_posts) >= 1) : ?>
    <div class="cat-featured mt-40">

        <!-- Main featured -->
        <?php setup_postdata($featured_posts[0]); ?>
        <div class="cat-feat-main" onclick="location.href='<?= get_permalink($featured_posts[0]) ?>'">
            <div class="thumb-full">
                <?php mukhayyam_post_thumbnail('mukhayyam-hero', 'img-placeholder', $featured_posts[0]->ID); ?>
            </div>
            <div class="cat-feat-info">
                <?php mukhayyam_category_badge($featured_posts[0]->ID); ?>
                <h2><?= get_the_title($featured_posts[0]) ?></h2>
                <p><?= get_the_excerpt($featured_posts[0]) ?></p>
            </div>
        </div>

        <!-- Side featured (2-4) -->
        <div class="cat-feat-side">
            <?php for ($i = 1; $i <= 3; $i++) :
                if (!isset($featured_posts[$i])) continue;
            ?>
            <div class="cat-feat-side-card" onclick="location.href='<?= get_permalink($featured_posts[$i]) ?>'">
                <div class="thumb-full">
                    <?php mukhayyam_post_thumbnail('mukhayyam-side', 'img-placeholder', $featured_posts[$i]->ID); ?>
                </div>
                <div class="cat-feat-side-info">
                    <?php mukhayyam_category_badge($featured_posts[$i]->ID); ?>
                    <h3><?= get_the_title($featured_posts[$i]) ?></h3>
                </div>
            </div>
            <?php endfor; ?>
        </div>

    </div>
    <?php endif; ?>


    <!-- ============================================================
         ARTICLES LIST + SIDEBAR
    ============================================================ -->
    <div class="cat-two-col mt-52">

        <!-- ARTICLES LIST -->
        <div>
            <div class="sec-head" style="margin-top:0">
                <h2>جميع المقالات</h2>
                <div class="line"></div>
                <span style="font-family:'Tajawal',sans-serif;font-size:12px;color:var(--muted)">
                    <?= $total_posts ?> مقال
                </span>
            </div>

            <div class="cat-list">
                <?php
                // نتخطى الـ ٤ المعروضة في الـ featured
                $paged = get_query_var('paged') ? get_query_var('paged') : 1;
                $list_offset = ($paged === 1) ? 4 : 0;

                $list_query = new WP_Query([
                    'posts_per_page' => 8,
                    'post_status'    => 'publish',
                    'cat'            => $current_cat->term_id ?? 0,
                    'paged'          => $paged,
                    'offset'         => ($paged === 1) ? 4 : (($paged - 1) * 8 + 4),
                ]);
                ?>

                <?php if ($list_query->have_posts()) : ?>
                    <?php while ($list_query->have_posts()) : $list_query->the_post(); ?>
                    <div class="cat-list-item">
                        <div class="clbody">
                            <?php mukhayyam_category_badge(null, 'dark'); ?>
                            <h3><a href="<?= get_permalink() ?>"><?= get_the_title() ?></a></h3>
                            <p><?= get_the_excerpt() ?></p>
                            <div class="clmeta">
                                <span><?= get_the_author() ?> · <?= get_the_date('j F Y') ?></span>
                                <?php mukhayyam_share_buttons(); ?>
                            </div>
                        </div>
                        <div class="cat-thumb">
                            <a href="<?= get_permalink() ?>">
                                <?php mukhayyam_post_thumbnail('mukhayyam-thumb'); ?>
                            </a>
                        </div>
                    </div>
                    <?php endwhile; ?>

                    <!-- PAGINATION -->
                    <div class="pagination">
                        <?php
                        $big = 999999999;
                        echo paginate_links([
                            'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                            'format'    => '?paged=%#%',
                            'current'   => max(1, $paged),
                            'total'     => $list_query->max_num_pages,
                            'type'      => 'list',
                            'prev_text' => '→ السابق',
                            'next_text' => 'التالي ←',
                        ]);
                        ?>
                    </div>

                <?php else : ?>
                    <p style="font-family:'Tajawal',sans-serif;color:var(--muted);padding:40px 0">
                        لا توجد مقالات في هذا القسم بعد.
                    </p>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>

        <!-- SIDEBAR -->
        <?php get_template_part('template-parts/sidebar'); ?>

    </div>

</div><!-- /wrap -->

<?php get_footer(); ?>
