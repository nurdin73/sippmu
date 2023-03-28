
 $(document).ready(function() {

 });

 function successMsg(msg) {
     //toastr.success(msg);
     
     Swal.fire({
      icon: 'success',
      title: msg,
      showConfirmButton: true,
      //timer: 1500
    });
 }

 function errorMsg(msg) {
     //toastr.error(msg);
     
     Swal.fire({
      title: '',
      html: '<div class="xred">'+msg+'</div>',
      icon: 'error',
      confirmButtonColor: '#d33',
      confirmButtonText: 'Ok'
    });
 }

 // header afix//