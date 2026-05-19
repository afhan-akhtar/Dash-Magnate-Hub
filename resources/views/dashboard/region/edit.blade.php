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
    .edit-header h4 { color: white; font-weight: 600; margin: 0 0 8px 0; font-size: 24px; }
    .edit-header .breadcrumb { background: transparent; padding: 0; margin: 0; }
    .edit-header .breadcrumb-item { color: rgba(255, 255, 255, 0.8); font-size: 14px; }
    .edit-header .breadcrumb-item a { color: white; text-decoration: none; }
    .edit-header .breadcrumb-item.active { color: rgba(255, 255, 255, 0.9); }
    .modern-edit-card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px; }
    .form-section-title { color: #2c3e50; font-weight: 600; font-size: 18px; margin-bottom: 24px; padding-bottom: 12px; border-bottom: 2px solid #e9ecef; display: flex; align-items: center; gap: 12px; }
    .form-section-title i { color: #667eea; font-size: 22px; }
    .form-label { color: #495057; font-weight: 600; margin-bottom: 8px; font-size: 13px; }
    .form-control { border-radius: 8px; border: 2px solid #e9ecef; padding: 12px 16px; }
    .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15); }
    .btn-back { background: white; color: #667eea; border: 2px solid white; border-radius: 8px; padding: 10px 20px; font-weight: 600; }
    .btn-save { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; border: none; border-radius: 8px; padding: 12px 32px; font-weight: 600; }
    .action-buttons { display: flex; gap: 12px; justify-content: flex-end; margin-top: 32px; }
</style>

<div class="row">
    <div class="col-12">
        <div class="edit-header">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-8">
                    <h4><i class="fa fa-map-marker"></i> Edit Region</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/home">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/dashboard/region">Regions</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
                <div class="col-lg-4 col-md-4 text-end">
                    <a href="/dashboard/region">
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
                    <span>Region Information</span>
                </div>
                <div class="row">
                    <div class="col-12 mb-4">
                        <label class="form-label">Location <span style="color: #dc3545;">*</span></label>
                        <select class="form-control" id="location_id" required>
                            <option value="">Select Location</option>
                        </select>
                    </div>
                    <div class="col-12 mb-4">
                        <label class="form-label">Region Name <span style="color: #dc3545;">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter region name" id="name" value="{{ $Edit[0]->name }}" required>
                    </div>
                </div>
                <div class="action-buttons">
                    <a href="/dashboard/region">
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
    var editLocationId = {{ $Edit[0]->stateId }};
    $(document).ready(function() {
        $.ajax({ method: "GET", url: '/Region/Location' }).done(function(response) {
            if (response.error) return;
            var loc = response.Location;
            $("#location_id").html('<option value="">Select Location</option>');
            if (loc && loc.length) {
                loc.forEach(function(l) {
                    var sel = l.location_id == editLocationId ? ' selected' : '';
                    $("#location_id").append('<option value="' + l.location_id + '"' + sel + '>' + l.name + '</option>');
                });
            }
        });
    });

    function Edit(event) {
        event.preventDefault();
        var fd = new FormData();
        fd.append('_token', $("input[name=_token]").val());
        fd.append('name', $("#name").val());
        fd.append('location_id', $("#location_id").val());
        fd.append('id', '{{ $id }}');
        $.ajax({
            method: "POST",
            url: '/Region/Update',
            processData: false,
            contentType: false,
            data: fd,
            beforeSend: function() {
                $('#Saved_Button').attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            },
        }).done(function(response) {
            if (response.error == true) {
                location.assign("/dashboard/login");
            }
            if (response.error == false) {
                $('#Saved_Button').removeAttr('disabled').html('<i class="fa fa-check"></i> Save Changes');
                Notification('success', 'mini', 'fa fa-check', 'bottom right', response.message);
                setTimeout(function() { location.assign("/dashboard/region"); }, 1500);
            }
        }).fail(function() {
            $('#Saved_Button').removeAttr('disabled').html('<i class="fa fa-check"></i> Save Changes');
            Notification('error', 'mini', 'fa fa-times', 'bottom right', 'An error occurred.');
        });
    }
</script>
