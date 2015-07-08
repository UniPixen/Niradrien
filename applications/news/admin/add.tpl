<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span9" id="titre">
                <h1 class="page-title">
                    {$title}
                    <span>{$smarty.post.name|escape}</span>
                </h1>
            </div>
            <div class="span3" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list&amp;p={$smarty.get.p}">{$lang.news}</a> \
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
        <form action="" action="" method="post" enctype="multipart/form-data">
            <div class="row-input">
                <label for="name">{$lang.name}</label>
                <div class="inputs">
                    <input type="text" name="name" required="true" value="{$smarty.post.name|escape}" />
                    {if isset($error.name)}
                        <span class="validate_error">{$error.name}</span>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label for="description">{$lang.description}</label>
                <div class="inputs">
                    <input type="text" name="description" required="true" value="{$smarty.post.description|escape}" />
                    {if isset($error.description)}
                        <small>{$error.description}</small>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label for="news_url">URL</label>
                <div class="inputs">
                    <input type="text" name="url" required="true" value="{$smarty.post.url|escape}" />
                </div>
            </div>

            <div class="row-input">
                <label for="news_url">{$lang.photo}</label>
                <div class="inputs">
                    <input type="file" name="photo" />
                    {if $smarty.post.photo != ''}
                        <br />
                        <img src="{$data_server}uploads/news/260x140/{$smarty.post.photo}" alt="" />
                        <input type="checkbox" name="deletePhoto" value="yes" />
                        {$lang.delete}
                    {/if}
                    {if isset($error.photo)}
                        <small>{$error.photo}</small>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label for="visible">{$lang.visible}</label>
                <div class="inputs">
                    <input type="checkbox" name="visible" value="true" class="checkbox" {if $smarty.post.visible == 'true'}checked="checked"{/if} />
                    {$lang.yes}
                </div>
            </div>

            <div class="form-submit">
                {if $smarty.get.c=='edit'}
                    <button type="submit" name="edit" class="btn btn-big-shadow" style="height: 45px; width: 200px;">{$lang.edit}</button>
                {else}
                    <button type="submit" name="add" class="btn btn-big-shadow" style="height: 45px; width: 200px;">{$lang.add}</button>
                {/if}
            </div>
        </form>
    </div>
</section>