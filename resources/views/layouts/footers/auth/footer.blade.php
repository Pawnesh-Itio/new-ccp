</div>
<footer class="footer  pt-3">
        <div class="copyright text-center" style="padding-bottom:10px">
            Copyright
            © <script>
            document.write(new Date().getFullYear())
            </script> by Trolim Pay.
        </div>
</footer>
<script src="{{asset('assets/js/plugins/dropzone.min.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
$(function() {
    $('.button').on('click', function() {
        $('.side-icon').addClass('text-dark');
        $('.button').removeClass('active'); // reset *all* buttons to the default state
        $(this).addClass('active'); // mark only the click-target as active
        $(this).find('.side-icon').removeClass('text-dark');
    })
});
</script>