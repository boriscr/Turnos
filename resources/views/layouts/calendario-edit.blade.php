<!-- CALENDARIO -->
<!-- npm install alpinejs @floating-ui/dom date-fns -->
<!-- Incluí Alpine si no está -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- CALENDARIO -->

<div class="calendario-box" x-data="iosCalendar" x-init="init({{ $fechas ?? '[]' }})">
    <h3>Seleccionar días de atención</h3>

    <div class="calendar-container">

        <div class="mode-selector">
            <button type="button" @click="mode = 'single'" :class="{ 'active': mode === 'single' }">Día único</button>
            <button type="button" @click="mode = 'range'" :class="{ 'active': mode === 'range' }">Rango</button>
        </div>

        <div class="toggle-weekends">
            <label>
                <span>Omitir fines de semana</span>
                <div @click="toggleWeekends()" :class="{ 'enabled': skipWeekends }" class="toggle-switch">
                    <span class="toggle-thumb" :class="{ 'on': skipWeekends }"></span>
                </div>
            </label>
        </div>

        <div class="calendar-header">
            <button @click="previousMonth()" type="button" class="nav-btn"><i class="bi bi-arrow-left-circle-fill"></i></button>
            <h2 x-text="monthNames[currentMonth] + ' ' + currentYear"></h2>
            <button @click="nextMonth()" type="button" class="nav-btn"><i class="bi bi-arrow-right-circle-fill"></i></button>
        </div>

        <div class="weekdays">
            <div>Dom</div><div>Lun</div><div>Mar</div><div>Mié</div><div>Jue</div><div>Vie</div><div>Sáb</div>
        </div>

        <div class="days-grid">
            <template x-for="(day, index) in days" :key="index">
                <button @click="selectDate(day)" 
                    :disabled="day === null || (skipWeekends && isWeekend(day))"
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

    <!-- input oculto para enviar fechas -->
    <input type="hidden" name="selected_dates" x-ref="selectedDatesInput" x-model="selectedDatesJSON">
</div>
