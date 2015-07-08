<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$title}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list&amp;p={$smarty.get.p}">{$lang.languages}</a> \ 
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
    <div class="container">
        <div class="row">
            <div class="span9">
                <form action="" action="" method="post" enctype="multipart/form-data">
                    <div class="row-input">
                        <label for="name">{$lang.name}</label>
                        <div class="inputs">
                            <input id="name" type="text" name="name" value="{$smarty.post.name|escape}" />
                            {if isset($error.name)}
                                <small>{$error.name}</small>
                            {/if}
                        </div>
                    </div>

                    <div class="row-input">
                        <label for="visible">{$lang.code}</label>
                        <div class="inputs">
                            <input type="text" name="code" value="{$smarty.post.code|escape}" />
                        </div>
                    </div>

                    <div class="row-input">
                        <label for="visible">Locale</label>
                        <div class="inputs">
                            <input type="text" name="locale" value="{$smarty.post.locale|escape}" />
                        </div>
                    </div>

                    <div class="row-input">
                        <label for="visible">Locale Territory</label>
                        <div class="inputs">
                            <input type="text" name="locale_territory" value="{$smarty.post.locale_territory|escape}" />
                        </div>
                    </div>

                    <div class="row-input">
                        <label>{$lang.flag}</label>
                        <div class="inputs">
                            <input type="file" name="flag" />
                            {if $smarty.post.flag != ''}
                                <img src="{$data_server}uploads/languages/{$smarty.post.flag}" alt="" />
                                <input type="checkbox" name="deleteFlag" value="yes" />
                                {$lang.delete}
                            {/if}
                            {if isset($error.flag)}
                                <small>{$error.flag}</small>
                            {/if}
                        </div>
                    </div>

                    <div class="row-input">
                        <label for="visible">{$lang.visible}</label>
                        <div class="inputs">
                            <input type="checkbox" id="visible" name="visible" value="1" class="checkbox" {if $smarty.post.visible == '1'}checked="checked"{/if} />
                            {$lang.yes}
                        </div>
                    </div>

                    {if $smarty.get.c=='edit'}
                        <button type="submit" name="edit" class="btn btn-big-shadow btn-middle" style="margin-top: 30px;">{$lang.edit}</button>
                    {else}
                        <button type="submit" name="add" class="btn btn-big-shadow btn-middle" style="margin-top: 30px;">{$lang.add}</button>
                    {/if}
                </form>
            </div>
        </div>
    </div>
</section>