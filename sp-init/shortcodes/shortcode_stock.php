<?php 
/*
Добавляем шорткод, который будет выводить галлерею.
*/
add_shortcode( 'stock_cp', 'add_shortcode_stock_cp' );
function add_shortcode_stock_cp( $atts ) {
	// Attributes
	extract( shortcode_atts(
			array(
				'width' => '',
				'height' => '' 
				), $atts )
	);	

	ob_start();

	$gallery = new Attachments( 'attachments_stock', cp_get_id_service_page('aktsii') );
	if($gallery->exist()):
	?>

	<div class="gallery_cp flexslider stock_cp " style="width:<?php echo $width; ?>px;">
		<div id="flexslider-slider" class="flexslider slider">
			<ul class="slides ">
			<?php while( $gallery->get() ) :  ?>
				<li class="">
				  <img src="<?php echo $gallery->src('full'); ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" alt="Акции" />
				</li>
			<?php endwhile; ?>
			</ul>
		</div>
		
		<script type="text/javascript">
			(function ($) {
			   $(window).load(function() {
				  $('#flexslider-slider').flexslider({
					animation: "fade",
					controlNav: true,
					touch: true,
					prevText: '',
					nextText: '',
					itemWidth: <?php echo $width; ?>,
					animationLoop: false,
					slideshow: false,
					sync: "#flexslider-carousel"
				  });			   
			   });
			}(jQuery));

			//jQuery(document).ready(function ($) {
			  // The slider being synced must be initialized first

			//});
		</script>
	</div>
	<?php endif;
	$content = ob_get_contents();
    ob_end_clean();
	
	return $content;
}

add_action( $tag = 'wp_enqueue_scripts', 'add_flexslider_styles' );
function add_flexslider_styles(){
	wp_register_style( $handle = 'flexslider_stock_styles', $src = get_stylesheet_directory_uri().'/css/flexslider_stock.css');
	
	if(is_post_type_archive( 'services' ) OR is_front_page() ){
		wp_enqueue_style( 'flexslider_stock_styles' );
	} 
}