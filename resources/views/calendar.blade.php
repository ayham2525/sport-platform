<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Attendance Calendar - Times & Ordering</title>

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/index.global.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.8/index.global.min.css" rel="stylesheet">

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
    }
    #calendar {
      max-width: 900px;
      margin: auto;
    }
    #copy-week-button {
      display: inline-block;
      margin: 10px 0;
      padding: 8px 12px;
      background: #007bff;
      color: #fff;
      border: none;
      cursor: pointer;
      border-radius: 4px;
    }
    #copy-week-button:hover {
      background: #0056b3;
    }
    #context-menu {
      position: absolute;
      display: none;
      background: #fff;
      border: 1px solid #ccc;
      z-index: 9999;
      box-shadow: 2px 2px 6px rgba(0,0,0,0.2);
    }
    #context-menu ul {
      list-style: none;
      margin: 0;
      padding: 0;
    }
    #context-menu li {
      padding: 8px 12px;
      cursor: pointer;
    }
    #context-menu li:hover {
      background: #f0f0f0;
    }
  </style>
</head>
<body>
  <h2>Attendance Calendar - Times & Ordering</h2>
  <button id="copy-week-button">Copy This Week to Next Week</button>
  <div id="calendar"></div>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.8/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.8/index.global.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const calendarEl = document.getElementById('calendar');
      const contextMenu = document.getElementById('context-menu');
      const copyWeekButton = document.getElementById('copy-week-button');
      let copiedEvent = null;
      let currentEvent = null;

      const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        editable: true,
        selectable: true,
        dateClick: function(info) {
          if (copiedEvent) {
            calendar.addEvent({
              title: copiedEvent.title + ' (Pasted)',
              start: info.dateStr,
              color: copiedEvent.backgroundColor
            });
            alert('Event pasted on ' + info.dateStr);
            copiedEvent = null;
          }
        },
        eventDidMount: function(info) {
          info.el.addEventListener('contextmenu', function(ev) {
            ev.preventDefault();
            currentEvent = info.event;

            contextMenu.style.top = ev.pageY + 'px';
            contextMenu.style.left = ev.pageX + 'px';
            contextMenu.style.display = 'block';
          });
        },
        eventDrop: function(info) {
          alert(
            'Event moved to ' +
            info.event.start.toISOString().slice(0,16).replace('T',' ')
          );
        },
        events: [
          {
            title: 'Morning Shift - John',
            start: '2025-07-01T08:00:00',
            end: '2025-07-01T12:00:00',
            color: '#28a745'
          },
          {
            title: 'Afternoon Shift - Anna',
            start: '2025-07-01T13:00:00',
            end: '2025-07-01T17:00:00',
            color: '#dc3545'
          },
          {
            title: 'Meeting',
            start: '2025-07-02T10:30:00',
            end: '2025-07-02T11:30:00',
            color: '#007bff'
          }
        ],
        weekends: true,
        nowIndicator: true
      });
      calendar.render();

      // Hide context menu on any click
      document.addEventListener('click', function() {
        contextMenu.style.display = 'none';
      });

      // Duplicate this event to next week
      document.getElementById('duplicate-event').addEventListener('click', function() {
        if (currentEvent) {
          const newStart = new Date(currentEvent.start);
          newStart.setDate(newStart.getDate() + 7);

          let newEnd = null;
          if (currentEvent.end) {
            newEnd = new Date(currentEvent.end);
            newEnd.setDate(newEnd.getDate() + 7);
          }

          calendar.addEvent({
            title: currentEvent.title + ' (Copy)',
            start: newStart.toISOString(),
            end: newEnd ? newEnd.toISOString() : undefined,
            color: currentEvent.backgroundColor
          });
          alert('Duplicated to ' + newStart.toISOString().slice(0,10));
        }
        contextMenu.style.display = 'none';
      });

      // Copy this event
      document.getElementById('copy-event').addEventListener('click', function() {
        if (currentEvent) {
          copiedEvent = {
            title: currentEvent.title,
            backgroundColor: currentEvent.backgroundColor
          };
          alert('Event copied! Click a date/time slot to paste.');
        }
        contextMenu.style.display = 'none';
      });

      // Copy all events in this week to next week
      copyWeekButton.addEventListener('click', function() {
        const view = calendar.view;
        const startDate = new Date(view.currentStart);
        const endDate = new Date(view.currentEnd);

        const eventsInWeek = calendar.getEvents().filter(event => {
          return event.start >= startDate && event.start < endDate;
        });

        if (eventsInWeek.length === 0) {
          alert("No events to copy in this week.");
          return;
        }

        eventsInWeek.forEach(event => {
          const newStart = new Date(event.start);
          newStart.setDate(newStart.getDate() + 7);

          let newEnd = null;
          if (event.end) {
            newEnd = new Date(event.end);
            newEnd.setDate(newEnd.getDate() + 7);
          }

          calendar.addEvent({
            title: event.title + " (Copy)",
            start: newStart.toISOString(),
            end: newEnd ? newEnd.toISOString() : undefined,
            color: event.backgroundColor
          });
        });

        alert(eventsInWeek.length + " event(s) duplicated to next week.");
      });
    });
  </script>

  <!-- Context Menu -->
  <div id="context-menu">
    <ul>
      <li id="duplicate-event">Duplicate This Event to Next Week</li>
      <li id="copy-event">Copy</li>
    </ul>
  </div>
</body>
</html>
