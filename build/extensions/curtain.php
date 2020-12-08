<?php

// 

$extensionList["curtain"] = "extensionCurtain";

function extensioncurtain ($d, $pd)
  {
	global $extraHTML;
	$workspace = false;
  $mans = '[]';
	$wo = '';
	$codeHTML = "";
	$codecaption = "The complete curtain JSON file used to define the manifests and images presented in this example.";
		
	if (isset($d["file"]) and file_exists($d["file"]))
		{
		$dets = getRemoteJsonDetails($d["file"], false, true);
			
		if (!$dets)
			{
			$dets = getRemoteJsonDetails($d["file"], false, false);
			$dets = explode(PHP_EOL, trim($dets));

			// Used to display the JSON used to create a given page for demos
			if (isset($d["displaycode"]))
				{$extraHTML .= displayCode ($dets, "The Curtain TXT File", "txt", $codecaption);}

			if (preg_match('/^http.+/', $dets[0]))
				{$mans = listToManifest ($dets); ///NEED TO UPDATE THIS ONE
				 $wo = '[{
					"manifestId": "'.$dets[0].'"
					}]';}
      }
    else {
			// Used to display the JSON used to create a given page for demos
			if (isset($d["displaycode"]))
				{$extraHTML .= displayCode ($dets, "The Curtain JSON File", "json", $codecaption);}
				
			$mans = json_encode($dets);			 
			/* 
			if (isset($dets["workspace"]))
			 {$workspace = "workspace: ".json_encode($dets["workspace"]);}			 

			if (isset($dets["windows"]))
			 {$wo = json_encode($dets["windows"]);}
			else
			 {$manifestIds = array_keys($dets["manifests"]);
				$manifestId = $manifestIds[0];				

			  $wo = '[{
					"manifestId": "'.$manifestId.'"
					}]';}*/
      }
    }

	$pd["extra_css_scripts"][] = "https://jpadfield.github.io/curtain-viewer/bundle.css";
	//$mirador_path = "https://unpkg.com/mirador@3.0.0-beta.10/dist/";
	$pd["extra_js_scripts"][] = "https://jpadfield.github.io/curtain-viewer/js/1.08958bb6.chunk.js";
	$pd["extra_js_scripts"][] = "https://jpadfield.github.io/curtain-viewer/js/app.a955ee34.js";

	$d["content"] = positionExtraContent ($d["content"], '<div class="row" style="padding-left:16px;padding-right:16px;"><div class="col-12 col-lg-12"><div class="curtain-viewer" data-iiif-manifest="https://jpadfield.github.io/curtain-viewer/public/manifest_testcollection.json"></div></div></div>'.$codeHTML);

  return (array("d" => $d, "pd" => $pd));
  }

	 
/*function listToManifest ($list)
	{
	$manifests = "{";

	foreach ($list as $k => $url)
		{$manifests .= "
".json_encode($url).":{\"provider\":\"Undefined\"},";}
	
	return($manifests."}");
	}    */
?>
