<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.upload_theme}</h1>
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

<section id="conteneur" class="upload">
    <div class="container">
        <div class="row">
            <h3 class="titre-centre">{$lang.select_category}</h3>
        </div>
        <div class="row">
            <form action="/" method="get">
                <p>
                    <select id="category" name="category">
                        {if $mainCategories}
                            {foreach from=$mainCategories item=c}
                                <option value="{$c.keywords}">
                                    {if $currentLanguage.code != 'fr'}
                                        {assign var='foo' value="name_`$currentLanguage.code`"}
                                        {$c.$foo}
                                    {else}
                                        {$c.name}
                                    {/if}
                                </option>
                            {/foreach}
                        {/if}
                    </select><br />
                    <button onclick="window.location='/author/upload/form/?category=' + document.getElementById('category').options[document.getElementById('category').selectedIndex].value;" type="button" class="btn btn-big-shadow">{$lang.next} <i class="hd-arrow-right"></i></button>
                </p>
            </form>
        </div>
    </div>
</section>