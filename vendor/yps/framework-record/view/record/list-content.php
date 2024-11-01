<div class="row mb-3 mt-5">

    <div class="col-12 text-center">

        <div class="btn-group mr-2" role="group" aria-label="">
            <?php echo $this->get_framework_view('Record', 'record/list-buttons.php'); ?>
        </div>

    </div>
</div>

<?php if(!empty($this->get_list_after_buttons_view())): ?>
    <?php echo $this->get_list_after_buttons_view(); ?>
<?php endif; ?>

<div class="row">
    <div class="col-sm-12 yps-record-table-wrapper <?php echo $this->get_record_table()->get_table_wrapper_class(); ?>">
        <!-- DATA TABLE -->
        <table 
                id="" 
                class="yps-record-table table table-striped table-bordered <?php echo $this->get_record_table()->get_table_class(); ?>"
                width="<?php echo $this->get_record_table()->get_table_width(); ?>" 
                data-url="<?php echo $this->get_record_config()->get_list_ajax_url(); ?>"
                data-filters-selector="<?php echo $this->get_record_table()->get_filters_selector(); ?>" 
                data-default-order-by="<?php echo $this->get_record_table()->get_default_order_by(); ?>" 
                data-default-order-dir="<?php echo $this->get_record_table()->get_default_order_dir(); ?>" 
                >
            <thead>
                <tr>
                    <?php foreach ($this->record_table->get_header() as $header_key => $header_settings): ?>
                        <th 
                            data-header-key="<?php echo $header_key; ?>" 
                            class="text-center <?php echo $header_settings['header_class']; ?>"
                            style="<?php echo $header_settings['style']; ?>"
                            data-row-class="<?php echo $header_settings['row_class']; ?>">
                            <?php echo $header_settings['label']; ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
           <tbody></tbody>

        </table>
        <!-- /DATA TABLE -->
    </div>
</div>

<div class="row mb-5 mt-3">

    <div class="col-12 text-center">

        <div class="btn-group mr-2" role="group" aria-label="">
            <?php echo $this->get_framework_view('Record', 'record/list-buttons.php'); ?>
        </div>

    </div>
</div>

<?php do_action('yps_record_list_footer'); ?>