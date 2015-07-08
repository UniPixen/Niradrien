<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.collections}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
                <a href="/members/{$smarty.session.member.username}">{$lang.my_account}</a> \
            </div>
        </div>
    </div>
</section>

{literal}
    <script>
        $(document).ready(function() {
            $("#content-collections ul li:eq(0)").siblings().hide();
            $("#menu-haut ul li").click(function(){
                var val = $(this).index();
                showthis(val + 1);
            });

            function showthis(val) {
                var btn_index = val - 1;

                // Liens
                $("#menu-haut ul li:eq(" + btn_index + ")").siblings().removeClass("active");
                $("#menu-haut ul li:eq(" + btn_index + ")").addClass("active");

                // Contenu
                $("#content-collections ul li:eq(" + btn_index + ")").show();
                $("#content-collections ul li:eq(" + btn_index + ")").siblings().hide();
            }
        });
    </script>
{/literal}

{if check_login_bool()}
    <div id="nouvelle-collection">
        <div id="modal-collection" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-collection" aria-hidden="true">
            <h4>{$lang.create_collection}</h4>
            <form id="formulaire-collection" method="post" action="{$smarty.server.REQUEST_URI|escape}" enctype="multipart/form-data">
                <div class="input-collection">
                    <input id="nom-collection" name="name" type="text" value="" placeholder="{$lang.new_collection}" />
                </div>

                <div class="input-collection">
                    <textarea id="description" name="description" placeholder="{$lang.description}"></textarea>
                </div>

                <label id="upload-photo" for="file_upload" class="btn">
                    <i class="hd-cloud-upload"></i>
                    {$lang.choose_couverture_photo}
                </label>
                <div class="input-collection">
                    <input id="file_upload" name="file_upload" type="file" />
                    <small>(260 x 140px)</small>
                </div>

                <div class="input-collection">
                    <label class="checkbox" for="publically_visible">
                        <input id="publically_visible" name="publically_visible" type="checkbox" value="1" />
                        {$lang.public_visible} ?
                    </label>
                </div>

                <input type="hidden" name="add_collection" value="yes" />
                <button type="submit" id="ajouter-collection" class="btn btn btn-big-shadow">
                    <i class="hd-bookmark"></i>
                    {$lang.bookmark_this}
                </button>
            </form>
        </div>

        <div id="collection-container">
            <form method="post" action="{$smarty.server.REQUEST_URI|escape}" enctype="multipart/form-data">
                {if isset($bookCollections)}
                    <select name="collection_id">
                        {foreach from=$bookCollections item=c}
                            <option value="{$c.id}">{$c.name}</option>
                        {/foreach}
                    </select>

                    <p id="creer-collection">{$lang.or} <a role="button" href="#modal-collection" data-toggle="modal">{$lang.create_collection}</a></p>

                    <input type="hidden" name="add_collection" value="yes" />
                    <button type="submit" class="btn">
                        <i class="hd-bookmark"></i>
                        {$lang.bookmark_this}
                    </button>
                {else}
                    <a id="premiere-collection" class="btn btn btn-big-shadow" role="button" href="#modal-collection" data-toggle="modal">{$lang.create_collection}</a>
                {/if}
            </form>
        </div>
    </div>
{/if}

<div id="menu-page">
    {include file="$root_path/applications/members/templates/tabsy.tpl"}
    <div id="menu-haut" class="menu-collections container">
        <ul>
            <li class="active"><a class="collections-list" href="javascript:;">{$lang.public_collections}</a></li>
            <li><a class="collections-list" href="javascript:;">{$lang.private_collections}</a></li>
        </ul>
    </div>
</div>

<section id="conteneur" class="conteneur-collections">
    <div class="container">
        <div class="row">
            
            <div id="content-collections" class="span12">
                <ul>
                    <li>
                        <div class="tab-content active">
                            <div id="collections-publiques">
                                <h2>{$lang.your_public_collections}</h2>
                                {if $collections.true}
                                    <div class="row">
                                        {foreach from=$collections.true item=c name=foo}
                                            <div class="collection-case span3">
                                                <a href="/collections/view/{$c.id}/{$c.name|url}" class="collection-image">
                                                    {if $c.photo != ''}
                                                        <img alt="{$c.name|escape}" height="200" src="{$data_server}/uploads/collections/{$c.photo}" width="370" />
                                                    {else}
                                                        <img alt="{$c.name|escape}" height="200" src="/static/images/collection-default.png" width="370" />
                                                    {/if}
                                                </a>

                                                <div class="collection-info">
                                                    <h3><a href="/collections/view/{$c.id}/{$c.name|url}">{$c.name}</a></h3>
                                                    <span>
                                                        <a href="/member/{$members[$c.member_id].username}" class="auteur">{$lang.by} {$members[$c.member_id].username}</a>
                                                    </span>
                                                </div>

                                                <div class="collection-note">
                                                    {section name=foo start=1 loop=6 step=1}
                                                        {if $c.rating >= $smarty.section.foo.index}
                                                            <i class="hd-star on"></i>
                                                        {else}
                                                            <i class="hd-star off"></i>
                                                        {/if}
                                                    {/section}
                                                    <br /><small>{$c.votes} {$lang.ratings|@lower}</small>
                                                </div>
                                            </div>
                                        {/foreach}
                                    </div>
                                {else}
                                    <p>{$lang.dont_have_public_collection}.</p>
                                    <p>{$lang.public_collections_description}</p>
                                {/if}
                            </div>
                        </div>
                    </li>
                    <li>
                        <div id="privees" class="tab-content active">
                            <div id="collections-privees">
                                <h2>{$lang.your_private_collections}</h2>
                                {if isset($collections.false)}
                                    <ul class="collection-list">
                                        {foreach from=$collections.false item=c name=foo}
                                            <div class="source">
                                                <a href="/collections/view/{$c.id}/{$c.name|url}" class="collection-image">
                                                    {if $c.photo != ''}
                                                        <img alt="{$c.name|escape}" height="200" src="{$data_server}/uploads/collections/{$c.photo}" width="370" />
                                                    {else}
                                                        <img alt="{$c.name|escape}" height="200" src="/static/img/default-collection.jpg" width="370" />
                                                    {/if}
                                                </a>

                                                <div class="collection-info">
                                                    <h3><a href="/collections/view/{$c.id}/{$c.name|url}">{$c.name}</a></h3>
                                                    {if $c.text != ''}
                                                        <p>{$c.text|nl2br}</p>  
                                                    {else}
                                                        <p>{$lang.no_description_collection}</p>
                                                    {/if}
                                                </div>

                                                <div class="collection-meta">
                                                    {$c.products} {$lang.products}<br />
                                                </div>
                                            </div>
                                        {/foreach}
                                    </ul>
                                {else}
                                    <p>{$lang.dont_have_private_collection}.</p>
                                {/if}
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>