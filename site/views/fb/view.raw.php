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

class VipPortfolioViewFb extends JView {
    
    protected $state       = null;
    protected $items       = null;
    protected $pagination  = null;
    
    protected $option;
    
    public function __construct($config) {
        parent::__construct($config);
        $this->option = JFactory::getApplication()->input->get("option");
    }
    
    /**
     * Display the view
     *
     * @return  mixed   False on error, null otherwise.
     */
    public function display($tpl = null){
        
        $app = JFactory::getApplication();
        /** @var $app JSite **/
        
        // Check for valid category
        $this->categoryId = $app->input->get->getInt("catid");
        $this->category   = null;
        
        if(!$this->categoryId){
            $this->category = VipPortfolioHelper::getCategory($this->categoryId);
            // Checking for published category
            if(!$this->category OR !$this->category->published){
                throw new Exception(JText::_("ITP_ERROR_CATEGORY_DOES_NOT_EXIST"));
            }
        }
        
        // Initialise variables
        $this->state      = $this->get('State');
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->params     = $this->state->params;
        
        $layout = $this->getLayout();
        
        switch($layout){
            
            default:
                $layout = "default";
                $this->defaultLayout($layout);
                break;
        }
        
        $this->version = new VipPortfolioVersion();
        $this->format = $this->document->getType();
        
        $this->prepareDocument();
        parent::display($tpl);
    }
    
    protected function defaultLayout($layout){
        
        // Add template style
        $this->document->addStyleSheet('media/'.$this->option.'/projects/' . $layout . '/style.css', 'text/css');
                
        if($this->params->get("extra_image")){
            foreach($this->items as $item){
                // Extra Images
                $extraImages[$item->id] = VipPortfolioHelper::getExtraImages($item->id);
            }
            $this->extraImages = $extraImages;
            $this->extraMax = $this->params->get("extra_max");
        }
    
    }
    
    /**
     * Prepares the document
     */
    protected function prepareDocument(){
        
        //Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));
    }
    
}