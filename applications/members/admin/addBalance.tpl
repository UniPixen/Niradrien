<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span8" id="titre">
                <h2 class="page-title">{$title}</h2>
            </div>
            <div class="span4" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
                <a href="/admin/?m=members&amp;c=list">{$lang.users}</a> \
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
        <form action="" method="post">
            <div class="row-input">
                <label for="balance">{$lang.amount}</label>
                <div class="inputs">
                    <input id="balance" type="text" name="balance" value="" required="true" />
                    {if isset($error.balance)}
                        <small>{$error.balance}</small>
                    {/if}
                </div>
            </div>
            
            {if $smarty.get.c == 'editBalance'}
                <button type="submit" name="edit" class="btn btn-big-shadow" style="width: 250px; height: 45px;">{$lang.edit}</button>
            {else}
                <button type="submit" name="add" class="btn btn-big-shadow" style="width: 250px; height: 45px;">{$lang.add}</button>
            {/if}
        </form>
    </div>
</section>