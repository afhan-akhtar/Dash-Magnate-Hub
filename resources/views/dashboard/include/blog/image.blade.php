@if (count($Image) != 0)
    @foreach ($Image as $i)
        <div class="swiper-slide">
            <div class="image-box">
                <img src="{{ asset('/uploads/image/' . $i->name) }}" class="w-100 h-100" style="object-fit: contain;">
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
    $(".swiper-slide").on('click', function(){$("#link").val($(this).find('img').attr('src'));});
</script>
