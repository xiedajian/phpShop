<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/7/18
 * Time: 15:38
 */

define('APP_ID','app');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
if (!@include(dirname(dirname(__FILE__)).'/global.php')) exit('global.php isn\'t exists!');
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');
if (!@include(BASE_CORE_PATH.'/33hao.php')) exit('33hao.php isn\'t exists!');
define('APP_SITE_URL',APPAPI_SITE_URL);
//define('TPL_NAME',TPL_COURSE_NAME);
//define('COURSE_RESOURCE_SITE_URL',COURSE_SITE_URL.DS.'resource');
//define('COURSE_TEMPLATES_URL',COURSE_SITE_URL.'/templates/'.TPL_NAME);
//define('BASE_TPL_PATH',BASE_PATH.'/templates/'.TPL_NAME);
Base::run();