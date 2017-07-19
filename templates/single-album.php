<div class="fb-PhotosWrap fb-container" data-paging="<?php $next ?>"> 
  <div id="<?php $count . "_" . $id ?>" data-next="<?php $next ?>" data-id="<?php $id ?>" data-limit="<?php $limit ?>" data-showfburl="<?php $showfburl ?>" class="fb-PhotoGallery fb-Clear">
<div class="fb-PhotoContainer" data-albumid="<?php echo $albumid ?>" data-page="1" >
   <?php 
   if(!empty($photos->data)):
   foreach ($photos->data as $key => $photo) {
              
           $rel = $settings['fppg_gallery'] == 'PrettyPhoto' ? 'fbgallery[' . $albumid . ']' : $albumid . 'fbgallery';
                        ?>
            <div class="fb-PhotoThumbWrap">
                <a id="" target="_blank" class="fb-PhotoThumbLink fb-PhotoThumbnail fb-Photo<?php echo $classtext ?>Thumb" data-cancomment="" data-viewonfb="<?php  echo $showfblink ?>" data-fburl="<?php  echo $photo->link ?>" data-from="<?php  echo $photo->from->id ?>" data-id="<?php  echo $photo->id ?>" href="<?php  echo $photo->source ?>" rel="<?php  echo $rel ?>" title="<?php echo $photo->name ?>">
                    <i style="background-image:url(<?php echo $photo->images[$position]->source ?>)"></i>
                </a>
            </div>
                <?php
                }
            endif;
               ?>      
<?php 
if($next!=""){
?>
<div class="fb-BottomBar" data-page="1" data-cancomment="<?php echo  $cancomment ?>" data-next="<?php echo $next ?>" data-id="<?php echo  $id ?>" ><span class="fb-Loadmore" style="margin: 0 5px"><img src="<?php echo  FPPG_URL ?>images/down-pointer.png" /><span id="fb-LoadMoreWall"><?php echo  __('Show more', 'fppg') ?></span></span></div>
<?php
}
?>
         </div>

    </div>
</div>