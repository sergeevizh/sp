<?php
//Заголовки для новых объектов
add_filter( 'enter_title_here', 'title_text_input' );
function title_text_input($title){
global $post;
	 switch($post->post_type){
		case 'questions': $title = 'Вопрос...'; break;
		case 'services': $title = 'Название услуги...'; break;
		case 'metro': $title = 'Название станции метро...'; break;
	 }
	 return $title;
}