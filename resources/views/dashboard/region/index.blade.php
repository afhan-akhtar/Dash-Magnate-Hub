<div class="modal fade" id="Create_Model">
    <div class="modal-dialog" style="max-width:60%;">
        <div class="modal-content animated slideInUp">
            <div class="modal-header">
                <h5 class="modal-title">Add New Region</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="Create(event)">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 mb-3">
                            <label>Location <span class="text-danger">*</span></label>
                            <select class="form-control" id="location_id" required>
                                <option value="">Select Location</option>
                            </select>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <label>Region Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Name" id="name" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger mx-2" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-success" id="Saved_Button"><i class="fa fa-check-square-o"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
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
@include('dashboard.attachments.header')
<div class="row pt-2 pb-2">
    <div class="col-8">
        <h4 class="page-title">Regions</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard/login">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Regions</li>
        </ol>
    </div>
    <div class="col-4">
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary waves-effect waves-light m-1" title="New" data-toggle="modal" data-target="#Create_Model"><i aria-hidden="true" class="fa fa-plus fa-2x"></i></button>
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
                <div class="col-lg-4 col-sm-12 my-2">
                    <label>Location</label>
                    <select class="form-control" id="filter_location_id">
                        <option value="">All Locations</option>
                    </select>
                </div>
                <div class="col-lg-4 col-sm-12 my-2">
                    <input type="text" class="form-control" id="search" placeholder="Search In DataBase">
                </div>
                <div class="col-lg-2 col-sm-6 my-2">
                    <select class="form-control" id="take">
                        <option value="20">Limit</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="500">500</option>
                    </select>
                </div>
                <div class="col-lg-2 col-sm-6 my-2">
                    <select class="form-control" id="orderby">
                        <option value="desc">Sort By</option>
                        <option value="asc">ASC</option>
                        <option value="desc">Desc</option>
                    </select>
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
                            <th scope="col">Name</th>
                            <th scope="col">Location</th>
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
<input type="hidden" class="region-id" name="region_id">
@include('dashboard.attachments.footer')
<script>
    $(document).ready(function() {
        Get_Location();
        Get();
    });

    function Get_Location() {
        $.ajax({ method: "GET", url: '/Region/Location' }).done(function(response) {
            if (response.error) return;
            var loc = response.Location;
            $("#location_id").html('<option value="">Select Location</option>');
            $("#filter_location_id").html('<option value="">All Locations</option>');
            if (loc && loc.length) {
                loc.forEach(function(l) {
                    $("#location_id").append('<option value="' + l.location_id + '">' + l.name + '</option>');
                    $("#filter_location_id").append('<option value="' + l.location_id + '">' + l.name + '</option>');
                });
            }
        });
    }

    function Get() {
        var fd = new FormData();
        fd.append('_token', $("input[name=_token]").val());
        fd.append('take', $("#take").val());
        fd.append('orderby', $("#orderby").val());
        fd.append('search', $("#search").val());
        fd.append('location_id', $("#filter_location_id").val());
        $.ajax({
            method: "POST",
            url: '/Region/Get',
            processData: false,
            contentType: false,
            data: fd,
            beforeSend: function() {
                $("#Table_Loader_Box").removeClass("d-none");
                $("#table").addClass("d-none");
            },
        }).done(function(response) {
            $("#tbody").html("").append(response);
            $("#Table_Loader_Box").addClass("d-none");
            $("#table").removeClass("d-none");
        });
    }

    function Create(event) {
        event.preventDefault();
        var fd = new FormData();
        fd.append('_token', $("input[name=_token]").val());
        fd.append('name', $("#name").val());
        fd.append('location_id', $("#location_id").val());
        $.ajax({
            method: "POST",
            url: '/Region/Insert',
            processData: false,
            contentType: false,
            data: fd,
            beforeSend: function() {
                $('#Saved_Button').attr('disabled', 'disabled').html(' ').append('<div class="Button_Loader"></div>');
            }
        }).done(function(response) {
            if (response.error == true) {
                Notification('error', 'mini', 'fa fa-check', 'bottom right', response.message || 'Error');
                $('#Saved_Button').removeAttr('disabled').html('<i class="fa fa-check-square-o"></i> Save');
                return;
            }
            if (response.error == false) {
                Get();
                $('.close').click();
                Sweet_Alert('Congratulation', response.message, 'success');
                $('#Saved_Button').removeAttr('disabled').html('<i class="fa fa-check-square-o"></i> Save');
                $("#name").val('');
                $("#location_id").val('');
            }
        });
    }

    function Delete(event) {
        event.preventDefault();
        var fd = new FormData();
        fd.append('_token', $("input[name=_token]").val());
        fd.append('id', $("input.region-id").val());
        $.ajax({
            method: "POST",
            url: '/Region/Delete',
            processData: false,
            contentType: false,
            data: fd
        }).done(function(response) {
            if (response.error == true) location.assign("/dashboard/login");
            if (response.error == false) {
                $('.no').click();
                Get();
                Sweet_Alert('Congratulation', response.message, 'success');
            }
        });
    }

    function Reset() {
        $("#take").val('20');
        $("#orderby").val('desc');
        $("#search").val(null);
        $("#filter_location_id").val('');
    }

    function Filter_Box_Toggle() {
        var v = $(".filter_Box").attr("visibility");
        if (v == 0) {
            $(".filter_Box").slideDown().attr("visibility", "1");
        } else {
            $(".filter_Box").slideUp().attr("visibility", "0");
        }
    }
</script>
