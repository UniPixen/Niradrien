{if check_login_bool()}
	<div id="tab-membre" class="dashboard container">
		<ul>
			{if $smarty.session.member.author == 'true'}
				<li {if $smarty.get.controller == 'author' || $smarty.get.controller == 'form' || $smarty.get.controller == 'products' || $smarty.get.controller == 'sales' || $smarty.get.controller == 'comments' || $smarty.get.controller == 'check-purchase' || $smarty.get.controller == 'index' || $smarty.get.controller == 'earnings'}class="selected"{/if}>
					<div></div>
					<a href="/author">{$lang.author}</a>
				</li>
			{/if}
			<li {if $smarty.get.controller == 'profile'}class="selected"{/if}>
				<div></div>
				<a href="/member/{$smarty.session.member.username}">{$lang.profile}</a>
			</li>
			{if $smarty.session.member.products != '0'}
				<li {if $smarty.get.controller == 'shop'}class="selected"{/if}>
					<div></div>
					<a href="/member/{$smarty.session.member.username}/shop">{$lang.shop}</a>
				</li>
			{/if}
			<li {if $smarty.get.controller == 'settings'}class="selected"{/if}>
				<div></div>
				<a href="/account/settings">{$lang.settings}</a>
			</li>
			<li {if $smarty.get.controller == 'downloads'}class="selected"{/if}>
				<div></div>
				<a href="/account/downloads">{$lang.downloads}</a>
			</li>
			<li {if $smarty.get.controller == 'collections'}class="selected"{/if}>
				<div></div>
				<a href="/account/collections">{$lang.collections}</a>
			</li>
			<li {if $smarty.get.controller == 'deposit' || $smarty.get.controller == 'payment'}class="selected"{/if}>
				<div></div>
				<a href="/account/deposit">{$lang.deposit}</a>
			</li>
			{if $smarty.session.member.author == 'true'}
				<li {if $smarty.get.controller == 'withdraw'}class="selected"{/if}>
					<div></div>
					<a href="/account/withdraw">{$lang.withdrawal}</a>
				</li>
			{/if}
			<li class="{if $smarty.get.controller == 'history'}selected{/if} last">
				<div></div>
				<a href="/account/history">{$lang.history}</a>
				<div class="last"></div>
			</li>
		</ul>
	</div>
{/if}