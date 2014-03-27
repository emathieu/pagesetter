<?php

require_once 'modules/pagesetter/guppy/plugins/input.string.php';


class GuppyInput_web_links extends GuppyInput_string
{
	
  var $onlySelectedCategory;
  var $selectedcategory;
  var $singleDropDown;
  var $previousvalue;
	
  function parseTypeData()
  {
 	list($onlySelectedCategory, $selectedcategory, $singleDropDown) = explode(':',$this->typeData);

  	if ($onlySelectedCategory !== '') {
  		$this->onlySelectedCategory = $onlySelectedCategory;
  	} else {
  		$this->onlySelectedCategory = false;
  	}

  	if (is_numeric($selectedcategory)) {
  		$this->selectedcategory = $selectedcategory;
  	} else {
  		$this->selectedcategory = 0;
  		$this->onlySelectedCategory = false;
  	}
  	if ($singleDropDown !== '') {
  		$this->singleDropDown = $singleDropDown;
  	} else {
  		$this->singleDropDown = false;
  	}
  }
  
  function render($guppy, $fieldSpec, $element, $isNew)
  {
    $htmlClass = 'web_kinks';

  	$this->parseTypeData();
  	
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
    $previousvalue = $this->value;
   
 	if (!SecurityUtil :: checkPermission('Web_Links::', '::', ACCESS_READ))
		return LogUtil :: registerPermissionError();

	if (!$isNew) {
		if ($this->onlySelectedCategory == 1) {
			$orderbysql = pnModAPIFunc('Web_Links', 'user', 'orderbyin', array ('orderby' => "titleA"));
			$links = pnModAPIFunc('Web_Links', 'user', 'weblinks', array ('cid' => $this->selectedcategory, 'orderbysql'=> $orderbysql));

			$html = "<select id=\"" . $this->ID . "\" name=\"" . $this->name . "\">\n";
			$html .= "<option value=\"\"></option>";
			foreach ($links as $link) {
				if ($link['lid'] == $this->value) {
					$html .= "<option value=\"".$link['lid']."\" selected=\"selected\">".$link['title']."</option>";
				} else {
					$html .= "<option value=\"".$link['lid']."\">".$link['title']."</option>";    			
				}
			}
			$html .= "</select>";
		} elseif ($this->singleDropDown == 1) {
			$links = pnModFunc('pagesetter', 'weblinks', 'alllinks', array ());

			$html = "<select id=\"" . $this->ID . "\" name=\"" . $this->name . "\">\n";
			$html .= "<option value=\"\"></option>";
			foreach ($links as $key => $link) {
				if ($key == $this->value) {
					$html .= "<option value=\"".$key."\" selected=\"selected\">".$link."</option>";
				} else {
					$html .= "<option value=\"".$key."\">".$link."</option>";
				}
			}
			$html .= "</select>";
		} else {
			$html = "<script type=\"text/javascript\" src=\"modules/pagesetter/pnjavascript/findweblinks.js\"></script>\n";
			$html .= "<input type=\"text\" name=\"" . $this->name . "\" id=\"" . $this->ID . "\" class=\"$htmlClass\" $style value=\"$imgHtml\"$readonly/>";

			$id = $this->ID;

			$popupUrl = pnModUrl('pagesetter', 'weblinks', 'selectlink', array());
			$popupHtml = "&nbsp; <button type=\"button\" class=\"popup-button\" onclick=\"pagesetterSelectLink('$id','$popupUrl')\">...</button>";
		}
	} else {
		$html = "Non disponible en crÃ©ation";
	}
    return $html . $popupHtml;
//    return $html;
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

  function useExtraTypeInfo()
  {
    // Inform the framework about the fact that this plugin
    // uses extra type parameters
    return true;
  }
  
  function getTitle()
  {
    return "Web_Links";
  }

  function getSqlType()
  {
    return 'VARCHAR(255)';
  }

  function OnPublicationCreated($args)
  {
	$title = $args['data']['core_title'];
	if ( $title == "" ) {
#		echo "//<pre><br>\n";
#		echo "// DEBUG: $title est vide sur creation de la page<br>\n";
#		echo "// il faut savoir où prendre le titre alors.<br>\n";
#		print_r($args);
#		print_r($this);
#		echo "//<br></pre>\n";
#		exit;
		return true;
	} else {
		$lid = $this->value;
		if (is_numeric($lid) && ($lid > 0)) {
        	return pnModFunc('pagesetter','weblinks','updatelink',
							array(	'lid'   => $lid,
									'title' => $title,
									'tid'   => $args['tid'],
									'pid'   => $args['pid']));;
		} else  {
			return true;
		}
  	}
  }

  function OnPublicationUpdated($args)
  {
    // remove pagesetter link in link description
	pnModFunc('pagesetter', 'weblinks', 'removelink', array ('lid' => $this->previousvalue));	
	return $this->OnPublicationCreated($args);

  }

  function OnPublicationDeleted($args)
  {
    // remove pagesetter link in link description
	pnModFunc('pagesetter', 'weblinks', 'removelink', array ('lid' => $this->value));	
	return true;
  }

}

?>
