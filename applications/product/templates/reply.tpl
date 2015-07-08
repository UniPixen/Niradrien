{if check_login_bool()}
	{if $show_form}
		<div id="avatar-reponse">
			{if $smarty.session.member.avatar != ''}
				<img alt="{$smarty.session.member.username}" src="{$data_server}uploads/members/{$smarty.session.member.member_id}/{$smarty.session.member.avatar}" />
			{else}
				<img alt="{$smarty.session.member.username}" src="{$data_server}images/member-default.png" />
			{/if}
		</div>

		<div class="commentaire-nouvelle-reponse commentaire-nouvelle-reponse-active">
			<form action="/product/{$comment.product_id}/{$comment.product_name|url}/comments" method="post">
				<input type="hidden" name="comment_id" value="{$comment.id}" />
				<textarea name="comment" placeholder="{$lang.write_reply}"></textarea>
				<input type="hidden" name="add_reply" value="yes" />
				<div class="form-submit">
					<button type="submit" class="btn btn-little-shadow">
						<i class="hd-comment"></i>
						{$lang.reply}
					</button>
				</div>
			</form>
		</div>
	{/if}
{/if}