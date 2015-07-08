<section class="titre-page admin" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h1 class="page-title">{$title}</h1>
			</div>
			<div class="span5" id="breadcrumbs">
				<a href="/admin">{$lang.home}</a> \
				<a href="/admin/?m=forum&amp;c=list">{$lang.forum}</a> \
			</div>
		</div>
	</div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
	<div class="container">
		{if $paging !=""}
			<div class="page-controls">
				{$paging}
			</div>
		{/if}

		{if is_array($reportedMessages)}
			<table class="table-grey">
				<thead>
					<tr>
						<th>{$lang.comment}</th>
						<th>{$lang.report_reason}</th>
						<th>{$lang.reported_by}</th>
						<th width="150"></th>
					</tr>
				</thead>

				<tbody>
					{foreach from=$reportedMessages item=d}
						<tr>
							<td>{$d.comment|nl2br}</td>
							<td>{$d.report_reason}</td>
							<td><a href="/member/{$members[$d.report_by].username}">{$members[$d.report_by].username}</a></td>
							<td>
								<a href="?m={$smarty.get.m}&amp;c=messages&amp;check={$d.id}" title="{$lang.cancel}">
									<img src="{$data_server}admin/images/icons/16x16/back.png" />
									{$lang.cancel}
								</a><br />
								<a href="?m={$smarty.get.m}&amp;c=messages&amp;moderate={$d.id}" title="{$lang.report_check}">
									<img src="{$data_server}admin/images/icons/16x16/report.png" />
									{$lang.report_check}
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
</section>