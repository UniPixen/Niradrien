<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h1 class="page-title">{$title}</h1>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
                {if isset($pdata)}
                    <a href="?m={$smarty.get.m}&amp;c=list">{$lang.pages}</a> \
                    <a href="?m={$smarty.get.m}&amp;c=list&amp;sub_of={$smarty.get.sub_of}&amp;p={$smarty.get.p}">{$pdata.name}</a> \
                    <a href="#">{$smarty.post.name|escape}</a>
                {else}
                    <a href="?m={$smarty.get.m}&amp;c=list">{$lang.pages}</a> \
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
                    <label for="name">{$lang.subpage_of}</label>
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
                <label for="url">{$lang.url}</label>
                <div class="inputs">
                    <input id="url" type="text" name="key" value="" required />
                    {if isset($error.key)}
                        <small>{$error.key}</small>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label for="name">{$lang.name}</label>
                <div class="inputs">
                    <input id="name" type="text" name="name" value="" required />
                    {if isset($error.name)}
                        <small>{$error.name}</small>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label for="title">{$lang.title}</label>
                <div class="inputs">
                    <input id="title" type="text" name="title" value="" />
                </div>
            </div>

            <div class="row-input">
                <label for="keywords">{$lang.keywords}</label>
                <div class="inputs">
                    <input type="text" id="keywords" name="keywords" value="" />
                </div>
            </div>

            <div class="row-input">
                <label for="description">{$lang.description}</label>
                <div class="inputs">
                    <input type="text" id="description" name="description" value="" />
                </div>
            </div>

            <div class="row-input">
                <label for="desc">{$lang.message}</label>
                <div class="inputs">
                    <textarea name="text" id="desc"></textarea>
                </div>
            </div>

            <div class="row-input">
                <label for="in_menu">{$lang.in_menu}</label>
                <div class="inputs">
                    <input type="checkbox" name="menu" value="true" id="in_menu" class="checkbox" {if $smarty.post.menu == 'true'}checked="checked"{/if} /> {$lang.yes}
                </div>
            </div>

            <div class="row-input">
                <label for="visible">{$lang.visible}</label>
                <div class="inputs">
                    <input type="checkbox" name="visible" value="true" id="visible" class="checkbox" {if $smarty.post.visible == 'true'}checked="checked"{/if} /> {$lang.yes}
                </div>
            </div>

            <div class="form-submit">
                {if $smarty.get.c == 'edit'}
                    <button type="submit" name="edit" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.edit}</button>
                {else}
                    <button type="submit" name="add" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.add}</button>
                {/if}
            </div>
        </form>
    </div>
</section>