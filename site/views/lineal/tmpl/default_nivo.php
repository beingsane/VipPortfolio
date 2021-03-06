<?php
/**
 * @package      VipPortfolio
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;?>
<div class="row-fluid">
    <?php if( !empty( $this->item->thumb ) ) { ?>
    <div class="span4">
       <a href="<?php echo $this->imagesUri.$this->item->image;?>" data-lightbox-gallery="com-list-nivo-gallery<?php echo $this->item->id;?>" class="<?php echo $this->modalClass;?>"> 
       
    	<img
    	width="<?php echo $this->params->get('lineal_thumb_width', 300); ?>" 
    	height="<?php echo $this->params->get('lineal_thumb_height', 300); ?>" 
    	src="<?php echo $this->imagesUri.$this->item->thumb;?>" 
    	alt="<?php echo $this->escape($this->item->title);?>" 
    	title="<?php echo $this->escape($this->item->title);?>" 
    	class="thumbnail"
    	/>  
        <?php if ( $this->params->get("lLinkable") OR !empty($this->modal) ) {?></a><?php } ?>
    	
    	<div class="itp-vp-extra-image">
         <?php 
         if (isset($this->extraImages[$this->item->id]) AND !empty($this->extraImages[$this->item->id])){
              $i = 0;
             foreach($this->extraImages[$this->item->id] as $eImage){?>
                <a href="<?php echo $this->imagesUri.$eImage['image'];?>" data-lightbox-gallery="com-list-nivo-gallery<?php echo $this->item->id;?>" class="<?php echo $this->modalClass;?>">
                    <img
                    width="<?php echo $this->params->get('lineal_extra_thumb_width', 50); ?>" 
                    height="<?php echo $this->params->get('lineal_extra_thumb_height', 50); ?>" 
                    src="<?php echo $this->imagesUri.$eImage['thumb'];?>" 
                    alt="" 
                    title="" 
                    class="thumbnail"
                    />  
                </a>
             <?php
              $i++;
              if($i==$this->extraMax){ break; }
             }
         }?>
        </div>
        
    </div>
    <?php } ?>
    <div class="span8">
        <?php if ($this->params->get("lDisplayTitle")) {?>
        <h3 class="itp-vp-ltitle" >
            <?php if($this->params->get("lTitleLinkable") AND $this->item->url ) { ?>
        	<a href="<?php echo $this->item->url;?>" <?php echo $this->openLink;?>><?php echo $this->item->title;?></a>
            <?php } else { ?>
            <?php echo $this->item->title;?>
            <?php }?>
        </h3>
        <?php echo (!empty($this->event->afterDisplayTitle) ) ? $this->event->afterDisplayTitle : ""; ?>
        <?php }?>
    
    	<?php echo (!empty($this->event->beforeDisplayContent) ) ? $this->event->beforeDisplayContent : ""; ?>
    	
        <p><?php echo $this->item->description;?></p>
        
        <?php if ($this->params->get("lDisplayUrl")) {?>
        <a href="<?php echo $this->item->url;?>" title="<?php echo $this->item->title;?>" <?php echo $this->openLink;?>><?php echo $this->item->url;?></a>
        <?php }?>

	</div>
</div>