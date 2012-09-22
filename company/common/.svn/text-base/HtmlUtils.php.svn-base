<?php
/**
 * 
 * html操作函数
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version         $ID: htmlUtils.php 2011-8-5 Administrator tengzhiwangluoruanjian$

 */
 

/**
 *
 * 判定字符相等的时候，选中标记
 * @param 参数值 $selectValue
 * @param 当前值 $currentValue
 */
function selected($selectValue, $currentValue, $returnvalue1='selected', $returnvalue2='') {
	if ($selectValue == $currentValue) {
		return $returnvalue1;
	}
	return $returnvalue2;
}


/**
 *
 * 从map里获取式样值
 * @param $serializestring
 * @param $item
 */
function styleSelected($serializestring, $item) {
	$styleMap = unserialize($serializestring);
	if ($item == 'color') {
		if (is_null($styleMap[$item])) {
			$styleMap[$item] = "#ffffff";
		}
		return $styleMap[$item];
	} else {
		if (!is_null($styleMap[$item])) {
			return 'checked="checked"';
		}
	}
	return;
}

/**
 *
 * 从post取得样式颜色，字体，下划线信息，组成字符串和map数组（序列化）
 * Enter description here ...
 * @param unknown_type $data
 */
function createStyle($data) {
	$styleString = "";
	$styleMap = array();
	$style = array();
	if (isset($data['style_color'])) {
		$styleMap['color'] = $data['style_color'];
		$styleString.="color:".$styleMap['color'];
	}
	if (isset($data['style_bold'])) {
		$styleMap['bold'] = $data['style_bold'];
		$styleString.=";font-weight:".$styleMap['bold'];
	}
	if (isset($data['style_underline'])) {
		$styleMap['underline'] = $data['style_underline'];
		$styleString.=";TEXT-DECORATION:".$styleMap['underline'];
	}
	$style['title_style'] = $styleString;
	$style['title_style_serialize'] = serialize($styleMap);
	return $style;
}

/**
 *
 * 判断指定字符里包含特定字符，显示选中状态
 * @param unknown_type $commastring
 * @param unknown_type $item
 */
function string2checked($commastring, $item) {
	if (in_array($item, explode(',', $commastring))) {
		return 'checked="checked"';
	}
	return;
}


/**
 *
 * 权限表示
 * @param unknown_type $dataPermission
 * @param unknown_type $rolePermission
 */
function explodeRole($dataPermission, $rolePermission) {
	$dataArray = explode('|', $dataPermission);
	$roleArray = explode(',', $rolePermission);
	$explodeRoleString = "";
	foreach ($dataArray as $dataItem) {
		$item =  explode('=', $dataItem);
		$checked = "";
		if (in_array($item[1], $roleArray)) {
			$checked = 'checked="checked"';
		}
		$explodeRoleString.='<span style="float:left; width:20%;"><input name="role_permission[]" type="checkbox" id="role_permission[]" value="'.$item[1].'" class="checkbox" '.$checked.'>'.$item[0].'</span>';
	}
	return $explodeRoleString;

}


/**
 *
 * 根据指定的值返回对应的图标
 * @param $statusValue
 * @param $defaultValue
 * @param $url
 * @param $pictureName
 * @param $altName
 */
function statusIcon($statusValue, $defaultValue, $url, $pictureName, $altName) {
	if ($statusValue == $defaultValue) {
		$url .= '/Public/Admin/images/'.$pictureName;
		return imgDisplay($url, $pictureName, $altName);
	}
	return;
}

/**
 *
 * 如果和指定值不相同的显示的图标
 * @param unknown_type $statusValue
 * @param unknown_type $defaultValue
 * @param unknown_type $url
 * @param unknown_type $pictureName
 * @param unknown_type $altName
 */
function attachStatus($attachImage, $attach, $url, $pictureName, $altName) {
	if ($attachImage) {
		$url .= '/Public/Admin/images/'.$pictureName;
	} else {
		return;
	}
	return imgDisplay($url, $pictureName, $altName);
}

function imgDisplay($url, $pictureName, $altName) {
	return '<img src="'.$url.'" alt="'.$altName.'" align="absmiddle">';
}

/**
 * 类别管理
 * Enter description here ...
 * @param unknown_type $dataList
 */
function getCategory($dataList, $rootCategory) {
	if (empty($rootCategory)) {
		$rootCategory = "0";
	}
	$newDataList = array();
	getCategoryTree($dataList, $newDataList, $rootCategory);
	return $newDataList;
}

/**
 *
 * 类别树表示
 * @param unknown_type $dataList
 * @param unknown_type $newDataList
 * @param unknown_type $rootCategory
 * @param unknown_type $str_repeat
 */
function getCategoryTree($dataList, &$newDataList, $rootCategory, $strRepeat) {
	foreach ($dataList as $row) {
		if ($row['parent_id'] == $rootCategory) {
			if (empty($strRepeat)) {
				$strRepeat = '';
			}
			$row['str_repeat']=$strRepeat;
			array_push($newDataList, $row);
			getCategoryTree($dataList, $newDataList, $row['id'], $strRepeat.'﹥ ');
		}
	}
}

function moduleTitle($module_name) {
	$modualMap = S('modual_map');
	if (empty($modualMap)) {
		
	}
	foreach ($modualMap as $key) {
		
	}
}

/**
 *
 * 类别管理背景设置
 * @param unknown_type $categoryId
 * @param unknown_type $expectId
 * @param unknown_type $style
 */
function bgStyle($categoryId, $expectId, $style) {
	if ($categoryId == $expectId) {
		return $style;
	} else {
		return "vertical-align: middle;";
	}
}

/**
 *
 * 显示类别信息
 * @param $dataList
 * @param $defaultId
 * @param $parentId
 */
function buildSelect($dataList, $parentId, $defaultId) {
	$bulidString = "";
	$currentDataList = getCategory($dataList, $parentId);
	foreach ($currentDataList as $row) {
		$selected = "";
		if ($row['id'] == $defaultId) {
			$selected = 'selected="selected"';
		}
		$bulidString.='<option value="'.$row['id'].'"'.$selected.'>'.$row['str_repeat'].$row['title'].'</option>';
	}
	return $bulidString;
}

 