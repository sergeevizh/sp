<?php
// добавляем колонки для объекта "girls"
function thorny_add_girls_meta_columns( $columns ){

	if ( ( !current_user_can('level_10')) && is_admin()  ) :
		$my_columns['cust_thumb_by_admin'] = 'Фото';
		$my_columns['title_by_admin'] = 'Имя';
		$my_columns['schedule'] = 'График';
		$my_columns['status'] = 'Статус';
		$my_columns['save_button'] = 'Сохранить';
		return $my_columns;	
	else:
		return $columns;
	endif;

}
add_filter('manage_edit-girls_columns', 'thorny_add_girls_meta_columns', 100);

// заполняем колонки данными для объекта "girls"
add_filter('manage_girls_posts_custom_column', 'thorny_fill_girls_meta_column', 100, 2); 
function thorny_fill_girls_meta_column($column_name, $post_id) {
	if ( ( !current_user_can('level_10')) && is_admin()  ) :
		if($column_name == 'save_button'){
			echo '<input 
						post_id="'.$post_id.'"
						class="btn btn_ajax_submit" 
						type="button" 
						value="OK">';
			echo '<img style="display:none;"
					src="'. CP_COSTUM_ADMIN_FOR_SP_DIR_URL.'/loading.gif" class="loadingimg">';					
		}
		if( $column_name == 'title_by_admin' ){     
			echo '<strong>'.get_the_title($post_id).'</strong>';
		}		
		if( $column_name == 'cust_thumb_by_admin' ){     
			if(get_the_post_thumbnail($post_id,'thumbnail')) 
			echo get_the_post_thumbnail($post_id,'thumbnail', array('class' => "no-lazy attachment-$size")); 
		}	
	endif;
}

add_action( 'wp_ajax_thorny_ajax_save_girls_data', 'thorny_ajax_save_girls_data' );
function thorny_ajax_save_girls_data(){
	if( !wp_verify_nonce( $_POST['nonce'], 'cp__nonce' ) ) {
        wp_send_json_error();
    }
	
	foreach($_POST as $var_name=>$var){
		if($var_name != 'nonce' AND $var_name != 'action'){
			foreach($var as $user_id=>$value){
				if($var_name == 'status') $var_name = 'status-girl';
				if($var_name == 'see') $var_name = 'see-girl';
				
				if($var_name == 'girl-of-day'){
					$girls = get_posts( array(
						'post_type'  => 'girls',
						'meta_key'   => 'girl-of-day',
						'meta_value' => 1,
						'exclude'    => $value
					) );
					 
					foreach( $girls as $girl ) {                       
						delete_post_meta( $girl->ID , 'girl-of-day' ); 
					}
				}				
				
				update_post_meta( $user_id,  $var_name,  $value);
			}
		}
	}
	if ( function_exists( 'w3tc_pgcache_flush' ) ) {
		w3tc_pgcache_flush();
	}
	//echo $error;
	exit;
}

add_action( 'wp_ajax_thorny_ajax_comment_trash', 'thorny_ajax_comment_trash' );
function thorny_ajax_comment_trash(){
	if( !wp_verify_nonce( $_POST['nonce'], 'cp__nonce' ) ) {
        wp_send_json_error();
    }
	
	if( !is_numeric($_POST['comment_id']) ) return false;
	
	$rez = wp_delete_comment ($_POST['comment_id']);
	$rez = ($rez)? 1 : 0 ;
	echo $rez;
	
	if ( function_exists( 'w3tc_pgcache_flush' ) ) {
		w3tc_pgcache_flush();
	}
	exit;
}
add_action( 'wp_ajax_thorny_ajax_comment_approved', 'thorny_ajax_comment_approved' );
function thorny_ajax_comment_approved(){
	if( !wp_verify_nonce( $_POST['nonce'], 'cp__nonce' ) ) {
        wp_send_json_error();
    }
	
	if( !is_numeric($_POST['comment_id']) ) return false;
	$rez = wp_set_comment_status($_POST['comment_id'], '1');
	$rez = ($rez)? 1 : 0 ;
	
	echo $rez;
	
	if ( function_exists( 'w3tc_pgcache_flush' ) ) {
		w3tc_pgcache_flush();
	}
	exit;
}


add_action( 'wp_ajax_thorny_ajax_comment_add', 'thorny_ajax_comment_add' );
function thorny_ajax_comment_add(){
	if( !wp_verify_nonce( $_POST['nonce'], 'cp__nonce' ) ) {
        wp_send_json_error();
    }
	
	if( !is_numeric($_POST['comment_id']) OR 
		!is_numeric($_POST['id_answer']) OR 
		!is_numeric($_POST['answer_post_ID']) OR 
		!is_numeric($_POST['answer_parent']) OR 
		!is_numeric($_POST['user_ID']) OR 
		empty($_POST['comment_content']) OR
		empty($_POST['answer_content']) ) return false;
	
	
	$commentarr['comment_ID'] = $_POST['comment_id'];
	$commentarr['comment_content'] = $_POST['comment_content'];
	$rez = wp_update_comment( $commentarr );
	$rez = wp_set_comment_status($_POST['comment_id'], '1');
	
	if($_POST['id_answer'] == 0){
		// создаем массив данных нового комментария
		$commentdata = array(
			 'comment_post_ID' => $_POST['answer_post_ID']
			,'comment_author' => $_POST['answer_author']
			,'comment_content' => $_POST['answer_content']
			,'comment_parent' => $_POST['answer_parent']
			,'comment_approved' => '1'
			,'user_ID' => $_POST['user_ID']
		);
		// добавляем данные в Базу Данных
		$rez = wp_new_comment( $commentdata );
		wp_set_comment_status($rez, '1');
		//echo ( $rez )? 1 : 0 ;	
	}
	else{
		$answerarr['comment_ID'] =      $_POST['id_answer'];
		$answerarr['comment_author'] =  $_POST['answer_author'];
		$answerarr['user_id'] =         $_POST['user_ID'];
		$answerarr['comment_content'] = $_POST['answer_content'];
		$rez = wp_update_comment( $answerarr );	
		//echo ( $rez )? 1 : 0 ;	
	}
	
	if ( function_exists( 'w3tc_pgcache_flush' ) ) {
		w3tc_pgcache_flush();
	}
	exit;
}

function thorny_add_admin_scripts(){
	wp_localize_script( 'jquery', 'cp_ajax_data', 
		array(
		   'url' => admin_url('admin-ajax.php'),
		   'nonce' => wp_create_nonce("cp__nonce")
		)
	);
	
	wp_register_script('thorny_ajax_girls_script', CP_COSTUM_ADMIN_FOR_SP_DIR_URL . 'js/ajax.js', array('jquery'));
	wp_enqueue_script('thorny_ajax_girls_script');
}
add_action('admin_enqueue_scripts', 'thorny_add_admin_scripts');