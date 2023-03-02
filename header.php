<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php wp_title( ' - ', true, 'right' ); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="format-detection" content="telephone=no">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php include get_stylesheet_directory().'/modules/mega-menu-mobile.php'; ?>

	<div id="page">
		<header class="header">

            <?php if(get_field('sitewide_notice_enable', 'option')): ?>
                <div class="header--site__wide__notice">
                    <div class="max__width">
                        <?php the_field('sitewide_notice', 'option'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- TOP BAR -->
            <div class="top-bar">
				
				<div class="max__width">
                    <div class="left">
                        <p>Personal <span>Service.</span> Expert <span>Advice.</span> Remarkable <span>Eyewear.</span></p>
                    </div>				

                    <div class="right">
                        <a href="tel:01144190410" class="phone">
                            <i class="fa fa-phone"></i><span>0114 419 0410</span>
                        </a>
                        <a class="button primary" href="/contact-us">Contact Us</a>
                    </div>
                </div>	

			</div>

            <div class="header__main">

                <div class="max__width">
                    
                    <div class="header__main--left">
                        <div class="logo">
                            <a href="<?php echo esc_url(home_url()); ?>" title="<?php bloginfo('name'); ?>">
                               <img src="<?php echo esc_url(get_stylesheet_directory_uri().'/img/logo.webp'); ?>" alt="<?php bloginfo('name'); ?>"/>
                            </a>
                        </div><!-- logo -->
                    </div><!-- left -->

                    <div class="header__main--right">
                        <nav class="header__nav">
                            <?php include get_stylesheet_directory().'/modules/mega-menu.php'; ?>
                        </nav><!-- header__nav -->

                        <a href="#nav_mobile" class="burger__menu">
                            <i class="fal fa-bars"></i>
                        </a>
                    </div><!-- right -->

                </div><!-- max__width -->
            </div><!-- header__main -->
		</header><!-- header -->
