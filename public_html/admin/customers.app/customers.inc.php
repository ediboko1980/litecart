<?php
  if (!isset($_GET['page'])) $_GET['page'] = 1;
?>
<div style="float: right;"><?php echo functions::form_draw_link_button(document::link('', array('doc' => 'edit_customer'), true), language::translate('title_add_new_customer', 'Add New Customer'), '', 'add'); ?></div>
<div style="float: right; padding-right: 10px;"><?php echo functions::form_draw_form_begin('search_form', 'get', '', false, 'onsubmit="return false;"') . functions::form_draw_search_field('query', true, 'placeholder="'. language::translate('text_search_phrase_or_keyword', 'Search phrase or keyword') .'"  onkeydown=" if (event.keyCode == 13) location=(\''. document::link('', array(), true, array('page', 'query')) .'&query=\' + this.value)"') . functions::form_draw_form_end(); ?></div>
<h1 style="margin-top: 0px;"><img src="<?php echo WS_DIR_ADMIN . $_GET['app'] .'.app/icon.png'; ?>" width="32" height="32" style="vertical-align: middle; margin-right: 10px;" /><?php echo language::translate('title_customers', 'Customers'); ?></h1>

<?php echo functions::form_draw_form_begin('customers_form', 'post'); ?>
<table width="100%" class="dataTable">
  <tr class="header">
    <th nowrap="nowrap"><?php echo functions::form_draw_checkbox('checkbox_toggle', '', ''); ?></th>
    <th nowrap="nowrap" align="left"><?php echo language::translate('title_id', 'ID'); ?></th>
    <th nowrap="nowrap" align="left" width="100%"><?php echo language::translate('title_name', 'Name'); ?></th>
    <th nowrap="nowrap" align="center"><?php echo language::translate('title_date_registered', 'Date Registered'); ?></th>
    <th nowrap="nowrap">&nbsp;</th>
  </tr>
<?php
  $customers_query = database::query(
    "select * from ". DB_TABLE_CUSTOMERS ."
    ". ((!empty($_GET['query'])) ? "where (email like '%". database::input($_GET['query']) ."%' or firstname like '%". database::input($_GET['query']) ."%' or lastname like '%". database::input($_GET['query']) ."%')" : "") ."
    order by firstname, lastname;"
  );
  
  if (database::num_rows($customers_query) > 0) {
  
    
  // Jump to data for current page
    if ($_GET['page'] > 1) database::seek($customers_query, (settings::get('data_table_rows_per_page') * ($_GET['page']-1)));
  
    $page_items = 0;
    while ($customer = database::fetch($customers_query)) {
      if (!isset($rowclass) || $rowclass == 'even') {
        $rowclass = 'odd';
      } else {
        $rowclass = 'even';
      }
?>
  <tr class="<?php echo $rowclass; ?>">
    <td nowrap="nowrap"><?php echo functions::form_draw_checkbox('orders['.$customer['id'].']', $customer['id']); ?></td>
    <td nowrap="nowrap" align="left"><?php echo $customer['id']; ?></td>
    <td nowrap="nowrap" align="left"><a href="<?php echo document::href_link('', array('doc' => 'edit_customer', 'customer_id' => $customer['id']), true); ?>"><?php echo $customer['firstname'] .' '. $customer['lastname']; ?></a></td>
    <td nowrap="nowrap" align="right"><?php echo strftime(language::$selected['format_datetime'], strtotime($customer['date_created'])); ?></td>
    <td nowrap="nowrap"><a href="<?php echo document::href_link('', array('doc' => 'edit_customer', 'customer_id' => $customer['id']), true); ?>"><img src="<?php echo WS_DIR_IMAGES . 'icons/16x16/edit.png'; ?>" width="16" height="16" align="absbottom" /></a></td>
  </tr>
<?php
      if (++$page_items == settings::get('data_table_rows_per_page')) break;
    }
  }
?>
  <tr class="footer">
    <td colspan="5" align="left"><?php echo language::translate('title_customers', 'Customers'); ?>: <?php echo database::num_rows($customers_query); ?></td>
  </tr>
</table>

<script>
  $(".dataTable input[name='checkbox_toggle']").click(function() {
    $(this).closest("form").find(":checkbox").each(function() {
      $(this).attr('checked', !$(this).attr('checked'));
    });
    $(".dataTable input[name='checkbox_toggle']").attr("checked", true);
  });

  $('.dataTable tr').click(function(event) {
    if ($(event.target).is('input:checkbox')) return;
    if ($(event.target).is('a, a *')) return;
    if ($(event.target).is('th')) return;
    $(this).find('input:checkbox').trigger('click');
  });
</script>

<?php
  echo functions::form_draw_form_end();

// Display page links
  echo functions::draw_pagination(ceil(database::num_rows($customers_query)/settings::get('data_table_rows_per_page')));
  
?>