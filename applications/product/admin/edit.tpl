<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h2 class="page-title">{$data.name}</h2>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="/admin/?m=product&amp;c=list">{$lang.products}</a> \
            </div>
        </div>
    </div>
</section>

<div class="dashboard container" id="tab-membre">
    <ul>
        <li class="selected">
            <div></div>
            <a href="/admin/?m=product&c=edit&id={$smarty.get.id}">{$lang.product_details}</a>
        </li>
        <li class="last">
            <div></div>
            <a href="/admin/?m=product&c=comments&id={$smarty.get.id}">{$lang.comments}</a>
            <div class="last"></div>
        </li>
    </ul>
</div>

<section id="conteneur">
    <div class="container">
        <form action="" method="post">
            <div class="row-input">
                <label>{$lang.seller}</label>
                <div class="right">
                    <a href="/admin/?m=members&c=edit&id={$data.member.member_id}" target="_blank">{$data.member.username}</a>
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.thumbnail}</label>
                <div class="inputs">
                    <img src="{$data_server}uploads/products/{$smarty.get.id}/{$data.thumbnail}" alt="" />
                </div>
            </div>

            <div class="row-input">
                <label>Preview Screenshots</label>
                <div class="inputs">
                    <img src="{$data_server}uploads/products/{$smarty.get.id}/preview.jpg" alt="" />
                </div>
            </div>

            <div class="row-input">
                <label for="idesc">{$lang.description}</label>
                <div class="inputs">
                    <textarea name="description">{$data.description}</textarea>
                </div>
            </div>

            <div class="row-input">
                <label for="idesc">{$lang.description_en}</label>
                <div class="inputs">
                    <textarea name="description_en">{$data.description_en}</textarea>
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
                <div class="inputs">
                    <select name="category[]">{$categoriesSelect}</select>
                </div>
                {if isset($error.category)}
                    {$error.category}
                {/if}
            </div>

            {if $data.demo_url != ''}
                <div class="row-input">
                    <label for="demo_url">{$lang.demo_url}:</label>
                    <div class="inputs">
                        <input type="text" name="demo_url" value="{$smarty.post.demo_url|escape}" />
                    </div>
                </div>
            {/if}

            <div id="attribute_fields">
                {if $attributes}
                    {foreach from=$attributes item=a}
                        <br />
                        <div class="row-input">
                            <label>{$a.name}</label>
                            <div class="inputs">
                                {if $a.type == 'select'}									  	
                                    <select id="custom_attributes_{$a.id}" name="attributes[{$a.id}]">
                                        {foreach from=$a.attributes item=ai}
                                            <option value="{$ai.id}" {if $smarty.post.attributes[$a.id] == $ai.id}selected="selected"{/if}>{$ai.name}</option>
                                        {/foreach}
                                    </select>
                                {elseif $a.type == 'multiple'}
                                    <select multiple="multiple" id="custom_attributes_{$a.id}" name="attributes[{$a.id}]">
                                        {foreach from=$a.attributes item=ai}
                                            <option value="{$ai.id}" {if $smarty.post.attributes[$a.id] == $ai.id}selected="selected"{/if}>{$ai.name}</option>
                                        {/foreach}
                                    </select>
                                {elseif $a.type == 'check'}
                                    {foreach from=$a.attributes item=ai}
                                        <input type="checkbox" name="attributes[{$a.id}][{$ai.id}]" value="{$ai.id}" {if isset($smarty.post.attributes[$a.id][$ai.id])}checked="checked"{/if} class="mt5"/> {$ai.name}<br />
                                    {/foreach}
                                {elseif $a.type == 'radio'}
                                    {foreach from=$a.attributes item=ai}
                                        <input type="radio" name="attributes[{$a.id}]" value="{$ai.id}" {if isset($smarty.post.attributes[$a.id]) && $smarty.post.attributes[$a.id] == $ai.id}checked="checked"{/if} /> {$ai.name} <br />
                                    {/foreach}
                                {else}
                                    <input type="text" name="attributes[{$a.id}]" value="{$smarty.post.attributes[$a.id]}" />
                                {/if}
                            </div>
                        </div>
                    {/foreach}
                {/if}
            </div>

            <div class="row-input">
                <label>{$lang.tags}</label>
                <div class="right">
                    {foreach from=$data.tags item=t}{$t}, {/foreach}
                </div>	
            </div>

            <div class="row-input">
                <label>{$lang.want_to_be_freefile}:</label>
                <div class="inputs">
                    {if $smarty.post.free_request == 'true'}
                        <img src="{$data_server}/admin/images/icons/24x24/accept.png" alt="" />
                    {else}
                        <img src="{$data_server}/admin/images/icons/24x24/delete.png" alt="" />
                    {/if}	
                    <input type="hidden" name="free_request" value="{$smarty.post.free_request}" />
                </div>
            </div>

            {if $smarty.post.free_request == 'true'}
                <div class="row-input">
                    <label for="freefile">{$lang.free_file}?</label>
                    <div class="inputs">
                        <input type="checkbox" name="free_file" value="true" {if $smarty.post.free_file == 'true'}checked="checked"{/if} />
                        {$lang.yes}	
                    </div>
                </div>
            {/if}

            <div class="row-input">
                <label for="datepick">{$lang.weekly_features_to}</label>
                <div class="inputs">
                    <input type="text" name="weekly_to" value="{$smarty.post.weekly_to|escape}" /> 
                </div>
            </div>

            <div class="row-input">
                <label for="price">{$lang.price} {$currency.symbol}</label>
                <div class="inputs">
                    <input type="text" name="price" value="{$smarty.post.price|escape}" /> 
                </div>
            </div>

            <div class="form-submit">
                <button type="submit" name="edit" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.edit}</button>
            </div>
        </form>
    </div>
</section>