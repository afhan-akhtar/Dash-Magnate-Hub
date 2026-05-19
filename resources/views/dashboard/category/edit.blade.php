@include('dashboard.attachments.header')

<style>
    .category-edit-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 24px 32px;
        margin-bottom: 24px;
        color: white;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
    }
    
    .category-edit-header h4 {
        color: white;
        font-weight: 600;
        margin: 0 0 8px 0;
        font-size: 24px;
    }
    
    .category-edit-header .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    
    .category-edit-header .breadcrumb-item {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
    }
    
    .category-edit-header .breadcrumb-item a {
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .category-edit-header .breadcrumb-item a:hover {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: underline;
    }
    
    .category-edit-header .breadcrumb-item.active {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .category-edit-header .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.6);
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
    
    .modern-category-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 32px;
    }
    
    .section-title {
        color: #2c3e50;
        font-weight: 600;
        font-size: 18px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .section-title i {
        color: #667eea;
        font-size: 22px;
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .form-label i {
        color: #667eea;
        font-size: 14px;
    }
    
    .form-label .required {
        color: #e74c3c;
        margin-left: 4px;
    }
    
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }
    
    .modern-image-upload {
        margin-bottom: 24px;
    }
    
    .modern-image-upload-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 12px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .modern-image-upload-label i {
        color: #667eea;
        font-size: 16px;
    }
    
    .modern-upload-area {
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        padding: 48px 32px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
        position: relative;
        min-height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modern-upload-area:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
        transform: translateY(-2px);
    }
    
    .modern-upload-area.dragover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.1);
        transform: scale(1.02);
    }
    
    .modern-upload-area.has-image {
        border-color: #38ef7d;
        background: rgba(56, 239, 125, 0.05);
    }
    
    .modern-upload-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }
    
    .modern-upload-icon {
        font-size: 64px;
        color: #667eea;
        margin-bottom: 20px;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .modern-upload-text {
        color: #495057;
        font-size: 16px;
        margin-bottom: 12px;
    }
    
    .modern-upload-text strong {
        color: #667eea;
        font-weight: 600;
    }
    
    .modern-upload-hint {
        color: #6c757d;
        font-size: 13px;
    }
    
    .modern-upload-preview {
        position: relative;
        max-width: 100%;
        z-index: 1;
    }
    
    .modern-upload-preview img {
        width: 100%;
        max-height: 250px;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .modern-upload-remove {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #e74c3c;
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
        box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
        z-index: 3;
    }
    
    .modern-upload-remove:hover {
        background: #c0392b;
        transform: scale(1.1) rotate(90deg);
    }
    
    .current-image-section {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        border-radius: 12px;
        padding: 24px;
    }
    
    .current-image-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 16px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .current-image-label i {
        color: #667eea;
        font-size: 16px;
    }
    
    .modern-image-preview-box {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        background: white;
        min-height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modern-image-preview-box img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: contain;
        max-height: 250px;
    }
    
    .action-buttons {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 32px;
        padding-top: 32px;
        border-top: 2px solid #e9ecef;
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
    
    .btn-cancel {
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        color: white;
    }
    
    .info-badge {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border: 1px solid #667eea;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 24px;
        color: #495057;
        font-size: 13px;
    }
    
    .info-badge i {
        color: #667eea;
        margin-right: 8px;
    }
    
    .category-icon-preview {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        color: white;
        font-size: 28px;
        margin-bottom: 12px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="category-edit-header">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-8">
                    <h4><i class="fa fa-tag"></i> Edit Category</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/home">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/dashboard/category">Categories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
                <div class="col-lg-4 col-md-4 text-end">
                    <a href="/dashboard/category">
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
        <div class="card modern-category-card">
            <div class="info-badge">
                <i class="fa fa-info-circle"></i>
                <strong>Note:</strong> Category name and image are used throughout the platform. Choose a clear, descriptive name and a relevant image.
            </div>

            <form onsubmit="Edit(event)">
                <!-- Category Information Section -->
                <div class="section-title">
                    <i class="fa fa-info-circle"></i>
                    <span>Category Information</span>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 mb-4">
                        <label class="form-label">
                            <i class="fa fa-tag"></i>
                            Category Name
                            <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" placeholder="Enter category name (e.g., Technology, Real Estate)" id="name" value="{{ $Edit[0]->name }}" required>
                        <small style="color: #6c757d; font-size: 12px; margin-top: 8px; display: block;">
                            <i class="fa fa-lightbulb-o"></i> Use a clear, descriptive name that users will easily understand
                        </small>
                    </div>
                </div>

                <!-- Category Image Section -->
                <div class="section-title">
                    <i class="fa fa-image"></i>
                    <span>Category Image</span>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="modern-image-upload">
                            <label class="modern-image-upload-label">
                                <i class="fa fa-cloud-upload"></i>
                                Upload New Image
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
                                        PNG, JPG, GIF up to 10MB<br>
                                        Recommended: Square image (500x500px)
                                    </div>
                                </div>
                            </div>
                            <small style="color: #6c757d; font-size: 12px; margin-top: 12px; display: block;">
                                <i class="fa fa-info-circle"></i> Leave empty to keep current image
                            </small>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="current-image-section">
                            <label class="current-image-label">
                                <i class="fa fa-picture-o"></i>
                                Current Image
                            </label>
                            <div class="modern-image-preview-box">
                                <img src="{{ asset('/uploads/category/card/'. $Edit[0]->card) }}" id="card_tag" alt="Current Category Image">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="/dashboard/category">
                        <button type="button" class="btn btn-cancel">
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
    // Form Submission
    function Edit(event) {
        event.preventDefault();
        
        // Validation
        var categoryName = $("#name").val();
        if (!categoryName || categoryName.trim() === '') {
            Notification('warning', 'mini', 'fa fa-exclamation-triangle', 'bottom right', 'Please enter a category name');
            $("#name").addClass('is-invalid');
            return;
        } else {
            $("#name").removeClass('is-invalid');
        }
        
        var fd = new FormData();
        fd.append('_token', $("input[name=_token]").val());
        fd.append('name', categoryName);
        fd.append('code', '<?php echo $code ?>');
        fd.append('id', '<?php echo $Edit[0]->id ?>');
        
        var card = $("#card")[0].files;
        for (var i = 0; i < card.length; i++) {
            fd.append("card[]", card[i], card[i]['name']);
        }

        $.ajax({
            method: "POST",
            url: '/Category/Update',
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
                    location.assign("/dashboard/category");
                }, 1500);
            }
        })
        .fail(function() {
            $('#Saved_Button').removeAttr('disabled').html('<i class="fa fa-check"></i> Save Changes');
            Notification('error', 'mini', 'fa fa-times', 'bottom right', 'An error occurred. Please try again.');
        });
    }

    // Image Upload Functions
    function readURL(input, previewId, uploadAreaId, uploadContentId) {
        if (input.files && input.files[0]) {
            var file = input.files[0];
            
            // Validate file size (10MB)
            if (file.size > 10 * 1024 * 1024) {
                Notification('warning', 'mini', 'fa fa-exclamation-triangle', 'bottom right', 'File size must be less than 10MB');
                $('#' + input.id).val('');
                return;
            }
            
            // Validate file type
            if (!file.type.match('image.*')) {
                Notification('warning', 'mini', 'fa fa-exclamation-triangle', 'bottom right', 'Please upload an image file');
                $('#' + input.id).val('');
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
        $("#card_tag").attr('src', '{{ asset('/uploads/category/card/'. $Edit[0]->card) }}');
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

    // Click anywhere on upload area to trigger file input
    $('#card_upload_area').on('click', function(e) {
        if (!$(e.target).hasClass('modern-upload-remove') && !$(e.target).closest('.modern-upload-remove').length) {
            $('#card').click();
        }
    });
</script>
