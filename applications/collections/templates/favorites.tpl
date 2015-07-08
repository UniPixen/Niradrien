<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.favorites}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
    <div class="container">
        <div class="row">
            <div id="liste-produits" class="span9">
                {if $products}
                    <div id="categorie-header" class="row">
                        <div id="tri-fichiers" class="span6">
                            <ul class="tri-fichiers">
                                <li>
                                    <span class="tri-select">{$lang.order_by}
                                        <strong>{$sort}</strong>
                                    </span>
                                    <ul>
                                        <li><a href="?sort=date&amp;order={$smarty.get.order}">{$lang.date}</a></li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="tri-fichiers tri-fichiers-sort">
                                <li>
                                    <a href="?sort={$smarty.get.sort}&amp;order={if $smarty.get.order == 'asc'}desc{else}asc{/if}" class="tri-fichiers-desc {if $smarty.get.order == 'asc'}asc{else}desc{/if}" title="{$lang.change_products_sort}">
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
                                    <a href="#" class="layout-grid" title="{$lang.list_view}">
                                        <i class="hd-grid layout-grid"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        {if $paging}
                            <div class="pagination-fichiers span3">
                                {$paging}
                            </div>
                        {/if}
                    </div>

                    <div class="item-list">
                        {foreach from=$products item=p}
                            <div class="source">
                                <a href="/product/{$p.id}/{$p.name|url}">
                                    <img alt="{$p.name|escape}" class="landscape-image-magnifier preload no_preview" data-item-author="{$lang.by} {$members[$p.member_id].username}" data-item-category="{foreach from=$p.categories item=e}{foreach from=$e item=c name=foo}{$categories[$c].name} {if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-cost="{$p.price|escape} {$currency.symbol}" data-item-name="{$p.name|escape}" data-preview-height="" data-preview-url="{$data_server}uploads/products/{$p.id}/preview.jpg" data-preview-width="" src="{$data_server}uploads/products/{$p.id}/{$p.thumbnail}" />
                                </a>
                                <div class="item-info">
                                    <h3><a href="/product/{$p.id}/{$p.name|url}">{$p.name}</a></h3>
                                    <a href="/member/{$members[$p.member_id].username}" class="author">{$members[$p.member_id].username}</a>
                                </div>
                                <small class="meta">
                                    <span class="meta-categories">
                                        {foreach from=$p.categories item=e}
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
                                    <em class="sale-count">{$p.price} {$currency.symbol}</em>
                                    <small>
                                        {$p.sales}
                                        {if $p.sales > 1}
                                            {$lang.sales|lower}
                                        {else}
                                            {$lang.sale|lower}
                                        {/if}
                                    </small>
                                    <div class="rating">
                                        {if $p.votes > 2}
                                            {section name=foo start=1 loop=6 step=1}
                                                {if $p.rating >= $smarty.section.foo.index}
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
                            </div>
                        {/foreach}
                    </div>
                {else}
                    {$lang.no_favorite}
                {/if}
            </div>
        </div>
    </div>
</section>