<div class="input-group mb-3 yps-wp-media-wrapper">
    <input 
        name="<?php echo $this->view['field_name']; ?>" 
        type="text" 
        class="yps-wp-media-url-field form-control" 
        <?php echo $this->view['field']->get_field_attributes() ?> 
        value="<?php echo $this->view['field']->get_value(); ?>" 
    />

    <div class="input-group-append">
        <button class="btn btn-primary yps-wp-media-button" type="button">
            <i class="fas fa-upload"></i> Upload
        </button>

        <button class="btn btn-secondary yps-wp-media-reset-button" type="button">
            Reset
        </button>
    </div>
</div>