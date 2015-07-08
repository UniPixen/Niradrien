{if isset($listeCategories)}
    <section id="titre-page">
        <div class="container">
            <div class="row">
                <div id="titre" class="span7">
                    <h1 class="page-title">{$lang.all_categories}</h1>
                </div>
                <div id="breadcrumbs" class="span5">
                    <a href="/">{$lang.home}</a> \
                    <a href="/category/all">{$lang.files}</a> \
                </div>
            </div>
        </div>
    </section>

    <div id="tab" class="container">
        <ul>
            <li class="">
                <div></div>
                <a href="/categories/plan/">{$lang.browse}</a>
            </li>
            <li class="selected last">
                <div></div>
                <a href="/category/" rel="nofollow">{$lang.list}</a>
                <div class="last"></div>
            </li>
        </ul>
    </div>

    <section id="conteneur">
        <div class="container">
            <div class="row">
                <div class="span12">
                    {$listeCategories}
                </div>
            </div>
        </div>
    </section>
{else}
    <section id="titre-page">
        <div class="container">
            <div class="row">
                <div id="titre" class="span7">
                    <h1 class="page-title">
                        {if $category > 0}
                            {if $currentLanguage.code != 'fr'}
                                {assign var='foo' value="description_`$currentLanguage.code`"}
                                {$category.$foo}
                            {else}
                                {$category.description}
                            {/if}
                        {else}
                            {$lang.all_categories}
                        {/if}
                    </h1>
                </div>
                <div id="breadcrumbs" class="span5">
                    <a href="/">{$lang.home}</a> \
                    <a href="/category/all">{$lang.files}</a> \
                </div>
            </div>
        </div>
    </section>

    <div id="conteneur">
        <div class="container">
            <div class="row">
                <aside id="aside-category" class="span3">
                    <div class="aside-box">
                        <h3 class="aside-header">{$lang.search_category}</h3>
                        <form class="aside-search" method="get" action="/search">
                            <input type="text" name="term" value="" placeholder="{$lang.search}" />
                            {if $category.keywords}
                                <input type="hidden" name="category" value="{$category.keywords}" />
                            {/if}
                            <button type="submit" class="btn">
                                <i class="hd-search"></i>
                                {$lang.search}
                            </button>
                        </form>
                    </div>

                    <div class="aside-box">
                        <h3 class="aside-header">{$lang.categories}</h3>
                        <ul class="categories-item">
                            <li><a href="/category/all">{$lang.start_browsing}</a></li>
                            {if $mainCategories}
                                {foreach from=$mainCategories item=c}
                                    <li>
                                        <a href="/category/{$c.keywords|url}" {if $c.keywords|url == $categoryName}class="active"{/if}>
                                            {if $currentLanguage.code == 'en'}
                                                {$c.name_en}
                                            {else}
                                                {$c.name}
                                            {/if}
                                        </a>
                                        {if isset($allCats[$c.id])}
                                            <ul {if $c.keywords|url != $categoryName}style="display: none;"{/if}>
                                                {foreach from=$allCats[$c.id] item=s}
                                                    <li>
                                                        <a href="/category/{$c.keywords|url}/{$s.keywords|url}" {if $s.keywords|url == $subCategoryName}class="active"{/if}>
                                                            {if $currentLanguage.code == 'en'}
                                                                {$s.name_en}
                                                            {else}
                                                                {$s.name}
                                                            {/if}
                                                        </a>
                                                        {if isset($allCats[$s.id])}
                                                            <ul {if $s.keywords|url != $subCategoryName}style="display: none;"{/if}>
                                                                {foreach from=$allCats[$s.id] item=sc}
                                                                    <li>
                                                                        <a href="/category/{$c.keywords|url}/{$s.keywords|url}/{$sc.keywords|url}" {if $sc.keywords|url == $subSubCategoryName}class="active"{/if}>
                                                                            {if $currentLanguage.code == 'en'}
                                                                                {$sc.name_en}
                                                                            {else}
                                                                                {$sc.name}
                                                                            {/if}
                                                                        </a>
                                                                    </li>
                                                                {/foreach}
                                                            </ul>
                                                        {/if}
                                                    </li>
                                                {/foreach}
                                            </ul>
                                        {/if}
                                    </li>
                                {/foreach}
                            {/if}
                        </ul>
                    </div>
                    {if $categoryPrices.price_min}
                        <div class="aside-box">
                            <h3 class="aside-header">{$lang.price}</h3>
                            <form class="aside-search price" method="get" action="{$smarty.server.REQUEST_URI|escape}">
                                <input type="text" value="{$smarty.get.price_min}" placeholder="{$categoryPrices.price_min}" name="price_min" />
                                <input type="text" value="{$smarty.get.price_max}" placeholder="{$categoryPrices.price_max}" name="price_max" />
                                <button type="submit" class="btn btn-small-shadow">
                                    <i class="hd-search"></i>
                                </button>
                            </form>
                        </div>
                    {/if}
                </aside>

                <div id="liste-produits" class="span9">
                    <div id="categorie-header" class="row">
                        <div id="tri-fichiers" class="span6">
                            <ul class="tri-fichiers">
                                <li>
                                    <span class="tri-select">
                                        {$lang.order_by}
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
                                    <a href="#" class="layout-grid" title="{$lang.grid_view}">
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
                                <div class="source">
                                    <a href="/product/{$i.id}/{$i.name|url}">
                                        <img alt="{$i.name|escape}" class="landscape-image-magnifier preload no_preview" data-item-author="{$lang.by} {$members[$i.member_id].username}" data-item-category="{foreach from=$i.categories item=e}{foreach from=$e item=c name=foo}{$categories[$c].name} {if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-cost="{$i.price|escape} {$currency.symbol}" data-item-name="{$i.name|escape}" data-preview-height="" data-preview-url="{$data_server}uploads/products/{$i.id}/preview.jpg" data-preview-width="" src="{$data_server}uploads/products/{$i.id}/{$i.thumbnail}" />
                                    </a>
                                    <div class="item-info">
                                        <h3><a href="/product/{$i.id}/{$i.name|url}">{$i.name}</a></h3>
                                        <a href="/member/{$members[$i.member_id].username}" class="author">{$members[$i.member_id].username}</a>
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
                                        <em class="sale-count">{$i.price} {$currency.symbol}</em>
                                        <small>
                                            {$i.sales}
                                            {if $i.sales > 1}
                                                {$lang.sales|lower}
                                            {else}
                                                {$lang.sale|lower}
                                            {/if}
                                        </small>
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
    </div>
{/if}