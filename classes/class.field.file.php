<?php
class FieldFile extends Field{
  protected $dir, $prefix;
  function __construct($name, $caption, $value, $is_required = false, $dir, $prefix = "", $help = "", $help_url = ""){
    parent::__construct($name, "file", $caption, $value, $is_required, "", $help, $help_url);
    $this->dir = $dir;
    $this->prefix = $prefix;
    if (!is_uploaded_file($this->value[$this->name]['tmp_name'])) {
      $this->value = NULL;
    }
    if (!empty($this->value)) {
      $extentions = array("#\.php#is", "#\.php3#is", "#\.php5#is", "#\.phtml#is", "#\.html#is", "#\.htm#is", "#\.hta#is",
      "#\.exe#is", "#\.pl#is", "#\.xml#is", "#\.inc#is", "#\.shtml#is", "#\.xht#is", "#\.xhtml#is", "#\.com#is",
      "#\.bat#is", "#\.cmd#is", "#\.jar#is", "#\.vbs#is", "#\.access#is", "#\.js#is");
      $path_parts = pathinfo($this->value[$this->name]['name']);
      $extens = ".".$path_parts['extension'];
      $path = basename($this->value[$this->name]['name'], $extens);
      $add = $extens;
      foreach ($extentions as $exten) {
        if (preg_match($exten, $extens)) {
          $add = ".txt";
        }
      }
      $path .= $add;
      $path = str_replace("//", "/", $dir."/".$prefix.$path);
      // Заменить copy на move_uploaded_file
      if (move_uploaded_file($this->value[$this->name]['tmp_name'], $path)) {
        @unlink($this->value[$this->name]['tmp_name']);
        @chmod($path, 0644);
      }
    }
  }
  function get_html(){
    if (!empty($this->css_style)) {
      $style = "style=\"".$this->css_style."\"";
    }else {
      $style = "";
    }
    if (!empty($this->css_class)) {
      $class = "class=\"".$this->css_class."\"";
    }else {
      $class = "";
    }
    $tag = "<input $style class='form-control $class'
      type=\"".$this->type."\"
      name=\"".$this->name."\">\n";
    if ($this->is_required) {
      $this->caption .= " *";
    }
    $help = "";
    if (!empty($this->help)) {
      $help .= "<span style = 'color:blue'>".nl2br($this->help)."</span>";
    }
    if (!empty($help)) {
      $help .= "<br>";
    }
    if (!empty($this->help_url)) {
      $help .= "<span style = 'color:blue'><a href=".$this->help_url.">Помощь</a></span>";
    }
    return array($this->caption, $tag, $help);
  }
  function check(){
    if ($this->is_required) {
      if (empty($this->value[$this->name])) {
        return "Поле \"".$this->caption."\" не заполнено.";
      }
    }
    return "";
  }
  function get_filename(){
    if (!empty($this->value)) {
      if (!empty($this->value[$this->name]['name'])) {
        // return mysql_real_escape_string($this->encodestring($this->prefix.$this->value[$this->name]['name']));
        // return mysql_real_escape_string($this->encodestring($this->prefix.$this->value[$this->name]['name']));
        return $this->encodestring($this->prefix.$this->value[$this->name]['name']);
      }else {
        return "";
      }
    }else {
      return "";
    }
  }
}
?>
