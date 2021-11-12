<?php

$router->get('login', 'LoginController@index');
$router->post('login', 'LoginController@login');

$router->get('register', 'LoginController@indexRegister');
$router->post('register', 'LoginController@register');

$router->get('forgotpassword', 'LoginController@indexForgot');
$router->post('forgotpassword', 'LoginController@forgot');

$router->get('resetpassword', 'LoginController@resetPassword');
$router->post('resetpassword', 'LoginController@resetPasswordAction');

$router->get('logout', 'LoginController@logout');

$router->get('dashboard', 'DashboardController@index');

$router->get('profile', 'ProfileController@index');
$router->post('profile', 'ProfileController@profileAction');
$router->get('changepassword', 'ProfileController@indexChangePassword');
$router->post('changePassword', 'ProfileController@changePasswordAction');

$router->get('invoices', 'InvoiceController@index');
$router->get('invoice/view', 'InvoiceController@indexView');
$router->get('invoice/pdf', 'InvoiceController@indexPdf');
$router->get('invoice/print', 'InvoiceController@indexPrint');

$router->get('quotes', 'QuoteController@index');
$router->get('quote/view', 'QuoteController@indexView');
$router->get('quote/print', 'QuoteController@indexPrint');
$router->get('quote/pdf', 'QuoteController@indexPdf');
$router->post('convertquote', 'QuoteController@indexAutoInvoice');

$router->get('tickets', 'TicketController@index');
$router->get('ticket/add', 'TicketController@indexAdd');
$router->get('ticket/edit', 'TicketController@indexEdit');
$router->post('ticket/action', 'TicketController@indexAction');
$router->get('ticket/fileDownload', 'TicketController@downloadFile');

$router->get('info', 'InfoController@index');
$router->post('info/action', 'InfoController@indexAction');

$router->post('upload', 'UploadController@index@3');
$router->post('upload/delete', 'UploadController@indexDelete@3');
$router->post('attachFile', 'UploadController@attachFile@3');
$router->post('attachFile/delete', 'UploadController@deleteAttachedFiles@3');

$router->get('invoice/paynow', 'PaymentController@index');
$router->get('invoice/cancelpay', 'PaymentController@indexCancel');
$router->get('invoice/successpay', 'PaymentController@indexSuccess');
$router->get('payment/success', 'PaymentController@indexSuccessShow');
$router->get('payment/cancel', 'PaymentController@indexCancelShow');

//$router->get('invoice/payu', 'PaymentController@payU');
//$router->get('invoice/stripe', 'PaymentController@stripe');

$router->get('closetab', 'CommonsController@indexCloseTab');