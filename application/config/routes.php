<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "admin";
$route['404_override'] = '';

/*
*	Donations Routes
*/
$route['all-donations'] = 'admin/donations/index';
$route['close-donations-search'] = 'admin/donations/close_search';
$route['export-donations'] = 'admin/donations/export_donations';
$route['all-donations/(:num)'] = 'admin/donations/index/$1';
$route['activate-donation/(:num)/(:num)'] = 'admin/donations/activate_donation/$1/$2';
$route['deactivate-donation/(:num)/(:num)'] = 'admin/donations/deactivate_donation/$1/$2';

/*
*	Hills Routes
*/
$route['all-hills'] = 'admin/hills/index';
$route['add-hill'] = 'admin/hills/add_hill';
$route['edit-hill/(:num)'] = 'admin/hills/edit_hill/$1';
$route['delete-hill/(:num)'] = 'admin/hills/delete_hill/$1';
$route['activate-hill/(:num)'] = 'admin/hills/activate_hill/$1';
$route['deactivate-hill/(:num)'] = 'admin/hills/deactivate_hill/$1';

/*
*	Settings Routes
*/
$route['settings'] = 'admin/settings';
$route['dashboard'] = 'admin/index';

/*
*	Login Routes
*/
$route['login-admin'] = 'login/login_admin';
$route['logout-admin'] = 'login/logout_admin';

/*
*	Users Routes
*/
$route['all-users'] = 'admin/users';
$route['all-users/(:num)'] = 'admin/users/index/$1';
$route['add-user'] = 'admin/users/add_user';
$route['edit-user/(:num)'] = 'admin/users/edit_user/$1';
$route['delete-user/(:num)'] = 'admin/users/delete_user/$1';
$route['activate-user/(:num)'] = 'admin/users/activate_user/$1';
$route['deactivate-user/(:num)'] = 'admin/users/deactivate_user/$1';
$route['reset-user-password/(:num)'] = 'admin/users/reset_password/$1';
$route['admin-profile/(:num)'] = 'admin/users/admin_profile/$1';

/*
*	Users Routes
*/
$route['all-donors'] = 'admin/donors';
$route['all-donors/(:num)'] = 'admin/donors/index/$1';
$route['add-donor/(:num)'] = 'admin/donors/add_donor/$1';
$route['edit-donor/(:num)/(:num)'] = 'admin/donors/edit_donor/$1/$2';
$route['delete-donor/(:num)/(:num)'] = 'admin/donors/delete_donor/$1/$2';
$route['activate-donor/(:num)/(:num)'] = 'admin/donors/activate_donor/$1/$2';
$route['deactivate-donor/(:num)/(:num)'] = 'admin/donors/deactivate_donor/$1/$2';
$route['reset-donor-password/(:num)/(:num)'] = 'admin/donors/reset_password/$1/$2';


/* End of file routes.php */
/* Location: ./application/config/routes.php */