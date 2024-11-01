<div class="row">
    <div class="col-xs-12">
        <?php foreach($this->view['field']->get_options() as $option_key => $option_value): ?>
            <input 
            type="checkbox" 
            name="<?php echo $this->view['field_name']; ?>[<?php echo $option_key; ?>]" 
            data-value="<?php echo $option_value; ?>" 
            value="1" /> <?php echo $option_value; ?><br/>
        <?php endforeach; ?>
    </div>
</div>
