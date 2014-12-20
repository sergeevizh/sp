<?php
//Добавляем блок характеристик
function metro_add_custom_box() {
    add_meta_box( 
				'services_fields_sectionid', 
				'Данные маршрутов',
				'metro_callback_fields', 
				'metro' 
				);
}
add_action('add_meta_boxes', 'metro_add_custom_box');

//Выводим поля в блоке характеристик
function metro_callback_fields() {
	global $post; 
	$return =  '';
	$script = get_post_meta($post->ID, 'script-metro', 1);
	if($script)
	$return .=  '<div style="width:450px;margin:0 auto;"><script type="text/javascript" charset="utf-8" src="//api-maps.yandex.ru/services/constructor/1.0/js/?sid='.$script.'&width=450&height=450"></script></div>';
	
	$return .=  '
	<p>Скрипт карты<br />
	<label><input type="text" name="CP_extra_fields_metro[script-metro]" value="'. $script .'" style="width:50%" /></p>';
	
	$return .= '<p><a target="_blank" href="http://api.yandex.ru/maps/tools/constructor/">Страница формирования Яндекс.Карты</a></p>';

	$return .= '<input type="hidden" name="metro_extra_fields_nonce" value="'. wp_create_nonce('nonce').'" />';	
	
	echo $return;
}



// включаем обновление полей при сохранении
add_action('save_post', 'metro_extra_fields_update', 0);
/* Сохраняем данные, при сохранении поста */
function metro_extra_fields_update( $post_ID ){
    if ( !isset( $_POST['metro_extra_fields_nonce'] ) ) return false; // проверка
    if ( !wp_verify_nonce( $_POST['metro_extra_fields_nonce'], 'nonce') ) return false; // проверка

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false; // выходим если это автосохранение
	
	if ( !current_user_can('edit_post', $post_ID ) ) return false; // выходим если юзер не имеет право редактировать запись

	if( !isset($_POST['CP_extra_fields_metro']) ) return false;	// выходим если данных нет
	
	//Теперь, нужно сохранить/удалить данные
	$_POST['CP_extra_fields_metro'] = array_map('trim', $_POST['CP_extra_fields_metro']); // чистим все данные от пробелов по краям
	foreach((array) $_POST['CP_extra_fields_metro'] as $key=>$value ){	
		if( empty($value) ){
			delete_post_meta($post_ID, $key);
		}else{
			if(preg_match_all("/(?<=sid\=)[A-z0-9.:\%\-\_\?\"\=\/\>\<\s]*(?=\&)/i", $value, $id)) $value = $id[0][0];			
			update_post_meta($post_ID, $key, $value);
		}		
	}	
	return $post_ID ;
}