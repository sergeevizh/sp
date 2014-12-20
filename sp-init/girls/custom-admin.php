<?php
//Заголовки для новых объектов
add_filter( 'enter_title_here', 'girls_title_text_input' );
function girls_title_text_input($title){
	global $post;
	global $MASSAGE_PARLOR_GIRLS;
	if($post->post_type == 'girls'){
		$title = 'Введите имя '.$MASSAGE_PARLOR_GIRLS['let_enter_name'].'...';
	}
	return $title;
}

//Добавляем две колонки для объекто "girls"
add_filter('manage_edit-girls_columns', 'add_thumb_girls_column', 10, 1);  
function add_thumb_girls_column( $columns ){   
	$out = array(); //print_r($columns); 
    $out['cust_thumb'] = 'Фото';
	//$out['schedule'] = 'График';
	$out['status'] = 'Статус';	
    foreach((array)$columns as $col=>$name){  
        $out[$col] = $name;  
    }   
    return $out;     
}

//Заполняем добавленные нами колонки для объекта "girls"
add_filter('manage_girls_posts_custom_column', 'fill_thumb_girls_column', 5, 2);
function fill_thumb_girls_column($column_name, $post_id) { 

    if( $column_name == 'cust_thumb' ){     
		if(get_the_post_thumbnail($post_id,'thumbnail')) 
		echo '<a href="'.site_url().'/wp-admin/post.php?post='.$post_id.'&action=edit">'.get_the_post_thumbnail($post_id,'thumbnail', array('class' => "no-lazy attachment-$size")).'</a>' ; 
	}
	
	if( $column_name == 'status' ){  ?>
		<p>
			<input type="hidden" name="see[<?php echo $post_id; ?>]" value="0">
			<input type="checkbox"<?php checked( 1, get_post_meta( $post_id, 'see-girl', 1 ) ) ?> name="see[<?php echo $post_id; ?>]" value="1"> Отображать анкету
		</p>
		<p>
			<input type="hidden" name="new[<?php echo $post_id; ?>]" value="0">
			<input type="checkbox" 
					<?php checked(get_post_meta($post_id, 'new', 1),1); ?> 
					value="1" 
					name="new[<?php echo $post_id; ?>]"
				> NEW 
		</p>
	<?php 
	}
	if( $column_name == 'schedule' ){  ?>
		<p><?php $status = get_post_meta($post_id, 'status-girl', 1); ?>
		Работает
			<table class="statustable">
				<tr>
					<td>Пн</td>
					<td>Вт</td>
					<td>Чтв</td>
					<td>Срд</td>
					<td>Птн</td>
					<td>Сбт</td>
					<td>Вс</td>
				</tr>
				<tr>
					<td>
						<input type="hidden" name="status[<?php echo $post_id; ?>][Mon]" value="0">
						<input type="checkbox"<?php checked( 1, $status['Mon'] ) ?> name="status[<?php echo $post_id; ?>][Mon]" value="1">
					</td>
					<td>
						<input type="hidden" name="status[<?php echo $post_id; ?>][Tue]" value="0">
						<input type="checkbox"<?php checked( 1, $status['Tue'] ) ?> name="status[<?php echo $post_id; ?>][Tue]" value="1">
					</td>
					<td>
						<input type="hidden" name="status[<?php echo $post_id; ?>][Wed]" value="0">
						<input type="checkbox"<?php checked( 1, $status['Wed'] ) ?> name="status[<?php echo $post_id; ?>][Wed]" value="1">
					</td>
					<td>
						<input type="hidden" name="status[<?php echo $post_id; ?>][Thu]" value="0">
						<input type="checkbox"<?php checked( 1, $status['Thu'] ) ?> name="status[<?php echo $post_id; ?>][Thu]" value="1">
					</td>
					<td>
						<input type="hidden" name="status[<?php echo $post_id; ?>][Fri]" value="0">
						<input type="checkbox"<?php checked( 1, $status['Fri'] ) ?> name="status[<?php echo $post_id; ?>][Fri]" value="1">
					</td>
					<td>
						<input type="hidden" name="status[<?php echo $post_id; ?>][Sat]" value="0">
						<input type="checkbox"<?php checked( 1, $status['Sat'] ) ?> name="status[<?php echo $post_id; ?>][Sat]" value="1">
					</td>
					<td>
						<input type="hidden" name="status[<?php echo $post_id; ?>][Sun]" value="0">
						<input type="checkbox"<?php checked( 1, $status['Sun'] ) ?> name="status[<?php echo $post_id; ?>][Sun]" value="1">
					</td>				
				</tr>
			</table>
		</p>
	<?php 
	}
}


//Сохраняем данные переданные из чекбоксов в колонках
//Данные сохраняются при нажатии на кнопку "применить" к действиям..
//Но лучше использовать аякс в этом моменте.. но и такой вариант пока сойдет.
add_action('init', 'edit_girls_data_admin_list_activate');
function edit_girls_data_admin_list_activate ( ) {
	if ( is_admin() AND (isset( $_GET['see'] ) OR isset( $_GET['new'] )) ) {

		if( is_array($_GET['new']) ){
			foreach($_GET['new'] as $post_id=>$val){
				update_post_meta($post_id, 'new', $val);
			}
		}
		if( is_array($_GET['see'])){
			foreach($_GET['see'] as $post_id=>$val){
				update_post_meta($post_id, 'see-girl', $val);
			}	
		}
 		if( is_array($_GET['status'])){
			foreach($_GET['status'] as $post_id=>$val){
				update_post_meta($post_id, 'status-girl', $val);
			}	
		} 
	}
}

//Изменяем запрос для сортировки по имени девушки
add_action('pre_get_posts', 'thorny_sortable_request_by_title');
function thorny_sortable_request_by_title( $query ){
	if ( is_admin() && $query->is_main_query() && $_GET['post_type'] == 'girls') {
		$query->set('orderby', 'title');
		$query->set('order', 'ASC');
	}
}

//Стили для новых столбцов
add_action('admin_head', 'thorny_add_status_column_css');
function thorny_add_status_column_css(){
	echo '<style type="text/css">
		.column-status{
			width:212px;
		}
		.column-status .statustable tr td{
			padding: 3px;
		}		
		.column-schedule{
			width:212px;
		}
		.column-schedule .statustable tr td{
			padding: 3px;
		}
	</style>';
}
/*************************************************************************/