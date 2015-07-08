<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h2 class="page-title">{$title}</h2>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m=system&amp;c=list">{$lang.settings}</a> \
                <a href="?m=payments&amp;c=list">{$lang.payments}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
    <div class="container">
        <form action="" action="" method="post" enctype="multipart/form-data">
            <div class="row-input">
                <label>{$lang.status}</label>
                <div class="inputs">
                    <select name="form[{$group}_status]">
                        {foreach from=$statuses item=s key=k}
                            {if $k == $status}
                                <option selected="selected" value="{$k}">{$s}</option>
                            {else}
                                <option value="{$k}">{$s}</option>
                            {/if}
                        {/foreach}
                    </select>
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.paypal_sandbox_mode}</label>
                <div class="inputs">
                    <select name="form[{$group}_sandbox_mode]">
                        {if $sandbox_mode}
                            <option selected="selected" value="1">{$lang.yes}</option>
                            <option value="0">{$lang.no}</option>
                        {else}
                            <option value="1">{$lang.yes}</option>
                            <option selected="selected" value="0">{$lang.no}</option>
                        {/if}
                    </select>
                </div>
            </div>

            <div class="row-input">
                <label for="email" >{$lang.email}</label>
                <div class="inputs">
                    <input type="text" name="form[{$group}_email]" value="{$email|escape}" />
                </div>
            </div>

            <div class="row-input">
                <label for="sort_order">{$lang.sort_order}</label>
                <div class="inputs">
                    <input type="text" name="form[{$group}_sort_order]" value="{$sort_order|escape}" />
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.logo}</label>
                <div class="inputs">
                    <input type="file" name="photo" />
                    {if $logo != ''}
                        <br />
                        <img src="{$data_server}uploads/{$smarty.get.m}/{$logo}" alt="{$lang.logo} PayPal" style="width: 300px;" />
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label for="value">{$lang.paypal_pdt_token}</label>
                <div class="inputs">
                    <input type="text" name="form[{$group}_pdt_token]" value="{$pdt_token|escape}" /><br />
                    <small>{$lang.paypal_pdt_token_help}<small>
                </div>
            </div>

            <div class="form-submit">
                <button type="submit" name="edit" class="btn btn-big-shadow" style="height: 45px;">{$lang.edit}</button>
            </div>
        </form>
    </div>
</section>