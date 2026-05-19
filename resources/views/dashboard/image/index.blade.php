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
<!-- Link Swiper's CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<div class="row pt-2 pb-2">
    <div class="col-8">
        <h4 class="page-title">Images</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard/login">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Images</li>
        </ol>
    </div>
    <div class="col-4">
        <div class="d-flex justify-content-end">
            <a href="/dashboard/product"><button class="btn btn-primary waves-effect waves-light m-1"
                    title="Back To Listing"><i aria-hidden="true"
                        class="fa fa-arrow-circle-o-left fa-2x"></i></button></a>
        </div>
    </div>
</div>
<style>
    label.file {
        position: relative;
        display: block;
        width: 100%;
        font-size: 80%;
        text-align: center;
        text-transform: uppercase;
        border: 2px dashed #ccc;
        margin: 3rem 0;
        padding: 3rem;
    }

    label.file:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }

    label.file:active,
    label.file.focus {
        border-color: #09f;
    }

    input[type=file] {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        outline: 0;
        border: 1px solid red;
    }

    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 200px;
        background-color: #ccc;
        padding: 5px;
        border-radius: 5px;
    }

    .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
</style>
<div class="row" style="height: 450px; margin-bottom:20px;">
    <div class="col-12">
        <div class="filter_Box" visibility="0">
            <div class="row">
                <div class="col-lg-6 col-sm-12 my-2">
                    <input type="text" class="form-control" id="search" placeholder="Search By Name">
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

        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-primary d-flex justify-content-between">
                    <div class="d-flex justify-content-start">
                        <li class="nav-item">
                            <a class="nav-link active"  Tab="#tabe-1"><i class="fa fa-list"></i>
                                <span class="hidden-xs">Uploaded</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" Tab="#tabe-2"><i class="fa fa-upload"></i>
                                <span class="hidden-xs">Add New</span>
                            </a>
                        </li>
                    </div>
                    <div>
                        <li>
                            <button class="btn btn-danger waves-effect waves-light d-none Delete_Button" title="Delete"
                            data-toggle="modal" data-target="#Delete_Model">
                            <i aria-hidden="true" class="fa fa-trash" style="font-size: 20px;"></i></button>
                        </li>
                    </div>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div id="tabe-1" class="container tab-pane active">
                        <div class="row justify-content-center align-items-center" style="height:50vh;overflow-y:scroll;">
                            <div id="Table_Loader_Box" class="d-none">
                                <div class="Table_Loader"></div>
                            </div>
                            <div class="col-12">
                                <div class="row" id="data"></div>
                            </div>
                        </div>
                    </div>
                    <div id="tabe-2" class="container tab-pane">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="text-uppercase">Multiple Form Uploads</h6>
                                <div>
                                    <button type="button"
                                        class="btn btn-outline-success waves-effect waves-light m-1 d-none"
                                        id="Submit" onclick="Upload()">
                                        <i class="fa fa-upload fa-2x"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form>
                                    <label class="file">
                                        Click To Select Images
                                        <input type="file" name="image" multiple
                                            accept="image/png, image/gif, image/jpeg" id="images">
                                    </label>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="col-12">
                                <div class="swiper mySwiper">
                                    <div class="swiper-wrapper" id="swiper-wrapper"></div>
                                </div>
                                <div class="button-row d-flex justify-content-center mb-5 mt-2">
                                    <button type="button"
                                        class="btn btn-outline-primary waves-effect waves-light m-1 Prev_Button">
                                        <i class="fa fa-arrow-left fa-2x"></i>
                                    </button>
                                    <button type="button"
                                        class="btn btn-outline-primary waves-effect waves-light m-1 Next_Button">
                                        <i class="fa fa-arrow-right fa-2x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

@csrf
@include('dashboard.attachments.footer')
<input type="hidden" id="selected">
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<script>
    $(".nav-link").on('click', function(){
        $(".nav-link").removeClass('active'); $(this).addClass('active'); $(".tab-pane").removeClass('active'); $($(this).attr('Tab')).addClass('active');
    });
</script>

<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper(".mySwiper", {
        navigation: {
            nextEl: ".Next_Button",
            prevEl: ".Prev_Button",
        },
        autoplay: {
            delay: 2000,
        },
        breakpoints: {
            // when window width is >= 320px
            320: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            // when window width is >= 480px
            480: {
                slidesPerView: 3,
                spaceBetween: 30
            },
            // when window width is >= 640px
            640: {
                slidesPerView: 4,
                spaceBetween: 40
            }
        }
    });
</script>
<script>
    Get();
    function Get() {
        $.ajax({
            method: "GET",
            url: '/Image/Get',
            beforeSend: function() {
                $("#Table_Loader_Box").removeClass("d-none");
                $("#data").addClass("d-none");
            },
        }).done(function(response) {
            $("#data").html("").append(response).removeClass("d-none");
            $("#Table_Loader_Box").addClass("d-none");
        });
    }

    function Upload() {
        var fd = new FormData();
        fd.append('_token', $("input[name=_token]").val());
        var Images = $('#images')[0].files;
        for (var i = 0; i < Images.length; i++) {
            fd.append("images[]", Images[i], Images[i]['name']);
        }
        $.ajax({
            method: "POST",
            url: '/Image/Insert',
            processData: false,
            contentType: false,
            data: fd,
            beforeSend: function() {
                $('#Submit').attr('disabled', 'disabled').html(' ').append(`<div class="Button_Loader"></div>`);
            },
        })
        .done(function(response) {
            $('#Submit').removeAttr('disabled').html(' ').html('<i class="fa fa-upload fa-2x"></i>');
            Notification('success', 'mini', 'fa fa-check', 'bottom right', response.message);
            Get();
            $("#swiper-wrapper").find('div').remove();
        });
    }

    function Delete(event) {
        event.preventDefault();
        var all = $("#selected").val();
        arrayy = all.split("|");
        for (var i = arrayy.length -1; i > -1; i--) {
            if (arrayy[i] != '') {
                (function(j) {
                    var fd = new FormData();
                    fd.append('_token', $("input[name=_token]").val());
                    fd.append('code', arrayy[i]);

                    $.ajax({
                            method: "POST",
                            url: '/Image/Delete',
                            processData: false,
                            contentType: false,
                            data: fd,
                        })
                        .done(function(response) {
                            if (response.error == 'logout') {
                                location.assign("/dashboard/logout");
                            }
                        });
                })(i);
            }
        }
        $('.no').click();
        Get();
        $("#selected").val(null);
        Sweet_Alert('Congratulation', 'Images Deleted Successfully', 'success');
    }

    // Multiple Images Show
    const fileInput = document.getElementById('images');
    const images = document.getElementById('swiper-wrapper');

    // Listen to the change event on the <input> element
    fileInput.addEventListener('change', (event) => {
        // Get the selected image file
        const imageFiles = event.target.files;

        // Empty the images div
        images.innerHTML = '';
        if (imageFiles.length > 0) {
            // Loop through all the selected images
            for (const imageFile of imageFiles) {
                const reader = new FileReader();

                // Convert each image file to a string
                reader.readAsDataURL(imageFile);

                // FileReader will emit the load event when the data URL is ready
                // Access the string using reader.result inside the callback function
                reader.addEventListener('load', () => {
                    // Create new <img> element and add it to the DOM
                    images.innerHTML += `
                    <div class="swiper-slide">
                        <img src='${reader.result}'>
                    </div>
                `;
                });
            }
            if (imageFiles.length > 3) {
                $('.slider_next_button').removeClass('d-none');
                $('.slider_prev_button').removeClass('d-none');
            } else {
                $('.slider_next_button').addClass('d-none');
                $('.slider_prev_button').addClass('d-none');
            }
            if (imageFiles.length > 0) {
                $('#Submit').removeClass('d-none');
                $("#images_row").addClass('d-none');
            } else {
                $('#Submit').addClass('d-none');
                $("#images_row").removeClass('d-none');
            }
        } else {
            // Empty the images div
            images.innerHTML = '';
        }
    });
</script>
