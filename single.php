<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<!-- ============================================================
     ARTICLE HERO IMAGE
============================================================ -->
<div class="article-hero">
    <?php if ( has_post_thumbnail() ) : ?>
        <?php the_post_thumbnail('mukhayyam-hero'); ?>
    <?php else : ?>
        <?php
        $gradients = ['g1','g2','g3','g4','g5'];
        $g = $gradients[ get_the_ID() % 5 ];
        ?>
        <div class="img-placeholder <?= $g ?>"></div>
    <?php endif; ?>
    <div class="article-hero-overlay"></div>
</div>


<!-- ============================================================
     ARTICLE CONTENT
============================================================ -->
<div class="article-wrap">

    <!-- META TOP -->
    <div class="article-meta-top">
        <!-- Avatar المؤلف -->
        <div class="art-author-avatar">
            <?php echo get_avatar( get_the_author_meta('email'), 44, '', '', ['class' => 'author-img'] ); ?>
        </div>

        <div class="art-author-info">
            <div class="name">
                <a href="<?= get_author_posts_url(get_the_author_meta('ID')) ?>">
                    <?= get_the_author() ?>
                </a>
            </div>
            <div class="date">
                <?= get_the_date('j F Y') ?> · <?= mukhayyam_reading_time() ?>
            </div>
        </div>

        <!-- Badge التصنيف -->
        <?php
        $cats = get_the_category();
        if ($cats) :
        ?>
        <a href="<?= get_category_link($cats[0]->term_id) ?>" class="badge">
            <?= esc_html($cats[0]->name) ?>
        </a>
        <?php endif; ?>

        <!-- مشاركة -->
        <?php mukhayyam_share_buttons(); ?>
    </div>


    <!-- ARTICLE TITLE -->
    <h1 class="article-title"><?= get_the_title() ?></h1>

    <!-- DECK / EXCERPT -->
    <?php if ( has_excerpt() ) : ?>
    <p class="article-deck"><?= get_the_excerpt() ?></p>
    <?php endif; ?>


    <!-- ARTICLE BODY -->
    <div class="article-body">
        <?php the_content(); ?>
    </div>


    <!-- TAGS -->
    <?php
    $tags = get_the_tags();
    if ($tags) :
    ?>
    <div class="article-tags">
        <span>الوسوم:</span>
        <?php foreach ($tags as $tag) : ?>
            <a href="<?= get_tag_link($tag->term_id) ?>" class="tag-pill">
                <?= esc_html($tag->name) ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>


    <!-- AUTHOR BIO BOX -->
    <?php
    $author_bio = get_the_author_meta('description');
    if ($author_bio) :
    ?>
    <div class="author-box">
        <div class="author-box-avatar">
            <?= get_avatar(get_the_author_meta('email'), 70, '', '', ['class' => 'author-img']) ?>
        </div>
        <div class="author-box-info">
            <h4><?= get_the_author() ?></h4>
            <p><?= esc_html($author_bio) ?></p>
        </div>
    </div>
    <?php endif; ?>

</div><!-- /article-wrap -->


<!-- ============================================================
     RELATED ARTICLES
============================================================ -->
<?php
$current_cats = wp_get_post_categories(get_the_ID());
$related_query = new WP_Query([
    'posts_per_page'      => 3,
    'post_status'         => 'publish',
    'post__not_in'        => [get_the_ID()],
    'category__in'        => $current_cats,
    'ignore_sticky_posts' => true,
]);
?>

<?php if ( $related_query->have_posts() ) : ?>
<div class="related-section mt-40">
    <div class="wrap">
        <div class="sec-head">
            <h2>مقالات ذات صلة</h2>
            <div class="line"></div>
        </div>
        <div class="related-grid mb-80">
            <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
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
                    </div>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
