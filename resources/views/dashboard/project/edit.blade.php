@include('dashboard.attachments.header')

<style>
    .project-edit-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 24px 32px;
        margin-bottom: 24px;
        color: white;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
    }
    
    .project-edit-header h4 {
        color: white;
        font-weight: 600;
        margin: 0 0 8px 0;
        font-size: 24px;
    }
    
    .project-edit-header .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    
    .project-edit-header .breadcrumb-item {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
    }
    
    .project-edit-header .breadcrumb-item a {
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .project-edit-header .breadcrumb-item a:hover {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: underline;
    }
    
    .project-edit-header .breadcrumb-item.active {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .project-edit-header .breadcrumb-item + .breadcrumb-item::before {
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
    
    .modern-project-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 32px;
    }
    
    .form-section {
        margin-bottom: 32px;
        padding-bottom: 32px;
        border-bottom: 2px solid #e9ecef;
    }
    
    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .section-title {
        color: #2c3e50;
        font-weight: 600;
        font-size: 18px;
        margin-bottom: 20px;
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
        gap: 6px;
    }
    
    .form-label i {
        color: #667eea;
        font-size: 14px;
    }
    
    .form-label .required {
        color: #e74c3c;
        margin-left: 4px;
    }
    
    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }
    
    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }
    
    select.form-control {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23667eea' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px 12px;
        padding-right: 40px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
    }
    
    select.form-control option {
        padding: 10px;
    }
    
    select.form-control:disabled {
        background-color: #f8f9fa;
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    .modern-image-upload {
        margin-top: 20px;
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
        padding: 32px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
        position: relative;
    }
    
    .modern-upload-area:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
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
    }
    
    .modern-upload-icon {
        font-size: 48px;
        color: #667eea;
        margin-bottom: 16px;
    }
    
    .modern-upload-text {
        color: #495057;
        font-size: 14px;
        margin-bottom: 8px;
    }
    
    .modern-upload-text strong {
        color: #667eea;
        font-weight: 600;
    }
    
    .modern-upload-hint {
        color: #6c757d;
        font-size: 12px;
    }
    
    .modern-upload-preview {
        position: relative;
        max-width: 300px;
        margin: 0 auto;
    }
    
    .modern-upload-preview img {
        width: 100%;
        height: auto;
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
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
    }
    
    .modern-upload-remove:hover {
        background: #c0392b;
        transform: scale(1.1);
    }
    
    .current-image-section {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        border-radius: 12px;
        padding: 24px;
        margin-top: 24px;
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
    }
    
    .modern-image-preview-box img {
        width: 100%;
        height: auto;
        display: block;
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
    
    .form-row-spacing {
        margin-bottom: 20px;
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

    .form-control, .form-select {
        padding: 0 0 0 15px;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="project-edit-header">
            <div class="row align-items-center">
                <div class="col-lg-10 col-md-10">
                    <h4><i class="fa fa-edit"></i> Edit Listing</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/home">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/dashboard/project">Listings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
                <div class="col-lg-2 col-md-2 text-end">
                    <a href="/dashboard/project">
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
        <div class="card modern-project-card">
            <div class="info-badge">
                <i class="fa fa-info-circle"></i>
                <strong>Note:</strong> All fields marked with <span style="color: #e74c3c;">*</span> are required. Make sure to fill them accurately.
            </div>

            <form onsubmit="Edit(event)">
                <!-- Basic Information Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fa fa-info-circle"></i>
                        <span>Basic Information</span>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 form-row-spacing">
                            <label class="form-label">
                                <i class="fa fa-tag"></i>
                                Category
                                <span class="required">*</span>
                            </label>
                            <select class="form-control" id="category_id" required>
                                <option value="">-- Select Category --</option>
                            </select>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 form-row-spacing">
                            <label class="form-label">
                                <i class="fa fa-map-marker"></i>
                                Location
                                <span class="required">*</span>
                            </label>
                            <select class="form-control" id="location_id" required>
                                <option value="">-- Select Location --</option>
                            </select>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 form-row-spacing">
                            <label class="form-label">
                                <i class="fa fa-globe"></i>
                                Region
                            </label>
                            <select class="form-control" id="region">
                                <option value="">-- Select Region --</option>
                            </select>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 form-row-spacing">
                            <label class="form-label">
                                <i class="fa fa-briefcase"></i>
                                Listing Name
                                <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" placeholder="Enter listing name" id="name" value="{{ $Edit[0]->name ?? '' }}" required>
                        </div>
                    </div>
                </div>

                <!-- Financial Information Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fa fa-dollar"></i>
                        <span>Financial Information</span>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-12 form-row-spacing">
                            <label class="form-label">
                                <i class="fa fa-money"></i>
                                Price
                                <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" placeholder="e.g. 250000, POA, or EOI" id="price" value="{{ $Edit[0]->price ?? '' }}" inputmode="text" required>
                        </div>

                        <div class="col-lg-6 col-md-12 form-row-spacing">
                            <label class="form-label">
                                <i class="fa fa-line-chart"></i>
                                Earning Type
                            </label>
                            <input type="text" class="form-control" placeholder="e.g., EBITDA, PETIBDA" id="earning_type" value="{{ $Edit[0]->earning_type ?? '' }}">
                        </div>

                        <div class="col-lg-6 col-md-12 form-row-spacing">
                            <label class="form-label">
                                <i class="fa fa-calendar"></i>
                                Years Operating
                                <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" placeholder="e.g., 5 years" id="trading" value="{{ $Edit[0]->trading ?? '' }}" required>
                        </div>

                        <div class="col-lg-6 col-md-12 form-row-spacing">
                            <label class="form-label">
                                <i class="fa fa-cubes"></i>
                                Stock Level
                            </label>
                            <input type="text" class="form-control" placeholder="Enter stock level" id="stock_level" value="{{ $Edit[0]->stock_level ?? '' }}">
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fa fa-file-text-o"></i>
                        <span>Description & Details</span>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 form-row-spacing">
                            <label class="form-label">
                                <i class="fa fa-align-left"></i>
                                Summary
                                <span class="required">*</span>
                            </label>
                            <textarea id="summary" placeholder="Enter a brief business summary" class="form-control" required>{{ $Edit[0]->summary ?? '' }}</textarea>
                        </div>

                        <div class="col-lg-12 col-md-12 form-row-spacing">
                            <label class="form-label">
                                <i class="fa fa-file-text"></i>
                                Description
                            </label>
                            <textarea id="description" placeholder="Enter detailed description" class="form-control">{{ $Edit[0]->description ?? '' }}</textarea>
                        </div>

                        <div class="col-lg-12 col-md-12 form-row-spacing">
                            <label class="form-label">
                                <i class="fa fa-location-arrow"></i>
                                Location Information
                            </label>
                            <textarea id="location_information" placeholder="Enter location details and accessibility information" class="form-control">{{ $Edit[0]->location_information ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Image Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fa fa-image"></i>
                        <span>Listing Image</span>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-12">
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
                                            PNG, JPG, GIF up to 10MB
                                        </div>
                                    </div>
                                </div>
                                <small style="color: #6c757d; font-size: 12px; margin-top: 12px; display: block;">
                                    <i class="fa fa-info-circle"></i> Leave empty to keep current image
                                </small>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <div class="current-image-section">
                                <label class="current-image-label">
                                    <i class="fa fa-picture-o"></i>
                                    Current Image
                                </label>
                                <div class="modern-image-preview-box">
                                    @if(isset($Edit[0]->card) && $Edit[0]->card)
                                        <img src="{{ asset('/uploads/project/card/'. $Edit[0]->card) }}" id="card_tag" alt="Current Image">
                                    @else
                                        <div style="padding: 40px; text-align: center; color: #6c757d;">
                                            <i class="fa fa-image" style="font-size: 48px; margin-bottom: 10px;"></i>
                                            <p>No image uploaded</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="/dashboard/project">
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
    // Load Categories and Locations
    Get_Category();
    Get_Location();

    function Get_Category() {
        $("#category_id").html('<option value="">Loading...</option>').attr('disabled', 'disabled');
        $.ajax({
            method: "GET",
            url: '/Project/Get/Category',
        })
        .done(function(response) {
            category = response.category;
            var category_id = '<?php echo $Edit[0]->category_id ?? '' ?>';
            if (category.length > 0) {
                $("#category_id").removeAttr('disabled').html('<option value="">-- Select Category --</option>');
                category.forEach(function(c) {
                    var selected = (category_id == c.category_id) ? 'selected' : '';
                    var name = c.name ? c.name.replace(/_/g, ' ') : '';
                    $("#category_id").append(`<option value="${c.category_id}" ${selected}>${name}</option>`);
                });
            } else {
                $("#category_id").html("").append(`<option value="">No Category Found</option>`);
            }
        })
        .fail(function() {
            $("#category_id").html('<option value="">Failed to load categories</option>');
            Notification('error', 'mini', 'fa fa-times', 'bottom right', 'Failed to load categories');
        });
    }

    function Get_Location() {
        $("#location_id").html('<option value="">Loading...</option>').attr('disabled', 'disabled');
        $.ajax({
            method: "GET",
            url: '/Project/Get/Location',
        })
        .done(function(response) {
            locations = response.location;
            var location_id = '<?php echo $Edit[0]->location_id ?? '' ?>';
            var region_id = '<?php echo $Edit[0]->region_id ?? '' ?>';
            if (locations.length > 0) {
                $("#location_id").removeAttr('disabled').html('<option value="">-- Select Location --</option>');
                locations.forEach(function(l) {
                    var selected = (location_id == l.location_id) ? 'selected' : '';
                    var name = l.name ? l.name : '';
                    $("#location_id").append(`<option value="${l.location_id}" ${selected}>${name}</option>`);
                });
                
                // Load regions if location is already selected
                if (location_id) {
                    Get_Regions(location_id, region_id);
                }
            } else {
                $("#location_id").html("").append(`<option value="">No Location Found</option>`);
            }
        })
        .fail(function() {
            $("#location_id").html('<option value="">Failed to load locations</option>');
            Notification('error', 'mini', 'fa fa-times', 'bottom right', 'Failed to load locations');
        });
    }

    // Load regions when location changes
    $(document).on('change', '#location_id', function() {
        var locationId = $(this).val();
        if (locationId) {
            Get_Regions(locationId);
        } else {
            $("#region").html('<option value="">-- Select Region --</option>');
        }
    });

    function Get_Regions(locationId, selectedRegionId) {
        $("#region").html('<option value="">Loading...</option>').attr('disabled', 'disabled');
        $.ajax({
            url: '/get-regions',
            type: 'POST',
            data: {
                stateId: locationId,
                _token: $('input[name="_token"]').val()
            },
            dataType: 'json',
        })
        .done(function(json) {
            var options = '<option value="">-- Select Region --</option>';
            if (json && json.length > 0) {
                for (var i = 0; i < json.length; i++) {
                    var selected = (selectedRegionId && json[i].id == selectedRegionId) ? 'selected' : '';
                    var name = json[i].name ? json[i].name : '';
                    options += '<option value="' + json[i].id + '" ' + selected + '>' + name + '</option>';
                }
            }
            $("#region").html(options).removeAttr('disabled');
        })
        .fail(function(xhr, ajaxOptions, thrownError) {
            console.log('Error loading regions:', thrownError);
            $("#region").html('<option value="">Failed to load regions</option>').removeAttr('disabled');
        });
    }

    // Form Submission
    function Edit(event) {
        event.preventDefault();
        
        // Validation (Broker & Franchise: mandatory only Price, Location, Category, Years Trading, Title, Summary)
        var requiredFields = ["name", "price", "trading", "summary", "location_id", "category_id"];
        var allFields = ["name", "price", "trading", "earning_type", "stock_level", "summary", "location_information", "description", "location_id", "category_id"];
        var isValid = true;
        
        requiredFields.forEach(function(field) {
            var value = $("#" + field).val();
            if (!value || value.trim() === '' || value === '0') {
                isValid = false;
                $("#" + field).addClass('is-invalid');
            } else {
                $("#" + field).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            Notification('warning', 'mini', 'fa fa-exclamation-triangle', 'bottom right', 'Please fill all required fields');
            return;
        }
        
        var fd = new FormData();
        fd.append('_token', $("input[name=_token]").val());
        fd.append('id', '<?php echo $Edit[0]->id ?? '' ?>');
        fd.append('code', '<?php echo $code ?? '' ?>');
        
        allFields.forEach(function(field) {
            fd.append(field, $("#" + field).val() || '');
        });
        // Add region if selected
        var regionValue = $("#region").val();
        if (regionValue) {
            fd.append('region_id', regionValue);
        }
        
        var card = $("#card")[0].files;
        for (var i = 0; i < card.length; i++) {
            fd.append("card[]", card[i], card[i]['name']);
        }

        $.ajax({
            method: "POST",
            url: '/Project/Update',
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
                    location.assign("/dashboard/project");
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
                return;
            }
            
            // Validate file type
            if (!file.type.match('image.*')) {
                Notification('warning', 'mini', 'fa fa-exclamation-triangle', 'bottom right', 'Please upload an image file');
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
        @if(isset($Edit[0]->card) && $Edit[0]->card)
            $("#card_tag").attr('src', '{{ asset('/uploads/project/card/'. $Edit[0]->card) }}');
        @endif
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
