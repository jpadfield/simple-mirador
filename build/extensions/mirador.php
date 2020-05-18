<?php

$extensionList["mirador"] = "extensionMirador";

function extensionMirador ($d, $pd)
  {
  $mans = '[]';
	$wo = '';
	$codeHTML = "";
		
	if (isset($d["file"]) and file_exists($d["file"]))
		{
		$dets = getRemoteJsonDetails($d["file"], false, true);
			
		if (!$dets)
			{
			$dets = getRemoteJsonDetails($d["file"], false, false);
			$dets = explode(PHP_EOL, trim($dets));
			$codeHTML = displayCode ($dets, "The Mirador TXT File", "txt");

			if (preg_match('/^http.+/', $dets[0]))
				{$mans = listToManifest ($dets);
				 $wo = '[{
					"manifestId": "'.$dets[0].'"
					}]';}
      }
    else {
			$codeHTML = displayCode ($dets, "The Mirador JSON File");
			$mans = json_encode($dets["manifests"]);			 
			 
			if (isset($dets["windows"]))
			 {$wo = json_encode($dets["windows"]);}
			else
			 {$manifestIds = array_keys($dets["manifests"]);
				$manifestId = $manifestIds[0];				

			  $wo = '[{
					"manifestId": "'.$manifestId.'"
					}]';}
      }
    }

	$mirador_path = "https://unpkg.com/mirador@3.0.0-beta.8/dist/";
	$pd["extra_js_scripts"][] = $mirador_path."mirador.min.js";

	ob_start();			
	echo <<<END
	$(function() {

var myMiradorInstance = Mirador.viewer({
       id: "mirador",
       windows: $wo,
       manifests: $mans
       });     
     });
END;
	$pd["extra_js"] .= ob_get_contents();
	ob_end_clean(); // Don't send output to client
     
	/*
	// https://unpkg.com version 2.7.2 did not seem to work, will try again once V3 is
	// fully released. - jpadfield 30/03/20
	$mirador_path = "https://unpkg.com/mirador@2.6.0/dist/";
	//$mirador_path = "https://tanc-ahrc.github.io/mirador/mirador/";
	
  $pd["extra_css_scripts"][] = $mirador_path."css/mirador-combined.min.css";
	$pd["extra_js_scripts"][] = $mirador_path."mirador.min.js";
	$pd["extra_js_scripts"][] = $mirador_path."mirador.min.js.map";
	$pd["extra_js"] .= '
	$(function() {
     myMiradorInstance = Mirador({
       id: "viewer",
       layout: '.$lo.',
       buildPath: "'.$mirador_path.'",
       data: '.$mans.',
       "windowObjects": '.$wo.'
       });
     });';
  //use to hide the label used for the first line which is just in place to provide a margin/padding on the left.
	$pd["extra_css"] .= "
#viewer {       
      display: block;
      width: 100%;
      height: 600px;
      position: relative;
     }";*/
//$d["content"] = positionExtraContent ($d["content"], '<div class="row" style="padding-left:16px;padding-right:16px;"><div class="col-12 col-lg-12"><div style="height:500px;" id="mirador"></div></div></div>');

	$d["content"] = positionExtraContent ($d["content"], '<div class="row" style="padding-left:16px;padding-right:16px;"><div class="col-12 col-lg-12"><div style="height:500px;" id="mirador"></div></div></div>'.$codeHTML);

  return (array("d" => $d, "pd" => $pd));
  }

	
function listToManifest ($list)
	{
	$manifests = array();

	foreach ($list as $k => $url)
		{
		$manifests[] = array(
			$url => array("provider" => "Undefined"),
			);
		}

	$manifests = json_encode($manifests);
	
	return($manifests);
	}    
?>
