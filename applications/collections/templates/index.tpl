<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.public_collections} <span>{$collections|@count} {$lang.collections|@lower}</span></h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
            </div>
        </div>
    </div>
</section>

<div id="menu-page">
    <div class="dashboard container" id="tab-membre">
        <ul>
            <li class="last">
                <div></div>
                <a href="#">{$lang.public_collections}</a>
                <div class="last"></div>
            </li>
        </ul>
    </div>
    <div class="container" id="tab-compte" style="margin: 0px auto; padding-bottom: 10px;">
        <form method="get" id="recherche-collection" action="/search">
            <fieldset id="collection-fieldset" style="display: block; overflow: hidden;">
                <input type="text" value="" placeholder="{$lang.search_collection} ..." name="mot" class="span12">
                <input id="type" name="type" type="hidden" value="collections" />
            </fieldset>
        </form>
    </div>
</div>

<section id="conteneur">
    <div class="container">
        <div class="row">
            <div class="span12">
                <div id="categorie-header" class="row">
                    <div id="tri-fichiers" class="span9">
                        <ul class="tri-fichiers">
                            <li>
                                <span class="tri-select">{$lang.order_by}
                                    <strong>
                                        {if $smarty.get.sort == 'date'}
                                            {$lang.date}
                                        {elseif $smarty.get.sort == 'name'}
                                            {$lang.title}
                                        {elseif $smarty.get.sort == 'category'}
                                            {$lang.category}
                                        {elseif $smarty.get.sort == 'rating'}
                                            {$lang.rating}
                                        {elseif $smarty.get.sort == 'sales'}
                                            {$lang.sales}
                                        {elseif $smarty.get.sort == 'price'}
                                            {$lang.price}
                                        {else}
                                            {$lang.date}
                                        {/if}
                                    </strong>
                                </span>
                                <ul>
                                    <li><a href="?sort=date&amp;order={$smarty.get.order}">{$lang.date}</a></li>
                                    <li><a href="?sort=name&amp;order={$smarty.get.order}">{$lang.title}</a></li>
                                    <li><a href="?sort=rating&amp;order={$smarty.get.order}">{$lang.rating}</a></li>
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
                    </div>

                    {if $paging}
                        <div class="pagination-fichiers span3">
                            {$paging}
                        </div>
                    {/if}
                </div>

                {if $collections}
                    <div id="collections-publiques" class="row">
                        {foreach from=$collections item=c}
                            <div class="collection-case span3">
                                <a href="/collections/view/{$c.id}/{$c.name|url}" class="collection-image">
                                    {if $c.photo != ''}
                                        <img alt="{$c.name|escape}" src="{$data_server}uploads/collections/{$c.photo}" />
                                    {else}
                                        <img alt="{$c.name|escape}" src="{$data_server}images/collection-default.png" />
                                    {/if}
                                </a>

                                <div class="collection-info">
                                    <h3><a href="/collections/view/{$c.id}/{$c.name|url}">{$c.name}</a></h3>
                                    <span>
                                        <a href="/member/{$members[$c.member_id].username}" class="auteur">{$lang.by} {$members[$c.member_id].username}</a>
                                    </span>
                                    {*
                                    {if $c.text != ''}
                                        <p>{$c.text|nl2br}</p>
                                    {else}
                                        <p>{$lang.no_description_collection}</p>
                                    {/if}
                                    *}
                                </div>

                                <div class="collection-note">
                                    {section name=foo start=1 loop=6 step=1}
                                        {if $c.rating >= $smarty.section.foo.index}
                                            <i class="hd-star on"></i>
                                        {else}
                                            <i class="hd-star off"></i>
                                        {/if}
                                    {/section}
                                    <br /><small>{$c.votes} {$lang.ratings}</small>
                                </div>
                            </div>
                        {/foreach}
                    </div>
                {else}
                    <div>{$lang.no_collections}</div>
                {/if}
                <div class="row">
                    <div class="pagination-fichiers span12">
                        {$paging}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>