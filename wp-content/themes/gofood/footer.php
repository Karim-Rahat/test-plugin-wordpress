<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package goFood
 */

?>
	
	<footer id="siteFooter" class="py-4 border-top">
		<div class="container d-flex flex-wrap justify-content-between align-items-center">
			<div class="col-md-4 d-flex align-items-center">
				<?php if ( has_site_icon() ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1" aria-label="Bootstrap">
						<img style="width: 40px;" src="<?php echo esc_url( get_site_icon_url() ); ?>" alt="">
					</a>
				<?php endif ?>
				<span class="mb-3 mb-md-0 text-body-secondary copyright-text"><?php echo esc_html( get_theme_mod( 'gofood_copyright_text', '© 2025 GoFood LTD.' ) ); ?></span>
			</div>

			<ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
				<li class="ms-3"><a class="text-body-secondary social-facebook" href="<?php echo esc_url( get_theme_mod( 'gofood_facebook_url', '#' ) ); ?>" aria-label="Instagram"><i class="fa-brands fs-4 fa-instagram"></i></a></li>
				<li class="ms-3"><a class="text-body-secondary social-instagram" href="<?php echo esc_url( get_theme_mod( 'gofood_instagram_url', '#' ) ); ?>" aria-label="Facebook"><i class="fa-brands fs-4 fa-facebook"></i></a></li>
			</ul>
		</div>
	</footer>

<?php wp_footer(); ?>

</body>
</html>
