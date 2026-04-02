<!-- ============================================================
     FOOTER
============================================================ -->
<footer class="site-footer">
    <div class="footer-wrap">
        <div class="footer-top">

            <!-- Brand -->
            <div class="footer-brand">
                <a href="<?php echo home_url('/'); ?>" class="f-logo">
                    <?php bloginfo('name'); ?>
                </a>
                <p class="f-about">
                    منصة صحفية عربية مستقلة تروي قصص الإنسان في زمن الحرب والنزوح. الكتابة مقاومة.
                </p>
            </div>

            <!-- Sections -->
            <div class="f-col">
                <h4>الأقسام</h4>
                <ul>
                    <?php
                    $cats = get_categories(['hide_empty' => false, 'number' => 6]);
                    foreach ($cats as $cat) :
                    ?>
                        <li>
                            <a href="<?= get_category_link($cat->term_id) ?>">
                                <?= esc_html($cat->name) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Site links -->
            <div class="f-col">
                <h4>المنصة</h4>
                <ul>
                    <li><a href="<?= home_url('/about') ?>">عن مخيّم</a></li>
                    <li><a href="<?= home_url('/submit') ?>">أرسل مقالك</a></li>
                    <li><a href="<?= home_url('/privacy') ?>">سياسة النشر</a></li>
                    <li><a href="<?= home_url('/copyright') ?>">حقوق النشر</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="f-col">
                <h4>تواصل</h4>
                <ul>
                    <li><a href="mailto:editor@mukhayyam.ps">editor@mukhayyam.ps</a></li>
                    <li><a href="#" target="_blank">تيليغرام</a></li>
                    <li><a href="#" target="_blank">تويتر / X</a></li>
                    <li><a href="#" target="_blank">إنستغرام</a></li>
                </ul>
            </div>

        </div>

        <!-- Bottom bar -->
        <div class="footer-bottom">
            <span>
                © <?php echo date('Y'); ?> <?php bloginfo('name'); ?> — رخصة المشاع الإبداعي CC-BY-NC
            </span>
            <div class="f-social">
                <a href="#" target="_blank" title="تيليغرام">✈</a>
                <a href="#" target="_blank" title="تويتر">✕</a>
                <a href="#" target="_blank" title="إنستغرام">◎</a>
                <a href="#" target="_blank" title="يوتيوب">▶</a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
