<?php
/**
 * Template Name: Blank mit container
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */
$pageId = is_singular() ? get_the_ID() : 0;
$pageSettings = apply_filters('get_page_meta_data', (int)$pageId);
$pageSettings->title_css ? $titleCss = 'class="entry-title ' . $pageSettings->title_css . '"' : $titleCss = 'class="entry-title"';
get_header();
?>
    <div class="site-content">
        <?= $pageSettings->custum_header; ?>
        <div id="content" class="<?= $pageSettings->main_container ? 'container' : 'container-fluid' ?> pb-3">
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
                    <footer <?php post_class("entry-footer") ?>></footer>
                </main><!-- #main -->
            </div><!-- #primary -->
        </div><!-- #content -->
    </div>
<?php
get_footer();