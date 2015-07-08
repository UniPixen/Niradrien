<section class="titre-profil admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h1 class="page-title">{$title}</h1>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
                <a href="/admin/?m=system&amp;c=list">{$lang.settings}</a> \
                <a href="?m={$smarty.get.m}&amp;c=social&amp;p={$smarty.get.p}">{$lang.social_networks}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
	<div class="container">
		<form action="" method="post">
			<div class="row-input">
				<label for="name">{$lang.name}</label>
				<div class="inputs">
					<input id="name" type="text" name="name" value="{$smarty.post.name|escape}">
					{if isset($error.name)}
						<small>{$error.name}</small>
					{/if}
				</div>
			</div>

			<div class="row-input">
				<label>{$lang.icon}</label>
				<div class="inputs">
					<input type="text" name="icon" value="{$smarty.post.icon|escape}">
					{if isset($error.icon)}
						<small>{$error.icon}</small>
					{/if}
				</div>
			</div>

			<div class="row-input">
				<label>{$lang.color}</label>
				<div class="inputs">
					<input type="text" name="color" value="{$smarty.post.color|escape}">
					{if isset($error.color)}
						<small>{$error.color}</small>
					{/if}
				</div>
			</div>

			<div class="row-input">
				<label>{$lang.site_username}</label>
				<div class="inputs">
					<input type="text" name="site_username" value="{$smarty.post.site_username|escape}">
					{if isset($error.site_username)}
						<small>{$error.site_username}</small>
					{/if}
				</div>
			</div>

			<div class="row-input">
				<label>{$lang.url}</label>
				<div class="inputs">
					<input type="text" name="url" value="{$smarty.post.url|escape}">
					{if isset($error.url)}
						<small>{$error.url}</small>
					{/if}
				</div>
			</div>
			
			<div class="row-input">
				<label for="visible">{$lang.visible}</label>
				<div class="inputs">
					<input type="checkbox" name="visible" id="visible" value="true" class="checkbox" {if $smarty.post.visible == 'true'}checked="checked"{/if} /> {$lang.yes}
				</div>
			</div>

			<div class="form-submit">
				<button type="submit" name="add" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.add}</button>
			</div>
		</form>
	</div>
</section>