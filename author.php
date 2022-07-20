<?php
/**
 * The template for displaying category pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

get_header();
?>

    <div class="site-content">
        <?php
        if (get_hupa_option('autoren_select_header')): ?>
            <div class="custom-header-wrapper">
                <?= apply_filters('get_content_custom_header', get_hupa_option('autoren_select_header'))->custum_header?>
            </div>
        <?php endif; ?>

        <div id="primary" class="content-area archive-wrapper  container pt-5">
            <!-- Hook to add something nice -->
            <?php bs_after_primary(); ?>

            <header class="page-header mb-4 d-flex">
                <div class="flex-shrink-0 me-3">
                    <?php echo get_avatar( get_the_author_meta('email'), '80', $default='', $alt='', array( 'class' => array( 'img-thumbnail rounded-circle' ) ) ); ?>
                </div>
                <div class="author-bio">
                    <h3><?php the_author(); ?></h3>
                    <?php the_author_meta('description'); ?>
                </div>
            </header>
            <div class="row">
                <div class="col">
                    <main id="main" class="site-main">
                        <?php if (have_posts()) : ?>
                            <?php while (have_posts()) : the_post();
                                $pageSettings = apply_filters('get_page_meta_data', (int)get_the_ID()); ?>
                                <div class="card shadow-sm horizontal mb-4">
                                    <div class="row">
                                        <!-- Featured Image-->
                                        <?php if (get_hupa_option('autoren_show_post_image')): ?>
                                            <?php if (has_post_thumbnail())
                                                echo '<div class="card-img-left-md img-archive-left align-self-center col-lg-4">' . get_the_post_thumbnail(null, 'large') . '</div>';
                                        endif; ?>
                                        <div class="col">
                                            <div class="card-body">
                                                <?php !get_hupa_option('autoren_show_kategorie') ?: bootscore_category_badge(); ?>
                                                <!-- Title -->
                                                <h4 class="blog-post-title pt-3">
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php
                                                        if ($pageSettings->showTitle) {
                                                            echo $pageSettings->custom_title ?: get_the_title();
                                                        } ?>
                                                    </a>
                                                </h4>
                                                <!-- Meta -->
                                                <?php if ('post' === get_post_type()) : ?>
                                                    <small class="fst-normal fw-normal d-inline-block text-muted mb-2">
                                                        <?php
                                                        !get_hupa_option('autoren_show_post_date') ?: the_date();
                                                        !get_hupa_option('autoren_show_post_author') ?: bootscore_author();
                                                        !get_hupa_option('autoren_show_post_kommentar') ?: bootscore_comment_count();
                                                        !get_hupa_option('edit_link') ?: bootscore_edit();
                                                        ?>
                                                    </small>
                                                <?php endif; ?>
                                                <!-- Excerpt & Read more -->
                                                <div class="archive-card-txt card-text fst-normal fw-normal mt-auto">
                                                    <?php the_excerpt(); ?> <a class="fst-normal small fw-normal read-more"
                                                                               href="<?php the_permalink(); ?>"><?php _e('Read more »', 'bootscore'); ?></a>
                                                </div>
                                                <?php if (get_hupa_option('social_kategorie')): ?>
                                                    <footer <?php post_class("entry-footer category") ?>>
                                                        <?php hupa_social_media(); ?>
                                                    </footer>
                                                <?php endif; ?>
                                                <!-- Tags -->
                                                <?php !get_hupa_option('autoren_show_post_tags') ?: bootscore_tags(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>

                        <!-- Pagination -->
                        <div>
                            <?php hupa_theme_pagination(); ?>
                        </div>
                    </main><!-- #main -->
                </div><!-- col -->
                <?php if (get_hupa_option('autoren_show_sidebar')): ?>
                    <div class="col-md-12 col-xl-4 col-xxl-3 mt-4 mt-md-0">
                        <aside id="secondary" class="widget-area">
                            <?php dynamic_sidebar('sidebar-' . get_hupa_option('autoren_select_sidebar')); ?>
                        </aside>
                    </div>
                <?php endif; ?>
            </div><!-- row -->
        </div><!-- #primary -->
    </div><!-- #content -->
<?php
if (get_hupa_option('autoren_select_footer')): ?>
    <div class="custom-footer-wrapper">
        <?= apply_filters('get_content_custom_footer', get_hupa_option('autoren_select_footer'))->custum_footer; ?>
    </div>
<?php endif;
get_footer();
