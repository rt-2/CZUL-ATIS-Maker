<?php

class NotamTextAdjustments {
    
    public static $all_abbrevs = [];

    private $abbrev = null;
    private $word = null;
    private $regex = null;

    public function __construct($abbrevStr, $wordStr) {
        
        $this->abbrev = $abbrevStr;
        $this->word = $wordStr;
        $this->regex = $abbrevStr;
        //echo 'ended';
        //echo 'ended';
        //echo 'ended';
        self::$all_abbrevs[] = serialize($this);

    }

    static public function AdjustAndReturnText($this_str) {
    
           //var_dump(self::$all_abbrevs);
        foreach(self::$all_abbrevs as $abbrev)
        {
            $abbrev= unserialize($abbrev);

            //var_dump($abbrev);

            //var_dump($abbrev->getRegex());

            //var_dump($abbrev->getWord());

            //echo '<br><br>';
            $this_str = preg_replace ( $abbrev->getRegex() , $abbrev->getWord() , $this_str);
            $this_str = str_replace ( ',' , '' , $this_str);
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



?>