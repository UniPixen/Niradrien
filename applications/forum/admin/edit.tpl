<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h1 class="page-title">{$lang.edit_subject} <span>{$data.name}</span></h1>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
                <a href="/admin/?m=forum&amp;c=list">{$lang.forum}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
    <div class="container">
        <form action="" method="post">
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
                <label>{$lang.visible}</label>
                <div class="inputs">
                    <input type="checkbox" name="visible" value="true" class="checkbox" {if $smarty.post.visible == 'true'}checked="checked"{/if} />
                    {$lang.yes}
                </div>
            </div>

            <div class="form-submit">
                <button type="submit" name="edit" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.edit}</button>
            </div>
        </form>
    </div>
</section>