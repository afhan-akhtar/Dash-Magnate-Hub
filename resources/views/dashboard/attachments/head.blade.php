<script>
    var xhr = new XMLHttpRequest();
    var url = '/Check/Session';
    xhr.open("GET", url, true);
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.response == '') {
                location.assign("/dashboard/logout");
            }
        }
    }
    xhr.send();
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>{{ config('app.name') }} | Dashboard</title>
    <!--favicon-->
    <link rel="icon" type="image/x-icon" href="/website/images/black.png" />
    <!-- Vector CSS -->
    <link href="/dashboard/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <!-- simplebar CSS-->
    <link href="/dashboard/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <!-- Bootstrap core CSS-->
    <link href="/dashboard/assets/css/bootstrap.min.css" rel="stylesheet" />
    <!-- animate CSS-->
    <link href="/dashboard/assets/css/animate.css" rel="stylesheet" type="text/css" />
    <!-- Icons CSS-->
    <link href="/dashboard/assets/css/icons.css" rel="stylesheet" type="text/css" />
    <!-- Sidebar CSS-->
    <link href="/dashboard/assets/css/sidebar-menu.css" rel="stylesheet" />
    <!-- Custom Style-->
    <link href="/dashboard/assets/css/app-style.css" rel="stylesheet" />
    <!-- skins CSS-->
    <link href="/dashboard/assets/css/skins.css" rel="stylesheet" />
    <!-- font awesome-->
    <!-- <link href="/dashboard/assets/css/font-awesome-6.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    {{-- Notification --}}
    <link rel="stylesheet" href="/dashboard/assets/plugins/notifications/css/lobibox.min.css"/>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    <!-- Own Style-->
    <link href="/dashboard/assets/css/own.css" rel="stylesheet" />
    <!-- Sidebar Visibility Fix-->
    <link href="/dashboard/assets/css/sidebar-visible.css" rel="stylesheet" />
</head>
