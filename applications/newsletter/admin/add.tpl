<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h2 class="page-title">{$title}</h2>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list&amp;p={$smarty.get.p}">{$lang.newsletter}</a> \
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
                    <input id="name" type="text" name="name" value="" required="true" />
                    {if isset($error.name)}
                        <small>{$error.name}</small>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label for="desc">{$lang.message}</label>
                <div class="inputs">
                    <textarea id="text" name="text"></textarea>
                    {if isset($error.text)}
                        <small>{$error.text}</small>
                    {/if}
                </div>
            </div>

            <input type="hidden" name="send_to" value="site" />
            <div class="form-submit">
                <button type="submit" name="add" class="btn btn-big-shadow" style="width: 250px; height: 45px;">{$lang.send}</button>
            </div>
        </form>
    </div>
</section>