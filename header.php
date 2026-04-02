<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- SCROLL PROGRESS BAR -->
<div class="scroll-bar" id="scrollBar"></div>

<!-- ============================================================
     TOP BAR
============================================================ -->
<div class="topbar">
    <div class="topbar-social">
        <?php
        // روابط التواصل — غيّرها من Appearance → Menus
        $social_links = [
            'يوتيوب'    => '#',
            'تويتر'     => '#',
            'إنستغرام'  => '#',
            'تيليغرام'  => '#',
        ];
        foreach ( $social_links as $name => $url ) :
        ?>
            <a href="<?= esc_url( $url ) ?>" target="_blank"><?= esc_html( $name ) ?></a>
        <?php endforeach; ?>
    </div>
    <div class="topbar-date">
        <?php echo date_i18n( 'l، j F Y' ); ?>
    </div>
</div>

<!-- ============================================================
     HEADER
============================================================ -->
<header class="site-header">
    <div class="header-inner">

        <!-- LOGO -->
        <div class="logo-row">
            <a href="<?php echo home_url('/'); ?>" class="logo-arabic">
                <?php bloginfo('name'); ?>
            </a>
            <span class="logo-sub">
                <?php bloginfo('description'); ?>
            </span>
        </div>

        <!-- NAVIGATION -->
        <nav class="main-nav" role="navigation" aria-label="القائمة الرئيسية">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => '',
                'fallback_cb'    => 'mukhayyam_fallback_menu',
            ]);
            ?>
        </nav>

    </div>
</header>
<?php

/**
 * Fallback menu لو ما تم إعداد القائمة بعد
 */
function mukhayyam_fallback_menu() {
    $categories = get_categories(['hide_empty' => false, 'number' => 8]);
    echo '<ul>';
    echo '<li><a href="' . home_url('/') . '" class="' . (is_home() ? 'active' : '') . '">الرئيسية</a></li>';
    foreach ($categories as $cat) {
        $active = (is_category($cat->term_id)) ? 'active' : '';
        echo '<li><a href="' . get_category_link($cat->term_id) . '" class="' . $active . '">' . esc_html($cat->name) . '</a></li>';
    }
    echo '<li><a href="' . get_page_link(get_page_by_path('about')) . '">عن مخيّم</a></li>';
    echo '</ul>';
}
