@if ($project[0]->images != null && $project[0]->images != 'null')
    @php
        $Images = json_decode($project[0]->images);
    @endphp
    @foreach ($Images as $Img)
        <div class="col-lg-3 mb-4">
            <div class="image-box" Name="{{$Img}}" Current="0">
                <img src="{{ asset('/uploads/project/images/' . $Img) }}" class="w-100 h-100" style="object-fit: contain;">
            </div>
        </div>
    @endforeach
@else
    <div class="col-12 text-center">
        <img src="/dashboard/empty.gif" class="img-fluid w-50">
    </div>
@endif
<!-- feather icon js-->
<script src="/dashboard/js/icons/feather-icon/feather.min.js"></script>
<script src="/dashboard/js/icons/feather-icon/feather-icon.js"></script>
<script>
    $(".image-box").on('click', function(){if ($(this).attr("Current") == 0) {$("#selected").val($(this).attr("Name")+'|'+$("#selected").val()); $(this).attr("Current",1).addClass("image-box-selected");}else{$("#selected").val($("#selected").val().replace($(this).attr("Name")+'|', '')); $(this).attr("Current",0).removeClass("image-box-selected");}if ($("#selected").val() != '') {$(".Delete_Button").removeClass('d-none');}else{$(".Delete_Button").addClass('d-none');}});
</script>
