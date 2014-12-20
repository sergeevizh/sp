<?php
function CP_RegisterTaxonomy(){

	$slag = 'program_type';
	$name = 'Типы программы';
	$singularName = 'Тип программы';
	$postsTypes = array('services');
	$NOTHierarchical = true;
	
	$capabilities = array(
							'manage_terms' => 'edit_posts',
							'edit_terms' => 'edit_posts',
							'delete_terms' => 'manage_categories',
							'assign_terms' => 'edit_posts'			
						);
	
	$labels = array(
		'name' => $name,
		'singular_name' => $singularName,
		'search_items' =>  'Поиск',
		'all_items' => 'Все типы',
		'parent_item' => $singularName,
		'parent_item_colon' => $singularName.':',
		'edit_item' => 'Редактировать',
		'update_item' => 'Обновить ',
		'add_new_item' => 'Добавить ',
		'new_item_name' => 'Создать новый ',
		'menu_name' => $name,
	);

	register_taxonomy(
		$slag,
		$postsTypes, //Типы записей для которых таксономия действует
		array(
			'hierarchical' => $NOTHierarchical,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => $slag ),
			'show_tagcloud' =>  false,
			'capabilities'  => $capabilities
		)
    ); 
	//-----------------------------------------------------// 		
}	
add_action('init', 'CP_RegisterTaxonomy');