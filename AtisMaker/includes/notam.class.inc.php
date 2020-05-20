<?php

require_once(dirname(__FILE__).'/../resources/notams.lib.inc.php');

class NotamTextAdjustments {
    
    public static $all_abbrevs = [];

    private $abbrev = null;
    private $word = null;
    private $regex = null;

    public function __construct($abbrevStr, $wordStr) {
        
        $this->abbrev = $abbrevStr;
        $this->word = $wordStr;
        $this->regex = $abbrevStr;
        self::$all_abbrevs[] = serialize($this);
    }
    
    static public function AdjustAndReturnText($this_str) {
    
        foreach(self::$all_abbrevs as $abbrev)
        {
            $abbrev= unserialize($abbrev);
            $this_str = preg_replace ( $abbrev->getRegex() , $abbrev->getWord() , $this_str);
        }

        return $this_str;
    }
    
    public function GetWord()
    {
        return $this->word;
    }

    public function getRegex()
    {
        return '/(?<=\W|^)'.$this->regex.'(?=\W|$)/';
    }


}
class NotamTextTranslations {
    
    public static $all_translations = [];

    private $word_before = null;
    private $word_after = null;
    private $regex = null;
    
    public function __construct($wordBefore, $wordAfter) {
        
        $this->word_before = $wordBefore;
        $this->word_after = $wordAfter;
        $this->regex = '/(?<=\W|^)'.$wordBefore.'(?=\W|$)/';
        self::$all_translations[] = serialize($this);

    }
    //
    static public function TranslateText($this_str) {
    
        foreach(self::$all_translations as $translation)
        {
            $translation_data = unserialize($translation);
            $this_str = preg_replace ( $translation_data->GetRegex() , $translation_data->GetWordAfter() , $this_str);
        }

        $this_str = preg_replace ( '/(?<=\W|^)(\d{2})L(?=\W|$)/' , '$1G' , $this_str);
        $this_str = preg_replace ( '/(?<=\W|^)(\d{2})R(?=\W|$)/' , '$1D' , $this_str);

        return $this_str;
    }
    //
    public function GetWordBefore()
    {
        return $this->word_before;
    }
    //
    public function GetWordAfter()
    {
        return $this->word_after;
    }
    //
    public function GetRegex()
    {
        return $this->regex;
    }
    
}

?>
