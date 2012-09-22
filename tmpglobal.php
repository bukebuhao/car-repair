<?php

class GlobalAction extends Action
{
private $roleId,$adminId,$username,$adminAccess;
protected $upload = 'Uploads/',$sys_version = '2.0beta1';
function _initialize()
{
$this->roleId = intval(Session::get('roleId'));
$this->adminId = intval(Session::get('adminId'));
$this->username = Session::get('username');
$this->adminAccess = Session::get('adminAccess');
if(empty($this->adminId) ||empty($this->roleId) ||$this->adminAccess != C('ADMIN_ACCESS'))
{
redirect( U('Public/login',array('jumpUri'=>safe_b64encode($_SERVER['REQUEST_URI']))));
}
import("ORG.Util.Page");
$module = D('Module')->Where('left_menu=1 and status=0')->Order('display_order DESC,id ASC')->findAll();
$data['leftBar'] = $module;
$appHost = empty($_SERVER['SERVER_NAME']) ?$_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
$frontUrl = 'http://'.$appHost .dirname($_SERVER['SCRIPT_NAME']);
Load('extend');
$data['moduleName'] = MODULE_NAME;
$data['serverTime'] = time();
$data['UPLOADS'] = $this->upload;
$this->assign($data);
$this->assign('appHost',$appHost);
$this->assign('frontUrl',$frontUrl);
$this->assign('roleId',$this->roleId);
$this->assign('adminId',$this->adminId);
$this->assign('username',$this->username);
}
Protected function _getAdminUid()
{
return $this->adminId;
}
Protected function _getRoleId()
{
return $this->roleId;
}
Protected function _getAdminName()
{
return $this->username;
}
public function debug()
{
$this->display('Public:debug');
}
protected function _insert($model = false,$jumpModel ='later')
{
$module = empty($model) ?$this->getActionName() : $model;
$dao = D($module);
if($daoCreate = $dao->create())
{
$daoAdd = $dao->add();
if(false !== $daoAdd)
{
self::_sysLog('insert',"录入: {$daoAdd}");
self::_message('success','录入成功');
}else
{
self::_message('error','录入失败');
}
}else
{
self::_message('error',$dao->getError());
}
}
protected function _modify($model = false,$jumpModel = 'later',$primary = 'id')
{
$id = intval($_POST['id']);
empty($id) &&self::_message('error','记录不存在');
$module = empty($model) ?$this->getActionName() : $model;
$dao = D($module);
$daoCreate = $dao->create();
if($daoCreate)
{
$daoSave = $dao->save();
if(false !== $daoSave)
{
self::_sysLog('modify',"编辑: {$id}");
self::_message('success','更新成功');
}else
{
self::_message('error','更新失败');
}
}else
{
self::_message('error',$dao->getError());
}
}
protected function _delete($model = 0,$jumpUri= 0,$param = 0,$field = 'id')
{
if(getMethod() == 'get'){
$operate = trim($_GET['operate']);
$item = intval($_GET[$field]);
}elseif(getMethod() == 'post'){
$operate = trim($_POST['operate']);
$item = $_POST[$field];
}
if($item){
if(empty($operate) ||$operate !='delete') self::_message('error','操作类型错误');
$jumpUri = empty($uri) ?__URL__ : $uri ;
$daoModel = empty($model)?$this->getActionName() : $model ;
$items = is_array($item) ?implode(',',$item) : $item ;
if(empty($param)){
$dao = D($daoModel);
$daoResult = $dao->where($field.' IN('.$items.')')->delete();
if(false !== $daoResult)
{
self::_sysLog('delete',"删除: {$items}");
$this->_message('success',"{$items} 删除成功",$jumpUri);
}else{
$this->_message('error',"删除失败",$jumpUri);
}
}else{
self::_deleteWith($daoModel,$items,$param,$jumpUri,$field);
}
}else{
$this->_message('error',"未选择要删除的记录",$jumpUri);
}
}
protected function _deleteWith($model = 0,$items = 0,$param = 0,$jumpUri= 0,$field = 'id')
{
$dao = D($model);
$daoList = $dao->Where($field.' IN('.$items.')')->findAll();
foreach ((array)$daoList as $row){
foreach ((array)$param as $value)
{
if(!empty($row[$value]))
{
@unlink(UPLOAD_PATH.'/'.$row[$value]);
}
}
}
$daoResult = $dao->Where($field.' IN('.$items.')')->delete();
if(false !== $daoResult)
{
self::_sysLog('modify',"删除: {$items}");
$this->_message('success',"{$items} 删除成功",$jumpUri);
}else{
$this->_message('error',"删除失败",$jumpUri);
}
}
protected function _recommend($type = 'set',$model = 0,$jumpUri = __URL__,$field = 'id')
{
if(getMethod() == 'get'){
$operate = trim($_GET['operate']);
$item = intval($_GET[$field]);
}elseif(getMethod() == 'post'){
$operate = trim($_POST['operate']);
$item = $_POST[$field];
}
if($item){
if(empty($operate) ||!in_array($operate,array('recommend','unRecommend'))) self::_message('error','操作类型错误',$jumpUri);
$daoModel = empty($model)?$this->getActionName() : $model ;
$items = is_array($item) ?implode(',',$item) : $item ;
$dao = D($daoModel);
$condition['recommend'] = $type == 'set'?1 : 0 ;
$daoResult = $dao->Where($field.' IN('.$items.')')->save($condition);
if(false !== $daoResult)
{
$mOperate = $operate == 'recommend'?'推荐:': '取消推荐:';
self::_sysLog('update',"{$mOperate} {$items}");
$this->_message('success',"{$items} 更新成功",$jumpUri);
}else{
$this->_message('error',"更新失败",$jumpUri);
}
}else{
$this->_message('error',"未选择要更新的记录");
}
}
protected function _setTop($type = 'set',$model = 0,$jumpUri= __URL__,$field = 'id')
{
if(getMethod() == 'get'){
$operate = trim($_GET['operate']);
$item = intval($_GET[$field]);
}elseif(getMethod() == 'post'){
$operate = trim($_POST['operate']);
$item = $_POST[$field];
}
if($item){
if(empty($operate) ||!in_array($operate,array('setTop','unSetTop'))) self::_message('error','操作类型错误',$jumpUri);
$daoModel = empty($model)?$this->getActionName() : $model ;
$items = is_array($item) ?implode(',',$item) : $item ;
$dao = D($daoModel);
$condition['istop'] = $type == 'set'?1 : 0 ;
$daoResult = $dao->Where($field.' IN('.$items.')')->save($condition);
if(false !== $daoResult)
{
$mOperate = $operate == 'setTop'?'置顶:': '取消置顶:';
self::_sysLog('update',"{$mOperate} {$items}");
$this->_message('success',"{$items} 更新成功",$jumpUri);
}else{
$this->_message('error',"更新失败",$jumpUri);
}
}else{
$this->_message('error',"未选择要更新的记录",$jumpUri);
}
}
protected function _setStatus($type = 'set',$model = 0,$jumpUri = __URL__,$field = 'id')
{
if(getMethod() == 'get'){
$operate = trim($_GET['operate']);
$item = intval($_GET[$field]);
}elseif(getMethod() == 'post'){
$operate = trim($_POST['operate']);
$item = $_POST[$field];
}
if($item){
if(empty($operate) ||!in_array($operate,array('setStatus','unSetStatus'))) self::_message('error','操作类型错误',$jumpUri);
$daoModel = empty($model)?$this->getActionName() : $model ;
$items = is_array($item) ?implode(',',$item) : $item ;
$dao = D($daoModel);
$condition['status'] = $type == 'set'?0 : 1 ;
$daoResult = $dao->Where($field.' IN('.$items.')')->save($condition);
if(false !== $daoResult)
{
$mOperate = $operate == 'setStatus'?'设置显示:': '设置隐藏:';
self::_sysLog('update',"{$mOperate} {$items}");
$this->_message('success',"ID: {$items} 更新成功",$jumpUri);
}else{
$this->_message('error',"更新失败",$jumpUri);
}
}else{
$this->_message('error',"未选择要更新的记录",$jumpUri);
}
}
protected function _move($newCategoryId = 0,$model = 0,$jumpUri = __URL__,$field = 'id')
{
if(getMethod() == 'get'){
$operate = trim($_GET['operate']);
$item = intval($_GET[$field]);
}elseif(getMethod() == 'post'){
$operate = trim($_POST['operate']);
$item = $_POST[$field];
}
if($item){
if(empty($operate) ||$operate != 'move') self::_message('error','操作类型错误',$jumpUri);
empty($newCategoryId) &&self::_message('error','新类别获取错误',$jumpUri);
$daoModel = empty($model)?$this->getActionName() : $model ;
$items = is_array($item) ?implode(',',$item) : $item ;
$dao = D($daoModel);
$condition['category_id'] = $newCategoryId ;
$daoResult = $dao->Where($field.' IN('.$items.')')->save($condition);
if(false !== $daoResult)
{
self::_sysLog('update',"移动: {$items} 至新栏目:{$newCategoryId}");
$this->_message('success',"ID:{$items} 更新成功",$jumpUri);
}else{
$this->_message('error',"更新失败",$jumpUri);
}
}else{
$this->_message('error',"未选择要更新的记录",$jumpUri);
}
}
protected function _batchModify($model = 0,$dataList = 0,$fields = array(),$jumpUri =__URL__,$cache = 0,$cacheOrder = '',$cacheWhere = '',$condition = 'shuguangUpdateId')
{
$count = count($dataList[$condition]);
if($count >0){
$fieldsMerge = array_merge($fields,array($condition)) ;
$daoModel = empty($model) ?$this->getActionName() : $model ;
$dao = D($daoModel);
foreach ($dataList[$condition] as $key=>$row)
{
foreach ($fieldsMerge as $item){
if(isset($dataList[$item][$key])){
$data[$key][$item] = $dataList[$item][$key];
}
}
}
$updateCount = 0;
foreach ($data as $key =>$value){
$result = $dao->Where('id='.$data[$key][$condition])->save($value);
if($result === false ){
self::_message('error',"ID: {$data[$key][$condition]} 更新失败，请检查数据的正确性",$jumpUri,10);
}
$updateCount++;
$items[] = $data[$key][$condition];
}
$updateItems = implode(',',$items);
self::_sysLog('update',"批量更新: {$updateItems} 影响 {$updateCount} 条记录");
!empty($cache) &&writeCache($cache,0,$cacheOrder,$cacheWhere);
self::_message('success',"更新 {$updateItems}，影响 {$updateCount} 条记录",$jumpUri);
}else{
self::_message('error','数据获取错误，可能是没有记录被选择',$jumpUri);
}
}
protected function _tags($method = 'insert',$tags = '',$titleId = 0,$model = 0,$jumpUri = __URL__)
{
if(!empty($tags)){
if($method == 'insert'){
self::_tagsInsert($tags,$titleId,$model,$jumpUri);
}elseif($method == 'modify'){
self::_tagsModify($tags,$titleId,$model,$jumpUri);
}
}
}
protected function _tagsInsert($tags = '',$titleId = 0,$model = 0,$jumpUri)
{
$dao = D('tags');
$model = empty($model) ?$this->getActionName() : $model ;
$tagsValue = str_replace(array(' ','，'),',',$tags);
$explodeTags = array_unique(explode(',',$tagsValue));
$tagCount = 0;
foreach ((array)$explodeTags as $value) {
$value = dHtml(trim($value));
if(!empty($value)){
$condition['tag_name'] = $value;
$condition['module'] = $model;
$getTags = $dao->where($condition)->find();
if(empty($getTags))
{
$tagInsert['tag_name'] = $value;
$tagInsert['module'] = $model;
$tagInsert['total_count'] = 1;
$tagInsert['tag_name'] = $value;
$daoTagsAdd = $dao->add($tagInsert);
if(false === $daoTagsAdd){
self::_message('error',"内容写入成功，tags:{$value} 写入失败",$jumpUri);
}
$getTags = false;
}else{
$data['id'] =  $getTags['id'];
$dao->setInc('total_count',$data);
}
self::_tagsCache($value,$titleId,$model);
$tagCount++;
if($tagCount >4) {
unset($explodeTags);
break;
}
}
}
}
protected function _tagsModify($tags = '',$titleId = 0,$model = 0,$jumpUri)
{
$daoTagCache = D('tagsCache');
$daoTags = D('tags');
$titleTagsArray = $daoTagCache->Where('title_id='.$titleId)->findAll();
$tagsArray = array();
foreach ((array)$titleTagsArray as $row)
{
$tagsArray[] = $row['tag_name'];
}
$titleTags = implode(',',$tagsArray);
$dao = D('tags');
$model = empty($model) ?$this->getActionName() : $model ;
$tagsValue = str_replace(array(' ','，'),',',$tags);
$explodeTags = array_unique(explode(',',$tagsValue));
$tagCount = 0;
foreach ((array)$explodeTags as $value) {
$value = dHtml(trim($value));
if(!empty($value)){
$condition['tag_name'] = $value;
$condition['module'] = $model;
$TagsArrayNew[] = $value;
if(!in_array($value,$tagsArray)) {
$getTags = $dao->where($condition)->find();
if(empty($getTags))
{
$tagInsert['tag_name'] = $value;
$tagInsert['module'] = $model;
$tagInsert['total_count'] = 1;
$tagInsert['tag_name'] = $value;
$daoTagsAdd = $dao->add($tagInsert);
if(false === $daoTagsAdd){
self::_message('error',"内容写入成功，tags:{$value} 写入失败",$jumpUri);
}
$getTags = false;
}else{
$data['id'] =  $getTags['id'];
$dao->setInc('total_count',$data);
}
self::_tagsCache($value,$titleId,$model);
}
$tagCount++;
if($tagCount >4) {
unset($explodeTags);
break;
}
}
}
foreach ($tagsArray as $tagName){
if(!in_array($tagName,$TagsArrayNew)){
$getTagsCount = $daoTagCache->Where("title_id!={$titleId} and tag_name='{$tagName}'")->count();
if($getTagsCount){
$daoTags->setDec('total_count',"tag_name='{$tagName}'");
}else{
$daoTags->Where("tag_name='{$tagName}'")->delete();
}
$daoTagCache->Where("title_id={$titleId} and tag_name='{$tagName}'")->delete();
}
}
}
protected function _tagsCache($tags = '',$titleId = 0,$model = 0)
{
$tagsCache = D('tagsCache');
$tagsCacheCondition['title_id'] = $titleId;
$tagsCacheCondition['tag_name'] = $tags;
$tagsCacheCondition['module'] = $model;
$tagsCache->tag_name = $tags;
$tagsCache->title_id = $titleId;
$tagsCache->module = $model;
$daoTagsCacheAdd = $tagsCache->add();
if(false === $daoTagsCacheAdd){
self::_message('error','内容写入成功，tagCache写入失败');
}
}
protected function _tagsDelete($module = NULL,$field = 'id'){
if(getMethod() == 'get'){
$operate = trim($_GET['operate']);
$item = intval($_GET[$field]);
}elseif(getMethod() == 'post'){
$operate = trim($_POST['operate']);
$item = $_POST[$field];
}
if(is_array($item)){
foreach ($item as $tid){
$condition['module'] = $module;
$condition['title_id'] = $tid;
$dao = D('TagsCache');
$dao->where($condition)->delete();
}
}else{
$condition['module'] = $module;
$condition['title_id'] = $item;
$dao = D('TagsCache');
$dao->Where($condition)->delete();
}
}
protected function _sysLog($action = '',$message = '',$uri = NULL)
{
$formatMessage = empty($message) ?'': " ({$message})";
$getConfig = getContent('cms.config.php','.');
$sysLog = $getConfig['sys_log'];
$sysLogExt = $getConfig['sys_log_ext'];
if(!empty($action) &&!empty($sysLog) &&in_array($action,explode(',',$sysLogExt))){
$getUri =  empty($uri) ?formatQuery($_SERVER['REQUEST_URI']) : $uri ;
$dao = D('AdminLog');
$dao->user_id = intval($this->adminId);
$dao->username = $this->username;
$dao->action = $getUri .$formatMessage;
$dao->ip = get_client_ip();
$dao->create_time = time();
$daoAdd = $dao->add();
$lastSql = $dao->getLastSql();
if(false === $daoAdd){
self::_message('error',"日志写入失败:<br />{$lastSql}",0,30);
}
}
}
protected function _setMethod($set = 'POST')
{
$getMethod = strtolower($_SERVER['REQUEST_METHOD']);
if($getMethod != $set){
self::_message('error',"当前只接受 {$set} 数据");
}
}
protected function _message($type = 'success',$content = '更新成功',$jumpUrl = __URL__,$time = 3,$ajax = false)
{
$jumpUrl = empty($jumpUrl) ?__URL__ : $jumpUrl ;
switch ($type){
case 'success':
$this->assign('jumpUrl',$jumpUrl);
$this->assign('waitSecond',$time);
$this->success($content,$ajax);
break;
case 'error':
$this->assign('jumpUrl','javascript:history.back(-1);');
$this->assign('waitSecond',$time);
$this->assign('error',$content);
$this->error($content,$ajax);
break;
case 'errorUri':
$this->assign('jumpUrl',$jumpUrl);
$this->assign('waitSecond',$time);
$this->assign('error',$content);
$this->error($content,$ajax);
break;
default:
die('error type');
break;
}
}
protected function _checkPermission($action = NULL)
{
$formatAction = strtolower($action);
if(empty($action)) $formatAction = strtolower(MODULE_NAME.'_'.ACTION_NAME);
$permissionFile = CMS_DATA."/cache.adminRole.php";
$permission = Session::get('permission');
if($permission != 'all'){
if(file_exists($permissionFile)){
$getPermission = @require($permissionFile);
foreach((array)$getPermission as $row){
if($row['id'] == $this->roleId){
$arrPermission = explode(',',strtolower($row['role_permission']).',index_index');
}
}
}else{
$roleDao = D('AdminRole');
$getPermission = $roleDao->Where("id={$this->roleId}")->find();
$arrPermission = explode(',',strtolower($getPermission['role_permission']).',index_index');
writeCache('AdminRole');
}
if(!in_array($formatAction,$arrPermission)){
self::_message('error','当前角色组无权限进行此操作，请联系管理员授权',0,20);
}
}
}
}
