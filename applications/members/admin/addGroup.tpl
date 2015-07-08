<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$title}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="/admin/?m=members&amp;c=groups">{$lang.members}</a> \
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
	<div class="container">
		<form action="" method="post">
			<div class="row-input">
				<label for="name">{$lang.name}</label>
				<div class="inputs">
					<input type="text" name="name" value="{$smarty.post.name|escape}" required="true" />
					{if isset($error.name)}
						<span class="validate_error">{$error.name}</span>
					{/if}
				</div>
			</div>

			<div class="row-input">
				<label class="required" for="desc">{$lang.description}</label>
				<div class="inputs">
					<textarea name="description" id="desc">{$smarty.post.description}</textarea>
					{if isset($error.desc)}
						<span class="validate_error">{$error.desc}</span>
					{/if}
				</div>
			</div>

			<div class="row-input">
				<label class="required">{$lang.applications}</label>
				<div class="inputs">
					<input type="checkbox" name="applications[system]" values="yes" {if isset($smarty.post.applications.system)}checked="checked"{/if}>
					<span>system</span><br />
					{foreach from=$applications item=m key=k}
						<input type="checkbox" name="applications[{$k}]" values="yes" {if isset($smarty.post.applications.$k)}checked="checked"{/if}>
						<span>{$k}</span><br />
					{/foreach}
				</div>
			</div>

			{if isset($error.applications)}
				<span class="validate_error">{$error.applications}</span>
			{/if}
			
			{if $smarty.get.c == 'editGroup'}
				<button type="submit" name="edit" class="btn btn-big-shadow" style="width: 250px; height: 45px;">{$lang.edit}</button>
			{else}
				<button type="submit" name="add" class="btn btn-big-shadow" style="width: 250px; height: 45px;">{$lang.add}</button>
			{/if}
		</form>
	</div>
</section>