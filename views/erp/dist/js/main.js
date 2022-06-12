(function () {

    $('[data-toggle="tooltip"]').tooltip()

    if (window.matchMedia('(max-width: 767px)').matches) {
        var oTable = $(".table-main").DataTable({
            "dom": '<<"dt-topbar" if><"t-scool" <t>><"dt-bot-bar" lp>>',
            "language": {
                "emptyTable": "Data kosong",
                "zeroRecords": "Data tidak ditemukan",
                "paginate": {
                    "previous": "<span class='icon-fi-sr-caret-left'></span>",
                    "next": "<span class='icon-fi-sr-caret-right'></span>",
                }
            },
            "info": false,
            "ordering": false,
            "responsive": {
                details: {
                    renderer: function (api, rowIdx) {
                        // Select hidden columns for the given row
                        var data = api.cells(rowIdx, ':hidden').eq(0).map(function (cell) {
                            var header = $(api.column(cell.column).header());

                            return '<tr>' +
                                '<td class="medium pl-0">' +
                                header.text() + ':' +
                                '</td> ' +
                                '<td>' +
                                api.cell(cell).data() +
                                '</td>' +
                                '</tr>';
                        }).toArray().join('');

                        return data ?
                            $('<table/>').append(data) :
                            false;
                    }
                }
            }
        });
        $(".table-main").removeClass('nowrap')
    } else {
        var oTable = $(".table-main").DataTable({
            "dom": '<<"dt-topbar" if><"t-scool" <t>><"dt-bot-bar" lp>>',
            "language": {
                "emptyTable": "Data kosong",
                "zeroRecords": "Data tidak ditemukan",
                "paginate": {
                    "previous": "<span class='icon-fi-sr-caret-left'></span>",
                    "next": "<span class='icon-fi-sr-caret-right'></span>",
                }
            },
            "info": false,
            "ordering": false
        });
    }

    $('#searchField').keyup(function () {
        oTable.search($(this).val()).draw();
    })


    $('.menu-btn').click(function () {
        $('.sidebar').addClass('show');
        $('body').append('<div class="sidebar-overlay"></div>');
    });
    $(document).on('click', '.sidebar-overlay', function () {
        $('.sidebar').removeClass('show');
        $(this).remove();
    })
    $(document).on('click', '.menu-close', function (e) {
        e.preventDefault();
        $('.sidebar').removeClass('show');
        $('.sidebar-overlay').remove();
    })
    $('select').select2({
        theme: 'bootstrap4 main-form-control',
        placeholder: "",
        minimumResultsForSearch: -1
    });
    $('.select').select2({
        theme: 'bootstrap4 main-form-control',
        placeholder: "Select",
        minimumResultsForSearch: -1
    });


    $('.kurir').select2({
        theme: 'bootstrap4 main-form-control',
        placeholder: "Pilih Kurir",
        minimumResultsForSearch: -1
    });
    $('.type-homepage').select2({
        theme: 'bootstrap4',
        placeholder: "Pilih Type Homepage",
        minimumResultsForSearch: -1
    });
    $('.category-btn').select2({
        theme: 'bootstrap4',
        placeholder: "Pilih Kategori Button",
        minimumResultsForSearch: -1
    });
    $('.status-order').select2({
        theme: 'bootstrap4 main-form-control',
        placeholder: "Pilih Status Order",
        minimumResultsForSearch: -1
    });
    $('.category').select2({
        theme: 'bootstrap4',
        placeholder: "Pilih Kategori",
        minimumResultsForSearch: -1
    });
    $('.tampilan').select2({
        theme: 'bootstrap4',
        placeholder: "Pilih Post",
        minimumResultsForSearch: -1
    });
    $('.address').select2({
        theme: 'bootstrap4 main-form-control',
        placeholder: "Pilih",
    });
    $('.searchless').select2({
        theme: 'bootstrap4 main-form-control',
        placeholder: "Pilih",
        minimumResultsForSearch: -1
    });
    $('.select-sm').select2({
        theme: 'bootstrap4 select-sm',
        placeholder: "",
        minimumResultsForSearch: -1
    });
    $('.single-file-upload').click(function () {
        var x = $(this).find('input[type="file"]').attr("id");
        $("#" + x).change(function () {
            var x = $(this).val().replace(/.*(\/|\\)/, '');
            var filename = $(this).closest('.single-file-upload').find('.filename');
            $(filename).html(x);
        })
    })
    $('.del-img').click(function () {
        $(this).closest('.img-container').css('display', 'none');
        $('.fileupload-edit').css('display', 'block');
    })
    $('.eye-btn').click(function () {
        var input = $(this).closest('.form-inline-group').find('.pass-form');
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    $('.dropdown-arrow').click(function () {
        var idMenu = $(this).attr('aria-expanded');
        if ($(this).attr('aria-expanded') == 'true') {
            $(this).removeClass("active")
        }
        if ($(this).attr('aria-expanded') == "false") {
            $(this).addClass("active")
        }
    })

    $(".sidebar-toggle-btn").click(function (e) {
        $(".sidebar").toggleClass('minimize');
        $(".main-content").toggleClass('maximize');
        $(".header-menu").toggleClass('maximize');
        $(this).toggleClass("active");
    })

    $(document).ready(function () {
        clockUpdate();
        setInterval(clockUpdate, 1000);
        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];
        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var t = new Date();
        var dateN = days[t.getDay()] + ', ' + t.getDate() + ' ' + monthNames[t.getMonth()] + ' ' + t.getFullYear();
        $('.dateN').html(dateN)
    })
    function clockUpdate() {
        var date = new Date();
        function addZero(x) {
            if (x < 10) {
                return x = '0' + x;
            } else {
                return x;
            }
        }
        var h = addZero(date.getHours());
        var m = addZero(date.getMinutes());
        var s = addZero(date.getSeconds());
        $('.digital-clock').text(h + ':' + m + ':' + s)
    }




    $(document).on('click', '.custom-file', function () {

        // $('.custom-file').click(function () {
        var x = $(this).find('input[type="file"]').attr("id");
        $("#" + x).change(function () {
            var x = $(this).val().replace(/.*(\/|\\)/, '');
            var filename = $(this).closest('.custom-file').find('.custom-file-label');
            $(filename).html(x);
        })
    })
    $('.date').datepicker({
        format: 'dd MM yyyy'
    });
    // function resizable(el, factor) {
    //     var int = Number(factor) || 7.7;
    //     function resize() { el.style.width = ((el.value.length + 2) * int) + 'px' }
    //     var e = 'keyup,keypress,focus,blur,change'.split(',');
    //     for (var i in e) el.addEventListener(e[i], resize, false);
    //     resize();
    // }
    // resizable(document.getElementById('txt'), 7);
    function getWidthOfInput(input){
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        var text = input.value.length ? input.value : input.placeholder;
        var style = window.getComputedStyle(input);
        ctx.lineWidth = 1;
        ctx.font = style.font;
        var text_width = ctx.measureText(text).width;
        return text_width;
    }
    
    function resizable (el, factor) {
        var int = Number(factor) || 7.7;
        function resize() { el.style.width = ((el.value.length + 1) * int) + 'px' }
        var e = 'keyup,keypress,focus,blur,change'.split(',');
        for (var i in e) el.addEventListener(e[i], resize, false);
        resize();
    }
    $(".txt").prop('disabled', true);
    $(".txt").each( function(i){
        resizable(this);
    });

    $('.edit-table').click(function (e) {
        e.preventDefault();
        $('.val-field input').removeAttr('disabled')
        $('.save-table').show();
        $('.edit-table').hide();
    })
})();

