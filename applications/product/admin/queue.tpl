<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.product_queue_list}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list">{$lang.products}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
    <div class="container">
        <div class="container">
            <div class="page-controls">{$paging}</div>
                {if is_array($data)}
                    <table class="table-grey">
                        <thead>
                            <tr>
                                <th>{$lang.name}</th>
                                <th>
                                    <a href="?m={$smarty.get.m}&amp;c={$smarty.get.c}&amp;p={$smarty.get.p}&amp;order=date&amp;dir={$orderDir}">{$lang.date}</a>
                                </th>
                                <th width="150"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$data item=d}
                                <tr id="row{$d.id}">
                                    <td>{$d.name}</td>
                                    <td>{$d.datetime|date_format:"%d %B %Y à %H:%M"}</td>
                                    <td>
                                        <a href="?m={$smarty.get.m}&amp;c=queue_view&amp;id={$d.id}&amp;p={$smarty.get.p}" title="{$lang.preview}">
                                            <img src="{$data_server}/admin/images/icons/16x16/edit.png" />
                                            {$lang.preview}
                                        </a>
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
            {else}
                {$lang.no_records}
            {/if}
        </div>
    </div>
</section>