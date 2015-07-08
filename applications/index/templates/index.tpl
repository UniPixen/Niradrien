<div id="slider">
    <div class="slider-container">
        <div class="slider">
            <ul>
                <li data-transition="fade,papercut" data-slotamount="10" data-masterspeed="300">
                    <img src="{$data_server}images/homepage-picture.jpg" alt="Themes" />
                    <div
                        class="caption total_produits slide-center sfb fadeout"
                        data-x="0"
                        data-y="100"
                        data-speed="300"
                        data-start="1000"
                        data-easing="easeOutExpo"
                    >
                        {$productsCount}
                    </div>
                    <div
                        class="caption prix_produits slide-center sfb fadeout"
                        data-x="0"
                        data-y="200"
                        data-speed="300"
                        data-start="1000"
                        data-easing="easeOutExpo"
                    >
                        {$lang.home_info}
                    </div>
                    <div
                        class="caption prix_produits slide-center sfb fadeout"
                        data-x="0"
                        data-y="255"
                        data-speed="300"
                        data-start="1000"
                        data-easing="easeOutExpo"
                    >
                        {$lang.home_info2} {$lowPrice} {$currency.symbol}
                    </div>
                    <div
                        class="caption type_produits slide-center sfb fadeout"
                        data-x="0"
                        data-y="320"
                        data-speed="300"
                        data-start="2000"
                        data-easing="easeOutExpo"
                    >
                        {if $randCategories}
                            HTML, WordPress, phpBB3, PSD
                        {/if}
                    </div>
                    <div
                        class="caption sfb slide-center fadeout"
                        data-x="0"
                        data-y="390"
                        data-speed="300"
                        data-start="3000"
                        data-easing="easeOutExpo"
                    >
                        <a href="/category/all" class="btn btn-big-shadow" role="button">{$lang.start_browsing}</a>
                        <a href="/popular" class="btn btn-big-shadow" role="button">{$lang.popular_files}</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<section id="conteneur" class="derniers-produits">
    <div class="container">
        <div class="section-header">
            <div class="row">
                <div class="span4">
                    <h3 class="titre-section">{$lang.new_products}</h3>
                </div>
                <div id="type-template" class="span8">
                    <form id="latest-products">
                        <ul>
                            <li>
                                <div class="categorie-list active">
                                    <input type="radio" name="category_id" id="category_all" value="all" />
                                    {$lang.all_files}
                                </div>
                            </li>
                            {if $mainCategories}
                                {foreach from=$mainCategories item=c}
                                    <li>
                                        <div class="categorie-list">
                                            {if $currentLanguage.code != 'fr'}
                                                {assign var='foo' value="name_`$currentLanguage.code`"}
                                                <input type="radio" name="category_id" id="category_{$c.id}" value="{$c.id}" />
                                                {$c.$foo}
                                            {else}
                                                <input type="radio" name="category_id" id="category_{$c.id}" value="{$c.id}" />
                                                {$c.name}
                                            {/if}
                                        </div>
                                    </li>
                                {/foreach}
                            {/if}
                        </ul>
                    </form>
                </div>
            </div>
        </div>

        <div class="loading">
            <div class="loader-icon big">
                <span class="spinner"></span>
                <span></span>
            </div>
            <div class="loading-text">{$lang.loading} ...</div>
        </div>

        <div class="derniers-produits">
            <div id="recent-items" class="row">
                <ul class="derniers-produits-list">
                    {if $recentProducts}
                        {foreach from=$recentProducts item=i name=foo}
                            <li class="item span2">
                                <a class="preview" href="/product/{$i.id}/{$i.name|url}">
                                    <img alt="{$i.name|escape}" class="landscape-image-magnifier preload no_preview" data-item-author="{$lang.by} {$members[$i.member_id].username}" data-item-category="{foreach from=$i.categories item=e}{foreach from=$e item=c name=foo}{if $currentLanguage.code != 'fr'}{assign var='foo' value="name_`$currentLanguage.code`"}{$categories[$c].$foo}{else}{$categories[$c].name}{/if}{if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-cost="{$i.price|string_format:"%.0f"} {$currency.symbol}" data-item-name="{$i.name|escape}" data-preview-height="" data-preview-url="{$data_server}uploads/products/{$i.id}/preview.jpg" data-preview-width="" src="{$data_server}uploads/products/{$i.id}/{$i.thumbnail}" title="{$i.name|escape}" />
                                </a>
                            </li>
                        {/foreach}
                    {else}
                        <div class="span12">{$lang.no_records}</div>
                    {/if}
                </ul>
            </div>
        </div>
    </div>
</section>

{if {isset($followingProducts)} || check_login_bool()}
    <section id="follow-auteurs">
        <div class="container">
            {if isset($followingProducts)}
                <div class="row">
                    <div class="section-header">
                        <div class="span4">
                            <h3 class="titre-section">{$lang.you_follow} …</h3>
                        </div>
                    </div>
                </div>
                <div class="derniers-produits produits-follow">
                    <div class="row">
                        {foreach from=$followingProducts item=f}
                            <div class="item span2">
                                <a class="preview" href="/product/{$f.id}/{$f.name|url}">
                                    <img alt="{$f.name|escape}" class="landscape-image-magnifier preload no_preview" data-item-author="{$lang.by} {$members[$f.member_id].username}" data-item-category="{foreach from=$f.categories item=e}{foreach from=$e item=c name=foo}{if $currentLanguage.code != 'fr'}{assign var='foo' value="name_`$currentLanguage.code`"}{$categories[$c].$foo}{else}{$categories[$c].name}{/if}{if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-cost="{$f.price|escape} {$currency.symbol}" data-item-name="{$f.name|escape}" data-preview-height="" data-preview-url="{$data_server}uploads/products/{$f.id}/preview.jpg" data-preview-width="" src="{$data_server}uploads/products/{$f.id}/{$f.thumbnail}" height="170" width="170" />
                                </a>
                            </div>
                        {/foreach}
                    </div>
                </div>
            {else}
                <div class="row">
                    <div class="section-header">
                        <div class="span4">
                            <h3 class="titre-section">{$lang.you_follow} ...</h3>
                        </div>
                    </div>
                    <div id="no-produits-follow">
                        <div id="no-produits-follow-icon">
                            <i class="hd-user"></i>
                        </div>
                        <span>{$lang.recent_products_follow}</span><br />
                        {$lang.click_follow_author}
                    </div>
                </div>
            {/if}
        </div>
    </section>
{/if}

{if $featuredAuthor}
    <section id="auteur-recompense">
        <div class="container">
            <div class="row">
                <div class="section-header">
                    <div class="span12">
                        <h3 class="titre-section">{$lang.featured_author} : <a href="/member/{$featuredAuthor.username}">{$featuredAuthor.username}</a></h3>
                    </div>
                </div>
                <a href="/member/{$featuredAuthor.username}" class="avatar" title="{$featuredAuthor.username}">
                    {if $featuredAuthor.avatar != ''}
                        <img alt="{$featuredAuthor.username}" class="" height="80" src="{$data_server}uploads/members/{$featuredAuthor.member_id}/{$featuredAuthor.avatar}" width="80" />
                    {else}
                        <img alt="{$featuredAuthor.username}" class="" height="80" src="{$data_server}images/member-default.png" width="80" />
                    {/if}
                </a>
                <div id="informations-auteur-recompense" class="span7">
                    <p>
                        {$featuredAuthorInfo}<br />
                        <a href="/member/{$featuredAuthor.username}/shop" class="btn btn-big-shadow">{$lang.view_shop_of} {$featuredAuthor.username}</a>
                    </p>
                </div>
                <div id="autres-fichiers-auteur" class="span3">
                    {if $featuredProducts}
                        {foreach from=$featuredProducts item=i}
                            <a href="/product/{$i.id}/{$i.name|url}">
                                <img alt="{$i.name|escape}" class="landscape-image-magnifier preload no_preview" data-item-author="{$lang.by} {$members[$i.member_id].username}" data-item-cost="{$i.price|escape}{$currency.symbol}" data-item-category="{foreach from=$i.categories item=e}{foreach from=$e item=c name=foo}{if $currentLanguage.code != 'fr'}{assign var='foo' value="name_`$currentLanguage.code`"}{$categories[$c].$foo}{else}{$categories[$c].name}{/if} {if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-name="{$i.name|escape}" data-preview-height="" data-preview-url="{$data_server}uploads/products/{$i.id}/preview.jpg" data-preview-width="" height="80" src="{$data_server}uploads/products/{$i.id}/{$i.thumbnail}" title="{$i.name|escape}" width="80" />
                            </a>
                        {/foreach}
                    {/if}
                    <span>{$lang.some_work_of} {$featuredAuthor.username}</span>
                </div>
            </div>
        </div>
    </section>
{/if}