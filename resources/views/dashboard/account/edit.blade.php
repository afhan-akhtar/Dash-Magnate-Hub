@include('dashboard.attachments.header')
<div class="row pt-2 pb-2">
    <div class="col-8">
        <h4 class="page-title">Accounts</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard/login">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/dashboard/account">Accounts</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </div>
    <div class="col-4">
        <div class="d-flex justify-content-end">
            <a href="/dashboard/account"><button class="btn btn-primary waves-effect waves-light m-1"
            title="Back To Listing"><i aria-hidden="true" class="fa fa-arrow-circle-o-left fa-2x"></i></button></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form onsubmit="Edit(event)">
                <div class="row">

                    <div class="col-lg-12 col-md-12 mt-3">
                        <input type="text" class="form-control" placeholder="FullName" id="name" value="{{ $Edit[0]->name }}" required>
                    </div>
                    <div class="col-lg-6 col-md-12 mt-3">
                        <input type="email" class="form-control" placeholder="Email" id="email" value="{{ $Edit[0]->email }}">
                    </div>
                    <div class="col-lg-6 col-md-12 mt-3">
                        <div class="position-relative has-icon-right">
                            <input type="password" id="password" class="form-control" placeholder="Enter Password">
                            <div class="form-control-position" style="top: 12px;">
                                <i class="icon-lock" onclick="myFunction()"></i>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success" id="Saved_Button"><i class="fa fa-check-square-o"></i> Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('dashboard.attachments.footer')
@csrf
<script>
    // Create Jquery
    function Edit(event) {
        event.preventDefault();
        var access = 0;

        var password = $("#password").val();
        if (password != '') {
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
                    Notification('warning', 'mini', 'fa fa-warning', 'bottom right', 'Medium (should include alphabets, numbers and special characters.'); access++;
                }
            }
        }


        if (access == 0) {
            ALL = ['name','email','password']; var fd = new FormData(); fd.append('_token', $("input[name=_token]").val());
            for (let i = 0; i < ALL.length; i++) {fd.append(ALL[i], $("#" + ALL[i]).val());} fd.append('code', '<?php echo $code ?>');

            $.ajax({
                method: "POST",
                url: '/Account/Update',
                processData: false,
                contentType: false,
                data: fd,
                beforeSend: function() {
                    $('#Saved_Button').attr('disabled', 'disabled').html(' ').append(`<div class="Button_Loader"></div>`);
                },
            })
            .done(function(response) {
                if (response.error == true) {Notification('success', 'mini', 'fa fa-check', 'bottom right', response.message);
                var time = 2;
                var CheckTime = setInterval(function(){
                    if(time <= 0){
                        clearInterval(CheckTime); location.assign("/dashboard/logout");
                    }
                }, 1000);
                $('#Saved_Button').removeAttr('disabled').html('<i class="fa fa-check-square-o"></i> Save');

                }
                if (response.error == false) {Notification('success', 'mini', 'fa fa-check', 'bottom right', response.message);$('#Saved_Button').removeAttr('disabled').html('<i class="fa fa-check-square-o"></i> Save');}
            });
        }

    }
    function myFunction() {
    var x = document.getElementById("password");
    if (x.type === "password") {x.type = "text";
    } else {x.type = "password";}}
</script>
