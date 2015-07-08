<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h1 class="page-title">{$title} {$smarty.post.name|escape}</h1>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list">{$lang.tags}</a> \
            </div>
        </div>
    </div>
</section>

<div id="content">
    <div class="container">
        <form action="" method="post">
            <div class="row-input">
                <label for="tag">Tag</label>
                <div class="inputs">
                    <input id="tag" type="text" name="name" value="{$smarty.post.name|escape}" required />
                    {if isset($error.name)}
                        <small>{$error.name}</small>
                    {/if}
                </div>
            </div>
            <div class="form-submit">
                {if $smarty.get.c=='edit'}
                    <button id="personal_info_submit_button" type="submit" name="edit" >{$lang.edit}</button>
                {else}
                    <button id="personal_info_submit_button" type="submit" name="add" >{$lang.add}</button>
                {/if}
            </div>
        </form>
    </div>
</div>