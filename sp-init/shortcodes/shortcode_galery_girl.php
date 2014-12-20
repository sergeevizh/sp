<?php 
add_shortcode( 'girl_gallery_cp', 'callback_girl_gallery_cp' );
function callback_girl_gallery_cp($atts) {
		// Attributes
		extract( shortcode_atts(
			array(
				'block' => 'holder', //switcher or holder
				'girl_name' => 'Имя',
				'width' => '468',
				'height' => '659' 
				), $atts )
		);	
		
		ob_start();
		
		$gallery = new Attachments( 'attachments_girls',get_the_ID() );
		$new = get_post_meta(get_the_ID(), 'new', 1);
		if($gallery->exist()):
		
			if($block == 'holder'){ ?>
			<div class="gallery-holder" style="overflow:hidden;width:<?php echo $width; ?>px; height:<?php echo $height; ?>px">
				<ul class="slide-list">
					<?php while( $gallery->get() ) : ?>
						<li class="slide "  style="width:<?php echo $width; ?>px; height:<?php echo $height; ?>px">
							<a 
							style="z-index:10;display:block;width:<?php echo $width; ?>px; height:<?php echo $height; ?>px"
							rel="girls_group" 
							class="holder-link " 
							title="Массажистка <?php echo $girl_name; ?>"
							href="<?php echo $gallery->src('full'); ?>">
								<img src="<?php echo $gallery->src(array($width, $height)); ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" alt=" Фото девушек" />
								<?php if($new == 1):?>
									<span class="girl-in-slider-new"></span>
								<?php endif;?>
							</a>
						</li>
					<?php endwhile; ?>
				</ul>
			</div>
			<script type="text/javascript">
				(function ($) {
				   $(window).load(function() {
						$("a[rel=girls_group]").fancybox({
						'transitionIn'		: 'none',
						'transitionOut'		: 'none',
						'titlePosition' 	: 'over',
						'opacity'		: true,
						'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
							return '<span id="fancybox-title-over">Фото ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
						}
							
						});
				   });
				}(jQuery));
				
				jQuery.fn.fadeGallery = function(_options) {
					var _options = jQuery.extend({
						slideElements: '.slide',
						pagerLinks: '.switcher li a',
						btnNext: 'a.next',
						btnPrev: 'a.prev',
						btnPlayPause: 'a.play-pause',
						pausedClass: 'paused',
						playClass: 'playing',
						activeClass: 'active',
						pauseOnHover: true,
						autoRotation: false,
						autoHeight: false,
						switchTime: 3000,
						duration: 650,
						event: 'click'
					}, _options);

					return this.each(function() {
						var _this = jQuery(this);
						var _slides = jQuery(_options.slideElements, _this);
						var _pagerLinks = jQuery(_options.pagerLinks, _this);
						var _btnPrev = jQuery(_options.btnPrev, _this);
						var _btnNext = jQuery(_options.btnNext, _this);
						var _btnPlayPause = jQuery(_options.btnPlayPause, _this);
						var _pauseOnHover = _options.pauseOnHover;
						var _autoRotation = _options.autoRotation;
						var _activeClass = _options.activeClass;
						var _pausedClass = _options.pausedClass;
						var _playClass = _options.playClass;
						var _autoHeight = _options.autoHeight;
						var _duration = _options.duration;
						var _switchTime = _options.switchTime;
						var _controlEvent = _options.event;

						var _hover = false;
						var _prevIndex = 0;
						var _currentIndex = 0;
						var _slideCount = _slides.length;
						var _timer;
						if (!_slideCount) return;
						_slides.hide().eq(_currentIndex).show();
						if (_autoRotation) _this.removeClass(_pausedClass).addClass(_playClass);
						else _this.removeClass(_playClass).addClass(_pausedClass);

						if (_btnPrev.length) {
							_btnPrev.bind(_controlEvent, function() {
								prevSlide();
								return false;
							});
						}
						if (_btnNext.length) {
							_btnNext.bind(_controlEvent, function() {
								nextSlide();
								return false;
							});
						}
						if (_pagerLinks.length) {
							_pagerLinks.each(function(_ind) {
								jQuery(this).bind(_controlEvent, function() {
									if (_currentIndex != _ind) {
										_prevIndex = _currentIndex;
										_currentIndex = _ind;
										switchSlide();
									}
									return false;
								});
							});
						}

						if (_btnPlayPause.length) {
							_btnPlayPause.bind(_controlEvent, function() {
								if (_this.hasClass(_pausedClass)) {
									_this.removeClass(_pausedClass).addClass(_playClass);
									_autoRotation = true;
									autoSlide();
								} else {
									if (_timer) clearRequestTimeout(_timer);
									_this.removeClass(_playClass).addClass(_pausedClass);
								}
								return false;
							});
						}

						function prevSlide() {
							_prevIndex = _currentIndex;
							if (_currentIndex > 0) _currentIndex--;
							else _currentIndex = _slideCount - 1;
							switchSlide();
						}

						function nextSlide() {
							_prevIndex = _currentIndex;
							if (_currentIndex < _slideCount - 1) _currentIndex++;
							else _currentIndex = 0;
							switchSlide();
						}

						function refreshStatus() {
							if (_pagerLinks.length) _pagerLinks.removeClass(_activeClass).eq(_currentIndex).addClass(_activeClass);
							_slides.eq(_prevIndex).removeClass(_activeClass);
							_slides.eq(_currentIndex).addClass(_activeClass);
						}

						function switchSlide() {
							_slides.stop(true, true);
							_slides.eq(_prevIndex).fadeOut(_duration);
							_slides.eq(_currentIndex).fadeIn(_duration);
							refreshStatus();
							autoSlide();
						}

						function autoSlide() {
							if (!_autoRotation || _hover) return;
							if (_timer) clearRequestTimeout(_timer);
							_timer = requestTimeout(nextSlide, _switchTime + _duration);
						}
						if (_pauseOnHover) {
							_this.hover(function() {
								_hover = true;
								if (_timer) clearRequestTimeout(_timer);
							}, function() {
								_hover = false;
								autoSlide();
							});
						}
						refreshStatus();
						autoSlide();
					});
				}
			</script>
			<?php 
			}
			elseif($block == 'switcher'){ 
			?>
				<div class="switcher">
					<ul>		
				
					<?php while( $gallery->get() ) : ?>
						<li class="" >
							<a href="#">
								<img src="<?php echo $gallery->src(array($width, $height)) ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" alt="Фото девушек" />
							</a>
						</li>
					<?php endwhile; ?>
					
					</ul>
				</div>		
			<?php 
			}
		endif;
		?>

	<?php
	$content = ob_get_contents();
    ob_end_clean();
	
	return $content;
}