
 <!--End Dashboard Content-->
        <!--start overlay-->
        <div class="overlay toggle-menu"></div>
        <!--end overlay-->
    </div>
    <!-- End container-fluid-->

</div>
<!--End content-wrapper-->
<!--Start Back To Top Button-->
{{-- <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a> --}}
 <!--Start footer-->
 <footer class="footer">
     <div class="container">
         <div class="text-center">
             Copyright © {{ date('Y') }} {{ config('app.name') }}
         </div>
     </div>
 </footer>
 <!--End footer-->

 <!--start color switcher-->
 {{-- <div class="right-sidebar">
     <div class="switcher-icon">
         <i class="zmdi zmdi-settings zmdi-hc-spin"></i>
     </div>
     <div class="right-sidebar-content">


        <p class="mb-0">Header Colors</p>
        <hr>

        <div class="mb-3">
            <button type="button" id="default-header" class="btn btn-outline-primary">Default Header</button>
        </div>

        <ul class="switcher">
            <li id="header1"></li>
            <li id="header2"></li>
            <li id="header3"></li>
            <li id="header4"></li>
            <li id="header5"></li>
            <li id="header6"></li>
        </ul>

        <p class="mb-0">Sidebar Colors</p>
        <hr>

        <div class="mb-3">
            <button type="button" id="default-sidebar" class="btn btn-outline-primary">Default Header</button>
        </div>

        <ul class="switcher">
            <li id="theme1"></li>
            <li id="theme2"></li>
            <li id="theme3"></li>
            <li id="theme4"></li>
            <li id="theme5"></li>
            <li id="theme6"></li>
        </ul>

     </div>
 </div> --}}
 <!--end color switcher-->

 </div>
 <!--End wrapper-->
 @include("dashboard.attachments.foot")
