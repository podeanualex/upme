<?php 
    global $upme_template_args,$upme; 
    extract($upme_template_args);

    // $profile_url = get_author_posts_url( $id );
    // $profile_pic_display = "<a href='".$profile_url."'>".$upme->pic($id,50)."</a>";
?>

<div class="upme-wrap">
    <div class="upme-author-design-one upme-author-design" style="background:<?php echo $background_color; ?>;color:<?php echo $font_color; ?>">

      <div class="upme-left">
        <div class="upme-profile-pic <?php echo $pic_style; ?> ">
          <?php echo $profile_pic_display; ?>
          
        </div>
        <div class="upme-author-name">
          <div class="upme-field-name">
            <a upme-data-user-id="<?php echo $id; ?>" href="<?php echo $profile_url; ?>" style="color:<?php echo $font_color; ?>" ><?php echo $profile_title_display; ?></a>
          </div>

          <?php echo $social_buttons; ?>

          <div class="upme-field-desc">
          <?php echo $description; ?>
          </div>
            
          <div class="upme-clear"></div>
        </div>
      </div>

      <div class="upme-clear"></div>

    </div>
</div>