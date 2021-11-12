--
-- Database: `Client Manager`
--

-- --------------------------------------------------------


-- --------------------------------------------------------

SET sql_mode = '';

-- --------------------------------------------------------

--
-- Table structure for table `cm_accounts`
--

DROP TABLE IF EXISTS `cm_accounts`;
CREATE TABLE IF NOT EXISTS `cm_accounts` (
  `id` int(11) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_no` varchar(255) NOT NULL,
  `currency` int(11) NOT NULL,
  `initial_balance` decimal(10,2) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `contact_person_phone` varchar(50) NOT NULL,
  `bank_url` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_account_transaction`
--

DROP TABLE IF EXISTS `cm_account_transaction`;
CREATE TABLE IF NOT EXISTS `cm_account_transaction` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `debit` decimal(10,2) NOT NULL,
  `credit` decimal(10,2) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `transaction_type` int(1) NOT NULL,
  `method` int(11) DEFAULT NULL,
  `account_id` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `user_id` int(11) NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_attached_files`
--

DROP TABLE IF EXISTS `cm_attached_files`;
CREATE TABLE IF NOT EXISTS `cm_attached_files` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `ext` varchar(10) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `file_type_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_clients`
--

DROP TABLE IF EXISTS `cm_clients`;
CREATE TABLE IF NOT EXISTS `cm_clients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `emailconfirmed` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_client_activity`
--

DROP TABLE IF EXISTS `cm_client_activity`;
CREATE TABLE IF NOT EXISTS `cm_client_activity` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type_id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_comments`
--

DROP TABLE IF EXISTS `cm_comments`;
CREATE TABLE IF NOT EXISTS `cm_comments` (
  `id` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `comment_by` int(11) NOT NULL,
  `comment_to` varchar(255) NOT NULL,
  `to_id` int(11) NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_contacts`
--

DROP TABLE IF EXISTS `cm_contacts`;
CREATE TABLE IF NOT EXISTS `cm_contacts` (
  `id` int(11) NOT NULL,
  `salutation` varchar(40) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `company` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `website` varchar(255) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `country` varchar(100) NOT NULL,
  `persons` text NOT NULL,
  `remark` text NOT NULL,
  `marketing` text NOT NULL,
  `expire` date NOT NULL,
  `lead_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `other` text NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_currency`
--

DROP TABLE IF EXISTS `cm_currency`;
CREATE TABLE IF NOT EXISTS `cm_currency` (
  `id` int(5) NOT NULL,
  `name` varchar(5) NOT NULL,
  `abbr` varchar(10) NOT NULL,
  `other` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_departments`
--

DROP TABLE IF EXISTS `cm_departments`;
CREATE TABLE IF NOT EXISTS `cm_departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_domains`
--

DROP TABLE IF EXISTS `cm_domains`;
CREATE TABLE IF NOT EXISTS `cm_domains` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `registration_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `provider` varchar(255) NOT NULL,
  `hosting` varchar(255) NOT NULL,
  `customer` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `renew` tinyint(1) NOT NULL,
  `remark` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_email_logs`
--

DROP TABLE IF EXISTS `cm_email_logs`;
CREATE TABLE IF NOT EXISTS `cm_email_logs` (
  `id` int(11) NOT NULL,
  `email_to` varchar(100) NOT NULL,
  `email_bcc` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `type_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_email_template`
--

DROP TABLE IF EXISTS `cm_email_template`;
CREATE TABLE IF NOT EXISTS `cm_email_template` (
  `id` int(11) NOT NULL,
  `template` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Dumping data for table `cm_email_template`
--

INSERT INTO `cm_email_template` (`id`, `template`, `name`, `subject`, `message`, `status`, `last_updated`) VALUES
(1, 'newuser', 'New Admin User', 'New Admin User ', '<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">Hello {name},</p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">Welcome to {business_name}.</p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">Your admin credentials has been created. Now you can login to admin portal. See below for credentials... </p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">---------------------------------------------------------------------------------------<br /></p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">Login URL: {login_url} <br />Username: {username}<br />Email Address: {email}<br />Password: {password}</p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">----------------------------------------------------------------------------------------</p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">We very much appreciate you for choosing us.</p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">{business_name} Team</p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\"><span style=\"font-family:Poppins, Poppins, sans-serif;color:#FF0000;\"><span style=\"font-weight:bolder;\">*DO NOT REPLY TO THIS E-MAIL*</span></span><br style=\"font-family:Poppins, Poppins, sans-serif;\" /><span style=\"font-family:Poppins, Poppins, sans-serif;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</span><br /></p>', 1, '2020-05-22 13:07:31'),
(2, 'newclient', 'New Client / Client Sign up', 'New Client', '<p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">Dear {name},</p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">Welcome to {business_name}.</p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">You can track your invoice, quotes, tickets, profile, transactions from this portal.</p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">Your login information is as follows:</p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">---------------------------------------------------------------------------------------</p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">Login URL: {client_login_url} <br />Email Address: {email}<br />Password: Your chosen password.</p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">----------------------------------------------------------------------------------------</p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">We very much appreciate you for choosing us.</p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\">{business_name} Team</p><p style=\"font-family:Verdana, Arial, Helvetica, sans-serif;\"><span style=\"font-family:Poppins, Poppins, sans-serif;color:#FF0000;\"><span style=\"font-weight:bolder;\">*DO NOT REPLY TO THIS E-MAIL*</span></span><br style=\"font-family:Poppins, Poppins, sans-serif;\" /><span style=\"font-family:Poppins, Poppins, sans-serif;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</span><br /></p>', 1, '2020-05-22 13:07:49'),
(3, 'forgotpassword', 'Forgot password', 'Forgot Password', '<div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;padding:5px;font-size:11pt;font-weight:bold;\"><span style=\"font-size:13.3333px;font-weight:400;font-family:Verdana;\">Hello {name}</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;padding:5px;font-size:11pt;font-weight:bold;\"><span style=\"font-size:13.3333px;font-weight:400;font-family:Verdana;\">This is to confirm that we have received a Forgot Password request for your Account Username - {email}</span><br /></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;font-size:13.3333px;padding:5px;\"><span style=\"font-family:Verdana;\">Click this link to reset your password- </span><br /><font color=\"#1da9c0\"><span style=\"padding:3px;font-family:Verdana;\"><b>{reset_link}</b></span></font></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;font-size:13.3333px;padding:5px;\"><span style=\"font-family:Verdana;\">Please note: until your password has been changed, your current password will remain valid. If you have not generated this request. Please contact us as soon as possible.</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;font-size:13.3333px;padding:0px 5px;\"><span style=\"font-family:Verdana;\">Regards,</span><br /><span style=\"font-family:Verdana;\">{business_name} Team</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;font-size:13.3333px;padding:0px 5px;\"><br /></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;font-size:13.3333px;padding:0px 5px;\"><span style=\"font-family:Poppins, Poppins, sans-serif;font-size:14px;color:#FF0000;\"><span style=\"font-weight:bolder;font-family:Verdana;\">*DO NOT REPLY TO THIS E-MAIL*</span></span><br style=\"color:rgb(0,0,0);font-family:Poppins, Poppins, sans-serif;font-size:14px;\" /><span style=\"color:rgb(0,0,0);font-family:Verdana;font-size:14px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</span><br /></div>', 1, '2020-05-22 13:07:57'),
(4, 'newinvoice', 'Invoice Created / New Invoice', 'Invoice Created', '<div style=\"padding:5px;\"><font color=\"#222222\" face=\"verdana, droid sans, lucida sans, sans-serif\"><span style=\"font-size:13.3333px;font-family:Verdana;\"><span style=\"font-size:14px;\">Hello </span><b><span style=\"font-size:14px;font-family:Verdana;\">{company}</span></b><span style=\"font-size:14px;font-family:Verdana;\">,</span></span></font></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;padding:5px;font-size:11pt;font-weight:bold;\"><span style=\"font-size:14px;font-weight:400;font-family:Verdana;\">This email serves as your official invoice from </span><strong style=\"font-size:13.3333px;\"><span style=\"font-family:Verdana;font-size:14px;\">{business_name}.</span></strong><br /></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;font-size:13.3333px;padding:10px 5px;\"><span style=\"font-family:Verdana;font-size:14px;\">Invoice URL: {invoice_url}</span><br /><span style=\"font-family:Verdana;font-size:14px;\">Invoice ID: {invoice_id}</span><br /><span style=\"font-family:Verdana;font-size:14px;\">Invoice Amount: {invoice_amount}</span><br /><span style=\"font-family:Verdana;font-size:14px;\">Paid Amount: {invoice_paid}</span><br /><span style=\"font-family:Verdana;font-size:14px;\">Due Amount: {invoice_due}</span><br /><span style=\"font-family:Verdana;font-size:14px;\">Due Date: {invoice_due_date}</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;font-size:13.3333px;padding:5px;\"><span style=\"font-size:14px;line-height:21.3333px;font-family:Verdana;\">Invoice PDF has been attached to this mail. If you have any questions or need assistance, please don\'t hesitate to contact us.</span><br /></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;font-size:13.3333px;padding:0px 5px;\"><span style=\"font-family:Verdana;font-size:14px;\">Best Regards,</span><br /><span style=\"font-family:Verdana;font-size:14px;\">{business_name} Team</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;font-size:13.3333px;padding:0px 5px;\"><br /></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;font-size:13.3333px;padding:0px 5px;\"><span style=\"font-family:Poppins, Poppins, sans-serif;font-size:14px;color:#FF0000;\"><span style=\"font-weight:bolder;font-family:Verdana;font-size:14px;\">*DO NOT REPLY TO THIS E-MAIL*</span></span><br style=\"color:rgb(0,0,0);font-family:Poppins, Poppins, sans-serif;font-size:14px;\" /><span style=\"color:rgb(0,0,0);font-family:Verdana;font-size:14px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</span><br /></div>', 1, '2020-05-22 13:08:03'),
(5, 'paymentconfirmation', 'Invoice Payment Confirmation', 'Invoice Payment Confirmation', '&lt;div style=&quot;padding:5px;&quot;&gt;&lt;font color=&quot;#222222&quot; face=&quot;verdana, droid sans, lucida sans, sans-serif&quot;&gt;&lt;span style=&quot;font-size:13.3333px;font-family:Verdana;&quot;&gt;Hello &lt;b&gt;{company}&lt;/b&gt;,&lt;/span&gt;&lt;/font&gt;&lt;/div&gt;&lt;div style=&quot;color:rgb(34,34,34);font-family:verdana, &#039;droid sans&#039;, &#039;lucida sans&#039;, sans-serif;padding:5px;font-size:11pt;font-weight:bold;&quot;&gt;&lt;span style=&quot;font-size:13.3333px;font-weight:400;font-family:Verdana;&quot;&gt;This is a payment receipt for Invoice {id}&lt;/span&gt;&lt;br /&gt;&lt;/div&gt;&lt;div style=&quot;color:rgb(34,34,34);font-family:verdana, &#039;droid sans&#039;, &#039;lucida sans&#039;, sans-serif;font-size:13.3333px;padding:5px;&quot;&gt;&lt;span style=&quot;font-family:Verdana;&quot;&gt;Login to your client Portal to view this invoice.&lt;/span&gt;&lt;/div&gt;&lt;div style=&quot;color:rgb(34,34,34);font-family:verdana, &#039;droid sans&#039;, &#039;lucida sans&#039;, sans-serif;font-size:13.3333px;padding:10px 5px;&quot;&gt;&lt;span style=&quot;font-family:Verdana;&quot;&gt;Invoice URL: {inv_url}&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-family:Verdana;&quot;&gt;Invoice ID: {id}&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-family:Verdana;&quot;&gt;Paid Amount: {paid_amount}&lt;/span&gt;&lt;br style=&quot;font-size:13.3333px;&quot; /&gt;&lt;span style=&quot;font-size:13.3333px;font-family:Verdana;&quot;&gt;Paid Date: {paid_date}&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-family:Verdana;&quot;&gt;Transaction Id: {txn_id}&lt;/span&gt;&lt;/div&gt;&lt;div style=&quot;color:rgb(34,34,34);font-family:verdana, &#039;droid sans&#039;, &#039;lucida sans&#039;, sans-serif;font-size:13.3333px;padding:5px;&quot;&gt;&lt;span style=&quot;font-size:13.3333px;line-height:21.3333px;font-family:Verdana;&quot;&gt;If you have any questions or need assistance, please don&#039;t hesitate to contact us.&lt;/span&gt;&lt;br /&gt;&lt;/div&gt;&lt;div style=&quot;color:rgb(34,34,34);font-family:verdana, &#039;droid sans&#039;, &#039;lucida sans&#039;, sans-serif;font-size:13.3333px;padding:0px 5px;&quot;&gt;&lt;span style=&quot;font-family:Verdana;&quot;&gt;Best Regards,&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-family:Verdana;&quot;&gt;{business_name} Team&lt;/span&gt;&lt;/div&gt;&lt;div style=&quot;color:rgb(34,34,34);font-family:verdana, &#039;droid sans&#039;, &#039;lucida sans&#039;, sans-serif;font-size:13.3333px;padding:0px 5px;&quot;&gt;&lt;br /&gt;&lt;/div&gt;&lt;div style=&quot;color:rgb(34,34,34);font-family:verdana, &#039;droid sans&#039;, &#039;lucida sans&#039;, sans-serif;font-size:13.3333px;padding:0px 5px;&quot;&gt;&lt;span style=&quot;font-family:Poppins, Poppins, sans-serif;font-size:14px;color:#FF0000;&quot;&gt;&lt;span style=&quot;font-weight:bolder;font-family:Verdana;&quot;&gt;*DO NOT REPLY TO THIS E-MAIL*&lt;/span&gt;&lt;/span&gt;&lt;br style=&quot;color:rgb(0,0,0);font-family:Poppins, Poppins, sans-serif;font-size:14px;&quot; /&gt;&lt;span style=&quot;color:rgb(0,0,0);font-family:Verdana;font-size:14px;&quot;&gt;This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!&lt;/span&gt;&lt;br /&gt;&lt;/div&gt;', 1, '2020-05-19 11:16:37'),
(6, 'invoicepaymentreminder', 'Invoice Payment Reminder', 'Invoice Payment Reminder', '<div style=\"padding:5px;\"><span style=\"font-family:Verdana;\">Hello <b>{company}</b>,</span></div><div style=\"padding:5px;\"><span style=\"font-family:Verdana;\">This is a billing reminder that your invoice no. {invoice_id} which was generated on {invoice_date} is due on </span><span style=\"font-family:Verdana;\">{invoice_due_date}</span></div><div style=\"padding:5px;\"><span style=\"font-family:Verdana;\">Invoice URL: {invoice_url}</span></div><div style=\"padding:5px;\"><span style=\"font-family:Verdana;\">Invoice ID: {invoice_id}</span></div><div style=\"padding:5px;\"><span style=\"font-family:Verdana;\">Invoice Amount: {invoice_amount}</span></div><div style=\"padding:5px;\"><span style=\"font-family:Verdana;\">Due Date: </span><span style=\"font-family:Verdana;\">{invoice_due_date}</span></div><div style=\"padding:5px;\"><span style=\"font-family:Verdana;\">If you have any questions or need assistance, please don\'t hesitate to contact us.</span></div><div style=\"padding:5px;\"><span style=\"font-family:Verdana;\">Best Regards,</span></div><div style=\"padding:5px;\"><span style=\"font-family:Verdana;\">{business_name} Team</span></div><div style=\"padding:0px 5px;\"><br /></div><div style=\"padding:0px 5px;\"><span style=\"color:rgb(255,0,0);font-weight:bold;\"><span style=\"font-family:Verdana;\">*DO NOT REPLY TO THIS E-MAIL*</span><br /></span><span style=\"font-family:Verdana;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</span><br /></div>', 1, '2020-05-22 13:08:13'),
(7, 'invoiceoverduenotice', 'Invoice Overdue Notice', 'Invoice Overdue Notice', '<div style=\"padding:5px;\"><font color=\"#222222\" face=\"verdana, droid sans, lucida sans, sans-serif\"><span style=\"font-size:13.3333px;font-family:Verdana;\">Hello <b>{company}</b>,</span></font></div><div style=\"padding:5px;\"><span style=\"font-size:13.3333px;color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;\">This is the notice that your invoice number <b>{invoice_id}</b> which was generated on {invoice_date} is now overdue.</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;padding:5px;font-size:11pt;font-weight:bold;\"><span style=\"font-family:Verdana;font-size:13.3333px;font-weight:400;\">Invoice URL: {invoice_url}</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;padding:5px;font-size:11pt;font-weight:bold;\"><span style=\"font-size:13.3333px;font-weight:400;\">Invoice Amount: {invoice_amount}</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;padding:5px;font-size:11pt;font-weight:bold;\"><span style=\"font-size:13.3333px;font-weight:400;\">Due Date: {invoice_due_date}</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;padding:5px;font-size:11pt;font-weight:bold;\"><span style=\"font-size:13.3333px;font-weight:400;\">If you have any questions or need assistance, please don\'t hesitate to contact us.</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;padding:5px;font-size:11pt;font-weight:bold;\"><span style=\"font-size:13.3333px;font-weight:400;\"><br /></span></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;padding:5px;font-size:11pt;font-weight:bold;\"><span style=\"font-size:13.3333px;font-weight:400;\">Best Regards,</span><br /></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;padding:5px;font-size:11pt;font-weight:bold;\"><span style=\"font-size:13.3333px;font-weight:400;\">{business_name} Team</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;font-size:13.3333px;padding:0px 5px;\"><br /></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;font-size:13.3333px;padding:0px 5px;\"><span style=\"font-family:Poppins, Poppins, sans-serif;font-size:14px;color:#FF0000;\"><span style=\"font-weight:bolder;font-family:Verdana;\">*DO NOT REPLY TO THIS E-MAIL*</span></span><br style=\"color:rgb(0,0,0);font-family:Poppins, Poppins, sans-serif;font-size:14px;\" /><span style=\"color:rgb(0,0,0);font-family:Verdana;font-size:14px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</span><br /></div>', 1, '2020-05-22 13:08:18'),
(8, 'invoicerefundconfirmation', 'Invoice Refund Confirmation', 'Invoice Refund Confirmation', '<div style=\"padding:5px;\"><font color=\"#222222\" face=\"verdana, droid sans, lucida sans, sans-serif\"><span style=\"font-size:13.3333px;font-family:Verdana;\">Hello <b>{company}</b>,</span></font></div><div style=\"padding:5px;\"><span style=\"font-size:13.3333px;color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;\">This is confirmation that a refund has been processed for Invoice <b>{invoice_id}</b> sent on {invoice_date}.</span></div><div style=\"padding:5px;\"><span style=\"font-size:13.3333px;color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;\"><br /></span><span style=\"font-size:13.3333px;color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;\">Invoice URL: {invoice_url}</span></div><div style=\"padding:5px;\"><span style=\"font-size:13.3333px;color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;\">Invoice ID: {invoice_id}</span></div><div style=\"padding:5px;\"><span style=\"font-size:13.3333px;color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;\">Invoice Amount: {invoice_amount}</span></div><div style=\"padding:5px;\"><span style=\"font-size:13.3333px;color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;\">Due Date: {invoice_due_date}</span></div><div style=\"padding:5px;\"><span style=\"font-size:13.3333px;color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;\">If you have any questions or need assistance, please don\'t hesitate to contact us.</span></div><div style=\"padding:5px;\"><span style=\"font-size:13.3333px;color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;\"><br /></span></div><div style=\"padding:5px;\"><span style=\"font-size:13.3333px;color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;\">Best Regards,</span></div><div style=\"padding:5px;\"><span style=\"font-size:13.3333px;color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;\">{business_name} Team</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;padding:5px;font-size:11pt;font-weight:bold;\"><div style=\"font-size:13.3333px;font-weight:400;padding:0px 5px;\"><br /></div></div><div style=\"color:rgb(34,34,34);font-family:verdana, \'droid sans\', \'lucida sans\', sans-serif;font-size:13.3333px;padding:0px 5px;\"><span style=\"font-family:Poppins, Poppins, sans-serif;font-size:14px;color:#FF0000;\"><span style=\"font-weight:bolder;font-family:Verdana;\">*DO NOT REPLY TO THIS E-MAIL*</span></span><br style=\"color:rgb(0,0,0);font-family:Poppins, Poppins, sans-serif;font-size:14px;\" /><span style=\"color:rgb(0,0,0);font-family:Verdana;font-size:14px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</span><br /></div>', 1, '2020-05-22 13:08:33'),
(9, 'newquotes', 'Quotation Created / New Quotation', 'Quotes Created', '<div style=\"padding:5px;\"><span style=\"color:rgb(34,34,34);font-family:Verdana;font-size:13.3333px;\">Hello {company}</span><br /></div><div style=\"color:rgb(34,34,34);font-family:verdana, sans-serif;font-size:13.3333px;padding:5px;\"><span style=\"font-family:Verdana;\">Here is the quote you requested for {project_name}. The quote is valid until {valid_until}.</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, sans-serif;font-size:13.3333px;padding:10px 5px;\"><span style=\"font-family:Verdana;\">Quote URL: {quote_url}</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, sans-serif;font-size:13.3333px;padding:10px 5px;\"><span style=\"font-size:13.3333px;font-family:Verdana;\">Amount: {amount}</span><br /></div><div style=\"color:rgb(34,34,34);font-family:verdana, sans-serif;font-size:13.3333px;padding:5px;\"><span style=\"font-size:13.3333px;line-height:21.3333px;\"><span style=\"font-family:Verdana;font-size:13.3333px;\">Quote PDF has been attached to this mail.</span><span style=\"font-family:Verdana;\"> You may view the quote at any time and if you have any query then contact us.</span></span></div><div style=\"color:rgb(34,34,34);font-family:verdana, sans-serif;font-size:13.3333px;padding:5px;\"><span style=\"font-family:Verdana;\">Best Regards,</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, sans-serif;font-size:13.3333px;padding:0px 5px;\"><span style=\"font-family:Verdana;\">{business_name} Team</span></div><div style=\"color:rgb(34,34,34);font-family:verdana, sans-serif;font-size:13.3333px;padding:0px 5px;\"><br /></div><div style=\"color:rgb(34,34,34);font-family:verdana, sans-serif;font-size:13.3333px;padding:0px 5px;\"><span style=\"font-family:Poppins, Poppins, sans-serif;font-size:14px;color:#FF0000;\"><span style=\"font-weight:bolder;font-family:Verdana;\">*DO NOT REPLY TO THIS E-MAIL*</span></span><br style=\"color:rgb(0,0,0);font-family:Poppins, Poppins, sans-serif;font-size:14px;\" /><span style=\"color:rgb(0,0,0);font-family:Verdana;font-size:14px;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</span><br /></div>', 1, '2020-05-22 13:08:43'),
(10, 'newticket', 'New Ticket', 'New Ticket Received', '<p><span style=\"font-family:Verdana;\">Dear {NAME},</span></p><p><span style=\"font-family:Verdana;\">Your support ticket </span><span style=\"font-weight:bolder;font-family:Verdana;\">{SUBJECT}</span><span style=\"font-family:Verdana;\"> has been submitted. We try to reply to all tickets as soon as possible, usually within 24 hours.</span></p><p><span style=\"font-family:Verdana;\">Your ID: {ID}</span></p><p><span style=\"font-family:Verdana;\">Your Email Address: {EMAIL}</span></p><p><span style=\"font-family:Verdana;\">Ticket message: {MESSAGE}</span></p><p><span style=\"font-family:Verdana;\">You can view the status of your ticket here: {TICKETURL}</span></p><p><span style=\"font-family:Verdana;\">You will receive an e-mail notification when our staff replies to your ticket.</span></p><p><span style=\"color:#FF0000;\"><span style=\"font-weight:bolder;font-family:Verdana;\">*DO NOT REPLY TO THIS E-MAIL*</span></span><br /><span style=\"font-family:Verdana;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</span></p>', 1, '2020-05-22 13:07:20'),
(11, 'ticketresponce', 'Ticket Responce', 'Ticket Responce ', '<p><span style=\"font-family:Verdana;\">Hello,</span></p><p><span style=\"font-family:Verdana;\">Staff just reply of your ticket </span><strong><span style=\"font-family:Verdana;\">{SUBJECT}</span></strong><span style=\"font-family:Verdana;\">.</span></p><p><span style=\"font-family:Verdana;\">Name: {NAME}</span></p><p><span style=\"font-family:Verdana;\">ID: {ID}</span></p><p><span style=\"font-family:Verdana;\">Email Address: {EMAIL}</span></p><p><span style=\"font-family:Verdana;\">Message: {MESSAGE}</span></p><p><span style=\"font-family:Verdana;\">You can manage this ticket here: {TICKETURL}</span></p><p><span style=\"color:#FF0000;font-family:Verdana;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br /><span style=\"font-family:Verdana;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</span></p>', 1, '2020-05-22 13:09:43'),
(12, 'ticketreply', 'Ticket Reply', 'Ticket Reply  ', '<p><span style=\"font-family:Verdana;\">Hello,</span></p><p><span style=\"font-family:Verdana;\">A new reply of ticket </span><strong><span style=\"font-family:Verdana;\">{SUBJECT} </span></strong><span style=\"font-family:Verdana;\">has been submitted.</span></p><p><span style=\"font-family:Verdana;\">Name: {NAME}</span></p><p><span style=\"font-family:Verdana;\">Ticket ID: {ID}</span></p><p><span style=\"font-family:Verdana;\">Email Address: {EMAIL}</span></p><p><span style=\"font-family:Verdana;\">Message: {MESSAGE}</span></p><p><span style=\"font-family:Verdana;\">You can manage this ticket here:</span><br /><span style=\"font-family:Verdana;\">{TICKETURL}</span></p><p><span style=\"color:#FF0000;font-family:Verdana;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br /><span style=\"font-family:Verdana;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wonot receive your reply!</span></p>', 1, '2020-05-22 13:10:02'),
(13, 'closeticket', 'Close Ticket', 'Close Ticket ', '<p><span style=\"font-family:Verdana;\">Ticket Close</span></p><p><span style=\"font-family:Verdana;\">Ticket Subject : {SUBJECT}</span></p><p><span style=\"font-family:Verdana;\">Ticket ID:{ID} is Closed.</span></p><p><span style=\"font-family:Verdana;\">You can manage this ticket here</span></p><p><span style=\"font-family:Verdana;\">{TICKETURL}</span></p><p><span style=\"color:#FF0000;font-family:Verdana;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br /><span style=\"font-family:Verdana;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</span></p>', 1, '2020-05-22 13:10:08'),
(14, 'deleteticket', 'Delete Ticket', 'Delete Ticket', '<p><span style=\"font-family:Verdana;\">Ticket Deleted</span></p><p><span style=\"font-family:Verdana;\">Ticket Subject : {SUBJECT}</span></p><p><span style=\"font-family:Verdana;\">Ticket ID:{ID} is Deleted.</span></p><p><span style=\"color:#FF0000;font-family:Verdana;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br /><span style=\"font-family:Verdana;\">This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</span></p>', 1, '2020-05-22 13:10:13');


-- --------------------------------------------------------

--
-- Table structure for table `cm_event`
--

DROP TABLE IF EXISTS `cm_event`;
CREATE TABLE IF NOT EXISTS `cm_event` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_date` date NOT NULL,
  `end_time` time NOT NULL,
  `allday` tinyint(1) NOT NULL,
  `color` varchar(255) NOT NULL,
  `other` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_expenses`
--

DROP TABLE IF EXISTS `cm_expenses`;
CREATE TABLE IF NOT EXISTS `cm_expenses` (
  `id` int(11) NOT NULL,
  `purchase_by` varchar(255) NOT NULL,
  `expense_type` int(11) NOT NULL,
  `currency` int(3) NOT NULL,
  `purchase_amount` decimal(10,2) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `purchase_date` date NOT NULL,
  `description` text NOT NULL,
  `other` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_expense_type`
--

DROP TABLE IF EXISTS `cm_expense_type`;
CREATE TABLE IF NOT EXISTS `cm_expense_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `other` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_invoice`
--

DROP TABLE IF EXISTS `cm_invoice`;
CREATE TABLE IF NOT EXISTS `cm_invoice` (
  `id` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `invoicedate` date NOT NULL,
  `duedate` date NOT NULL,
  `paiddate` date DEFAULT NULL,
  `currency` int(5) NOT NULL,
  `paymenttype` int(11) NOT NULL,
  `items` text NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `discount_type` int(2) NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid` decimal(10,2) NOT NULL,
  `due` decimal(10,2) NOT NULL,
  `note` text DEFAULT NULL,
  `tc` varchar(255) NOT NULL,
  `want_payment` int(1) NOT NULL,
  `want_signature` int(1) NOT NULL,
  `project_id` int(11) NOT NULL,
  `quote_id` int(11) NOT NULL,
  `other` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `inv_status` tinyint(1) NOT NULL,
  `rid` int(11) NOT NULL,
  `mail_sent` tinyint(1) NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_items`
--

DROP TABLE IF EXISTS `cm_items`;
CREATE TABLE IF NOT EXISTS `cm_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `currency` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `other` varchar(255) NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_leads`
--

DROP TABLE IF EXISTS `cm_leads`;
CREATE TABLE IF NOT EXISTS `cm_leads` (
  `id` int(11) NOT NULL,
  `salutation` varchar(30) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `company` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `website` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `country` varchar(255) NOT NULL,
  `remark` text NOT NULL,
  `source` varchar(255) NOT NULL,
  `marketing` text NOT NULL,
  `expire` date NOT NULL,
  `contact_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_login_attempts`
--

DROP TABLE IF EXISTS `cm_login_attempts`;
CREATE TABLE IF NOT EXISTS `cm_login_attempts` (
  `user_id` int(11) NOT NULL,
  `email` varchar(96) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `count` int(4) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cm_media`
--

DROP TABLE IF EXISTS `cm_media`;
CREATE TABLE IF NOT EXISTS `cm_media` (
  `id` int(11) NOT NULL,
  `media` varchar(255) NOT NULL,
  `ext` varchar(5) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `user_id` int(11) NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cm_media`
--

INSERT INTO `cm_media` (`id`, `media`, `ext`, `status`, `user_id`, `date_of_joining`) VALUES
(1, 'media-11522721505e4801f4d734d.png', 'png', 1, 1, '2020-02-15 14:36:36'),
(2, 'media-15313023855e4801f4d9000.png', 'png', 1, 1, '2020-02-15 14:36:36'),
(3, 'media-12063910725e4801f50a709.png', 'png', 1, 1, '2020-02-15 14:36:37');

-- --------------------------------------------------------

--
-- Table structure for table `cm_notes`
--

DROP TABLE IF EXISTS `cm_notes`;
CREATE TABLE IF NOT EXISTS `cm_notes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `color` varchar(7) NOT NULL,
  `background` varchar(7) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `other` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_noticeboard`
--

DROP TABLE IF EXISTS `cm_noticeboard`;
CREATE TABLE IF NOT EXISTS `cm_noticeboard` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_payments`
--

DROP TABLE IF EXISTS `cm_payments`;
CREATE TABLE IF NOT EXISTS `cm_payments` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `txn_id` varchar(255) NOT NULL,
  `method` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` int(3) NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `payer_ip` varchar(255) NOT NULL,
  `payer_email` varchar(255) NOT NULL,
  `paid_to` varchar(255) NOT NULL,
  `payment_date` date NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_payment_method`
--

DROP TABLE IF EXISTS `cm_payment_method`;
CREATE TABLE IF NOT EXISTS `cm_payment_method` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `other` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_projects`
--

DROP TABLE IF EXISTS `cm_projects`;
CREATE TABLE IF NOT EXISTS `cm_projects` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `customer` int(11) NOT NULL,
  `billing_method` int(2) NOT NULL,
  `currency` int(4) NOT NULL,
  `rate_hour` varchar(10) NOT NULL,
  `project_hour` varchar(10) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `staff` text NOT NULL,
  `task` text NOT NULL,
  `completed` int(3) NOT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_proposal`
--

DROP TABLE IF EXISTS `cm_proposal`;
CREATE TABLE IF NOT EXISTS `cm_proposal` (
  `id` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `proposal` text NOT NULL,
  `currency` int(5) NOT NULL,
  `paymenttype` int(2) NOT NULL,
  `date` date NOT NULL,
  `expiry` date NOT NULL,
  `items` text NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `discounttype` int(1) NOT NULL,
  `discount` varchar(10) NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `note` varchar(255) NOT NULL,
  `tc` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  `mail_sent` tinyint(1) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_recurring_invoice`
--

DROP TABLE IF EXISTS `cm_recurring_invoice`;
CREATE TABLE IF NOT EXISTS `cm_recurring_invoice` (
  `id` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `currency` int(5) NOT NULL,
  `paymenttype` int(11) NOT NULL,
  `items` text NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `discount_type` int(2) NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `note` text DEFAULT NULL,
  `tc` varchar(255) NOT NULL,
  `want_payment` int(1) NOT NULL,
  `want_signature` int(1) NOT NULL,
  `other` text NOT NULL,
  `repeat_every` varchar(10) NOT NULL,
  `inv_status` tinyint(1) NOT NULL,
  `inv_date` date NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_recurring_log`
--

DROP TABLE IF EXISTS `cm_recurring_log`;
CREATE TABLE IF NOT EXISTS `cm_recurring_log` (
  `id` int(50) NOT NULL,
  `recurring_type` varchar(50) NOT NULL,
  `logs` text NOT NULL,
  `other` text NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_salarytemplate`
--

DROP TABLE IF EXISTS `cm_salarytemplate`;
CREATE TABLE IF NOT EXISTS `cm_salarytemplate` (
  `id` int(11) NOT NULL,
  `grade` varchar(128) NOT NULL,
  `currency` int(11) NOT NULL,
  `basic_salary` varchar(20) NOT NULL,
  `allowance` text NOT NULL,
  `deduction` text NOT NULL,
  `gross_salary` varchar(20) NOT NULL,
  `total_allowance` varchar(20) NOT NULL,
  `total_deduction` varchar(20) NOT NULL,
  `net_salary` varchar(20) NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cm_setting`
--

DROP TABLE IF EXISTS `cm_setting`;
CREATE TABLE IF NOT EXISTS `cm_setting` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `data` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cm_setting`
--

INSERT INTO `cm_setting` (`id`, `name`, `data`, `status`) VALUES
(1, 'siteinfo', '{\"logo\":\"media-12063910725e4801f50a709.png\",\"name\":\"Client Manager\",\"legal_name\":\"Client Manager LLC\",\"email\":\"support@pepdev.com\",\"phone\":\"1234567890\",\"fax\":\"2134567\",\"language\":\"en-US\",\"timezone\":\"Asia\\/Calcutta\",\"date_format\":\"d-m-Y\",\"date_my_format\":\"m-Y\",\"address\":{\"address1\":\"Address Line 11\",\"address2\":\"Address Line 2\",\"city\":\"City\",\"country\":\"Country\",\"pincode\":\"411048\"},\"invoice\":{\"template\":\"5\",\"prefix\":\"INV-\",\"accountnumber\":\"12345678901\",\"accountname\":\"John Doe\",\"bankname\":\"Bank Name\",\"bankdetails\":\"Bank City, Country\",\"customernote\":\"\",\"tc\":\"\",\"signature_label\":\"John Doe (CEO)\"},\"quote\":{\"prefix\":\"QTN-\"},\"signature\":\"\"}', 1),
(2, 'admintheme', '{\"logo\":\"media-12063910725e4801f50a709.png\",\"logo_icon\":\"media-15313023855e4801f4d9000.png\",\"favicon\":\"media-11522721505e4801f4d734d.png\",\"layout\":\"\",\"layout_fixed\":\"menu-fixed page-hdr-fixed\",\"side_menu\":\"dark\",\"header_color\":\"bg-white\",\"submit\":\"\",\"layout_menu\":false,\"sidemenu\":false}', 1),
(3, 'emailsetting', '{\"status\":\"1\",\"fromemail\":\"support@pepdev.com\",\"fromname\":\"Client Manager\",\"reply\":\"support@pepdev.com\",\"host\":\"\",\"port\":\"\",\"username\":\"\",\"password\":\"\",\"encryption\":\"ssl\",\"authentication\":\"0\"}', 1),
(4, 'cronsetting', '851932206', 1),
(6, 'paypalgateway', '{\"username\":\"\",\"password\":\"\",\"signature\":\"\",\"email\":\"\",\"mode\":\"0\",\"status\":\"1\"}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cm_staff_attendance`
--

DROP TABLE IF EXISTS `cm_staff_attendance`;
CREATE TABLE IF NOT EXISTS `cm_staff_attendance` (
  `id` int(200) UNSIGNED NOT NULL,
  `staff_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `monthyear` varchar(10) NOT NULL,
  `a1` varchar(3) DEFAULT NULL,
  `a2` varchar(3) DEFAULT NULL,
  `a3` varchar(3) DEFAULT NULL,
  `a4` varchar(3) DEFAULT NULL,
  `a5` varchar(3) DEFAULT NULL,
  `a6` varchar(3) DEFAULT NULL,
  `a7` varchar(3) DEFAULT NULL,
  `a8` varchar(3) DEFAULT NULL,
  `a9` varchar(3) DEFAULT NULL,
  `a10` varchar(3) DEFAULT NULL,
  `a11` varchar(3) DEFAULT NULL,
  `a12` varchar(3) DEFAULT NULL,
  `a13` varchar(3) DEFAULT NULL,
  `a14` varchar(3) DEFAULT NULL,
  `a15` varchar(3) DEFAULT NULL,
  `a16` varchar(3) DEFAULT NULL,
  `a17` varchar(3) DEFAULT NULL,
  `a18` varchar(3) DEFAULT NULL,
  `a19` varchar(3) DEFAULT NULL,
  `a20` varchar(3) DEFAULT NULL,
  `a21` varchar(3) DEFAULT NULL,
  `a22` varchar(3) DEFAULT NULL,
  `a23` varchar(3) DEFAULT NULL,
  `a24` varchar(3) DEFAULT NULL,
  `a25` varchar(3) DEFAULT NULL,
  `a26` varchar(3) DEFAULT NULL,
  `a27` varchar(3) DEFAULT NULL,
  `a28` varchar(3) DEFAULT NULL,
  `a29` varchar(3) DEFAULT NULL,
  `a30` varchar(3) DEFAULT NULL,
  `a31` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_staff_payment`
--

DROP TABLE IF EXISTS `cm_staff_payment`;
CREATE TABLE IF NOT EXISTS `cm_staff_payment` (
  `id` int(11) NOT NULL,
  `month_year` varchar(20) NOT NULL,
  `month` int(2) NOT NULL,
  `currency` int(11) NOT NULL,
  `gross_salary` varchar(20) NOT NULL,
  `total_deduction` varchar(20) NOT NULL,
  `net_salary` varchar(20) NOT NULL,
  `method` int(11) NOT NULL,
  `advance` varchar(20) NOT NULL,
  `deduction` varchar(20) NOT NULL,
  `amount` varchar(20) NOT NULL,
  `comments` text DEFAULT NULL,
  `salarytemplate` text NOT NULL,
  `salarytemplate_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_subscribe`
--

DROP TABLE IF EXISTS `cm_subscribe`;
CREATE TABLE IF NOT EXISTS `cm_subscribe` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_taxes`
--

DROP TABLE IF EXISTS `cm_taxes`;
CREATE TABLE IF NOT EXISTS `cm_taxes` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `rate` float NOT NULL,
  `description` varchar(255) NOT NULL,
  `other` text NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_tickets`
--

DROP TABLE IF EXISTS `cm_tickets`;
CREATE TABLE IF NOT EXISTS `cm_tickets` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `department` varchar(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `priority` varchar(10) NOT NULL,
  `reply_status` tinyint(1) NOT NULL,
  `remark` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `customer` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `last_updated` datetime NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_tickets_message`
--

DROP TABLE IF EXISTS `cm_tickets_message`;
CREATE TABLE IF NOT EXISTS `cm_tickets_message` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `attached` text NOT NULL,
  `message_by` tinyint(1) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_joining` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `cm_users`
--

DROP TABLE IF EXISTS `cm_users`;
CREATE TABLE IF NOT EXISTS `cm_users` (
  `user_id` int(5) NOT NULL,
  `user_role` int(4) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `meta` text DEFAULT NULL,
  `other` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `temp_hash` varchar(225) NOT NULL,
  `emailconfirmed` bit(1) DEFAULT NULL,
  `salarytemplate_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_of_joining` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cm_users`
--

INSERT INTO `cm_users` (`user_id`, `user_role`, `user_name`, `firstname`, `lastname`, `email`, `mobile`, `meta`, `other`, `password`, `temp_hash`, `emailconfirmed`, `salarytemplate_id`, `status`, `date_of_joining`) VALUES
(1, 1, 'admin', 'Client', 'Manager', 'support@pepdev.com', '123456789', '{\"dob\":\"\",\"address1\":\"\",\"address2\":\"\",\"city\":\"\",\"country\":\"\",\"pin\":\"\"}', NULL, '$2y$10$9AdTEHAJODoONsTAsgsZWeRnSCPavPsWJ6f8ifUQ6p1w7zHAAj9/q', '', b'1', 1, 1, '2018-02-15 21:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `cm_user_role`
--

DROP TABLE IF EXISTS `cm_user_role`;
CREATE TABLE IF NOT EXISTS `cm_user_role` (
  `id` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `permission` text NOT NULL,
  `date_of_joining` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cm_user_role`
--

INSERT INTO `cm_user_role` (`id`, `name`, `description`, `permission`, `date_of_joining`) VALUES
(1, 'Admin', 'You can not change Admin role setting', '[\"contacts\",\"contact\\/add\",\"contact\\/edit\",\"contact\\/delete\",\"projects\",\"project\\/add\",\"project\\/edit\",\"project\\/delete\",\"quotes\",\"quote\\/add\",\"quote\\/edit\",\"quote\\/delete\",\"invoices\",\"invoice\\/add\",\"invoice\\/edit\",\"invoice\\/delete\",\"expenses\",\"expense\\/add\",\"expense\\/edit\",\"expense\\/delete\",\"users\",\"user\\/add\",\"user\\/edit\",\"user\\/delete\",\"subscriber\",\"subscriber\\/add\",\"subscriber\\/edit\",\"subscriber\\/delete\"]', '2018-01-11 11:45:47'),
(2, 'Sales Manager', 'Sales Manager', '[\"contacts\",\"contact\\/add\",\"contact\\/edit\",\"contact\\/delete\",\"contact\\/view\",\"leads\",\"lead\\/add\",\"lead\\/edit\",\"lead\\/delete\",\"projects\",\"project\\/add\",\"project\\/edit\",\"quotes\",\"quote\\/add\",\"quote\\/edit\",\"quote\\/view\",\"invoices\",\"invoice\\/add\",\"invoice\\/edit\",\"invoice\\/delete\",\"invoice\\/view\",\"invoice\\/copy\",\"invoice\\/pdf\",\"invoice\\/sendmail\",\"recurring\",\"recurring\\/add\",\"recurring\\/edit\",\"recurring\\/view\",\"ticket\\/add\",\"ticket\\/edit\",\"domain\\/add\",\"domain\\/edit\",\"expense\\/add\",\"expense\\/edit\",\"calendar\\/add\",\"calendar\\/edit\",\"calendar\\/delete\",\"note\\/add\",\"note\\/edit\",\"note\\/delete\",\"user\\/add\",\"user\\/edit\",\"subscriber\\/add\",\"subscriber\\/edit\",\"cronsetting\",\"cronlog\",\"emaillog\",\"tax\\/add\",\"tax\\/edit\",\"tax\\/delete\",\"currency\\/add\",\"currency\\/edit\",\"currency\\/delete\",\"paymentmethod\\/add\",\"paymentmethod\\/edit\",\"paymentmethod\\/delete\",\"department\\/add\",\"department\\/edit\",\"department\\/delete\",\"expensetype\\/add\",\"expensetype\\/edit\",\"expensetype\\/delete\",\"item\\/add\",\"get\\/media\",\"media\\/upload\",\"media\\/delete\"]', '2018-05-19 05:50:49'),
(3, 'Team Manager', 'Team Manager', '[\"contacts\",\"leads\",\"lead\\/add\",\"lead\\/edit\",\"lead\\/delete\",\"lead\\/convert\",\"projects\",\"project\\/add\",\"project\\/edit\",\"project\\/delete\",\"project\\/view\",\"project\\/documents\",\"project\\/comment\",\"quotes\",\"quote\\/pdf\",\"invoices\",\"invoice\\/copy\",\"recurring\",\"recurring\\/pdf\",\"calendar\",\"calendar\\/add\",\"calendar\\/edit\",\"calendar\\/delete\",\"notes\",\"note\\/add\",\"note\\/edit\",\"note\\/delete\"]', '2020-05-20 09:00:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cm_accounts`
--
ALTER TABLE `cm_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_account_transaction`
--
ALTER TABLE `cm_account_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_attached_files`
--
ALTER TABLE `cm_attached_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_clients`
--
ALTER TABLE `cm_clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cm_client_activity`
--
ALTER TABLE `cm_client_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_comments`
--
ALTER TABLE `cm_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_contacts`
--
ALTER TABLE `cm_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_currency`
--
ALTER TABLE `cm_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_departments`
--
ALTER TABLE `cm_departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_domains`
--
ALTER TABLE `cm_domains`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_email_logs`
--
ALTER TABLE `cm_email_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_email_template`
--
ALTER TABLE `cm_email_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_event`
--
ALTER TABLE `cm_event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_expenses`
--
ALTER TABLE `cm_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_expense_type`
--
ALTER TABLE `cm_expense_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_invoice`
--
ALTER TABLE `cm_invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_items`
--
ALTER TABLE `cm_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_leads`
--
ALTER TABLE `cm_leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_login_attempts`
--
ALTER TABLE `cm_login_attempts`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cm_media`
--
ALTER TABLE `cm_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_notes`
--
ALTER TABLE `cm_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_noticeboard`
--
ALTER TABLE `cm_noticeboard`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_payments`
--
ALTER TABLE `cm_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_payment_method`
--
ALTER TABLE `cm_payment_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_projects`
--
ALTER TABLE `cm_projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_proposal`
--
ALTER TABLE `cm_proposal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_recurring_invoice`
--
ALTER TABLE `cm_recurring_invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_recurring_log`
--
ALTER TABLE `cm_recurring_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_salarytemplate`
--
ALTER TABLE `cm_salarytemplate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_setting`
--
ALTER TABLE `cm_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_staff_attendance`
--
ALTER TABLE `cm_staff_attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_staff_payment`
--
ALTER TABLE `cm_staff_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_subscribe`
--
ALTER TABLE `cm_subscribe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_taxes`
--
ALTER TABLE `cm_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_tickets`
--
ALTER TABLE `cm_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_tickets_message`
--
ALTER TABLE `cm_tickets_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cm_users`
--
ALTER TABLE `cm_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- Indexes for table `cm_user_role`
--
ALTER TABLE `cm_user_role`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cm_accounts`
--
ALTER TABLE `cm_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_account_transaction`
--
ALTER TABLE `cm_account_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_attached_files`
--
ALTER TABLE `cm_attached_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_clients`
--
ALTER TABLE `cm_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

  
--
-- AUTO_INCREMENT for table `cm_client_activity`
--
ALTER TABLE `cm_client_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_comments`
--
ALTER TABLE `cm_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_contacts`
--
ALTER TABLE `cm_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_currency`
--
ALTER TABLE `cm_currency`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_departments`
--
ALTER TABLE `cm_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_domains`
--
ALTER TABLE `cm_domains`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_email_logs`
--
ALTER TABLE `cm_email_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_email_template`
--
ALTER TABLE `cm_email_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `cm_event`
--
ALTER TABLE `cm_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_expenses`
--
ALTER TABLE `cm_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_expense_type`
--
ALTER TABLE `cm_expense_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_invoice`
--
ALTER TABLE `cm_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_items`
--
ALTER TABLE `cm_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_leads`
--
ALTER TABLE `cm_leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_login_attempts`
--
ALTER TABLE `cm_login_attempts`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_media`
--
ALTER TABLE `cm_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cm_notes`
--
ALTER TABLE `cm_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_noticeboard`
--
ALTER TABLE `cm_noticeboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_payments`
--
ALTER TABLE `cm_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_payment_method`
--
ALTER TABLE `cm_payment_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_projects`
--
ALTER TABLE `cm_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_proposal`
--
ALTER TABLE `cm_proposal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_recurring_invoice`
--
ALTER TABLE `cm_recurring_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_recurring_log`
--
ALTER TABLE `cm_recurring_log`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_salarytemplate`
--
ALTER TABLE `cm_salarytemplate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_setting`
--
ALTER TABLE `cm_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_staff_attendance`
--
ALTER TABLE `cm_staff_attendance`
  MODIFY `id` int(200) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_staff_payment`
--
ALTER TABLE `cm_staff_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_subscribe`
--
ALTER TABLE `cm_subscribe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_taxes`
--
ALTER TABLE `cm_taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_tickets`
--
ALTER TABLE `cm_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_tickets_message`
--
ALTER TABLE `cm_tickets_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `cm_users`
--
ALTER TABLE `cm_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cm_user_role`
--
ALTER TABLE `cm_user_role`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

