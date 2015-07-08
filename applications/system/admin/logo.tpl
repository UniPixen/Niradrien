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
	<form action="" method="post">
		<div class="container">
			<div class="row-input">
				<label for="logo">{$lang.site_logo}</label>
				<div class="inputs">
					<input id="logo" type="file" name="value" />
					<small>PNG 186x45</small>
					{if $smarty.post.value != ''}
						<img src="{$data_server}uploads/logo/{$smarty.post.value}" alt="{$lang.logo}" />
					{/if}
					{if isset($error.photo)}
						<small>{$error.photo}</small>
					{/if}
				</div>
			</div>
			<div class="form-submit">
				<button type="submit" name="edit" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.save}</button>
			</div>
		</div>
	</form>
</section>