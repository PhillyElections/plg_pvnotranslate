<?php
/**
 * @version     $Id: pvnotranslate.php
 * @package     PVotes
 * @subpackage  Content
 * @copyright   Copyright (C) 2015 Philadelphia Elections Commission
 * @license     GNU/GPL, see LICENSE.php
 * @author      Matthew Murphy <matthew.e.murphy@phila.gov>
 * */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * Example Content Plugin
 *
 * @package     Joomla
 * @subpackage  Content
 * @since       1.5
 */
class plgContentPvnotranslate extends JPlugin
{
    /**
     * Constructor
     *
     * @param object $subject The object to observe
     * @param object $params  The object that holds the plugin parameters
     * @since 1.5
     */
    public function __construct(&$subject, $params)
    {
        parent::__construct($subject, $params);
    }

    /**
     * Default event
     *
     * Isolate the content and call actual processor
     *
     * @param   object      The article object.  Note $article->text is also available
     * @param   object      The article params
     * @param   int         The 'page' number
     */
    public function onPrepareContent(&$article, &$params, $limitstart)
    {
        global $mainframe;
        if (is_object($article)) {
            $this->getPvnotranslateStrings($article->title);
            return $this->getPvnotranslateDisplay($article->text);
        }
        return $this->getPvnotranslateDisplay($article);
    }

    /**
     * Example after display title method
     *
     * Method is called by the view and the results are imploded and displayed in a placeholder
     *
     * @param   object   $article   The article object.  Note $article->text is also available
     * @param   object   $params   The article params
     * @param   int      $limitstart   The 'page' number
     * @return  string
     */
    public function onAfterDisplayTitle(&$article, &$params, $limitstart)
    {
        global $mainframe;
        return '';
    }

    /**
     * Example before display content method
     *
     * Method is called by the view and the results are imploded and displayed in a placeholder
     *
     * @param   object   $article   The article object.  Note $article->text is also available
     * @param   object   $params   The article params
     * @param   int      $limitstart   The 'page' number
     * @return  string
     */
    public function onBeforeDisplayContent(&$article, &$params, $limitstart)
    {
        global $mainframe;
        return '';
    }

    /**
     * Example after display content method
     *
     * Method is called by the view and the results are imploded and displayed in a placeholder
     *
     * @param   object   $article   The article object.  Note $article->text is also available
     * @param   object   $params   The article params
     * @param   int      $limitstart   The 'page' number
     * @return  string
     */
    public function onAfterDisplayContent(&$article, &$params, $limitstart)
    {
        global $mainframe;
        return '';
    }

    /**
     * Example before save content method
     *
     * Method is called right before content is saved into the database.
     * Article object is passed by reference, so any changes will be saved!
     * NOTE:  Returning false will abort the save with an error.
     *  You can set the error by calling $article->setError($message)
     *
     * @param   object   $article   A JTableContent object
     * @param   bool     $isNew   If the content is just about to be created
     * @return  bool        If false, abort the save
     */
    public function onBeforeContentSave(&$article, $isNew)
    {
        global $mainframe;
        return true;
    }

    /**
     * Example after save content method
     * Article is passed by reference, but after the save, so no changes will be saved.
     * Method is called right after the content is saved
     *
     *
     * @param   object   $article   A JTableContent object
     * @param   bool     $isNew   If the content is just about to be created
     * @return  void
     */
    public function onAfterContentSave(&$article, $isNew)
    {
        global $mainframe;
        return true;
    }

    /**
     * Check for a Pvnotranslate block,
     * skip <script> blocks, and
     * call getPvnotranslateStrings() as appropriate.
     *
     * @param   string   $text  content
     * @return  bool
     */
    public function getPvnotranslateDisplay(&$text)
    {
        // Quick, cheap chance to back out.
        if (JString::strpos($text, 'PVNOTRANSLATE') === false) {
            //return true;
        }

        $text = explode('<script', $text);
        foreach ($text as $i => $str) {
            if ($i == 0) {
                $this->getPvnotranslateStrings($text[$i]);
            } else {
                $str_split = explode('</script>', $str);
                foreach ($str_split as $j => $str_split_part) {
                    if (($j % 2) == 1) {
                        $this->getPvnotranslateStrings($str_split[$i]);
                    }
                }
                $text[$i] = implode('</script>', $str_split);
            }
        }
        $text = implode('<script', $text);

        return true;
    }

    /**
     * Find Pvnotranslate blocks,
     * get display per block.
     *
     * @param   string   $text  content
     * @return  bool
     */
    public function getPvnotranslateStrings(&$text)
    {
        // Quick, cheap chance to back out.
        if (JString::strpos($text, 'PVNOTRANSLATE') === false) {
            //return true;
        }
        jimport("kint.kint");
        $search = "(_-Al Schmidt_-|_-Lisa Deeley_-|_-Anthony Clarke_-|_-Deeley_-|_-Schmidt_-|_-Clarke_-)";

        while (preg_match($search, $text, $regs, PREG_OFFSET_CAPTURE)) {
            $string = JString::str_ireplace("_-","",$regs[0][0]);
//            d($regs, $search, $regs[0][0], "<span class=\"notranslate\">$string</div>", $text);
            //$string = $temp[1];

            // Let's make sure it's not a remote file
            $text = JString::str_ireplace($regs[0][0], "<span class=\"notranslate\">".$string."</span>", $text);
//            dd($text);
        }
        return true;
    }
}
