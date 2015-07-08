<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h2 class="page-title">{$title}</h2>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list&amp;p={$smarty.get.p}">{$lang.countries}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row-input">
                <label for="name">{$lang.name} {$lang.french}</label>
                <div class="inputs">
                    <input type="text" name="name" value="{$smarty.post.name}" />
                    {if isset($error.name)}
                        <small>{$error.name}</small>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label for="name">{$lang.name} {$lang.english}</label>
                <div class="inputs">
                    <input type="text" name="name_en" value="{$smarty.post.name_en}" />
                    {if isset($error.name_en)}
                        <small>{$error.name_en}</small>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.photo}</label>
                <div class="inputs">
                    {if $smarty.get.c == 'edit'}
                        <img src="{$data_server}uploads/countries/{$smarty.post.photo}" alt="" style="height: 40px;" />
                    {/if}
                    <input type="file" name="photo" />
                    {if isset($error.photo)}
                        <small>{$error.photo}</small>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label for="visible">{$lang.europe}</label>
                <div class="inputs">
                    <input type="checkbox" name="europe" value="true" class="checkbox" {if $smarty.post.europe == 'true'}checked="checked"{/if} /> {$lang.yes}
                </div>
            </div>

            <div class="row-input">
                <label for="visible">{$lang.visible}</label>
                <div class="inputs">
                    <input type="checkbox" name="visible" value="true" class="checkbox" {if $smarty.post.visible == 'true'}checked="checked"{/if} /> {$lang.yes}
                </div>
            </div>

            <div class="form-submit">
                {if $smarty.get.c == 'edit'}
                    <button type="submit" name="edit">{$lang.edit}</button>
                {else}
                    <button type="submit" name="add">{$lang.add}</button>
                {/if}
            </div>
        </form>
    </div>
</section>