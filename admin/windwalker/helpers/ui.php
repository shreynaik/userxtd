<?php
/**
 * @package     Windwalker.Framework
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */


// No direct access
defined('_JEXEC') or die;

/**
 * A UI helper to generate modal etc.
 *
 * @package     Windwalker.Framework
 * @subpackage  Helpers
 */
class AKHelperUi {    
    
    /**
     * Set a HTML element as modal container.
     * 
     * @param   string  $selector   Modal ID to select element.
     * @param   array   $option     Modal options. 
     */
    public static function modal($selector, $option = array())
    {
        $doc = JFactory::getDocument();
        
        if(JVERSION >= 3) {
            JHtml::_('bootstrap.modal', $selector);
        }else{
            JHtml::_('behavior.modal');
            
            $script =
<<<SCRIPT
            window.addEvent('domready', function(){
                SqueezeBox.assign($$('a#{$selector}_link'), {
                    parse: 'rel',
                    onOpen: function(e) {
                        e.getChildren().show();
                    }
                });
                
                $('{$selector}').hide();
                
            });
SCRIPT;

            $doc->addScriptDeclaration($script);
        }
    }
    
    /**
     * The link to open modal.
     * 
     * @param   string  Modal title.
     * @param   string  Modal select ID.
     * @param   array   modal params.
     *
     * @return  string  Link body text.    
     */
    public static function modalLink($title, $selector, $option = array())
    {
        $tag     = JArrayHelper::getValue($option, 'tag', 'a');
        $id     = isset($option['id']) ? " id=\"{$option['id']}\"" : "id=\"{$selector}_link\"";
        $class     = isset($option['class']) ? " class=\"{$option['class']} cursor-pointer\"" : 'class="cursor-pointer"';
        $onclick = isset($option['onclick']) ? " onclick=\"{$option['onclick']}\"" : '';
        $icon    = JArrayHelper::getValue($option, 'icon', '');
        
        if( JVERSION >= 3 ) {
            $button = "<{$tag} data-toggle=\"modal\" data-target=\"#$selector\"{$id}{$class}{$onclick}>
                    <i class=\"{$icon}\" title=\"$title\"></i>
                    $title</{$tag}>" ;
        }
        else
        {
            $rel    = JArrayHelper::getValue($option, 'rel');
            $rel    = $rel ? " rel=\"{$rel}\"" : '';
            $button = "<{$tag} href=\"#{$selector}\"{$id}{$class}{$onclick}{$rel}>{$title}</{$tag}>" ;
        }
        
        return $button;
    }
    
    /**
     * Put content and render it as modal box HTML.
     *
     * @param   string  $selector  The ID selector for the modal.
     * @param   array   $content   HTML content to put in modal.
     * @param   string  $option    Optional markup for the modal, footer or title.
     *
     * @return  string  HTML markup for a modal
     *
     * @since   3.0
     */
    public static function renderModal($selector = 'modal', $content = '', $option = array())
    {
        self::modal($selector, $option) ;
        
        $header = '';
        $footer = '';
        
        // Header
        if( !empty($option['title']) ) {
            $header = <<<HEADER
<div class="modal-header">
    <button type="button" role="presentation" class="close" data-dismiss="modal">x</button>
    <h3>{$option['title']}</h3>
</div>
HEADER;
        }
        
        //Footer
        if( !empty($option['footer']) ) {
            $footer = <<<FOOTER
<div class="modal-footer">
    {$option['footer']}
</div>
FOOTER;
        }
        
        // Box
        $html = <<<MODAL
<div class="modal hide fade {$selector}" id="{$selector}">
{$header}

<div id="{$selector}-container" class="modal-body">
    {$content}
</div>

{$footer}
</div>
MODAL;
        
        
        return $html ;
    }
    
    /**
     * getQuickaddForm
     */
    static public function getQuickaddForm($id, $path, $extension = null)
    {
        $content = '';
        
        try
        {
            $form = new JForm($id.'.quickaddform', array('control' => $id));
            $form->loadFile(JPATH_ROOT.'/'.$path) ;
        }
        catch (Exception $e)
        {
            Jerror::raiseWarning(404, $e->getMessage());
            return false;
        }
        
        // Set Category Extension
        if( $extension ) {
            $form->setValue('extension', null, $extension) ;
        }
        
        
        $fieldset = $form->getFieldset('quickadd') ;
        
        foreach( $fieldset as $field ):
            
            $content .= "<div class=\"control-group\" id=\"{$field->id}-wrap\">
                            <div class=\"control-label\">
                                {$field->label}
                            </div>
                            <div class=\"controls\">
                                {$field->input}
                            </div>
                        </div>" ;
        endforeach;
        
        if(JVERSION < 3) {
            $content = "<fieldset class=\"adminform form-horizontal\">{$content}</fieldset>" ;
        }
        
        return $content ;
    }
}


