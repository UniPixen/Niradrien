<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$title}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m=attributes&amp;c=list">{$lang.attributes}</a> \
                <a href="?m={$smarty.get.m}&amp;c=attr&amp;id={$smarty.get.id}&amp;p={$smarty.get.p}">{$pdata.name}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
    <div class="container">
        <form action="" action="" method="post" enctype="multipart/form-data">
            <div class="row-input">
                <label>{$lang.name}</label>
                <div class="inputs">
                    <input type="text" name="name" required="true" value="{$smarty.post.name|escape}" />
                    {if isset($error.name)}
                        <small>{$error.name}</small>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.name} {$lang.english}</label>
                <div class="inputs">
                    <input type="text" name="name_en" required="true" value="{$smarty.post.name_en|escape}" />
                    {if isset($error.name_en)}
                        <small>{$error.name_en}</small>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.tooltip}</label>
                <div class="inputs">
                    <input type="text" name="tooltip" value="{$smarty.post.tooltip|escape}" />
                    {if isset($error.tooltip)}
                        <small>{$error.tooltip}</small>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.photo}</label>
                <div class="inputs">
                    <input type="file" name="photo" />
                    {if isset($smarty.post.photo) && !is_null($smarty.post.photo)}
                        <br /><br />
                        <img src="{$data_server}/uploads/attributes/{$smarty.post.photo}" alt="" />
                        <input type="checkbox" name="deletePhoto" value="yes" />
                        {$lang.delete}
                    {/if}
                    {if isset($error.photo)}
                        <small>{$error.photo}</small>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.visible}</label>
                <div class="inputs">
                    <input type="checkbox" name="visible" value="true" class="checkbox" {if $smarty.post.visible == 'true'}checked="checked"{/if} />
                    {$lang.yes}
                </div>
            </div>

            <div class="form-submit">
                {if $smarty.get.c == 'editAttr'}
                    <button type="submit" name="edit" class="btn btn-big-shadow" style="height: 45px; width: 200px;">{$lang.edit}</button>
                {else}
                    <button type="submit" name="add" class="btn btn-big-shadow" style="height: 45px; width: 200px;">{$lang.add}</button>
                {/if}
            </div>
        </form>
    </div>
</section>