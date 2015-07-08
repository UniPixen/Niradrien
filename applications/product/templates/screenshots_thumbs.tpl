<div class="thumbnails">
	{foreach from=$previewFiles item=f name=foo}
	<a href="/product/screenshots_file/{$product.id}?index={$smarty.foreach.foo.index}" target="preview">
		<img src="{$data_server}uploads/products/{$product.id}/preview/{$f}" height="60" />
	</a>
	{/foreach}
</div>
<div class="purchase">
    <a href="/product/{$product.id}" target="_top">
		<img alt="{$domain} Purchase This" class="purchase" src="{$data_server}logo/zipmarket_preview_logo.png">
	</a>
</div>