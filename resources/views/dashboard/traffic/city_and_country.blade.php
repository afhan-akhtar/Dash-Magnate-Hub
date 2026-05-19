@include('dashboard.attachments.header')
<div class="row pt-2 pb-2">
    <div class="col-8">
        <h4 class="page-title">Traffic</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard/login">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Traffic</li>
            <li class="breadcrumb-item active" aria-current="page">City & Country</li>
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
                    <div class="col-lg-5 col-sm-12 my-2">
                        <label>Country</label>
                        <select class="form-control" id="country" onchange="Get_City()">
                            @if($Country != '')
                            <option value="{{ $Country }}">{{ $Country }}</option>
                            @endif
                            @foreach($Countries as $c)
                                @if($Country != $c->country)
                                    <option value="{{ $c->country }}">{{ $c->country }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-5 col-sm-12 my-2">
                        <label for="">City</label>
                        <select class="form-control" id="city" disabled></select>
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
                            <th scope="col">Date & Day</th>
                            <th scope="col">Traffic</th>
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
    function Get() {
        var fd = new FormData(); fd.append('_token', $("input[name=_token]").val());
        fd.append('country', $("#country").val());
        fd.append('city', $("#city").val());
        $.ajax({
            method: "POST",
            url: '/Traffic/Get/City/And/Country',
            processData: false,
            contentType: false,
            data: fd,
            beforeSend: function() {
                $("#Table_Loader_Box").removeClass("d-none");
                $("#table").addClass("d-none");
            },
        }).done(function(response) {
            $("#tbody").html("").append(response); $("#Table_Loader_Box").addClass("d-none"); $("#table").removeClass("d-none");
        });
    }

    Get_City();
    function Get_City() {
        var country = $("#country").val();
        $("#city").html('').attr('disabled', 'disabled');
        if (country != 0) {
            $.ajax({
                method: "GET",
                url: '/Get/City/' + country
            })
            .done(function(response) {
                $("#city").html("");
                cities = response.Cities;
                if (cities.length > 0) { var City = '<?php echo $City ?>';
                    $("#city").removeAttr('disabled');
                    cities.forEach(function(c) { if(City == c.city){ $("#city").append(`<option value="${c.city}">${c.city}</option>`);} });
                    cities.forEach(function(c) { if(City != c.city){ $("#city").append(`<option value="${c.city}">${c.city}</option>`);} });
                    Get();
                } else {
                    $("#city").html("").append(`<option value="0">No Cities Found</option>`);
                }
            });
        }
    }
</script>
