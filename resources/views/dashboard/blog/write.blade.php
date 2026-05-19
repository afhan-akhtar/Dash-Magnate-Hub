@include('dashboard.attachments.header')
<!-- Link Swiper's CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<link rel="stylesheet" href="/dashboard/assets/plugins/summernote/dist/summernote-bs4.css" />

<style>
    .blog-write-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 24px 32px;
        margin-bottom: 24px;
        color: white;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
    }
    
    .blog-write-header h4 {
        color: white;
        font-weight: 600;
        margin: 0 0 8px 0;
        font-size: 24px;
    }
    
    .blog-write-header .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    
    .blog-write-header .breadcrumb-item {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
    }
    
    .blog-write-header .breadcrumb-item a {
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .blog-write-header .breadcrumb-item a:hover {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: underline;
    }
    
    .blog-write-header .breadcrumb-item.active {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .blog-write-header .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .modern-blog-card {
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
    
    .image-gallery-section {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
    }
    
    .swiper {
        width: 100%;
        height: 200px;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .swiper-slide:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }
    
    .swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .navigation-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-top: 16px;
    }
    
    .navigation-buttons .btn {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid #667eea;
        color: #667eea;
        background: white;
    }
    
    .navigation-buttons .btn:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .link-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }
    
    .link-section label {
        color: #495057;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
    }
    
    .input-group {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .input-group .form-control {
        border: 2px solid #e9ecef;
        padding: 12px 16px;
        font-size: 14px;
        font-family: monospace;
    }
    
    .input-group .form-control:focus {
        border-color: #667eea;
        box-shadow: none;
    }
    
    .input-group-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        cursor: pointer;
        padding: 12px 20px;
        transition: all 0.3s ease;
    }
    
    .input-group-text:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: scale(1.05);
    }
    
    .editor-section {
        margin-top: 24px;
    }
    
    .editor-section label {
        color: #495057;
        font-weight: 600;
        margin-bottom: 12px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
    }
    
    .note-editor {
        border-radius: 12px;
        border: 2px solid #e9ecef;
        overflow: hidden;
    }
    
    .note-editor:focus-within {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
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
    
    .info-badge {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border: 1px solid #667eea;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        color: #495057;
        font-size: 13px;
    }
    
    .info-badge i {
        color: #667eea;
        margin-right: 8px;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="blog-write-header">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-8">
                    <h4><i class="fa fa-pencil"></i> Write Blog Content</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/home">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/dashboard/blog">Blogs</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Write</li>
                    </ol>
                </div>
                <div class="col-lg-4 col-md-4 text-end">
                    <a href="/dashboard/blog">
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
        <div class="card modern-blog-card">
            <form onsubmit="Edit(event)">
                <!-- Image Gallery Section -->
                <div class="section-title">
                    <i class="fa fa-images"></i>
                    <span>Image Gallery</span>
                </div>
                
                <div class="info-badge">
                    <i class="fa fa-info-circle"></i>
                    <strong>Tip:</strong> Click on images to insert them into your blog content. Use the navigation buttons to browse through available images.
                </div>

                <div class="image-gallery-section">
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper" id="swiper-wrapper"></div>
                    </div>
                    
                    <div class="navigation-buttons">
                        <button type="button" class="btn Prev_Button">
                            <i class="fa fa-chevron-left"></i> Previous
                        </button>
                        <button type="button" class="btn Next_Button">
                            <i class="fa fa-chevron-right"></i> Next
                        </button>
                    </div>
                </div>

                <!-- Link Section -->
                <div class="section-title">
                    <i class="fa fa-link"></i>
                    <span>Image Link</span>
                </div>

                <div class="link-section">
                    <label><i class="fa fa-chain"></i> Copy Image Link</label>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Click an image to get its link" id="link" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text" id="copy-button" title="Copy to clipboard">
                                <i class="fa fa-copy"></i> Copy
                            </span>
                        </div>
                    </div>
                    <small style="color: #6c757d; font-size: 12px; margin-top: 8px; display: block;">
                        <i class="fa fa-lightbulb-o"></i> Click on an image above to get its URL, then click copy to use it in your blog
                    </small>
                </div>

                <!-- Editor Section -->
                <div class="section-title">
                    <i class="fa fa-edit"></i>
                    <span>Blog Content</span>
                </div>

                <div class="editor-section">
                    <label><i class="fa fa-file-text-o"></i> Write Your Blog Content</label>
                    <textarea id="blog">{{ $Edit[0]->blog }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="/dashboard/blog">
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
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="/dashboard/assets/plugins/summernote/dist/summernote-bs4.min.js"></script>
@csrf

<script>
    // Initialize Summernote
    $('#blog').summernote({
        height: 500,
        tabsize: 2,
        placeholder: 'Start writing your blog content here...',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Initialize Swiper
    var swiper = new Swiper(".mySwiper", {
        navigation: {
            nextEl: ".Next_Button",
            prevEl: ".Prev_Button",
        },
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        loop: true,
        breakpoints: {
            320: {
                slidesPerView: 2,
                spaceBetween: 15
            },
            480: {
                slidesPerView: 3,
                spaceBetween: 20
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 25
            },
            1024: {
                slidesPerView: 5,
                spaceBetween: 30
            }
        }
    });

    // Copy Link
    $('#copy-button').click(function() {
        var linkInput = $("#link");
        if (linkInput.val()) {
            linkInput.select();
            document.execCommand("copy");
            Notification('success', 'mini', 'fa fa-check', 'bottom right', 'Link Copied to Clipboard!');
            
            // Visual feedback
            $(this).html('<i class="fa fa-check"></i> Copied!');
            setTimeout(() => {
                $(this).html('<i class="fa fa-copy"></i> Copy');
            }, 2000);
        } else {
            Notification('warning', 'mini', 'fa fa-exclamation-triangle', 'bottom right', 'Please select an image first');
        }
    });

    // Load Images
    Get();

    function Get() {
        $.ajax({
            method: "GET",
            url: '/Blog/Get/Image',
            beforeSend: function() {
                $("#swiper-wrapper").html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
            },
        }).done(function(response) {
            $("#swiper-wrapper").html(response);
            $("#swiper-wrapper").removeClass("d-none");
            swiper.update();
        }).fail(function() {
            $("#swiper-wrapper").html('<div class="text-center p-4 text-danger"><i class="fa fa-exclamation-triangle"></i> Failed to load images</div>');
        });
    }

    // Save Blog
    function Edit(event) {
        event.preventDefault();
        
        var blogContent = $("#blog").val();
        if (!blogContent || blogContent.trim() === '') {
            Notification('warning', 'mini', 'fa fa-exclamation-triangle', 'bottom right', 'Please write some content');
            return;
        }
        
        var fd = new FormData();
        fd.append('_token', $("input[name=_token]").val());
        fd.append('blog', blogContent);
        fd.append('code', '<?php echo $code; ?>');

        $.ajax({
            method: "POST",
            url: '/Blog/Save',
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
                    location.assign("/dashboard/blog");
                }, 1500);
            }
        })
        .fail(function() {
            $('#Saved_Button').removeAttr('disabled').html('<i class="fa fa-check"></i> Save Changes');
            Notification('error', 'mini', 'fa fa-times', 'bottom right', 'An error occurred. Please try again.');
        });
    }
</script>
