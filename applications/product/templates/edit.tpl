{if $product.votes > 2}
    <div id="modal-rating" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-rating" aria-hidden="true">
        <h4>{$lang.buyers_ratings}</h4>
        <div id="rating-stars">
            {section name=foo start=1 loop=6 step=1}
                {if $productRatings.average >= $smarty.section.foo.index}
                    <i class="hd-star on"></i>
                {else}
                    <i class="hd-star off"></i>
                {/if}
            {/section}
        </div>
        <p>{$lang.average_of} {$productRatings.average} {$lang.based_on|lower} {$productRatings.count} {$lang.ratings|lower}.</p>
        <ul class="rating-stats">
            {section name=foo start=1 loop=6 step=1}
                <li>
                    <span class="rating-level">
                        {$smarty.section.foo.index}
                        {if $smarty.section.foo.index > 1}
                            {$lang.stars|lower}
                        {else}
                            {$lang.star|lower}
                        {/if}
                    </span>
                    <div class="rating-graph">
                        <div class="rating-graph-bar">
                            <div class="rating-graph-bar-progress" style="width: {math equation="{$productRatings.stats.{$smarty.section.foo.index}} / {$productRatings.count} * 100" format="%.0f"}%;">{math equation="{$productRatings.stats.{$smarty.section.foo.index}} / {$productRatings.count} * 100" format="%.0f"}%</div>
                        </div>
                    </div>
                    <span class="rating-count">{$productRatings.stats.{$smarty.section.foo.index}}</span>
                </li>
            {/section}
        </ul>
    </div>
{/if}
<section id="titre-page" class="titre-item">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title{if $product.name|strlen > 20} small-title{/if}" itemprop="name">{$product.name}</h1>
                {if $product.votes > 2}
                    <div id="note-produit" itemtype="http://schema.org/AggregateRating" itemscope="" itemprop="aggregateRating" data-placement="right" data-toggle="tooltip" data-original-title="{$lang.average_of} {$product.rating}, {$lang.based_on|lower} {$product.votes} {$lang.ratings}.">
                        <meta content="{$product.rating}" itemprop="ratingValue">
                        <meta content="{$product.votes}" itemprop="ratingCount">
                        {section name=foo start=1 loop=6 step=1}
                            {if $product.rating >= $smarty.section.foo.index}
                                <i class="hd-star on"></i>
                            {else}
                                <i class="hd-star off"></i>
                            {/if}
                        {/section}
                        <a href="#modal-rating" data-toggle="modal" role="button">{$product.votes} {$lang.buyers_ratings|lower}</a>
                    </div>
                {else}
                    <div id="note-produit" class="unknow-note" data-placement="right" data-toggle="tooltip" data-original-title="{$lang.average_of} {$product.rating}, {$lang.based_on|lower} {$product.votes|lower} {$lang.ratings}.">
                        <i class="hd-star-unknow"></i>
                        <i class="hd-star-unknow"></i>
                        <i class="hd-star-unknow"></i>
                        <i class="hd-star-unknow"></i>
                        <i class="hd-star-unknow"></i>
                    </div>
                {/if}
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
                <a href="/category/all">{$lang.files}</a> \
                {foreach from=$product.categories item=e}
                    {foreach from=$e item=c name=foo}
                        <a href="/category/{$categories[$c].keywords|url}">
                            {if $currentLanguage.code != 'fr'}
                                {assign var='foo' value="name_`$currentLanguage.code`"}
                                {$categories[$c].$foo}
                            {else}
                                {$categories[$c].name}
                            {/if}
                        </a>
                        {if !$smarty.foreach.foo.last} \ {/if}
                    {/foreach}
                {/foreach} \
            </div>
        </div>
    </div>
</section>

<div id="tab" class="container">
    <ul>
        <li class="{if $smarty.get.controller != 'faq' && $smarty.get.controller != 'comments' && $smarty.get.controller != 'edit'} selected {/if} ">
            <a href="/product/{$product.id}/{$product.name|url}">{$lang.product_details}</a>
        </li>
        {if check_login_bool() && $product.member_id == $smarty.session.member.member_id}
            <li class="{if $smarty.get.controller == 'faq'}selected{/if}" id="faq-tab">
                <a href="/product/{$product.id}/{$product.name|url}/faq">{$lang.faqs}</a>
            </li>
        {/if}
        {if check_login_bool() && $product.member_id == $smarty.session.member.member_id}
            <li class="{if $smarty.get.controller == 'edit'}selected{/if}" >
                <a href="/product/{$product.id}/{$product.name|url}/edit">{$lang.edit}</a>
            </li>
        {/if}
        <li class="last{if $smarty.get.controller == 'comments'}selected last{/if}  {if check_login_bool() && $product.member_id == $smarty.session.member.member_id}last{/if}">
            <a href="/product/{$product.id}/{$product.name|url}/comments">{$lang.comments}</a>
        </li>
    </ul>
</div>

<section id="conteneur">
    <div class="container">
        <div class="row">
            <div id="editer-produit" class="span8">
                <form method="post" action="{$smarty.server.REQUEST_URI|escape}" enctype="multipart/form-data">
                    <div class="row">
                        <h2>{$lang.name_description}</h2>
                    </div>

                    <div class="row">
                        <label class="inputs-label" for="description">{$lang.description_fr} :</label>
                        <div class="inputs">
                            <textarea name="description" id="description">{$smarty.post.description}</textarea>
                            <span class="description-language fr"></span>
                        </div>
                    </div>

                    <div class="row">
                        <label class="inputs-label" for="description_en">{$lang.description_en} :</label>
                        <div class="inputs">
                            <textarea name="description_en" id="description_en">{$smarty.post.description_en}</textarea>
                            <span class="description-language en"></span>
                        </div>
                    </div>

                    <div class="row">
                        <h2>{$lang.attributes}</h2>
                    </div>

                    <div class="row">
                        <label class="inputs-label" for="description">{$lang.category} :</label>
                        <div class="inputs">
                            <select id="category" name="category">{$categoriesSelect}</select>
                        </div>
                    </div>

                    {if $attributes}
                        {foreach from=$attributes item=a}
                            <script>
                                $(document).ready(function() {
                                    if ($('#attribute_na_' + {$a.id}).is(':checked')) {
                                        $('#custom_attributes_' + {$a.id}).addClass('disabled');
                                        $('#custom_attributes_' + {$a.id}).attr('disabled', true);
                                    }
                                    
                                    $('#attribute_na_' + {$a.id}).change(function() {
                                        if ($(this).is(':checked')) {
                                            $('#custom_attributes_' + {$a.id}).addClass('disabled');
                                            $('#custom_attributes_' + {$a.id}).attr('disabled', true);
                                        }
                                        else {
                                            $('#custom_attributes_' + {$a.id}).removeClass('disabled');
                                            $('#custom_attributes_' + {$a.id}).attr('disabled', false);
                                        }
                                    });
                                });
                            </script>
                            <div class="row">
                                <label class="inputs-label">{$a.name} :</label>
                                <div class="inputs">
                                    {if $a.not_applicable == 'true'}
                                        <input type="checkbox" value="na" name="attributes[{$a.id}]" id="attribute_na_{$a.id}" {if isset($smarty.post.attributes[$a.id]) && $smarty.post.attributes[$a.id] == 'na'}checked{/if} />
                                        <label for="attribute_na_{$a.id}">{$lang.not_applicable}</label>
                                        <br />
                                    {/if}
                                    {if $a.type == 'select'}
                                        <select id="custom_attributes_{$a.id}" name="attributes[{$a.id}]" {if isset($smarty.post.attributes[$a.id]) && $smarty.post.attributes[$a.id] == 'na'}disabled{/if}>
                                            {foreach from=$a.attributes item=ai}
                                                <option value="{$ai.id}" {if isset($smarty.post.attributes[$a.id]) && $smarty.post.attributes[$a.id] == $ai.id}selected{/if}>{$ai.name}</option>
                                            {/foreach}
                                        </select>
                                    {elseif $a.type == 'multiple'}
                                        <select multiple="multiple" id="custom_attributes_{$a.id}" name="attributes[{$a.id}]">
                                            {foreach from=$a.attributes item=ai}
                                                <option value="{$ai.id}" {if isset($smarty.post.attributes[$a.id]) && $smarty.post.attributes[$a.id] == $ai.id}selected{/if}>{$ai.name}</option>
                                            {/foreach}
                                        </select>
                                    {elseif $a.type == 'check'}
                                        {foreach from=$a.attributes item=ai}
                                            <div class="attribute-checkbox">
                                                {if !empty({$ai.photo})}<img src="/static/uploads/attributes/{$ai.photo}" title="{$ai.name}" alt="{$ai.name}" />{/if}
                                                {$ai.name}
                                                <input type="checkbox" name="attributes[{$a.id}][{$ai.id}]" value="{$ai.id}" {if isset($smarty.post.attributes[$a.id][{$ai.id}]) && $smarty.post.attributes[$a.id][$ai.id] == $ai.id}checked{/if} id="attribute-{$ai.id}" />
                                            </div>
                                        {/foreach}
                                    {elseif $a.type == 'radio'}
                                        {foreach from=$a.attributes item=ai}
                                            <input type="radio" name="attributes[{$a.id}]" value="{$ai.id}" {if isset($smarty.post.attributes[$a.id]) && $smarty.post.attributes[$a.id] == $ai.id}checked{/if} class="mt5" />{$ai.name}<br />
                                        {/foreach}
                                    {else}
                                        <input class="big input mt5" type="text" name="attributes[{$a.id}]" value="{$smarty.post.attributes[$a.id]}" {if isset($smarty.post.attributes[$a.id][$ai.id])}checked{/if} />
                                    {/if}
                                </div>
                            </div>
                        {/foreach}
                    {/if}

                    <div class="row">
                        <label class="inputs-label">{$lang.demo_url} :</label>
                        <div class="inputs">
                            <input type= "text" name="demo_url" value="{$smarty.post.demo_url}" {if $smarty.post.demo_url == 'true'}checked="checked"{/if} /><br />
                            <small>{$lang.demo_url_info}</small>
                        </div>
                    </div>

                    <div class="form-submit">
                        <input type="hidden" name="save" value="yes" />
                        <button type="submit" class="btn btn-little-shadow"><i class="hd-disk"></i> {$lang.save_changes}</button>
                    </div>
                </form>

                {if isset($inUpdateQueue)}
                    <div class="notice flash">{$lang.is_in_update_queue}</div>
                {else}
                    <form action="{$smarty.server.REQUEST_URI|escape}" enctype="multipart/form-data" id="envois-fichiers" method="post">
                        <div class="row">
                            <h2>{$lang.tags}</h2>
                        </div>

                        <div class="row">
                            <label class="inputs-label">{$lang.tags} :</label>
                            <div class="inputs">
                                <input type="text" value="{$smarty.post.tags|escape}" class="tagInput input big" name="tags" data-role="tagsinput" placeholder="{$lang.add_tag}" />
                                <small>{$lang.tags_info}</small>
                            </div>
                        </div>

                        <div class="row">
                            <h2>{$lang.files}</h2>

                            <div id="upload-container">
                                <div id="uploads" style="display: none;"></div>
                                <div id="fancy-upload">
                                    <span id="spanButtonPlaceHolder"></span>
                                    <input id="btnCancel" type="hidden" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" /><br />
                                    <div class="fieldset flash" id="fsUploadProgress">
                                        <span class="legend" style="display: none;">Queue files</span>
                                    </div>
                                    <div id="divStatus" style="display: none;">0 uploaded files</div>
                                    <div id="divFileProgressContainer"></div>
                                    <div id="thumbnails"></div>
                                </div>
                            </div>

                            <small style="color: #bfbfbf; font-size: 12px;">
                                {$lang.trouble_uploading} ? <a href="/help/upload" target="_blank" style="color: #999;">{$lang.follow_instructions}</a>
                            </small>
                        </div>

                        <div id="upload_form_user_files_section">
                            <div class="row">
                                <label class="inputs-label">{$lang.thumbnail}</label>
                                <div class="inputs">
                                    <select class="input big ajaxinput" id="temporary_files_to_assign_thumbnail" name="thumbnail">
                                        <option class="placeholder" value="">{$lang.not_uploaded_files}</option>
                                        {if isset($smarty.session.temp.uploaded_files) && $smarty.session.temp.uploaded_files}
                                            {foreach from=$smarty.session.temp.uploaded_files item=s}
                                                <option class="placeholder" value="{$s.filename}" {if $smarty.post.thumbnail == $s.filename}selected="selected"{/if}>{$s.name}</option>
                                            {/foreach}
                                        {/if}
                                    </select><br />
                                    <small>{$lang.thumbnail_info}</small>
                                </div>
                            </div>

                            <div class="row">
                                <label class="inputs-label">{$lang.downloaded_files}</label>
                                <div class="inputs">
                                    <select class="input big ajaxinput" id="temporary_files_to_assign_source" name="main_file">
                                        <option class="placeholder" value="">{$lang.not_uploaded_files}</option>
                                        {if isset($smarty.session.temp.uploaded_files) && $smarty.session.temp.uploaded_files}
                                            {foreach from=$smarty.session.temp.uploaded_files item=s}
                                                <option class="placeholder" value="{$s.filename}" {if $smarty.post.main_file == $s.filename}selected="selected"{/if}>{$s.name}</option>
                                            {/foreach}
                                        {/if}
                                    </select><br />
                                    <small>{$lang.main_file_info}</small>
                                  </div>
                            </div>

                            <div class="row">
                                <label class="inputs-label">{$lang.theme_preview}</label>
                                <div class="inputs">
                                    <select class="input big ajaxinput" id="temporary_files_to_assign_gallery_preview" name="theme_preview">
                                        <option class="placeholder" value="">{$lang.not_uploaded_files}</option>
                                        {if isset($smarty.session.temp.uploaded_files) && $smarty.session.temp.uploaded_files}
                                            {foreach from=$smarty.session.temp.uploaded_files item=s}
                                                <option class="placeholder" value="{$s.filename}" {if $smarty.post.theme_preview == $s.filename}selected="selected"{/if}>{$s.name}</option>
                                            {/foreach}
                                        {/if}
                                    </select><br />
                                    <small>{$lang.theme_preview_info}</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="note">
                                    {$lang.filesize_info}
                                </div>
                            </div>

                            <div class="row">
                                <h2>{$lang.message_to_reviewer}</h2>
                            </div>

                            <div class="row">
                                <label class="inputs-label">{$lang.comment}</label>
                                <div class="inputs">
                                    <textarea rows="10" name="comments_to_reviewer" id="comments_to_reviewer" class="input big">{$smarty.post.reviewer_comment}</textarea><br />
                                    <small>{$lang.upload_terms}</small><br />
                                </div>
                            </div>

                            <div class="form-submit">
                                <input type="hidden" name="upload" value="yes" />
                                <button type="submit" class="btn btn-little-shadow"><i class="hd-cloud-upload"></i> {$lang.upload}</button>
                            </div>
                        </div>
                    </form>

                    {literal}
                        <script>
                            var swfu;
                            window.onload = function() {
                                var settings = {
                                    flash_url : "{/literal}{$data_server}{literal}/js/swfupload/Flash/swfupload.swf",
                                    upload_url: "{/literal}/applications/upload{literal}/ajax/swfupload/doUpload.php",
                                    file_post_name: "file",
                                    file_size_limit : "500 MB",
                                    file_types : "{/literal}{$fileTypes}{literal}",
                                    file_types_description : "Files",
                                    file_upload_limit : 100,
                                    file_queue_limit : 0,
                                    debug: false,

                                    post_params: {
                                        'sessID': '{/literal}{$sessID}{literal}'
                                    },

                                    custom_settings : {
                                        progressTarget : "fsUploadProgress",
                                        cancelButtonId : "btnCancel"
                                    },

                                    button_image_url: "/static/images/upload.png",
                                    button_width: 250,
                                    button_height: 50,
                                    button_placeholder_id: "spanButtonPlaceHolder",

                                    // The event handler functions are defined in handlers.js
                                    file_queued_handler : fileQueued,
                                    file_queue_error_handler : fileQueueError,
                                    file_dialog_complete_handler : fileDialogComplete,
                                    upload_start_handler : uploadStart,
                                    upload_progress_handler : uploadProgress,
                                    upload_error_handler : uploadError,
                                    upload_success_handler : uploadSuccess,
                                    upload_complete_handler : uploadComplete,
                                    queue_complete_handler : queueComplete  // Queue plugin event
                                };

                                swfu = new SWFUpload(settings);
                            };
                        </script>
                    {/literal}
                {/if}
            </div>

            {include file="$root_path/applications/product/templates/aside-fichier.tpl"}
        </div>
    </div>
</section>

<script src="{$data_server}js/tagsinput.js"></script>
<script src="{$data_server}js/licenses.js"></script>
<script src="{$data_server}js/swfupload/swfupload.js"></script>
<script src="/applications/upload/ajax/swfupload/swfupload.queue.js"></script>
<script src="/applications/upload/ajax/swfupload/fileprogress.js"></script>
<script src="/applications/upload/ajax/swfupload/handlers.js"></script>