<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$title}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m=quiz&amp;c=list">{$lang.quiz}</a> \
                <a href="?m={$smarty.get.m}&amp;c=answers&amp;id={$smarty.get.id}&amp;p={$smarty.get.p}">{$lang.answers}</a> \
                <a href="?m={$smarty.get.m}&amp;c=answers&amp;id={$smarty.get.id}&amp;p={$smarty.get.p}">{$pdata.name}</a> \
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
        <form action="" action="" method="post" enctype="multipart/form-data">
            <div class="row-input">
                <label>{$lang.name}</label>
                <div class="inputs">
                    <input type="text" name="name" id="name" required="true" value="{$smarty.post.name|escape}" />
                    {if isset($error.name)}
                        <small>{$error.name}</small>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.right}</label>
                <div class="inputs">
                    <input type="checkbox" name="right" value="true" class="checkbox" {if $smarty.post.right == 'true'}checked="checked"{/if} /> {$lang.yes}
                </div>
            </div> 

            <div class="form-submit">
                {if $smarty.get.c=='editAnswer'}
                    <button type="submit" name="edit" class="btn btn-big-shadow" style="width: 250px; height: 45px;">{$lang.edit}</button>
                {else}
                    <button type="submit" name="add" class="btn btn-big-shadow" style="width: 250px; height: 45px;">{$lang.add}</button>
                {/if}
            </div>
        </form>
    </div>
</section>