<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.author}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
                <a href="/members/{$smarty.session.member.username}">{$lang.my_account}</a> \
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
        {if $authorAnnouncements}
            <div class="row">
                <div id="author-announcement" class="span12">
                    <h2>
                        <i class="hd-info"></i>
                        {$lang.announcements}
                    </h2>
                    {foreach from=$authorLastAnnouncement item=announcement}
                        {$announcement.message}
                    {/foreach}
                </div>
            </div>
        {/if}

        <div class="row">
            <div id="dashboard-auteur" class="span8">
                <div id="author-upload" class="row">
                    <div class="auteur-panel span8">
                        <h3>{$lang.begin_upload}</h3>
                        <form action="/author/" method="get">
                            <fieldset>
                                <label>{$lang.select_category_upload} :</label>
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
                                <button onclick="window.location='/upload/form/?category=' + document.getElementById('category').options[document.getElementById('category').selectedIndex].value;" type="button" class="btn btn btn-big-shadow">
                                    <i class="hd-arrow-right"></i>
                                    {$lang.next}
                                </button><br />
                                <small>
                                    <a href="/help/upload">{$lang.need_help}</a>
                                </small>
                            </fieldset>
                        </form>
                    </div>
                </div>

                <div id="author-numbers" class="row">
                    <div class="auteur-panel span4">
                        <h3>{$lang.this_week_stats}</h3>
                        <span>{$weekStats.earning|string_format:"%.2f"} {$currency.symbol}</span>
                        <p>{$lang.a_total_of} <strong>{$weekStats.sold}</strong> {$lang.sales_this_month|@lower}.</p>
                    </div>

                    <div class="auteur-panel span4">
                        <h3>{$lang.comments}</h3>
                        <span>{$commentaires_auteur}</span>
                        {if $commentaires_auteur > 1}
                            {$lang.new_comments|@lower}
                        {else}
                            {$lang.new_comment|@lower}
                        {/if}
                    </div>
                </div>
            </div>

            <div id="author-announcement-list" class="span4">
                <h3>Dernières annonces</h3>
                {if $authorAnnouncements}
                    <ul>
                        {foreach from=$authorAnnouncements item=announcementx}
                            <li>
                                <span>{$announcementx.datetime|date_format:"%e %B %Y"}</span>
                                <p>{$announcementx.message}</p>
                            </li>
                        {/foreach}
                    </ul>
                {/if}
            </div>
        </div>
    </div>
</section>