<?php
/**
 * Template Name: Blank ohne container
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */
$pageSettings = apply_filters('get_page_meta_data', (int)get_the_ID());
$pageSettings->title_css ? $titleCss = 'class="entry-title ' . $pageSettings->title_css . '"' : $titleCss = 'class="entry-title"';
get_header();
?>
    <div class="site-content">
<?= $pageSettings->custum_header; ?>
    <div id="content">
        <div id="primary" class="content-area">
            <main id="main" class="site-main">
                <div class="entry-content">
                    <?php the_post(); ?>
                    <?php the_content(); ?>
                    <?php wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'bootscore'),
                        'after' => '</div>',
                    ));
                    ?>
                </div>

            </main><!-- #main -->

        </div><!-- #primary -->
    </div><!-- #content -->

<?php
get_footer();
