<article id="box-recently-viewed-products" class="box hidden-xs">

  <h2 class="title"><?php echo language::translate('title_recently_viewed', 'Recently Viewed'); ?></h2>

  <div class="listing row">

    <?php foreach ($products as $product) { ?>
    <div class="col-xs-3">
      <a href="<?php echo htmlspecialchars($product['link']); ?>" title="<?php echo htmlspecialchars($product['name']); ?>" <?php echo !empty(document::$settings['product_modal_window']) ? ' data-toggle="lightbox" data-require-window-width="768" data-max-width="980"' : ''; ?>>
        <img class="img-thumbnail hover-light" src="<?php echo htmlspecialchars($product['thumbnail']); ?>" alt="" />
        </a>
      </div>
    <?php } ?>

  </div>
</article>