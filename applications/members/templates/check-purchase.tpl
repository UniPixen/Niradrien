<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.check_purchase}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
                <a href="/members/{$smarty.session.member.username}/">{$lang.my_account}</a> \
            </div>
        </div>
    </div>
</section>

<div id="commission">
    <div id="commission-container">
        <div class="btn btn btn-big-shadow btn-grey">
            {$lang.commission} : {$commission.percent} &#37;
        </div>
    </div>
</div>

<div id="menu-page">
    {include file="$root_path/applications/members/templates/tabsy.tpl"}
    {include file="$root_path/applications/members/templates/author-tab.tpl"}
</div>

<section id="purchase-check">
    <div class="container">
        <div class="row">
            <div class="span12">
                <h2>{$lang.download_key}</h2>
                <form method="post" action="{$smarty.server.REQUEST_URI|escape}" id="purchase-check-form">
                    <input type="text" value="" placeholder="Ex : ‘0d8246bf-03fb-eb6d-62f40ce9’" name="key" id="purchase-key" />
                    <button type="submit" id="submit-key">
                        <i class="hd-search"></i>
                    </button>
                    <input type="submit" class="btn btn-big-shadow" value="{$lang.check}" />
                </form>
            </div>
        </div>
    </div>
</section>

{if $smarty.post.key}
    <section id="purchase-exist">
        <div class="container">
            <div class="row">
                <div class="span12">
                    {if $purchased}
                        <i class="purchase-exist-icon hd-check"></i>
                        <h2>{$lang.existing_purchase}</h2>
                        {foreach from=$product item=i name=foo}
                            <div id="order-container">
                                <table id="order-table">
                                    <tr>
                                        <td>{$lang.order_id}</td>
                                        <td>#{$i.id}</td>
                                    </tr>
                                    <tr>
                                        <td>{$lang.payment_date}</td>
                                        <td>{$i.paid_datetime|date_format:"%e %B %Y à %Hh%M"}</td>
                                    </tr>
                                    <tr>
                                        <td>{$lang.product_id}</td>
                                        <td>{$i.product_id}</td>
                                    </tr>
                                    <tr>
                                        <td>{$lang.product_name}</td>
                                        <td><a href="/product/{$i.product_id}/{$i.product_name|url}">{$i.product_name}</a></td>
                                    </tr>
                                    <tr>
                                        <td>{$lang.purchased}</td>
                                        <td><a href="/member/{$members[$i.buyer_id].username}">{$members[$i.buyer_id].username}</a></td>
                                    </tr>
                                    <tr>
                                        <td>{$lang.licence_type}</td>
                                        <td>
                                            {if {$i.extended} == 'true'}
                                                {$lang.extended_licence}
                                            {else}
                                                {$lang.regular_licence}
                                            {/if}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{$lang.downloaded}</td>
                                        <td>
                                            {if {$i.downloaded} == 'true'}
                                                <i class="hd-check"></i>
                                            {else}
                                                <i class="hd-cross"></i>
                                            {/if}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{$lang.paid_price}</td>
                                        <td>{$i.paid_price|string_format:"%.2f"} {$currency.symbol}</td>
                                    </tr>
                                    <tr>
                                        <td>{$lang.purchase_key}</td>
                                        <td>{$i.code_achat}</td>
                                    </tr>
                                </table>
                            </div>
                        {/foreach}
                    {else}
                        <i class="purchase-exist-icon hd-cross"></i>
                        <h2>{$lang.unknow_key}</h2>
                        <p>{$lang.unknow_key_text}</p>
                    {/if}
                </div>
            </div>
        </div>
    </section>
{/if}