<?php
defined( 'ABSPATH' ) or die();

/**
 * ADMIN OPTIONS HANDLE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */


$pageSettings = apply_filters('get_page_meta_data', (int) get_the_ID());
$pageSettings->show_menu ? $show = '' : $show = 'd-none';

$id404 = get_hupa_option('hupa_select_404');
if($id404 && is_404()) {
    $showMenuMeta = get_post_meta(get_hupa_option('hupa_select_404'), '_hupa_show_menu', true);
    is_404() && $showMenuMeta ? $show = '' : $show = 'd-none';
}

$menu = apply_filters('get_menu_auswahl', get_hupa_option('menu'));
get_hupa_option( 'handy' ) == 1 ? $handyMenu = 'menu1' : $handyMenu = 'menu2';

?>
<nav id="nav-main-starter"
     class="<?=$show?> hupa-box-shadow navbar-root navbar navbar-expand-lg <?=$menu->height?>  <?= ! get_hupa_option( 'fix_header' ) ?: 'fixed-top' ?>">
    <div class="<?=$menu->relative?>  <?=$pageSettings->menu_container ? 'container-lg ' . $menu->container . '' : 'container-fluid ' . $menu->container?>">
        <?php if (!$menu->show_img && get_hupa_frontend('nav-img')):?>
            <a class="middle-image-nav-sm" href="<?php echo esc_url( home_url() ); ?>">
                <img src="<?=get_hupa_frontend('nav-img')->url?>"
                     alt="<?=get_bloginfo('name')?>"
                     class="img-fluid logo md" width="">
            </a>
        <?php endif; ?>
        <?php if(get_hupa_frontend('nav-img') && $menu->show_img): ?>
            <a class="navbar-brand d-none d-xl-flex <?=$menu->logo?>" href="<?php echo esc_url( home_url() ); ?>">
                <img src="<?= get_hupa_frontend('nav-img')->url?>"
                     alt="<?=get_bloginfo('name')?>" class="logo md" width="">
            </a>
            <a class="navbar-brand  ps-2 img-fluid d-lg-flex d-xl-none <?=$menu->logo?>" href="<?php echo esc_url( home_url() ); ?>">
                <img src="<?=get_hupa_frontend('nav-img')->url?>"
                     alt="<?=get_bloginfo('name')?>"
                     class="logo sm" width="">
            </a>
        <?php endif; ?>
        <!-- Top Nav Widget -->
        <div class="top-nav main-widget order-lg-3  d-none d-sm-flex justify-content-end  <?=$menu->widget?>">
            <?php if ( is_active_sidebar( 'top-nav' ) ) : ?>
                <div class="widget-navigation">
                    <?php dynamic_sidebar( 'top-nav' ); ?>
                </div>
            <?php endif; ?>
        </div>
        <button class="navbar-toggler border-0 focus-0 py-2 pe-0 ms-auto ms-lg-2" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvas-navbar" aria-controls="offcanvas-navbar">
            <i class="text-secondary fa fa-bars"></i>
        </button>

        <div class="offcanvas <?=$handyMenu?> offcanvas-end" tabindex="-1" data-bs-hideresize="true" id="offcanvas-navbar">
            <div class="offcanvas-header">
                <?php if(get_hupa_frontend('nav-img')): ?>
                    <div id="logoPlaceholder"></div>
                <?php else: ?>
                    <div id="customPlaceholder"></div>
                <?php endif; ?>
                <div class="menu btn2 open cursor-pointer"data-bs-dismiss="offcanvas"><div class="icon"></div></div>
            </div>
            <div class="offcanvas-body justify-content-<?=$menu->block?>">
                <!-- Bootstrap 5 Nav Walker Main Menu -->
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'main-menu',
                    'container'      => false,
                    'menu_class'     => '',
                    'fallback_cb'    => '__return_false',
                    'items_wrap'     => '<ul id="bootscore-navbar" class="navbar-nav align-items-center %2$s">%3$s</ul>',
                    'depth'          => 6,
                    'walker' => new bootstrap_5_wp_nav_menu_walker()
                ) );
                ?>
                <!-- Bootstrap 5 Nav Walker Main Menu End -->
            </div>
        </div>
    </div><!-- container -->
</nav>