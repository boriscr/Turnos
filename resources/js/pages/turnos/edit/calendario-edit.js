document.addEventListener('alpine:init', () => {
    Alpine.data('iosCalendar', () => ({
        currentDate: new Date(),
        selectedDates: [],
        mode: 'single',
        skipWeekends: true,
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio','Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],

        get selectedDatesJSON() {
            return JSON.stringify(this.selectedDates.map(date => this.formatDate(date)));
        },
        get currentYear() { return this.currentDate.getFullYear(); },
        get currentMonth() { return this.currentDate.getMonth(); },

        get days() {
            const year = this.currentYear;
            const month = this.currentMonth;
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDay = firstDay.getDay();
            const days = [];

            for (let i = 0; i < startingDay; i++) { days.push(null); }
            for (let i = 1; i <= daysInMonth; i++) { days.push(new Date(year, month, i)); }
            const totalCells = Math.ceil(days.length / 7) * 7;
            while (days.length < totalCells) { days.push(null); }
            return days;
        },

        init(preselected = []) {
            this.currentDate = new Date();
            if (typeof preselected === 'string') {
                preselected = JSON.parse(preselected);
            }
            this.selectedDates = preselected.map(dateStr => {
                let parts = dateStr.split('-');
                return new Date(parts[0], parts[1] - 1, parts[2]);
            });
            this.updateSelectedDatesInput();
        },

        previousMonth() {
            this.currentDate = new Date(this.currentYear, this.currentMonth - 1, 1);
        },

        nextMonth() {
            this.currentDate = new Date(this.currentYear, this.currentMonth + 1, 1);
        },

        formatDate(date) {
            if (!date) return null;
            return date.toISOString().split('T')[0];
        },

        isToday(date) {
            if (!date) return false;
            const today = new Date();
            return date.getDate() === today.getDate() &&
                   date.getMonth() === today.getMonth() &&
                   date.getFullYear() === today.getFullYear();
        },

        isWeekend(date) {
            if (!date) return false;
            return date.getDay() === 0 || date.getDay() === 6;
        },

        isSelected(date) {
            if (!date) return false;
            return this.selectedDates.some(d => d.toDateString() === date.toDateString());
        },

        isInRange(date) {
            if (this.mode !== 'range' || this.selectedDates.length < 2) return false;
            const start = this.selectedDates[0];
            const end = this.selectedDates[this.selectedDates.length - 1];
            return date >= start && date <= end;
        },

        toggleWeekends() {
            this.skipWeekends = !this.skipWeekends;
            if (this.skipWeekends) {
                this.selectedDates = this.selectedDates.filter(date => !this.isWeekend(date));
            }
        },

        selectDate(date) {
            if (!date || (this.skipWeekends && this.isWeekend(date))) return;
            const index = this.selectedDates.findIndex(d => d.toDateString() === date.toDateString());

            if (index >= 0) {
                this.selectedDates.splice(index, 1);
            } else {
                if (this.mode === 'single') {
                    this.selectedDates.push(date);
                } else {
                    if (this.selectedDates.length === 0 || this.selectedDates.length > 1) {
                        this.selectedDates = [date];
                    } else {
                        const start = this.selectedDates[0];
                        const end = date;
                        const [startDate, endDate] = start < end ? [start, end] : [end, start];
                        const allDates = [];
                        let currentDate = new Date(startDate);
                        while (currentDate <= endDate) {
                            if (!this.skipWeekends || !this.isWeekend(currentDate)) {
                                allDates.push(new Date(currentDate));
                            }
                            currentDate.setDate(currentDate.getDate() + 1);
                        }
                        this.selectedDates = allDates;
                    }
                }
            }
            this.updateSelectedDatesInput();
        },

        updateSelectedDatesInput() {
            if (this.$refs.selectedDatesInput) {
                this.$refs.selectedDatesInput.value = this.selectedDatesJSON;
            }
        }
    }))
});
