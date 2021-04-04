<?php

/*

*/
class AtisConstructor {
    
    public static $HEAD = "\t";
    public static $SEP = " ";
    public static $FOOT = " \r\t\t";

    public $all_sections = [];

    public function __construct() {
        
    }
    //
    public function addSection($text)
    {
        $this->all_sections[] = (string)$text;
    }
    //
    public function returnResult()
    {
        
        global $return_str;
        $return_str = '';
        $i = 0;
        
        foreach($this->all_sections as $section)
        {
            if(strlen($section) > 0)
            {
                if($i != 0) $return_str .= self::$SEP;
                $return_str .= $section;
                $i++;
            }
        }
        
        $return_str = preg_replace ( '/(?<=\W|^)(\d{2}?)r(?=\W|$)/' , "$1R" , $return_str);
        $return_str = preg_replace ( '/(?<=\W|^)(\d{2}?)l(?=\W|$)/' , "$1L" , $return_str);
        $return_str = preg_replace ( '/(?<=\W|^)(\d{2}?)c(?=\W|$)/' , "$1C" , $return_str);
        $return_str = preg_replace ( '/(?<=\W|^)(\d{2}?)d(?=\W|$)/' , "$1D" , $return_str);
        $return_str = preg_replace ( '/(?<=\W|^)(\d{2}?)g(?=\W|$)/' , "$1G" , $return_str);
        
        if(strlen($return_str) > 0)
        {
            $return_str = (self::$HEAD) . $return_str . (self::$FOOT);
        }

        return $return_str;
    }
}

/*

*/
class AtisSectionConstructor {
    
    public static $HEAD = "";
    public static $SEP = " [,] ";
    public static $FOOT = " [,] "; // Used to be [;] but the voice recorder can't handle those anymore...

    public $all_sections = [];

    public function __construct() {
        
    }
    //
    public function addSection($text)
    {
        $this->all_sections[] = (string)$text;
    }
    //
    public function returnResult()
    {
        $return_str = '';
        $i = 0;

        foreach($this->all_sections as $section)
        {
            if(strlen($section) > 0)
            {
                if($i != 0) $return_str .= self::$SEP;
                $return_str .= $section;
                $i++;
            }
        }
        
        if(strlen($return_str) > 0)
        {
            $return_str[0] = strtoupper($return_str[0]);
            $return_str = (self::$HEAD) . $return_str . (self::$FOOT);
        }
        
        return $return_str;
    }
    
}
?>
