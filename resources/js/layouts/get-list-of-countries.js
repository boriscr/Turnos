// public/js/location-selector.js
export class LocationSelector {
    constructor() {
        this.countrySelect = null;
        this.stateSelect = null;
        this.citySelect = null;
        this.isInitialLoad = true;
        this.currentStateId = null;
        this.currentCityId = null;
        this.initialized = false;
        this.lastLoadedStateId = null;
    }

    init() {
        if (this.initialized) return;

        this.countrySelect = document.getElementById('country_id');
        this.stateSelect = document.getElementById('state_id');
        this.citySelect = document.getElementById('city_id');

        if (!this.countrySelect || !this.stateSelect || !this.citySelect) {
            console.error('Location select elements not found');
            return;
        }

        // Guardar valores iniciales desde los elementos, no de dataset
        this.currentStateId = this.stateSelect.value;
        this.currentCityId = this.citySelect.value;
        this.lastLoadedStateId = this.currentStateId;

        console.log('Location selector initialized', {
            country: this.countrySelect.value,
            state: this.currentStateId,
            city: this.currentCityId,
            lastLoadedState: this.lastLoadedStateId,
            stateOptions: this.stateSelect.querySelectorAll('option').length,
            cityOptions: this.citySelect.querySelectorAll('option').length
        });

        this.setupEventListeners();
        this.initializeLocationSelects();
        this.initialized = true;
    }

    setupEventListeners() {
        this.countrySelect.addEventListener('change', () => {
            console.log('Country changed to:', this.countrySelect.value);
            this.isInitialLoad = false;
            this.loadStates(this.countrySelect.value);
        });

        this.stateSelect.addEventListener('change', () => {
            console.log('State changed to:', this.stateSelect.value);
            this.isInitialLoad = false;
            this.currentStateId = this.stateSelect.value;
            this.loadCities(this.stateSelect.value);
        });
    }

    async loadStates(countryId, preselectedStateId = null) {
        console.log('Loading states for country:', countryId, 'Preselected state:', preselectedStateId);

        if (!countryId) {
            this.resetDependentSelects();
            return;
        }

        const previousStateId = this.stateSelect.value;

        this.stateSelect.innerHTML = '<option value="">Cargando provincias...</option>';
        this.stateSelect.disabled = true;
        this.citySelect.innerHTML = '<option value="">Selecciona una ciudad</option>';
        this.citySelect.disabled = true;

        try {
            const response = await fetch(`/get-states/${countryId}`);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            this.stateSelect.innerHTML = '<option value="">Selecciona una provincia</option>';

            if (data.length > 0) {
                data.forEach(state => {
                    const option = document.createElement('option');
                    option.value = state.id;
                    option.textContent = state.name;
                    this.stateSelect.appendChild(option);
                });
                this.stateSelect.disabled = false;

                // Lógica de selección MEJORADA
                let stateToSelect = null;

                if (preselectedStateId) {
                    stateToSelect = preselectedStateId;
                    console.log('State preselected from parameter:', preselectedStateId);
                } else if (this.isInitialLoad && this.currentStateId) {
                    stateToSelect = this.currentStateId;
                    console.log('State preselected from current value:', this.currentStateId);
                } else if (previousStateId && this.stateSelect.querySelector(`option[value="${previousStateId}"]`)) {
                    stateToSelect = previousStateId;
                    console.log('State kept from previous:', previousStateId);
                }

                if (stateToSelect && this.stateSelect.querySelector(`option[value="${stateToSelect}"]`)) {
                    this.stateSelect.value = stateToSelect;
                    console.log('State selected:', stateToSelect);
                    await this.loadCities(stateToSelect, this.currentCityId);
                } else {
                    console.log('No state selected, clearing cities');
                    this.citySelect.innerHTML = '<option value="">Selecciona una ciudad</option>';
                    this.citySelect.disabled = true;
                }
            } else {
                this.stateSelect.innerHTML = '<option value="">No hay provincias disponibles</option>';
            }
        } catch (error) {
            console.error('Error loading states:', error);
            this.stateSelect.innerHTML = '<option value="">Error al cargar provincias</option>';
        }
    }

    async loadCities(stateId, preselectedCityId = null) {
        console.log('Loading cities for state:', stateId, 'Preselected city:', preselectedCityId);

        if (!stateId) {
            this.citySelect.innerHTML = '<option value="">Selecciona una ciudad</option>';
            this.citySelect.disabled = true;
            return;
        }

        // Verificar si necesitamos recargar las ciudades
        const hasExistingCities = this.citySelect.querySelectorAll('option').length > 1;
        const needsReload = !hasExistingCities || stateId !== this.lastLoadedStateId;

        console.log('Cities load check:', {
            stateId,
            lastLoadedStateId: this.lastLoadedStateId,
            hasExistingCities,
            needsReload,
            preselectedCityId
        });

        if (needsReload) {
            this.citySelect.innerHTML = '<option value="">Cargando ciudades...</option>';
            this.citySelect.disabled = true;

            try {
                const response = await fetch(`/get-cities/${stateId}`);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                this.citySelect.innerHTML = '<option value="">Selecciona una ciudad</option>';

                if (data.length > 0) {
                    data.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.name;
                        this.citySelect.appendChild(option);
                    });
                    this.citySelect.disabled = false;

                    // Actualizar el último estado cargado
                    this.lastLoadedStateId = stateId;

                    // Lógica de selección de ciudad MEJORADA
                    let cityToSelect = null;

                    if (preselectedCityId) {
                        cityToSelect = preselectedCityId;
                        console.log('City preselected from parameter:', preselectedCityId);
                    } else if (this.isInitialLoad && this.currentCityId) {
                        cityToSelect = this.currentCityId;
                        console.log('City preselected from current value:', this.currentCityId);
                    }

                    if (cityToSelect && this.citySelect.querySelector(`option[value="${cityToSelect}"]`)) {
                        this.citySelect.value = cityToSelect;
                        console.log('City selected:', cityToSelect);
                    }
                } else {
                    this.citySelect.innerHTML = '<option value="">No hay ciudades disponibles</option>';
                }
            } catch (error) {
                console.error('Error loading cities:', error);
                this.citySelect.innerHTML = '<option value="">Error al cargar ciudades</option>';
            }
        } else {
            console.log('Cities already loaded for this state, just updating selection');
            // Solo actualizar la selección si es necesario
            let cityToSelect = null;

            if (preselectedCityId && this.citySelect.querySelector(`option[value="${preselectedCityId}"]`)) {
                cityToSelect = preselectedCityId;
            } else if (this.isInitialLoad && this.currentCityId && this.citySelect.querySelector(`option[value="${this.currentCityId}"]`)) {
                cityToSelect = this.currentCityId;
            }

            if (cityToSelect) {
                this.citySelect.value = cityToSelect;
                console.log('City selection updated:', cityToSelect);
            }
        }
    }

    resetDependentSelects() {
        this.stateSelect.innerHTML = '<option value="">Selecciona una provincia</option>';
        this.citySelect.innerHTML = '<option value="">Selecciona una ciudad</option>';
        this.stateSelect.disabled = true;
        this.citySelect.disabled = true;
        this.lastLoadedStateId = null;
    }

    initializeLocationSelects() {
        const initialCountryId = this.countrySelect.value;

        // Obtener datos desde data attributes
        const oldStateId = this.stateSelect.dataset.oldState || null;
        const oldCityId = this.citySelect.dataset.oldCity || null;
        const argentinaId = this.countrySelect.dataset.argentinaId || null;

        console.log('Context values for initialization:', {
            initialCountryId,
            oldStateId,
            oldCityId,
            argentinaId,
            currentStateId: this.currentStateId,
            currentCityId: this.currentCityId,
            stateHasOptions: this.stateSelect.querySelectorAll('option').length > 1
        });

        // SIEMPRE cargar estados si hay un país seleccionado, incluso si ya hay opciones
        if (initialCountryId) {
            console.log('Country is selected, loading states...');

            let stateToPreselect = null;

            if (oldStateId) {
                stateToPreselect = oldStateId;
                console.log('Using old state from validation:', oldStateId);
            } else if (this.currentStateId) {
                stateToPreselect = this.currentStateId;
                console.log('Using current state from user:', this.currentStateId);
            }

            this.loadStates(initialCountryId, stateToPreselect);

        } else if (argentinaId) {
            console.log('Setting default Argentina and loading states');
            this.countrySelect.value = argentinaId;
            this.loadStates(argentinaId);

        } else if (oldStateId) {
            console.log('Restoring from validation errors');
            const oldCountryId = this.countrySelect.dataset.oldCountry;
            if (oldCountryId) {
                this.countrySelect.value = oldCountryId;
                this.loadStates(oldCountryId, oldStateId);
            }
        }

        setTimeout(() => {
            this.isInitialLoad = false;
            console.log('Initial load completed');
        }, 1000);
    }
}

if (window.location.pathname.includes('users/edit') || window.location.pathname.includes('profile/edit') || window.location.pathname.includes('register')) {
    // Inicialización automática cuando el DOM está listo
    document.addEventListener('DOMContentLoaded', function () {
        const locationSelector = new LocationSelector();
        locationSelector.init();
    });
}