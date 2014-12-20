<?php 
/*
Добавляем шорткод, который будет выводить галлерею.
*/

/*
animation: "fade",              //String: Select your animation type, "fade" or "slide"
slideDirection: "horizontal",   //String: Select the sliding direction, "horizontal" or "vertical"
slideshow: true,                //Boolean: Animate slider automatically
slideshowSpeed: 7000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
animationDuration: 600,         //Integer: Set the speed of animations, in milliseconds
directionNav: true,             //Boolean: Create navigation for previous/next navigation? (true/false)
controlNav: true,               //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
keyboardNav: true,              //Boolean: Allow slider navigating via keyboard left/right keys
mousewheel: false,              //Boolean: Allow slider navigating via mousewheel
prevText: "Previous",           //String: Set the text for the "previous" directionNav item
nextText: "Next",               //String: Set the text for the "next" directionNav item
pausePlay: false,               //Boolean: Create pause/play dynamic element
pauseText: 'Pause',             //String: Set the text for the "pause" pausePlay item
playText: 'Play',               //String: Set the text for the "play" pausePlay item
randomize: false,               //Boolean: Randomize slide order
slideToStart: 0,                //Integer: The slide that the slider should start on. Array notation (0 = first slide)
animationLoop: true,            //Boolean: Should the animation loop? If false, directionNav will received "disable" classes at either end
pauseOnAction: true,            //Boolean: Pause the slideshow when interacting with control elements, highly recommended.
pauseOnHover: false,            //Boolean: Pause the slideshow when hovering over slider, then resume when no longer hovering
controlsContainer: "",          //Selector: Declare which container the navigation elements should be appended too. Default container is the flexSlider element. Example use would be ".flexslider-container", "#container", etc. If the given element is not found, the default action will be taken.
manualControls: "",             //Selector: Declare custom control navigation. Example would be ".flex-control-nav li" or "#tabs-nav li img", etc. The number of elements in your controlNav should match the number of slides/tabs.
start: function(){},            //Callback: function(slider) - Fires when the slider loads the first slide
before: function(){},           //Callback: function(slider) - Fires asynchronously with each slider animation
after: function(){},            //Callback: function(slider) - Fires after each slider animation completes
end: function(){}               //Callback: function(slider) - Fires when the slider reaches the last slide (asynchronous)
*/

add_shortcode( 'galery_interior', 'add_shortcode_gallery_cp' );
function add_shortcode_gallery_cp( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'ids' => '',
			'id_slider' => 'flexslider-slider',
			'id_carousel' => 'flexslider-carousel',
			'width_slider' => '',
			'height_slider' => '',
			'width_carousel' => '',
			'height_carousel' => '',			
			), $atts )
	);

	$ids=explode(',', $ids);
	
	$gallery = new Attachments( 'attachments_appartaments',25 );	
	
	ob_start();
	if($gallery->exist()):
	?>
	<div class="gallery_cp flexslider">
		<div id="<?php echo $id_slider; ?>" class="flexslider slider">
			<ul class="slides">
			<?php while( $gallery->get() ) : ?>
				<li>
					<img src="<?php echo $gallery->src('full') ?>" width="<?php echo $width_slider; ?>" height="<?php echo $height_slider; ?>" alt="Фото девушек" />
				</li>
			<?php endwhile; ?>
			</ul>
		</div>
		
		<div id="<?php echo $id_carousel; ?>" class="flexslider carousel">
		  <ul class="slides">
			<?php $gallery = new Attachments( 'attachments_appartaments',25 );
					$start_slide = 0;
					$i = 0;
					while( $gallery->get() ) : 
						if(isset( $_GET['img']) AND $gallery->id() == $_GET['img'] ){
							$start_slide = $i;
						}
					?>
				<li>
					<a href="#<?php echo $id_slider; ?>">
					<img src="<?php echo $gallery->src(array($width_carousel, $height_carousel)) ?>" width="<?php echo $width_carousel; ?>" height="<?php echo $height_carousel; ?>" alt="Фото девушек" />
					</a>
				</li>
			<?php $i++; endwhile; ?>
			</ul>
		</div>
		<script type="text/javascript">
			(function ($) {
			   $(window).load(function() {
				  $('#<?php echo $id_carousel; ?>').flexslider({
					animation: "slide",
					slideToStart: 2,
					controlNav: false,
					//directionNav: false,
					animationLoop: false,
					slideshow: false,
					itemWidth: <?php echo $width_carousel; ?>,
					itemMargin: 5,
					prevText: '',
					nextText: '',
					asNavFor: '#<?php echo $id_slider; ?>'
				  });
				  
				 
				  
				  $('#<?php echo $id_slider; ?>').flexslider({
					animation: "slide",
					controlNav: false,
					//directionNav: false,
					touch: true,
					prevText: '',
					nextText: '',
					itemWidth: 1100,
					animationLoop: false,
					slideshow: false,
					sync: "#<?php echo $id_carousel; ?>"
				  });
					//Стартуем с нужного нам слайда
					$('#<?php echo $id_slider; ?>').flexslider(<?php echo $start_slide; ?>);
					
					<?php if( !is_page_template( 'direct.php' ) ): ?>
					//После загрузки страницы скролим к слайдеру
					$('html, body').animate({ scrollTop: $('#<?php echo $id_slider; ?>').offset().top }, 1000);
					
					
					//При нажатии на тригеры скроллим к слайдеру
					$('a[href^="#"], a[href^="."]').click( function(){ // если в href начинается с # или ., то ловим клик
						var scroll_el = $(this).attr('href'); // возьмем содержимое атрибута href
						if (scroll_el.length != 0) { // проверим существование элемента чтобы избежать ошибки
						$('html, body').animate({ scrollTop: $(scroll_el).offset().top }, 500); // анимируем скроолинг к элементу scroll_el
						}
					});
					<?php endif; ?>
			   });
			}(jQuery));

			//jQuery(document).ready(function ($) {
			  // The slider being synced must be initialized first

			//});
		</script>
	</div>
	<?php 	endif;
	$content = ob_get_contents();
    ob_end_clean();
	
	return $content;
}