<section id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h1 class="page-title">{$lang.featured_files}</h1>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/">{$lang.home}</a> \
                <a href="/category/all">{$lang.files}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
    <div class="container">
        <div class="row">
            <div class="span12">
                <h2>{$lang.current_featured_product}</h2>
                {if isset($topProduct)}
                    <div class="featured-item-thumbs">
                        <a href="/product/{$topProduct.id}/{$topProduct.name|url}">
                            <img alt="{$topProduct.name|escape}"  class="landscape-image-magnifier preload no_preview" data-item-author="by {$members[$topProduct.member_id].username}" data-item-category="{foreach from=$topProduct.categories item=e}{foreach from=$e item=c name=foo}{$categories[$c].name} {if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-cost="{$currency.symbol}{$topProduct.price}" data-item-name="{$topProduct.name|escape}" data-preview-height="" data-preview-url="{$data_server}uploads/products/{$topProduct.id}/preview.jpg" data-preview-width="" src="{$data_server}uploads/products/{$topProduct.id}/{$topProduct.thumbnail}" title="{$topProduct.name|escape}" />
                        </a>
                        <a href="/member/{$members[$topProduct.member_id].username}" class="avatar" title="{$members[$topProduct.member_id].username}">
                            {if $members[$topProduct.member_id].avatar != ''}
                                <img alt="{$members[$topProduct.member_id].username}" src="{$data_server}uploads/members/{$topProduct.member_id}/{$members[$topProduct.member_id].avatar}" height="40" width="40">
                            {else}
                                <img alt="{$members[$topProduct.member_id].username}" src="{$data_server}images/member-default.png" height="40" width="40">
                            {/if}
                        </a>
                    </div>

                    <div class="featured-item-info">
                        <h3><a href="/product/{$topProduct.id}/{$topProduct.name|url}">{$topProduct.name|escape}</a></h3>
                        <small>{$lang.by} <a href="/member/{$members[$topProduct.member_id].username}/">{$members[$topProduct.member_id].username}</a></small><br />
                        <span class="rating">
                            {section name=foo1 start=1 loop=6 step=1}
                                {if $topProduct.rating >= $smarty.section.foo1.index}
                                    <img src="/static/img/star-on.png" alt="" />
                                {else}
                                    <img src="/static/img/star-off.png" alt="" />
                                {/if}
                            {/section}
                            {$topProduct.sales} {$lang.sales}
                        </span>
                        <span class="price">{$currency.symbol}{$topProduct.price}</span>
                    </div>
                {else}
                    Aucun
                {/if}
            </div>
        </div>

        <div class="row">
            <div class="span12">
                <h2>{$lang.featured_last_six_months}</h2>
                {if $product}
                    <ul class="item-grid">
                        {foreach from=$product item=i name=foo}
                            <li class="source">
                                <div class="thumbnail">
                                    <a href="/product/{$i.id}/{$i.name|url}">
                                        <img alt="{$i.name|escape}"  class="landscape-image-magnifier preload no_preview" data-item-author="by {$members[$i.member_id].username}" data-item-category="{foreach from=$i.categories item=e}{foreach from=$e item=c name=foo}{$categories[$c].name} {if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-cost="{$currency.symbol}{$i.price}" data-item-name="{$i.name|escape}" data-preview-height="" data-preview-url="{$data_server}uploads/products/{$i.id}/preview.jpg" data-preview-width="" height="80" src="{$data_server}uploads/products/{$i.id}/{$i.thumbnail}" title="{$i.name|escape}" width="80" />
                                    </a>
                                </div>

                                <div class="item-info">
                                    <h3><a href="/product/{$i.id}/{$i.name|url}">{$i.name|escape}</a></h3>
                                    <a href="/member/{$members[$i.member_id].username}/" class="author">{$members[$i.member_id].username}</a>
                                </div>

                                <small class="meta"></small>

                                <div class="sale-info">
                                    <small class="sale-count">{$i.sales} {$lang.sales}</small>
                                    <small class="price">{$currency.symbol}{$i.price}</small>
                                    <div class="rating">
                                        {section name=foo start=1 loop=6 step=1}
                                            {if $i.rating > $smarty.section.foo.index}
                                                <img src="/static/img/star-on.png" alt="" />
                                            {else}
                                                <img src="/static/img/star-off.png" alt="" />
                                            {/if}
                                        {/section}
                                    </div>
                                </div>
                            </li>
                        {/foreach}
                    </ul>
                {else}
                    {$lang.no_products}
                {/if}
            </div>
        </div>
        <div class="row">
            <div class="span4">
                <h2>{$lang.featured_authors}</h2>
                {if $featuredAuthors}
                    {foreach from=$featuredAuthors item=u name=foo}
                        <div class="content-box top-author">
                            <div class="avatar-wrapper">
                                <a href="/member/{$u.username}" class="avatar">
                                {if $u.avatar != ''}
                                    <img alt="{$u.username}" class="" height="80" src="{$data_server}uploads/members/{$u.member_id}/{$u.avatar}" width="80" />
                                {else}
                                    <img alt="{$u.username}" class="" height="80" src="{$data_server}images/member-default.png" width="80" />
                                {/if}
                                </a>
                            </div>
                            <strong>{$u.username}</strong>
                        </div>
                    {/foreach}
                {else}
                    Pas d'auteur récompensé
                {/if}
            </div>
        </div>
    </div>
</section>