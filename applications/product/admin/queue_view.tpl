<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$data.name}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="/admin/?m=products&amp;c=list">{$lang.products}</a> \
                <a href="?m={$smarty.get.m}&amp;c=queue&amp;p={$smarty.get.p}">{$lang.queue}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
    <div class="container">
        <form action="" method="post">
            <div class="row-input">
                <label>{$lang.seller}</label>
                <div class="right">
                    <a href="/admin/?m=members&c=edit&id={$data.member.member_id}" title="" target="_blank">
                        <img src="{$data_server}uploads/members/{$data.member.member_id}/{$data.member.avatar}" alt="{$data.member.username}" />
                        {$data.member.username}
                    </a>
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.thumbnail}</label>
                <div class="inputs">
                    <img src="{$data_server}uploads/products/{$smarty.get.id}/{$data.thumbnail}" alt="" />
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.preview_screenshots}</label>
                <div class="inputs">
                    <img src="{$data_server}uploads/products/{$smarty.get.id}/preview.jpg" alt="" />
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.description}</label>
                <div class="right" style="width: 710px;">
                    {$data.description}
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.description_en}</label>
                <div class="right" style="width: 710px;">
                    {$data.description_en}
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.theme_preview}</label>
                <div class="right">
                    <a href="{$data_server}uploads/products/{$smarty.get.id}/{$data.theme_preview}">{$data.theme_preview}</a>
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.main_file}</label>
                <div class="right">
                    <a href="{$data_server}uploads/products/{$smarty.get.id}/{$data.main_file}">{$data.main_file}</a>
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.category}</label>
                <div class="right">
                    {foreach from=$data.categories item=c}
                        {foreach from=$c item=e name=foo}
                            {$categories[$e].name}
                            {if !$smarty.foreach.foo.last} &rsaquo; {/if}
                        {/foreach}
                        <br />
                    {/foreach}
                </div>
            </div>

            {if $data.demo_url != ''}
                <div class="row-input">
                    <label for="demo_url">{$lang.demo_url}:</label>
                    <div class="right">
                        <a href="{$data.demo_url}" target="_blank">{$data.demo_url}</a>
                    </div>
                </div>
            {/if}

            <div class="row-input">
                <label>{$lang.tags}</label>
                <div class="right">
                    {foreach from=$data.tags item=t}
                        {$t}, 
                    {/foreach}
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.comment}</label>
                <div class="right">
                    {$data.reviewer_comment}
                </div>	
            </div>

            {if isset($smarty.post.free_request)}
                <div class="row-input">
                    <label>{$lang.want_to_be_freefile}</label>
                    <div class="inputs">
                        {if $smarty.post.free_request == 'true'}
                            <img src="{$data_server}admin/images/icons/24x24/accept.png" alt="" />
                        {else}
                            <img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
                        {/if}	
                        <input type="hidden" name="free_request" value="{$smarty.post.free_request}" />
                    </div>
                </div>

                {if $smarty.post.free_request == 'true'}
                    <div class="row-input">
                        <label for="freefile">{$lang.free_file} ?</label>
                        <div class="inputs">
                            <input class="input big" id="freefile" type="checkbox" name="free_file" value="true" {if $smarty.post.free_file == 'true'}checked="checked"{/if} />
                            {$lang.yes}	
                        </div>
                    </div>
                {/if}
            {/if}

            <div class="row-input">
                <label>{$lang.member_price}</label>
                <div class="right">
                    {$data.suggested_price|string_format:"%.2f"} {$currency.symbol}
                </div>	
            </div>

            <div class="form-submit">
                <button type="button" onclick="showFields('approve');" class="btn btn-little-shadow">
                    <i class="hd-check"></i>
                    {$lang.approve}
                </button>
                <button type="button" onclick="showFields('unapprove');" class="btn btn-little-shadow" style="box-shadow: 0 3px 0 #fdb705; background: #fdcd0d;">
                    <i class="hd-cross"></i>
                    {$lang.back_with_comment}
                </button>
                <button type="button" onclick="showFields('delete');" class="btn btn-little-shadow" style="box-shadow: 0 3px 0 #bb2010; background: #e74c3c;">
                    <i class="hd-trash"></i>
                    {$lang.remove_product}
                </button>
            </div>

            <div class="row-input" id="approve_product" {if $smarty.post.action != 'approve'}style="display: none;"{/if}>
                <label for="newprice">{$lang.new_price}</label>
                <div class="right">
                    <input type="text" id="newprice" class="title" style="width:182px" value="" name="price" >
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
                <input class="btn btn-little-shadow" type="submit" name="submit" value="{$lang.save}" onclick="return confirm('{$lang.are_you_sure_submit}');" />
            </p>
        </form>
    </div>
</section>

<script src="{$data_server}admin/js/jquery.datepick.pack.js"></script>
<script src="{$data_server}admin/js/jquery.datepick-en-GB.js"></script> 
<script>
    {literal}
        $(document).ready(function() {
            Administry.dateInput('#datepick'); 
        });
    {/literal}
</script>