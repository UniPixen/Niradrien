<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.begin_upload}</h1>
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

<section id="conteneur">
    <div class="container">
        <div class="row">
            <div id="upload-list" class="span8">
                {if !isset($hideForm)}
                    <form id="upload-form" method="post" enctype="multipart/form-data">
                        <div class="row-input">
                            <h4>{$lang.name}</h4>
                            <input type="text" id="name" name="name" value="{$smarty.post.name|escape}" /><br />
                            <small class="input-description">{$lang.upload_name_info}</small>
                        </div>

                        <div class="row-input">
                            <h4>{$lang.description_fr}</h4>
                            <textarea name="description" id="description">{$smarty.post.description|escape}</textarea>
                           <small class="input-description">{$lang.upload_desc_info}</small>
                        </div>

                        <div class="row-input">
                            <h4>{$lang.description_en}</h4>
                            <textarea name="description_en" id="description_en">{$smarty.post.description_en|escape}</textarea>
                            <small class="input-description">{$lang.upload_desc_info}</small>
                        </div>

                        <div class="row-input">
                            <h4>{$lang.files}</h4>
                            <div id="upload-container">
                                <div id="uploads" style="display: none;"></div>
                                <div id="fancy-upload">
                                    <span id="spanButtonPlaceHolder"></span>
                                    <input id="btnCancel" type="hidden" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" /><br />
                                    <div class="fieldset flash" id="fsUploadProgress">
                                        <span class="legend" style="display: none;">{$lang.queue_files}</span>
                                    </div>
                                    <div id="divStatus" style="display: none;">0 {$lang.uploaded_files|lower}</div>
                                    <div id="divFileProgressContainer"></div>
                                    <div id="thumbnails"></div>
                                </div>
                            </div>

                            <small class="input-description">
                                {$lang.trouble_uploading} ? <a href="/help/upload" target="_blank">{$lang.follow_instructions}</a>
                            </small>
                        </div>

                        <div class="row-input">
                            <h4>{$lang.thumbnail}</h4>
                            <select class="input big ajaxinput" id="temporary_files_to_assign_thumbnail" name="thumbnail">
                                <option class="placeholder" value="">{$lang.not_uploaded_files}</option>
                                {if $smarty.session.temp.uploaded_files}
                                    {foreach from=$smarty.session.temp.uploaded_files item=s}
                                        <option class="placeholder" value="{$s.filename}" {if $smarty.post.thumbnail == $s.filename}selected="selected"{/if}>{$s.name}</option>
                                    {/foreach}
                                {/if}
                            </select><br />
                            <small class="input-description">{$lang.preview_thumbnail_info}</small>
                        </div>

                        <div class="row-input">
                            <h4>{$lang.main_file}</h4>
                            <select class="input big ajaxinput" id="temporary_files_to_assign_source" name="main_file">
                                <option class="placeholder" value="">{$lang.not_uploaded_files}</option>
                                {if $smarty.session.temp.uploaded_files}
                                    {foreach from=$smarty.session.temp.uploaded_files item=s}
                                        <option class="placeholder" value="{$s.filename}" {if $smarty.post.main_file == $s.filename}selected="selected"{/if}>{$s.name}</option>
                                    {/foreach}
                                {/if}
                            </select><br />
                            <small class="input-description">{$lang.main_file_info}</small>
                        </div>

                        <div class="row-input">
                            <h4>{$lang.preview_pictures}</h4>
                            <select class="input big ajaxinput" id="temporary_files_to_assign_gallery_preview" name="theme_preview">
                                <option class="placeholder" value="">{$lang.not_uploaded_files}</option>
                                {if $smarty.session.temp.uploaded_files}
                                    {foreach from=$smarty.session.temp.uploaded_files item=s}
                                        <option class="placeholder" value="{$s.filename}" {if $smarty.post.theme_preview == $s.filename}selected="selected"{/if}>{$s.name}</option>
                                    {/foreach}
                                {/if}
                            </select><br />
                            <small class="input-description">{$lang.preview_pictures_info}</small>
                        </div>

                        <div class="row-input">
                            {$lang.filesize_info}
                        </div>

                        <div class="row-input">
                            <h4>{$lang.category}</h4>
                            <select name="category[]">{$categoriesSelect}</select>
                        </div>

                        <div id="attribute_fields">
                            {if $attributes}
                                <h4>{$lang.attributes}</h4>
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
                                    <div class="row-input">
                                        <label class="inputs-label">{$a.name} :</label>
                                        <div class="inputs">
                                            {if $a.not_applicable == 'true'}
                                                <input type="checkbox" value="na" name="attributes[{$a.id}]" id="attribute_na_{$a.id}" {if isset($smarty.post.attributes[$a.id]) && $smarty.post.attributes[$a.id] == 'na'}checked{/if} />
                                                <label for="attribute_na_{$a.id}">{$lang.not_applicable}</label>
                                                <br />
                                            {/if}
                                            {if $a.type == 'select'}
                                                <select class="input big mt5" id="custom_attributes_{$a.id}" name="attributes[{$a.id}]">
                                                    {foreach from=$a.attributes item=ai}
                                                        <option value="{$ai.id}" {if $smarty.post.attributes[$a.id] == $ai.id}selected="selected"{/if}>{$ai.name}</option>
                                                    {/foreach}
                                                </select>
                                                {elseif $a.type == 'multiple'}
                                                    <select multiple="multiple" id="custom_attributes_{$a.id}" name="attributes[{$a.id}]">
                                                        {foreach from=$a.attributes item=ai}
                                                            <option value="{$ai.id}" {if isset($smarty.post.attributes[$a.id]) == $ai.id}selected="selected"{/if}>{$ai.name}</option>
                                                        {/foreach}
                                                    </select>
                                                {elseif $a.type == 'check'}
                                                    {foreach from=$a.attributes item=ai}
                                                        <div class="attribute-checkbox">
                                                            {if !empty({$ai.photo})}<img src="/static/uploads/attributes/{$ai.photo}" title="{$ai.name}" alt="{$ai.name}" />{/if}
                                                            {$ai.name}
                                                            <input type="checkbox" name="attributes[{$a.id}][{$ai.id}]" value="{$ai.id}" {if isset($smarty.post.attributes[$a.id][$ai.id])}checked="checked"{/if} id="attribute-{$ai.id}" />
                                                        </div>
                                                    {/foreach}
                                                {elseif $a.type == 'radio'}
                                                    {foreach from=$a.attributes item=ai}
                                                        <input type="radio" name="attributes[{$a.id}]" value="{$ai.id}" {if isset($smarty.post.attributes[$a.id][{$ai.id}]) && $smarty.post.attributes[$a.id][{$ai.id}] == $ai.id}checked="checked"{/if} class="mt5" />{$ai.name}<br />
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
                            <h4>{$lang.demo_url}</h4>
                            <input autocomplete="off" name="demo_url" type="text" value="{$smarty.post.demo_url|escape}" /><br />
                            <small class="input-description">{$lang.optional} : {$lang.demo_url_info|@lower} (ex : http://site.com/demo/).</small>
                        </div>

                        <div class="row-input">
                            <h4>{$lang.tags}</h4>
                            <input type="text" value="{$smarty.post.tags|escape}" class="tagInput input big" name="tags" data-role="tagsinput" placeholder="{$lang.add_tag}" />
                        </div>

                        <div class="row-input">
                            <h4>{$lang.message_to_reviewer}</h4>
                            <textarea name="comments_to_reviewer" id="comments_to_reviewer">{$smarty.post.comments_to_reviewer|escape}</textarea>
                        </div>

                        <div class="row-input">
                            <h4>{$lang.price}</h4>
                            <input type="text" class="input big" name="suggested_price" value="{$smarty.post.suggested_price|escape}" />
                        </div>

                        <div class="row-input">
                            <label class="checkbox">
                                <input class="mt5" name="source_license" type="checkbox" value="true" {if isset($smarty.post.source_license)}checked="checked"{/if} />
                                {$lang.source_license_text} {$website_title}.
                            </label>
                        </div>

                        <div class="form-submit">
                            <input type="hidden" name="upload" value="yes" />
                            <button type="submit" class="btn btn-big-shadow">
                                <i class="hd-cloud-upload"></i>
                                {$lang.upload}
                            </button>
                        </div>
                    </form>
                {/if}
            </div>

            <div id="upload-aside" class="span4">
                <h3>{$lang.switch_category}</h3>
                <form id="begin-upload" action="/" method="get" >
                    <select id="category" name="category">
                        {if $mainCategories}
                            {foreach from=$mainCategories item=c}
                                <option value="{$c.keywords}" {if $smarty.get.category == $c.keywords}selected="selected"{/if}>
                                    {if $currentLanguage.code != 'fr'}
                                        {assign var='foo' value="name_`$currentLanguage.code`"}
                                        {$c.$foo}
                                    {else}
                                        {$c.name}
                                    {/if}
                                </option>
                            {/foreach}
                        {/if}
                    </select>
                    <button onclick="window.location='/author/upload/form/?category=' + document.getElementById('category').options[document.getElementById('category').selectedIndex].value;" type="button" class="btn btn-little-shadow">
                        <i class="hd-arrow-right"></i>
                        {$lang.switch_category}
                    </button>
                    <a href="/help/upload" class="btn btn-little-shadow" role="button" target="_blank">{$lang.help}</a>
                </form>

                <div class="post-it">
                    <h3>{$lang.get_product_accepted}</h3>
                    {$lang.get_product_accepted_text}
                </div>

                <div class="post-it">
                    <h3>{$lang.troubles} ?</h3>
                    <p>{$lang.troubles_text1}</p>
                    <p>{$lang.troubles_text2}</p>
                </div>
            </div>
        </div>
    </div>

    {literal}
        <script>
            var swfu;
            window.onload = function() {
                var settings = {
                    flash_url: "{/literal}{$data_server}{literal}/js/swfupload/Flash/swfupload.swf",
                    upload_url: "{/literal}/applications/{$smarty.get.module}{literal}/ajax/swfupload/doUpload.php",
                    file_post_name: "file",
                    file_size_limit: "500 MB",
                    file_types: "{/literal}{$fileTypes}{literal}",
                    file_types_description: "Files",
                    file_upload_limit: 100,
                    file_queue_limit: 0,
                    debug: false,

                    post_params: {
                        'sessID': '{/literal}{$sessID}{literal}'
                    },

                    custom_settings: {
                        progressTarget: "fsUploadProgress",
                        cancelButtonId: "btnCancel"
                    },

                    button_image_url: "/static/images/upload.png",
                    button_width: 250,
                    button_height: 50,
                    button_placeholder_id: "spanButtonPlaceHolder",

                    // The event handler functions are defined in handlers.js
                    file_queued_handler: fileQueued,
                    file_queue_error_handler: fileQueueError,
                    file_dialog_complete_handler: fileDialogComplete,
                    upload_start_handler: uploadStart,
                    upload_progress_handler: uploadProgress,
                    upload_error_handler: uploadError,
                    upload_success_handler: uploadSuccess,
                    upload_complete_handler: uploadComplete,
                    queue_complete_handler: queueComplete // Queue plugin event
                };

                swfu = new SWFUpload(settings);
            };
        </script>
    {/literal}
</section>

<script src="/static/js/tagsinput.js"></script>
<script src="/static/js/swfupload/swfupload.js"></script>
<script src="/applications/upload/ajax/swfupload/swfupload.queue.js"></script>
<script src="/applications/upload/ajax/swfupload/fileprogress.js"></script>
<script src="/applications/upload/ajax/swfupload/handlers.js"></script>