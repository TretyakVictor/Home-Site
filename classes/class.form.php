<?php
class Form{
  public $fields;
  protected $action, $method, $button_caption, $button_name, $form_name, $css_td_class, $css_td_style, $css_fld_class, $css_fld_style;

  public function __construct($flds, $button_caption, $button_name = "", $form_name = "",  $method = "POST", $action = "", $css_td_class = "", $css_td_style = "", $css_fld_class = ""){
    $this->fields = $flds;
    $this->method = $method;
    $this->action = $action;
    $this->button_name = $button_name;
    $this->form_name = $form_name;
    $this->button_caption = $button_caption;
    $this->css_td_class = $css_td_class;
    $this->css_td_style = $css_td_style;
    $this->css_fld_class = $css_fld_class;
    $this->css_td_style = $css_td_style;
    foreach ($flds as $key => $value) {
      if (!is_subclass_of($value, "Field")) {
        throw new ExceptionObject($key, "\"$key\" не является элементом управления");
      }
    }
  }
  public function print_form(){
    $enctype = "";
    if (!empty($this->fields)) {
      foreach ($this->fields as $value){
        if (!empty($this->css_fld_class)) {
          $value->css_class = $this->css_fld_class;
          $value->css_style = $this->css_fld_style;
        }
        if (!empty($this->css_fld_style)) {
          $value->css_style = $this->css_fld_style;
        }
        if ($value->get_type() == "file") {
          $enctype = "enctype='multipart/form-data'";
        }
      }
    }
    if (!empty($this->css_td_style)) {
      $style = "style=\"".$this->css_td_style."\"";
    }else {
      $style = "";
    }
    if (!empty($this->css_td_class)) {
      $class = "class=\"".$this->css_td_class."\"";
    }else {
      $class = "";
    }
    if (empty($this->form_name)) {
      $this->form_name = "form";
    }
    if (!empty($this->action)) {
      echo "<form name=\"".htmlspecialchars($this->form_name, ENT_QUOTES)."\" class='form-horizontal' role='form' action='{$this->action}' $enctype method={$this->method} >";
    }else {
      echo "<form name=\"".htmlspecialchars($this->form_name, ENT_QUOTES)."\" class='form-horizontal' role='form' $enctype method={$this->method} >";
    }
    if (!empty($this->fields)) {
      foreach ($this->fields as $value) {
        list($caption, $tag, $help) = $value->get_html();
        if (is_array($tag)) {
          $tag = ipmlode("<br>", $tag);
        }
        switch ($value->get_type()) {
          case "hidden":
            echo $tag;
            break;

          default:
            echo "<div class='form-group secondMenuGrp'>
              <label class='control-label col-sm-3'>$caption</label>
              <div class='col-sm-9'>
                $tag
            </div></div>";
            if (!empty($help)) {
              echo "<div $style class='form-group secondMenuGrp $class'>";
              echo "$help</div>";
            }
            break;
        }
      }
    }
    echo "<div class='form-group secondMenuGrp'>
      <div class='col-sm-offset-3 col-sm-9'>";
      if (!empty($this->button_name)) {
        echo "<input type=submit class='btn btn-default' name=\"".htmlspecialchars($this->button_name, ENT_QUOTES)."\" value=\"".htmlspecialchars($this->button_caption, ENT_QUOTES)."\">";
      } else {
        echo "<input type=submit class='btn btn-default' value=\"".htmlspecialchars($this->button_caption, ENT_QUOTES)."\">";
      }
      echo "</div>
    </div>
    </form>";
  }
  public function __toString(){
    $this->print_form();
  }
  public function check(){
    $arr =array();
    if (!empty($this->fields)) {
      foreach ($this->fields as $value) {
        $str = $value->check();
        if (!empty($str)) {
          $arr[] = $str;
        }
      }
    }
    return $arr;
  }
}
?>
