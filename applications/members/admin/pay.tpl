<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h2 class="page-title">{$lang.payout}</h2>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
                <a href="/admin/?m=members&amp;c=list">{$lang.members}</a> \
                <a href="?m={$smarty.get.m}&amp;c=withdraws">{$lang.payout_requests}</a>
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row-input">
                <label>{$lang.user}</label>
                <div class="right">
                    <a href="/admin/?m=members&c=edit&id={$member.member_id}" title="" target="_blank">{$member.username}</a>
                </div>
            </div>
            <div class="row-input">
                <label>{$lang.payment_method}</label>
                <div class="right">
                    {$data.method}
                </div>
            </div>
            <div class="row-input">
                <label>{$lang.french_resident}</label>
                <div class="right">
                    {if $data.french != 'false'}
                        {if $data.french == 'iam'}
                            {$lang.i_am_author}
                        {else}
                            {$lang.i_am_not_author}
                            <br /><br />
                            <strong>ABN:</strong> {$data.abn}<br />
                            <strong>ACN:</strong> {$data.acn}
                        {/if}
                    {else}
                        <img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
                    {/if}
                </div>
            </div>
            <div class="row-input">
                <label>{$lang.email}</label>
                <div class="right">
                    {$data.payment_email}
                </div>
            </div>
            <div class="row-input">
                <label>{$lang.date}</label>
                <div class="right">
                    {$data.datetime|date_format:"%d %B %Y"}
                </div>
            </div>
            <div class="row-input">
                <label>{$lang.paid}</label>
                <div class="right">
                    {if $data.paid == 'true'}
                        <img src="{$data_server}admin/images/icons/24x24/accept.png" alt="" />
                    {else}
                        <img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
                    {/if}
                </div>
            </div>
            <div class="row-input">
                <label>{$lang.amount}</label>
                <div class="right">
                    {if $data.paid == 'true'}
                        {$data.amount|string_format:"%.2f"} {$currency.symbol}
                    {else}
                        {if !is_numeric($data.amount)}
                            {$data.amount}
                        {else}
                            {$data.amount|string_format:"%.2f"} {$currency.symbol}
                        {/if}
                    {/if}
                </div>
            </div>
            <div class="row-input">
                <label>{$lang.current_balance}</label>
                <div class="right">
                    {$member.earning|string_format:"%.2f"} {$currency.symbol}
                </div>
            </div>
            <div class="row-input">
                <label for="payout">{$lang.payout}</label>
                <div class="inputs">
                    <input type="text" id="payout" name="payout" value="{if isset($smarty.post.payout)}{$smarty.post.payout|escape}{elseif !is_numeric($data.amount)}{$member.earning|string_format:"%.2f"}{else}{$data.amount|string_format:"%.2f"}{/if}" />
                </div>
            </div>
            <div class="form-submit">
                <button type="submit" name="edit" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.payout}</button>
            </div>
        </form>
    </div>
</section>