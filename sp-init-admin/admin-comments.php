<?php 
/*
* Админка управления контактами
*/
function register_cp_admin_comments_menu_page(){
	//if ( ( !current_user_can('level_10')) && is_admin()  ) :
		$count_com = get_comments(array(
							'post_id' => get_the_ID(),
							'status' => 0,
							'parent' => 0,
							'count' => true)
						);
		$count_com = ($count_com > 0)?'(<span style="color: red;font-weight: bold;font-size: 1.2em;">'.$count_com.'</span>)':'';
	
		add_menu_page( 
			'Управление отзывами', 
			'Отзывы '.$count_com , 
			'publish_posts', 
			'cp_admin_comments', 
			'handle_cp_admin_comments_menu_page', 
			'dashicons-format-chat',
			26
		);
	//endif;
}

function handle_cp_admin_comments_menu_page(){
?>
<style>
.clear{
	clear: both;
	float: none;
}

.widefat {
	border: none !important;
	-webkit-box-shadow: none !important;
	box-shadow: none !important;
}

td.comment.notapproved {
	 background: #F5EAED;
}

td.comment {
	border: 1px solid #e1e1e1;
	padding-bottom: 25px;
}

td.comment .commentdiv{
	overflow: hidden;
}

td.comment .answercomment{
	padding: 25px 0 0 25px;
}

td.comment .answercomment .head div{
	padding: 0px 0 10px 0;
}

td.comment .row-actions {
	visibility: visible;
}

td.comment .head div{
	float: left;
	padding-right: 15px;
	font-size: 15px;
	font-weight: bold;
	margin-top: 10px;
}

td.comment .head .fromcomment{
	padding:5px 0px;
	font-weight: normal;
	font-size: 13px;
	opacity: 0.6;
}

td.comment .head div.comment_date{
	float: right;
}
td.comment .textarea_comment_content textarea{
	/*min-height:80px;*/
	width:100%;
	color: #000;
}

td.comment .answercomment .textarea_comment_content textarea{
	 background: #EAF5EC;
}

td.comment .answercomment .textarea_comment_content textarea{
	 background: #B6D7A8 !important;
}

tr.razdel td.td_razdel{
	height:20px; 	
	background: #f1f1f1 !important;
} 

td.comment .head div.notapproved_mess {
/*position: absolute;
left: 500px;*/
background-color: #F4BEBE;
padding: 10px;
border-radius: 5px;
color: #fff;
margin-top: 0px;
}

.chat{
text-align: center;
}

.chat .more{
	padding: 5px 10px;
	border: 1px solid #0074a2;
	border-radius: 4px;
	margin: 0 5px;
}

.chat .more.current{
	border: 0px solid #0074a2;
	background-color:#ddd;
}
</style>
<div id="wpbody-content" aria-label="Основное содержимое" tabindex="0">
<div class="wrap">
<h2>Отзывы</h2>
<ul class="subsubsub">
		<?php 
			$count_com = get_comments(array(
								'post_id' => get_the_ID(),
								'status' => 0,
								'parent' => 0,
								'count' => true)
							);
			$count_com = ($count_com > 0)?'(<span style="color: red;font-weight: bold;font-size: 1.2em;">'.$count_com.'</span>)':'';
		
		?>
	<li class="allcomments">
		<a href="admin.php?page=cp_admin_comments" class="current">Все отзывы</a>
	|</li>
	<li class="unansweredcomments">
		<a href="admin.php?page=cp_admin_comments&unanswered=true">Ожидающие <?php echo $count_com; ?></a>
	</li>
</ul>

<table cellspacing="10" class="wp-list-table widefat fixed posts">
<!------<thead>
	<tr>
		<th 
		scope="col" 
		id="cust_thumb" 
		class="manage-column column-cust_thumb" 
		style="">
		<?php if($_GET['unanswered'] == 'true'): 
			$commentstatus = 0;
		?>
		Ожидающие ответа
		<?php else:
			$commentstatus = '';
		?>
		Все отзывы
		<?php endif; ?>
		</th>
	</tr>
</thead>------->
<tbody id="the-list">
	<?php
		$number	= 10;
		if($_GET['unanswered'] == 'true'){
			$max_page = ceil(
				get_comments(
					array('post_id' => get_the_ID(),'status' => 0,'parent' => 0,'count' => true))/$number
			);		
		}
		else{
			$max_page = ceil(
				get_comments(
					array('post_id' => get_the_ID(),'status' => 'approve','parent' => 0,'count' => true))/$number
			);		
		}

							
		$comment_page = is_numeric( $_GET['comment_page'])?$_GET['comment_page']:1;
		$offset = ($comment_page * $number) - $number;

		$args = array(
					'number' => $number
					,'offset' => $offset
					,'order' => 'DESC'
					,'parent' => 0
					,'status' => $commentstatus,
		);

		if( $comments = get_comments( $args ) ){
			foreach($comments as $comment){
			?>

	<tr	class="level-0 razdel"><td class="td_razdel"></td></tr>
	<tr 
	id="comment-<?php echo  $comment->comment_ID; ?>" 
	class="comment-<?php echo  $comment->comment_ID; ?> level-0">
		<td class="comment <?php echo ($comment->comment_approved == 1)?'':'notapproved'; ?>">
			<div class="commentdiv">	
				<div class="head">
					<div><?php echo $comment->comment_author; ?></div>
					<div><?php echo $comment->comment_author_email; ?></div>
					<div>IP: <?php echo $comment->comment_author_IP; ?></div>
					<?php echo ($comment->comment_approved == 1)?'':'
						<div class="notapproved_mess">Ожидает одобрения</div>
					'; ?>					
					
					
					<div class="comment_date"><?php echo get_comment_date("Y-m-d g:i:s", $comment->comment_ID ); ?></div>
					<div class="clear"></div>
					<div class="fromcomment">
					Отправлен со страницы: <b><?php echo get_the_title($comment->comment_post_ID) ?></b>
					</div>
				</div>
				<div class="clear"></div>
				<div class="textarea_comment_content">
					<textarea onkeydown="textAreaHeight(this)" disabled class="comment_content" ><?php echo  $comment->comment_content; ?></textarea>
				</div>
				
				<?php 
					$answerargs = array(
										'offset' => ''
										,'status' => 'approve'
										,'order' => 'ASC'
										,'parent' => $comment->comment_ID
					);

					if( $answers = get_comments( $answerargs ) ){
						foreach($answers as $answer){
								?>
								<div class="answercomment" id="answercomment-<?php echo  $answer->comment_ID; ?>" >
								<div class="head">
									<div><input type="hidden" value="<?php echo  $answer->comment_ID; ?>" class="id_answer">
										 <input type="hidden" value="<?php echo get_the_title($comment->comment_post_ID) ?>" class="answer_author">
										<input style="display:none;" checked type="radio" name="user_id-<?php echo  $comment->comment_ID; ?>" value="0" class="user_id"> 
										 
										Ответила <b><?php echo $answer->comment_author ?></b></div>
									<div class="comment_date"><?php echo get_comment_date("Y-m-d g:i:s", $answer->comment_ID ); ?></div>
									<div class="clear"></div>
								</div>
								
								<div class="textarea_comment_content">
									<textarea onkeydown="textAreaHeight(this)" disabled class="answer_content"><?php echo $answer->comment_content; ?></textarea>
								</div>				
								</div>

								<?php 
						}
					}
					else{
						?>
						<div style="display:none;" class="answercomment answercomment_new" id="answercomment-new" >
							<div class="head">
								<div><input type="hidden" value="0" class="id_answer">
									 <input type="hidden" value="<?php echo get_the_title($comment->comment_post_ID) ?>" class="answer_author">
								Ответ от 
								<label><input type="radio" name="user_id-<?php echo  $comment->comment_ID; ?>" checked value="1" class="user_id"> Администратора</label>
								<label><input type="radio" name="user_id-<?php echo  $comment->comment_ID; ?>" value="0" class="user_id"> Массажистки</label>								
								:</div>
								<div class="comment_date"></div>
								<div class="clear"></div>
							</div>
							<div class="textarea_comment_content">
								<textarea disabled class="answer_content" ></textarea>
							</div>				
						</div>
						<?php 					
					}
				?>
				<div class="clear"></div>
			
				<div class="row-actions navi_links">
					<span class="approve" style="<?php echo ($comment->comment_approved == 1)?'':'display:inline-block;'; ?>"> <a comment_id="<?php echo  $comment->comment_ID; ?>" href="#"><b>Одобрить</b></a> | </span>
					
					<span class="reply"><a comment_id="<?php echo  $comment->comment_ID; ?>" href="#">Ответить/Редактировать</a> | </span>
					
					<span class="trash"><a comment_id="<?php echo  $comment->comment_ID; ?>" href="#">Удалить</a></span>
					
				</div>
				<div style="display:none;" class="row-actions loader">
					<img src="<?php echo CP_COSTUM_ADMIN_FOR_SP_DIR_URL; ?>/loader.gif" class="loadingimg">
				</div>				
				<div style="display:none;" class="row-actions save_button">
					<button class="btn save" answer_post_ID="<?php echo  $comment->comment_post_ID; ?>" comment_id="<?php echo  $comment->comment_ID; ?>">Сохранить</button>
					<button class="btn reset" comment_id="<?php echo  $comment->comment_ID; ?>">Отмена</button>
				</div>
			</div>
		</td>

			<?php	
			}
		}
		?>		
	</tr>
</tbody>
</table>
<br class="clear">
</div>
<div class="clear"></div>
</div>

<div class="chat">
	<div class="scroll-chat">
	</div>
	<?php if( $max_page > 1): ?>
		<?php if( $comment_page > 1): ?>
			<a href="<?php echo add_query_arg(array('comment_page' => '1'));?>" id="more-comments" class="more">К первым</a>
		
		<?php endif; ?>
		<?php if( ($comment_page-1) > 1): ?>
			<a href="<?php echo add_query_arg(array('comment_page' => ($comment_page-1)));?>"  class="more">К предыдщим</a>
		<?php endif; ?>		
		
			<span class="more current"><?php echo $comment_page; ?></span>
		
		<?php if( ($comment_page+1)< $max_page): ?>
			<a href="<?php echo add_query_arg(array('comment_page' => ($comment_page+1)));?>"  class="more">К следующшим</a>
		<?php endif; ?>		
		
		<?php if( $comment_page < $max_page): ?>
			<a href="<?php echo add_query_arg(array('comment_page' => $max_page));?>"  class="more">К последним</a>
		<?php endif; ?>		
	<?php endif; ?>
</div>	
<?php 
}
add_action( 'admin_menu', 'register_cp_admin_comments_menu_page' );