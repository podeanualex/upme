<?php 
    global $upme_template_args;     
    extract($upme_template_args);
    $background_color='black';
?>

<div class="upme-wrap" style="background-color: black !important;">
    
    
    
    <div class="upme-slider-design-three  upme-team-design-three upme-team-design" style="background:black !important;color:<?php echo $font_color; ?>">
        
        <div class="flexslider flexslider-three" style="background-color: black !important;">
            <ul class="slides" style="background-color: black !important;">            
        
                <?php 
                    $x = 0;
                    foreach($users as $key => $user){
                        
                        extract($user);
                    
                        $x++;
                       
                ?>
                
                
                    <?php if($x%4 == 1){ ?>
                    <li class="upme-single-profile-li" style="background-color: black !important;">
                    <?php } ?>
                        
                        <div class="upme-single-profile">
                            <div class="overlay">
                        <div class="upme-author-name">
                                <a upme-data-user-id="<?php echo  $id; ?>" href="<?php echo $profile_url; ?>"  ><?php echo  $profile_title_display; ?></a>
                            </div>
                            </div>
                            <div class="upme-profile-pic <?php echo $pic_style; ?> "  >
                            <?php echo $profile_pic_display; ?>
                            </div>
                            

                            <div class="upme-social-boxes">
                            <?php echo $social_buttons; ?>
                            </div>

                            <div class="upme-clear"></div>
                        </div>
                    
                    <?php if($x%4 == 0){
                    if ($x == 8){
                        break;
                    } 
                        ?>
                    </li>
                    <?php } ?>
                
                <?php } ?>
                
                
            </ul>
        </div>

    </div>
</div>
<style type="text/css">
    .flex-next{
        left: unset !important;
    }
</style>