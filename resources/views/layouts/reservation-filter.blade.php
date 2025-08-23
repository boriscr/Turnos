            <!-- Barra de búsqueda y filtros -->
            <div class="search-filters mb-4">
                <!-- Barra de búsqueda existente -->
                <form action="{{ route('reservations.index') }}" method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="Buscar por name, surname o DNI..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-active">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                        @if (request('search'))
                            <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Limpiar
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Filtro de available_dates -->
                <div class="date-filters">
                    <form action="{{ route('reservations.index') }}" method="GET" id="dateFilterForm">
                        <input type="hidden" name="search" value="{{ request('search') }}">

                        <div class="btn-group" role="group">
                            <button type="submit" name="date" value="anteriores"
                                class="btn {{ request('date') == 'anteriores' ? 'btn-active' : 'btn-outline-primary' }}">
                                Anteriores
                            </button>
                            <button type="submit" name="date" value="hoy"
                                class="btn {{ request('date', 'hoy') == 'hoy' ? 'btn-active' : 'btn-outline-primary' }}">
                                Hoy
                            </button>
                            <button type="submit" name="date" value="futuros"
                                class="btn {{ request('date') == 'futuros' ? 'btn-active' : 'btn-outline-primary' }}">
                                Futuros
                            </button>
                        </div>
                        <hr>

                        <div class="row mt-2">
                            <div class="col-md-5">
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ request('start_date') }}" placeholder="Fecha inicio">
                            </div>
                            <div class="col-md-5">
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ request('end_date') }}" placeholder="Fecha fin">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" name="date" value="personalizado"
                                    class="btn btn-default w-100">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
