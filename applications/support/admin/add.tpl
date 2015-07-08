<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h2 class="page-title">{$title}</h2>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
            <a href="?m={$smarty.get.m}&amp;c=list&amp;p={$smarty.get.p}">{$lang.support}</a> \
            <a href="?m={$smarty.get.m}&amp;c=category&amp;p={$smarty.get.p}">{$lang.categories}</a> \
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
                    <input type="text" name="name" value="{$smarty.post.name|escape}" required="true" />
                    {if isset($error.name)}
                        <small>{$error.name}</small>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label for="name">{$lang.english_en}</label>
                <div class="inputs">
                    <input type="text" name="name_en" value="{$smarty.post.name_en|escape}" required="true" />
                    {if isset($error.name_en)}
                        <small>{$error.name_en}</small>
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
                {if $smarty.get.c == 'edit'}
                    <button type="submit" name="edit" class="btn btn-big-shadow" style="width: 250px; height: 45px;">{$lang.edit}</button>
                {else}
                    <button type="submit" name="add" class="btn btn-big-shadow" style="width: 250px; height: 45px;">{$lang.add}</button>
                {/if}
            </div>
        </form>
    </div>
</section>