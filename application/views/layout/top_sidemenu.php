<div class="user-panel">
    <?php
    $file   = "";
    $result = $this->customlib->getUserData();
    $image = $result["image"];
    if (!empty($image)) {
        $file = "uploads/user_images/" . $image;
    } else {
        $file = "uploads/user_images/avatar.jpg";
    }
    ?>
    <div class="image text-center"><img src="<?php echo base_url() . $file; ?>" class="img-circle" alt=""> </div>
    <div class="info">
        <div style="line-height:20px;font-size:12px;font-weight:bold;"><?php echo $this->customlib->getSessionUsername(); ?></div>
        <div style="line-height:20px;font-size:11px;"><?php echo $this->customlib->getSessionCabangName(); ?></div>
    </div>
</div>