<?php
$router->get('login', 'LoginController@index');
$router->post('login', 'LoginController@login');
$router->get('forgotpassword', 'LoginController@forgotpassword');
$router->post('forgotpassword', 'LoginController@forgotAction');
$router->get('resetpassword', 'LoginController@resetPassword');
$router->post('resetpassword', 'LoginController@resetPasswordAction');
$router->get('logout', 'LoginController@logout');
$router->get('dashboard', 'DashboardController@index');

$router->get('closetab', 'CommonsController@indexCloseTab');

$router->get('user', 'UserController@index');
$router->get('user/add', 'UserController@indexAdd');
$router->post('user/add', 'UserController@indexAction');
$router->get('user/edit', 'UserController@indexEdit');
$router->post('user/edit', 'UserController@indexAction');
$router->post('user/delete', 'UserController@indexDelete');

$router->get('role', 'UserController@userRole');
$router->get('role/add', 'UserController@userRoleAdd');
$router->post('role/add', 'UserController@userRoleAction');
$router->get('role/edit', 'UserController@userRoleEdit');
$router->post('role/edit', 'UserController@userRoleAction');
$router->post('role/delete', 'UserController@userRoleDelete');

$router->get('profile', 'ProfileController@index');
$router->post('profile', 'ProfileController@indexAction');
$router->post('profile/password', 'ProfileController@indexPassword');

$router->get('invoices', 'InvoiceController@index');
$router->get('invoice/view', 'InvoiceController@indexView');
$router->post('invoice/copy', 'InvoiceController@indexCopy');
$router->get('invoice/pdf', 'InvoiceController@indexPdf');
$router->get('invoice/add', 'InvoiceController@indexAdd');
$router->post('invoice/add', 'InvoiceController@indexAction');
$router->get('invoice/edit', 'InvoiceController@indexEdit');
$router->post('invoice/edit', 'InvoiceController@indexAction');
$router->post('invoice/delete', 'InvoiceController@indexDelete');
$router->post('invoice/sendmail', 'InvoiceController@indexMail');
$router->post('invoice/markas', 'InvoiceController@markAs');
$router->post('invoice/mailmessage', 'InvoiceController@mailMessage');
$router->post('invoice/payment', 'InvoiceController@indexPayment');
$router->post('invoice/payment/delete', 'InvoiceController@indexPaymentDelete');

$router->get('recurring', 'RecurringController@index');
$router->get('recurring/view', 'RecurringController@indexView');
$router->get('recurring/pdf', 'RecurringController@indexPdf');
$router->get('recurring/add', 'RecurringController@indexAdd');
$router->post('recurring/add', 'RecurringController@indexAction');
$router->get('recurring/edit', 'RecurringController@indexEdit');
$router->post('recurring/edit', 'RecurringController@indexAction');
$router->post('recurring/delete', 'RecurringController@indexDelete');

$router->get('recurringjob', 'RecurringjobController@index');

$router->get('expenses', 'ExpenseController@index');
$router->get('expense/add', 'ExpenseController@indexAdd');
$router->post('expense/add', 'ExpenseController@indexAction');
$router->get('expense/edit', 'ExpenseController@indexEdit');
$router->post('expense/edit', 'ExpenseController@indexAction');
$router->post('expense/delete', 'ExpenseController@indexDelete');

$router->get('domains', 'DomainController@index');
$router->get('domain/add', 'DomainController@indexAdd');
$router->post('domain/add', 'DomainController@indexAction');
$router->get('domain/edit', 'DomainController@indexEdit');
$router->post('domain/edit', 'DomainController@indexAction');
$router->post('domain/delete', 'DomainController@indexDelete');

$router->get('tickets', 'TicketController@index');
$router->get('ticket/add', 'TicketController@indexAdd');
$router->post('ticket/add', 'TicketController@indexAction');
$router->get('ticket/edit', 'TicketController@indexEdit');
$router->post('ticket/edit', 'TicketController@indexAction');
$router->get('ticket/fileDownload', 'TicketController@downloadFile');
$router->post('ticket/delete', 'TicketController@indexDelete');

$router->get('quotes', 'QuoteController@index');
$router->get('quote/view', 'QuoteController@indexView');
$router->get('quote/pdf', 'QuoteController@indexPdf');
$router->get('quote/autoinvoice', 'InvoiceController@indexAutoInvoice');
$router->get('quote/add', 'QuoteController@indexAdd');
$router->post('quote/add', 'QuoteController@indexAction');
$router->get('quote/edit', 'QuoteController@indexEdit');
$router->post('quote/edit', 'QuoteController@indexAction');
$router->post('quote/delete', 'QuoteController@indexDelete');

$router->get('contacts', 'ContactController@index');
$router->get('contact/view', 'ContactController@indexView');
$router->get('contact/add', 'ContactController@indexAdd');
$router->post('contact/add', 'ContactController@indexAction');
$router->get('contact/edit', 'ContactController@indexEdit');
$router->post('contact/edit', 'ContactController@indexAction');
$router->post('contact/delete', 'ContactController@indexDelete');
$router->post('contact/sendmail', 'ContactController@indexMail');
$router->post('contact/import', 'ContactController@indexImport');
$router->get('contact/sample', 'ContactController@indexSmapleDownload');
$router->get('contact/search', 'ContactController@indexContactSearch');

$router->get('leads', 'LeadController@index');
$router->get('lead/add', 'LeadController@indexAdd');
$router->post('lead/add', 'LeadController@indexAction');
$router->get('lead/edit', 'LeadController@indexEdit');
$router->post('lead/edit', 'LeadController@indexAction');
$router->post('lead/delete', 'LeadController@indexDelete');
$router->get('lead/convert', 'LeadController@convertLead');

$router->get('accounts', 'AccountController@index');
$router->get('account/add', 'AccountController@indexAdd');
$router->post('account/add', 'AccountController@indexAction');
$router->get('account/edit', 'AccountController@indexEdit');
$router->post('account/edit', 'AccountController@indexAction');
$router->post('account/delete', 'AccountController@indexDelete');
$router->get('account/statement', 'AccountController@indexStatement');

$router->get('account/transactions', 'AccountController@transactions');
$router->get('account/transaction/add', 'AccountController@transactionAdd');
$router->post('account/transaction/add', 'AccountController@transactionAction');
$router->get('account/transaction/edit', 'AccountController@transactionEdit');
$router->post('account/transaction/edit', 'AccountController@transactionAction');
$router->post('account/transaction/delete', 'AccountController@transactionDelete');

$router->get('salarytemplate', 'SalarytemplateController@index');
$router->get('salarytemplate/add', 'SalarytemplateController@indexAdd');
$router->post('salarytemplate/add', 'SalarytemplateController@indexAction');
$router->get('salarytemplate/edit', 'SalarytemplateController@indexEdit');
$router->post('salarytemplate/edit', 'SalarytemplateController@indexAction');
$router->post('salarytemplate/delete', 'SalarytemplateController@indexDelete');

$router->get('managesalary', 'ManagesalaryController@index');
$router->get('managesalary/view', 'ManagesalaryController@indexView');
$router->get('managesalary/add', 'ManagesalaryController@indexAdd');
$router->post('managesalary/add', 'ManagesalaryController@indexAction');
$router->get('managesalary/edit', 'ManagesalaryController@indexEdit');
$router->post('managesalary/edit', 'ManagesalaryController@indexAction');
$router->get('managesalary/history', 'ManagesalaryController@history');
$router->get('managesalary/history/view', 'ManagesalaryController@historyView');
$router->get('managesalary/history/pdf', 'ManagesalaryController@historyPdf');
$router->post('managesalary/history/delete', 'ManagesalaryController@historyDelete');

$router->get('makepayment', 'ManagesalaryController@makepayment');
$router->get('makepayment/add', 'ManagesalaryController@makepaymentAdd');
$router->post('makepayment/add', 'ManagesalaryController@makepaymentAction');
$router->post('checkstaffsalary', 'ManagesalaryController@checkStaffSalary');

$router->get('staffattendance', 'StaffattendanceController@index');
$router->get('staffattendance/view', 'StaffattendanceController@indexView');
$router->get('staffattendance/add', 'StaffattendanceController@indexAdd');
$router->post('staffattendance/add', 'StaffattendanceController@indexAction');

$router->get('noticeboard', 'NoticeboardController@index');
$router->get('noticeboard/view', 'NoticeboardController@indexView');
$router->get('noticeboard/add', 'NoticeboardController@indexAdd');
$router->post('noticeboard/add', 'NoticeboardController@indexAction');
$router->get('noticeboard/edit', 'NoticeboardController@indexEdit');
$router->post('noticeboard/edit', 'NoticeboardController@indexAction');
$router->post('noticeboard/delete', 'NoticeboardController@indexDelete');

// $router->get('clients', 'ContactController@indexClients');
// $router->get('client/edit', 'ContactController@indexClientEdit');
// $router->post('client/action', 'ContactController@indexClientAction');
// $router->post('client/delete', 'ContactController@indexClientDelete');

$router->get('calendar', 'CalendarController@index');
$router->get('calendar/event', 'CalendarController@indexEvent');
$router->post('calendar/add', 'CalendarController@indexAction');
$router->post('calendar/edit', 'CalendarController@indexAction');
$router->post('calendar/delete', 'CalendarController@indexDelete');

$router->get('items', 'ItemsController@index');
$router->get('item/add', 'ItemsController@indexAdd');
$router->post('item/add', 'ItemsController@indexAction');
$router->get('item/edit', 'ItemsController@indexEdit');
$router->post('item/edit', 'ItemsController@indexAction');
$router->post('item/delete', 'ItemsController@indexDelete');
$router->get('item/search', 'ItemsController@indexItemSearch');

$router->get('taxes', 'FinanceController@taxes');
$router->post('tax/add', 'FinanceController@taxAction');
$router->post('tax/edit', 'FinanceController@taxAction');
$router->post('tax/delete', 'FinanceController@taxDelete');

$router->get('paymentmethod', 'FinanceController@paymentMethod');
$router->post('paymentmethod/add', 'FinanceController@paymentMethodAction');
$router->post('paymentmethod/edit', 'FinanceController@paymentMethodAction');
$router->post('paymentmethod/delete', 'FinanceController@paymentMethodDelete');

$router->get('currency', 'FinanceController@currency');
$router->post('currency/add', 'FinanceController@currencyAction');
$router->post('currency/edit', 'FinanceController@currencyAction');
$router->post('currency/delete', 'FinanceController@currencyDelete');

$router->get('paypalgateway', 'FinanceController@paypalGateway');
$router->post('paypalgateway', 'FinanceController@paypalGatewayAction');

$router->get('departments', 'TypesController@departments');
$router->post('department/add', 'TypesController@departmentAction');
$router->post('department/edit', 'TypesController@departmentAction');
$router->post('department/delete', 'TypesController@departmentDelete');

$router->get('expensetype', 'TypesController@expenseType');
$router->post('expensetype/add', 'TypesController@expenseTypeAction');
$router->post('expensetype/edit', 'TypesController@expenseTypeAction');
$router->post('expensetype/delete', 'TypesController@expenseTypeDelete');

$router->get('projects', 'ProjectController@index');
$router->get('project/view', 'ProjectController@indexView');
$router->get('project/add', 'ProjectController@indexAdd');
$router->post('project/add', 'ProjectController@indexAction');
$router->get('project/edit', 'ProjectController@indexEdit');
$router->post('project/edit', 'ProjectController@indexAction');
$router->post('project/delete', 'ProjectController@indexDelete');
$router->post('project/comment', 'ProjectController@makeComment');

$router->get('notes', 'NoteController@index');
$router->get('note/add', 'NoteController@indexAdd');
$router->post('note/add', 'NoteController@indexAction');
$router->get('note/edit', 'NoteController@indexEdit');
$router->post('note/edit', 'NoteController@indexAction');
$router->post('note/delete', 'NoteController@indexDelete');

$router->get('info', 'SettingController@siteInfo');
$router->post('info', 'SettingController@siteInfoAction');

$router->get('customization', 'SettingController@customization');
$router->post('customization', 'SettingController@customizationAction');

$router->get('subscriber', 'SubscriberController@index');
$router->get('subscriber/add', 'SubscriberController@indexAdd');
$router->post('subscriber/add', 'SubscriberController@indexAction');
$router->get('subscriber/edit', 'SubscriberController@indexEdit');
$router->post('subscriber/edit', 'SubscriberController@indexAction');
$router->post('subscriber/delete', 'SubscriberController@indexDelete');

$router->get('send/email', 'SenderController@indexMail');
$router->post('send/email', 'SenderController@indexMailAction');
$router->get('sendbulk/email', 'SenderController@indexBulkMail');
$router->post('sendbulk/email', 'SenderController@indexBulkMailAction');
$router->post('get/receiver', 'SenderController@indexUsers');

$router->get('emailtemplate', 'EmailtemplateController@index');
$router->post('emailtemplate', 'EmailtemplateController@indexAction');
$router->get('emailsettings', 'EmailtemplateController@emailSettings');
$router->post('emailsettings', 'EmailtemplateController@emailSettingsAction');

$router->get('emaillog', 'UtilitiesController@emailLog');
$router->get('cronlog', 'UtilitiesController@cronLog');

$router->get('dbbackup', 'UtilitiesController@databaseBackup');
$router->post('dbbackup', 'UtilitiesController@dbBackupDownload');

$router->get('cronsetting', 'SettingController@cronSetting');
$router->post('cronsetting', 'SettingController@cronSettingAction');

$router->post('attach/documents', 'UploadController@attachDocuments');
$router->post('attach/documents/delete', 'UploadController@attachDocumentsDelete');

$router->get('get/media', 'UploadController@getMedia');
$router->post('media/upload', 'UploadController@uploadMedia');
$router->post('media/delete', 'UploadController@mediaDelete');