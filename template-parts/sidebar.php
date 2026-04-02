<aside class="sidebar">

    <!-- أكثر قراءة -->
    <div class="widget">
        <h3>الأكثر قراءة</h3>
        <ul class="widget-list">
            <?php
            $popular = new WP_Query([
                'posts_per_page'      => 5,
                'post_status'         => 'publish',
                'orderby'             => 'comment_count', // أو استخدم WP-PostViews plugin للمشاهدات
                'ignore_sticky_posts' => true,
            ]);
            while ($popular->have_posts()) : $popular->the_post();
            ?>
            <li>
                <a href="<?= get_permalink() ?>"><?= get_the_title() ?></a>
            </li>
            <?php endwhile; wp_reset_postdata(); ?>
        </ul>
    </div>

    <!-- نشرة بريدية -->
    <div class="nl-box">
        <h3>اشترك في النشرة</h3>
        <p>قصص لا تقرأها في مكان آخر، كل أسبوع في صندوق بريدك.</p>
        <div class="nl-form">
            <?php if ( shortcode_exists('mc4wp_form') ) : ?>
                <!-- Mailchimp for WP plugin -->
                <?php echo do_shortcode('[mc4wp_form id="your-form-id"]'); ?>
            <?php else : ?>
                <!-- Fallback بسيط -->
                <input type="email" placeholder="بريدك الإلكتروني">
                <button type="button">اشترك الآن</button>
            <?php endif; ?>
        </div>
    </div>

    <!-- الأقسام -->
    <div class="widget">
        <h3>تصفّح بالموضوع</h3>
        <div class="tags-cloud">
            <?php
            $all_cats = get_categories(['hide_empty' => false, 'number' => 12]);
            foreach ($all_cats as $cat) :
            ?>
            <a href="<?= get_category_link($cat->term_id) ?>" class="tag-pill">
                <?= esc_html($cat->name) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Widgets من WP Dashboard -->
    <?php if ( is_active_sidebar('sidebar-main') ) : ?>
        <?php dynamic_sidebar('sidebar-main'); ?>
    <?php endif; ?>

</aside>
