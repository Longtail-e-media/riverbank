<?php
$resml=$resmr='';

$lmenuRec = Menu::getMenuByParent(0, 1);
$rmenuRec = Menu::getMenuByParent(0, 2);
$current_url = $_SERVER["REQUEST_URI"];
$data = explode('/',$current_url);

if(!empty($lmenuRec)) {
	$resml.='<ul class="nav-left">';
	foreach($lmenuRec as $lmenuRow) {
		$linkActive=$PlinkActive='';
		$n = !empty(SITE_FOLDER)?2:1;
		$tot = strlen(SITE_FOLDER)+$n;
		$data = substr($_SERVER['REQUEST_URI'], $tot);

		if(!empty($data)):	
			$linkActive = ($lmenuRow->linksrc==$data)?" active":"";
			$parentInfo	= Menu::find_by_linksrc($data);
			if($parentInfo):
				$PlinkActive = ($lmenuRow->id==$parentInfo->parentOf)?" active":"";
			endif;
		endif;

		$lmenusubRec = Menu::getMenuByParent($lmenuRow->id, 1);	
		$ldrop1 = !empty($lmenusubRec)?'class="sub"':'';
        $resml.='<li '.$ldrop1.'>';
        	$resml.= getMenuList($lmenuRow->name, $lmenuRow->linksrc, $lmenuRow->linktype, $linkActive.$PlinkActive);
        	if(!empty($lmenusubRec)) {
        		$resml.='<ul>';
        		foreach($lmenusubRec as $lmenusubRow) {
	                $resml.='<li>';
	                	$resml.= getMenuList($lmenusubRow->name, $lmenusubRow->linksrc, $lmenusubRow->linktype);
	                $resml.='</li>';
	            }
	            $resml.='</ul>';
        	}
        $resml.='</li>';
    }
    $resml.='</ul>';

}

if(!empty($rmenuRec)) {
	$resmr.='<ul class="nav-right">';
	foreach($rmenuRec as $rmenuRow) {
		$linkActive=$PlinkActive='';
		$n = !empty(SITE_FOLDER)?2:1;
		$tot = strlen(SITE_FOLDER)+$n;
		$data = substr($_SERVER['REQUEST_URI'], $tot);

		if(!empty($data)):	
			$linkActive = ($rmenuRow->linksrc==$data)?" active":"";
			$parentInfo	= Menu::find_by_linksrc($data);
			if($parentInfo):
				$PlinkActive = ($rmenuRow->id==$parentInfo->parentOf)?" active":"";
			endif;
		endif;

		$rmenusubRec = Menu::getMenuByParent($rmenuRow->id, 1);	
		$rdrop1 = !empty($rmenusubRec)?'class="sub"':'';
        $resmr.='<li '.$rdrop1.'>';
        	$menuLink = ($rmenuRow->linktype == 1) ? $rmenuRow->linksrc : BASE_URL . $rmenuRow->linksrc;
            $target   = ($rmenuRow->linktype == 1) ? ' target="_blank"' : '';
            $resmr.= '<a href="' . $menuLink . '" class="' . $linkActive.$PlinkActive . '"' . $target . '>' . $rmenuRow->name . '</a>';
        	if(!empty($rmenusubRec)) {
        		$resmr.='<ul>';
        		foreach($rmenusubRec as $rmenusubRow) {
	                $resmr.='<li>';
	                	$resmr.= getMenuList($rmenusubRow->name, $rmenusubRow->linksrc, $rmenusubRow->linktype);
	                $resmr.='</li>';
	            }
	            $resmr.='</ul>';
        	}
        $resmr.='</li>';
    }
    $resmr.='</ul>';
}

$jVars['module:menu-left'] = $resml;
$jVars['module:menu-right'] = $resmr;



$result=$result2='';
$main=$responsive='';

$menuRec = Menu::getAllMenu(0);

$current_url = $_SERVER["REQUEST_URI"];
$data = explode('/',$current_url);

if($menuRec):
	$result.='<ul>'; 	
	$main.='<ul  id="main-menu" class="md-menu">'; 	
		foreach($menuRec as $menuRow):	
			$linkActive=$PlinkActive='';
			$tot = strlen(SITE_FOLDER)+2;
			$data = substr($_SERVER['REQUEST_URI'], $tot);

			if(!empty($data)):	
				$linkActive = ($menuRow->linksrc==$data)?" active":"";
				$parentInfo	= Menu::find_by_linksrc($data);
				if($parentInfo):
					$PlinkActive = ($menuRow->id==$parentInfo->parentOf)?" active":"";
				endif;
			endif;
			$menusubRec = Menu::getMenuByParent($menuRow->id, 1);	
			$drop1 = !empty($menusubRec)?'class="dropdown"':'';
			$result.='<li '.$drop1.'>';
			$result.= getMenuList($menuRow->name, $menuRow->linksrc, $menuRow->linktype, $linkActive.$PlinkActive, $drop1);
				/* Second Level Menu */
				if($menusubRec):		
				$result.='<ul class="dropdown-menu">';	
				foreach($menusubRec as $menusubRow): 
				   $menusub2Rec = Menu::getMenuByParent($menusubRow->id,1);	
				   $chkparent2 = (!empty($menusub2Rec))?1:0;
				   $drop2 = !empty($menusub2Rec)?'class="have-submenu"':'';
				   $result.='<li id="menu-item-'.$menusubRow->id.'" '.$drop2.'>';
				   $result.= getMenuList($menusubRow->name, $menusubRow->linksrc, $menusubRow->linktype, '', $chkparent2);
				   		/* Third Level Menu */
				   		if($menusub2Rec):
				   			$result.='<div class="sub-menu">
				   			<ul class="sub-menu-inner">';
				   			foreach ($menusub2Rec as $menusub2Row):
				   				$menusub3Rec = Menu::getMenuByParent($menusub2Row->id,1);	
				   				$chkparent3 = (!empty($menusub3Rec))?1:0;
				   				$drop3 = !empty($menusub3Rec)?'class="have-submenu"':'';
				   				$result.='<li id="menu-item-'.$menusub2Row->id.'" '.$drop3.'>';
				   				$result.= getMenuList($menusub2Row->name, $menusub2Row->linksrc, $menusub2Row->linktype, '', $chkparent3);
				   					/* Fourth Level Menu */
				   					if($menusub3Rec):
				   						$result.='<div class="sub-menu">
				   						<ul class="sub-menu-inner">';
				   						foreach($menusub3Rec as $menusub3Row):
				   							$menusub4Rec = Menu::getMenuByParent($menusub3Row->id,1);
				   							$chkparent4 = (!empty($menusub4Rec))?1:0;
				   							$result.='<li id="menu-item-'.$menusub2Row->id.'">';
				   							$result.= getMenuList($menusub3Row->name, $menusub3Row->linksrc, $menusub3Row->linktype, '', $chkparent4);
				   								/* Fifth Level Menu */
				   								if($menusub4Rec):
				   									$result.='<ul>';
				   									foreach($menusub4Rec as $menusub4Row):
				   										$menusub5Rec = Menu::getMenuByParent($menusub4Row->id,1);
				   										$chkparent5 = (!empty($menusub4Rec))?1:0;
				   										$result.='<li>'.getMenuList($menusub4Row->name, $menusub4Row->linksrc, $menusub4Row->linktype,$chkparent5).'</li>';
				   									endforeach;
				   									$result.='</ul>';
				   								endif;
				   							$result.='</li>';
				   						endforeach;			   							
				   						$result.='</ul>
				   						</div>';
				   					endif;
				   				$result.='</li>';
				   			endforeach;
				   			$result.='</ul>
				   			</div>';
				   	    endif;
				   	$result.='</li>';    
				endforeach;
				$result.='</ul>';
				endif;
			$result.='</li>';
		endforeach;
	$result.='</ul>';
endif;			

$jVars['module:menu']= $main.$result;
$jVars['module:menu-responsive']= $responsive.$result;


/*require_once 'Mobile_Detect.php';*/
$detect = new Mobile_Detect;

$ret='';
 
// Any mobile device.

            $ret.='<div id="menuArea">
				  <input type="checkbox" id="menuToggle"></input>

				<label for="menuToggle" class="menuOpen">
				  <div class="open"></div>
				</label>

				<div class="menu menuEffects">
				  <label for="menuToggle"></label>
				  <div class="menuContent">
				  '.$result.'
				  </div>
				</div>
				</div>';
	$ret.='<div class="header-nav">
                <!-- Menu module -->
                '.$resml.'
                <!-- Menu module -->

            </div>';
$jVars['module:res-menu']= $ret;

//Footer Menu
$result1='';
$FmenuRec = Menu::getMenuByParent(0,2);
if($FmenuRec):
$result1.='<ul id="main-menu" class="md-menu">';
	foreach($FmenuRec as $FmenuRow):
	   $result1.='<li>';
	   $result1.= getMenuList($FmenuRow->name, $FmenuRow->linksrc, $FmenuRow->linktype,'');
		   $subRec = Menu::getMenuByParent($FmenuRow->id,2);	
		   /*if($subRec):
			   $result1.='<ul>';
				foreach($subRec as $subRow):
					$result1.='<li>';
	   					$result1.= getMenuList($subRow->name, $subRow->linksrc, $subRow->linktype,'child');
	   				$result1.='</li>';
				endforeach;
			   $result1.='</ul>';
		   endif;*/
	   $result1.='</li>';
   	endforeach;
$result1.='</ul>';
endif;
$jVars['module:bottom_menu']= $result1;
?>