<section id="titre-page">
	<div class="container">
		<div class="row">
			<div id="titre" class="span7">
				<h1 class="page-title">
					{if isset($searchText) && !empty($searchText)}
						{$searchText}
					{else}
						{$lang.start_browsing}
					{/if}

					<span>
						{if $results}
							{count($results)}
							{if (count($results)) > 1}
								{$lang.results|@lower}
							{else}
								{$lang.result|@lower}
							{/if}
						{else}
							0 {$lang.result|@lower}
						{/if}
					</span>
				</h1>
			</div>
			<div id="breadcrumbs" class="span5">
				<a href="/">{$lang.home}</a> \
				<a href="/category/all">{$lang.search}</a> \
			</div>
		</div>
	</div>
</section>

<div id="conteneur">
	<div class="container">
		<div class="row">
			<aside id="aside-category" class="span3">
				<h3 class="aside-header">{$lang.search_within}</h3>
				<form class="aside-search" action="/search" method="get">
					<input onclick="this.value = &quot;&quot;" type="text" value="{$lang.search_within} {$searchText}" />
					<input id="type" name="type" type="hidden" value="{$type}" />
					<input id="category" name="category" type="hidden" value="" />
					<input id="base" name="base" type="hidden" value="{$smarty.get.term|escape}" />
					<input id="collection_id" name="collection_id" type="hidden" value="" />
					{if isset($smarty.get.categories) && is_array($smarty.get.categories)}
						{foreach from=$smarty.get.categories item=c key=k}
							<input type="hidden" name="categories[{$k}]" value="1" />
						{/foreach}
					{/if}
					<button class="btn" type="submit">
						<i class="hd-search"></i>
						{$lang.search}
					</button>
				</form>
			</aside>

			{if $smarty.get.type != 'forum'}
				<div id="liste-produits" class="span9">
			{else}
				<div id="forum" class="span9">
			{/if}
				{if $smarty.get.type != 'forum'}
					{if $authorSearch}
						<div class="row">
							<div id="recherche-auteur" class="span9">
								{$lang.member_corresponds_search} :
								{foreach from=$authorSearch item=u name=foo}
									<a href="/members/{$u.username}" class="avatar" title="{$u.username}">
										{if $u.avatar != ''}
											<img alt="{$u.username}" src="{$data_server}/uploads/members/{$u.member_id}/{$u.avatar}" />
										{else}
											<img alt="{$u.username}" src="{$data_server}/images/member-default.png" />
										{/if}
									</a>
									<a href="/members/{$u.username}">{$u.username}</a>
								{/foreach}
							</div>
						</div>
					{/if}
					<div id="categorie-header" class="row">
						<div id="tri-fichiers" class="span4">
							<ul class="tri-fichiers">
								<li>
	                                <span class="tri-select">
	                                    {$lang.order_by}
	                                    <strong>{$sort}</strong>
	                                </span>
									<ul>
										<li><a href="?sort=date&amp;term={$searchText|escape}&amp;order={if $smarty.get.order == 'asc'}desc{else}asc{/if}">{$lang.date}</a></li>
										<li><a href="?sort=name&amp;term={$searchText|escape}&amp;order={if $smarty.get.order == 'asc'}desc{else}asc{/if}">{$lang.name}</a></li>
										<li><a href="?sort=rating&amp;term={$searchText|escape}&amp;order={if $smarty.get.order == 'asc'}desc{else}asc{/if}">{$lang.rating}</a></li>
										<li><a href="?sort=sales&amp;term={$searchText|escape}&amp;order={if $smarty.get.order == 'asc'}desc{else}asc{/if}">{$lang.sales}</a></li>
									</ul>
								</li>
							</ul>

							<ul class="tri-fichiers tri-fichiers-sort">
								<li>
									<a href="?sort={if $smarty.get.sort == ''}date{else}{$smarty.get.sort}{/if}&amp;term={$searchText|escape}&amp;order={if $smarty.get.order == 'asc'}desc{else}asc{/if}" class="tri-fichiers-desc {if $smarty.get.order == 'asc'}asc{else}desc{/if}" title="{$lang.change_products_sort}">
										<i class="hd-arrow-{if $smarty.get.order == 'asc'}up{else}down{/if}"></i>
									</a>
								</li>
							</ul>

							<ul class="tri-fichiers tri-fichiers-list layout-switcher">
								<li>
									<a href="#" class="layout-list active" title="{$lang.list_view}">
										<i class="hd-list layout-list"></i>
									</a>
								</li>
							</ul>

							<ul class="tri-fichiers tri-fichiers-grid layout-switcher">
								<li>
									<a href="#" class="layout-grid" title="{$lang.grid_view}">
										<i class="hd-grid layout-grid"></i>
									</a>
								</li>
							</ul>
						</div>

						{if $paging}
							<div class="pagination-fichiers span5">
								{$paging}
							</div>
						{/if}
					</div>
				{/if}

				{if $results}
					{if $type == 'members'}
						<ul class="user-list">
							{foreach from=$results item=u name=foo}
								<li>
									<div class="thumbnail">
										<a href="/members/{$u.username}" class="avatar" title="{$u.username}">
											{if $u.avatar != ''}
												<img alt="{$u.username}" height="80" width="80" src="{$data_server}/uploads/members/{$u.member_id}/{$u.avatar}" />
											{else}
												<img alt="{$u.username}" height="80" width="80" src="{$data_server}/images/member-default.png" />
											{/if}
										</a>
									</div>

									<div class="user-info">
										<h3><a href="/members/{$u.username}">{$u.username}</a></h3>
										<ul class="badges"></ul>
									</div>

									<small class="meta">
										<strong>{$u.products}</strong> {$lang.products}<br />
										<strong>{$u.followers}</strong> {$lang.followers}<br />
										{$lang.member_since}: {$u.register_datetime|date_format:"%b %Y"}<br />
										{if $u.freelance == 'true'}
											{$lang.available_freelancer}<br />
										{/if}
									</small>

									<div class="sale-info">
										<small>
											{$u.sales}
											{if $u.sales > 1}
												{$lang.sales|lower}
											{else}
												{$lang.sale|lower}
											{/if}
										</small>
										<div class="rating">
                                            {if $u.votes > 2}
                                                {section name=foo1 start=1 loop=6 step=1}
                                                    {if $u.rating >= $smarty.section.foo1.index}
                                                        <i class="hd-star on"></i>
                                                    {else}
                                                        <i class="hd-star off"></i>
                                                    {/if}
                                                {/section}
                                            {else}
                                                <div class="unknow-note">
                                                    <i class="hd-star-unknow"></i>
                                                    <i class="hd-star-unknow"></i>
                                                    <i class="hd-star-unknow"></i>
                                                    <i class="hd-star-unknow"></i>
                                                    <i class="hd-star-unknow"></i>
                                                </div>
                                            {/if}
										</div>
									</div>
								</li>
							{/foreach}
						</ul>
					{elseif $type == 'collections'}
						<div id="collections-publiques" class="row">
							{foreach from=$results item=c name=foo}
								<div class="collection-case span3">
									<a href="/collections/view/{$c.id}/{$c.name|url}" class="collection-image">
										{if $c.photo != ''}
											<img alt="{$c.name|escape}" src="{$data_server}/uploads/collections/{$c.photo}" width="260" height="140" />
										{else}
											<img alt="{$c.name|escape}" src="/static/images/collection-default.png" width="260" height="140" />
										{/if}
									</a>

									<div class="collection-info">
										<h3>
											<a href="/collections/view/{$c.id}/{$c.name|url}">{$c.name}</a>
										</h3>
										<span>
											<a href="/members/{$members[$c.member_id].username}" class="auteur">{$members[$c.member_id].username}</a>
										</span>
									</div>

									<div class="collection-note">
										{section name=foo start=1 loop=6 step=1}
											{if $c.rating >= $smarty.section.foo.index}
												<i class="hd-star on"></i>
											{else}
												<i class="hd-star off"></i>
											{/if}
										{/section}
										<br />
										<small>{$c.votes} {$lang.ratings}</small>
									</div>
								</div>
							{/foreach}
						</div>
					{elseif $type == 'forum'}
						{foreach from=$results item=thread name=foo}
							<div id="threads-list">
								<div class="thread row" id="{$thread.id}">
									<div class="thread-avatar span1">
										<a title="{$members[$thread.member_id].username}" class="avatar" href="/member/{$members[$thread.member_id].username}">
				            				{if $members[$thread.member_id].avatar != ''}
				            					<img src="{$data_server}uploads/members/{$thread.member_id}/{$members[$thread.member_id].avatar}" alt="{$members[$thread.member_id].username}" class="thread-author" />
				            				{else}
				            					<img src="{$data_server}images/member-default.png" alt="{$members[$thread.member_id].username}" class="thread-author">
				            				{/if}
										</a>
										<small>
											{$memberTotalMessages.{$members[$thread.member_id].member_id}.total_member_messages}
											{if $memberTotalMessages.{$members[$thread.member_id].member_id}.total_member_messages > 1}
												{$lang.messages|lower}
											{else}
												{$lang.message|lower}
											{/if}
										</small>
				                        <ul class="badges">
				                            {foreach from=$badges[$thread.member_id] item=b name=foo}
				                                {if $currentLanguage.code != 'fr'}
				                                    {assign var='foo' value="name_`$currentLanguage.code`"}
				                                    <li data-original-title="{$b.$foo|escape}" title="{$b.$foo|escape}" data-toggle="tooltip" data-placement="right">
				                                        <img src="{$data_server}{$b.photo}" alt="{$b.$foo|escape}" />
				                                    </li>
				                                {else}
				                                    <li data-original-title="{$b.name|escape}" title="{$b.name|escape}" data-toggle="tooltip" data-placement="right">
				                                        <img src="{$data_server}{$b.photo}" alt="{$b.name|escape}" />
				                                    </li>
				                                {/if}
				                            {/foreach}
				                        </ul>
									</div>
									<div class="thread-content span8">
										<p>
											{$lang.message_wrote_by} <a href="/member/{$members[$thread.member_id].username}">{$members[$thread.member_id].username}</a>
						                	{if isset($members[$thread.member_id].applications.forum)}
						                		<strong class="message-type-moderator">{$lang.moderator}</strong>
						                	{/if}
										</p>
										<div class="thread-header">
											<a href="/forum/thread/{$thread.thread_name|url}/{$thread.id}">{$thread.thread_name}</a>
										</div>
										<p>{$thread.comment}</p>
										<div class="thread-footer">
											<ul>
												<li>
													{if $currentLanguage.code != 'fr'}
														{$thread.datetime|timeAgo|@lower} {$lang.ago|@lower}
													{else}
														{$lang.ago} {$thread.datetime|timeAgo|@lower}
													{/if}
												</li>
												<li><a href="/forum/thread/{$thread.thread_name|url}/{$thread.id}#post-{$thread.id}">{$lang.permalink}</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						{/foreach}
					{else}
						<div class="item-list">
							{foreach from=$results item=i name=foo}
								<div class="source">
									<a href="/product/{$i.id}/{$i.name|url}" onclick="">
										<img alt="{$i.name|escape}" class="landscape-image-magnifier preload no_preview" data-item-author="{$lang.by} {$members[$i.member_id].username}" data-item-category="{foreach from=$i.categories item=e}{foreach from=$e item=c name=foo}{$categories[$c].name} {if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-cost="{$i.price|escape} {$currency.symbol}" data-item-name="{$i.name|escape}" data-preview-height="" data-preview-url="{$data_server}uploads/products/{$i.id}/preview.jpg" data-preview-width="" src="{$data_server}uploads/products/{$i.id}/{$i.thumbnail}" title=""  height="170" width="170" />
									</a>

									<div class="item-info">
										<h3>
											<a href="/product/{$i.id}/{$i.name|url}">
												{$i.name}
											</a>
										</h3>
										<a href="/member/{$members[$i.member_id].username}/" class="author">
											{$members[$i.member_id].username}
										</a>
									</div>

									<small class="meta">
                                        <span class="meta-categories">
                                            {foreach from=$i.categories item=e}
                                                {foreach from=$e item=c name=foo}
                                                    <a href="/category/{category id=$categories[$c].id}">
                                                        {if $currentLanguage.code != 'fr'}
                                                            {assign var='foo' value="name_`$currentLanguage.code`"}
                                                            {$categories[$c].$foo}
                                                        {else}
                                                            {$categories[$c].name}
                                                        {/if}
                                                    </a>
                                                {/foreach}
                                            {/foreach}
                                        </span>
									</small>

									<div class="sale-info">
										<em class="sale-count">{$i.price} {$currency.symbol}</em>
										<small>
											{$i.sales}
											{if $i.sales > 1}
												{$lang.sales|lower}
											{else}
												{$lang.sale|lower}
											{/if}
										</small>
										<div class="rating">
											{section name=foo start=1 loop=6 step=1}
												{if $i.rating >= $smarty.section.foo.index}
													<i class="hd-star on"></i>
												{else}
													<i class="hd-star off"></i>
												{/if}
											{/section}
										</div>
									</div>
								</div>
							{/foreach}
						</div>
					{/if}
					<div class="row">
						<div class="pagination-fichiers span9">{$paging}</div>
					</div>
				{else}
					{$lang.no_results_found}
				{/if}
			</div>
		</div>
	</div>
</div>