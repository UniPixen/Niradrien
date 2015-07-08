<div id="tab">
	<ul>
		<li class="{if $smarty.get.controller != 'faq' && $smarty.get.controller != 'comments' && $smarty.get.controller != 'edit'} selected {/if} ">
			<div></div>
			<a href="/product/{$product.id}">{$lang.product_details}</a>
		</li>
		{if $faqs>0 || check_login_bool() && $product.member_id == $smarty.session.member.member_id}
			<li class="{if $smarty.get.controller == 'faq'}selected{/if}" id="faq-tab">
				<div></div>
				<a href="/product/faq/{$product.id}">{$lang.faqs}</a>
			</li>
		{/if}
		{if check_login_bool() && $product.member_id == $smarty.session.member.member_id}
			<li class="last{if $smarty.get.controller == 'edit'}selected{/if}" >
				<div></div>
				<a href="/product/edit/{$product.id}">{$lang.edit}</a>
			</li>
		{/if}
		{if check_login_bool() && $product.member_id == $smarty.session.member.member_id}
			<li class="last{if $smarty.get.controller == 'comments'}selected{/if} last">
				<div></div>
				<a href="/product/comments/{$product.id}">{$lang.comments}</a>
				<div class="last"></div>
			</li>
		{/if}
	</ul>
</div>