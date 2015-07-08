<div id="erreur">
    <h1>404</h1>
    <h2>{$lang.page_not_found_message}<br />{$lang.search_a_product}</h2>
    <form id="erreur-search" method="get" action="/search">
        <input type="text" value="" placeholder="Ex : 'Responsive WordPress'" name="term" />
        <button id="submit-erreur-search" type="submit">
            <i class="hd-search"></i>
        </button>
    </form>
    <a class="btn btn btn-big-shadow" role="button" href="/category/all">{$lang.start_browsing}</a>
    <a class="btn btn btn-big-shadow" role="button" href="/popular">{$lang.popular_files}</a>
</div>

{if $topMonthlyProducts}
    <section id="conteneur">
        <div class="container">
            <div class="row">
                <div class="section-header">
                    <div class="span12">
                        <h3 class="titre-section">{$lang.popular_files}</h3>
                    </div>
                </div>
            </div>
            <divs class="row">
                {foreach from=$topMonthlyProducts item=i name=foo}
                    {assign var='position' value=$position+1}
                    <div class="item span2">
                        <a href="/product/{$i.id}/{$i.name|url}" class="preview">
                            <img width="170" height="170" title="{$i.name|escape}" src="{$data_server}uploads/products/{$i.id}/{$i.thumbnail}" data-preview-width="" data-preview-url="{$data_server}uploads/products/{$i.id}/preview.jpg" data-preview-height="" data-item-name="{$i.name|escape}" data-item-cost="{$i.price} {$currency.symbol}" data-item-category="{foreach from=$i.categories item=e}{foreach from=$e item=c name=foo}{$categories[$c].name} {if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-author="{$lang.by} {$members[$i.member_id].username}" class="landscape-image-magnifier preload no_preview" alt="{$i.name|escape}" />
                        </a>
                    </div>
                {/foreach}
            </div>
        </div>
    </section>
{/if}