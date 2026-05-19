<div class="modal fade" id="Create_Model">
    <div class="modal-dialog" style="max-width:70%;">
        <div class="modal-content animated slideInUp">
            <div class="modal-header">
                <h5 class="modal-title">Add New</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="Create(event)">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Category <span style="color: red;">*</span></label>
                            <select class="form-control" id="category_id" required>
                                <option value="">-- Select Category --</option>
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Location <span style="color: red;">*</span></label>
                            <select class="form-control" id="location_id" required>
                                <option value="">-- Select Location --</option>
                            </select>
                        </div>
                        <div class="col-lg-12 col-md-12 mt-2">
                            <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Listing Name <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" placeholder="Enter listing name" id="name" required>
                        </div>
                        <div class="col-lg-12 col-md-12 mt-2">
                            <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Price <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" placeholder="e.g. 250000, POA, or EOI" id="price" inputmode="text" required>
                        </div>
                        <div class="col-lg-12 col-md-12 mt-2">
                            <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Years Operating <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" placeholder="Enter years operating (e.g., 5 years)" id="trading" required>
                        </div>
                        <div class="col-lg-12 col-md-12 mt-2">
                            <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Earning Type</label>
                            <input type="text" class="form-control" placeholder="Enter earning type (e.g., EBITDA, PETIBDA)" id="earning_type">
                        </div>
                        <div class="col-lg-12 col-md-12 mt-2">
                            <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Stock Level</label>
                            <input type="text" class="form-control" placeholder="Enter stock level" id="stock_level">
                        </div>
                        <div class="col-lg-12 col-md-12 mt-2">
                            <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Summary <span style="color: red;">*</span></label>
                            <textarea id="summary" placeholder="Enter business summary" rows="5" class="form-control" required></textarea>
                        </div>
                        <div class="col-lg-12 col-md-12 mt-2">
                            <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Location Information</label>
                            <textarea id="location_information" placeholder="Enter location details" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="col-lg-12 col-md-12 mt-2">
                            <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Description</label>
                            <textarea id="description" placeholder="Enter detailed description" rows="5" class="form-control"></textarea>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="modern-image-upload">
                                <label class="modern-image-upload-label">
                                    Card Image <span class="required">*</span>
                                </label>
                                <div class="modern-upload-area compact" id="card_upload_area">
                                    <input type="file" class="modern-upload-input" id="card" accept="image/png, image/gif, image/jpeg" required>
                                    <div class="modern-upload-preview" id="card_preview" style="display: none;">
                                        <img id="card_tag" src="" alt="Preview">
                                        <button type="button" class="modern-upload-remove" onclick="removeImage('card', 'card_preview', 'card_upload_area', 'card_upload_content')">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modern-upload-content" id="card_upload_content">
                                        <i class="fa fa-cloud-upload modern-upload-icon"></i>
                                        <div class="modern-upload-text">
                                            <strong>Click to upload</strong> or drag and drop
                                        </div>
                                        <div class="modern-upload-hint">
                                            PNG, JPG, GIF up to 10MB
                                        </div>
                                    </div>
                                    <div class="modern-upload-progress">
                                        <div class="modern-upload-progress-bar" id="card_progress"></div>
                                    </div>
                                </div>
                            </div>
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
        <h4 class="page-title">Listings</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard/login">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listings</li>
        </ol>
    </div>
    <div class="col-4">
        <div class="d-flex justify-content-end">
            {{-- <button class="btn btn-primary waves-effect waves-light m-1" title="New" data-toggle="modal" data-target="#Create_Model"><i aria-hidden="true" class="fa fa-plus fa-2x"></i></button> --}}
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
                <div class="col-lg-8 col-sm-12 my-2">
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
                            <th scope="col">Listing ID</th>
                            <th scope="col">Card Image</th>
                            <th scope="col">Name</th>
                            <th scope="col">Views</th>
                            <th scope="col">Raiser</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
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
@include('dashboard.attachments.footer')
<script>
    $(document).ready(function() {
        Get(); 
        Get_Category(); 
        Get_Location();
    });
    
    function Get() {
        var fd = new FormData(); fd.append('_token', $("input[name=_token]").val());
        let All = ['take','orderby','search','start_date','end_date'];
        for (let i = 0; i < All.length; i++) { fd.append(All[i], $("#" + All[i]).val());}
        $.ajax({
            method: "POST",
            url: '/Project/Get',
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
        });
    }
    function Get_Category() {
        $("#category_id").html('').attr('disabled', 'disabled');
        $.ajax({
            method: "GET",
            url: '/Project/Get/Category',
        })
        .done(function(response) {
            category = response.category;
            if (category.length > 0) {
                $("#category_id").removeAttr('disabled').html("").append(
                    `<option value="0">Select Category</option>`);
                category.forEach(function(c) {
                    $("#category_id").append(`<option value="${c.category_id}">${c.name}</option>`);
                });
            } else {
                $("#category_id").html("").append(`<option value="0">No Category Found</option>`);
            }
        });
    }
    function Get_Location() {
        $("#location_id").html('').attr('disabled', 'disabled');
        $.ajax({
            method: "GET",
            url: '/Project/Get/Location',
        })
        .done(function(response) {
            locations = response.location;
            if (locations.length > 0) {
                $("#location_id").removeAttr('disabled').html("").append(`<option value="0">Select Location</option>`);
                locations.forEach(function(l) {
                    $("#location_id").append(`<option value="${l.location_id}">${l.name}</option>`);
                });
            } else {
                $("#location_id").html("").append(`<option value="0">No Location Found</option>`);
            }
        });
    }
    // Create Jquery
    function Create(event) {
        event.preventDefault();
        ALL = ["name", "price", "trading", "earning_type", "stock_level", "summary", "location_information","location_id","category_id","description"];
        var fd = new FormData(); fd.append('_token', $("input[name=_token]").val());
        for (let i = 0; i < ALL.length; i++) {fd.append(ALL[i], $("#" + ALL[i]).val());}
        var card = $("#card")[0].files; for (var i = 0; i < card.length; i++) {fd.append("card[]", card[i], card[i]['name']);}

        $.ajax({
            method: "POST",
            url: '/Project/Insert',
            processData: false,
            contentType: false,
            data: fd,
            beforeSend: function() {
                $('#Saved_Button').attr('disabled', 'disabled').html(' ').append(
                    `<div class="Button_Loader"></div>`);
            },
        })
        .done(function(response) {
            if (response.error == true) {
                location.assign("/dashboard/login");
            }
            if (response.error == false) {
                Get(); $('.close').click();
                // Notification('success', 'mini', 'fa fa-check', 'bottom right', response.message);
                Sweet_Alert('Congratulation',response.message,'success');
                $('#Saved_Button').removeAttr('disabled').html('<i class="fa fa-check-square-o"></i> Create');
                for (let i = 0; i < ALL.length; i++) {
                    $("#" + ALL[i]).val('');
                }Get_Project(); Get_Category(); Get_Location();
            }
        });
    }
    function Delete(event){
    event.preventDefault(); var fd = new FormData();fd.append('_token', $("input[name=_token]").val());fd.append('code', $(".code").val());
    $.ajax({method: "POST",url: '/Project/Delete',processData: false,contentType: false,data: fd}).done(function(response) {if (response.error == true) {location.assign("/dashboard/login");}if (response.error == false) {$('.no').click(); Get(); Sweet_Alert('Congratulation',response.message,'success');}});}

    function Reset() {
    $("#take").html('').append(`<option value="20">Limit</option>`).append(`<option value="50">50</option>`).append(
        `<option value="100">100</option>`).append(`<option value="200">200</option>`).append(
        `<option value="300">300</option>`).append(`<option value="400">400</option>`).append(
        `<option value="500">500</option>`);
    $("#orderby").html('').append(`<option value="desc">Sort By</option>`).append(
        `<option value="asc">ASC</option>`).append(`<option value="desc">Desc</option>`);
    $("#search").val(null); $("#start_date").val(null); $("#end_date").val(null);}

    // Modern Image Upload Functions
    function readURL(input, previewId, uploadAreaId, uploadContentId) {
        if (input.files && input.files[0]) {
            var file = input.files[0];
            var reader = new FileReader();

            reader.onload = function (e) {
                $(previewId).find('img').attr('src', e.target.result);
                $(previewId).show();
                $(uploadContentId).hide();
                $(uploadAreaId).addClass('has-image');
            }

            reader.readAsDataURL(file);
        }
    }

    function removeImage(inputId, previewId, uploadAreaId, uploadContentId) {
        $('#' + inputId).val('');
        $('#' + previewId).hide();
        $('#' + uploadContentId).show();
        $('#' + uploadAreaId).removeClass('has-image');
    }

    // Drag and Drop
    $('#card_upload_area').on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dragover');
    });

    $('#card_upload_area').on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
    });

    $('#card_upload_area').on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
        var files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            $('#card')[0].files = files;
            readURL($('#card')[0], '#card_preview', '#card_upload_area', '#card_upload_content');
        }
    });

    $("#card").change(function() {
        readURL(this, '#card_preview', '#card_upload_area', '#card_upload_content');
    });
</script>
