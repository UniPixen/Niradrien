<section class="titre-profil admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h1 class="page-title">
                    {if $smarty.get.c == 'edit'}
                        {$lang.edit_payment_rates}
                    {else}
                        {$lang.add_payment_rate}
                    {/if}
                </h1>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list&amp;p={$smarty.get.p}">{$lang.payment_rates}</a> \ 
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
        <form action="" action="" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label class="inputs-label">{$lang.percent}</label>
                <div class="inputs">
                    <input type="text" name="percent" required="true" value="{$smarty.post.percent|escape}" />
                    {if isset($error.percent)}
                        <small>{$error.percent}</small>
                    {/if}
                </div>
            </div>

            <div class="input-group">
                <label class="inputs-label">{$lang.from}</label>
                <div class="inputs">
                    <input type="text" name="from" value="{$smarty.post.from|escape}" required="true" />
                    {if isset($error.from)}
                        <small>{$error.from}</small>
                    {/if}
                </div>
            </div>

            <div class="input-group">
                <label class="inputs-label">{$lang.to}</label>
                <div class="inputs">
                    <input type="text" name="to" value="{$smarty.post.to|escape}" required="true" />
                    {if isset($error.to)}
                        <small>{$error.to}</small>
                    {/if}
                </div>
            </div>

            <div class="form-submit">
                {if $smarty.get.c=='edit'}
                    <button type="submit" name="edit" class="btn btn-big-shadow" style="height: 45px; width: 200px;">{$lang.edit}</button>
                {else}
                    <button type="submit" name="add" class="btn btn-big-shadow" style="height: 45px; width: 200px;">{$lang.add}</button>
                {/if}
            </div>
        </form>
    </div>
</section>