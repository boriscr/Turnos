            <!-- Barra de búsqueda y filtros -->
            <div class="search-filters mb-4">
                <!-- Barra de búsqueda existente -->
                <form action="{{ route('reservas.index') }}" method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="Buscar por name, surname o DNI..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-default">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                        @if (request('search'))
                            <a href="{{ route('reservas.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Limpiar
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Filtro de fechas -->
                <div class="date-filters">
                    <form action="{{ route('reservas.index') }}" method="GET" id="dateFilterForm">
                        <input type="hidden" name="search" value="{{ request('search') }}">

                        <div class="btn-group" role="group">
                            <button type="submit" name="fecha" value="anteriores"
                                class="btn {{ request('fecha') == 'anteriores' ? 'btn-default' : 'btn-outline-primary' }}">
                                Anteriores
                            </button>
                            <button type="submit" name="fecha" value="hoy"
                                class="btn {{ request('fecha', 'hoy') == 'hoy' ? 'btn-default' : 'btn-outline-primary' }}">
                                Hoy
                            </button>
                            <button type="submit" name="fecha" value="futuros"
                                class="btn {{ request('fecha') == 'futuros' ? 'btn-default' : 'btn-outline-primary' }}">
                                Futuros
                            </button>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-5">
                                <input type="date" name="fecha_inicio" class="form-control"
                                    value="{{ request('fecha_inicio') }}" placeholder="Fecha inicio">
                            </div>
                            <div class="col-md-5">
                                <input type="date" name="fecha_fin" class="form-control"
                                    value="{{ request('fecha_fin') }}" placeholder="Fecha fin">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" name="fecha" value="personalizado"
                                    class="btn btn-default w-100">
                                    <i class="bi bi-filter"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
