<?php

require_once 'modules/pagesetter/guppy/plugins/input.string.php';
require_once("modules/pagesetter/common.php");


class GuppyInput_mymap extends GuppyInput_string
{
	var $previousvalue;
	var $dataType;

    function parseTypeData() {
        $this->dataType = $this->typeData;
   }

	function render($guppy, $fieldSpec, $element, $isNew)
	{
		$this->parseTypeData();

		$tid = pagesetterGetTID($args);
		if ($tid === false)
			return pagesetterErrorPage(__FILE__, __LINE__, 'No default publication type specified (go to admin :: pagesetter :: configuration :: general)');

		$htmlClass = 'mymap';
		$previousvalue = $this->value;

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

		if (!$isNew || ($this->dataType != 1)) {
			$imgHtml = htmlspecialchars($this->value);
			$id = $this->ID;
			$html = "<script type=\"text/javascript\" src=\"modules/pagesetter/pnjavascript/findmymap.js\"></script>\n";
			$html .= "<input type=\"text\" name=\"" . $this->name . "\" id=\"" . $this->ID . "\" class=\"$htmlClass\" $style value=\"$imgHtml\"$readonly/>";
			$popupUrl = pnModUrl('pagesetter', 'mymap', 'selectmap', array('tid' => $tid, 'dataType' => $this->dataType));
			$popupHtml = "&nbsp; <button type=\"button\" class=\"popup-button\" onclick=\"pagesetterSelectMap('$id', $this->dataType, '$popupUrl')\">...</button>";
		} else {
			$html = "Non disponible en création";
		}

		return $html . $popupHtml;
	}

	function validate()
	{
		return true;
	}

	// ===[ Pagesetter interface ]==============================================

	function active()
	{
		return true;
	}

    function useExtraTypeInfo() {
        return true;
    }

	function getTitle()
	{
		return "MyMap Marker";
	}

	function getSqlType()
	{
		return 'VARCHAR(255)';
	}

	function OnPublicationCreated($args)
	{
		// $title = $args['data']['core_title'];
		// $mymap = $this->value;
		// list ($mid, $mkid) = explode ("|", $mymap);
		// if (is_numeric($mkid) && ($mkid > 0)) {
			// return pnModFunc('pagesetter','mymap','updatemarker',
							// array(	'mkid' 	=> $mkid,
									// 'mid' 	=> $mid,
									// 'title' => $title,
									// 'tid' 	=> $args['tid'],
									// 'pid' 	=> $args['pid']));;
		// } else  {
			return true;
		// }
	}

	function OnPublicationUpdated($args)
	{
		// remove pagesetter link in marker description
		// list ($mid, $mkid) = explode ("|", $this->previousvalue);
		// pnModFunc('pagesetter', 'weblinks', 'removelink', array ('mkid' => $mkid));	

		// return $this->OnPublicationCreated($args);
		return true;
	}

	function OnPublicationDeleted($args)
	{
		// remove pagesetter link in marker description
		// list ($mid, $mkid) = explode ("|", $this->previousvalue);
		// pnModFunc('pagesetter', 'weblinks', 'removelink', array ('mkid' => $mkid));	
		return true;
	}
}
?>
