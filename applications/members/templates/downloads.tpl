<section id="titre-page" class="page-header">
	<div class="container">
		<div class="row">
			<div id="titre" class="span7">
				<h1 class="page-title">
					{$lang.downloads}
					<span>
						{$countPurchases}
						{if $countPurchases > 1}
							{$lang.files|lower}
						{else}
							{$lang.file|lower}
						{/if}
					</span>
				</h1>
			</div>
			<div id="breadcrumbs" class="span5">
				<a href="/">{$lang.home}</a> \
				<a href="/members/{$smarty.session.member.username}">{$lang.my_account}</a> \
			</div>
		</div>
	</div>
</section>
{include file="$root_path/applications/members/templates/tabsy.tpl"}

<section id="conteneur">
	<div class="container">
		<div class="row">
			<div class="span9">
				{if $product}
					<div id="telechargements" class="item-list">
						{foreach from=$product item=i name=foo}
							{if $i.extended == 'true'}
								{assign var='licence_type' value='extended'}
							{else}
								{assign var='licence_type' value='regular'}
							{/if}

							<div class="source">
								<a href="/product/{$i.product_id}/{$i.name|url}">
									<img alt="{$i.name|escape}" class="landscape-image-magnifier preload no_preview" data-item-author="{$lang.by} {$members[$i.owner_id].username}" data-item-category="{foreach from=$i.categories item=e}{foreach from=$e item=c name=foo}{if $currentLanguage.code != 'fr'}{assign var='foo' value="name_`$currentLanguage.code`"}{$categories[$c].$foo}{else}{$categories[$c].name}{/if}{if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-cost="{$i.product_price|escape} {$currency.symbol}" data-item-name="{$i.name|escape}" data-preview-height="" data-preview-url="{$data_server}uploads/products/{$i.product_id}/preview.jpg" data-preview-width="" src="{$data_server}uploads/products/{$i.product_id}/{$i.thumbnail}" />
								</a>

								<div class="item-info">
									<h3><a href="/product/{$i.product_id}/{$i.name|url}">{$i.name}</a></h3>
									<small>
										<a href="/licenses" target="_blank" title="{$i.code_achat}">
											{if {$i.extended} == 'true'}
												{$lang.extended_licence}
											{else}
												{$lang.regular_licence}
											{/if}
										</a>
										<a href="/account/downloads/certificat-licence/{$i.code_achat}">{$lang.license_certificate}</a>
									</small>
								</div>

								{if $i.status == 'active'}
									<div class="item-telecharger">
										<a href="/account/download/{$i.code_achat}" class="btn btn-big-shadow">
											<i class="hd-cloud-download"></i>
											{$lang.download}
										</a>
										{if $i.downloaded == 'true'}
											<div class="note-telechargement">
												<div class="note">
													{if isset($ratedProducts[$i.product_id])}
														{section name=foo start=5 loop=6 step=-1 max=5}
															{if $ratedProducts[$i.product_id].rate >= $smarty.section.foo.index}
																<a href="/account/download/{$i.code_achat}?rating={$smarty.section.foo.index}" class="star-on">
																	<i class="hd-star on"></i>
																</a>
															{else}
																<a href="/account/download/{$i.code_achat}?rating={$smarty.section.foo.index}" class="star-off">
																	<i class="hd-star off"></i>
																</a>
															{/if}
														{/section}
													{else}
														{section name=foo start=5 loop=6 step=-1 max=5}
															<a href="/account/download/{$i.code_achat}?rating={$smarty.section.foo.index}" title="{$smarty.section.foo.index} {$lang.stars|lower}" rel="nofollow" class="star-off">
																<i class="hd-star off"></i>
															</a>
														{/section}
													{/if}
												</div>
											</div>
										{else}
											<div class="never-downloaded">{$lang.product_never_downloaded}</div>
										{/if}
									</div>
								{else}
									<div class="item-telecharger delete">
										<div class="produit-supprime">
											<strong>{$lang.download_not_available}</strong><br />
											{$lang.product_removed}
										</div>
										<small class="telecharger-licence">{$lang.still_download_license}</small>
									</div>
								{/if}
							</div>
						{/foreach}
					</div>
				{else}
					<div id="no-download">
						<p>{$lang.no_download_products}</p>
					</div>
				{/if}
			</div>
			<div id="aside-telechargement" class="span3">
				<div id="advice">
					<h3>{$lang.advice}</h3>
					<p>{$lang.download_immediately_info}</p>
				</div>

				<div id="probleme-telechargement">
					<h3>{$lang.a_problem} ?</h3>
					<p>{$lang.a_problem_info}</p>
					<ol class="text-list decimal">
						<li>{$lang.a_problem_info1}</li>
						<li>{$lang.a_problem_info2}</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</section>