<?php

Loader::LoadClass('CategoryUtil');

class GuppyInput_categorylist extends GuppyInput
{
    var $pcat;
    var $type;

    function parseTypeData() {
        // Get Config
        if (is_numeric($this->typeData))
        $this->typeData .= ':2';

        list ($pcat,$type) = explode(':', $this->typeData);

        $this->pcat = $pcat;
        $this->type = $type;
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
        if (!isset($this->pcat))
        return "No parent category for category list";

        $cats = CategoryUtil::getSubCategories($this->pcat);

        //The ids are separated by semi-colons, so we need to strip'em and create a array
        $vals = explode(":", $this->value);


        unset($vals[0]);
        unset($vals[count($vals)]);
        if ($this->type <= 2) {
            $bag = '<select '.($this->type == 2?'size="8" multiple="multiple" ':'').'name="' . $this->name . '[]" id="' . $this->ID . "\" class=\"$htmlClass\" $style>\n";
            //Get the full list of items, cross check with $vals to select items
            foreach($cats as $cat) {
                $bag .= '<option value="'.$cat['id'].'"';
                if(array_search($cat['id'], $vals) !== false) {
                    $bag .= ' selected="selected" ';
                    //break;
                }
                $bag .= ">".guppy_translate($cat['name'])."</option>\n";
            }
            $bag .= "</select>\n";
        } elseif ($this->type == 3) {
            $bag = '<div style="margin: 15px">';
            foreach($cats as $cat) {
                $bag .= '<input type="checkbox" name="'.$this->name.'[]" id="'.$this->ID.'" value="'.$cat['id'].'"';
                if(array_search($cat['id'], $vals) !== false) {
                    $bag .= ' checked="checked" ';
                }
                $bag .= ' />&nbsp;&nbsp;'.guppy_translate($cat['name']).'<br />';
            }
            $bag .= '</div>';
        }

        return $bag;

    }


    function decode()
    {
        //This retrieves the array of ids and stores the results in a semi-colon separated string
        $bag = ':';
        $value = FormUtil::getPassedValue($this->name, array(), 'GETPOST');

        foreach ($value as $id)
        $bag .= $id.':';
        $this->value = $bag;

        // If magic quotes are on then all query/post variables are escaped - so strip slashes
        if (get_magic_quotes_gpc())
        $this->value = stripslashes($this->value);

        return $this->value;
    }


    function validate()
    {
        if (empty($this->value) || is_null($this->value) || $this->value == ':') {
            if ($this->mandatory) {
                $this->error = __('Please enter something in', $dom);
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

        switch ($operator) {
            case 'eq':
            case 'like':
            case 'in':
                $ids = explode(';', $value);
                $first = true;
                foreach ($ids as $id) {
                    if (!$first)
                    $sql .= " AND";
                    $first = false;
                    $sql .= " LOCATE(':". DataUtil::formatForStore($id) . ":', $tableName.$field) > 0";
                }
                break;
            case 'ne':
            case 'nin':
                $ids = explode(';', $value);
                $first = true;
                foreach ($ids as $id) {
                    if (!$first)
                    $sql .= " AND";
                    $first = false;
                    $sql .= " LOCATE(':". DataUtil::formatForStore($id) . ":', $tableName.$field) = 0";
                }
                break;
            default:
                return pagesetterErrorApi(__FILE__, __LINE__, "Unknown filter operator '$operator'.");
                break;
        }
        return compact('join','sql');
    }

    function getTitle()
    {
        return 'Category List';
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
