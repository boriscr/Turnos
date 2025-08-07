<!-- CALENDARIO -->
<!-- npm install alpinejs @floating-ui/dom date-fns -->
<div class="calendario-box">
    <H3>Seleccionar días de atención</H3>
    <div class="calendar-container">
        <!-- Selector de modo -->

        <div class="mode-selector">
            <button type="button" @click="mode = 'single'" :class="{ 'active': mode === 'single' }">Día único</button>
            <button type="button" @click="mode = 'range'" :class="{ 'active': mode === 'range' }">Rango</button>
        </div>

        <!-- Toggle días no laborables -->
        <div class="toggle-weekends">
            <label>
                <span>Omitir fines de semana</span>
                <div @click="toggleWeekends()" :class="{ 'enabled': skipWeekends }" class="toggle-switch">
                    <span class="toggle-thumb" :class="{ 'on': skipWeekends }"></span>
                </div>
            </label>
        </div>

        <!-- Header del calendario -->
        <div class="calendar-header">
            <button @click="previousMonth" type="button" class="nav-btn"><i
                    class="bi bi-arrow-left-circle-fill"></i></button>
            <h2 x-text="monthNames[currentMonth] + ' ' + currentYear"></h2>
            <button @click="nextMonth" type="button" class="nav-btn"><i
                    class="bi bi-arrow-right-circle-fill"></i></button>
        </div>

        <!-- Días de la semana -->
        <div class="weekdays" id="weekdays">
            <div>Dom</div>
            <div>Lun</div>
            <div>Mar</div>
            <div>Mié</div>
            <div>Jue</div>
            <div>Vie</div>
            <div>Sáb</div>
        </div>

        <!-- Días del mes -->
        <div class="days-grid">
            <template x-for="(day, index) in days" :key="index">
                <button @click="selectDate(day)" :disabled="day === null || (skipWeekends && isWeekend(day))"
                    :class="{
                        'selected': isSelected(day),
                        'range': isInRange(day),
                        'disabled': day === null || (skipWeekends && isWeekend(day)),
                        'today': isToday(day) && !isSelected(day),
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

<!-- FIN CALENDARIO -->
