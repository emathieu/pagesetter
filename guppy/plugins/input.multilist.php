<?php

class GuppyInput_multilist extends GuppyInput
{
  var $lid;
  
  function parseTypeData() {
  	// Get Config
    list ($lid) = explode(':', $this->typeData);
    
    $this->lid = $lid;
  }
  
  function render($guppy)
  {
  
  	$this->parseTypeData();
  	
  	$htmlClass = 'pn_list';
  	
    if ($this->mandatory)
      if ($this->data == '')
        $htmlClass .= " mde";
      else
        $htmlClass .= " mdt";

    $style = $this->getHtmlStyle();
    
    if (!isset($this->lid))
    	return "No list ID for multible list";
    	
	if (!pnModAPILoad('pagesetter','admin'))
      return _MODAPILOADFAILED;
      
    $list = pnModAPIFunc('pagesetter',
                         'admin',
                         'getList',
                         array('lid' => $this->lid));

	//The ids are separated by semi-colons, so we need to strip'em and create a array
	$vals = explode(";", $this->value);
	unset($vals[0]);
	unset($vals[count($vals)-1]);

	$bag = "<select size=\"8\" multiple=\"multiple\" name=\"" . $this->name . "[]\" id=\"" . $this->ID . "\" class=\"$htmlClass\" $style>\n";

		//Get the full list of items, cross check with $vals to select items
		foreach($list['items'] as $items){
        //print_r ($items) ;

			$bag .= "<option value='$items[id]'";
			foreach($vals as $j){
				if( $j == $items[id]){
					$bag .= " selected='SELECTED'";
					//break;
				}
			}
			$bag .= ">$items[title]</option>\n";
		}

	    $bag .= "</select>\n";

	    return $bag;

  }


  function decode()
  {
	//This retrieves the array of ids and stores the results in a semi-colon separated string
	$bag = ':';
	foreach ($_POST[$this->name] as $id)
		$bag .= $id.':';
    $this->value = $bag;

      // If magic quotes are on then all query/post variables are escaped - so strip slashes
    if (get_magic_quotes_gpc())
      $this->value = stripslashes($this->value);
      
    return $this->value;
  }


  function validate()
  {

    if ($this->value == ''  ||  $this->value == null)
    {
      if ($this->mandatory)
      {
        $this->error = _GUPPYMISSINGMANDATORY;
        return false;
      }
    }
    return true;
  }


  function getErrorMessage()
  {
    return $this->error;
  }


  // ===[ Pagesetter interface ]==============================================

  function active()
  {
	return true;
  }
  
  function useExtraTypeInfo() {
  	return true;
  }
  
  function useFilterHandler() {
  	return true;
  }
  
  function useOrderByHandler() {
  	return false;
  }
  
  function getFilterSQL ($operator, $value, $tableName, &$tableColumns) {
  	include_once('modules/pagesetter/common.php');
  	
  	if (!pnModAPILoad('pagesetter','admin'))
      return _MODAPILOADFAILED;
  	
  	$join = '';
  	
  	$tid = pagesetterGetTID();
  	$pubInfo = pnModAPIFunc('pagesetter', 'admin', 'getPubTypeInfo', array('tid' => $tid));
  	$fieldIndex = $pubInfo['fieldIndex'][$this->name];
  	$fieldID = $pubInfo['fields'][$fieldIndex]['id'];
  	$field = pagesetterGetPubColumnName($fieldID);
  	$sql = '';
  	
  	switch (operator) {
  		case 'eq':
  		case 'like':
  		case 'in':
  			$ids = explode(';', $value);
  			foreach ($ids as $id) {
  				$sql .= " AND $tableName.$field LIKE '%:". pnVarPrepForStore($id) . ":%'";
  			}
	  		break;
  		case 'ne':
  		case 'nin':
  			$ids = explode(';', $value);
  			foreach ($ids as $id) {
  				$sql .= " AND $tableName.$field NOT LIKE '%:". pnVarPrepForStore($id) . ":%'";
  			}
  			break;
  		default:
  			return pagesetterErrorApi(__FILE__, __LINE__, "Unknown filter operator '$operator'.");
  			break;
  	}
  	
  	return compat('join','sql');
  }
  
  function getTitle()
  {
    return 'Multiple List';
  }

  function getDefaultWidth()
  {
    return 400;
  }

  function getDefaultHeight()
  {
    return 400;
  }

  function getSqlType()
  {
      return 'VARCHAR(255)';
  }

  function getSqlFormat()
  {
    return null;
  }
}
?>
