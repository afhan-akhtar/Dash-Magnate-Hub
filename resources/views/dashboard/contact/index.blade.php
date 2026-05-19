<div class="modal fade" id="Delete_Model">
    <div class="modal-dialog" style="max-width:30%;">
        <div class="modal-content animated swing" style="background-color: #000;">
            <form onsubmit="Delete(event)">
                <div class="modal-body">
                    <div class="row">
                        <div class="d-flex justify-content-center my-3"><img src="/dashboard/assets/images/delete_icon.png" class="img-fluid" style="width:20%;"></div>
                    </div>
                    <h5 class="text-white text-center">Are You Sure</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger no" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                    <button type="submit" class="btn btn-success" id="Delete_Button"><i class="fa fa-check"></i> Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="Read_Model">
    <div class="modal-dialog" style="max-width:60%;">
        <div class="modal-content animated slideInUp">
            <div class="modal-header">
                <h5 class="modal-title">Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h6>Type : <span id="type_span"></span></h6>
                        <h6>Name : <span id="name_span"></span></h6>
                        <h6>Email : <span id="email_span"></span></h6>
                        <h6>Phone : <span id="phone_span"></span></h6>
                        <h6>Message : <span id="message_span"></span></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('dashboard.attachments.header')

<div class="row pt-2 pb-2">
    <div class="col-8">
        <h4 class="page-title">Forms</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard/login">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Forms</li>
        </ol>
    </div>
    <div class="col-4">
        <div class="d-flex justify-content-end">
            <!-- <button class="btn btn-primary waves-effect waves-light m-1" title="New" data-toggle="modal" data-target="#Create_Model"><i aria-hidden="true" class="fa fa-plus fa-2x"></i></button> -->
            <button class="btn btn-warning waves-effect waves-light m-1" title="Filter" id="filter_button" onclick="Filter_Box_Toggle()">
                <i aria-hidden="true" class="fa fa-filter fa-2x"></i></button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
        <div class="filter_Box" visibility="0">
            <div class="row">
                <div class="col-lg-6 col-sm-12 my-2">
                    <input type="text" class="form-control" id="search" placeholder="Search In DataBase">
                </div>
                <div class="col-lg-2 col-sm-6 my-2">
                    <select class="form-control" id="take">
                        <option value="20">Limit</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="300">300</option>
                        <option value="400">400</option>
                        <option value="500">500</option>
                    </select>
                </div>
                <div class="col-lg-2 col-sm-6 my-2">
                    <select class="form-control" id="type">
                        <option value="">type</option>
                        <option value="News Letter Form">News Letter Form</option>
                        <option value="Contact Form">Contact Form</option>
                    </select>
                </div>
                <div class="col-lg-2 col-sm-6 my-2">
                    <select class="form-control" id="orderby">
                        <option value="desc">Sort By</option>
                        <option value="asc">ASC</option>
                        <option value="desc">Desc</option>
                    </select>
                </div>
                <div class="col-4 my-2">
                    <input type="text" class="form-control" id="Table_Search" placeholder="Search In Table">
                </div>
                <div class="col-3 my-2">
                    <input type="date" class="form-control" id="start_date" placeholder="Search In Table">
                </div>
                <div class="col-3 my-2">
                    <input type="date" class="form-control" id="end_date" placeholder="Search In Table">
                </div>
                <div class="col-2 my-2 d-flex justify-content-around">
                    <button class="btn btn-warning waves-effect waves-light" title="Search" style="padding: 7px 0; width:47%;" onclick="Get()">
                    <i aria-hidden="true" class="fa fa-search" style="font-size: 20px;"></i></button>

                    <button class="btn btn-success waves-effect waves-light" title="Reset" style="padding: 7px 0; width:47%;" onclick="Reset()">
                    <i aria-hidden="true" class="fa fa-refresh" style="font-size: 20px;"></i></button>
                </div>
            </div>
        </div>

            <div class="table-responsive table-fixed mt-3">
                <div id="Table_Loader_Box" class="d-none"><div class="Table_Loader"></div></div>
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col" class="sortable-column" data-column="type">Type <span class="sort-icon"><i class="fa fa-sort" style="opacity: 0.3;"></i></span></th>
                            <th scope="col" class="sortable-column" data-column="name">Name <span class="sort-icon"><i class="fa fa-sort" style="opacity: 0.3;"></i></span></th>
                            <th scope="col" class="sortable-column" data-column="email">Email <span class="sort-icon"><i class="fa fa-sort" style="opacity: 0.3;"></i></span></th>
                            <th scope="col" class="sortable-column" data-column="phone">Phone <span class="sort-icon"><i class="fa fa-sort" style="opacity: 0.3;"></i></span></th>
                            <th scope="col" class="sortable-column" data-column="date">Date <span class="sort-icon"><i class="fa fa-sort" style="opacity: 0.3;"></i></span></th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@csrf
<input type="hidden" class="code">
<input type="hidden" id="sort_column" value="">
<input type="hidden" id="sort_direction" value="">
@include('dashboard.attachments.footer')
<script>
    $(document).ready(function() {
        Get();
    });
    
    function Get() {
        var fd = new FormData(); fd.append('_token', $("input[name=_token]").val());
        let All = ['take','orderby','search','start_date','end_date','type'];
        for (let i = 0; i < All.length; i++) { fd.append(All[i], $("#" + All[i]).val());}
        $.ajax({
            method: "POST",
            url: '/Contact/Get',
            processData: false,
            contentType: false,
            data: fd,
            beforeSend: function() {
                $("#Table_Loader_Box").removeClass("d-none"); $("#table").addClass("d-none");
            },
        }).done(function(response) {
            $("#tbody").html("").append(response); 
            $("#Table_Loader_Box").addClass("d-none"); 
            $("#table").removeClass("d-none");
            // Initialize DataTables after data loads
        });
    }

    function Delete(event){
    event.preventDefault(); var fd = new FormData();fd.append('_token', $("input[name=_token]").val());fd.append('code', $(".code").val());
    $.ajax({method: "POST",url: '/Contact/Delete',processData: false,contentType: false,data: fd}).done(function(response) {if (response.error == true) {location.assign("/dashboard/login");}if (response.error == false) {$('.no').click(); Get(); Sweet_Alert('Congratulation',response.message,'success');}});}

    function Reset() {
    $("#take").html('').append(`<option value="20">Limit</option>`).append(`<option value="50">50</option>`).append(
        `<option value="100">100</option>`).append(`<option value="200">200</option>`).append(
        `<option value="300">300</option>`).append(`<option value="400">400</option>`).append(
        `<option value="500">500</option>`);
    $("#orderby").html('').append(`<option value="desc">Sort By</option>`).append(
        `<option value="asc">ASC</option>`).append(`<option value="desc">Desc</option>`);
    $("#search").val(null); $("#start_date").val(null); $("#end_date").val(null);}
</script>
