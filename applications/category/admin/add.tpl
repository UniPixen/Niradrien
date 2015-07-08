<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h1 class="page-title">{$title}</h1>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
                {if isset($pdata)}
                    <a href="?m={$smarty.get.m}&amp;c=list">{$lang.categories}</a> \
                    <a href="?m={$smarty.get.m}&c=list&sub_of={$smarty.get.sub_of}&amp;p={$smarty.get.p}">{$pdata.name}</a> \
                    <a href="#">{$smarty.post.name|escape}</a>
                {else}
                    <a href="?m={$smarty.get.m}&amp;c=list">{$lang.categories}</a> \
                {/if}
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
        <form action="" action="" method="post" enctype="multipart/form-data">
            {if $smarty.get.c == 'edit'}
                <div class="row-input">
                    <label for="name">{$lang.subcategory_of}</label>
                    <div class="inputs">
                        <select name="sub_of">
                            <option value="0">{$lang.none}</option>
                            {$select}
                        </select>
                        <input type="hidden" name="sub_of_old" value="{if isset($smarty.post.sub_of_old)}{$smarty.post.sub_of_old}{else}{$smarty.post.sub_of}{/if}" />
                        {if isset($error.sub)}
                            <small>{$error.sub}</small>
                        {/if}
                    </div>
                </div>
            {/if}

            <div class="row-input">
                <label for="name">
                    <img alt="fr" src="{$data_server}uploads/languages/fr.svg" style="height: 20px;" />
                    {$lang.name} {$lang.french}
                </label>
                <div class="inputs">
                    <input id="name" type="text" name="name" value="{$smarty.post.name|escape}" />
                    {if isset($error.name)}
                        <small>{$error.name}</small>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label for="name_en">
                    <img alt="gb" src="{$data_server}uploads/languages/gb.svg" style="height: 20px;" style="height: 20px;" /> {$lang.name} {$lang.english}
                </label>
                <div class="inputs">
                    <input id="name_en" type="text" name="name_en" value="{$smarty.post.name_en|escape}" />
                </div>
            </div>

            <div class="row-input">
                <label for="title">{$lang.title}</label>
                <div class="inputs">
                    <input id="title" type="text" name="title" value="{$smarty.post.title|escape}" />
                </div>
            </div>

            <div class="row-input">
                <label for="keywords">{$lang.keywords}</label>
                <div class="inputs">
                    <input type="text" id="keywords" name="keywords" value="{$smarty.post.keywords|escape}" />
                </div>
            </div>

            <div class="row-input">
                <label for="description"><img alt="fr" src="{$data_server}uploads/languages/fr.svg" style="height: 20px;" /> {$lang.description} {$lang.french}</label>
                <div class="inputs">
                    <input type="text" id="description" name="description" value="{$smarty.post.description|escape}" />
                </div>
            </div>

            <div class="row-input">
                <label for="description"><img alt="gb" src="{$data_server}uploads/languages/gb.svg" style="height: 20px;" /> {$lang.description} {$lang.english}</label>
                <div class="inputs">
                    <input type="text" id="description_en" name="description_en" value="{$smarty.post.description_en|escape}" />
                </div>
            </div>

            <div class="row-input">
                <label for="visible">{$lang.visible}</label>
                <div class="inputs">
                    <input type="checkbox" name="visible" value="true" id="visible" class="checkbox" {if $smarty.post.visible == 'true'}checked="checked"{/if} /> {$lang.yes}
                </div>
            </div>

            <div class="form-submit" style="margin-top: 30px;">
                {if $smarty.get.c == 'edit'}
                    <button type="submit" name="edit" value="edit" class="btn btn-big-shadow" style="height: 45px; width: 200px;">{$lang.edit}</button>
                {else}
                    <button type="submit" name="add" value="edit" class="btn btn-big-shadow" style="height: 45px; width: 200px;">{$lang.add}</button>
                {/if}
            </div>
        </form>
    </div>
</section>