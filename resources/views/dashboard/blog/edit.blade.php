@include('dashboard.attachments.header')
<div class="row pt-2 pb-2">
    <div class="col-8">
        <h4 class="page-title">Blogs</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard/login">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/dashboard/blog">Blogs</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </div>
    <div class="col-4">
        <div class="d-flex justify-content-end">
            <a href="/dashboard/blog"><button class="btn btn-primary waves-effect waves-light m-1"
                    title="Back To Listing"><i aria-hidden="true"
                        class="fa fa-arrow-circle-o-left fa-2x"></i></button></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form onsubmit="Edit(event)">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <input type="text" class="form-control" placeholder="Write Blog Title" id="name" value="{{ $Edit[0]->name }}" required>
                    </div>
                    <div class="col-lg-12 col-md-12 mt-2">
                        <textarea id="description" rows="5" class="form-control" placeholder="Write Card Description">{{ $Edit[0]->description }}</textarea>
                    </div>
                    <div class="col-lg-6 col-md-6 mt-2">
                        <label class="form-label" style="font-weight: 600;">Blog Category</label>
                        <select class="form-control" id="blog_category_ids" multiple size="4">
                            @foreach($blog_categories as $cat)
                            <option value="{{ $cat->blog_category_id }}" {{ in_array($cat->blog_category_id, $selected_categories ?? []) ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                    </div>
                    <div class="col-lg-6 col-md-6 mt-2">
                        <label class="form-label" style="font-weight: 600;">Blog Tags</label>
                        <select class="form-control" id="blog_tag_ids" multiple size="6">
                            @foreach($blog_tags as $tag)
                            <option value="{{ $tag->blog_tag_id }}" {{ in_array($tag->blog_tag_id, $selected_tags ?? []) ? 'selected' : '' }}>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                    </div>
                    <div class="col-lg-6 col-md-12 mt-2">
                        <input type="text" class="form-control" placeholder="Write Writter Name" id="writter_name" value="{{ $Edit[0]->writter_name }}" required>
                    </div>
                    <div class="col-6 mt-2">
                        <div class="modern-image-upload">
                            <label class="modern-image-upload-label">
                                Writer Image
                            </label>
                            <div class="modern-upload-area compact" id="writter_image_upload_area">
                                <input type="file" class="modern-upload-input" id="writter_image" accept="image/png, image/gif, image/jpeg">
                                <div class="modern-upload-preview" id="writter_image_preview" style="display: none;">
                                    <img id="writter_tag_new" src="" alt="Preview">
                                    <button type="button" class="modern-upload-remove" onclick="removeImage('writter_image', 'writter_image_preview', 'writter_image_upload_area', 'writter_image_upload_content')">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <div class="modern-upload-content" id="writter_image_upload_content">
                                    <i class="fa fa-cloud-upload modern-upload-icon"></i>
                                    <div class="modern-upload-text">
                                        <strong>Click to upload</strong>
                                    </div>
                                </div>
                            </div>
                            <small style="color: #6c757d; font-size: 0.75rem; margin-top: 0.5rem; display: block;">Leave empty to keep current image</small>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="modern-image-upload">
                            <label class="modern-image-upload-label">
                                Card Image
                            </label>
                            <div class="modern-upload-area compact" id="card_upload_area">
                                <input type="file" class="modern-upload-input" id="card" accept="image/png, image/gif, image/jpeg">
                                <div class="modern-upload-preview" id="card_preview" style="display: none;">
                                    <img id="card_tag_new" src="" alt="Preview">
                                    <button type="button" class="modern-upload-remove" onclick="removeImage('card', 'card_preview', 'card_upload_area', 'card_upload_content')">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <div class="modern-upload-content" id="card_upload_content">
                                    <i class="fa fa-cloud-upload modern-upload-icon"></i>
                                    <div class="modern-upload-text">
                                        <strong>Click to upload</strong>
                                    </div>
                                </div>
                            </div>
                            <small style="color: #6c757d; font-size: 0.75rem; margin-top: 0.5rem; display: block;">Leave empty to keep current image</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Current Images</label>
                        <div class="d-flex justify-content-center gap-3">
                            <div class="modern-image-preview-box" style="flex: 1;">
                                <img src="{{ asset('/uploads/blog/writter_image/'. $Edit[0]->writter_image) }}" id="writter_tag" alt="Writer Image">
                            </div>
                            <div class="modern-image-preview-box" style="flex: 1;">
                                <img src="{{ asset('/uploads/blog/card/'. $Edit[0]->card) }}" id="card_tag" alt="Card Image">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success" id="Saved_Button"><i class="fa fa-check-square-o"></i> Save Changes</button>
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
        event.preventDefault(); ALL = ['name','description','writter_name']; var fd = new FormData(); fd.append('_token', $("input[name=_token]").val()); fd.append('id', '<?php echo $Edit[0]->id ?>');
        for (let i = 0; i < ALL.length; i++) {fd.append(ALL[i], $("#" + ALL[i]).val());} fd.append('code', '<?php echo $code ?>');
        var blog_category_ids = $("#blog_category_ids").val();
        if (blog_category_ids && blog_category_ids.length) {
            blog_category_ids.forEach(function(id) { fd.append('blog_category_ids[]', id); });
        }
        var blog_tag_ids = $("#blog_tag_ids").val();
        if (blog_tag_ids && blog_tag_ids.length) {
            blog_tag_ids.forEach(function(id) { fd.append('blog_tag_ids[]', id); });
        }
        var card = $("#card")[0].files; for (var i = 0; i < card.length; i++) {fd.append("card[]", card[i], card[i]['name']);}
        var writter_image = $("#writter_image")[0].files; for (var i = 0; i < writter_image.length; i++) {fd.append("writter_image[]", writter_image[i], writter_image[i]['name']);}

        $.ajax({
            method: "POST",
            url: '/Blog/Update',
            processData: false,
            contentType: false,
            data: fd,
            beforeSend: function() {
                $('#Saved_Button').attr('disabled', 'disabled').html(' ').append(`<div class="Button_Loader"></div>`);
            },
        })
        .done(function(response) {
            if (response.error == true) {
                location.assign("/dashboard/login");
            }
            if (response.error == false) {
                Notification('success', 'mini', 'fa fa-check', 'bottom right', response.message);
                $('#Saved_Button').removeAttr('disabled').html('<i class="fa fa-check-square-o"></i> Save Changes');
            }
        });
    }
    // Modern Image Upload Functions
    function readURL(input, previewId, uploadAreaId, uploadContentId, currentImageId) {
        if (input.files && input.files[0]) {
            var file = input.files[0];
            var reader = new FileReader();

            reader.onload = function (e) {
                $(previewId).find('img').attr('src', e.target.result);
                $(previewId).show();
                $(uploadContentId).hide();
                $(uploadAreaId).addClass('has-image');
                // Also update the current image preview
                if (currentImageId) {
                    $(currentImageId).attr('src', e.target.result);
                }
            }

            reader.readAsDataURL(file);
        }
    }

    function removeImage(inputId, previewId, uploadAreaId, uploadContentId) {
        $('#' + inputId).val('');
        $('#' + previewId).hide();
        $('#' + uploadContentId).show();
        $('#' + uploadAreaId).removeClass('has-image');
        // Restore original images
        if (inputId === 'card') {
            $("#card_tag").attr('src', '{{ asset('/uploads/blog/card/'. $Edit[0]->card) }}');
        } else if (inputId === 'writter_image') {
            $("#writter_tag").attr('src', '{{ asset('/uploads/blog/writter_image/'. $Edit[0]->writter_image) }}');
        }
    }

    // Card Image Drag and Drop
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
            readURL($('#card')[0], '#card_preview', '#card_upload_area', '#card_upload_content', '#card_tag');
        }
    });

    $("#card").change(function() {
        readURL(this, '#card_preview', '#card_upload_area', '#card_upload_content', '#card_tag');
    });

    // Writer Image Drag and Drop
    $('#writter_image_upload_area').on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dragover');
    });

    $('#writter_image_upload_area').on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
    });

    $('#writter_image_upload_area').on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
        var files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            $('#writter_image')[0].files = files;
            readURL($('#writter_image')[0], '#writter_image_preview', '#writter_image_upload_area', '#writter_image_upload_content', '#writter_tag');
        }
    });

    $("#writter_image").change(function() {
        readURL(this, '#writter_image_preview', '#writter_image_upload_area', '#writter_image_upload_content', '#writter_tag');
    });
</script>
