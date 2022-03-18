"use strict";
var KTDatatablesAdvancedColumnRendering = (function() {
    var initTable1 = function() {
        var table = $("#kt_table_1");
        // begin first table
        table.DataTable({
            responsive: true,
            paging: true
        });
    };
    var initYajraTable = function() {
        var table = $("#ComplaintYajraTable");
    };
    return {
        //main function to initiate the module
        init: function() {
            initTable1();
            initYajraTable();
        }
    };

    var initTable2 = function() {
        var table2 = $(".kt_table_2");
        // begin first table
        table2.DataTable({
            responsive: true,
            paging: true
        });
    };
    return {
        //main function to initiate the module
        init: function() {
            initTable2();
        }
    };
})();
jQuery(document).ready(function() {
    KTDatatablesAdvancedColumnRendering.init();
});
