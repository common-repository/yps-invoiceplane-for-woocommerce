<div class="progress position-relative">
  <div 
      class="progress-bar" 
      role="progressbar" 
      style="width: <?php echo $this->view['field']['options']['progress']; ?>%;" 
      aria-valuenow="<?php echo $this->view['field']['options']['progress']; ?>" 
      aria-valuemin="0" 
      aria-valuemax="100">
      <span class="justify-content-center d-flex position-absolute w-100">
          <?php echo $this->view['field']['options']['progress']; ?>%
      </span>
  </div>
</div>