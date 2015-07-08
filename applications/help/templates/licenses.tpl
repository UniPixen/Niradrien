<section id="titre-page">
	<div class="container">
		<div class="row">
			<div id="titre" class="span7">
				<h1 class="page-title">{$lang.licenses}</h1>
			</div>
			<div id="breadcrumbs" class="span5">
				<a href="/">{$lang.home}</a> \
			</div>
		</div>
	</div>
</section>

<div id="conteneur">
	<div class="container">
		<div id="licenses" class="row">
			<div class="span12">
				<p>{$lang.licenses_text}</p>
				<table class="table-grey">
					<thead>
						<tr>
							<th></th>
							<th>{$lang.regular_licence}</th>
							<th>{$lang.extended_licence}</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>{$lang.number_of_projects}</td>
							<td>1</td>
							<td>1</td>
						</tr>
						<tr>
							<td>{$lang.for_single_free_project}</td>
							<td><i class="hd-check" title="{$lang.one_licence_by_project}"></i></td>
							<td><i class="hd-check" title="{$lang.one_licence_by_project}"></i></td>
						</tr>
						<tr>
							<td>
								{$lang.for_project_multiple_sales}<br />
								<span class="example">{$lang.example} : {$lang.sale_of_theme_on} {$website_title}</span>
							</td>
							<td><i class="hd-cross" title="{$lang.not_suitable_licence}"></i></td>
							<td><i class="hd-check" title="{$lang.one_licence_by_project}"></i></td>
						</tr>
						<tr>
							<td>
								{$lang.for_single_project_service}<br />
								<span class="example">{$lang.example} : {$lang.made_website_single_customer}</span>
							</td>
							<td><i class="hd-check" title="{$lang.one_licence_by_project_service}"></i></td>
							<td><i class="hd-cross" title="{$lang.not_suitable_licence_service}"></i></td>
						</tr>
						<tr>
							<td>{$lang.to_be_sold_as_it}</td>
							<td><i class="hd-cross" title="{$lang.not_suitable_licence}"></i></td>
							<td><i class="hd-cross" title="{$lang.not_suitable_licence}"></i></td>
						</tr>
					</tbody>
				</table>
				<span class="table-informations">{$lang.hover_icons_learn_more}</span>
			</div>
		</div>
	</div>
</div>