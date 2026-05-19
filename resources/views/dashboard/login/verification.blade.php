<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>{{ config('app.name') }} | Verification</title>
    <!--favicon-->
    <link rel="icon" type="image/x-icon" href="/website/images/black.png" />
    <!-- Bootstrap core CSS-->
    <link href="/dashboard/assets/css/bootstrap.min.css" rel="stylesheet" />
    <!-- animate CSS-->
    <link href="/dashboard/assets/css/animate.css" rel="stylesheet" type="text/css" />
    <!-- Icons CSS-->
    <link href="/dashboard/assets/css/icons.css" rel="stylesheet" type="text/css" />
    <!-- Custom Style-->
    <link href="/dashboard/assets/css/app-style.css" rel="stylesheet" />
    <link rel="stylesheet" href="/dashboard/assets/plugins/notifications/css/lobibox.min.css"/>
</head>

<body>
    <style>
        .custom-loader {
            width:50px;
            height:16px;
            background:
                radial-gradient(circle closest-side,#ffffff 90%,#0000) 0%   50%,
                radial-gradient(circle closest-side,#ffffff 90%,#0000) 50%  50%,
                radial-gradient(circle closest-side,#ffffff 90%,#0000) 100% 50%;
            background-size:calc(100%/4) 100%;
            background-repeat: no-repeat;
            animation:d7 0.8s infinite linear;
        }
        @keyframes d7 {
            33%{background-size:calc(100%/4) 0%  ,calc(100%/4) 100%,calc(100%/4) 100%}
            50%{background-size:calc(100%/4) 100%,calc(100%/4) 0%  ,calc(100%/4) 100%}
            66%{background-size:calc(100%/4) 100%,calc(100%/4) 100%,calc(100%/4) 0%  }
        }
        .otp-field {
            display: flex;
            justify-content: center;
            padding: 30px 0;
        }

        .otp-field input {
            width: 15%;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            margin: 5px;
            border: 2px solid #55525c;
            background: #21232d;
            font-weight: bold;
            color: #fff;
            outline: none;
            transition: all 0.1s;
        }

        .otp-field input:focus {
            border: 2px solid #e5000a;
            box-shadow: 0 0 2px 2px #e5000a;
        }

        .disabled {
            opacity: 0.5;
        }

        .wroung-code-animation {
            -webkit-animation: kf_shake 0.4s 1 linear;
            -moz-animation: kf_shake 0.4s 1 linear;
            -o-animation: kf_shake 0.4s 1 linear;
        }

        @-webkit-keyframes kf_shake {
            0% {
                -webkit-transform: translate(30px);
            }

            20% {
                -webkit-transform: translate(-30px);
            }

            40% {
                -webkit-transform: translate(15px);
            }

            60% {
                -webkit-transform: translate(-15px);
            }

            80% {
                -webkit-transform: translate(8px);
            }

            100% {
                -webkit-transform: translate(0px);
            }
        }

        @-moz-keyframes kf_shake {
            0% {
                -moz-transform: translate(30px);
            }

            20% {
                -moz-transform: translate(-30px);
            }

            40% {
                -moz-transform: translate(15px);
            }

            60% {
                -moz-transform: translate(-15px);
            }

            80% {
                -moz-transform: translate(8px);
            }

            100% {
                -moz-transform: translate(0px);
            }
        }

        @-o-keyframes kf_shake {
            0% {
                -o-transform: translate(30px);
            }

            20% {
                -o-transform: translate(-30px);
            }

            40% {
                -o-transform: translate(15px);
            }

            60% {
                -o-transform: translate(-15px);
            }

            80% {
                -o-transform: translate(8px);
            }

            100% {
                -o-origin-transform: translate(0px);
            }
        }
    </style>
    <!-- start loader -->
    <div id="pageloader-overlay" class="visible incoming">
        <div class="loader-wrapper-outer">
            <div class="loader-wrapper-inner">
                <div class="loader"></div>
            </div>
        </div>
    </div>
    <!-- end loader -->

    <!-- Start wrapper-->
    <div id="wrapper">

        <div class="loader-wrapper">
            <div class="lds-ring">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="card card-authentication1 mx-auto my-5">
            <div class="card-body">
                <div class="card-content p-2">
                    <div class="text-center">
                        <img src="/website/images/black.png" style="width:31%;">
                    </div>
                    <div class="card-title text-uppercase text-center py-3">OTP Verification</div>
                    <form onsubmit="Check(event)">

                        <div class="form-row">
                            <div class="form-group col-12">
                                <div class="otp-field">
                                    <input type="text" maxlength="1" />
                                    <input type="text" maxlength="1" />
                                    <input type="text" maxlength="1" />
                                    <input type="text" maxlength="1" />
                                    <input type="text" maxlength="1" />
                                    <input type="text" maxlength="1" />
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-block d-flex justify-content-center" id="Resend_Button">Resend</button>
                    </form>
                </div>
            </div>
        </div>
        <!--Start Back To Top Button-->
        <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
        <!--End Back To Top Button-->



    </div>
    <!--wrapper-->

    <!-- Bootstrap core JavaScript-->
    <script src="/dashboard/assets/js/jquery.min.js"></script>
    <script src="/dashboard/assets/js/popper.min.js"></script>
    <script src="/dashboard/assets/js/bootstrap.min.js"></script>

    <!-- sidebar-menu js -->
    <script src="/dashboard/assets/js/sidebar-menu.js"></script>

    <!-- Custom scripts -->
    <script src="/dashboard/assets/js/app-script.js"></script>
    <script src="/dashboard/assets/plugins/notifications/js/lobibox.min.js"></script>
</body>
@csrf
</html>
<!-- OTP jAVASCRIPT -->
<script>
    const inputs = document.querySelectorAll(".otp-field input");

    inputs.forEach((input, index) => {
        input.dataset.index = index;
        input.addEventListener("keyup", handleOtp);
        input.addEventListener("paste", handleOnPasteOtp);
    });

    function handleOtp(e) {
        const input = e.target;
        let value = input.value;
        let isValidInput = value.match(/[0-9a-z]/gi);
        input.value = "";
        input.value = isValidInput ? value[0] : "";

        // alert(input.value);
        $(".otp-field input").removeClass("wroung-code-animation");
        var num = parseFloat($(this).val());
        var check = Number.isInteger(num);
        if (check == false) {
            $(input).val(null);
            $(input).css({
                'border': '2px solid red',
                'box-shadow': '0 0 2px 2px red'
            });
        } else {
            $(input).css({
                'border': '2px solid #e5000a',
                'box-shadow': '0 0 2px 2px #e5000a'
            });
            let fieldIndex = input.dataset.index;
            if (fieldIndex < inputs.length - 1 && isValidInput) {
                $(input).css({
                    'border': 'none',
                    'box-shadow': 'none'
                });
                input.nextElementSibling.focus();
            }

            if (e.key === "Backspace" && fieldIndex > 0) {
                input.previousElementSibling.focus();
            }

            if (fieldIndex == inputs.length - 1 && isValidInput) {
                submit();
            }
        }

    }

    function handleOnPasteOtp(e) {
        const data = e.clipboardData.getData("text");
        const value = data.split("");
        if (value.length === inputs.length) {
            inputs.forEach((input, index) => (input.value = value[index]));
            submit();
        }
    }

    function submit() {
        console.log("Submitting...");
        // 👇 Entered OTP
        let otp = "";
        inputs.forEach((input) => {
            otp += input.value;
            input.disabled = true;
            input.classList.add("disabled");
        });

        // Email Verification

        var fd = new FormData();
        fd.append('otp', otp);

        var code = '<?php echo $code; ?>';
        fd.append('code', code);

        var csrf_token = $("input[name=_token]").val();
        fd.append('_token', csrf_token);

        $.ajax({
            method: "POST",
            url: '/otp-verification',
            processData: false,
            contentType: false,
            data: fd,
        })

        .done(function(data) {
            if (data.error == true) {
                $(".otp-field input").removeClass("disabled");
                $(".otp-field input").removeAttr("disabled");
                $(".otp-field input").addClass("wroung-code-animation");
                $(".otp-field input").val(null);
                $(".otp-field input").css({
                    'border': '2px solid red',
                    'box-shadow': '0 0 2px 2px red'
                });
            } else {
                location.assign("/dashboard/home");
            }
        });

    }
</script>
<script>
    $("#Resend_Button").on('click', function(e) {
        e.preventDefault();
        var fd = new FormData();

        var code = '<?php echo $code; ?>';
        fd.append('code', code);

        var csrf_token = $("input[name=_token]").val();
        fd.append('_token', csrf_token);

        $.ajax({
            method: "POST",
            url: '/resend-otp',
            processData: false,
            contentType: false,
            data: fd,
            beforeSend: function() {
                $('#Resend_Button').attr('disabled','disabled').html(' ').append(`<div class="custom-loader"></div>`);
            },
        })

        .done(function(data) {
            if (data.error == false) {
              $("#Resend_Button").attr('disabled','disabled');
              var timeLeft = 60;
              var elem = document.getElementById('Resend_Button');

              var timerId = setInterval(countdown, 1000);

              function countdown() {
                if (timeLeft == -1) {
                  clearTimeout(timerId);
                  $("#Resend_Button").text('Resend');
                  $("#Resend_Button").removeAttr('disabled');
                } else {
                  elem.innerHTML = 'Resend In ' + timeLeft;
                  timeLeft--;
                }
              }
            }
        });
    });
</script>
