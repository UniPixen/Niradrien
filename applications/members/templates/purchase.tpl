<div id="conteneur">
    <div class="container">
        <div class="row">
            <section class="span12">
                <div id="purchase-complete" class="row">
                    <h3 class="titre-purchase-success">{$lang.thanks_purchase}</h3>
                    <span class="product-name">{$order_info.product_name}</span>
                    <img src="{$data_server}uploads/products/{$order_info.product_id}/thumbnail.png" alt="{$order_info.product_name}" />
                    <a class="btn btn-big-shadow" role="button" href="/account/download/{$order_info.code_achat}">
                        <i class="hd-cloud-download"></i>
                        {$lang.start_download}
                    </a><br />
                    <small class="download-link">{$lang.or_go_to|lower} <a href="/account/downloads">{$lang.download_page|lower}</a></small>
                </div>
            </section>
        </div>
    </div>
</div>