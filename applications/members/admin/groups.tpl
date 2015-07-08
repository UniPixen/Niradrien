<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.groups}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="/admin/?m=members&amp;c=list">{$lang.members}</a> \
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
                        <th>{$lang.description}</th>
                        <th width="150"></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$data item=d}
                        <tr>
                            <td>{$d.name}</td>
                            <td>{$d.description|strip_tags|truncate:300}</td>
                            <td>
                                <a href="?m={$smarty.get.m}&c=editGroup&id={$d.ug_id}" title="{$lang.edit}">
                                    <img src="{$data_server}/admin/images/icons/16x16/edit.png" />
                                    {$lang.edit}
                                </a><br />
                                <a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'deleteUserGroup':true,'id':'{/literal}{$d.ug_id}{literal}'}{/literal},'deleteRow');">
                                    <img src="{$data_server}/admin/images/icons/16x16/delete.png" />
                                    {$lang.delete}
                                </a>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        {/if}

        <div style="margin-top: 30px;">
            <button onclick="window.location='?m={$smarty.get.m}&c=addGroup';" type="button" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.add_new}</button>
        </div>
    </div>
</section>