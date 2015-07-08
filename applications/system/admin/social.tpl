<section id="titre-page" class="titre-profil admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$title}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list">{$lang.settings}</a> \
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
        {if $paging !=""}
            <div class="page-controls">{$paging}</div>
        {/if}

        {if is_array($data)}
            <table class="table-grey">
                <thead>
                    <tr>
                        <th>{$lang.name}</th>
                        <th>{$lang.icon}</th>
                        <th>{$lang.color}</th>
                        <th>{$lang.site_username}</th>
                        <th>{$lang.url}</th>
                        <th>{$lang.visible}</th>
                        <th width="150"></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$data item=d}
                        <tr id="row{$d.id}">
                            <td>{$d.name}</td>
                            <td>
                                {if $d.icon != ''}
                                    <i class="{$d.icon}" style="color: {$d.color}"></i>
                                {/if}
                            </td>
                            <td>
                                {if $d.color != ''}
                                    <span style="background: {$d.color}; display: block; float: left; margin-right: 10px; height: 16px; width: 16px; border-radius: 16px; margin-top: 3px;"></span> {$d.color}
                                {/if}
                            </td>
                            <td>
                                {if $d.site_username != ''}
                                    {$d.site_username}
                                {/if}
                            </td>
                            <td>
                                {if $d.url != ''}
                                    {$d.url}
                                {/if}
                            </td>
                            <td>
                                {if $d.visible == 'true'}
                                    <img src="{$data_server}/admin/images/icons/24x24/accept.png" alt="" />
                                {else}
                                    <img src="{$data_server}/admin/images/icons/24x24/delete.png" alt="" />
                                {/if}
                            </td>                           
                            <td>
                                <a href="?m={$smarty.get.m}&amp;c=editSocial&amp;id={$d.id}" title="{$lang.edit}">
                                    <img src="{$data_server}/admin/images/icons/16x16/edit.png" />
                                    {$lang.edit}
                                </a><br />
                                <a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'deleteSocialRow':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
                                    <img src="{$data_server}/admin/images/icons/16x16/delete.png" />
                                    {$lang.delete}
                                </a>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>    
        {else}
            {$lang.no_records}  
        {/if}

        <div class="form-submit" style="margin-top: 30px;">
            <button onclick="window.location='?m={$smarty.get.m}&amp;c=addSocial';" type="button" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.add_new}</button>
        </div>
    </div>
</section>