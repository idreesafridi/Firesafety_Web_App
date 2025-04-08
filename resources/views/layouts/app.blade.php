<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ config('app.name', 'UFP') }}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        ::placeholder {
          color: black !important;
          opacity: 1; /* Firefox */
        }

        :-ms-input-placeholder { /* Internet Explorer 10-11 */
         color: black;
        }

        ::-ms-input-placeholder { /* Microsoft Edge */
         color: black;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }

        /* Firefox */
        input[type=number] {
          -moz-appearance: textfield;
        }

        body {font-family: Arial, Helvetica, sans-serif;}

        /* The Modal (background) */
        .modal {
          display: none; /* Hidden by default */
          position: fixed; /* Stay in place */
          z-index: 1; /* Sit on top */
          padding-top: 100px; /* Location of the box */
          left: 0;
          top: 0;
          width: 100%; /* Full width */
          height: 100%; /* Full height */
          overflow: auto; /* Enable scroll if needed */
          background-color: rgb(0,0,0); /* Fallback color */
          background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
          background-color: #fefefe;
          margin: auto;
          padding: 20px;
          border: 1px solid #888;
          width: 80%;
        }

        /* The Close Button */
        .close {
          color: #aaaaaa;
          float: right;
          font-size: 28px;
          font-weight: bold;
        }

        .close:hover,
        .close:focus {
          color: #000;
          text-decoration: none;
          cursor: pointer;
        }
        .select2-container {
            width:250px!important;
        }
        /*.cell {*/
        /*  vertical-align:top;*/
        /*  display: inline-block;*/
        /*  width: 150px;*/
        /*  border-right:1px solid black;*/
        /*  display: table-cell;*/
        /*  overflow:hidden;*/
        /*}*/
        .color-green{
            color: green;
        }
        .color-red {
            color: red;
        }
        .hidden
        {
            display: none;
        }
    </style>

    <!-- Main Styles -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/jquery-ui/jquery-ui.structure.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/jquery-ui/jquery-ui.theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/styles/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/styles/custom.css') }}">

    <!-- mCustomScrollbar -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/mCustomScrollbar/jquery.mCustomScrollbar.min.css') }}">

    <!-- Waves Effect -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/waves/waves.min.css') }}">

    <!-- Sweet Alert -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/sweet-alert/sweetalert.css') }}">
    
    <!-- FlexDatalist -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/flexdatalist/jquery.flexdatalist.min.css') }}">

    <!-- Popover -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/popover/jquery.popSelect.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/select2/css/select2.min.css') }}">

    <!-- Timepicker -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/timepicker/bootstrap-timepicker.min.css') }}">

    <!-- Touch Spin -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/touchspin/jquery.bootstrap-touchspin.min.css') }}">

    <!-- Colorpicker -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/colorpicker/css/bootstrap-colorpicker.min.css') }}">

    <!-- Datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/datepicker/css/bootstrap-datepicker.min.css') }}">

    <!-- DateRangepicker -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/daterangepicker/daterangepicker.css') }}">

    <!-- Color Picker -->
    <link rel="stylesheet" href="{{ asset('assets/color-switcher/color-switcher.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/form-wizard/prettify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/dropify/css/dropify.min.css') }}">
    
    <!-- TinyMCE -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/tinymce/skins/lightgray/skin.min.css') }}">
    <!-- Must include this script FIRST -->
    <script src="{{ asset('assets/plugin/tinymce/tinymce.min.js') }}"></script>
</head>

<body>
    <div class="main-menu">
        <header class="header">
            <a href="{{ url('/') }}" class="logo"><img src="{{ asset('assets/images/logo/logo-1.png') }}"></a>
            <button type="button" class="button-close fa fa-times js__menu_close"></button>
            <div class="user">
                <a href="{{ url('/') }}" class="avatar"><img src="{{ (Auth::User()->avatar != '')  ? '/avatar/'.Auth::User()->avatar : asset('assets/images/avatar.png') }}" alt="" style="width: 65px;height: 60px;"><span class="status online"></span></a>
                <h5 class="name"><a href="{{ route('Profile') }}"> {{ Auth::User()->username }} </a></h5>
                <h5 class="position"> {{ Auth::User()->designation }}</h5>
                <!-- /.name -->
                <div class="control-wrap js__drop_down">
                    <i class="fa fa-caret-down js__drop_down_button"></i>
                    <div class="control-list">
                    <div class="control-item"><a href="{{ route('Profile') }}"><i class="fa fa-user"></i>Update Profile</a></div>
                    <div class="control-item"><a href="{{ route('changePassword') }}"><i class="fa fa-user"></i>Update Password</a></div>

                    <div class="control-item">

                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out"></i> {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                    </div>
                </div>
                </div>
            </div>
        </header>
        <div class="content">
            <div class="navigation">
                <h5 class="title">Navigation</h5>
                <ul class="menu js__accordion">
                    <?php $myRights = explode(', ', Auth::User()->rights); ?>
                        <li>
                            <a class="waves-effect" href="{{ url('/') }}?year=2023&month=1"><i class="menu-icon fa fa-home"></i><span>Home</span></a>
                        </li>
                        
                        @if(in_array('Branch', $myRights)) <!-- Auth::User()->designation == 'Super Admin' OR Auth::User()->designation == 'Branch Admin' OR  -->
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-building"></i><span>Branch</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('Branch.create') }}">Add Branch</a></li>
                                <li><a href="{{ route('Branch.index') }}">All Branch</a></li>
                            </ul>
                        </li>
                        @endif
                        @if(in_array('User', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-user"></i><span>Users</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('User.create') }}">Add User</a></li>
                                <li><a href="{{ route('User.index') }}">All Users</a></li>
                            </ul>
                        </li>
                        @endif
                        @if(in_array('Employee', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-user"></i><span>Employees</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('Employees.create') }}">Add Employee</a></li>
                                <li><a href="{{ route('Employees.index') }}">All Employees</a></li>
                            </ul>
                        </li>
                        @endif
                        @if(in_array('Supplier', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-users"></i><span>Supplier</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('Supplier.create') }}">Add Supplier</a></li>
                                <li><a href="{{ route('Supplier.index') }}">All Suppliers</a></li>
                            </ul>
                        </li>
                        @endif
                        @if(in_array('Category', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-bars"></i><span>Category</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('Category.create') }}">Add Category</a></li>
                                <li><a href="{{ route('Category.index') }}">All Categories</a></li>
                            </ul>
                        </li>
                        @endif
                        @if(in_array('Product', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-box"></i><span>Products</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('Products.create') }}">Add Product</a></li>
                                <li><a href="{{ route('Products.index') }}">All Products</a></li>
                            </ul>
                        </li>
                        @endif
                        @if(in_array('Customer', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-warehouse"></i><span>Customer Management</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('Customer.create') }}">Add Customer</a></li>
                                <li><a href="{{ route('Customer.index') }}">All Customers</a></li>
                            </ul>
                        </li>
                        @endif
                        
                        @if(in_array('Quotes', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-dollar-sign"></i><span>Quotes</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('Quotes.create') }}">Generate Quote</a></li>
                                <li><a href="{{ route('Quotes.index') }}">All Quotes</a></li>

                                <li><a href="{{ route('create-qoute') }}">Upload Quote</a></li>
                            </ul>
                        </li>
                        @endif
                        
                        @if(in_array('Invoice', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-book"></i><span>Invoice</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('Invoice.create') }}">Generate Invoice</a></li>
                                <li><a href="{{ route('Invoice.index') }}">All Invoices</a></li>
                            </ul>
                        </li>
                        @endif

                        
                        @if(in_array('Invoice', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-box"></i><span>Inventory</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="">Generate Inventory</a></li>
                                <li><a href="">All Inventory</a></li>
                            </ul>
                        </li>
                        @endif

                        @if(in_array('Invoice', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-envelope"></i></i><span>Envelop</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="">Generate Envelop</a></li>
                                <li><a href="">All Envelop</a></li>
                            </ul>
                        </li>
                        @endif

                        @if(in_array('Challan', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-file"></i><span>Incoming Challan</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('IncomingChallan.create') }}">Generate Challan</a></li>
                                <li><a href="{{ route('IncomingChallan.index') }}">All Challans</a></li>
                            </ul>
                        </li>
                        
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-file"></i><span>Delivery Challan</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('DeliveryChallan.create') }}">Generate Challan</a></li>
                                <li><a href="{{ route('DeliveryChallan.index') }}">All Challans</a></li>
                            </ul>
                        </li>
                        @endif
                        
                        @if(in_array('CashMemo', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-file"></i><span>Cash Memo</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('CashMemo.create') }}">Generate Cash Memo</a></li>
                                <li><a href="{{ route('CashMemo.index') }}">All Cash Memo</a></li>
                            </ul>
                        </li>
                        @endif

                        @if(in_array('GeneralTemplate', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-file"></i><span>General Template</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('GeneralTemplate.create') }}">Generate General Template</a></li>
                                <li><a href="{{ route('GeneralTemplate.index') }}">All General Template</a></li>
                            </ul>
                        </li>

                        <!--<li>-->
                        <!--    <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-file"></i><span>Email General Template</span><span class="menu-arrow fa fa-angle-down"></span></a>-->
                        <!--    <ul class="sub-menu js__content">-->
                        <!--        <li><a href="{{ route('EmailGeneralTemplate.create') }}">Generate Email General Template</a></li>-->
                        <!--        <li><a href="{{ route('EmailGeneralTemplate.index') }}">All Email General Template</a></li>-->
                        <!--    </ul>-->
                        <!--</li>-->

                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-file"></i><span>Support Quotations</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('SafetyCare.index') }}">Safety Care</a></li>
                                <li><a href="{{ route('ViqasEnterprise.index') }}">Viqas Enterprise</a></li>
                            </ul>
                        </li>
                        @endif
                       
                        @if(in_array('Expense', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon far fa-money-bill-alt"></i><span>Expenses</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('Expenses.create') }}">Add Expense</a></li>
                                <li><a href="{{ route('Expenses.index') }}">All Expenses</a></li>
                            </ul>
                        </li>

                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon far fa-money-bill-alt"></i><span>Expense Category</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('ExpenseCategory.create') }}">Add Expense Category</a></li>
                                <li><a href="{{ route('ExpenseCategory.index') }}">All Expense Category</a></li>
                            </ul>
                        </li>    
                        @endif

                        @if(in_array('Reports', $myRights))
                        <li>
                            <a class="waves-effect" href="{{ route('salesReport') }}">
                                <i class="menu-icon fa fa-list"></i><span>Sales Report</span></a>
                        </li>
                        @endif

                        @if(in_array('Payroll', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon far fa-list-alt"></i><span>Payroll</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('Salary.create') }}">Generate Salary Slip</a></li>
                                <li><a href="{{ route('Salary.index') }}">All Salaries</a></li>
                            </ul>
                        </li>
                        @endif
                        
                        @if(in_array('Reports', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-file-image"></i><span>Reports</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <!-- <li><a href="{{ route('salesReport') }}">Sales report</a></li> -->
                                <li><a href="{{ route('quotationReport') }}">Quotation report</a></li>
                                <li><a href="{{ route('supplierReport') }}">Supplier report</a></li>
                                <li><a href="{{ route('customerReport') }}">Customer report</a></li>
                                <li><a href="{{ route('expenseReport') }}">Expense report</a></li>
                                <li><a href="{{ route('expiryReport') }}">Expiry Record report</a></li>
                            </ul>
                        </li>
                        @endif

                        @if(in_array('TermsCondition', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-file-image"></i><span>Terms & Condition</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('TermsAndCondition.create') }}">New Terms & Condition</a></li>
                                <li><a href="{{ route('TermsAndCondition.index') }}">All Terms & Condition</a></li>
                            </ul>
                        </li>
                        @endif

                        @if(in_array('Accounts', $myRights))
                        <li>
                            <a class="waves-effect parent-item js__control" href="#"><i class="menu-icon fa fa-file-image"></i><span>Accounts</span><span class="menu-arrow fa fa-angle-down"></span></a>
                            <ul class="sub-menu js__content">
                                <li><a href="{{ route('Accounts.create') }}">Recieve Payment</a></li>
                                <li><a href="{{ route('Accounts.index') }}">All Payments</a></li>
                            </ul>
                        </li>
                        @endif

                        <!--@if(Auth::User()->designation == 'Super Admin' OR Auth::User()->designation == 'Branch Admin' OR in_array('Invoice', $myRights))-->
                        <!--<li>-->
                        <!--    <a class="waves-effect" href="{{ route('Invoice.create') }}"><i class="menu-icon fa fa-dollar-sign"></i> <span>Generate Invoice</span></a>-->
                        <!--</li>-->
                        <!--<li>-->
                        <!--    <a class="waves-effect" href="{{ url('/') }}"><i class="menu-icon fa fa-file-image"></i><span>All Invoices</span></a>-->
                        <!--</li>-->
                        <!--@endif-->

                        @if(in_array('BackupDatabase', $myRights))
                        <li>
                            <a class="waves-effect" href="{{ url('/backup')}}" target="_blank">
                                <i class="menu-icon fa fa-database"></i><span>Backup Database</span>
                            </a>
                        </li>
                        @endif
                </ul>
            </div>
        </div>
    </div>

    @yield('content')


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
    <script src="assets/script/html5shiv.min.js"></script>
    <script src="assets/script/respond.min.js"></script>
<![endif]-->
<!-- 
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
    <script src="{{ asset('assets/scripts/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/modernizr.min.js') }}"></script>
    <script src="../../../../cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/plugin/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/nprogress/nprogress.js') }}"></script>
    <script src="{{ asset('assets/plugin/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/waves/waves.min.js') }}"></script>
    <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
          modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
          modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        }
    </script>
    <!-- Full Screen Plugin -->
    <script src="{{ asset('assets/plugin/fullscreen/jquery.fullscreen-min.js') }}"></script>
<!-- Form Wizard -->
    <script src="{{ asset('assets/plugin/form-wizard/prettify.js') }}"></script>
    <script src="{{ asset('assets/plugin/form-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/form.wizard.init.min.js') }}"></script>
    <!-- Flex Datalist -->
    <script src="{{ asset('assets/plugin/flexdatalist/jquery.flexdatalist.min.js') }}"></script>

    <!-- Popover -->
    <script src="{{ asset('assets/plugin/popover/jquery.popSelect.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('assets/plugin/select2/js/select2.min.js') }}"></script>

    <!-- Multi Select -->
    <script src="{{ asset('assets/plugin/multiselect/multiselect.min.js') }}"></script>

    <!-- Touch Spin -->
    <script src="{{ asset('assets/plugin/touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/jquery-ui/jquery.ui.touch-punch.min.js') }}"></script>
    <!-- Timepicker -->
    <script src="{{ asset('assets/plugin/timepicker/bootstrap-timepicker.min.js') }}"></script>

    <!-- Colorpicker -->
    <script src="{{ asset('assets/plugin/colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>

    <!-- Datepicker -->
    <script src="{{ asset('assets/plugin/datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    <!-- Moment -->
    <script src="{{ asset('assets/plugin/moment/moment.js') }}"></script>

    <!-- DateRangepicker -->
    <script src="{{ asset('assets/plugin/daterangepicker/daterangepicker.js') }}"></script>

    <!-- Maxlength -->
    <script src="{{ asset('assets/plugin/maxlength/bootstrap-maxlength.min.js') }}"></script>

    <!-- Demo Scripts -->
    <script src="{{ asset('assets/scripts/form.demo.min.js') }}"></script>

    <script src="{{ asset('assets/scripts/main.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/mycommon.js') }}"></script>
    <script src="{{ asset('assets/color-switcher/color-switcher.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/fileUpload.demo.min.js') }}"></script>
    
    
    <!-- TinyMCE -->
    <!-- Plugin Files DON'T INCLUDES THESES FILES IF YOU USE IN THE HOST -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/tinymce/skins/lightgray/skin.min.css') }}">
    <script src="{{ asset('assets/plugin/tinymce/plugins/advlist/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/anchor/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/autolink/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/autoresize/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/autosave/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/bbcode/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/charmap/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/code/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/codesample/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/colorpicker/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/contextmenu/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/directionality/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/emoticons/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/example/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/example_dependency/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/fullpage/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/fullscreen/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/hr/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/image/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/imagetools/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/importcss/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/insertdatetime/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/layer/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/legacyoutput/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/link/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/lists/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/media/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/nonbreaking/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/noneditable/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/pagebreak/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/paste/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/preview/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/print/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/save/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/searchreplace/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/spellchecker/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/tabfocus/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/table/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/template/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/textcolor/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/textpattern/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/visualblocks/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/visualchars/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/plugins/wordcount/plugin.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/tinymce/themes/modern/theme.min.js') }}"></script>
    <!-- Plugin Files DON'T INCLUDES THESES FILES IF YOU USE IN THE HOST -->
    <script src="{{ asset('assets/scripts/tinymce.init.min.js') }}"></script>
    
    <!-- Data Tables -->
    <script src="{{ asset('assets/plugin/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/datatables/media/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/datatables/extensions/Responsive/js/responsive.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/datatables.demo.min.js') }}"></script>

    <script type="text/javascript">
        $('#example2').dataTable( {
            "order": [[ 0, "desc" ]],
        } );
        
        $('#exampleQuote').dataTable( {
            "order": [[ 1, "desc" ]],
        } );
    </script>

    <script type="text/javascript">
        // tinymce.init({
        //   selector: '.description',
        // });
    </script>


    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script>
        @if(Session::has('message'))
            var type="{{Session::get('alert-type','info')}}"
            switch(type){
                case 'info':
                     toastr.info("{{ Session::get('message') }}");
                     break;
                case 'success':
                    toastr.success("{{ Session::get('message') }}");
                    break;
                case 'warning':
                    toastr.warning("{{ Session::get('message') }}");
                    break;
                case 'error':
                    toastr.error("{{ Session::get('message') }}");
                    break;
            }
        @endif
        </script>

<script>
    $(document).on("click", ".passingID", function () {
     var ids = $(this).data('id');
     $(".modal-body #invoice_id").val( ids );
    });
  </script>
  
  
<script>
// Ajax
$.ajaxSetup({
 headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 }
});

// var sel = $(this).parent('td').find('select');

// load capacity by product id
$(document).on('change', '#customerTable .productTD select', function () {
    var productCapacity = $(this).closest('tr').find('.productCapacity');
    var productID = this.value;
    $.ajax({
        url:"/checkForCapcityAjax",
        method:"POST",
        data:{productID:productID},
        success:function(data){
            // replace question
            console.log(data);
            productCapacity.html(data);
        }
    });

    var description = $(this).closest('tr').next('tr').find('.description');
    var trid = $(this).closest('tr').next('tr').data('trid');
    console.log( trid + ' tridd ' );
    var available_inventory = $(this).closest('tr').find('.available_inventory');
    $.ajax({
        url:"/loadDescription",
        method:"POST",
        data:{productID:productID},
        success:function(data){
            // replace question
            console.log(data);
            description.val(data.description);
            tinyMCE.get('tmce_'+trid).setContent(data.description);
            available_inventory.val(data.inventory); // for invoice only
            // tinymce.init({
            //   selector: '.description',
            // });
        }
    });
});

// for invoice investory
$(document).on('keyup', '#customerTable .qty', function () {
    var qty = parseInt($(this).val());
    var inventory = parseInt($(this).closest('td').find('.available_inventory').val());
    var product_id = $(this).closest('tr').find('.product_id').val();
  
    if (qty > inventory) { // show alert
        var data = '<h4>Available qty ('+inventory+') is less than required qty ('+qty+')</h4>';
        data += '<form action="{{ route('update.inventory') }}" method="post" class="mt-3">';
        data += '@csrf';

        data += '<input type="hidden" name="product_id" value="'+product_id+'">';

        data += '<label>Enter available qty</label>';
        data += '<input type="number" class="form-control mb-3" name="inventory" min="1" required>';
        data += '<button class="btn btn-sm btn-primary" type="submit">Update Inventory</button>';
        data += '</form>';
        $(".modal-body").html(data);
        $('#investoryModal').modal('show'); 
    }
});

// load price by product and capacity
$(document).on('change', '.productCapacityTD select', function () {
    var price = $(this).closest('tr').find('.price');
    
    var productID      = $(this).closest('tr').find(':selected').val();
    var productCapacity = this.value;
    
    var customer_id = $("#customer_id").find(":selected").val();
    
    $.ajax({
        url:"/checkForPriceAjax",
        method:"POST",
        data:{productID:productID, productCapacity:productCapacity, customer_id:customer_id},
        success:function(data){
            // replace question
            console.log(data);
            price.val(data);
        }
    });
});



// check for cities by company name
$("#customer_company_name").change(function(){
    var customer_company_name = this.value;
    
    $("#customer_city").html('<option value="">Loading...</option>');
    
    $.ajax({
        url:"{{ url('/checkForCityAjax') }}",
        method:"POST",
        data:{customer_company_name:customer_company_name},
        success:function(data){
            // replace question
            console.log(data);
            $("#customer_city").html(data);
        }
    });
});
// check for address by city and comany name
$("#customer_city").change(function(){
    var customer_city           = this.value;
    var customer_company_name   = $("#customer_company_name").find(":selected").val();
    
    $("#customer_address").html('<option value="">Loading...</option>');
    
    $.ajax({
        url:"{{ url('/checkForAddressAjax') }}",
        method:"POST",
        data:{customer_city:customer_city, customer_company_name:customer_company_name},
        success:function(data){
            // replace question
            console.log(data);
            $("#customer_address").html(data);
        }
    });
});


// check for customer by company, city and address
$("#customer_address").change(function(){
    var customer_address        = this.value;
    var customer_city           = $("#customer_city").find(":selected").val();
    var customer_company_name   = $("#customer_company_name").find(":selected").val();
    
    $("#customer_id").html('<option value="">Loading...</option>');
    
    $.ajax({
        url:"{{ url('/checkForCustomerAjax') }}",
        method:"POST",
        data:{customer_address:customer_address, customer_city:customer_city, customer_company_name:customer_company_name},
        success:function(data){
            // replace question
            console.log(data);
            $("#customer_id").html(data);
        }
    });
});


$("#customer_id").change(function(){
    var customer_id        = this.value;
    console.log(customer_id);
    
    $.ajax({
        url:"{{ url('/checkForCustomerNTNAjax') }}",
        method:"POST",
        data:{customer_id:customer_id},
        success:function(data){
            // replace question
            console.log(data);
            $("#customer_ntn_no").val(data); 
        }
    });
});

$("#GST").on('change', function(){
    let tax_rate = $("#tax_rate").val();
    if(this.checked) {
        $("#tax_rate").val(18);
        $("#tax_rate").prop('required',true);
        
        $("#wh_tax").val(4);
        $("#wh_tax").prop('required',true);
    }else{
        $("#tax_rate").prop('required',false);
        $("#tax_rate").val('');
        
        $("#wh_tax").prop('required',false);
        $("#wh_tax").val('');
    }
});
</script>

<script type="text/javascript">
    $(document).on('click', '.view_hide', function () {
        $(this).closest('th').find('.description').toggle();
    });
    $(document).on('click', '.view_hide_new', function () {
        $(this).closest('th').find('.txtdescription_wrapper').toggle();
    });
</script>


</body>
</html>