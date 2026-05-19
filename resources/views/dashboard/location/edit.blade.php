@include('dashboard.attachments.header')

<style>
    .edit-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 24px 32px;
        margin-bottom: 24px;
        color: white;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
    }
    
    .edit-header h4 {
        color: white;
        font-weight: 600;
        margin: 0 0 8px 0;
        font-size: 24px;
    }
    
    .edit-header .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    
    .edit-header .breadcrumb-item {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
    }
    
    .edit-header .breadcrumb-item a {
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .edit-header .breadcrumb-item a:hover {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: underline;
    }
    
    .edit-header .breadcrumb-item.active {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .edit-header .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .modern-edit-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 32px;
    }
    
    .form-section-title {
        color: #2c3e50;
        font-weight: 600;
        font-size: 18px;
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .form-section-title i {
        color: #667eea;
        font-size: 22px;
    }
    
    .form-label {
        color: #495057;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .form-control {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        padding: 12px 16px;
        transition: all 0.3s ease;
        font-size: 15px;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }
    
    .modern-image-upload {
        margin-bottom: 24px;
    }
    
    .modern-image-upload-label {
        color: #495057;
        font-weight: 600;
        margin-bottom: 12px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
    }
    
    .modern-upload-area {
        border: 3px dashed #667eea;
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }
    
    .modern-upload-area:hover {
        border-color: #764ba2;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        transform: scale(1.02);
    }
    
    .modern-upload-area.dragover {
        border-color: #38ef7d;
        background: linear-gradient(135deg, rgba(56, 239, 125, 0.1) 0%, rgba(17, 153, 142, 0.1) 100%);
    }
    
    .modern-upload-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }
    
    .modern-upload-icon {
        font-size: 48px;
        color: #667eea;
        margin-bottom: 16px;
        display: block;
    }
    
    .modern-upload-text {
        color: #2c3e50;
        font-size: 16px;
        margin-bottom: 8px;
    }
    
    .modern-upload-hint {
        color: #6c757d;
        font-size: 13px;
    }
    
    .modern-upload-preview {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .modern-upload-preview img {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
        border-radius: 12px;
    }
    
    .modern-upload-remove {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }
    
    .modern-upload-remove:hover {
        background: #c82333;
        transform: scale(1.1);
    }
    
    .current-image-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-top: 24px;
    }
    
    .current-image-section .form-label {
        margin-bottom: 16px;
    }
    
    .modern-image-preview-box {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        background: white;
        padding: 8px;
    }
    
    .modern-image-preview-box img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
        display: block;
    }
    
    .btn-back {
        background: white;
        color: #667eea;
        border: 2px solid white;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-back:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
        color: #667eea;
    }
    
    .btn-save {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 32px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(17, 153, 142, 0.4);
        color: white;
    }
    
    .action-buttons {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 32px;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="edit-header">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-8">
                    <h4><i class="fa fa-map-marker"></i> Edit Location</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/home">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/dashboard/location">Locations</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
                <div class="col-lg-4 col-md-4 text-end">
                    <a href="/dashboard/location">
                        <button class="btn btn-back" title="Back To Listing">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card modern-edit-card">
            <form onsubmit="Edit(event)">
                <div class="form-section-title">
                    <i class="fa fa-info-circle"></i>
                    <span>Location Information</span>
                </div>

                <div class="row">
                    <div class="col-12 mb-4">
                        <label class="form-label">Location Name <span style="color: #dc3545;">*</span></label>
                        <div class="input-group-icon">
                            <input type="text" class="form-control" placeholder="Enter location name (e.g., Sydney, Melbourne)" id="name" value="{{ $Edit[0]->name }}" required>
                        </div>
                        <small style="color: #6c757d; font-size: 12px; margin-top: 4px; display: block;">
                            <i class="fa fa-info-circle"></i> This name will be displayed across the platform
                        </small>
                    </div>

                    <div class="col-12">
                        <div class="modern-image-upload">
                            <label class="modern-image-upload-label">
                                <i class="fa fa-image"></i> Location Card Image
                            </label>
                            <div class="modern-upload-area" id="card_upload_area">
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
                                        <strong>Click to upload</strong> or drag and drop
                                    </div>
                                    <div class="modern-upload-hint">
                                        PNG, JPG, GIF up to 10MB | Recommended: 800x600px
                                    </div>
                                </div>
                            </div>
                            <small style="color: #6c757d; font-size: 12px; margin-top: 8px; display: block;">
                                <i class="fa fa-lightbulb-o"></i> Leave empty to keep the current image
                            </small>
                        </div>
                    </div>
                </div>

                <div class="current-image-section">
                    <label class="form-label"><i class="fa fa-picture-o"></i> Current Image</label>
                    <div class="row">
                        <div class="col-lg-6 col-md-8 col-sm-12">
                            <div class="modern-image-preview-box">
                                <img src="{{ asset('/uploads/location/card/'. $Edit[0]->card) }}" id="card_tag" alt="Current Location Image">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="/dashboard/location">
                        <button type="button" class="btn btn-secondary">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                    </a>
                    <button type="submit" class="btn btn-save" id="Saved_Button">
                        <i class="fa fa-check"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('dashboard.attachments.footer')
@csrf

<script>
    function Edit(event) {
        event.preventDefault();
        
        var fd = new FormData();
        fd.append('_token', $("input[name=_token]").val());
        fd.append('name', $("#name").val());
        fd.append('code', '<?php echo $code ?>');
        fd.append('id', '<?php echo $Edit[0]->id ?>');
        
        var card = $("#card")[0].files;
        for (var i = 0; i < card.length; i++) {
            fd.append("card[]", card[i], card[i]['name']);
        }
        
        $.ajax({
            method: "POST",
            url: '/Location/Update',
            processData: false,
            contentType: false,
            data: fd,
            beforeSend: function() {
                $('#Saved_Button').attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            },
        })
        .done(function(response) {
            if (response.error == true) {
                location.assign("/dashboard/login");
            }
            if (response.error == false) {
                $('#Saved_Button').removeAttr('disabled').html('<i class="fa fa-check"></i> Save Changes');
                Notification('success', 'mini', 'fa fa-check', 'bottom right', response.message);
                setTimeout(function() {
                    location.assign("/dashboard/location");
                }, 1500);
            }
        })
        .fail(function() {
            $('#Saved_Button').removeAttr('disabled').html('<i class="fa fa-check"></i> Save Changes');
            Notification('error', 'mini', 'fa fa-times', 'bottom right', 'An error occurred. Please try again.');
        });
    }

    function readURL(input, previewId, uploadAreaId, uploadContentId) {
        if (input.files && input.files[0]) {
            var file = input.files[0];
            
            // Validate file size (10MB)
            if (file.size > 10 * 1024 * 1024) {
                Notification('warning', 'mini', 'fa fa-exclamation-triangle', 'bottom right', 'File size must be less than 10MB');
                return;
            }
            
            var reader = new FileReader();
            reader.onload = function (e) {
                $(previewId).find('img').attr('src', e.target.result);
                $(previewId).show();
                $(uploadContentId).hide();
                $(uploadAreaId).addClass('has-image');
                $("#card_tag").attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage(inputId, previewId, uploadAreaId, uploadContentId) {
        $('#' + inputId).val('');
        $('#' + previewId).hide();
        $('#' + uploadContentId).show();
        $('#' + uploadAreaId).removeClass('has-image');
        $("#card_tag").attr('src', '{{ asset('/uploads/location/card/'. $Edit[0]->card) }}');
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

