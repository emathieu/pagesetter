<?php

function smarty_modifier_ps_icon($string)
{
   switch ($string) {
      case "application/pdf":
         return '<img src="/images/icons/pdf2icon.gif" align="middle" border="0">';
         break;
      case "image/jpeg":
         return '<img src="/images/icons/JPG-icon.png" align="middle" border="0">';
         break;
      case "video/x-ms-wmv":
      case "audio/mpeg":
         return '<img src="/images/icons/wma.png" align="middle" border="0">';
         break;
      case "Vido/Quicktime":
      case "video/quicktime":
         return '<img src="/images/icons/qt.png" align="middle" border="0">';
         break;
      case "application/vnd.ms-excel":
         return '<img src="/images/icons/icon-xls.png" align="middle" border="0">';
         break;
      default:
         return "";
   }
}

?>
