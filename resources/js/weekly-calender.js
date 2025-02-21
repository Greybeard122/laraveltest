// weekly-calendar.js
document.addEventListener('DOMContentLoaded', function() {
    const calendar = {
        currentDate: new Date(),
        scheduleData: [],

        init: function() {
            this.container = document.getElementById('weekly-calendar');
            this.prevButton = document.getElementById('prev-week');
            this.nextButton = document.getElementById('next-week');
            
            this.bindEvents();
            this.fetchScheduleData();
        },

        bindEvents: function() {
            this.prevButton.addEventListener('click', () => this.navigateWeek(-1));
            this.nextButton.addEventListener('click', () => this.navigateWeek(1));
        },

        async fetchScheduleData() {
            try {
                const startDate = this.getWeekDates()[0].toISOString().split('T')[0];
                const endDate = this.getWeekDates()[6].toISOString().split('T')[0];
                
                const response = await fetch(`/admin/schedules/date-range?start_date=${startDate}&end_date=${endDate}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                this.scheduleData = await response.json();
                this.renderCalendar();
            } catch (error) {
                console.error('Error fetching schedule:', error);
            }
        },

        navigateWeek: function(direction) {
            this.currentDate.setDate(this.currentDate.getDate() + (direction * 7));
            this.fetchScheduleData();
        },

        getWeekDates: function() {
            const dates = [];
            const current = new Date(this.currentDate);
            current.setDate(current.getDate() - current.getDay()); // Start from Sunday

            for (let i = 0; i < 7; i++) {
                dates.push(new Date(current));
                current.setDate(current.getDate() + 1);
            }
            return dates;
        },

        renderCalendar: function() {
            const weekDates = this.getWeekDates();
            const timeSlots = ['AM', 'PM'];
            
            let html = `
                <div class="grid grid-cols-8 border border-gray-200">
                    <div class="sticky left-0 bg-white z-10 border-r">
                        <div class="h-16 border-b flex items-center justify-center font-bold">
                            Time
                        </div>
                    </div>
            `;

            // Render header days
            weekDates.forEach(date => {
                const isToday = this.isToday(date);
                html += `
                    <div class="text-center ${isToday ? 'bg-blue-50' : ''} border-r">
                        <div class="h-16 border-b flex flex-col items-center justify-center">
                            <div class="font-bold">${date.toLocaleDateString('en-US', { weekday: 'short' })}</div>
                            <div class="text-sm ${isToday ? 'text-blue-600 font-bold' : 'text-gray-600'}">
                                ${date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}
                            </div>
                        </div>
                    </div>
                `;
            });

            // Render AM/PM slots
            timeSlots.forEach(timeSlot => {
                html += `
                    <div class="sticky left-0 bg-white z-10 border-r">
                        <div class="h-32 border-b flex items-center justify-center font-medium text-gray-700">
                            ${timeSlot}
                        </div>
                    </div>
                `;

                weekDates.forEach(date => {
                    const appointments = this.getAppointmentsForTimeSlot(date, timeSlot.toLowerCase());
                    html += `
                        <div class="h-32 border-b border-r relative group hover:bg-gray-50 p-1">
                            ${this.renderAppointments(appointments)}
                        </div>
                    `;
                });
            });

            html += '</div>';
            this.container.innerHTML = html;
        },

        renderAppointments: function(appointments) {
            if (!appointments.length) return '';
            
            return appointments.map(appointment => `
                <div class="mb-1 bg-blue-100 text-blue-800 p-2 rounded text-sm hover:bg-blue-200 transition-colors cursor-pointer"
                     onclick="showAppointmentDetails(${JSON.stringify(appointment).replace(/"/g, '&quot;')})">
                    <div class="font-medium">${appointment.studentName}</div>
                    <div class="text-xs text-blue-600 truncate">${appointment.title}</div>
                </div>
            `).join('');
        },

        getAppointmentsForTimeSlot: function(date, timeSlot) {
            return this.scheduleData.filter(appointment => {
                const appointmentDate = new Date(appointment.date);
                return appointmentDate.toDateString() === date.toDateString() &&
                       appointment.timeSlot.toLowerCase() === timeSlot;
            });
        },

        isToday: function(date) {
            const today = new Date();
            return date.toDateString() === today.toDateString();
        }
    };

    calendar.init();

    // Add to window scope for onclick handler
    window.showAppointmentDetails = function(appointment) {
        Swal.fire({
            title: 'Appointment Details',
            html: `
                <div class="text-left">
                    <p><strong>Student:</strong> ${appointment.studentName}</p>
                    <p><strong>File:</strong> ${appointment.title}</p>
                    <p><strong>Date:</strong> ${new Date(appointment.date).toLocaleDateString()}</p>
                    <p><strong>Time:</strong> ${appointment.timeSlot.toUpperCase()}</p>
                    ${appointment.remarks ? `<p><strong>Remarks:</strong> ${appointment.remarks}</p>` : ''}
                </div>
            `,
            confirmButtonText: 'Close',
            customClass: {
                container: 'appointment-modal'
            }
        });
    };
});