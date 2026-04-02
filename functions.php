<?php
/**
 * Mukhayyam Theme Functions
 */

// ============================================================
// 1. THEME SETUP
// ============================================================
function mukhayyam_setup() {

    // RTL + Arabic language support
    load_theme_textdomain( 'mukhayyam', get_template_directory() . '/languages' );

    // Featured images on posts
    add_theme_support( 'post-thumbnails' );

    // HTML5 markup
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
    ]);

    // Title tag handled by WP
    add_theme_support( 'title-tag' );

    // Register nav menus
    register_nav_menus([
        'primary' => __( 'القائمة الرئيسية', 'mukhayyam' ),
        'social'  => __( 'روابط التواصل الاجتماعي', 'mukhayyam' ),
    ]);
}
add_action( 'after_setup_theme', 'mukhayyam_setup' );


// ============================================================
// 2. ENQUEUE STYLES & SCRIPTS
// ============================================================
function mukhayyam_scripts() {

    // Google Fonts (Arabic)
    wp_enqueue_style(
        'mukhayyam-fonts',
        'https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Cairo:wght@300;400;600;700;900&family=Tajawal:wght@300;400;500;700&display=swap',
        [],
        null
    );

    // Main stylesheet
    wp_enqueue_style(
        'mukhayyam-main',
        get_template_directory_uri() . '/assets/css/main.css',
        [ 'mukhayyam-fonts' ],
        '1.0.0'
    );

    // Main JS
    wp_enqueue_script(
        'mukhayyam-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        '1.0.0',
        true // load in footer
    );
}
add_action( 'wp_enqueue_scripts', 'mukhayyam_scripts' );


// ============================================================
// 3. CUSTOM POST EXCERPT LENGTH
// ============================================================
function mukhayyam_excerpt_length( $length ) {
    return 25; // كلمة
}
add_filter( 'excerpt_length', 'mukhayyam_excerpt_length' );

function mukhayyam_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'mukhayyam_excerpt_more' );


// ============================================================
// 4. CUSTOM IMAGE SIZES
// ============================================================
add_image_size( 'mukhayyam-hero',     1200, 675,  true ); // Hero main 16:9
add_image_size( 'mukhayyam-card',     800,  500,  true ); // Article card
add_image_size( 'mukhayyam-side',     400,  300,  true ); // Side card
add_image_size( 'mukhayyam-thumb',    300,  225,  true ); // List thumbnail


// ============================================================
// 5. HELPER FUNCTIONS
// ============================================================

/**
 * طباعة badge التصنيف
 */
function mukhayyam_category_badge( $post_id = null, $class = '' ) {
    if ( ! $post_id ) $post_id = get_the_ID();
    $cats = get_the_category( $post_id );
    if ( empty( $cats ) ) return;
    $cat = $cats[0];
    echo '<span class="badge ' . esc_attr( $class ) . '">' . esc_html( $cat->name ) . '</span>';
}

/**
 * وقت القراءة التقديري
 */
function mukhayyam_reading_time( $post_id = null ) {
    if ( ! $post_id ) $post_id = get_the_ID();
    $content    = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( strip_tags( $content ) );
    $minutes    = max( 1, ceil( $word_count / 200 ) );
    return $minutes . ' دقيقة للقراءة';
}

/**
 * التاريخ بالعربية
 */
function mukhayyam_arabic_date( $format = 'j F Y', $post_id = null ) {
    if ( ! $post_id ) $post_id = get_the_ID();
    return get_the_date( $format, $post_id );
}

/**
 * أزرار المشاركة
 */
function mukhayyam_share_buttons( $post_id = null ) {
    if ( ! $post_id ) $post_id = get_the_ID();
    $url   = urlencode( get_permalink( $post_id ) );
    $title = urlencode( get_the_title( $post_id ) );
    ?>
    <div class="share-row">
        <a href="https://t.me/share/url?url=<?= $url ?>&text=<?= $title ?>" target="_blank" title="تيليغرام">✈</a>
        <a href="https://wa.me/?text=<?= $title ?>%20<?= $url ?>" target="_blank" title="واتساب">💬</a>
        <a href="https://twitter.com/intent/tweet?url=<?= $url ?>&text=<?= $title ?>" target="_blank" title="تويتر">✕</a>
    </div>
    <?php
}

/**
 * الـ fallback لو ما في صورة مميزة
 */
function mukhayyam_post_thumbnail( $size = 'mukhayyam-card', $class = '' ) {
    if ( has_post_thumbnail() ) {
        the_post_thumbnail( $size, [ 'class' => $class ] );
    } else {
        // Gradient placeholder بناءً على ID المقال
        $gradients = ['g1','g2','g3','g4','g5','g6','g7','g8','g9','g10'];
        $g = $gradients[ get_the_ID() % 10 ];
        echo '<div class="img-placeholder ' . $g . '"></div>';
    }
}


// ============================================================
// 6. WIDGET AREAS
// ============================================================
function mukhayyam_widgets_init() {
    register_sidebar([
        'name'          => 'السايدبار الرئيسي',
        'id'            => 'sidebar-main',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ]);
}
add_action( 'widgets_init', 'mukhayyam_widgets_init' );


// ============================================================
// 7. REMOVE UNNECESSARY WP STUFF
// ============================================================
remove_action( 'wp_head', 'wp_generator' );             // إخفاء نسخة WP
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
