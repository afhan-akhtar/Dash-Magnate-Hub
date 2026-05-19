@include('dashboard.attachments.header')
<div class="row pt-2 pb-2">
    <div class="col-8">
        <h4 class="page-title">Traffic</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard/login">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Traffic</li>
            <li class="breadcrumb-item active" aria-current="page">Country</li>
        </ol>
    </div>
    <div class="col-4">
        <div class="d-flex justify-content-end">
            <button class="btn btn-success waves-effect waves-light m-1" title="Refresh" onclick="Get()"><i class="fa fa-refresh fa-2x"></i></button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="table-responsive table-fixed mt-3">
                <div id="Table_Loader_Box" class="">
                    <div class="Table_Loader"></div>
                </div>
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Country</th>
                            <th scope="col">Traffic</th>
                            <th scope="col">Action</th>
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
@include('dashboard.attachments.footer')
<script>
    $(document).ready(function() {
        Get();
    });
    
    function Get() {
        var fd = new FormData(); fd.append('_token', $("input[name=_token]").val());
        $.ajax({
            method: "POST",
            url: '/Traffic/Get/Country',
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
            // Initialize DataTables after data loads
        });
    }
</script>
