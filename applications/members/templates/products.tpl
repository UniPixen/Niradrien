<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.my_products}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
                <a href="/members/{$smarty.session.member.username}/">{$lang.my_account}</a> \
            </div>
        </div>
    </div>
</section>

<div id="commission">
    <div id="commission-container">
        <div class="btn btn btn-big-shadow btn-grey">
            {$lang.commission} : {$commission.percent} &#37;
        </div>
    </div>
</div>

<div id="menu-page">
    {include file="$root_path/applications/members/templates/tabsy.tpl"}
    {include file="$root_path/applications/members/templates/author-tab.tpl"}
</div>

<section id="conteneur">
    <div class="container">
        <div class="row">
            <div id="liste-produits" class="span9">
                {if $product}
                    <div class="item-list">
                        {foreach from=$product item=p name=foo}
                            <div class="source">
                                <a href="/product/{$p.id}/{$p.name|url}">
                                    <img alt="{$p.name|escape}" class="landscape-image-magnifier preload no_preview" data-item-author="{$lang.by} {$members[$p.member_id].username}" data-item-category="{foreach from=$p.categories item=e}{foreach from=$e item=c name=foo}{$categories[$c].name} {if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-cost="{$p.price|escape} {$currency.symbol}" data-item-name="{$p.name|escape}" data-preview-height="" data-preview-url="{$data_server}uploads/products/{$p.id}/preview.jpg" data-preview-width="" src="{$data_server}uploads/products/{$p.id}/{$p.thumbnail}" />
                                </a>
                                <div class="item-info">
                                    <h3><a href="/product/{$p.id}/{$p.name|url}">{$p.name}</a></h3>
                                    {if $p.status == 'active'}
                                        <span style="font-size: 12px; border-radius: 4px; border: 1px solid #27ae60; color: #27ae60; padding: 3px 7px;">En ligne</span>
                                    {elseif $p.status == 'unapproved'}
                                        <span style="font-size: 12px; border-radius: 4px; border: 1px solid #e74c3c; color: #e74c3c; padding: 3px 7px;">Refusé</span>
                                    {elseif $p.status == 'queue'}
                                        <span style="font-size: 12px; border-radius: 4px; border: 1px solid #bfbfbf; color: #bfbfbf; padding: 3px 7px;">En attente</span>
                                    {elseif $p.status == 'deleted'}
                                        <span style="font-size: 12px; border-radius: 4px; border: 1px solid #e74c3c; color: #e74c3c; padding: 3px 7px;">Supprimé</span>
                                    {/if}
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
                                    <a style="margin-top: 15px;" href="/product/{$p.id}/{$p.name|url}" class="btn">
                                        <i class="hd-pen"></i>
                                        {$lang.edit}
                                    </a>
                                </small>
                                <div class="sale-info">
                                    <em class="sale-count">{$p.price} {$currency.symbol}</em>
                                    <small>
                                        {$p.sales}
                                        {if $p.sales > 1}
                                            {$lang.sales|lower}
                                        {else}
                                            {$lang.sale|@lower}
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
                    {if $paging}
                        <div id="pagination-footer">
                            <div class="pagination-fichiers">
                                {$paging}
                            </div>
                        </div>
                    {/if}
                {else}
                    {$lang.no_products}
                {/if}
            </div>
        </div>
    </div>
</section>