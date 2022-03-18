@extends('layouts.base')
@section('content')
    
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="alert alert-light alert-elevate" role="alert">
        <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
        <div class="alert-text">
            This example is almost identical to text based individual column example and provides the same functionality.
            With server-side processing enabled, all paging, searching, ordering actions that DataTables performs are handed off to a server where an SQL engine (or similar) can perform these actions on the large data set.
            See official documentation <a class="kt-link kt-font-bold" href="https://datatables.net/examples/api/multi_filter_select.html" target="_blank">here</a>.
        </div>
    </div>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Advanced Search Form
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <div class="dropdown dropdown-inline">
                            <button type="button" class="btn btn-default btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="la la-download"></i> Export
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <ul class="kt-nav">
                                    <li class="kt-nav__section kt-nav__section--first">
                                        <span class="kt-nav__section-text">Choose an option</span>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon la la-print"></i>
                                            <span class="kt-nav__link-text">Print</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon la la-copy"></i>
                                            <span class="kt-nav__link-text">Copy</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon la la-file-excel-o"></i>
                                            <span class="kt-nav__link-text">Excel</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon la la-file-text-o"></i>
                                            <span class="kt-nav__link-text">CSV</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon la la-file-pdf-o"></i>
                                            <span class="kt-nav__link-text">PDF</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        &nbsp;
                        <a href="#" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Search Form -->
            <form class="kt-form kt-form--fit kt-margin-b-20">
                <div class="row kt-margin-b-20">
                    <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                        <label>RecordID:</label>
                        <input type="text" class="form-control kt-input" placeholder="E.g: 4590" data-col-index="0">
                    </div>
                    <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                        <label>OrderID:</label>
                        <input type="text" class="form-control kt-input" placeholder="E.g: 37000-300" data-col-index="1">
                    </div>
                    <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                        <label>Country:</label>
                        <select class="form-control kt-input" data-col-index="2">
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                        <label>Agent:</label>
                        <input type="text" class="form-control kt-input" placeholder="Agent ID or name" data-col-index="4">
                    </div>
                </div>
                <div class="row kt-margin-b-20">
                    <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                        <label>Ship Date:</label>
                        <div class="input-daterange input-group" id="kt_datepicker">
                            <input type="text" class="form-control kt-input" name="start" placeholder="From" data-col-index="5" />
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                            </div>
                            <input type="text" class="form-control kt-input" name="end" placeholder="To" data-col-index="5" />
                        </div>
                    </div>
                    <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                        <label>Status:</label>
                        <select class="form-control kt-input" data-col-index="6">
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                        <label>Type:</label>
                        <select class="form-control kt-input" data-col-index="7">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>
                <div class="kt-separator kt-separator--md kt-separator--dashed"></div>
                <div class="row">
                    <div class="col-lg-12">
                        <button class="btn btn-primary btn-brand--icon" id="kt_search">
                            <span>
                                <i class="la la-search"></i>
                                <span>Search</span>
                            </span>
                        </button>
                        &nbsp;&nbsp;
                        <button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                            <span>
                                <i class="la la-close"></i>
                                <span>Reset</span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>

            <!--begin: Datatable -->
            <div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>
            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead>
                    <tr>
                        <th>Record ID</th>
                        <th>Order ID</th>
                        <th>Country</th>
                        <th>Ship City</th>
                        <th>Company Agent</th>
                        <th>Ship Date</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Record ID</th>
                        <th>Order ID</th>
                        <th>Country</th>
                        <th>Ship City</th>
                        <th>Company Agent</th>
                        <th>Ship Date</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>

@endsection

@section('top-styles')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>


    {{-- <script src="assets/plugins/custom/datatables/datatables.bundle.js" type="text/javascript"></script> --}}


{{-- <script src="{{ asset('js/ssm_datatable.js') }}" type="text/javascript"></script> --}}


    <script src="{{ asset('assets/js/pages/crud/datatables/search-options/advanced-search.js') }}" type="text/javascript"></script>

@endsection





   