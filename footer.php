
    <footer role="contentinfo">
            
        <div class="footer__menus">
            <div class="max__width">
                <?php
                    while(have_rows('footer_menus', 'options')) : the_row();

                    $footer_menu = get_sub_field('footer_menu');
                ?>
                    <article class="footer__menu">
                        <?php if($footer_menu): ?>
                            <h5><?php echo $footer_menu->name; ?> <i class="fas fa-chevron-down"></i></h5>
                            <?php wp_nav_menu(array('menu' => $footer_menu->name, 'container' => false)); ?>
                        <?php endif; ?>
                    </article>

                <?php endwhile; ?>

                <article>
                    <h5>Sheffield</h5>
                    <ul>
                        <li class="footer-email"><i class="fa-regular fa-at"></i> <?php echo FL1_Helpers::hide_email(get_field('footer_email', 'option')) ?></li>
                        <li class="footer-address"><i class="fa-regular fa-phone"></i> 0114 419 0410</li>
                        <li class="footer-address"><i class="fa-regular fa-location-dot"></i> <?php the_field('footer_address', 'option'); ?></li>
                    </ul>
                </article>

                <article>
                    <h5>Chesterfield</h5>
                    <ul>
                        <li class="footer-email"><i class="fa-regular fa-at"></i> <?php echo FL1_Helpers::hide_email(get_field('footer_email', 'option')) ?></li>
                        <li class="footer-address"><i class="fa-regular fa-phone"></i> 0114 419 0410</li>
                        <li class="footer-address"><i class="fa-regular fa-location-dot"></i> 7 Packers Row,<br>Chesterfield,<br>S40 1RB</li>
                    </ul>
                </article>

            </div>

        </div><!-- footer__menus -->

        <div class="subfooter">
            <div class="max__width">

                <div class="subfooter--left">
                    <figure><img src="<?php echo esc_url(get_stylesheet_directory_uri().'/img/logo.webp'); ?>" alt="<?php bloginfo('name'); ?>" alt="<?php bloginfo('name') ?>" /></figure>
                </div><!-- subfooter--left -->

                <div class="subfooter--right">
                    <small><a href="https://thomson-website-solutions.com/" target="_blank">Website by Thomson Website Solutions</a></small>
                </div><!-- subfooter--left -->

            </div><!-- max__width -->
        </div><!-- subfooter -->
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
