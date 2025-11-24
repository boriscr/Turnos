<!-- Contenedor principal de filtros de fecha -->
<div class="date-filter-container">
    <!-- Botón toggle para cambiar entre filtros (solo móvil) ///-->
    <button type="button" class="date-filter-toggle d-md-none btn btn-outline-secondary mb-2 w-100">
        <span><i class="bi bi-calendar-range"></i> {{ __('button.search.change_filter_type') }}</span>
    </button>

    <!-- Contenedor de los dos filtros -->
    <div class="date-filters-wrapper position-relative">
        <!-- Filtro rápido de fechas (por defecto visible) -->
        <div class="d-flex align-items-center date-filter quick-filter active">
            <!-- Botón para cambiar a rango (escritorio) -->
            <button type="button" class="filter-switch-btn me-2 d-none d-md-block" title="Cambiar a rango de fechas">
                <span><i class="bi bi-chevron-right"></i></span>
            </button>

            <div class="btn-group w-100" role="group">
                <input type="hidden" name="date" id="fechaInput" value="{{ request('date', 'today') }}">

                <button type="button" data-value="yesterday"
                    class="btn {{ request('date', 'today') == 'yesterday' ? 'btn-active' : 'btn-outline-primary' }} date-btn">
                    <span>{{ __('button.search.yesterday') }}</span>
                </button>

                <button type="button" data-value="today"
                    class="btn {{ request('date', 'today') == 'today' ? 'btn-active' : 'btn-outline-primary' }} date-btn">
                    <span>{{ __('button.search.today') }}</span>
                </button>

                <button type="button" data-value="tomorrow"
                    class="btn {{ request('date', 'today') == 'tomorrow' ? 'btn-active' : 'btn-outline-primary' }} date-btn">
                    <span>{{ __('button.search.tomorrow') }}</span>
                </button>
            </div>
        </div>

        <!-- Filtro de rango de fechas (oculto por defecto) -->
        <div class="date-filter range-filter">
            <div class="d-flex align-items-center">
                <!-- Botón para cambiar a filtro rápido -->
                <button type="button" class="filter-switch-btn me-2" title="Cambiar a filtro rápido">
                    <span><i class="bi bi-chevron-left"></i></span>
                </button>

                <div class="row g-2 w-100">
                    <div class="col-md-5">
                        <label class="form-label small mb-1">{{ __('medical.labels.start_date') }}</label>
                        <input type="date" name="start_date" class="form-control form-control-sm"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small mb-1">{{ __('medical.labels.end_date') }}</label>
                        <input type="date" name="end_date" class="form-control form-control-sm"
                            value="{{ request('end_date') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
