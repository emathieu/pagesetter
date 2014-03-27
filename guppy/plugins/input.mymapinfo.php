<?php

require_once 'modules/pagesetter/guppy/plugins/input.string.php';
require_once("modules/pagesetter/common.php");
require_once("modules/pagesetter/pnmymap.php");


class GuppyInput_mymapinfo extends GuppyInput_string
{
	var $fid;
	var $infoType;

    function parseTypeData() {
		list($fid, $infoType) = explode(':',$this->typeData, 2);
		$this->fid = $fid;
		$this->infoType = $infoType;
   }

	function render($guppy, $fieldSpec, $element, $isNew)
	{
        $this->parseTypeData();

		// $tid = pagesetterGetTID($args);
		// if ($tid === false)
			// return pagesetterErrorPage(__FILE__, __LINE__, 'No default publication type specified (go to admin :: pagesetter :: configuration :: general)');

		// $htmlClass = 'mymap';
		// $previousvalue = $this->value;

		// if ($this->mandatory)
			// if ($this->data == '')
				// $htmlClass .= " mde";
			// else
				// $htmlClass .= " mdt";

		// $style = $this->getHtmlStyle();
		// if ($style != '')
			// $style = " style=\"$style\"";

		// if ($this->readonly)
			// $readonly = " readonly=\"1\"";
		// else
			// $readonly = '';

		// if (!$isNew || ($this->dataType != 1)) {
			$imgHtml = htmlspecialchars($this->value);
			$id = $this->ID;
			$html = "<script type=\"text/javascript\" src=\"modules/pagesetter/pnjavascript/findmymap.js\"></script>\n";
			$html .= "<input type=\"text\" name=\"" . $this->name . "\" id=\"" . $this->ID . "\" class=\"$htmlClass\" $style value=\"$imgHtml\"$readonly/>";
			// $popupUrl = pnModUrl('pagesetter', 'mymap', 'selectmap', array('tid' => $tid, 'dataType' => $this->dataType));
			// $popupHtml = "&nbsp; <button type=\"button\" class=\"popup-button\" onclick=\"pagesetterSelectMap('$id', $this->dataType, '$popupUrl')\">...</button>";
		// } else {
			// $html = "Non disponible en création";
		// }

		return $html;
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
		return "MyMap Infos";
	}

	function getSqlType()
	{
		return 'VARCHAR(255)';
	}

	function OnPrePublicationCreated($args)
	{
		// Dirty way to get field name
		$tmp = explode(":", $this->name);
		$fname = $tmp[2];
        $this->parseTypeData();
		foreach ($args['pubInfo']['fields'] as $field) {
			if ($field['id'] === $this->fid){
				$mkinfo = pagesetter_mymap_decode($args['pubData'][$field['name']]);
				if (isset($mkinfo['lat'])) {
					$coords = array('lat' => $mkinfo['lat'],'lng' => $mkinfo['lng']);
				} else {
					$marker = pnModAPIFunc('MyMap','user','getMarkers',array('id' => $mkinfo['mkid']));
					$coords = array(
							'lat'	=> $marker[0]['lat'],
							'lng'	=> $marker[0]['lng']);
				}
				$info = pagesetter_mymap_reverse($coords, $this->infoType);
				$args['pubData'][$fname] = $info;
			}
		}
		return true;
	}

	function OnPrePublicationUpdated($args)
	{
		return $this->OnPrePublicationCreated($args);
	}

}
?>
