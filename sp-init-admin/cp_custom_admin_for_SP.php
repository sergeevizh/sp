<?php
/*
Plugin Name: Costum Admin Panel by CasePress
Description: 
Version: 0.1
Author: CasePress
Author URI: http://casepress.org
*/

//Определяем константу и помещаем в нее путь до папки с плагином. 
define ("CP_COSTUM_ADMIN_FOR_SP_DIR_URL", plugin_dir_url(__FILE__));
require_once('cp_ajax_save_data.php');
require_once('admin-comments.php');



//Редирект при входе на страницы на которые НЕТ доступа
function thorny_users_redirect(){
	if(is_user_logged_in()){
		global $pagenow;
		$acsess_array = array(
						'admin-ajax.php', 
						'edit.php',
						'admin.php',
						);
		if ( ( !current_user_can('level_10')) && is_admin()  ) :
			if( !in_array($pagenow, $acsess_array) ){
				wp_redirect( site_url().'/wp-admin/edit.php?post_type=girls', 301);
				die();
			}
			elseif($pagenow == 'edit.php'){
				if( $_GET['post_type'] != 'girls'){
					wp_redirect( site_url().'/wp-admin/edit.php?post_type=girls', 301);
					die();				
				}
			}
			elseif($pagenow == 'admin.php'){
				if( $_GET['page'] != 'cp_admin_comments'){
					wp_redirect( site_url().'/wp-admin/edit.php?post_type=girls', 301);
					die();				
				}				
			}
		endif;	
	}
}
//При инициализации админки, проверяем права пользователя
add_action('admin_init','thorny_users_redirect');

//Удаляем пункты меню 
function thorny_remove_menus(){
	if ( ( !current_user_can('level_10')) && is_admin()  ) :
		remove_menu_page('index.php'); // Консоль
		remove_menu_page('edit-comments.php'); // Комменты
		remove_menu_page('upload.php'); // Медиафайлы
		remove_menu_page('link-manager.php'); // Ссылки
		remove_menu_page('plugins.php'); // Плагины
		remove_menu_page('themes.php'); // Внешний вид
		remove_menu_page('users.php'); // Пользователи
		remove_menu_page('tools.php'); // Инструменты
		remove_menu_page('edit.php'); // Записи
		
		remove_menu_page('edit.php?post_type=page');
		remove_menu_page('edit.php?post_type=questions');
		remove_menu_page('edit.php?post_type=services');
		remove_menu_page('edit.php?post_type=metro');
		
		remove_menu_page('admin.php?page=wpseo_dashboard');
		remove_submenu_page( 'wpseo_dashboard', 'wpseo_bulk-editor' );
		
		remove_menu_page('profile.php'); // Профиль
		remove_menu_page('options-general.php'); // Параметры
		
		//'normal', 'advanced', 'side'
		remove_meta_box( 'commentstatusdiv' , 'post' , 'normal' ); 
		remove_meta_box( 'commentsdiv' , 'post' , 'normal' );
		remove_meta_box( 'authordiv' , 'post' , 'normal' );
		remove_meta_box( 'postexcerpt' , 'post' , 'normal' );
		remove_meta_box( 'trackbacksdiv' , 'post' , 'normal' );
		remove_meta_box( 'revisionsdiv' , 'post' , 'normal' );
		remove_meta_box( 'postcustom' , 'post' , 'normal' );
		remove_meta_box( 'slugdiv' , 'post' , 'normal' );
		remove_meta_box( 'formatdiv' , 'post' , 'side' );
		
	endif;
}
//инициализация функции удаления пунктов меню
add_action( 'admin_menu', 'thorny_remove_menus' );

//Удаляем со страницы кнопки добавления контента
function thorny_remove_add_posts_top_menu(){
	if ( ( !current_user_can('level_10')) && is_admin()  ) :
	
		if($_GET['see'] == 'noactive'){
			$current_girls = '$(".wrap ul.subsubsub li.publish a")';
		}
		elseif($_GET['post_type'] == 'girls'){
			$current_girls = '$(".wrap ul.subsubsub li.all a")';	
		}
		elseif($_GET['unanswered'] == 'true'){
			$current_girls = '$(".wrap ul.subsubsub li.unansweredcomments a")';
		}
		elseif($_GET['page'] == 'cp_admin_comments'){
			$current_girls = '$(".wrap ul.subsubsub li.allcomments a")';	
		}		
	
		echo '
		<script type="text/javascript">
			/* dell admin-bar-new-content */
			var $ = jQuery.noConflict();
			$(document).ready(function() {
				$("#wp-admin-bar-new-content").empty();
				
				$(".wrap h2 a").detach();
				$("div.error").detach();
				$("div.settings-error").detach();
				$("#wp-admin-bar-comments").detach();
				
				$(".menupop").detach();
				$("#wpbody-content .update-nag").detach();
				$("#edge-mode").detach();
				
				$("#wpadminbar").empty().html(\'<ul style="float: right;padding: 0 25px;"><li><a href="'. wp_logout_url().'">Выйти</a></li></ul>\');
				
				$("#adminmenu li ul").detach();
				$(".tablenav").detach();
				$(".wrap ul.subsubsub li.trash").detach();
				
				var count_span = $(".wrap ul.subsubsub li.all .current .count").html();
				$(".wrap ul.subsubsub li.all a").empty().html("Активные анкеты " + count_span);

				$(".wrap ul.subsubsub li.publish").empty().html("<a href=\"edit.php?see=noactive&amp;post_type=girls\">Неактивные анкеты</a>");
				
				$(".wrap ul.subsubsub li a").removeClass("current");
				'.$current_girls.'.addClass("current");	
			});
		</script>
		';
	endif;
}
//инициализация функции JS для удаления из верхней панели кнопок добавления контента
add_action( 'admin_head', 'thorny_remove_add_posts_top_menu');


// изменяем запрос для сортировки колонки 
add_filter('pre_get_posts', 'thorny_new_orders_request');
function thorny_new_orders_request( $query ){
	if ( ( !current_user_can('level_10')) && is_admin()  ) :
		$query->set('posts_per_page', -1);
		
		if($_GET['see']=='noactive'){
			$query->set('meta_query', array(
										   array(
											   'key' => 'see-girl',
											   'value' => 0
										   )
									)
			);
		}
		else{
			$query->set('meta_query', array(
										   array(
											   'key' => 'see-girl',
											   'value' => 1
										   )
									)
			);
		}
	endif;
}

add_filter('show_admin_bar', '__return_false');