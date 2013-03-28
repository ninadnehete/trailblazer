<?php /*%%SmartyHeaderCode:43005152921ea15cb2-80609588%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a4ae221a0afec453b3434759643ea9d9b45f2540' => 
    array (
      0 => 'C:\\xampp\\htdocs\\trailblazer\\application/views\\audit_trail\\trail_transactions.tpl',
      1 => 1364480833,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '43005152921ea15cb2-80609588',
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_51546c750cdb3',
  'has_nocache_code' => false,
  'cache_lifetime' => 1,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51546c750cdb3')) {function content_51546c750cdb3($_smarty_tpl) {?>  <!--
 * Trailblazer Digital Accounting Audit Trail Program
 * @author Kristian Jacob Abad Lora <kjalora92@yahoo.com>
 * @date-created October 31, 2012
-->
	<head>
		<title>Trailblazer - Trailing the Transactions</title>
	</head>
	
	<body>
		<div id="transDetailsModal">
		</div>
		<!-- Main -->
		<br>
		<div id="main-wrapper" style="width: 900px">
			<div id="navi" style="float: right; font-size: 14pt">
				<a href="#" onclick="history.go(-1);return false;"><i class="icon-arrow-left"></i></a>
				<span> | </span>
				<a href="http://localhost/trailblazer/audit_trail"><i class="icon-home"></i></a>
			</div>
			<div style="margin: 0 auto; text-align: center">
				<h2>Expense Transactions</h2>
				<br>
													<table id="table" class="table table-hover" style="text-align: left; color: black">
						<thead>
																								<th style="text-align: center">Date</th>
																	<th style="text-align: center">OR No</th>
																	<th style="text-align: center">Amount Due</th>
																	<th style="text-align: center">Name</th>
																	<th style="text-align: center">Address</th>
																	<th style="text-align: center">Contact</th>
																						<th>Audit Trail Results</th>
							<!--<th></th>-->
						</thead>
						<tbody>
																																<tr>
										<td style="text-align: center; vertical-align: center">2010-01-08</td>
										<td style="text-align: center; vertical-align: center"><a href="#trans-details4367332" data-toggle="modal" onClick="getDetails('or_no=4367332&amt=1380&name=Roble Shipping Lines&address=Smo. Rosario, Naval, Biliran&contact='); return false;">4367332</a></td>
										<td class="amount" style="text-align: right;">Php 1,380.00</td>
										<td style="text-align: center; vertical-align: center">Roble Shipping Lines</td>
										<td style="text-align: center; vertical-align: center">Smo. Rosario, Naval, Biliran</td>
										<td style="text-align: center; vertical-align: center"></td>
										<td style="text-align: center; vertical-align: center; width: 30px"><a href="http://localhost/trailblazer/audit_trail/summary?ref=ET-1-2010&or_no=4367332&acct=Freight-In&fs=Income Statement&fs_amt=1380.00&fs_file=is-1-2010.is&ledger=General Ledger&lg_ref=604&lg_desc=4367332&lg_debit=&lg_credit=1380&lg_total_amt=1380&journal=Cash Disbursements Journal&jl_ref=CDJ-1-2010&jl_desc=4367332&jl_amt=1380&trans=Expense Transactions&trans_amt=1380&trans_total_amt=1380">View</a></td>
										<!--<td style="text-align: center; vertical-align: center; width: 30px"><a>System Audit</a></td>-->
									</tr>
																													</tbody>
					</table>
								<div id="source-file">Source File: D:\Kristian Lora\My Documents\Lalaine's Bookstore\transaction_files\expense_transactions\et-1-2010.tf</div>
			</div>
		</div>
	
	<link href="http://localhost/trailblazer/assets/stylesheets/bootstrap.css" rel="stylesheet"></link>
	<link href="http://localhost/trailblazer/assets/stylesheets/bootstrap-responsive.css" rel="stylesheet"></link>
	<link href="http://localhost/trailblazer/assets/stylesheets/main.css" rel="stylesheet"></link>
	<script src="http://localhost/trailblazer/assets/scripts/jquery.js" type="text/javascript"></script>
	<script src="http://localhost/trailblazer/assets/scripts/bootstrap.js" type="text/javascript"></script>
	<script>
		function getDetails(url) {	
			url = url.replace(/\s/g,"%20");
			console.log(url);
			$('#transDetailsModal').load('load_trans_details/index?' + url);
			$('#trans-details').modal('show');
			$('body').append("<div id='backdrop' class='modal-backdrop fade in'></div>");
		}
		
		function closeIt() {
			$('#trans-details').remove();
			$('#backdrop').remove();
		}
		
		function textIt(name, contact_no, date, or_no, amt_due) {
			var ref = Math.floor(Math.random() * 9000) + 1000;
			var message = "Good day, " + name + "! This is Trailblazer - an automated accounting audit trail. We would just like you to confirm if you have made a transaction with Lalaine's Bookstore amounting to Php" + amt_due + " last " + date + " with OR No: " + or_no + ". Please reply TRAIL<space><Ref No><space><Yes or No> to this number. Your Ref No is " + ref + ". We will be waiting for your reply. Help us fight agaist fraud!";
			$.ajax({
				url: 'http://localhost:8011/send/sms/0' + contact_no + '/' + message + '/',
				type: "GET",
				dataType: "text",
				async: false,
				success: function() {
				},
				error: function(data, status, error){
					alert('Message sent!');
					//if (data.readyState == 4) 
					//	alert('Message sent!');
					//else if (data.readyState == 0) 
					//	alert('Message sending failed! Make sure that your GSM modem is properly inserted to your PC and/or that you have opened FrontlineSMS.');
				}
			});
			$.ajax({
				url: 'index/textPerson?ref=' + ref + '&name=' + name + '&contact=' + contact_no + '&date=' + date + '&or_no=' + or_no + '&amt=' + amt_due,
				type: "GET",
				async: false
			});
		}
	</script>
</html><?php }} ?>