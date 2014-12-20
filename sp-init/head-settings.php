<?php 
/*
* Админка управления контактами
*/

//Все опции для управления контактами
$admin_header_options = array (
	array( "id" => "CP_address" ),
	array( "id" => "CP_metro" ),
	array( "id" => "CP_phone1" ),	
	array( "id" => "CP_phone2" ),
	array( "id" => "CP_mail" ),	
);

//Добавляем опции и их обработку при сохранении, регистрируем страницу опций
function admin_header_options() {
	global $admin_header_options;

	if (isset($_REQUEST['action']) AND 'admin_save'== $_REQUEST['action'] ) {
		foreach ($admin_header_options as $value) {
		
			if( isset( $_REQUEST[ $value['id'] ] ) ){
				$value_one = $_REQUEST[ $value['id'] ];
				if(is_array($value_one))
					$value_one = serialize($value_one);
				
				update_option( $value['id'], $value_one); 
			} 
		}

		if(stristr($_SERVER['REQUEST_URI'],'&saved=true')) {
			$location = $_SERVER['REQUEST_URI']; 
		} 
		else {
			$location = $_SERVER['REQUEST_URI'] . "&saved=true"; 
		}
		
		header("Location: $location");
		die;
	}

	add_options_page('Ваш адрес', 'Ваш адрес', 'manage_options', 'header-settings', 'CP_admin_header');
	//add_submenu_page( 'themes.php', 'Ваш адрес', 'Ваш адрес', 'header-settings', 'header-settings', 'CP_admin_header');
	
}

function CP_admin_header() {
	global $admin_header_options;

	if (isset($_REQUEST['saved']) ) 
		echo '<div  class="wrap" style=""><h2 style="color:green;">Настройки сохранены.</h2></div>';
	 
	if (isset($_REQUEST['reset']) ) 
		echo '<div  class="wrap" style=""><h2 style="color:green;">Настройки сброшены.</h2></div>';
	?>
	<form method="post">
	<style>
	.options-table, .save_button_box{
		margin: auto;
		width: inherit;		
	}
	
	.save_button_box{
		margin-bottom: 25px;		
	}
	
	.save_button {
	width: 200px;
	}
	
	.inputtext{
	width: 350px;
	border-radius: 6px;
	}
	</style>
	<table class="options-table form-table">
		<tr>
		<td>Ваш адрес</td><td><input class="inputtext"
									name="CP_address"
									type="text"
									value="<?php echo get_option('CP_address'); ?>" /></td>		
		</tr>
		<tr>
			<td>Станция метро</td><td><input class="inputtext"
										name="CP_metro"
										type="text"
										value="<?php echo get_option('CP_metro'); ?>" /></td>		
		</tr>
		<tr>
			<td>Ваш телефон 1</td><td><input class="inputtext"
										name="CP_phone1"
										type="text"
										value="<?php echo get_option('CP_phone1'); ?>" /></td>		
		</tr>
		<tr>
			<td>Ваш телефон 2</td><td><input class="inputtext"
										name="CP_phone2"
										type="text"
										value="<?php echo get_option('CP_phone2'); ?>" /></td>		
		</tr>
		<tr>
			<td>E mail</td><td><input class="inputtext"
										name="CP_mail"
										type="text"
										value="<?php echo get_option('CP_mail'); ?>" /></td>		
		</tr>		
		<tr>
			<td></td>
			<td>
				<input name="admin_save" type="submit" value="Сохранить изменения" class="save_button" />
				<input type="hidden" name="action" value="admin_save" />
			</td>		
		</tr>		
		
		<tr>
	</table>
	</form>
<?php }

	add_action('admin_menu', 'admin_header_options');