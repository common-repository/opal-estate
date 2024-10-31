<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $property, $post;
$property = opalesetate_property( get_the_ID() );

?>
<article itemscope itemtype="http://schema.org/Property" <?php post_class(); ?>>
	<?php opalestate_get_loop_thumbnail( opalestate_get_option('loop_image_size','large') ); ?>
	<div class="entry-content">
		<div class="property-price-wrapper"><?php opalestate_property_loop_price(); ?></div>
		<?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>
		<?php opalestate_get_loop_short_meta(); ?>
	</div><!-- .entry-content -->

	<?php do_action( 'opalestate_after_property_loop_item' ); ?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />
</article><!-- #post-## -->
