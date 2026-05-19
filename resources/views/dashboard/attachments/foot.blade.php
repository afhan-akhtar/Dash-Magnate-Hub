<!-- Bootstrap core JavaScript-->
<script src="/dashboard/assets/js/jquery.min.js"></script>
<script src="/dashboard/assets/js/popper.min.js"></script>
<script src="/dashboard/assets/js/bootstrap.min.js"></script>

<!-- simplebar js -->
<script src="/dashboard/assets/plugins/simplebar/js/simplebar.js"></script>
<!-- sidebar-menu js -->
<script src="/dashboard/assets/js/sidebar-menu.js"></script>
<!-- Custom scripts -->
<script src="/dashboard/assets/js/app-script.js"></script>
<!-- Chart js -->
<!-- Apex Chart JS -->
<script src="/dashboard/assets/plugins/apexcharts/apexcharts.js"></script>
<script src="/dashboard/assets/plugins/apexcharts/apex-custom-script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="/dashboard/assets/plugins/Chart.js/Chart.min.js"></script>
<!-- Vector map JavaScript -->
<script src="/dashboard/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="/dashboard/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- Easy Pie Chart JS -->
<script src="/dashboard/assets/plugins/jquery.easy-pie-chart/jquery.easypiechart.min.js"></script>
<!-- Sparkline JS -->
<script src="/dashboard/assets/plugins/sparkline-charts/jquery.sparkline.min.js"></script>
<script src="/dashboard/assets/plugins/jquery-knob/excanvas.js"></script>
<script src="/dashboard/assets/plugins/jquery-knob/jquery.knob.js"></script>
{{-- Sweet Alert --}}
<script src="/dashboard/assets/plugins/alerts-boxes/js/sweetalert.min.js"></script>
{{-- Notification --}}
<script src="/dashboard/assets/plugins/notifications/js/lobibox.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script>
$(function() {
    $(".knob").knob();
});
function Notification(type,size,icon,position,message){
    Lobibox.notify(type, {
        pauseDelayOnHover: true,
        size: size,
        rounded: true,
        delayIndicator: true,
        icon: icon,
        continueDelayOnInactiveTab: false,
        position: position,
        msg: message
    });
}
function Sweet_Alert(title,message,type){
    swal({
        title: title,
        text: message,
        icon: type,
        buttons: false,
        dangerMode: true,
    })
}
function Filter_Box_Toggle(){
    var visi =  $(".filter_Box").attr('visibility');
    if(visi == 0){
        $("#filter_button").html(' ').append(`<i aria-hidden="true" class="fa fa-times-circle fa-2x"></i>`);
        $(".filter_Box").show(300).attr('visibility',1);
    }else{
        $("#filter_button").html(' ').append(`<i aria-hidden="true" class="fa fa-filter fa-2x"></i>`);
        $(".filter_Box").hide(300).attr('visibility',0);
    }
}

$("#Table_Search").keyup(function () {
    var value = this.value.toLowerCase().trim();
    $("#table tr").each(function (index) {
        if (!index) return;
        $(this).find("td").each(function () {
            var id = $(this).text().toLowerCase().trim();
            var not_found = (id.indexOf(value) == -1);
            $(this).closest('tr').toggle(!not_found);
            return not_found;
        });
    });
});

// Global DataTable instance
var dataTableInstance = null;

// Initialize DataTables on a table
function initDataTable(tableId) {
    if (dataTableInstance) {
        dataTableInstance.destroy();
        dataTableInstance = null;
    }
    
    dataTableInstance = $(tableId).DataTable({
        "order": [],
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "responsive": true,
        "language": {
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(filtered from _MAX_ total entries)",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        },
        "columnDefs": [
            { "orderable": false, "targets": [0, -1] } // Disable sorting on # and Action columns
        ]
    });
    
    return dataTableInstance;
}
</script>
<style>
table.dataTable thead .sorting:before,
table.dataTable thead .sorting_asc:before,
table.dataTable thead .sorting_desc:before,
table.dataTable thead .sorting:after,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_desc:after {
    opacity: 1;
    font-size: 10px;
}
table.dataTable thead .sorting:before {
    content: "\f0de";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    opacity: 0.3;
}
table.dataTable thead .sorting:after {
    content: "\f0dd";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    opacity: 0.3;
}
table.dataTable thead .sorting_asc:before {
    content: "\f0de";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    opacity: 1;
    color: #007bff;
}
table.dataTable thead .sorting_desc:after {
    content: "\f0dd";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    opacity: 1;
    color: #007bff;
}
</style>
<!-- Index js -->
<script src="/dashboard/assets/js/index.js"></script>


</body>

</html>
