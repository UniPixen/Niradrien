{if $product.status == 'active'}
	{if $product.votes > 2}
		<div id="modal-rating" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-rating" aria-hidden="true">
		    <h4>{$lang.buyers_ratings}</h4>
			<div id="rating-stars">
				{section name=foo start=1 loop=6 step=1}
					{if $productRatings.average >= $smarty.section.foo.index}
						<i class="hd-star on"></i>
					{else}
						<i class="hd-star off"></i>
					{/if}
				{/section}
			</div>
		    <p>{$lang.average_of} {$productRatings.average} {$lang.based_on|lower} {$productRatings.count} {$lang.ratings|lower}.</p>
		    <ul class="rating-stats">
				{section name=foo start=1 loop=6 step=1}
			    	<li>
			    		<span class="rating-level">
			    			{$smarty.section.foo.index}
			    			{if $smarty.section.foo.index > 1}
			    				{$lang.stars|@lower}
			    			{else}
			    				{$lang.star|@lower}
			    			{/if}
			    		</span>
			    		<div class="rating-graph">
			    			<div class="rating-graph-bar">
			    				<div class="rating-graph-bar-progress" style="width: {math equation="{$productRatings.stats.{$smarty.section.foo.index}} / {$productRatings.count} * 100" format="%.0f"}%;">{math equation="{$productRatings.stats.{$smarty.section.foo.index}} / {$productRatings.count} * 100" format="%.0f"}%</div>
			    			</div>
			    		</div>
			    		<span class="rating-count">{$productRatings.stats.{$smarty.section.foo.index}}</span>
			    	</li>
				{/section}
		    </ul>
		</div>
	{/if}
	<div id="page-item" itemscope itemtype="http://schema.org/CreativeWork">
		<section id="titre-page" class="titre-item">
			<div class="container">
				<div class="row">
					<div id="titre" class="span7">
						<h1 class="page-title{if $product.name|strlen > 20} small-title{/if}" itemprop="name">{$product.name}</h1>
						{if $product.votes > 2}
							<div id="note-produit" itemtype="http://schema.org/AggregateRating" itemscope="" itemprop="aggregateRating" data-placement="right" data-toggle="tooltip" data-original-title="{$lang.average_of} {$product.rating}, {$lang.based_on|lower} {$product.votes|lower} {$lang.ratings}.">
								<meta content="{$product.rating}" itemprop="ratingValue">
								<meta content="{$product.votes}" itemprop="ratingCount">
								{section name=foo start=1 loop=6 step=1}
									{if $productRatings.average >= $smarty.section.foo.index}
										<i class="hd-star on"></i>
									{else}
										<i class="hd-star off"></i>
									{/if}
								{/section}
								<a href="#modal-rating" data-toggle="modal" role="button">{$product.votes} {$lang.buyers_ratings|lower}</a>
							</div>
						{else}
							<div id="note-produit" class="unknow-note" data-placement="right" data-toggle="tooltip" data-original-title="{$lang.less_3_votes_unknow_rating}">
								<i class="hd-star-unknow"></i>
								<i class="hd-star-unknow"></i>
								<i class="hd-star-unknow"></i>
								<i class="hd-star-unknow"></i>
								<i class="hd-star-unknow"></i>
							</div>
					    {/if}
					</div>
					<nav id="breadcrumbs" class="span5" itemprop="breadcrumb">
						<a href="/">{$lang.home}</a> \
						<a href="/category/all/">{$lang.files}</a> \
						{foreach from=$product.categories item=e}
							{foreach from=$e item=c name=foo}
								<a href="/category/{category id=$categories[$c].id}">
									{if $currentLanguage.code != 'fr'}
										{assign var='foo' value="name_`$currentLanguage.code`"}
										{$categories[$c].$foo}
									{else}
										{$categories[$c].name}
									{/if}
								</a>
								{if !$smarty.foreach.foo.last} \ {/if}
							{/foreach}
						{/foreach} \
					</nav>
				</div>
			</div>
		</section>

		<div id="tab" class="container">
			<ul>
				<li class="selected">
					<a href="/product/{$product.id}/{$product.name|url}">{$lang.product_details}</a>
				</li>
				{if $faqs > 0 || check_login_bool() && $product.member_id == $smarty.session.member.member_id}
					<li class="{if $smarty.get.controller == 'faq'}selected{/if}" id="faq-tab">
						<a href="/product/{$product.id}/{$product.name|url}/faq">{$lang.faqs}</a>
					</li>
				{/if}
				{if check_login_bool() && $product.member_id == $smarty.session.member.member_id}
					<li class="last{if $smarty.get.controller == 'edit'}selected{/if}" >
						<a href="/product/{$product.id}/{$product.name|url}/edit">{$lang.edit}</a>
					</li>
				{/if}
				<li class="last{if $smarty.get.controller == 'comments'}selected last{/if}  {if check_login_bool() && $product.member_id == $smarty.session.member.member_id}last{/if}">
					<a href="/product/{$product.id}/{$product.name|url}/comments">{$lang.comments}</a>
				</li>
			</ul>
		</div>

		{if isset($paypal_show)}
			<div>{$lang.paypal_proceed}</div>
			<form id="form" action="{$paypal.url}" method="post">
				<input type="hidden" name="cmd" value="_ext-enter" />
				<input type="hidden" name="redirect_cmd" value="_xclick" />
				<input type="hidden" name="business" value="{$paypal.business}" />
				<input type="hidden" name="item_name" value="{$product.name|escape}" />
				<input type="hidden" name="currency_code" value="{$currency.code}" />
				<input type="hidden" name="no_shipping" value="1" />
				<input type="hidden" name="no_note" value="1" />
				<input type="hidden" name="notify_url" value="https://{$domain}/product/paypal/notify/" />
				<input type="hidden" name="return" value="https://{$domain}/product/paypal/success/" />
				<input type="hidden" name="cancel_return" value="https://{$domain}/product/paypal/cancel/" />
				<input type="hidden" name="image_url" value="" />
				<input type="hidden" name="email" value="{$smarty.session.member.email}" />
				<input type="hidden" name="first_name" value="{$smarty.session.member.firstname}" />
				<input type="hidden" name="last_name" value="{$smarty.session.member.lastname}" />
				<input type="hidden" name="custom" value="{$orderID}" />
				<input type="hidden" name="custom2" value="{$smarty.post.licence}" />
				<input type="hidden" name="amount" value="{$price}" />
				<input type="hidden" name="cs" value="0" />
				<input type="hidden" name="page_style" value="PayPal" />
				<noscript>
					<button type="submit">{$lang.click_to_pay}</button>
				</noscript>
			</form>
			{literal}
				<script>
					$(document).ready(function() {
						$("#form").submit();
					});
				</script>
			{/literal}
		{/if}

		{include file="$root_path/applications/product/templates/conteneur-paiement.tpl"}

		<div class="container page">
			{foreach from=$miniatureProducts item=i}
				<link href="{$data_server}uploads/products/{$i.id}/{$i.thumbnail}" itemprop="thumbnailUrl" />
			{/foreach}
			<div class="row">
		  		<article id="item" class="span8">
		  			<div class="slider-theme">
		  				<ul>
							{foreach from=$previewFiles item=f name=foo}
								<li>
									<img src="{$data_server}uploads/products/{$product.id}/preview/{$f}" itemprop="image" alt="{$product.name}" />
								</li>
							{/foreach}
		  				</ul>
		  			</div>

					<script src="{$data_server}js/social-share.js"></script>

					<div id="partager-item">
						<strong>{$lang.share} :</strong>
						<ul id="share">
							<li>
								<a class="icon-facebook socialShare" href="#partager" data-original-title="{$lang.share_facebook}" data-toggle="tooltip" data-placement="top" data-type="facebook" data-url="https://{$domain}/product/{$product.id}/{$product.name|url}" data-title="{$product.name}" data-description="{$product.description|truncate:255}" data-media="{$data_server}uploads/products/{$product.id}/preview.jpg">
									<i class="hd-facebook"></i>
								</a>
							</li>
							<li>
								<a class="icon-pinterest socialShare" href="#partager" data-original-title="{$lang.share_pinterest}" data-toggle="tooltip" data-placement="top" data-type="pinterest" data-url="https://{$domain}/product/{$product.id}/{$product.name|url}" data-description="{$product.name}" data-media="{$data_server}uploads/products/{$product.id}/preview.jpg">
									<i class="hd-pinterest"></i>
								</a>
							</li>
							<li>
								<a class="icon-twitter socialShare" href="#partager" data-original-title="{$lang.share_twitter}" data-toggle="tooltip" data-placement="top" data-type="twitter" data-url="https://{$domain}/product/{$product.id}/{$product.name|url}" data-description="{$product.name} {$lang.by} {$product.member.username}" data-via="{$website_title}">
									<i class="hd-twitter"></i>
								</a>
							</li>
							<li>
								<a class="icon-google-plus socialShare" href="#partager" data-original-title="{$lang.share_google_plus}" data-toggle="tooltip" data-placement="top" data-type="googleplus" data-url="https://{$domain}/product/{$product.id}/{$product.name|url}" data-description="{$product.description|truncate:255}">
									<i class="hd-google-plus"></i>
								</a>
							</li>
						</ul>
						
						{if $product.demo_url}
							<ul id="preview">
								<li>
									<a href="/product/preview/{$product.id}/{$product.name|url}" data-original-title="{$lang.live_preview}" data-toggle="tooltip" data-placement="top" target="_blank">
										<i class="hd-preview"></i>
									</a>
								</li>
							</ul>
						{/if}

						<script>$('.socialShare').socialShare();</script>
					</div>
					
		  			<div id="description-item" itemprop="description">{$product.description}</div>

			        <div class="produits-auteur">
			        	{if $otherProducts}
			        		{foreach from=$otherProducts item=i}
			        			<a href="/product/{$i.id}/{$i.name|url}">
			        				<img alt="{$i.name|escape}"  class="landscape-image-magnifier preload no_preview" data-item-author="par {$product.member.username}" data-item-category="{foreach from=$i.categories item=e}{foreach from=$e item=c name=foo}{$categories[$c].name} {if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-cost="{$i.price} {$currency.symbol}" data-item-name="{$i.name|escape}" data-preview-height="" data-preview-url="{$data_server}uploads/products/{$i.id}/preview.jpg" data-preview-width="" src="{$data_server}uploads/products/{$i.id}/{$i.thumbnail}" title="{$i.name|escape}" />
			        			</a>
			        		{/foreach}
			        	{/if}

			        	{if $otherProductsCount < 9}
			        		{section name=foo start=$otherProductsCount loop=9 step=1}
			        			<div class="thumbnail"></div>
			        		{/section}
			        	{/if}
			        	<div id="plus-produits">
			        		<small>
			        			<a href="/member/{$product.member.username}/shop">{$lang.more_products_by} {$product.member.username}</a>
			        		</small>
			        	</div>
			        </div>
		        </article>
		        {include file="$root_path/applications/product/templates/aside-fichier.tpl"}
			</div>
		</div>
	</div>
	
	<script src="{$data_server}js/licenses.js"></script>
{else}
	<div id="page-not-available-product">
		<div class="container page">
			<div class="row">
				<article id="not-available-product" class="span8">
					<h1>{$lang.product_not_longer_available}</h1>
					<p>{$lang.not_longer_available_sentences}</p>
					<div id="fichiers-interessants">
						<span>{$lang.some_interesting_products} :</span>
						<div class="thumbnail"></div>
						<div class="thumbnail"></div>
						<div class="thumbnail"></div>
						<div class="thumbnail"></div>
						<div class="thumbnail"></div>
						<div class="thumbnail"></div>
						<div class="thumbnail"></div>
					</div>
				</article>
				<div id="not-available-product-picture" class="span4">
					<img src="{$data_server}images/carton-vide.svg" alt="{$lang.empty_box}" />
				</div>
			</div>
		</div>
	</div>
{/if}