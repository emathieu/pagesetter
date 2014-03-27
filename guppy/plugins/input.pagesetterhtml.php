<?php

require_once 'modules/pagesetter/guppy/plugins/input.string.php';


class GuppyInput_pagesetterhtml extends GuppyInput_string
{
  function render($guppy)
  {
    $htmlClass = 'phhtml';

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

    $imgHtml = htmlspecialchars($this->value, ENT_QUOTES);
    
//    $ihtml = "<script type=\"text/javascript\" src=\"modules/pagesetter/pnjavascript/showimage.js\"></script>\n";

    $html .= "<input type=\"text\" name=\"" . $this->name . "\" id=\"" . $this->ID . "\" class=\"$htmlClass\" $style value=\"$imgHtml\"$readonly/>";

    $id = $this->ID;
    $popupUrl = pnModUrl('pagesetter', 'user', 'pubfind',
                         array('url' => 'relative', 'html' => 'a'));
    $popupHtml = "&nbsp; <button onClick=\"pagesetterFindPub('$id','$popupUrl','relative','string')\">...</button>";

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
    return _GUPPYPAGESETTERHTML;
  }

  function getSqlType()
  {
    return 'VARCHAR(255)';
  }
}

?>
