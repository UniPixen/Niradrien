<section id="titre-page" class="titre-page admin">
	<div class="container">
		<div class="row">
			<div id="titre" class="span7">
				<h1 class="page-title">
					{foreach from=$data item=d name=foo}
						{if $smarty.foreach.foo.index < 1}
							{$d.product_name}
						{/if}
					{/foreach}
				</h1>
			</div>
			<div id="breadcrumbs" class="span5">
				<a href="/admin">{$lang.home}</a> \
				<a href="?m={$smarty.get.m}&c=list">{$lang.products}</a> \
				<a href="#">{$lang.comments}</a> \
			</div>
		</div>
	</div>
</section>

<div class="dashboard container" id="tab-membre">
	<ul>
		<li>
			<div></div>
			<a href="/admin/?m=product&amp;c=edit&amp;id={$smarty.get.id}">{$lang.product_details}</a>
		</li>
		<li class="selected last">
			<div></div>
			<a href="/admin/?m=product&amp;c=comments&amp;id={$smarty.get.id}">{$lang.comments}</a>
			<div class="last"></div>
		</li>
	</ul>
</div>

<section id="conteneur">
	<div class="container">
		<div class="page-controls">{$paging}</div>
		{if is_array($data)}
			<table class="table-grey">
				<thead>
					<tr>
						<th>{$lang.user}</th>
						<th>{$lang.product}</th>
						<th>{$lang.comment}</th>
						<th>{$lang.reported}</th>
						<th>{$lang.added_on}</th>
						<th width="150"></th>
					</tr>
				</thead>
				<tbody>
				{foreach from=$data item=d}
					<tr id="row{$d.id}" class="{cycle values="no,alt"}">
						<td><a href="/member/{$members[$d.member_id].username}" target="_blank">{$members[$d.member_id].username}</a></td>
						<td><a href="/product/{$d.product_id}/{$d.product_name|url}" target="_blank">{$d.product_name}</a></td>
						<td>{$d.comment|nl2br}</td>
						<td>
							{if $d.report_by != '0'}
								<i class="hd-check" style="font-size: 24px; color: #27ae60;"></i>
							{else}
								<i class="hd-cross" style="font-size: 24px; color: #ea6153;"></i>
							{/if}
						</td>
						<td>
							{$d.datetime|date_format: "%d/%m/%Y à %H:%M"}
						</td>
						<td>
							{if $d.report_by != '0'}
								<a href="?m={$smarty.get.m}&amp;c=comments&amp;id={$d.id}&amp;report={$d.id}&amp;p={$smarty.get.p}" title="{$lang.ignore}">
									<img src="{$data_server}admin/images/icons/16x16/back.png" alt="{$lang.ignore}" />
									{$lang.ignore}
								</a>
								<br />
							{/if}
							<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'deleteComment':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
								<img src="{$data_server}admin/images/icons/16x16/delete.png" />
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
	</div>
</section>