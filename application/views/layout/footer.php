    </div>
    </div>

    <!-- Required Js -->
    <script src="<?php echo base_url(); ?>assets/js/vendor-all.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/ripple.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/pcoded.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/sweet-alert/sweetalert2.all.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/app-custom.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/sstoast.js"></script>

    <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/daterangepicker.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/select2.full.min.js"></script>

    <!-- datatable Js -->
    <script src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/buttons.colVis.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/buttons.print.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/pdfmake.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/jszip.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/buttons.html5.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/pages/data-export-custom.js"></script>

    <script>
$(document).ready(function() {
    checkCookie();

    $('input[name="daterange"]').daterangepicker({
        autoUpdateInput: false,
        autoApply: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
            'DD/MM/YYYY'));
        $('input[name="src_date1"]').val(picker.startDate.format('YYYY/MM/DD'));
        $('input[name="src_date2"]').val(picker.endDate.format('YYYY/MM/DD'));
    });


});

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function checkCookie() {
    var ticks = getCookie("modelopen");
    if (ticks != "") {
        ticks++;
        setCookie("modelopen", ticks, 1);
        if (ticks == "2" || ticks == "1" || ticks == "0") {
            $('#exampleModalCenter').modal();
        }
    } else {
        // user = prompt("Please enter your name:", "");
        $('#exampleModalCenter').modal();
        ticks = 1;
        setCookie("modelopen", ticks, 1);
    }
}

function delete_recordById(url, Msg, isReload = '', datatableReload = '') {

    if (Msg != '') {
        Msg = "'" + Msg + "'";
    } else {
        Msg = "data ini";
    }
    Swal.fire({
        title: 'Peringatan!',
        text: "Anda yakin akan menghapus " + Msg + " ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus data!',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: url,
                success: function(res) {
                    Swal.fire(
                        'Deleted!',
                        'Data berhasil di hapus.',
                        'success'
                    );
                    if (isReload != 'no') {
                        window.location.reload(true);
                    }
                    if (datatableReload != '') {
                        datatableReload;
                    }

                }
            })

        }
    });

}

const mapIcon = '<?= base_url("assets/images/map.svg") ?>';
const BASE_URL = '<?= base_url("/") ?>';
    </script>

    </body>

    </html>