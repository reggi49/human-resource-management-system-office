<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'portal';
$route['careers'] = 'careers';
$route['careers/apply/(:num)'] = 'careers/detail/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['kpi/definitions'] = 'kpi/definitions';
$route['kpi/add_definition'] = 'kpi/add_definition';
$route['kpi/save_definition'] = 'kpi/save_definition';
$route['kpi/edit_definition/(:num)'] = 'kpi/edit_definition/$1';
$route['kpi/delete_definition/(:num)'] = 'kpi/delete_definition/$1';

$route['payroll/pph21_master'] = 'payroll/Pph21_Master';
$route['payroll/Pph21_Master'] = 'payroll/Pph21_Master';
$route['payroll/Pph21_master'] = 'payroll/Pph21_Master';

$route['landing'] = 'landing';
$route['portal'] = 'portal';
$route['portal/login'] = 'portal/login';
$route['portal/login_auth'] = 'portal/login_auth';
$route['presence']                 = 'face/index';
$route['api/register_face_web']    = 'face/register';
$route['api/registerFaceWeb']      = 'face/register';
$route['api/biometric_logs']       = 'face/admin_logs';
$route['Api/biometric_logs']       = 'face/admin_logs';

// ─── Face Recognition v2 Routes ───────────────────────────────────────────
$route['face']                     = 'face/index';
$route['face/register']            = 'face/register';
$route['face/api/challenge']       = 'face/api_challenge';
$route['face/api/enroll']          = 'face/api_enroll';
$route['face/api/enroll/finalize'] = 'face/api_enroll_finalize';
$route['face/api/verify']          = 'face/api_verify';
$route['face/api/verify-pin']      = 'face/api_verify_pin';
$route['face/api/delete-biometric'] = 'face/api_delete_biometric';
$route['face/photo/(:num)']        = 'face/photo/$1';
$route['face/admin/logs']          = 'face/admin_logs';
$route['face/admin/health']        = 'face/admin_health';




