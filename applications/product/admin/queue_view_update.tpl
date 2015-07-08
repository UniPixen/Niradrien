<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span5" id="titre">
                <h1 class="page-title">{$product.name}</h1>
            </div>
            <div class="span7" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list">{$lang.products}</a> \
                <a href="/admin/?m=products&amp;c=queue_update">Queue Update</a> \
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
        <form action="/" method="post">
            <div class="row-input">
                <label>{$lang.seller}</label>
                <div class="right">
                    <a href="/admin/?m=members&amp;c=edit&amp;id={$product.member.member_id}" target="_blank">{$product.member.username}</a>
                </div>
            </div>

            {if $data.thumbnail != ''}
                <div class="row-input">
                    <label>{$lang.thumbnail}</label>
                    <div class="inputs">
                        <img src="{$data_server}uploads/products/{$product.id}/temp/{$data.thumbnail}" alt="" />
                    </div>
                </div>
            {/if}

            {if $data.theme_preview != ''}
                <div class="row-input">
                    <label>{$lang.theme_preview}</label>
                    <div class="right">
                        <a href="{$data_server}/uploads/products/{$product.id}/temp/{$data.theme_preview}">{$data.theme_preview}</a> 
                    </div>
                </div>
            {/if}

            {if $data.main_file != ''}
                <div class="row-input">
                    <label>{$lang.main_file}</label>
                    <div class="right">
                        <a href="{$data_server}/uploads/products/{$product.id}/temp/{$data.main_file}">{$data.main_file}</a>
                    </div>
                </div>
            {/if}

            <div class="row-input">
                <label>{$lang.tags}</label>
                <div class="right">
                    {foreach from=$data.tags item=t}{$t}, {/foreach}	
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.comment}</label>
                <div class="right">
                    {$data.reviewer_comment}	
                </div>
            </div>

            <div class="form-submit">
                <button type="button" onclick="showFields('approve');"class="btn-icon upload">{$lang.approve}</button>
                <button type="button" onclick="showFields('delete');" class="btn-icon submit">{$lang.remove_product_update}</button>
            </div>

            <div class="row-input" id="approve_product" {if $smarty.post.action != 'approve'}style="display: none;"{/if}>
                <label for="newprice">{$lang.new_price}</label>
                <div class="right" style="margin-left:-100px">
                    <input type="text" id="newprice" class="title" style="width:182px" value="{$product.price|escape}" name="price" ><br />
                    {$lang.old_price} : {$product.price|string_format:"%.0f"} &euro;
                </div>
            </div>
            <input id="product_action" type="hidden" name="action" value="{$smarty.post.action}" />
            
            <div class="row-input" id="unapprove_product" {if $smarty.post.action != 'delete'}style="display: none;"{/if}>
                <label for="comm">{$lang.comment}</label>
                <div class="right" style="margin-left:-100px">
                    <textarea id="comm" name="comment_to_member"></textarea>		
                </div>
            </div>

            <p id="submit_form" {if $smarty.post.action == ''}style="display: none;"{/if}>
                <input type="submit" name="submit" value="{$lang.save}" onclick="return confirm('{$lang.are_you_sure_submit}');" class="btn btn-big-shadow" style="height: 45px; width: 270px;" />
            </p>	
        </form>
    </div>
</section>