@include('dashboard.attachments.header')
<div class="row pt-2 pb-2">
    <div class="col-8">
        <h4 class="page-title">Traffic</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard/login">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Traffic</li>
            <li class="breadcrumb-item active" aria-current="page">Month</li>
        </ol>
    </div>
    <div class="col-4">
        <div class="d-flex justify-content-end">
            <button class="btn btn-success waves-effect waves-light m-1" title="Refresh" onclick="Get()"><i class="fa fa-refresh fa-2x"></i></button>
            <button class="btn btn-warning waves-effect waves-light m-1" title="Filter" id="filter_button" onclick="Filter_Box_Toggle()"><i class="fa fa-filter fa-2x"></i></button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="filter_Box" visibility="0">
                <div class="row">
                    <div class="col-lg-10 col-sm-12 my-2">
                        <label>Year</label>
                        <select class="form-control" id="year">
                            <option value="{{ $Year }}">{{ $Year }}</option>
                            @foreach($Years as $y)
                                @if($Year != $y->year)
                                    <option value="{{ $y->year }}">{{ $y->year }}</option>
                                @endif
                            @endforeach
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
                <div id="Table_Loader_Box" class="">
                    <div class="Table_Loader"></div>
                </div>
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Month</th>
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
        fd.append('year', $("#year").val());
        $.ajax({
            method: "POST",
            url: '/Traffic/Get/Month',
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
