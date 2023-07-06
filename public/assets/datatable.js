$(function () {
    const tableContainer = $('.table-container');

    localStorage.removeItem('choose');

    tableContainer.each((i, e) => {

        const tableDiv = $(e).find('table');
        const sort = tableDiv.data('sort');
        const ajaxUrl = tableDiv.data('ajaxurl');
        const ajaxMethod = tableDiv.data('ajaxmethod');
        const rowClick = tableDiv.data('rowclick');
        const pageLength = parseInt(tableDiv.data('pagelength')) > 0 ? parseInt(tableDiv.data('pagelength')) : 20;
        const lengthChange = tableDiv.data('lengthchange') === true ? true : false;
        const chooseDiv = '.datatableChoose';
        const paginatiOnTop = tableDiv.data('paginationtop');

        let domText=`"<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"`;

        if (paginatiOnTop){
             domText=`<"top"lp<"clear">>rt<"bottom"ip<"clear">>`;
        }

        let formData = [];

        const columns = [];

        tableDiv.find('thead tr:first th').each((i, e) => {
            let key = $(e).attr(':key');
            let callback = $(e).attr(':callback');
            let className = $(e).attr(':className');

            if (key === '') {
                columns.push({
                    className,
                    data: null,
                    defaultContent: '',
                    render: eval(callback)
                });
            } else {
                columns.push({
                    className,
                    data: key,
                    render: eval(callback)
                });
            }
        });

        const table = tableDiv.DataTable({
            "language": {
                "sDecimal": ",",
                "sEmptyTable": "Tabloda herhangi bir veri mevcut değil",
                "sInfo": "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
                "sInfoEmpty": "Kayıt yok",
                "sInfoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "Sayfada _MENU_ kayıt göster",
                "sLoadingRecords": "Yükleniyor...",
                "sProcessing": "İşleniyor...",
                "sSearch": "Ara:",
                "sZeroRecords": "Eşleşen kayıt bulunamadı",
                "oPaginate": {
                    "sFirst": "İlk",
                    "sLast": "Son",
                    "sNext": "Sonraki",
                    "sPrevious": "Önceki"
                },
                "oAria": {
                    "sSortAscending": ": artan sütun sıralamasını aktifleştir",
                    "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
                },
                "select": {
                    "rows": {
                        "_": "%d kayıt seçildi",
                        "0": "",
                        "1": "1 kayıt seçildi"
                    }
                }
            },
            dom: domText,
            serverSide: true,
            orderCellsTop: true,
            ordering: sort,
            searching: false,
            lengthChange,
            lengthMenu: [10, 20, 50, 75, 100],
            pageLength,
            processing: true,
            columns,
            ajax: {
                url: ajaxUrl,
                method: ajaxMethod,
                data: function (d) {

                    d.page = (d.start / d.length) + 1;

                    formData.forEach(item => {
                        if (item.value !== '') {
                            d[item.name] = item.value
                        }
                    })
                }
            },
            drawCallback: function (settings, json) {
                tableDiv.find('.timer-times').each((i, e) => {
                    timerDatatable($(e));
                });

                tableDiv.find('.row-status-color').each((i, e) => {
                    statusRowColor($(e));
                });

                tableDiv.find('.row-orderScore-color').each((i, e) => {
                    orderScoreRowColor($(e));
                });

                tableDiv.find('.row-region-color').each((i, e) => {
                    regionRowColor($(e));
                });

                tableDiv.find('.listShippingMethod').each((i, e) => {
                    listShippingMethod($(e));
                });


                // CHOSE
                let chooseStorage = localStorage.getItem('choose') ? JSON.parse(localStorage.getItem('choose')) : [];
                tableDiv.find(chooseDiv).each(function () {
                    const value = $(this).val();
                    $(this).removeAttr('checked');
                    $(this).parents('tr').find('td').removeClass('chooseRow');
                    chooseStorage.map((c) => {
                        if (c.id === value) {
                            $(this).attr('checked', true);
                            $(this).parents('tr').find('td').addClass('chooseRow');
                        }
                    });
                });


            }
        });

        table.on( 'xhr', function () {
            const json = table.ajax.json();
            if(typeof json.eventData !== 'undefined'){
                window.postMessage(JSON.stringify(json.eventData));
            }
        } );

        const datatableSearch = (context) => {
            formData = [];
            // Input Data
            context.find('.input-filter').each((i, e) => {
                formData.push({
                    name: $(e).attr('name'),
                    value: $(e).val()
                });
            });

            context.find('.date-filter').each((i, e) => {
                formData.push({
                    name: $(e).attr('name'),
                    value: $(e).val()
                });
            });

            // Select Data
            context.find('.select-filter').each((i, e) => {

                let selectName = $(e).attr('name');
                let selectVal = $(e).val();
                if (selectVal === '') {
                    formData = formData.filter(item => item.name !== selectName);
                }
                formData.push({
                    name: selectName,
                    value: selectVal
                });
            });

            //table.ajax.url(ajaxUrl + '?' + $('FORM[NAME="codeTableForm"]').serialize()).load();
            table.draw();
        }

        const search = _.debounce(datatableSearch, 700);

        tableDiv.find('.input-filter').keyup(() => search(tableDiv));

        tableDiv.find('.date-filter').change(() => search(tableDiv));

        tableDiv.find('.select-filter').change(() => search(tableDiv));

        const dateRangePicker = (context) => {

            const inputName = $(context).data('name');

            const inputName2 = `${inputName}2`;
            $(context).daterangepicker({
                autoUpdateInput: false,
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-danger',
                cancelClass: 'btn-inverse',
                ranges: {
                    'Dün': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Son 7 Gün': [moment().subtract(6, 'days'), moment()],
                    'Son 30 Gün': [moment().subtract(29, 'days'), moment()],
                    'Bu Ay': [moment().startOf('month'), moment().endOf('month')],
                    'Geçen Ay': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: {
                    cancelLabel: 'Sil',
                    applyLabel: 'Uygula',
                    format: 'DD-MM-YYYY'
                }
            });

            $(context).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                $(context).parent().find('#' + inputName).find('input:first').val(picker.startDate.format('YYYY-MM-DD'));
                $(context).parent().find('#' + inputName).find('input:last').val(picker.endDate.format('YYYY-MM-DD'));
                search(tableDiv);
            });

            $(context).on('cancel.daterangepicker', function (ev, picker) {
                $(context).val('');
                $(context).parent().find('#' + inputName).find('input').val('');
                search(tableDiv);
            });
        }

        tableDiv.find('.date-range-picker').each((i, e) => {
            dateRangePicker(e);
        });

        // Row Click
        if (rowClick !== '') {
            tableDiv.on('click', 'tbody > tr > td:not(.details-control)', function () {
                const data = table.row(this).data();
                location.href = data[rowClick];
            });
        }

        // Form Filter
        $('#datatableFormFilter').submit(function (e) {
            e.preventDefault();
            formData = [];
            datatableSearch($(this));
        })

        // CHOSE
        tableDiv.on('change', 'tbody > tr > td > ' + chooseDiv, function (e) {
            e.preventDefault();

            const value = $(this).val();
            let chooseStorage = localStorage.getItem('choose') ? JSON.parse(localStorage.getItem('choose')) : [];

            if ($(this).is(':checked')) {
                $(this).parents('tr').find('td').addClass('chooseRow');

                chooseStorage = chooseStorage.filter((c) => c.id !== value);
                chooseStorage.push({
                    id: value
                });

                tableDiv.find('.chooseCounter').text(chooseStorage.length);

                localStorage.setItem('choose', JSON.stringify(chooseStorage));

            } else {
                $(this).parents('tr').find('td').removeClass('chooseRow');

                chooseStorage = chooseStorage.filter((c) => c.id !== value);

                tableDiv.find('.chooseCounter').text(chooseStorage.length);
                localStorage.setItem('choose', JSON.stringify(chooseStorage));
            }
        });

        $('.allChose').change(function () {
            const allChoseThis = $(this);
            tableDiv.find(chooseDiv).each(function () {

                if (allChoseThis.is(':checked')) {
                    // $(this).attr('checked', true);
                    $(this).prop('checked', true);
                } else {
                    // $(this).removeAttr('checked');
                    $(this).prop('checked', false);
                }
                $(this).trigger('change');
            });
        });

    });

});

const listShippingMethod = (a) => {
    const el = $(a),
        parent = el.closest("tr"),
        shippingKey = el.data("shippingKey");

    if (shippingKey === 'come_get' || shippingKey === 'restaurant_courier') {
        parent.addClass("list_row_come_get");
    } else if (shippingKey === 'special') {
        parent.addClass("list_row_special");
    }
}

const statusToHtml = (data, type, row, meta) => {
    if (data === 1) {
        return `<span class="label label-success">Aktif</span>`;
    } else if (data === 2) {
        return `<span class="label label-primary">Yakında</span>`;
    } else {
        return `<span class="label label-danger">Pasif</span>`;
    }
}

const detailAction = (data, type, row, meta) => {
    return `<a href="${row.viewUrl}" class="btn table-colored-btn btn-sm">Detay</a>`;
}

const timeFormat = (data, type, row, meta) => {
    return `<div class="table-date">${data}</div>`;
}

const orderDateFormat = (data, type, row, meta) => {
    return `<div class="row-status-color" data-orderStatusId="${row.order_status_id}">${data}</div>`;
}

const choose = (data, type, row, meta) => {
    return `<input style="opacity: 1; left: auto" type="checkbox" name="choose" class="datatableChoose" value="${data}" />`;
}

const timerDatatable = (context) => {
    const time = context.data('time');
    const pause = context.data('pause');
    if (time) {
        const myFormat = 'MS';
        const currentDate = new Date();

        currentDate.setSeconds(currentDate.getSeconds() - time);

        context.countdown({
            since: currentDate,
            format: myFormat,
            compact: true,
            onTick: watchCountUp,
        });

        if (pause) {
            context.countdown('pause');
        }

        function watchCountUp(periods) {
            if (!pause && (periods[3] > 0 || periods[4] > 0 || 10 < periods[5])) {

                if (context.parents('tr').hasClass('come-get') === false) {
                    context.parents('tr').addClass('times-wait-30');
                }
            }
        }
    }
}

const statusRowColor = (context) => {

    const orderStatusId = context.data('orderstatusid');
    const shippingKey = context.data('shippingkey');

    // Satırları Statülere Göre Renklendir
    let bgClass = '';
    if (ORDER_STATUS.ORDER_ARRIVED === orderStatusId) {
        bgClass = 're-new-order-color';
    } else if (ORDER_STATUS.USER_PREPARING.includes(orderStatusId)) {
        bgClass = 're-preparing-color';
    } else if (ORDER_STATUS.USER_ROAD.includes(orderStatusId)) {
        bgClass = 're-on-the-road-color';
    } else if (ORDER_STATUS.USER_COMPLETE.includes(orderStatusId)) {
        bgClass = 're-completed-order-color';
    } else if (ORDER_STATUS.USER_INCOMPLETE.includes(orderStatusId)) {
        bgClass = 're-incomplete-order-color';
    } else if (ORDER_STATUS.USER_CANCEL.includes(orderStatusId)) {
        bgClass = 're-cancelled-order-color';
    }else if (-1 === orderStatusId) {
        bgClass = 're-missing-order-color';
    }

    if (shippingKey === 'come_get' || shippingKey === 'come_get_cash') {
        context.parents('tr').removeClass('times-wait-30');
        bgClass = 'come-get';
    }

    context.parents('tr').addClass(bgClass);
}

const orderScoreRowColor = (context) => {

    const isRead = context.data('isread');
    // Satırları Statülere Göre Renklendir
    let bgClass = 'order-score-read-color';
    if (isRead === 0) {
        bgClass = 'order-score-unread-color';
    }

    context.parents('tr').addClass(bgClass);
}


const regionRowColor = (context) => {

    const zoneColor = context.data('zonecolor');

    context.parents('tr').css({
        'background': zoneColor,
        'color': '#fff'
    });

}
