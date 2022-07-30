<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Bootscore
 */

if (get_hupa_option('hupa_select_404')):
    get_header('404');
    global $post;
    $post = get_post(get_hupa_option('hupa_select_404'));
    $pageSettings = apply_filters('get_page_meta_data', (int)get_the_ID());
    $pageSettings->title_css ? $titleCss = 'class="' . $pageSettings->title_css . '"' : $titleCss = '';
    ?>
    <div class="site-content">
        <?= $pageSettings->custum_header; ?>
        <div id="content" class="<?= $pageSettings->main_container ? 'container' : 'container-fluid' ?>">
            <div id="primary" class="content-area">
                <!-- Hook to add something nice -->
                <?php bs_after_primary(); ?>
                <main id="main" class="site-main">

                    <header class="entry-header">
                        <!-- Title -->
                        <?php
                        if ($pageSettings->showTitle) {
                            echo $pageSettings->custom_title ? '<h1 ' . $titleCss . '> ' . $pageSettings->custom_title . '</h1>' : '<h1 ' . $titleCss . '>' . get_the_title() . '</h1>';
                        }
                        ?>
                        <!-- Featured Image-->
                        <?php bootscore_post_thumbnail(); ?>
                        <!-- .entry-header -->
                    </header>
                    <div class="entry-content">
                        <!-- Content -->
                        <?php the_content(); ?>
                        <!-- .entry-content -->
                        <?php wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'bootscore'),
                            'after' => '</div>',
                        ));
                        ?>
                    </div>
                    <footer <?php post_class("entry-footer") ?>>
                        <?php hupa_social_media(); ?>
                    </footer>
                    <!-- Comments -->
                    <?php comments_template(); ?>
                </main><!-- #main -->
            </div><!-- #primary -->
        </div><!-- #content -->
    </div>
    <?php
    get_footer('404');
else:
    get_header();
    ?>
    <div id="content" class="site-content container py-5 mt-5">
        <div id="primary" class="content-area">

            <main id="main" class="site-main">
                <section class="error-404 not-found">
                    <div class="page-404">

                        <h1 class="mb-3">404</h1>
                        <!-- Remove this line and place some widgets -->
                        <p class="alert alert-info mb-4"><?php esc_html_e('Page not found.', 'bootscore'); ?></p>
                        <!-- 404 Widget -->
                        <?php if (is_active_sidebar('404-page')) : ?>
                            <div><?php dynamic_sidebar('404-page'); ?></div>
                        <?php endif; ?>
                        <a class="btn btn-outline-primary" href="<?php echo esc_url(home_url()); ?>"
                           role="button"><?php esc_html_e('Back Home &raquo;', 'bootscore'); ?></a>
                    </div>
                </section><!-- .error-404 -->
            </main><!-- #main -->
        </div><!-- #primary -->
    </div><!-- #content -->
    <?php
    get_footer();
endif;
