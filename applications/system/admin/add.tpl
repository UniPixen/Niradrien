<section class="titre-profil admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h1 class="page-title">{$title}</h1>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list">{$lang.settings}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
    <div class="container">
        <form action="" method="post">
            <label for="edit">{$smarty.post.help_text}</label>
            <input type="text" name="value" value="{$smarty.post.value|escape}" required />
            <input type="hidden" name="name" value="{$smarty.post.name|escape}" />

            {if isset($error.name)}
                <small>{$error.name}<small>
            {/if}

            <div style="margin-top: 30px;">
                {if $smarty.get.c == 'edit'}
                    <button class="btn btn-big-shadow" type="submit" name="edit" style="height: 45px; width: 170px;">
                        <i class="hd-pen"></i>
                        {$lang.edit}
                    </button>
                {else}
                    <button class="btn btn-big-shadow" type="submit" name="add" style="height: 45px; width: 170px;">
                        <i class="hd-plus-square"></i>
                        {$lang.add}
                    </button>
                {/if}
            </div>
        </form>
    </div>
</section>