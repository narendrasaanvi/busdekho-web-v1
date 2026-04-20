/* ===============================
   GLOBAL VARIABLES
=============================== */
let fromCityId = null;
let toCityId = null;
let currentBuses = [];
let debounceTimer;

/* ===============================
   DOM ELEMENTS
=============================== */
const fromInput = document.getElementById('fromInput');
const toInput = document.getElementById('toInput');
const fromDropdown = document.getElementById('fromDropdown');
const toDropdown = document.getElementById('toDropdown');
const searchBtn = document.getElementById('searchBtn');
const swapBtn = document.getElementById('swapBtn');
const resultsSection = document.getElementById('resultsSection');
const busList = document.getElementById('busList');
const busCount = document.getElementById('busCount');
const busModal = document.getElementById('busModal');
const modalContent = document.getElementById('modalContent');

/* ===============================
   DEBOUNCE FUNCTION
=============================== */
function debounceFetch(input, dropdown, setter) {
    clearTimeout(debounceTimer);

    debounceTimer = setTimeout(() => {
        fetchCities(input.value, dropdown, setter);
    }, 400);
}

/* ===============================
   CITY SEARCH
=============================== */
async function fetchCities(keyword, dropdown, setCity) {

    if (!keyword) {
        dropdown.innerHTML = '';
        return;
    }

    try {
        let res = await fetch(`https://busdekho.in/busapi/city_search.php?keyword=${keyword}&limit=5`, {
            method: 'POST'
        });

        let data = await res.json();
        dropdown.innerHTML = '';

        if (data.status && data.data.length) {
            data.data.forEach(city => {

                let div = document.createElement('div');
                div.className = "p-3 hover:bg-gray-100 cursor-pointer text-sm";
                div.innerText = city.city + " " + city.state;

                div.onclick = () => {
                    setCity(city);
                    dropdown.innerHTML = '';
                };

                dropdown.appendChild(div);
            });
        }

    } catch (error) {
        console.error("City fetch error:", error);
    }
}

/* ===============================
   INPUT EVENTS
=============================== */
fromInput.addEventListener('input', function () {
    debounceFetch(this, fromDropdown, (city) => {
        fromCityId = city.id;
        this.value = city.city;
    });
});

toInput.addEventListener('input', function () {
    debounceFetch(this, toDropdown, (city) => {
        toCityId = city.id;
        this.value = city.city;
    });
});

/* ===============================
   SEARCH BUSES
=============================== */
searchBtn.addEventListener('click', async () => {

    if (!fromCityId || !toCityId) {
        alert("Please select both cities");
        return;
    }

    if (fromCityId === toCityId) {
        alert("From and To city cannot be same");
        return;
    }

    resultsSection.classList.remove('hidden');

    /* LOADER */
    busList.innerHTML = `
    <div class="space-y-4 animate-pulse">
        ${[1,2,3].map(() => `
            <div class="bg-white p-4 rounded-xl shadow">
                <div class="flex justify-between mb-3">
                    <div class="h-4 bg-gray-300 rounded w-1/3"></div>
                    <div class="h-4 bg-gray-300 rounded w-1/4"></div>
                </div>
                <div class="flex justify-between items-center">
                    <div class="h-6 bg-gray-300 rounded w-16"></div>
                    <div class="h-4 bg-gray-300 rounded w-20"></div>
                    <div class="h-6 bg-gray-300 rounded w-16"></div>
                </div>
                <div class="flex justify-between mt-3">
                    <div class="h-4 bg-gray-300 rounded w-1/2"></div>
                    <div class="h-8 bg-gray-300 rounded w-20"></div>
                </div>
            </div>
        `).join('')}
    </div>
    `;

    try {
        let formData = new FormData();
        formData.append('city_from', fromCityId);
        formData.append('city_to', toCityId);
        formData.append('limit', 10);

        let res = await fetch('https://busdekho.in/busapi/search.php', {
            method: 'POST',
            body: formData
        });

        let data = await res.json();

        if (data.status === true || data.status === "true") {
            renderBuses(data.data);
            busCount.innerText = data.count + " buses found";
        } else {
            busList.innerHTML = "<p class='text-center text-gray-500'>No buses found</p>";
        }

    } catch (error) {
        console.error("Search error:", error);
        busList.innerHTML = "<p class='text-center text-red-500'>Something went wrong</p>";
    }
});

/* ===============================
   RENDER BUSES
=============================== */
function renderBuses(buses) {

    currentBuses = buses;
    busList.innerHTML = '';

    buses.forEach((bus, index) => {

        let card = document.createElement('div');
        card.className = 'bg-white rounded-xl shadow p-4 hover:shadow-lg transition';

        card.innerHTML = `
        <div class="flex justify-between text-sm font-semibold text-gray-700 mb-2">
            <span>${bus.vendor}</span>
            <span class="text-red-500">${bus.type}</span>
        </div>

        <div class="flex items-center justify-between text-center">

            <div>
                <h3 class="text-lg font-bold">${bus.starttime}</h3>
                <p class="text-xs text-gray-500">${bus.city_from}</p>
            </div>

            <div class="text-xs text-gray-500">
                ⏱ ${bus.yatraTime}
            </div>

            <div>
                <h3 class="text-lg font-bold">${bus.endtime}</h3>
                <p class="text-xs text-gray-500">${bus.city_to}</p>
            </div>

        </div>

        <div class="flex justify-between items-center mt-3">
            <span class="text-xs text-gray-500">${bus.route}</span>

            <button onclick="openModal('${bus.bus_code}')"
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">
                View
            </button>
        </div>
        `;

        busList.appendChild(card);
    });
}

/* ===============================
   MODAL
=============================== */
async function openModal(busCode) {

    // Show loader in modal
    modalContent.innerHTML = `
        <div class="text-center py-10 animate-pulse">
            <p class="text-gray-500">Loading bus details...</p>
        </div>
    `;

    busModal.classList.remove('hidden');
    busModal.classList.add('flex');

    try {

        let res = await fetch(`https://busdekho.in/busapi/get-bus.php?bus_code=${busCode}`);
        let data = await res.json();

        if (!data.status) {
            modalContent.innerHTML = `<p class="text-red-500">No details found</p>`;
            return;
        }

        let bus = data.data;

        /* ===============================
           BUILD STATIONS HTML
        =============================== */
        let stationsHTML = '';

        bus.stations.forEach(st => {
            stationsHTML += `
                <div class="flex justify-between text-sm border-b py-1">
                    <span>${st.station_name}</span>
                    <span>${st.arrival} - ${st.departure}</span>
                    <span>${st.km} KM</span>
                </div>
            `;
        });

        /* ===============================
           FINAL MODAL CONTENT
        =============================== */
        modalContent.innerHTML = `
            <h2 class="text-lg font-bold mb-2">${bus.vendor} (${bus.bus_line})</h2>

            <div class="text-sm text-gray-600 mb-2">
                <strong>Bus Code:</strong> ${bus.bus_code}
            </div>

            <div class="flex justify-between mb-3">
                <div>
                    <strong>${bus.from.name}</strong>
                </div>
                <div>➡️</div>
                <div>
                    <strong>${bus.to.name}</strong>
                </div>
            </div>

            <div class="mb-3">
                <strong>Route:</strong> ${bus.route}
            </div>

            <div class="mb-3">
                <strong>Bus Type:</strong> ${bus.bus_types.join(', ')}
            </div>

            <div class="mb-3">
                <strong>Depot :</strong> ${bus.seat}
            </div>

            <hr class="my-2">

            <h3 class="font-semibold mb-2">🛑 Stations</h3>

            <div class="max-h-60 overflow-y-auto">
                ${stationsHTML}
            </div>
        `;

    } catch (error) {
        console.error("Bus detail error:", error);
        modalContent.innerHTML = `<p class="text-red-500">Something went wrong</p>`;
    }
}

function closeModal() {
    busModal.classList.add('hidden');
    busModal.classList.remove('flex');
}

busModal.addEventListener('click', (e) => {
    if (e.target === busModal) {
        closeModal();
    }
});

/* ===============================
   SWAP
=============================== */
swapBtn.addEventListener('click', () => {

    let fromVal = fromInput.value;
    let toVal = toInput.value;

    fromInput.value = toVal;
    toInput.value = fromVal;

    let temp = fromCityId;
    fromCityId = toCityId;
    toCityId = temp;
});

/* ===============================
   POPULAR ROUTES
=============================== */
async function loadPopularRoutes() {

    const container = document.getElementById('popularRoutes');
    if (!container) return;

    try {
        let res = await fetch('https://busdekho.in/busapi/city_search.php?limit=10');

        let data = await res.json();

        console.log("Popular Routes:", data);

        if (data.status && data.data.length) {

            let cities = data.data.slice(0, 6);

            container.innerHTML = '';

            for (let i = 0; i < cities.length - 1; i++) {

                let from = cities[i];
                let to = cities[i + 1];

                let div = document.createElement('div');
                div.className = "bg-white p-4 rounded-lg shadow hover:shadow-xl hover:bg-[#f3ebff] cursor-pointer transition";

                div.innerHTML = `🚌 <strong>${from.city}</strong> → <strong>${to.city}</strong>`;

                container.appendChild(div);
            }

        } else {
            container.innerHTML = "<p>No routes found</p>";
        }

    } catch (error) {
        console.error("Popular routes error:", error);
    }
}

/* ===============================
   INIT
=============================== */
document.addEventListener("DOMContentLoaded", () => {
    loadPopularRoutes();
});

/* ===============================
   INIT (PAGE LOAD)
=============================== */
window.onload = function () {
    loadPopularRoutes();
};