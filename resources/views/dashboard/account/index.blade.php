<div class="modal fade" id="Create_Model">
    <div class="modal-dialog" style="max-width:60%;">
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
                        <div class="col-lg-12 col-md-12 mt-3">
                            <input type="text" class="form-control" placeholder="FullName" id="name" required>
                        </div>
                        <div class="col-lg-6 col-md-12 mt-3">
                            <input type="email" class="form-control" placeholder="Email" id="email" required>
                        </div>
                        <div class="col-lg-6 col-md-12 mt-3">
                            <div class="position-relative has-icon-right">
                                <input type="password" id="password" class="form-control" placeholder="Enter Password" required>
                                <div class="form-control-position" style="top:10px;">
                                    <i class="icon-lock" onclick="myFunction()"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-block">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-danger mx-2" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                                <button type="submit" class="btn btn-success" id="Saved_Button"><i class="fa fa-check-square-o"></i> Save</button>
                            </div>
                        </div>
                    </div>
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
                        <div class="d-flex justify-content-center my-3"><img
                                src="/dashboard/assets/images/delete_icon.png" class="img-fluid" style="width:20%;">
                        </div>
                    </div>
                    <h5 class="text-white text-center">Are You Sure</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger no" data-dismiss="modal"><i class="fa fa-times"></i>
                        No</button>
                    <button type="submit" class="btn btn-success" id="Delete_Button"><i class="fa fa-check"></i>
                        Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('dashboard.attachments.header')
<div class="row pt-2 pb-2">
    <div class="col-8">
        <h4 class="page-title">Accounts</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard/home">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Accounts</li>
        </ol>
    </div>
    <div class="col-4">
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary waves-effect waves-light m-1" title="New" data-toggle="modal"
            data-target="#Create_Model"><i aria-hidden="true" class="fa fa-plus fa-2x"></i></button>
            <button class="btn btn-warning waves-effect waves-light m-1" title="Filter" id="filter_button"
            onclick="Filter_Box_Toggle()"><i aria-hidden="true" class="fa fa-filter fa-2x"></i></button>
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
                        <button class="btn btn-warning waves-effect waves-light" title="Search"
                            style="padding: 7px 0; width:47%;" onclick="Get()">
                            <i aria-hidden="true" class="fa fa-search" style="font-size: 20px;"></i></button>

                        <button class="btn btn-success waves-effect waves-light" title="Reset"
                            style="padding: 7px 0; width:47%;" onclick="Reset()">
                            <i aria-hidden="true" class="fa fa-refresh" style="font-size: 20px;"></i></button>
                    </div>
                </div>
            </div>

            <div class="table-responsive table-fixed mt-3">
                <div id="Table_Loader_Box" class="d-none">
                    <div class="Table_Loader"></div>
                </div>
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
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
<input type="hidden" id="code">
@include('dashboard.attachments.footer')
<script>
    // Prevent duplicate event bindings
    var statusHandlerBound = false;
    
    $(document).ready(function() {
        Get();
        // Bind Status handler once
        if (!statusHandlerBound) {
            bindStatusHandler();
            statusHandlerBound = true;
        }
    });
    
    function bindStatusHandler() {
        // Use event delegation on document to handle dynamically added elements
        $(document).off('click', '.Status').on('click', '.Status', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $button = $(this);
            // Prevent multiple clicks
            if ($button.data('processing')) {
                return false;
            }
            $button.data('processing', true).prop('disabled', true);
            
            var fd = new FormData();
            fd.append('code', $button.attr('Code'));
            fd.append('_token', $("input[name=_token]").val());
            
            $.ajax({
                method: "POST",
                url: '/Account/Status',
                processData: false,
                contentType: false,
                data: fd,
            })
            .done(function(response) {
                if (response.error == 'logout') {
                    location.assign("/dashboard/logout");
                    return;
                }
                if (response.error == false) {
                    // Refresh the list
                    Get();
                    // Show notification only once
                    Notification('success', 'mini', 'fa fa-check', 'bottom right', 'Account ' + response.message);
                } else {
                    Notification('error', 'mini', 'fa fa-times-circle', 'bottom right', response.message || 'An error occurred');
                }
            })
            .fail(function(xhr, status, error) {
                console.error('Status update failed:', error);
                Notification('error', 'mini', 'fa fa-times-circle', 'bottom right', 'Failed to update status. Please try again.');
            })
            .always(function() {
                // Re-enable button after request completes
                $button.data('processing', false).prop('disabled', false);
            });
        });
        
        // Bind Table_Button handler
        $(document).off('click', '.Table_Button').on('click', '.Table_Button', function() {
            $("#code").val($(this).attr('Code'));
        });
    }
    
    function Get() {
        var fd = new FormData();
        fd.append('_token', $("input[name=_token]").val());
        let All = ['take','orderby','search','start_date','end_date'];
        for (let i = 0; i < All.length; i++) { 
            var val = $("#" + All[i]).val();
            fd.append(All[i], val ? val : '');
        }
        $.ajax({
            method: "POST",
            url: '/Account/Get',
            processData: false,
            contentType: false,
            data: fd,
            beforeSend: function() {
                $("#Table_Loader_Box").removeClass("d-none");
                $("#table").addClass("d-none");
            },
        }).done(function(response) {
            $("#tbody").html(response);
            $("#Table_Loader_Box").addClass("d-none");
            $("#table").removeClass("d-none");
        }).fail(function(xhr, status, error) {
            console.log('AJAX error:', error);
            $("#Table_Loader_Box").addClass("d-none");
            $("#table").removeClass("d-none");
            $("#tbody").html('<tr><td colspan="5" class="text-center text-danger">Error loading data. Please refresh the page.</td></tr>');
        });
    }
    // Create Jquery
    function Create(event) {
        event.preventDefault();
        var access = 0;
        // Check Password Type And Confirm Password
        var password = $("#password").val();
        var number = /([0-9])/;
        var Capital = /[A-Z]+/;
        var alphabets = /([a-zA-Z])/;
        var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
        var password = $('#password').val().trim();
        if (password.length < 6) {
            Notification('error', 'mini', 'fa fa-times-circle', 'bottom right', 'Weak (should be atleast 6 characters.');
            access++;
        } else {
            if (password.match(number) && password.match(alphabets) && password.match(special_characters)) {
                if(password.match(Capital)){

                }else{
                    Notification('warning', 'mini', 'fa fa-warning', 'bottom right', 'Password Must Be Contain Atleast One Capital Character');
                    access++;
                }
            } else {
                Notification('warning', 'mini', 'fa fa-warning', 'bottom right', 'Medium (should include alphabets, numbers and special characters.');
                access++;
            }
        }

        if (access == 0) {
            ALL = ['name','email','password'];
            var fd = new FormData();
            fd.append('_token', $("input[name=_token]").val());
            for (let i = 0; i < ALL.length; i++) {
                fd.append(ALL[i], $("#" + ALL[i]).val());
            }
            $.ajax({
                method: "POST",
                url: '/Account/Insert',
                processData: false,
                contentType: false,
                data: fd,
                beforeSend: function() {
                    $('#Saved_Button').attr('disabled', 'disabled').html('').append(`<div class="Button_Loader"></div>`);
                },
            })
            .done(function(response) {
                if (response.error == false) {
                Get(); $('.close').click();
                Sweet_Alert('Congratulation', response.message, 'success');
                $('#Saved_Button').removeAttr('disabled').html('<i class="fa fa-check-square-o"></i> Create');
                for (let i = 0; i < ALL.length; i++) { $("#" + ALL[i]).val(''); }}
                if (response.error == true) {
                Notification('error', 'mini', 'fa fa-times-circle', 'bottom right', response.message);
                $('#Saved_Button').removeAttr('disabled').html('<i class="fa fa-check-square-o"></i> Create');}
            });
        }
    }

    function Delete(event) {
        event.preventDefault();
        var fd = new FormData();
        fd.append('_token', $("input[name=_token]").val());
        fd.append('code', $("#code").val());
        $.ajax({
            method: "POST",
            url: '/Account/Delete',
            processData: false,
            contentType: false,
            data: fd,
            beforeSend: function() {
                $('#Delete_Button').attr('disabled', 'disabled').html('').append(`<div class="Button_Loader"></div>`);
            },
        }).done(function(response) {
            if (response.error == true) {
                location.assign("/dashboard/login");
            }
            if (response.error == false) {
                $('.no').click(); Get(); Sweet_Alert('Congratulation', response.message, 'success');
                $('#Delete_Button').removeAttr('disabled').html('<i class="fa fa-check"></i> Delete');
            }
        });
    }

    function Reset() {
    $("#take").html('').append(`<option value="20">Limit</option>`).append(`<option value="50">50</option>`).append(
        `<option value="100">100</option>`).append(`<option value="200">200</option>`).append(
        `<option value="300">300</option>`).append(`<option value="400">400</option>`).append(
        `<option value="500">500</option>`);
    $("#orderby").html('').append(`<option value="desc">Sort By</option>`).append(
        `<option value="asc">ASC</option>`).append(`<option value="desc">Desc</option>`);
    $("#search").val(null); $("#start_date").val(null); $("#end_date").val(null);}

    function myFunction() {
    var x = document.getElementById("password");
    if (x.type === "password") {x.type = "text";
    } else {x.type = "password";}}
</script>
