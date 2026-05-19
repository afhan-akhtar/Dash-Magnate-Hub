@include('dashboard.attachments.header')

<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 24px 32px;
        margin-bottom: 24px;
        color: white;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
    }
    
    .profile-header h4 {
        color: white;
        font-weight: 600;
        margin: 0 0 8px 0;
        font-size: 24px;
    }
    
    .profile-header .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    
    .profile-header .breadcrumb-item {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
    }
    
    .profile-header .breadcrumb-item a {
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .profile-header .breadcrumb-item a:hover {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: underline;
    }
    
    .profile-header .breadcrumb-item.active {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .profile-header .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .action-btn {
        background: white;
        color: #667eea;
        border: 2px solid white;
        border-radius: 8px;
        padding: 10px 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        margin: 0 4px;
    }
    
    .action-btn:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
        color: #667eea;
    }
    
    .action-btn i {
        font-size: 18px;
    }
    
    .modern-profile-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .profile-avatar-section {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        padding: 48px 32px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 500px;
    }
    
    .profile-avatar {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-avatar i {
        font-size: 80px;
        color: white;
    }
    
    .profile-name-display {
        text-align: center;
        margin-top: 16px;
    }
    
    .profile-name-display h3 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 28px;
    }
    
    .profile-name-display p {
        color: #6c757d;
        font-size: 14px;
        margin: 0;
    }
    
    .profile-info-section {
        padding: 48px 32px;
    }
    
    .info-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .info-card:hover {
        background: white;
        border-color: #667eea;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }
    
    .info-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    
    .info-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }
    
    .info-card-label {
        color: #6c757d;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    
    .info-card-value {
        color: #2c3e50;
        font-size: 16px;
        font-weight: 500;
        margin: 0;
        padding-left: 52px;
    }
    
    .edit-btn-inline {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        float: right;
    }
    
    .edit-btn-inline:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        color: white;
    }
    
    /* Modern Modal Styles */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 8px 32px rgba(0,0,0,0.15);
    }
    
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 20px 24px;
    }
    
    .modal-header .modal-title {
        font-weight: 600;
        font-size: 18px;
    }
    
    .modal-header .close {
        color: white;
        opacity: 0.8;
        text-shadow: none;
    }
    
    .modal-header .close:hover {
        opacity: 1;
    }
    
    .modal-body {
        padding: 32px 24px;
    }
    
    .modal-footer {
        border-top: 2px solid #e9ecef;
        padding: 16px 24px;
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
    
    .has-icon-right .form-control {
        padding-right: 45px;
    }
    
    .has-icon-right .form-control-position {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    
    .has-icon-right .form-control-position:hover {
        color: #667eea;
    }
    
    .btn-save-modal {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-save-modal:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(17, 153, 142, 0.4);
        color: white;
    }
    
    .btn-cancel-modal {
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-cancel-modal:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
    }
    
    .password-requirements {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        border: 1px solid #667eea;
        border-radius: 8px;
        padding: 16px;
        margin-top: 16px;
        font-size: 13px;
    }
    
    .password-requirements h6 {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 12px;
        font-size: 14px;
    }
    
    .password-requirements ul {
        margin: 0;
        padding-left: 20px;
        color: #495057;
    }
    
    .password-requirements li {
        margin-bottom: 6px;
    }
</style>

<!-- Change Name Modal -->
<div class="modal fade" id="Name_Model">
    <div class="modal-dialog" style="max-width:500px;">
        <div class="modal-content animated slideInUp">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-user"></i> Change Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="Change_Name(event)">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label style="font-weight: 600; color: #495057; margin-bottom: 8px; font-size: 13px;">
                                <i class="fa fa-user"></i> Full Name
                            </label>
                            <input type="text" class="form-control" placeholder="Enter your full name" id="name" value="{{ session()->get('name') }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-save-modal Saved_Button">
                        <i class="fa fa-check"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Email Modal -->
<div class="modal fade" id="Email_Model">
    <div class="modal-dialog" style="max-width:500px;">
        <div class="modal-content animated slideInUp">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-envelope"></i> Change Email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="Change_Email(event)">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label style="font-weight: 600; color: #495057; margin-bottom: 8px; font-size: 13px;">
                                <i class="fa fa-envelope"></i> Email Address
                            </label>
                            <input type="email" class="form-control" placeholder="Enter your email address" id="email" value="{{ session()->get('email') }}" required>
                            <small style="color: #6c757d; font-size: 12px; margin-top: 8px; display: block;">
                                <i class="fa fa-info-circle"></i> You will be logged out after changing your email
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-save-modal Saved_Button">
                        <i class="fa fa-check"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="Password_Model">
    <div class="modal-dialog" style="max-width:600px;">
        <div class="modal-content animated slideInUp">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-lock"></i> Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="Change_Password(event)">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label style="font-weight: 600; color: #495057; margin-bottom: 8px; font-size: 13px;">
                                <i class="fa fa-key"></i> Current Password
                            </label>
                            <div class="position-relative has-icon-right">
                                <input type="password" id="current_password" class="form-control" placeholder="Enter current password" required>
                                <div class="form-control-position">
                                    <i class="fa fa-eye" onclick="Type_Fuction_1()"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label style="font-weight: 600; color: #495057; margin-bottom: 8px; font-size: 13px;">
                                <i class="fa fa-lock"></i> New Password
                            </label>
                            <div class="position-relative has-icon-right">
                                <input type="password" id="password" class="form-control" placeholder="Enter new password" required>
                                <div class="form-control-position">
                                    <i class="fa fa-eye" onclick="Type_Fuction_2()"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label style="font-weight: 600; color: #495057; margin-bottom: 8px; font-size: 13px;">
                                <i class="fa fa-check-circle"></i> Confirm Password
                            </label>
                            <div class="position-relative has-icon-right">
                                <input type="password" id="confirm_password" class="form-control" placeholder="Confirm new password" required>
                                <div class="form-control-position">
                                    <i class="fa fa-eye" onclick="Type_Fuction_3()"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="password-requirements">
                        <h6><i class="fa fa-shield"></i> Password Requirements:</h6>
                        <ul>
                            <li>At least 6 characters long</li>
                            <li>Contains at least one uppercase letter (A-Z)</li>
                            <li>Contains at least one lowercase letter (a-z)</li>
                            <li>Contains at least one number (0-9)</li>
                            <li>Contains at least one special character (~!@#$%^&*-_+=?><)</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-save-modal Saved_Button">
                        <i class="fa fa-check"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-8">
                    <h4><i class="fa fa-user-circle"></i> My Profile</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/home">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </div>
                <div class="col-lg-4 col-md-4 text-end">
                    <button class="btn action-btn" title="Edit Name" data-toggle="modal" data-target="#Name_Model">
                        <i class="fa fa-user"></i>
                    </button>
                    <button class="btn action-btn" title="Edit Email" data-toggle="modal" data-target="#Email_Model">
                        <i class="fa fa-envelope"></i>
                    </button>
                    <button class="btn action-btn" title="Change Password" data-toggle="modal" data-target="#Password_Model">
                        <i class="fa fa-lock"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card modern-profile-card">
            <div class="row g-0">
                <div class="col-lg-5 col-md-12">
                    <div class="profile-avatar-section">
                        <div class="profile-avatar">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="profile-name-display">
                            <h3>{{ session()->get('name') }}</h3>
                            <p><i class="fa fa-shield"></i> Dashboard Administrator</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-12">
                    <div class="profile-info-section">
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="info-card-icon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div class="info-card-label">Full Name</div>
                                    <button class="edit-btn-inline" data-toggle="modal" data-target="#Name_Model">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                </div>
                            </div>
                            <p class="info-card-value">{{ session()->get('name') }}</p>
                        </div>

                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="info-card-icon">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div class="info-card-label">Email Address</div>
                                    <button class="edit-btn-inline" data-toggle="modal" data-target="#Email_Model">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                </div>
                            </div>
                            <p class="info-card-value">{{ session()->get('email') }}</p>
                        </div>

                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="info-card-icon">
                                    <i class="fa fa-lock"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div class="info-card-label">Password</div>
                                    <button class="edit-btn-inline" data-toggle="modal" data-target="#Password_Model">
                                        <i class="fa fa-edit"></i> Change
                                    </button>
                                </div>
                            </div>
                            <p class="info-card-value">••••••••••••</p>
                        </div>

                        <div style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border: 1px solid #667eea; border-radius: 12px; padding: 20px; margin-top: 32px;">
                            <h6 style="color: #667eea; font-weight: 600; margin-bottom: 12px;">
                                <i class="fa fa-info-circle"></i> Security Tips
                            </h6>
                            <ul style="margin: 0; padding-left: 20px; color: #495057; font-size: 13px;">
                                <li>Use a strong, unique password</li>
                                <li>Never share your password with anyone</li>
                                <li>Change your password regularly</li>
                                <li>Log out when using shared computers</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@csrf
@include('dashboard.attachments.footer')

<script>
    function Change_Name(event) {
        event.preventDefault();
        
        var name = $("#name").val();
        if (!name || name.trim() === '') {
            Notification('warning', 'mini', 'fa fa-exclamation-triangle', 'bottom right', 'Please enter your name');
            return;
        }
        
        let ALL = ['name'];
        var fd = new FormData();
        fd.append('_token', $("input[name=_token]").val());
        for (let i = 0; i < ALL.length; i++) {
            fd.append(ALL[i], $("#" + ALL[i]).val());
        }

        $.ajax({
            method: "POST",
            url: '/Profile/Update/Name',
            processData: false,
            contentType: false,
            data: fd,
            beforeSend: function() {
                $('.Saved_Button').attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            },
        })
        .done(function(response) {
            $('.Saved_Button').removeAttr('disabled').html('<i class="fa fa-check"></i> Save Changes');
            if (response.error == true) {
                Notification('error', 'mini', 'fa fa-times', 'bottom right', response.message);
                setTimeout(function() {
                    location.assign("/dashboard/logout");
                }, 2000);
            }
            if (response.error == false) {
                $(".close").click();
                Notification('success', 'mini', 'fa fa-check', 'bottom right', response.message);
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        })
        .fail(function() {
            $('.Saved_Button').removeAttr('disabled').html('<i class="fa fa-check"></i> Save Changes');
            Notification('error', 'mini', 'fa fa-times', 'bottom right', 'An error occurred. Please try again.');
        });
    }

    function Change_Email(event) {
        event.preventDefault();
        
        var email = $("#email").val();
        if (!email || email.trim() === '') {
            Notification('warning', 'mini', 'fa fa-exclamation-triangle', 'bottom right', 'Please enter your email');
            return;
        }
        
        let ALL = ['email'];
        var fd = new FormData();
        fd.append('_token', $("input[name=_token]").val());
        for (let i = 0; i < ALL.length; i++) {
            fd.append(ALL[i], $("#" + ALL[i]).val());
        }

        $.ajax({
            method: "POST",
            url: '/Profile/Update/Email',
            processData: false,
            contentType: false,
            data: fd,
            beforeSend: function() {
                $('.Saved_Button').attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            },
        })
        .done(function(response) {
            $('.Saved_Button').removeAttr('disabled').html('<i class="fa fa-check"></i> Save Changes');
            if (response.error == true) {
                Notification('error', 'mini', 'fa fa-times', 'bottom right', response.message);
            }
            if (response.error == false) {
                $(".close").click();
                Notification('success', 'mini', 'fa fa-check', 'bottom right', response.message);
            }
            if (response.reload == true) {
                setTimeout(function() {
                    location.assign("/dashboard/logout");
                }, 2000);
            }
        })
        .fail(function() {
            $('.Saved_Button').removeAttr('disabled').html('<i class="fa fa-check"></i> Save Changes');
            Notification('error', 'mini', 'fa fa-times', 'bottom right', 'An error occurred. Please try again.');
        });
    }

    function Change_Password(event) {
        event.preventDefault();

        var access = 0;
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();

        if (password != confirm_password) {
            Notification('error', 'mini', 'fa fa-times-circle', 'bottom right', 'Password and Confirm Password do not match');
            access++;
        }

        if (access == 0) {
            var number = /([0-9])/;
            var Capital = /[A-Z]+/;
            var alphabets = /([a-zA-Z])/;
            var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
            var password = $('#password').val().trim();
            
            if (password.length < 6) {
                Notification('error', 'mini', 'fa fa-times-circle', 'bottom right', 'Password must be at least 6 characters long');
                access++;
            } else {
                if (password.match(number) && password.match(alphabets) && password.match(special_characters)) {
                    if(!password.match(Capital)){
                        Notification('warning', 'mini', 'fa fa-warning', 'bottom right', 'Password must contain at least one uppercase letter');
                        access++;
                    }
                } else {
                    Notification('warning', 'mini', 'fa fa-warning', 'bottom right', 'Password must include letters, numbers and special characters');
                    access++;
                }
            }
        }

        if (access == 0) {
            let ALL = ['password','current_password'];
            var fd = new FormData();
            fd.append('_token', $("input[name=_token]").val());
            for (let i = 0; i < ALL.length; i++) {
                fd.append(ALL[i], $("#" + ALL[i]).val());
            }

            $.ajax({
                method: "POST",
                url: '/Profile/Update/Password',
                processData: false,
                contentType: false,
                data: fd,
                beforeSend: function() {
                    $('.Saved_Button').attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
                },
            })
            .done(function(response) {
                $('.Saved_Button').removeAttr('disabled').html('<i class="fa fa-check"></i> Save Changes');
                if (response.error == true) {
                    Notification('error', 'mini', 'fa fa-times', 'bottom right', response.message);
                } else {
                    $(".close").click();
                    Notification('success', 'mini', 'fa fa-check', 'bottom right', response.message);
                    setTimeout(function() {
                        location.assign("/dashboard/logout");
                    }, 2000);
                }
            })
            .fail(function() {
                $('.Saved_Button').removeAttr('disabled').html('<i class="fa fa-check"></i> Save Changes');
                Notification('error', 'mini', 'fa fa-times', 'bottom right', 'An error occurred. Please try again.');
            });
        }
    }

    function Type_Fuction_1() {
        var x = document.getElementById("current_password");
        var icon = event.target;
        if (x.type === "password") {
            x.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            x.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function Type_Fuction_2() {
        var x = document.getElementById("password");
        var icon = event.target;
        if (x.type === "password") {
            x.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            x.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function Type_Fuction_3() {
        var x = document.getElementById("confirm_password");
        var icon = event.target;
        if (x.type === "password") {
            x.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            x.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

