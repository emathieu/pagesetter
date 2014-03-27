//=============================================================================
// External interface functions
//=============================================================================

var currentPagesetterInput = null;

function pagesetterSelectLink(targetId, pagesetterURL)
{
  	var url = pagesetterURL;

  	currentPagesetterEditor = null;
	currentPagesetterInput = document.getElementById(targetId);
  	if (currentPagesetterInput == null)
    	alert("Unknown input element '" + targetId + "'");

	url += "&lid="+currentPagesetterInput.value;
	
  	window.open(url, "", "width=600pt,height=200pt,resizable=1");
}

//=============================================================================
// Paste link into parent input field
//=============================================================================

function weblinksPasteLink(link)
{
    var value = link;

	// Where to insert the calculated value
  	var targetInputElement = window.opener.currentPagesetterInput;

	// Simply overwrite value of input elements
  	targetInputElement.value = value;

    window.opener.focus();
  	window.close();
}

function weblinksHandleCancel()
{
  window.opener.focus();
  window.close();
}


