<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Kun Faris</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="https://img.pikbest.com/origin/09/27/06/70epIkbEsTkz9.png!sw800">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            scroll-behavior: smooth;
            background-color: #2d1100;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: rgba(49, 22, 0, 0.8);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 10px;
            max-width: 900px;
            margin: 30px auto;
            transform: translateZ(0);
        }

        h1 {
            font-size: 2.5rem;
            color: #582900;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #calendar {
            margin-top: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            background-color: rgba(255, 145, 0, 0.5);
            color: #fff;
            padding: 15px;
        }

        .modal-content {
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            border: none;
        }

        .close {
            color: #fff;
        }

        .close:hover {
            color: #fff;
        }

        .modal-header {
            background-color: #582900;
            color: #fff;
            border-bottom: none;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .modal-body,
        .modal-footer {
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .btn {
            border-radius: 5px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #291a00;
            border-color: #291a00;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:active {
            background-color: #582900 !important;
            border-color: #582900 !important;
            box-shadow: 0 0 0 .2rem rgba(43, 21, 0, 0.5) !important;
        }

        .btn-primary:focus {
            box-shadow: 0 0 0 .2rem rgba(43, 21, 0, 0.5) !important;
        }

        .btn-primary:hover {
            background-color: #291a00;
            border-color: #291a00;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        #backgroundVideo {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        #calendar .fc-event {
            color: #fff;
            font-size: 14px;
            font-family: 'Roboto', sans-serif;
            transition: all 0.3s ease;
        }

        #calendar .fc-header {
            background-color: #007bff;
            color: #fff;
            border: none;
        }

        #calendar .fc-event:hover {
            transform: scale(1.05);
            background-color: #391b00;
        }

        #calendar .fc-day-grid-event {
            border-radius: 5px;
        }

        .fc-event {
            background-color: #291a00 !important;
            border: 1px solid #291a00 !important;
        }

        .fc-event .fc-title {
            display: block;
            white-space: normal;
        }

        .fc-event .fc-room {
            font-size: 0.85em;
            color: #fff;
        }

        .fc-content {
            text-align: center;
            background-color: #582900;
            border: 1px solid #391b00 !important;
        }

        .fc-today {
            color: #ffffff;
            background-color: #2c140068 !important;
        }

        .toast-info {
            background-color: rgba(255, 145, 0, 0.5);
        }
    </style>
</head>

<body>
    <video autoplay muted loop id="backgroundVideo">
        <source src="{{ asset('background.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="container">
        <a href="{{ route('events.list') }}" class="btn btn-primary mb-3">Cari</a>
        <a href="{{ url('kontak') }}" class="btn btn-secondary mb-3">Kontak</a>
        <div id="calendar" class="calendar"></div>
    </div>

    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Tambah</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <input type="hidden" id="eventId">
                        <div class="form-group">
                            <label for="eventTitle">Judul</label>
                            <input type="text" class="form-control" id="eventTitle" placeholder="Masukkan Judul">
                        </div>
                        <div class="form-group">
                            <label for="eventStart">Mulai</label>
                            <input type="text" class="form-control" id="eventStart" readonly>
                        </div>
                        <div class="form-group">
                            <label for="eventEnd">Selesai</label>
                            <input type="text" class="form-control" id="eventEnd" readonly>
                        </div>
                        <div class="form-group">
                            <label for="eventDescription">Deskripsi</label>
                            <textarea class="form-control" id="eventDescription" rows="3" placeholder="Masukkan Deskripsi"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="eventLocation">Ruangan</label>
                            <select class="form-control" id="eventLocation">
                                <option value="">Pilih Ruangan</option>
                                <option value="Ruangan 1">Ruangan 1</option>
                                <option value="Ruangan 2">Ruangan 2</option>
                                <option value="Ruangan 3">Ruangan 3</option>
                                <option value="Ruangan 4">Ruangan 4</option>
                                <option value="Ruangan 5">Ruangan 5</option>
                                <option value="Ruangan 6">Ruangan 6</option>
                                <option value="Ruangan 7">Ruangan 7</option>
                                <option value="Ruangan 8">Ruangan 8</option>
                                <option value="Ruangan 9">Ruangan 9</option>
                                <option value="Ruangan 10">Ruangan 10</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="eventCategory">Baju</label>
                            <input type="text" class="form-control" id="eventCategory" placeholder="Masukkan Baju">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-danger" id="removeEventBtn"
                        style="display:none;">Hapus</button>
                    <button type="button" class="btn btn-primary" id="saveEventBtn">Simpan</button>
                    <button type="button" class="btn btn-info" id="showEventBtn"
                        style="display:none;">Lihat</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            var SITEURL = "{{ url('/') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var calendar = $('#calendar').fullCalendar({
                editable: true,
                events: SITEURL + "/events",
                displayEventTime: false,
                selectable: true,
                selectHelper: true,
                select: function(start, end, allDay) {
                    var today = moment().startOf('day');
                    if (start.isBefore(today)) {
                        alert('Anda tidak bisa memesan ruangan untuk tanggal sebelum hari ini.');
                        calendar.fullCalendar('unselect');
                        return;
                    }
                    $('#eventModal').modal('show');
                    $('#eventStart').val(moment(start).format('YYYY-MM-DD'));
                    $('#eventEnd').val(moment(end).subtract(1, 'day').format(
                        'YYYY-MM-DD'));
                },
                eventRender: function(event, element) {
                    element.find('.fc-title').append('<div class="fc-room">' + event.location +
                        '</div>');
                },
                eventClick: function(event) {
                    $('#eventModal').modal('show');
                    $('#eventModalLabel').text('Edit Acara');
                    $('#eventId').val(event.id);
                    $('#eventTitle').val(event.title);
                    $('#eventStart').val(moment(event.start).format('YYYY-MM-DD'));
                    $('#eventEnd').val(event.end ? moment(event.end).subtract(1, 'day').format(
                        'YYYY-MM-DD') : moment(event.start).format(
                        'YYYY-MM-DD'));
                    $('#eventDescription').val(event.description);
                    $('#eventLocation').val(event.location);
                    $('#eventCategory').val(event.category);
                    $('#saveEventBtn').text('Perbarui').data('event', event);
                    $('#removeEventBtn').show().data('event', event);
                    $('#showEventBtn').show().data('event', event);
                },
                eventDrop: function(event, delta, revertFunc) {
                    var today = moment().startOf('day');
                    if (event.start.isBefore(today)) {
                        alert('Anda tidak bisa memindahkan acara ke tanggal sebelum hari ini.');
                        revertFunc();
                        return;
                    }

                    var updatedEvent = {
                        id: event.id,
                        title: event.title,
                        start: event.start.format('YYYY-MM-DD'),
                        end: event.end ? event.end.format('YYYY-MM-DD') : null,
                        description: event.description,
                        location: event.location,
                        category: event.category,
                        _method: 'PUT'
                    };

                    if (hasOverlappingEvents(updatedEvent)) {
                        alert(
                            'Acara tidak bisa dipindahkan. Ruangan sudah terpakai pada waktu tersebut.'
                        );
                        revertFunc();
                        return;
                    }

                    clearTimeout(window.dragTimeout);
                    window.dragTimeout = setTimeout(function() {
                        $.ajax({
                            url: SITEURL + "/events/" + event.id,
                            data: updatedEvent,
                            type: "POST",
                            success: function(data) {
                                calendar.fullCalendar('updateEvent', event);
                                displayMessage("Acara berhasil diperbarui");
                            },
                            error: function() {
                                revertFunc();
                                showErrorPopup("Gagal memperbarui acara");
                            }
                        });
                    }, 500);
                }
            });

            $('#eventModal').on('show.bs.modal', function(e) {
                var event = $(e.relatedTarget).data('event');
                if (event) {
                    $('#eventModalLabel').text('Edit Acara');
                    $('#eventId').val(event.id);
                    $('#eventTitle').val(event.title);
                    $('#eventStart').val(moment(event.start).format('YYYY-MM-DD'));
                    $('#eventEnd').val(event.end ? moment(event.end).subtract(1, 'day').format(
                        'YYYY-MM-DD') : moment(event.start).format(
                        'YYYY-MM-DD'));
                    $('#eventDescription').val(event.description);
                    $('#eventLocation').val(event.location);
                    $('#eventCategory').val(event.category);
                    $('#saveEventBtn').text('Perbarui').data('event', event);
                    $('#removeEventBtn').show().data('event', event);
                    $('#showEventBtn').show().data('event', event);
                } else {
                    $('#eventModalLabel').text('Tambah Acara');
                    $('#saveEventBtn').text('Simpan').removeData('event');
                    $('#removeEventBtn').hide().removeData('event');
                    $('#showEventBtn').hide().removeData('event');
                    $('#eventForm')[0].reset();
                }
            });

            function hasOverlappingEvents(newEvent) {
                var events = $('#calendar').fullCalendar('clientEvents');
                var newEventStart = moment(newEvent.start);
                var newEventEnd = moment(newEvent.end || newEvent.start).subtract(1, 'day');

                for (var i = 0; i < events.length; i++) {
                    var event = events[i];
                    var eventStart = moment(event.start);
                    var eventEnd = moment(event.end || event.start).subtract(1, 'day');

                    if (event.id !== newEvent.id &&
                        event.location === newEvent.location &&
                        newEventStart.isBefore(eventEnd) &&
                        newEventEnd.isAfter(eventStart) &&
                        eventEnd.isAfter(moment())) {
                        return true;
                    }
                }
                return false;
            }

            $('#saveEventBtn').click(function() {
                var eventData;
                var updateBtn = $(this).text() === 'Perbarui';

                if (updateBtn) {
                    var event = $(this).data('event');
                    event.title = $('#eventTitle').val();
                    event.description = $('#eventDescription').val();
                    event.location = $('#eventLocation').val();
                    event.category = $('#eventCategory').val();
                    event.start = moment($('#eventStart').val());
                    event.end = moment($('#eventEnd').val()).add(1, 'day');
                    eventData = {
                        id: event.id,
                        title: event.title,
                        start: event.start.format('YYYY-MM-DD'),
                        end: event.end ? event.end.format('YYYY-MM-DD') : null,
                        description: event.description,
                        location: event.location,
                        category: event.category,
                        _method: 'PUT'
                    };
                    var requestUrl = SITEURL + "/events/" + event.id;
                } else {
                    eventData = {
                        title: $('#eventTitle').val(),
                        start: $('#eventStart').val(),
                        end: $('#eventEnd').val() ? moment($('#eventEnd').val()).add(1, 'day').format(
                            'YYYY-MM-DD') : null,
                        description: $('#eventDescription').val(),
                        location: $('#eventLocation').val(),
                        category: $('#eventCategory').val()
                    };
                    var requestUrl = SITEURL + "/events";
                }

                if (hasOverlappingEvents(eventData)) {
                    alert('Acara tidak bisa dibuat. Ruangan sudah terpakai pada waktu tersebut.');
                    return;
                }

                $.ajax({
                    url: requestUrl,
                    data: eventData,
                    type: "POST",
                    success: function(data) {
                        if (updateBtn) {
                            calendar.fullCalendar('updateEvent', event);
                        } else {
                            eventData.id = data.id;
                            calendar.fullCalendar('renderEvent', eventData, true);
                        }
                        $('#eventModal').modal('hide');
                        displayMessage(updateBtn ? "Acara berhasil diperbarui" :
                            "Acara berhasil dibuat");
                        location.reload();
                    },
                    error: function(xhr) {
                        showErrorPopup(xhr.responseJSON.message || "Gagal menyimpan acara");
                    }
                });
            });

            $('#removeEventBtn').click(function() {
                var event = $(this).data('event');
                if (confirm("Anda yakin ingin menghapus acara ini?")) {
                    $.ajax({
                        url: SITEURL + "/events/" + event.id,
                        data: {
                            _method: 'DELETE'
                        },
                        type: "POST",
                        success: function(response) {
                            calendar.fullCalendar('removeEvents', event.id);
                            calendar.fullCalendar('removeEvents');
                            calendar.fullCalendar('refetchEvents');
                            $('#eventModal').modal('hide');
                            displayMessage("Acara berhasil dihapus");
                        }
                    });
                }
            });

            $('#eventModal').on('hidden.bs.modal', function() {
                $('#eventModalLabel').text('Tambah Acara');
                $('#saveEventBtn').text('Simpan').removeData('event');
                $('#removeEventBtn').hide().removeData('event');
                $('#showEventBtn').hide().removeData('event');
                $('#eventForm')[0].reset();
            });

            $('#showEventBtn').click(function() {
                var event = $(this).data('event');
                window.location.href = SITEURL + "/events/" + event.id;
            });

            function showErrorPopup(message) {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 0,
                    extendedTimeOut: 0,
                    tapToDismiss: true,
                    positionClass: 'toast-top-right',
                    preventDuplicates: true,
                    newestOnTop: true,
                };
                toastr.error(message, "Error");
            }

            function displayMessage(message) {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000,
                    extendedTimeOut: 1000,
                    tapToDismiss: true,
                    positionClass: 'toast-top-right',
                    preventDuplicates: true,
                    newestOnTop: true,
                };

                toastr.success(message);
            }

            function notifyTodaysEvents() {
                $.ajax({
                    url: `${SITEURL}/events/today`,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (Array.isArray(data) && data.length > 0) {
                            const eventList = data.map(event => {
                                const loc = event.location ? `di ${event.location}` : '';
                                return `<li>${event.title} ${loc}</li>`;
                            }).join('');
                            const message = `<ul>${eventList}</ul>`;

                            toastr.options = {
                                closeButton: true,
                                progressBar: true,
                                timeOut: 0,
                                extendedTimeOut: 0,
                                tapToDismiss: false,
                                positionClass: 'toast-top-right',
                                preventDuplicates: true,
                                newestOnTop: true,
                            };

                            toastr.info(message, 'Acara Hari Ini', {
                                allowHtml: true,
                                escapeHtml: false
                            });
                        } else {
                            toastr.options = {
                                closeButton: true,
                                progressBar: true,
                                timeOut: 5000,
                                extendedTimeOut: 1000,
                                tapToDismiss: true,
                                positionClass: 'toast-top-right',
                                preventDuplicates: true,
                                newestOnTop: true,
                            };

                            toastr.info("Tidak ada acara terjadwal untuk hari ini.", 'Info');
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.options = {
                            closeButton: true,
                            progressBar: true,
                            timeOut: 5000,
                            extendedTimeOut: 1000,
                            tapToDismiss: true,
                            positionClass: 'toast-top-right',
                            preventDuplicates: true,
                            newestOnTop: true,
                        };

                        const errorMsg = xhr.status === 404 ?
                            "Endpoint tidak ditemukan." :
                            "Gagal mengambil acara hari ini.";
                        toastr.error(errorMsg, 'Error');
                    }
                });
            }

            notifyTodaysEvents();
        });
    </script>


</body>

</html>
