{include file="$root_path/applications/members/templates/elite_shop.tpl"}

{if $member.homeimage}
    <div id="couverture-profil">
        <div id="image-couverture" style="background: url('{$data_server}uploads/members/{$member.member_id}/{$member.homeimage}');"></div>
    </div>
{else}
    <div id="couverture-profil">
        <div id="image-couverture" style="background: url('{$data_server}images/couverture-default.png');"></div>
    </div>
{/if}

<section id="conteneur" class="conteneur-profil">
    <div class="container">
        <div class="row">
            <div id="boutique" class="span8">
                <div id="categorie-header" class="row">
                    <div id="tri-fichiers" class="span6">
                        <ul class="tri-fichiers">
                            <li>
                                <span class="tri-select">{$lang.order_by}
                                    <strong>{$sort}</strong>
                                </span>
                                <ul>
                                    <li><a href="?sort=date&amp;order={$smarty.get.order}">{$lang.date}</a></li>
                                    <li><a href="?sort=name&amp;order={$smarty.get.order}">{$lang.title}</a></li>
                                    <li><a href="?sort=category&amp;order={$smarty.get.order}">{$lang.category}</a></li>
                                    <li><a href="?sort=rating&amp;order={$smarty.get.order}">{$lang.rating}</a></li>
                                    <li><a href="?sort=sales&amp;order={$smarty.get.order}">{$lang.sales}</a></li>
                                    <li><a href="?sort=price&amp;order={$smarty.get.order}">{$lang.price}</a></li>
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

                {if $product}
                    <div class="item-list">
                        {foreach from=$product item=i name=foo}
                            {if $i.status == 'queue'}
                                <style type="text/css">
                                    #boutique .item-list .source.queue:after {
                                        content: '{$lang.product_awaiting_approval}';
                                    }
                                </style>
                            {/if}
                            <div class="source{if $i.status == 'queue'} queue{/if}">
                                <a href="/product/{$i.id}/{$i.name|url}">
                                    <img data-tooltip="{$i.name|escape}" alt="{$i.name|escape}" class="landscape-image-magnifier preload no_preview" data-item-author="{$lang.by} {$member.username}" data-item-category="{foreach from=$i.categories item=e}{foreach from=$e item=c name=foo}{if $currentLanguage.code == 'en'}{$categories[$c].name_en}{else}{$categories[$c].name}{/if} {if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-cost="{$i.price|escape} {$currency.symbol}" data-item-name="{$i.name|escape}" data-preview-height="" data-preview-url="{$data_server}uploads/products/{$i.id}/preview.jpg" data-preview-width="" src="{$data_server}uploads/products/{$i.id}/{$i.thumbnail}" />
                                </a>
                                <div class="item-info">
                                    <h3><a href="/product/{$i.id}/{$i.name|url}">{$i.name}</a></h3>
                                    <a href="/member/{$member.username}" class="author">{$member.username}</a><br />
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
                                    <br />
                                    {if check_login_bool() && $member.member_id == $smarty.session.member.member_id}
                                        <a class="btn" href="/product/{$i.id}/{$i.name|url}/edit" style="margin-top: 15px;">
                                            <i class="hd-pen"></i>
                                            {$lang.edit}
                                        </a>
                                    {/if}
                                </small>
                                <div class="sale-info">
                                    <em class="sale-count">{$i.price} {$currency.symbol}</em>
                                    <small>{$i.sales} {$lang.sales|lower}</small>
                                    <div class="rating">
                                        {if $i.votes > 2}
                                            {section name=foo start=1 loop=6 step=1}
                                                {if $i.rating >= $smarty.section.foo.index}
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
                        <div class="pagination">{$paging}</div>
                    {/if}
                {else}
                    {$lang.no_products}
                {/if}
                <div class="pagination">{$paging}</div>
            </div>

            {include file="$root_path/applications/members/templates/aside-member.tpl"}
        </div>
    </div>
</section>