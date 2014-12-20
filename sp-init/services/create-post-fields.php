<?php
//Добавляем блок характеристик
function services_add_custom_box() {
    add_meta_box( 
				'services_fields_sectionid', 
				'Характеристики услуги',
				'services_callback_fields', 
				'services' 
				);
}
add_action('add_meta_boxes', 'services_add_custom_box');

//Выводим поля в блоке характеристик
function services_callback_fields() {
	global $post; 
	$my_post = $post;
	
	$return =  '
	<style>
		.extra_field_box{
		float:left;
		margin:0 10px;
		
		}
		.clear{
			clear:both;
			float:none;
		}
	</style>
	';
	
	
//Тип программы
	/*
	$return .=  '<div class="extra_field_box program_type">
				Тип программы
				<select name="CP_extra_fields_services[program_type]" />';

	$return .= '<option value="main" '. selected( get_post_meta($my_post->ID, 'program_type', true), 'main', false ) .' >Общая</option>';
	$return .= '<option value="complex" '. selected( get_post_meta($my_post->ID, 'program_type', true), 'complex', false ) .' >Комплексная</option>';		
	$return .= '</select>
				</div>';
	*/
//Продолжительность услуги
	$return .=  '<div class="extra_field_box duration_services">Продолжительность  ';
	$return .= ' <input name="CP_extra_fields_services[duration_services]" size="1" value="'.get_post_meta($my_post->ID, 'duration_services', true).'"> часа';
	$return .= '</div>';
//Цена услуги (рублей)
	$return .=  '<div class="extra_field_box services_prise">Цена ';
	$return .= ' <input name="CP_extra_fields_services[services_prise]" size="4" value="'.get_post_meta($my_post->ID, 'services_prise', true).'"> рублей';
	$return .= '</div>';
	$return .= '<div class="clear"></div>';
	$return .= '<input type="hidden" name="services_extra_fields_nonce" value="'. wp_create_nonce('nonce').'" />';	
	echo $return;
}



// включаем обновление полей при сохранении
add_action('save_post', 'services_extra_fields_update', 0);
/* Сохраняем данные, при сохранении поста */
function services_extra_fields_update( $post_ID ){ 
    if ( !isset( $_POST['services_extra_fields_nonce'] ) ) return false; // проверка
    if ( !wp_verify_nonce( $_POST['services_extra_fields_nonce'], 'nonce') ) return false; // проверка

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false; // выходим если это автосохранение
	
	if ( !current_user_can('edit_post', $post_ID ) ) return false; // выходим если юзер не имеет право редактировать запись

	if( !isset($_POST['CP_extra_fields_services']) ) return false;	// выходим если данных нет
	
	//Теперь, нужно сохранить/удалить данные
	$_POST['CP_extra_fields_services'] = array_map('trim', $_POST['CP_extra_fields_services']); // чистим все данные от пробелов по краям

	foreach( $_POST['CP_extra_fields_services'] as $key=>$value ){
		if( empty($value) ){
			delete_post_meta($post_ID , $key); // удаляем поле если значение пустое
			continue;
		}
		update_post_meta($post_ID , $key, $value); // add_post_meta() работает автоматически
	}
	return $post_ID ;
}