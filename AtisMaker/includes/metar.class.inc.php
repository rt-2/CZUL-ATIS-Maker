<?php
class MetarPart 
{
	public function __construct()
	{
		$this->name='';
		$this->result_str='';
	}
	public function SetResultString($resultString)
	{
		$this->result_str = $resultString;
	}
}
class MetarSubPart extends MetarPart
{
	public function __construct()
	{
		MetarPart::__construct();
	}
	public function Add($name)
	{
		$this->name = $name;
	}
	public function SetResultString($resultString)
	{
		MetarPart::SetResultString($resultString);
	}
}
class MetarMainPart extends MetarPart
{
    public static $allMetarMainPartsByNames = array();
	
	public function __construct()
	{
		MetarPart::__construct();
		$this->regex='';
		$this->subPartsByNames = array();
	}
	public function SetNew($name, $regex, $alwaysThere = true)
	{
		$this->name=$name;
		$this->regex = '(?<'.$name.'>(?<=\s)?'.$regex.'(?=\s)?)'.($alwaysThere === false ? '?' : '');
		self::$allMetarMainPartsByNames[$name] = $this;
	}
	public function addSubPart($names, $regex)
	{
		preg_match_all($regex, $this->result_str, $matches);
		foreach($names as $this_name)
		{
			$this->subPartsByNames[$this_name] = new MetarSubPart();
			$this->subPartsByNames[$this_name]->Add($this_name);
			$this->subPartsByNames[$this_name]->SetResultString($matches[$this_name][0]);
		}
	}
}

?>