<section id="titre-page">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$collection.name}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
                <a href="/collections">{$lang.collections}</a> \
            </div>
        </div>
    </div>
</section>

<div id="conteneur">
    <div class="container">
        <div class="row">
            <aside class="span3" id="aside-collection">
                <div id="photo-collection">
                    {if $collection.photo != ''}
                        <img src="{$data_server}uploads/collections/{$collection.photo}" alt="{$collection.name|escape}" />
                    {else}
                        <img src="{$data_server}images/collection-default.png" alt="{$collection.name|escape}" />
                    {/if}
                </div>
                {if check_login_bool()}
                    <div id="votes-collection">
                        <h4>{$lang.rate_this_collection}</h4>
                        {if $collection.rate}
                            {section name=foo start=1 loop=6 step=1}
                                {if $collection.rate.rate >= $smarty.section.foo.index}
                                    <i class="hd-star on"></i>
                                {else}
                                    <i class="hd-star off"></i>
                                {/if}
                            {/section}
                            <br />
                        {else}
                            <div class="note">
                                {section name=foo start=5 loop=6 step=-1 max=5}
                                    <a href="/collections/rate/{$collection.id}?rating={$smarty.section.foo.index}">
                                        <i class="hd-star off"></i>
                                    </a>
                                {/section}
                            </div>
                        {/if}
                        <small>{$collection.votes} {$lang.ratings|@lower}</small>

                        {if $collection.rate}
                            <br />
                            <small>{$lang.you_rate} {$collection.rate.rate} {$lang.stars|@lower}</small>
                        {/if}
                    </div>
                {/if}

                <div id="auteur-collection">
                    <h3>{$lang.collection_of}</h3>
                    <div id="avatar-portefolio">
                        <a class="avatar" href="/member/{$collection.member.username}" title="{$collection.member.username}">
                            {if $collection.member.avatar != ''}
                                <img src="{$data_server}uploads/members/{$collection.member.member_id}/{$collection.member.avatar}" alt="{$collection.member.username}" />
                            {else}
                            <img src="{$data_server}images/member-default.png" alt="{$collection.member.username}" />
                            {/if}
                        </a>
                    </div>

                    <div id="informations-auteur">
                        <h3>{$collection.member.username}</h3>
                        <ul class="badges">
                            {foreach from=$member_badges item=b name=foo}
                                <li>
                                    <img src="{$data_server}{$b.photo}" alt="{$b.name|escape}" title="{$b.name|escape}" />
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                </div>

                {if check_login_bool() && $collection.member_id == $smarty.session.member.member_id}
                    {if check_login_bool()}
                        <div id="modal-collection" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-collection" aria-hidden="true">
                            <h4>{$lang.edit_collection}</h4>
                            <form enctype="multipart/form-data" method="post">
                                <div class="input-collection">
                                    <input name="name" type="text" value="{$collection.name|escape}" placeholder="{$lang.name}" />
                                </div>

                                <label id="upload-photo" for="file_upload" class="btn"><i class="hd-cloud-upload"></i> {$lang.choose_couverture_photo}</label>
                                <div class="input-collection">
                                    <input id="file_upload" name="file_upload" type="file" />
                                    <small>(370 x 200px)</small>
                                </div>

                                <div class="input-collection">
                                    <textarea id="description" name="description" placeholder="{$lang.description}">{if $collection.text}{$collection.text|escape}{/if}</textarea>
                                </div>

                                <div class="input-collection">
                                    <label for="collection_publically_visible" class="checkbox">
                                        <input name="collection[publically_visible]" type="hidden" value="0" />
                                        <input id="collection_publically_visible" name="publically_visible" type="checkbox" value="1" {if $collection.public == 'true'}checked="checked"{/if} />
                                        {$lang.publicly_viewable}
                                    </label>
                                </div>

                                <input type="hidden" name="edit" value="yes" />
                                <button id="editer-collection-button" type="submit" class="btn btn btn-big-shadow">
                                    <i class="hd-pen"></i> {$lang.edit_collection}
                                </button>
                            </form>
                        </div>

                        <a role="button" href="#modal-collection" data-toggle="modal" class="btn btn-big-shadow">
                            <i class="hd-pen"></i>
                            {$lang.edit_collection}
                        </a>
                    {/if}

                    <div id="supprimer-collection">
                        <form action="" method="post">
                            <input type="hidden" name="delete" value="yes" />
                                <button type="submit" onclick="return confirm('{$lang.are_you_sure_delete_collection}');" class="btn btn-big-shadow btn-supprimer">
                                <i class="hd-trash"></i>
                                {$lang.delete_collection}
                            </button>
                        </form>
                    </div>
                {/if}
            </aside>

            <div id="liste-produits" class="span9">
                {if $collection.text != ''}
                    <div id="collection-header">
                        <div class="collection-info">
                            {$collection.text|nl2br}
                        </div>
                    </div>
                {/if}

                <div id="categorie-header" class="row">
                    <div id="tri-fichiers" class="span6">
                        <ul class="tri-fichiers">
                            <li>
                                <span class="tri-select">
                                    {$lang.order_by}
                                    <strong>{$sort}</strong>
                                </span>
                                <ul>
                                    <li>
                                        <a href="?sort=date&amp;order={$smarty.get.order}">{$lang.date}</a>
                                    </li>
                                    <li>
                                        <a href="?sort=name&amp;order={$smarty.get.order}">{$lang.title}</a>
                                    </li>
                                    <li>
                                        <a href="?sort=category&amp;order={$smarty.get.order}">{$lang.category}</a>
                                    </li>
                                    <li>
                                        <a href="?sort=rating&amp;order={$smarty.get.order}">{$lang.rating}</a>
                                    </li>
                                    <li>
                                        <a href="?sort=sales&amp;order={$smarty.get.order}">{$lang.sales}</a>
                                    </li>
                                    <li>
                                        <a href="?sort=price&amp;order={$smarty.get.order}">{$lang.price}</a>
                                    </li>
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
                                {if check_login_bool() && $collection.member_id == $smarty.session.member.member_id}
                                    <a href="/collections/view/{$collection.id}/?delete={$i.id}" title="{$lang.remove_product_collection}" class="btn btn-supprimer">
                                        <i class="hd-trash"></i>
                                        {$lang.delete}
                                    </a>
                                {/if}
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
                {else}
                    <p>{$lang.no_collection_info}</p>
                {/if}
                <div class="pagination">{$paging}</div>
            </div>
        </div>
    </div>
</div>