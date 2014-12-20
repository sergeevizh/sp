<?php
//Добавляем блок характеристик
function girls_add_custom_box() {
    add_meta_box( 
				'girls_fields_sectionid', 
				'Характеристики девушки',
				'girls_callback_fields', 
				'girls' 
				);
}
add_action('add_meta_boxes', 'girls_add_custom_box');

//Выводим поля в блоке характеристик
function girls_callback_fields() {
	global $post;  ?>

	<label>Возраст</label>
	<select name="CP_extra_fields_girls[age]">
		<?php for($a=18;$a<=40;$a++){
			echo '<option '.selected(get_post_meta($post->ID, 'age', 1),$a,false).' value="'.$a.'">'.$a.'</a>';
		} ?>
	</select>
	<label>Рост</label>
	<select name="CP_extra_fields_girls[height]">
		<?php for($a=150;$a<=200;$a++){
			echo '<option '.selected(get_post_meta($post->ID, 'height', 1),$a,false).' value="'.$a.'">'.$a.'</a>';
		} ?>
	</select>
	<label>Вес</label>
	<select name="CP_extra_fields_girls[weight]">
		<?php for($a=40;$a<=80;$a++){
			echo '<option '.selected(get_post_meta($post->ID, 'weight', 1),$a,false).' value="'.$a.'">'.$a.'</a>';
		} ?>
	</select>
	<label>Грудь</label>
	<select name="CP_extra_fields_girls[bust]">
		<?php for($a=1;$a<=5;$a++){
			echo '<option '.selected(get_post_meta($post->ID, 'bust', 1),$a,false).' value="'.$a.'">'.$a.'</a>';
		} ?>
	</select>
	<br><br>
	<label>New?</label>
	<input type="hidden" name="CP_extra_fields_girls[new]" value="0">
	<input type="checkbox" <?php checked(get_post_meta($post->ID, 'new', 1), '1') ?> name="CP_extra_fields_girls[new]" value="1"> |
	
	<label>Анкета активна?</label>
	<input type="hidden" name="CP_extra_fields_girls[see-girl]" value="0">
	<input type="checkbox" <?php checked(get_post_meta($post->ID, 'see-girl', 1), '1') ?> name="CP_extra_fields_girls[see-girl]" value="1">	
	<br><br>
	
	<label>Любимая программа </label>
	<select name="CP_extra_fields_girls[services]">
							<?php 
							$services = get_post_meta($post->ID, 'services', 1);
							$array_query = array(
												'post_type' => 'services',
												'posts_per_page' => -1 
												);

							$my_query = new WP_Query( $array_query );
							if ( $my_query->have_posts() ):
								while ( $my_query->have_posts() ):
									$my_query->the_post();
									echo '<option '.selected($services, $post->ID, false).' value="'.$post->ID.'">'.get_the_title().'</a>';
									endwhile;
								endif;
								wp_reset_postdata();
							?>
	</select>	

	<input type="hidden" name="girls_extra_fields_nonce" value="<?php echo wp_create_nonce('nonce'); ?>" />	
<?php 
}



// включаем обновление полей при сохранении
add_action('save_post', 'girls_extra_fields_update', 0);
/* Сохраняем данные, при сохранении поста */
function girls_extra_fields_update( $post_ID ){

    if ( !isset( $_POST['girls_extra_fields_nonce'] ) ) return false; // проверка
	
    if ( !wp_verify_nonce( $_POST['girls_extra_fields_nonce'], 'nonce') ) return false; // проверка

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false; // выходим если это автосохранение
	
	if ( !current_user_can('edit_post', $post_ID ) ) return false; // выходим если юзер не имеет право редактировать запись

	if( !isset($_POST['CP_extra_fields_girls']) ) return false;	// выходим если данных нет
	
	//Теперь, нужно сохранить/удалить данные
	$_POST['CP_extra_fields_girls'] = array_map('trim', $_POST['CP_extra_fields_girls']); // чистим все данные от пробелов по краям
	
	foreach( $_POST['CP_extra_fields_girls'] as $key=>$value ){
		if( empty($value) ){
			delete_post_meta($post_ID , $key); // удаляем поле если значение пустое
			continue;
		}
		update_post_meta($post_ID , $key, $value); // add_post_meta() работает автоматически
	}
	return $post_ID ;
}


//******************* ПЛАГИН Attachments ДОЛЖЕН БЫТЬ ВКЛЮЧЕН *************************//
add_action( 'attachments_register', 'attachments_girls' );
function attachments_girls( $attachments ){
	$args = array(
		'label' => 'Фото девушки',
		'post_type' => array( 'girls' ),
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
	 
	$attachments->register( 'attachments_girls', $args );
}

add_action( 'attachments_register', 'attachments_girls_video' );
function attachments_girls_video( $attachments ){
	$args = array(
		'label' => 'Видео девушки',
		'post_type' => array( 'girls' ),
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
	 
	$attachments->register( 'attachments_girls_video', $args );
}