<?php
abstract class Field{
  // protected $name, $type, $caption, $value, $is_required, $parameters, $help, $help_url;
  // public $css_class, $css_style;
  protected $name, $type, $caption, $value, $is_required, $parameters, $help, $help_url;
  public $css_class, $css_style;

  function __construct($name, $type, $caption, $value = "", $is_required = false, $parameters = "", $help = "", $help_url = ""){
    // $this->name = $this->encodestring($name);
    $this->name = $name;
    $this->type = $type;
    $this->caption = $caption;
    $this->value = $value;
    $this->is_required = $is_required;
    $this->parameters = $parameters;
    $this->help = $help;
    $this->help_url = $help_url;
  }
  abstract function check();
  abstract function get_html();
  public function get_type(){
    return $this->type;
  }
  public function get_value(){
    return $this->value;
  }
  public function _get($key)
  {
    if (isset($this->$key)) {
      return $this->$key;
    }else {
      throw new ExceptionMember($key, "Член "._CLASS_."::$key не существует в текущем контексте");
    }
  }
  protected function encodestring($str)
  {
    $str=strtr($str,"абвгдеёзийклмнопрстуфхъыэ_", "abvgdeeziyklmnoprstufh'iei");
    $str=strtr($str,"АБВГДЕЁЗИЙКЛМНОПРСТУФХЪЫЭ_", "ABVGDEEZIYKLMNOPRSTUFH'IEI");
    $str=strtr($str, array(
        "ж"=>"zh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh", 
        "щ"=>"shch","ь"=>"", "ю"=>"yu", "я"=>"ya",
        "Ж"=>"ZH", "Ц"=>"TS", "Ч"=>"CH", "Ш"=>"SH",
        "Щ"=>"SHCH","Ь"=>"", "Ю"=>"YU", "Я"=>"YA",
        "ї"=>"i", "Ї"=>"Yi", "є"=>"ie", "Є"=>"Ye"));
    return $str;
  }
}
?>
