<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script type="text/javascript">
  const Toast = Swal.mixin({
    toast: true,
    position: 'bottom-end',
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })
</script>
   
@if (Session::has('success'))
  <script type="text/javascript">
    Toast.fire({
      icon: 'success',
      title: '{{Session::get('success')}}'
    })
  </script>
@endif


@if (count($errors) > 0)
  @foreach ($errors->all() as $error)
    <script type="text/javascript">
      Toast.fire({
        icon: 'error',
        title: '{{ $error }}'
      })
    </script>
  @endforeach 
@endif

@if(session('info'))
  <script type="text/javascript">
    Toast.fire({
      icon: 'info',
      title: '{{ session('info') }}'
    })
  </script>
@endif

@if(session('warning'))
  <script type="text/javascript">
    Toast.fire({
      icon: 'warning',
      title: '{{ session('warning') }}'
    })
  </script>
@endif

@if(session('swalwarning'))
  <script type="text/javascript">
    Swal.fire({
      icon: 'warning',
      title: '{{ session('swalwarning') }}',
      showConfirmButton: false,
      footer: '<a href="https://play.google.com/store/apps/details?id=com.orbachinujbuk.bjsandbarexam" target="_blank"><img alt="Get it on Google Play" class="img-responsive" style="max-width: 250px; width: auto;" src="https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png"></a>'
    })
  </script>
@endif

@if(session('swalsuccess'))
  <script type="text/javascript">
    Swal.fire({
      icon: 'success',
      title: '{{ session('swalsuccess') }}',
    })
  </script>
@endif