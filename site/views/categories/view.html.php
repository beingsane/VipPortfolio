<?php
/**
 * @package      ITPrism Components
 * @subpackage   Vip Portfolio
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * Vip Portfolio is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class VipPortfolioViewCategories extends JView {
    
    protected $state = null;
    protected $items = null;
    protected $pagination = null;
    
    protected $option;
    
    public function __construct($config) {
        parent::__construct($config);
        $this->option = JFactory::getApplication()->input->get("option");
    }
    
    public function display($tpl = null) {
        
        $app = JFactory::getApplication();
        /** @var $app JSite **/
        
        // Initialise variables
        $this->state          = $this->get('State');
        $this->items          = $this->get('Items');
        $this->pagination     = $this->get('Pagination');
        $this->params         = $this->state->params;
        
        $this->projectLayout  = $app->input->getCmd("project_layout", "default");
        
        $layout = $this->getLayout();
        
    	switch( $layout ) {
            case "tabbed":
                $this->tabbedLayout($layout);
                break;

            case "imagemenu":
                $this->imagemenuLayout($layout);
                break;
                
    		default:
    			$layout =   "default";
    			// Add template style
                $this->document->addStyleSheet('media/'.$this->option.'/categories/' . $layout . '/style.css', 'text/css', null );
    			break;
    	}
        
        $this->version =   new VipPortfolioVersion();
        
        $this->prepareLightBox();
        $this->prepareDocument();
                
        parent::display($tpl);
    }
    
    protected function tabbedLayout($layout) {
        
        // Add template style
        $this->document->addStyleSheet('media/'.$this->option.'/categories/' . $layout . '/style.css', 'text/css', null );
                
        // Only loads projects from the published categories
        foreach ($this->items as $item){
            $categories[] = $item->id;
        }
        
        $published = 1;
        $projects_ = VipPortfolioHelper::getProjects($categories, $published);
        
        $projects  = array();
        foreach ($projects_ as &$item){
            $projects[$item['catid']][] = $item;  
        }
        
        $this->projects = $projects;
        
    }
    
    protected function imagemenuLayout($layout) {
        
        // Add template style
        $this->document->addStyleSheet('media/'.$this->option.'/categories/' . $layout . '/style.css', 'text/css', null );
        $this->document->addScript('media/'.$this->option.'/js/imagemenu/ImageMenu.js');
        
        $cssStyles = "";
        foreach($this->items as $item) {
            $cssStyles .= "
            #itp-vp-image-menu ul li.item" .$item->id."  a {
                background: url('". $this->params->get("images_directory")."/" . $item->image."') repeat scroll 0%;
            }
            ";
        }
        if(!empty($cssStyles)) {
            $this->document->addStyleDeclaration($cssStyles);
        }
        
        $js = "window.addEvent('domready', function(){
    	  var myMenu = new ImageMenu(
    	    $$('#itp-vp-image-menu a'),{
        	    openWidth:" . $this->params->get("cimgmenuOpenWidth", 310) .", 
        	    border:" . $this->params->get("cimgmenuBorder", 2).", 
        	    duration:" .  $this->params->get("cimgmenuDuration", 400).", 
    	    	OnClickOpen:function(e,i){ 
        	     window.location = e;
        	    }
    	    });
        });";
        
        $this->document->addScriptDeclaration($js);
                
    }
    
    protected function prepareLightBox() {
        
        $modal          = "";
        $layout         = $this->getLayout();
        $hasModal       = false;
        
        switch($layout) {
            case "tabbed":
                $modal = $this->params->get("ctabModal");
                break;
        }
        
        switch($modal) {
            
            case "slimbox":
                
                JHTML::_('behavior.framework');
                
                $this->document->addStyleSheet('media/'.$this->option.'/js/slimbox/css/slimbox.css');
                $this->document->addScript('media/'.$this->option.'/js/slimbox/slimbox.js');
                
                break;
            
            case "native": // Joomla! native
                // Adds a JavaScript needs for modal windows
                JHTML::_('behavior.modal', 'a.vip-modal');
                break;
        }
        
        $this->modal    = $modal;
    }
    
    /**
     * Prepares the document
     */
    protected function prepareDocument(){
        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        
        //Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));
        
        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if($menu){
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        }else{
            $this->params->def('page_heading', JText::_('COM_VIPPORTFOLIO_CATEGORIES_DEFAULT_PAGE_TITLE'));
        }
        
        // Set page title
        $title = $this->params->get('page_title', '');
        if(empty($title)){
            $title = $app->getCfg('sitename');
        }elseif($app->getCfg('sitename_pagetitles', 0)){
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        }
        $this->document->setTitle($title);
        
        // Meta Description
        if($this->params->get('menu-meta_description')){
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }
        
        // Meta keywords
        if($this->params->get('menu-meta_keywords')){
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }
        
    }
    
}