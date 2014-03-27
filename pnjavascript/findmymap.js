//=============================================================================
// External interface functions
//=============================================================================

function pagesetterSelectMap(targetId, dataType, pagesetterURL)
{
  	var url = pagesetterURL;
	currentPagesetterInput = document.getElementById(targetId);
  	currentPagesetterEditor = null;
  	if (currentPagesetterInput == null)
    	alert("Unknown input element '" + targetId + "'");

  	url += "&mid="+currentPagesetterInput.value+"&targetID="+targetId;
  	window.open(url, "", "width=660pt,height=700pt,resizable=0");
}

//=============================================================================
// Paste link into parent input field
//=============================================================================

function googlemapPasteLink(p1, p2, inputID, selectType)
{
	selectType = selectType || 1;
	if (selectType == 1)
		var value = p1 + "|" + p2;
	else
		var value = "lat:" + p1 + "|lng:" + p2;

	// Where to insert the calculated value
  	var targetInputElement = window.opener.document.getElementById(inputID);

	// Simply overwrite value of input elements
  	targetInputElement.value = value;

  	window.close();
}

function onMapChanged(selectElement)
{
  var selectedValue = selectElement.value;
  window.location.replace("/index.php?module=pagesetter&type=mymap&func=selectmap&mid="+selectedValue+"&targetID=f_pubedit_mymap_0");
}

