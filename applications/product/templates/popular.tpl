<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.popular_files}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
                <a href="/categorie/all">{$lang.files}</a> \
            </div>
        </div>
    </div>
</section>

<div id="menu-page">
    <div class="container" id="menu-haut" style="margin-top: 0; padding-top: 30px;">
        <h3 class="titre-centre" style="font-size: 30px; margin-bottom: 0px;">
            <a href="/popular/{$prevDate}">
                <i class="hd-arrow-left"></i>
            </a>
            {$lang.ends_on} {$endDate|date_format:"%d %B"}

            {if isset($nextDate)}
                <a href="/popular/{$nextDate}">
                    <i class="hd-arrow-right"></i>
                </a>
            {else}
                <span class="slider-control slider-next-disabled" style="color: #dedede;">
                    <i class="hd-arrow-right"></i>
                </span>
            {/if}
        </h3>
    </div>
</div>

<section id="conteneur">
    <div class="container">
        <div id="popular-week" class="row">
            <div class="span12">
                <h2>{$lang.this_week}</h2>
                {if $topSellProducts}
                    <div class="item-grid">
                        {assign var='position' value='0'}
                        {foreach from=$topSellProducts item=i name=foo}
                            {assign var='position' value=$position+1}
                            <div class="source">
                                <a href="/product/{$i.id}/{$i.name|url}">
                                    <img alt="{$i.name|escape}" class="landscape-image-magnifier preload no_preview" data-item-author="{$lang.by} {$members[$i.member_id].username}" data-item-category="{foreach from=$i.categories item=e}{foreach from=$e item=c name=foo}{$categories[$c].name} {if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-cost="{$i.price} {$currency.symbol}" data-item-name="{$i.name|escape}" data-preview-height="" data-preview-url="{$data_server}uploads/products/{$i.id}/preview.jpg" data-preview-width="" src="{$data_server}uploads/products/{$i.id}/{$i.thumbnail}" />
                                </a>

                                <div class="item-info">
                                    <h3><a href="/product/{$i.id}/{$i.name|url}">{$i.name}</a></h3>
                                    <a href="/member/{$members[$i.member_id].username}/" class="author">{$members[$i.member_id].username}</a>
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
                                    <small class="sale-count">
                                        {$i.sales}
                                        {if $i.votes > 1}
                                            {$lang.sales|lower}
                                        {else}
                                            {$lang.sale|lower}
                                        {/if}
                                    </small>
                                    <small class="price">{$i.price|string_format:"%.2f"} {$currency.symbol}</small>
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
                                                {section name=foo start=1 loop=6 step=1}
                                                    <i class="hd-star-unknow"></i>
                                                {/section}
                                            </div>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        {/foreach}
                    </div>
                {else}
                    {$lang.no_products}
                {/if}
            </div>
        </div>

        <div id="popular-month" class="row">
            <div class="span9">
                <h2>{$lang.three_last_month} <span style="color: #bfbfbf; font-size: 14px;">{$lang.until|@lower} {$endMonthlyDate|date_format:"%d %B %Y"}</span></h2>
                {if $topMonthlyProducts}
                    <div class="item-grid">
                        {foreach from=$topMonthlyProducts item=i name=foo}
                            {assign var='position' value=$position+1}
                            <div class="source">
                                <a href="/product/{$i.id}/{$i.name|url}">
                                    <img alt="{$i.name|escape}" class="landscape-image-magnifier preload no_preview" data-item-author="{$lang.by} {$members[$i.member_id].username}" data-item-category="{foreach from=$i.categories item=e}{foreach from=$e item=c name=foo}{$categories[$c].name} {if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-cost="{$i.price} {$currency.symbol}" data-item-name="{$i.name|escape}" data-preview-height="" data-preview-url="{$data_server}uploads/products/{$i.id}/preview.jpg" data-preview-width="" src="{$data_server}uploads/products/{$i.id}/{$i.thumbnail}" />
                                </a>

                                <div class="item-info">
                                    <h3><a href="/product/{$i.id}/{$i.name|url}">{$i.name}</a></h3>
                                    <a href="/member/{$members[$i.member_id].username}/" class="author">{$members[$i.member_id].username}</a>
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
                                    <small class="sale-count">
                                        {$i.sales}
                                        {if $i.sales > 1}
                                            {$lang.sales|lower}
                                        {else}
                                            {$lang.sale|lower}
                                        {/if}
                                    </small>
                                    <small class="price">{$i.price|string_format:"%.2f"} {$currency.symbol}</small>
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
                                                {section name=foo start=1 loop=6 step=1}
                                                    <i class="hd-star-unknow"></i>
                                                {/section}
                                            </div>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        {/foreach}
                    </div>
                {else}
                    {$lang.no_products}
                {/if}
            </div>

            <div class="span3" style="border-left: 1px solid #ededed; padding-left: 30px; width: 239px;">
                {if $topAuthors}
                    <h2>{$lang.top_authors}<span style="color: #bfbfbf; font-size: 14px; margin-left: 10px;">{$lang.of} {$lang.monthArr[$month]|lower}</span></h2>
                    {foreach from=$topAuthors item=u name=foo}
                        <div style="overflow: hidden;">
                            <div style="border-right: 1px solid #ededed; float: left; margin-right: 20px; padding-right: 20px; text-align: center; width: 100px;">
                                <a href="/member/{$u.username}" class="avatar" title="{$u.username}">
                                    {if $u.avatar != ''}
                                        <img alt="{$u.username}" src="{$data_server}uploads/members/{$u.member_id}/{$u.avatar}" style="border-radius: 80px; border: 3px solid #ededed; max-width: 70px;" />
                                    {else}
                                        <img alt="{$u.username}" src="{$data_server}images/member-default.png" />
                                    {/if}
                                </a>
                            </div>
                            <h3 style="margin-bottom: 0; margin-top: 5px; float: left;"><a href="/member/{$u.username}">{$u.username}</a></h3>
                            <i class="hd-cart" style="margin-right: 5px;"></i>
                            <small>
                                {$u.sales}
                                {if $u.sales > 1}
                                    {$lang.sales|lower}
                                {else}
                                    {$lang.sale|lower}
                                {/if}
                            </small>
                        </div>
                    {/foreach}
                {/if}
            </div>
        </div>
    </div>
</section>