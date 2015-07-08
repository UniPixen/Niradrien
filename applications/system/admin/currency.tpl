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

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
        <form action="" method="post">
            <div class="row-input">
                <label for="currency">{$lang.currency}</label>
                <div class="inputs">
                    <select id="currency" name="code">
                        {if $data}
                            {foreach from=$data item=c}
                                <option value="{$c.code}" {if $c.active == 'yes'}selected="selected"{/if}>{$c.name}</option>
                            {/foreach}
                        {/if}
                    </select>
                </div>
            </div>

            <div class="form-submit">
                <button type="submit" name="save" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.save}</button>
            </div>
        </form>
    </div>
</section>