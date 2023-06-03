/**
 * App Calendar
 */

/**
 * ! If both start and end dates are same Full calendar will nullify the end date value.
 * ! Full calendar will end the event on a day before at 12:00:00AM thus, event won't extend to the end date.
 * ! We are getting events from a separate file named app-calendar-events.js. You can add or remove events from there.
 **/

'use-strict';

// RTL Support
var direction = 'ltr',
  assetPath = '../../../app-assets/';
if ($('html').data('textdirection') == 'rtl') {
  direction = 'rtl';
}

if ($('body').attr('data-framework') === 'laravel') {
  assetPath = $('body').attr('data-asset-path');
}

$(document).on('click', '.fc-sidebarToggle-button', function (e) {
  $('.app-calendar-sidebar, .body-content-overlay').addClass('show');
});

$(document).on('click', '.body-content-overlay', function (e) {
  $('.app-calendar-sidebar, .body-content-overlay').removeClass('show');
});

function set_form_schedules(events, delete_event = null){
    var sch_data = [];
    events.map(event => {
        sch_data.push({
            id:event.id,
            is_new:event.extendedProps.is_new,
            title:event.title,
            start_date:event._instance.range.start,
            end_date:event._instance.range.end,
            description:event.extendedProps.description,
            patient_id:eventData.extendedProps.guests.split('_')[0],
            patient_transaction_id : eventData.extendedProps.guests.split('_')[1],
        })
    })
    $('#booked_schedules').val(JSON.stringify(sch_data));
    if (delete_event != null){
        var deleted_schedule_ids = $('#deleted_schedules').val();
        if (deleted_schedule_ids != undefined && deleted_schedule_ids != null && deleted_schedule_ids != ''){
            deleted_schedule_ids = JSON.parse(deleted_schedule_ids);
        } else {
            deleted_schedule_ids = [];
        }
        deleted_schedule_ids.push(delete_event);
        $('#deleted_schedules').val(JSON.stringify(deleted_schedule_ids));
    }
}

document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar'),
        eventToUpdate,
        sidebar = $('.event-sidebar'),
        calendarsColor = {
        Business: 'primary',
        Holiday: 'success',
        Personal: 'danger',
        Family: 'warning',
        ETC: 'info'
        },
        eventForm = $('.event-form'),
        addEventBtn = $('.add-event-btn'),
        cancelBtn = $('.btn-cancel'),
        updateEventBtn = $('.update-event-btn'),
        toggleSidebarBtn = $('.btn-toggle-sidebar'),
        eventTitle = $('#title'),
        eventLabel = $('#select-label'),
        startDate = $('#start-date'),
        endDate = $('#end-date'),
        // eventUrl = $('#event-url'),
        eventGuests = $('#event-guests'),
        eventLocation = $('#event-location'),
        allDaySwitch = $('.allDay-switch'),
        selectAll = $('.select-all'),
        calEventFilter = $('.calendar-events-filter'),
        filterInput = $('.input-filter'),
        btnDeleteEvent = $('.btn-delete-event'),
        calendarEditor = $('#event-description-editor');

  // --------------------------------------------
  // On add new item, clear sidebar-right field fields
  // --------------------------------------------
    $('.add-event button').on('click', function (e) {
        $('.event-sidebar').addClass('show');
        $('.sidebar-left').removeClass('show');
        $('.app-calendar .body-content-overlay').addClass('show');
    });

  // Label  select
  if (eventLabel.length) {
    function renderBullets(option) {
      if (!option.id) {
        return option.text;
      }
      var $bullet =
        "<span class='bullet bullet-" +
        $(option.element).data('label') +
        " bullet-sm me-50'> " +
        '</span>' +
        option.text;

      return $bullet;
    }
    eventLabel.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Select value',
      dropdownParent: eventLabel.parent(),
      templateResult: renderBullets,
      templateSelection: renderBullets,
      minimumResultsForSearch: -1,
      escapeMarkup: function (es) {
        return es;
      }
    });
  }

  // Guests select
  if (eventGuests.length) {
    function renderGuestAvatar(option) {
      if (!option.id) {
        return option.text;
      }

      var $avatar =
        "<div class='d-flex flex-wrap align-items-center'>" +
        "<div class='avatar avatar-sm my-0 me-50'>" +
        "<span class='avatar-content'>" +
        "<img src='" +
        assetPath +
        'images/avatars/' +
        $(option.element).data('avatar') +
        "' alt='avatar' />" +
        '</span>' +
        '</div>' +
        option.text +
        '</div>';

      return $avatar;
    }
    eventGuests.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Select value',
      dropdownParent: eventGuests.parent(),
      closeOnSelect: false,
      templateResult: renderGuestAvatar,
      templateSelection: renderGuestAvatar,
      escapeMarkup: function (es) {
        return es;
      }
    });
  }

  // Start date picker
  if (startDate.length) {
    var start = startDate.flatpickr({
      enableTime: true,
      altFormat: 'Y-m-dTH:i:S',
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      }
    });
  }

  // End date picker
  if (endDate.length) {
    var end = endDate.flatpickr({
      enableTime: true,
      altFormat: 'Y-m-dTH:i:S',
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      }
    });
  }

  // Event click function
  function eventClick(info) {
    eventToUpdate = info.event;
    if (eventToUpdate.url) {
      info.jsEvent.preventDefault();
      window.open(eventToUpdate.url, '_blank');
    }

    sidebar.modal('show');
    addEventBtn.addClass('d-none');
    cancelBtn.addClass('d-none');
    updateEventBtn.removeClass('d-none');
    btnDeleteEvent.removeClass('d-none');

    eventTitle.val(eventToUpdate.title);
    start.setDate(eventToUpdate.start, true, 'Y-m-d');
    eventToUpdate.allDay === true ? allDaySwitch.prop('checked', true) : allDaySwitch.prop('checked', false);
    eventToUpdate.end !== null
      ? end.setDate(eventToUpdate.end, true, 'Y-m-d')
      : end.setDate(eventToUpdate.start, true, 'Y-m-d');
    sidebar.find(eventLabel).val(eventToUpdate.extendedProps.calendar).trigger('change');
    eventToUpdate.extendedProps.location !== undefined ? eventLocation.val(eventToUpdate.extendedProps.location) : null;

    eventToUpdate.extendedProps.guests !== undefined
      ? eventGuests.val(eventToUpdate.extendedProps.guests).trigger('change')
      : null;
    eventToUpdate.extendedProps.description !== undefined
      ? calendarEditor.val(eventToUpdate.extendedProps.description)
      : null;

    //  Delete Event
    btnDeleteEvent.on('click', function () {
        Swal.fire({
            html: "<div class='mb-7'>Do you confirm to delete this schedule?</div>",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-active-light"
            }
        }).then(function (res){
            if (res.value){
                eventToUpdate.remove();
                //set_form_schedules(calendar.getEvents(), eventToUpdate.id);
                //removeEvent(eventToUpdate.id);
                sidebar.modal('hide');
                // $('.event-sidebar').removeClass('show');
                $('.app-calendar .body-content-overlay').removeClass('show');

                $.ajax({
                    url:"/calendar/action",
                    method:"POST",
                    data:{
                        id:eventToUpdate.id,
                        type: 'delete'
                    },
                    success:function()
                    {
                        // calendar.fullCalendar('refetchEvents');
                        // alert("Event deleted Successfully");
                    }
                })
                location.reload();
            }
        })
    });
  }

  // Modify sidebar toggler
  function modifyToggler() {
    $('.fc-sidebarToggle-button')
      .empty()
      .append(feather.icons['menu'].toSvg({ class: 'ficon' }));
  }

  // Selected Checkboxes
  function selectedCalendars() {
    var selected = [];
    $('.calendar-events-filter input:checked').each(function () {
      selected.push($(this).attr('data-value'));
    });
    return selected;
  }

  // --------------------------------------------------------------------------------------------------
  // AXIOS: fetchEvents
  // * This will be called by fullCalendar to fetch events. Also this can be used to refetch events.
  // --------------------------------------------------------------------------------------------------
  function fetchEvents(info, successCallback) {
    // Fetch Events from API endpoint reference
    /* $.ajax(
      {
        url: '../../../app-assets/data/app-calendar-events.js',
        type: 'GET',
        success: function (result) {
          // Get requested calendars as Array
          var calendars = selectedCalendars();

          return [result.events.filter(event => calendars.includes(event.extendedProps.calendar))];
        },
        error: function (error) {
          console.log(error);
        }
      }
    ); */

    var calendars = selectedCalendars();
    // We are reading event object from app-calendar-events.js file directly by including that file above app-calendar file.
    // You should make an API call, look into above commented API call for reference
    selectedEvents = events.filter(function (event) {
      return calendars.includes(event.extendedProps.calendar.toLowerCase());
    });

    // if (selectedEvents.length > 0) {
    // successCallback(selectedEvents);
    successCallback(events);
    // }
  }

  // Calendar plugins
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    events: fetchEvents,
    editable: true,
    dragScroll: true,
    dayMaxEvents: 2,
    eventResizableFromStart: true,
    customButtons: {
      sidebarToggle: {
        text: 'Sidebar'
      }
    },
    headerToolbar: {
      start: 'sidebarToggle, prev,next, title',
      end: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
    },
    direction: direction,
    initialDate: new Date(),
    navLinks: true, // can click day/week names to navigate views
    eventClassNames: function ({ event: calendarEvent }) {
      const colorName = calendarsColor[calendarEvent._def.extendedProps.calendar];

      return [
        // Background Color
        'bg-light-' + colorName
      ];
    },
    dateClick: function (info) {
      var date = moment(info.date).format('YYYY-MM-DD');
      resetValues();
      sidebar.modal('show');
      addEventBtn.removeClass('d-none');
      updateEventBtn.addClass('d-none');
      btnDeleteEvent.addClass('d-none');
      startDate.val(date);
      endDate.val(date);
    },
    eventClick: function (info) {
      eventClick(info);
    },
    eventDidMount : function(arg) {
        var patient_key = arg.event.extendedProps.guests;
        var patient_element = '';
        if (!isNaN(parseInt(patient_key)) && patient_object[patient_key] != undefined){
            patient_element = '<div><li class="event-patient">' + patient_object[patient_key] + '</li></div>';
        }
        if ($(arg.el).hasClass('fc-daygrid-dot-event')){
            $(arg.el).css('display', 'block');
            var new_sub_parent = $('<div style="display:flex">');
            $(arg.el).children().appendTo(new_sub_parent);
            new_sub_parent.appendTo($(arg.el));
            $(arg.el).append(patient_element);
        } else {
            $(arg.el).append(patient_element);
        }

    },
    datesSet: function () {
      modifyToggler();
    },
    viewDidMount: function () {
      modifyToggler();
    }
  });

  // Render calendar
  calendar.render();
  // Modify sidebar toggler
  modifyToggler();
  // updateEventClass();

  // Validate add new and update form
  $.validator.addMethod("sepcRequired", function(value, element, arg){
    if (eventToUpdate){
        return true;
    } else {
        return value != null;
    }
   }, "This field is required.");

  if (eventForm.length) {
    eventForm.validate({
      submitHandler: function (form, event) {
        event.preventDefault();
        if (eventForm.valid()) {
        //   sidebar.modal('hide');
        }
      },
      rules: {
        'start-date': { required: true },
        'end-date': { required: true },
        'title': { required: true },
        'guests': { sepcRequired: 'default' },
      },
      messages: {
        'start-date': { required: 'Start Date is required' },
        'end-date': { required: 'End Date is required' }
      },
      errorElement : 'div',
      errorPlacement: function(error, element) {
        // var placement = $(element).data('error');
        // if (placement) {
        //   $(placement).append(error)
        // } else {
          error.insertBefore(element);
        // }
      }
    });
  }

  // Sidebar Toggle Btn
  if (toggleSidebarBtn.length) {
    toggleSidebarBtn.on('click', function () {
      cancelBtn.removeClass('d-none');
    });
  }

  // ------------------------------------------------
  // addEvent
  // ------------------------------------------------
  function addEvent(eventData) {
    eventData.extendedProps.calendar = 'Business';
    calendar.addEvent(eventData);
    // calendar.refetchEvents();
    // set_form_schedules(calendar.getEvents());
    $.ajax({
        url:"/calendar/action",
        method:"POST",
        data:{
            title: eventData.title,
            start_date: eventData.start,
            end_date: eventData.end,
            patient_id:eventData.extendedProps.guests.split('_')[0],
            patient_transaction_id : eventData.extendedProps.guests.split('_')[1],
            description:eventData.extendedProps.description,
            type: 'add'
        },
        success:function()
        {
            // calendar.fullCalendar('refetchEvents');
            // alert("Event Created Successfully");
        }
    })
    location.reload();
  }

  // ------------------------------------------------
  // updateEvent
  // ------------------------------------------------
  function updateEvent(eventData) {
    var propsToUpdate = ['id', 'title'];
    var extendedPropsToUpdate = ['calendar', 'guests', 'location', 'description'];
    eventData.extendedProps.calendar = 'Business';
    updateEventInCalendar(eventData, propsToUpdate, extendedPropsToUpdate);
    // set_form_schedules(calendar.getEvents());
    $.ajax({
        url:"/calendar/action",
        method:"POST",
        data:{
            id:eventData.id,
            title: eventData.title,
            start_date: eventData.start,
            end_date: eventData.end,
            patient_id:eventData.extendedProps.guests != null ? eventData.extendedProps.guests.split('_')[0] : null,
            patient_transaction_id : eventData.extendedProps.guests != null ? eventData.extendedProps.guests.split('_')[1] : null,
            description:eventData.extendedProps.description,
            type: 'edit'
        },
        success:function()
        {
            // calendar.fullCalendar('refetchEvents');
            // alert("Event Edited Successfully");
        }
    })
    location.reload();
  }

  // ------------------------------------------------
  // removeEvent
  // ------------------------------------------------
  function removeEvent(eventId) {
    removeEventInCalendar(eventId);
  }

  // ------------------------------------------------
  // (UI) updateEventInCalendar
  // ------------------------------------------------
  const updateEventInCalendar = (updatedEventData, propsToUpdate, extendedPropsToUpdate) => {
    const existingEvent = calendar.getEventById(updatedEventData.id);

    // --- Set event properties except date related ----- //
    // ? Docs: https://fullcalendar.io/docs/Event-setProp
    // dateRelatedProps => ['start', 'end', 'allDay']
    // eslint-disable-next-line no-plusplus
    for (var index = 0; index < propsToUpdate.length; index++) {
      var propName = propsToUpdate[index];
      existingEvent.setProp(propName, updatedEventData[propName]);
    }

    // --- Set date related props ----- //
    // ? Docs: https://fullcalendar.io/docs/Event-setDates
    existingEvent.setDates(updatedEventData.start, updatedEventData.end, { allDay: updatedEventData.allDay });

    // --- Set event's extendedProps ----- //
    // ? Docs: https://fullcalendar.io/docs/Event-setExtendedProp
    // eslint-disable-next-line no-plusplus
    for (var index = 0; index < extendedPropsToUpdate.length; index++) {
      var propName = extendedPropsToUpdate[index];
      existingEvent.setExtendedProp(propName, updatedEventData.extendedProps[propName]);
    }
  };

  // ------------------------------------------------
  // (UI) removeEventInCalendar
  // ------------------------------------------------
  function removeEventInCalendar(eventId) {
    calendar.getEventById(eventId).remove();
  }

  // Add new event
  $(addEventBtn).on('click', function () {
    if (eventForm.valid()) {
        Swal.fire({
            html: "<div class='mb-7'>Do you confirm to add this schedule?</div>",
            icon: "info",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-active-light"
            }
        }).then(function (res){
            if (res.value){
                var max_id = 0;
                calendar.getEvents().map(event_item => {
                    if (max_id < parseInt(event_item.id)){
                        max_id = parseInt(event_item.id);
                    }
                })
                var newEvent = {
                    id: max_id + 1,
                    title: eventTitle.val(),
                    start: startDate.val(),
                    end: endDate.val(),
                    startStr: startDate.val(),
                    endStr: endDate.val(),
                    display: 'block',
                    extendedProps: {
                    location: eventLocation.val(),
                    guests: eventGuests.val(),
                    calendar: eventLabel.val(),
                    description: calendarEditor.val()
                    }
                };
                // if (eventUrl.val().length) {
                //     newEvent.url = eventUrl.val();
                // }
                if (allDaySwitch.prop('checked')) {
                    newEvent.allDay = true;
                }
                addEvent(newEvent);
                sidebar.modal('hide');
            }
        })
    }
  });

  // Update new event
  updateEventBtn.on('click', function () {
    if (eventForm.valid()) {
        Swal.fire({
            html: "<div class='mb-7'>Do you confirm to update this schedule?</div>",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-active-light"
            }
        }).then(function (res){
            if (res.value){
                var eventData = {
                    id: eventToUpdate.id,
                    title: sidebar.find(eventTitle).val(),
                    start: sidebar.find(startDate).val(),
                    end: sidebar.find(endDate).val(),
                    // url: eventUrl.val(),
                    extendedProps: {
                        location: eventLocation.val(),
                        guests: eventGuests.val(),
                        calendar: eventLabel.val(),
                        description: calendarEditor.val()
                        },
                    display: 'block',
                    allDay: allDaySwitch.prop('checked') ? true : false
                };
                updateEvent(eventData);
                sidebar.modal('hide');
            }
        })
    }
  });

  // Reset sidebar input values
  function resetValues() {
    endDate.val('');
    // eventUrl.val('');
    startDate.val('');
    eventTitle.val('');
    eventLocation.val('');
    allDaySwitch.prop('checked', false);
    eventGuests.val('').trigger('change');
    calendarEditor.val('');
  }

  // When modal hides reset input values
  sidebar.on('hidden.bs.modal', function () {
    resetValues();
  });

  // Hide left sidebar if the right sidebar is open
  $('.btn-toggle-sidebar').on('click', function () {
    btnDeleteEvent.addClass('d-none');
    updateEventBtn.addClass('d-none');
    addEventBtn.removeClass('d-none');
    $('.app-calendar-sidebar, .body-content-overlay').removeClass('show');
  });

  // Select all & filter functionality
  if (selectAll.length) {
    selectAll.on('change', function () {
      var $this = $(this);

      if ($this.prop('checked')) {
        calEventFilter.find('input').prop('checked', true);
      } else {
        calEventFilter.find('input').prop('checked', false);
      }
    //   calendar.refetchEvents();
    });
  }

  if (filterInput.length) {
    filterInput.on('change', function () {
      $('.input-filter:checked').length < calEventFilter.find('input').length
        ? selectAll.prop('checked', false)
        : selectAll.prop('checked', true);
    //   calendar.refetchEvents();
    });
  }
});
