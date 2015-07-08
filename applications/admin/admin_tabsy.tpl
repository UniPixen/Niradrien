<div id="tab-membre" class="dashboard container">
	<ul>
		<li {if $smarty.get.m == ''}class="selected"{/if}>
			<div></div>
			<a href="/admin/">{$lang.dashboard}</a>
		</li>
		<li {if $smarty.get.m == 'members'}class="selected"{/if}>
			<div></div>
			<a href="/admin/?m=members&amp;c=list">{$lang.members}</a>
		</li>
		<li {if $smarty.get.m == 'product' && $smarty.get.c == 'list'}class="selected"{/if}>
			<div></div>
			<a href="/admin/?m=product&amp;c=list">{$lang.products}</a>
		</li>
		<li {if $smarty.get.m == 'product' && $smarty.get.c == 'sales'}class="selected"{/if}>
			<div></div>
			<a href="/admin/?m=product&amp;c=sales">{$lang.sales}</a>
		</li>
		<li {if $smarty.get.m == 'system'}class="selected"{/if}>
			<div></div>
			<a href="/admin/?m=system&amp;c=list">{$lang.settings}</a>
		</li>
		<li {if $smarty.get.m == 'support'}class="selected"{/if}>
			<div></div>
			<a href="/admin/?m=support&amp;c=list">{$lang.support}</a>
		</li>
		<li {if $smarty.get.m == 'category'}class="selected"{/if}>
			<div></div>
			<a href="/admin/?m=category&amp;c=list">{$lang.categories}</a>
		</li>
		<li {if $smarty.get.m == 'pages'}class="selected"{/if}>
			<div></div>
			<a href="/admin/?m=pages&amp;c=list">{$lang.pages}</a>
		</li>
		<li {if $smarty.get.m == 'news'}class="selected"{/if}>
			<div></div>
			<a href="/admin/?m=news&amp;c=list">{$lang.news}</a>
		</li>
		<li {if $smarty.get.m == 'collections'}class="selected"{/if}>
			<div></div>
			<a href="/admin/?m=collections&amp;c=list">
				{$lang.collections}
			</a>
		</li>
		<li class="{if $smarty.get.m == 'reports'}selected{/if} last">
			<div></div>
			<a href="/admin/?m=reports&amp;c=list">{$lang.reports}</a>
			<div class="last"></div>
		</li>
	</ul>
</div>