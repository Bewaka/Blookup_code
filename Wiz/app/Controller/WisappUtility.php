<?php
   
class WisappUtililty {

   function GenerateSQLScript($filepath) 
   {
		$xmlDoc = new DOMDocument();
		$xmlDoc->load($filepath);

		$x = $xmlDoc->documentElement;
		foreach ($x->childNodes AS $item)
		{
			print $item->nodeName . " = " . $item->nodeValue . "<br>";
		}
   }
}
?>