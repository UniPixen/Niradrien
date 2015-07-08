<section id="titre-page">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.top_authors}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
                <a href="/category/all">{$lang.files}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
    <div class="container">
        <div class="row">
            <div class="span9">
                <div id="categorie-header" class="row">
                    {if $paging}
                        <div class="pagination-fichiers span9">
                            {$paging}
                        </div>
                    {/if}
                </div>

                {if $members}
                    <div class="item-list">
                        {foreach from=$members item=m name=meilleurs_auteurs}
                            <div class="source">
                                <a href="/member/{$m.username}" class="avatar" title="{$m.username}">
                                    {if $m.avatar != ''}
                                        <img alt="{$m.username}" src="{$data_server}uploads/members/{$m.member_id}/{$m.avatar}" />
                                    {else}
                                        <img alt="{$m.username}" src="{$data_server}images/member-default.png" />
                                    {/if}
                                </a>
                                <div class="item-info">
                                    <h3><a href="/member/{$m.username}">{$smarty.foreach.meilleurs_auteurs.index+$number}. {$m.username}</a></h3>
                                    <ul class="badges">
                                        {foreach from=$badges[$m.member_id] item=b name=foo}
                                            {if $currentLanguage.code != 'fr'}
                                                {assign var='foo' value="name_`$currentLanguage.code`"}
                                                <li><img src="{$data_server}{$b.photo}" height="20" width="20" alt="{$b.$foo|escape}" title="{$b.$foo|escape}" /></li>
                                            {else}
                                                <li><img src="{$data_server}{$b.photo}" height="20" width="20" alt="{$b.name|escape}" title="{$b.name|escape}" /></li>
                                            {/if}
                                        {/foreach}
                                    </ul>
                                </div>
                                <small class="meta">
                                    <strong>{$m.products}</strong> {if $m.products <= 1}{$lang.product|@lower}{else}{$lang.products|@lower}{/if}<br />
                                    <strong>{$m.followers}</strong> {if $m.followers <= 1}{$lang.follower|@lower}{else}{$lang.followers|@lower}{/if}<br />
                                    {$lang.member_since} : {$m.register_datetime|date_format:"%B %Y"}<br />
                                    {if $m.freelance == 'true'}{$lang.available_freelancer}<br />{/if}
                                    {if $m.live_city}{$lang.lives_in} : {$m.live_city}<br />{/if}
                                </small>
                                <div class="sale-info">
                                    <em class="sale-count">{$m.sales}</em>
                                    <small>{$lang.sales|@lower}</small>
                                    <div class="rating">
                                        {section name=meilleurs_auteurs start=1 loop=6 step=1}
                                            {if $m.rating >= $smarty.section.meilleurs_auteurs.index}
                                                <i class="hd-star on"></i>
                                            {else}
                                                <i class="hd-star off"></i>
                                            {/if}
                                        {/section}
                                    </div>
                                </div>
                            </div>
                        {/foreach}
                    </div>
                {else}
                    {$lang.no_member_found}
                {/if}

                {if $paging}
                    <div id="pagination-langueter" class="row">
                        <div class="pagination-fichiers span9">
                            {$paging}
                        </div>
                    </div>
                {/if}
            </div>
        </div>
    </div>
</section>