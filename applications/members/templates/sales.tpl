<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.my_sales}</h1>
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
            <div id="liste-produits" class="span12">
                {if $sales}
                    <div class="item-list">
                        {foreach from=$sales item=p name=foo}
                            <div class="source">
                                <a href="/product/{$p.id}/{$p.name|url}">
                                    <img alt="{$p.name|escape}" class="landscape-image-magnifier preload no_preview" data-item-author="{$lang.by} {$members[$p.owner_id].username}" data-item-category="{foreach from=$p.categories item=e}{foreach from=$e item=c name=foo}{$categories[$c].name} {if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-cost="{$products[$p.product_id].price|escape} {$currency.symbol}" data-item-name="{$p.name|escape}" data-preview-height="" data-preview-url="{$data_server}uploads/products/{$p.id}/preview.jpg" data-preview-width="" src="{$data_server}uploads/products/{$p.id}/{$p.thumbnail}" />
                                </a>
                                <div class="item-info">
                                    <h3><a href="/product/{$p.id}/{$p.name|url}">{$p.name}</a></h3>
                                    <span class="meta-categories" style="font-size: 12px;">
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
                                </div>
                                <div class="meta" style="font-size: 12px;">
                                    <span style="font-family: 'Courier New'; background: #ededed; border-radius: 4px; display: inline-block; padding: 7px 15px; margin-bottom: 10px; font-size: 16px; font-weight: bold;">{$p.code_achat}</span><br />
                                    <a href="/licenses" style="border-right: 1px solid #ededed; padding-right: 15px; margin-right: 15px;">
                                        {if $p.extended == 'true'}
                                            {$lang.extended_licence}
                                        {else}
                                            {$lang.regular_licence}
                                        {/if}
                                    </a>
                                    <a href="/member/{$members[$p.buyer_id].username}">
                                        {if $members[$p.buyer_id].avatar != ''}
                                            <img src="http://hadriendesign.dev/static/uploads/members/{$p.buyer_id}/{$members[$p.buyer_id].avatar}" alt="{$members[$p.buyer_id].username}" style="border: none; width: 20px; height: 20px; border-radius: 20px; margin-right: 7px;" />
                                        {else}
                                            <img alt="{$members[$p.buyer_id].username}" src="{$data_server}images/member-default.png" style="border: none; width: 20px; height: 20px; border-radius: 20px; margin-right: 7px;" />
                                        {/if}
                                        {$members[$p.buyer_id].username}
                                    </a>
                                </div>
                                <div class="sale-info">
                                    <span style="font-size: 12px; color: #bfbfbf; display: block; margin-bottom: 5px;">{$lang.paid}</span>
                                    <em class="sale-count">{$p.paid_price} {$currency.symbol}</em>
                                    <small>{$p.paid_datetime|date_format:"%e %B %Y"}</small>
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