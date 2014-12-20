<?php
// поддержка миниатюр и мею
if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
	add_theme_support('menus');
}

function cp_get_id_service_page($service_page){
	
	$service_page_array = array(
		'about'
		,'girls'
		,'interior'
		,'vacansii'
		,'guest-room'
		,'schedule'
		,'direct'
		,'aktsii'
		,'sauna'
		,'index-2'
		,'direct'
		
	);
	
	if( in_array($service_page, $service_page_array) ){
		$page = get_page_by_path($service_page, OBJECT, 'page');
		return $page->ID ;	
	}
	else{
		return NULL;
	}
}


add_action( 'attachments_register', 'attachments_appartaments' );
function attachments_appartaments( $attachments ){
	$args = array(
		'label' => 'Интерьеры салона',
		'post_type' => array( 'page' ),
		'filetype' => array ( 'image' ),
		'note' => null,
		'button_text' => __( 'Прикрепить изображение или загрузить его', 'attachments' ),
		'modal_text' => __( 'Прикрепить изображение или загрузить его', 'attachments' ),
		'fields' => array(
			 array(
			  'name'      => 'title',                         
			  'type'      => 'text',                          
			  'label'     => __( 'Title', 'attachments' ),    
			  'default'   => 'title',                         
			)
		) 
	);
	 
	if(isset($_GET['post']) AND $_GET['post']== cp_get_id_service_page('interior')) $attachments->register( 'attachments_appartaments', $args );
}

add_action( 'attachments_register', 'attachments_appartaments_video' );
function attachments_appartaments_video( $attachments ){
	$args = array(
		'label' => 'Видео интерьеры салона',
		'post_type' => array( 'page' ),
		'filetype' => array ( 'video' ),
		'note' => null,
		'button_text' => __( 'Прикрепить видео или загрузить его', 'attachments' ),
		'modal_text' => __( 'Прикрепить видео или загрузить его', 'attachments' ),
		'fields' => array(
			 array(
			  'name'      => 'title',                         
			  'type'      => 'text',                          
			  'label'     => __( 'Title', 'attachments' ),    
			  'default'   => 'title',                         
			)
		) 
	);
	 
	if(isset($_GET['post']) AND $_GET['post']== cp_get_id_service_page('interior')) $attachments->register( 'attachments_appartaments_video', $args );
}

add_action( 'attachments_register', 'attachments_index_video' );
function attachments_index_video( $attachments ){
	$args = array(
		'label' => 'Видео интерьеры салона',
		'post_type' => array( 'page' ),
		'filetype' => array ( 'video' ),
		'note' => null,
		'button_text' => __( 'Прикрепить видео или загрузить его', 'attachments' ),
		'modal_text' => __( 'Прикрепить видео или загрузить его', 'attachments' ),
		'fields' => array(
			 array(
			  'name'      => 'title',                         
			  'type'      => 'text',                          
			  'label'     => __( 'Title', 'attachments' ),    
			  'default'   => 'title',                         
			)
		) 
	);
	 
	if(isset($_GET['post']) AND $_GET['post']== cp_get_id_service_page('index-2')) $attachments->register( 'attachments_index_video', $args );
}

add_action( 'attachments_register', 'attachments_direct_video' );
function attachments_direct_video( $attachments ){
	$args = array(
		'label' => 'Видео интерьеры салона',
		'post_type' => array( 'page' ),
		'filetype' => array ( 'video' ),
		'note' => null,
		'button_text' => __( 'Прикрепить видео или загрузить его', 'attachments' ),
		'modal_text' => __( 'Прикрепить видео или загрузить его', 'attachments' ),
		'fields' => array(
			 array(
			  'name'      => 'title',                         
			  'type'      => 'text',                          
			  'label'     => __( 'Title', 'attachments' ),    
			  'default'   => 'title',                         
			)
		) 
	); 
	 
	if(isset($_GET['post']) AND $_GET['post']== cp_get_id_service_page('direct')) $attachments->register( 'attachments_direct_video', $args );
}

add_action( 'attachments_register', 'attachments_stock' );
function attachments_stock( $attachments ){
	$args = array(
		'label' => 'Изображения для акций',
		'post_type' => array( 'page' ),
		'filetype' => array ( 'image' ),
		'note' => null,
		'button_text' => __( 'Прикрепить изображение или загрузить его', 'attachments' ),
		'modal_text' => __( 'Прикрепить изображение или загрузить его', 'attachments' ),
		'fields' => array(
			 array(
			  'name'      => 'title',                         
			  'type'      => 'text',                          
			  'label'     => __( 'Title', 'attachments' ),    
			  'default'   => 'title',                         
			)
		) 
	);
	 
	if(isset($_GET['post']) AND $_GET['post']== cp_get_id_service_page('aktsii')) $attachments->register( 'attachments_stock', $args );
}

add_action( 'attachments_register', 'attachments_sauna' );
function attachments_sauna( $attachments ){
	$args = array(
		'label' => 'Интерьеры сауны',
		'post_type' => array( 'page' ),
		'filetype' => array ( 'image' ),
		'note' => null,
		'button_text' => __( 'Прикрепить изображение или загрузить его', 'attachments' ),
		'modal_text' => __( 'Прикрепить изображение или загрузить его', 'attachments' ),
		'fields' => array(
			 array(
			  'name'      => 'title',                         
			  'type'      => 'text',                          
			  'label'     => __( 'Title', 'attachments' ),    
			  'default'   => 'title',                         
			)
		) 
	);
	 
	if(isset($_GET['post']) AND $_GET['post']== cp_get_id_service_page('sauna')) $attachments->register( 'attachments_sauna', $args );
}

add_filter('get_previous_post_join', 'exclude_dismissed_girls_join',10,3);
add_filter('get_next_post_join', 'exclude_dismissed_girls_join',10,3);
function exclude_dismissed_girls_join($join, $in_same_term, $excluded_terms){
	global $wpdb, $post;
	$my_join = $join . " INNER JOIN $wpdb->postmeta AS pm ON pm.post_id = p.ID ";
	return $my_join;
}
add_filter('get_previous_post_where', 'exclude_dismissed_girls_where',10,3);
add_filter('get_next_post_where', 'exclude_dismissed_girls_where',10,3);
function exclude_dismissed_girls_where($where, $in_same_term, $excluded_terms){
	global $wpdb, $post;
	$my_where = $where . " AND pm.meta_key = 'see-girl' AND pm.meta_value = 1 ";
	return $my_where;
}

/***************************************************/
/*********************  Видео   ********************/
remove_shortcode( 'video' );
add_shortcode( 'video', 'no_add_shortcode' );
function no_add_shortcode(){}

function cp_get_video_atts($p_id = false){
	$p_id = $p_id?$p_id:get_the_ID();
	$video_atts = false;
	$videos = new Attachments( 'attachments_girls_video', $p_id );
	if( $videos->exist() ){
		$videos->get();
		$video_atts['url'] = $videos->url();
		$video_atts['title'] = $videos->field('title');
	}
	
	return $video_atts;
}

function cp_get_photo_count($p_id = false){
	$p_id = $p_id?$p_id:get_the_ID();
	$photo_count = 0;
	$photo = new Attachments( 'attachments_girls', $p_id );
	if( $photo->exist() ) : 
		while( $photo->get() ) :
			$photo_count++;
		endwhile;
	endif;
	
	return $photo_count;
}

/* added by furserg */
function cp_get_video_count( $p_id = false ){
	$p_id = $p_id?$p_id:get_the_ID();
	$videos_count = 0;
	$videos = new Attachments( 'attachments_girls_video', $p_id );
	if( $videos->exist() ) : 
		while( $videos->get() ) :
			$videos_count++;
		endwhile;
	endif;
	
	return $videos_count;
}

/* added by furserg */
function cp_get_status_girl( $girl_id = false ){
	$girl_id       = $girl_id ? $girl_id : get_the_ID();
	$girl_schedule = get_post_meta( $girl_id, 'status-girl', true );
	
	if( is_array( $girl_schedule )){
		return $girl_schedule[ date( 'D' ) ];	 
	}
	else{
		return $girl_schedule;
	}	
}


function cp_get_contact_form(){ ?>
	<form method="post">
		<div class="handler_message"><?php handler_contact_form(); ?></div>
		<div class="contact-name">
			<span>Имя:</span>
			<input type="name" class="contact_input" name="contact_name" value="<?php echo $_POST['contact_name']; ?>" required >
		</div>
		<div class="contact-email">
			<span>E-mail:</span>
			<input type="email" class="contact_input" name="contact_email" value="<?php echo $_POST['contact_email']; ?>" required pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" >
		</div>
		<div class="contact-phone">
			<span>Телефон:</span>
			<input type="tel" 
			class="contact_input" 
			name="contact_phone" 
			value="<?php echo $_POST['contact_phone']; ?>" 
			placeholder="+7(000)999-88-77"
			title="Телефон в формате +7(000)999-88-77"
			required 
			pattern="\+[0-9]{1}\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}" >
		</div>
		<div class="contact-message">
			<span>Сообщение:</span>
			<textarea name="contact_message"><?php echo $_POST['contact_message']; ?></textarea>
		</div>
		<div class="contact-submit">
			<input name="submit" type="submit" id="submit" value="Оставить сообщение">
			<input type="hidden" name="contact_nonce" value="<?php echo wp_create_nonce('contact_nonce'); ?>" />
		</div>
	</form>
	<?php 
}

function handler_contact_form(){

    if ( !isset( $_POST['contact_nonce'] ) ) return false; // проверка
    if ( !wp_verify_nonce( $_POST['contact_nonce'], 'contact_nonce') ) return false; // проверка
	
	if(	!empty( $_POST['contact_name'] ) AND
		!empty( $_POST['contact_email'] ) AND
		!empty( $_POST['contact_phone'] ) AND
		!empty( $_POST['contact_message'] ) ){
			
			$user_email = get_option('CP_mail');
			
			$title_to_user = 'Сообщение со страницы контактов сайта'.strtoupper (get_option('blogname'));
			
			$message_to_user = "Отправитель: ".$_POST['contact_name'];
			$message_to_user .= "\r\n";
			$message_to_user .= "E-mail: ".$_POST['contact_email'];
			$message_to_user .= "\r\n";
			$message_to_user .= "Телефон: ".$_POST['contact_phone'];
			$message_to_user .= "\r\n";
			$message_to_user .= "Сообщение: ";
			$message_to_user .= "\r\n===\r\n";
			$message_to_user .= $_POST['contact_message'];
			$message_to_user .= "\r\n===\r\n";
			
			$mail_to_user = wp_mail($user_email, $title_to_user, $message_to_user);
			
			if ( wp_mail($user_email, $title_to_user, $message_to_user) ){
				$message = "Ваше сообщение отправлено";
			}
			else{
				$message = "Не удалось отправить сообщение";
			}
	}
	else{
		$message = "Заполните, пожалуйста, все поля формы";
	}
	echo $message;
}

add_action ( 'comment_post', 'cp_print_message_status_comment' );
function cp_print_message_status_comment() {
	if( !session_id() ) {
		session_start();
	}
	$_SESSION['cp_commented'] = 1;
}