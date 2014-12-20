( function( $ ) {
	$(window).load(function() {
		var str_data = '';
		var get = location.search; //Разгребаем get запрос
		var tmp = new Array();
		var tmp2 = new Array();
		var param = new Array();
		if(get != '') {
			tmp = (get.substr(1)).split('&');
			for(var i=0; i < tmp.length; i++) {
				tmp2 = tmp[i].split('=');
				param[tmp2[0]] = tmp2[1];       //param будет содержать пары ключ->значение
			}
		}
		
		$(".btn_ajax_submit").click(function(){
			var post_id = $(this).attr('post_id');
			var var_name_arr = [];
			var see = 100;
			$("table.wp-list-table.posts #post-" + post_id + " input[type=checkbox]").each(
				function(i,elem) {
					var_name_arr = $(this).attr('name').split('[');
					if($(this).attr('checked') == 'checked'){
						//alert( $(this).attr('name') + " = " +$(this).val() );
						str_data = str_data + $(this).attr('name') + '=' + $(this).val() + '&';
						
						if( var_name_arr[0] == 'see'){
							see = $(this).val();
						}
					}
					else{
						//alert( $(this).attr('name') + " = 0");
						str_data = str_data + '&' +  $(this).attr('name') + '=0&';
						if( var_name_arr[0] == 'see'){
							see = '0';
						}
					}
				}
			);
			
			$("table.wp-list-table.posts #post-" + post_id + " input[type=radio]").each(
				function(i,elem) {
					if($(this).attr('checked') == 'checked'){
						//alert( str_data + $(this).attr('name') + '['+ $(this).val() +']=1&');
						str_data = str_data + $(this).attr('name') + '['+ $(this).val() +']=1&';
					}
				}
			);
			/*
			
			var select_name = '';
			$("table.wp-list-table.posts #post-" + post_id + " select").each(
				function(i,elem_select) {
					select_name = $(this).attr('name');
					
					$(this).children().each(
						function(a,elem_option) {
							if($(this).attr('selected') == 'selected'){
								//alert(select_name  + " = " +$(this).val() );
								return false;
							}
						}
					);
				}
			);			
			*/
			$("table.wp-list-table.posts #post-" + post_id + " td.column-status").css({opacity : 0.4});
			
			$("table.wp-list-table.posts #post-" + post_id + " td.save_button .loadingimg").css({'display' : 'block'});
			
			$("table.wp-list-table.posts #post-" + post_id + " td.save_button .btn_ajax_submit").css({'display' : 'none'});			
			
			$.ajax({
				type: "POST",
				url: cp_ajax_data.url,
				data: str_data + "nonce="+ cp_ajax_data.nonce + "&action=thorny_ajax_save_girls_data"
			})
			.done(function( result ) {
				
				$("table.wp-list-table.posts #post-" + post_id + " td.column-status").css({opacity : 1});
				
				$("table.wp-list-table.posts #post-" + post_id + " td.save_button .loadingimg").css({'display' : 'none'});
				
				$("table.wp-list-table.posts #post-" + post_id + " td.save_button .btn_ajax_submit").css({'display' : 'block'});
				if( (param['see'] == 'noactive' && see == '1') || 
					(param['see'] != 'noactive' && see == '0')){
					$("table.wp-list-table.posts #post-" + post_id)
					.css({'backgroundColor' : '#FFD5D5'})
					.animate(
									{
										opacity: 0.1,
									},
									700, 
									function(){
										$(this).hide()
									} 
								);
				}
				str_data = '';
			});
		});
	
		$(".approve a").click(function(){
			
			var comment_id = $(this).attr('comment_id');

			$("table.wp-list-table.posts #comment-" + comment_id + " td.notapproved").css({'opacity' : 0.5});
			
			$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .row-actions").css({'display' : 'none'});
			$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .row-actions.loader").css({'display' : 'block'});
			
			//alert( 'одобрямс коммент с ид ' + $(this).attr('comment_id') );

			$.ajax({
				type: "POST",
				url: cp_ajax_data.url,
				data:   "comment_id=" + comment_id + 
						"&nonce="+ cp_ajax_data.nonce + 
						"&action=thorny_ajax_comment_approved"
			})
			.done(function( result ) {
				if(result == 1){
					$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .navi_links").css({'display' : 'block'});
					$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .row-actions.loader").css({'display' : 'none'});
					
					$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .row-actions .approve").css({'display' : 'none'});
					
					$("table.wp-list-table.posts #comment-" + comment_id + " td.notapproved").css({'backgroundColor' : '#FFFFFF'});
					$("table.wp-list-table.posts #comment-" + comment_id + " .head .notapproved_mess").remove();
					
					if(param['unanswered'] == 'true'){
						$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .commentdiv")
						.animate(
									{
										opacity: 0.2, 
										height: 0,
										
									},
									700, 
									function(){
										$(this).parent().animate(
										{
											paddingBottom: 0, 
											paddingTop: 0,
										},
										200) 
									} 
								);					
					}
					else{
						$("table.wp-list-table.posts #comment-" + comment_id + " td.notapproved").animate({
								opacity: 1,
							}, 1000 );
					}
				}
				else{
					//alert(result);
					alert('При одобрении комментария произошел сбой, попробуйте еще раз');
				}
			});
		
			return false;
		})
		
		$(".comment .trash a").click(function(){
			var comment_id = $(this).attr('comment_id');
			
			if (confirm("Вы действительно хотите удалить комментарий от " + $("table.wp-list-table.posts #comment-" + comment_id + " td.comment .head > div").html())){
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .row-actions").css({'display' : 'none'});
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .row-actions.loader").css({'display' : 'block'});
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment").css({'backgroundColor' : '#F5EAED'});
				
				//alert( 'удалямс коммент с ид ' + $(this).attr('comment_id') );

				$.ajax({
					type: "POST",
					url: cp_ajax_data.url,
					data:   "comment_id=" + comment_id + 
							"&nonce="+ cp_ajax_data.nonce + 
							"&action=thorny_ajax_comment_trash"
				})
				.done(function( result ) {
					if(result == 1){
						$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .commentdiv")
						.animate( 
									{
										opacity: 0.2, 
										height: 0,
										
									},
									700, 
									function(){
										$(this).parent().animate(
										{
											paddingBottom: 0, 
											paddingTop: 0,
										},
										200) 
									} 
								);
					}
					else{
						//alert(result);
						alert('При удалении комментария произошел сбой, попробуйте еще раз');
					}
				});			
			}
			return false;
		})	
		
		$(".reply a").click(function(){
			var comment_id = $(this).attr('comment_id');
			$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .answercomment").show(400, function(){
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .row-actions").css({'display' : 'none'});
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .row-actions.save_button").css({'display' : 'block'});
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment textarea").attr({'disabled' : false});
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment textarea").css({'borderColor' : '#5b9dd9'});
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment textarea").focus();
			});

			return false;
		})	
		
		$(".save").click(function(){
			var comment_id = $(this).attr('comment_id');
			var answer_post_ID = $(this).attr('answer_post_ID');
			var user_id = $("table.wp-list-table.posts #comment-" + comment_id + " td.comment .answercomment input:checked").val();
			var id_answer = $("table.wp-list-table.posts #comment-" + comment_id + " td.comment .answercomment input.id_answer").val();
			
			if(user_id == 1){
				var answer_author = 'Администратор';
			}
			else{
				var answer_author = $("table.wp-list-table.posts #comment-" + comment_id + " td.comment .answercomment input.answer_author").val();
			}
			
			var answer_content = $("table.wp-list-table.posts #comment-" + comment_id + " td.comment .textarea_comment_content textarea.answer_content").val();
			
			var comment_content = $("table.wp-list-table.posts #comment-" + comment_id + " td.comment .textarea_comment_content textarea.comment_content").val();
			var answer_parent = comment_id;
			
			$("table.wp-list-table.posts #comment-" + comment_id + " td.comment").css({'opacity' : 0.5});
			$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .row-actions").css({'display' : 'none'});
			$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .row-actions.loader").css({'display' : 'block'});
			
			/*			
			alert(
						"answer_post_ID=" + answer_post_ID + 
						"&answer_author=" + answer_author + 
						"&id_answer=" + id_answer + 
						"&answer_content=" + answer_content + 
						"&answer_parent=" + answer_parent + 
						"&user_ID=" + user_id +
						
						"&comment_content=" + comment_content + 
						"&comment_id=" + comment_id
			);			
			*/			
						
						
						
			$.ajax({
				type: "POST",
				url: cp_ajax_data.url,
				data:   "answer_post_ID=" + answer_post_ID + 
						"&answer_author=" + answer_author + 
						"&id_answer=" + id_answer + 
						"&answer_content=" + answer_content + 
						"&answer_parent=" + answer_parent + 
						"&user_ID=" + user_id +
						
						"&comment_content=" + comment_content + 
						"&comment_id=" + comment_id + 
						
						"&nonce="+ cp_ajax_data.nonce + 
						"&action=thorny_ajax_comment_add"
			})
			.done(function( result ) {
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .row-actions").css({'display' : 'block'});
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .row-actions.loader").css({'display' : 'none'});
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .row-actions.save_button").css({'display' : 'none'});
				$("table.wp-list-table.posts #comment-" + comment_id + " td.notapproved").css({'backgroundColor' : '#FFFFFF'});
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment textarea").attr({'disabled' : 'disabled'});
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment textarea").css({'borderColor' : 'rgba(222,222,222,.75)'});
				
				
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment").animate({
							opacity: 1,
						}, 600 );

				//alert(result);

			});
			return false;		
		})
		
		$(".reset").click(function(){
			var comment_id = $(this).attr('comment_id');
			
			$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .answercomment_new").hide(400, function(){});
			
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .row-actions.navi_links").css({'display' : 'block'});
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment .row-actions.save_button").css({'display' : 'none'});
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment textarea").attr({'disabled' : 'disabled'});
				$("table.wp-list-table.posts #comment-" + comment_id + " td.comment textarea").css({'borderColor' : '#dddddd'});			
			
			return false;		
		})
		
		
		$(".textarea_comment_content textarea").each(function (i) {
			textAreaHeight( this );
		});
		
	})
} )( jQuery );

function textAreaHeight(textarea) {
        if (!textarea._tester) {
            var ta = textarea.cloneNode();
            ta.style.position = 'absolute';
            ta.style.zIndex = -2000000;
            ta.style.visibility = 'hidden';
            ta.style.height = '1px';
            ta.id = '';
            ta.name = '';
            textarea.parentNode.appendChild(ta);
            textarea._tester = ta;
            textarea._offset = ta.clientHeight - 50;
        }
        if (textarea._timer) clearTimeout(textarea._timer);
        textarea._timer = setTimeout(function () {
            textarea._tester.style.width = textarea.clientWidth + 'px';
            textarea._tester.value = textarea.value;
            textarea.style.height = (textarea._tester.scrollHeight - textarea._offset) + 'px';
            textarea._timer = false;
        }, 1);
    }