<?php 
    global $upme_template_args;     
    extract($upme_template_args);
?>

<div class="upme-wrap">
    
    
    
    <div class="upme-slider-design  upme-team-design-four upme-team-design" style="background:<?php echo 'black'; ?>;color:<?php echo $font_color; ?>" >
        
        
            <ul class="slides" style="background-color: black !important;">            
        
                <?php 
                    $x = 0;
                    foreach($users as $key => $user){ 
                        extract($user);
                        $x++;
                ?>
                
                
                  
                    <li class="upme-single-profile-li" style="background-color: black !important;">
          
                        
                        <div class="upme-single-profile" style="background-color: black !important;">
                            <div class="upme-profile-pic <?php echo $pic_style; ?> "  >
                            <?php echo $profile_pic_display; ?>
                            </div>

                            <div class="upme-clear" style="background-color: black !important;"></div>
                        </div>
                    
         
                    </li>
        
                
                <?php } ?>
                
                
            </ul>
        </div>

    
</div>
<style type="text/css">
    .flex-next{
        left: unset !important;
    }
</style>