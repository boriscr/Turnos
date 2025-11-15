<!-- CALENDARIO -->
<!-- npm install alpinejs @floating-ui/dom date-fns -->
<!-- CALENDARIO -->
<div class="calendario-box">
    <strong>{{ __('appointment.date.title') }}</strong>

    <!-- Contador de combinaciones -->
    <div class="combinations-counter" :class="currentCombinations > MAX_COMBINATIONS ? 'exceeded' : 'within-limit'"
        x-text="`Combinaciones: ${currentCombinations.toLocaleString()} / ${MAX_COMBINATIONS.toLocaleString()}`">
    </div>

    <div class="calendar-container">
        <!-- Selector de modo -->
        <div class="mode-selector">
            <button type="button" @click="mode = 'single'"
                :class="{ 'active': mode === 'single' }"><span>{{ __('appointment.date.single_day') }}</span></button>
            <button type="button" @click="mode = 'range'"
                :class="{ 'active': mode === 'range' }"><span>{{ __('appointment.date.range') }}</span></button>
        </div>

        <!-- Toggle días no laborables -->
        <div class="toggle-weekends">
            <label>
                <span>{{ __('appointment.date.toggle_weekends') }}</span>
                <div @click="toggleWeekends()" :class="{ 'enabled': skipWeekends }" class="toggle-switch">
                    <span class="toggle-thumb" :class="{ 'on': skipWeekends }"></span>
                </div>
            </label>
        </div>

        <!-- Header del calendario -->
        <div class="calendar-header">
            <button @click="previousMonth" type="button" class="nav-btn"><i
                    class="bi bi-arrow-left-circle-fill"></i></button>
            <b x-text="monthNames[currentMonth] + ' ' + currentYear"></b>
            <button @click="nextMonth" type="button" class="nav-btn"><i
                    class="bi bi-arrow-right-circle-fill"></i></button>
        </div>

        <!-- Días de la semana -->
        <div class="weekdays" id="weekdays">
            <div>{{ __('appointment.date.su') }}</div>
            <div>{{ __('appointment.date.mo') }}</div>
            <div>{{ __('appointment.date.tu') }}</div>
            <div>{{ __('appointment.date.we') }}</div>
            <div>{{ __('appointment.date.th') }}</div>
            <div>{{ __('appointment.date.fr') }}</div>
            <div>{{ __('appointment.date.sa') }}</div>
        </div>

        <!-- Días del mes -->
        <div class="days-grid">
            <template x-for="(day, index) in days" :key="index">
                <button @click="selectDate(day)" :disabled="day === null || (skipWeekends && isWeekend(day))"
                    :class="{
                        'selected': isSelected(day) && !isExceeded(day),
                        'exceeded': isExceeded(day),
                        'range': isInRange(day),
                        'disabled': day === null || (skipWeekends && isWeekend(day)),
                        'today': isToday(day) && !isSelected(day) && !isExceeded(day),
                        'weekend': isWeekend(day)
                    }"
                    type="button">
                    <span x-text="day ? day.getDate() : ''"></span>
                </button>
            </template>
        </div>
    </div>
</div>
<input type="hidden" name="selected_dates" x-ref="selectedDatesInput">
