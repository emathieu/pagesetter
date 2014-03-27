<?php

require_once 'modules/pagesetter/guppy/plugins/input.string.php';


class GuppyInput_mediashareid extends GuppyInput_string
{
  function render($guppy)
  {
    $htmlClass = 'mshtmlhtml';

    if ($this->mandatory)
      if ($this->data == '')
        $htmlClass .= " mde";
      else
        $htmlClass .= " mdt";

    $style = $this->getHtmlStyle();
    if ($style != '')
      $style = " style=\"$style\"";

    if ($this->readonly)
      $readonly = " readonly=\"1\"";
    else
      $readonly = '';

    $imgHtml = htmlspecialchars($this->value);
    
    $html = "<script type=\"text/javascript\" src=\"modules/mediashare/pnjavascript/finditem.js\"></script>\n";

    $html .= "<input type=\"text\" name=\"" . $this->name . "\" id=\"" . $this->ID . "\" class=\"$htmlClass\" $style value=\"$imgHtml\"$readonly/>";

    $id = $this->ID;

    $popupUrl = pnModUrl('mediashare', 'external', 'finditem', 
                         array('url' => 'relative', 'mode' => 'id'));
    $popupHtml = "&nbsp; <button type=\"button\" class=\"popup-button\" onclick=\"mediashareFindItem('$id','$popupUrl')\">...</button>";

    return $html . $popupHtml;
  }


  function validate()
  {
    if (!parent::validate())
      return false;

    if (!$this->mandatory  &&  $this->value == '')
      return true;

    return true;
  }


  // ===[ Pagesetter interface ]==============================================

  function active()
  {
    return true;
  }

  function getTitle()
  {
    return _GUPPYMEDIASHAREID;
  }

  function getSqlType()
  {
    return 'VARCHAR(255)';
  }
}

?>
