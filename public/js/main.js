document.addEventListener('DOMContentLoaded', function () {
    fetchLgas();
})

function fetchLgas() {
    loading(true);
    fetch('api/getLgas')
        .then(response => response.json())
        .then(data => {
            const lgaSelect = document.getElementById('lgaSelect');
            if (!lgaSelect) return;
            lgaSelect.innerHTML = '<option selected disabled>Select LGA</option>'; // Clear existing options
            data.forEach(lga => {
                const option = document.createElement('option');
                option.value = lga.lga_id;
                option.textContent = lga.lga_name;
                lgaSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching LGAs:', error))
        .finally(() => { loading(false); });
}
function fetchWards() {
    loading(true);
    const lgaId = document.getElementById('lgaSelect').value;
    if (!lgaId) return loading(false) && modal({ message: "All fields are required", state: 0 });

    fetch(`api/getWards?lga_id=${lgaId}`)
        .then(response => response.json())
        .then(data => {
            const wardSelect = document.getElementById('wardSelect');
            wardSelect.innerHTML = '<option value="">Select Ward</option>'; // Clear existing options
            data.forEach(ward => {
                const option = document.createElement('option');
                option.value = ward.ward_id;
                option.textContent = ward.ward_name;
                wardSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching Wards:', error))
        .finally(() => { loading(false); });
}
function fetchPollingUnits() {
    loading(true);
    const wardId = document.getElementById('wardSelect').value;
    if (!wardId) return loading(false) && modal({ message: "All fields are required", state: 0 });

    fetch(`api/getPollingUnits?ward_id=${wardId}`)
        .then(response => response.json())
        .then(data => {
            const pollingUnitSelect = document.getElementById('pollingUnitSelect');
            pollingUnitSelect.innerHTML = '<option value="">Select Polling Unit</option>'; // Clear existing options
            data.forEach(pollingUnit => {
                const option = document.createElement('option');
                option.value = pollingUnit.polling_unit_id;
                option.textContent = pollingUnit.polling_unit_name;
                pollingUnitSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching Polling Units:', error))
        .finally(() => { loading(false); });
}
function fetchParty() {
    loading(true);

    fetch(`api/getParty`)
        .then(response => response.json())
        .then(data => {
            const partySelect = document.getElementById('partySelect');
            partySelect.innerHTML = '<option value="">Select Party</option>'; // Clear existing options
            data.forEach(party => {
                const option = document.createElement('option');
                option.value = party.partyid;
                option.textContent = party.partyname;
                partySelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching Party:', error))
        .finally(() => { loading(false); });
}
function fetchVote() {
    loading(true);
    const lga = document.getElementById("lgaSelect").selectedOptions[0].value,
        ward = document.getElementById("wardSelect").selectedOptions[0].value,
        polling_unit_name = document.getElementById("pollingUnitSelect").selectedOptions[0].textContent,
        party = document.getElementById("partySelect").selectedOptions[0].value;
    fetch(`api/getVote?lga=${lga}&ward=${ward}&polling_unit=${polling_unit_name}&party=${party}`)
        // .then(async response => { console.log(await response.text()) })
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            const voteSelect = document.getElementById('vote');
            voteSelect.value = data.party_score;
        })
        .catch(error => console.error('Error fetching Party:', error))
        .finally(() => { loading(false); });
}
document.querySelector('#lgaSelect:not(.total)')
    ?.addEventListener('change', function () {
        const lgaId = this.value;
        if (lgaId) {
            fetchWards(lgaId);
        } else {
            document.getElementById('wardSelect').innerHTML = '<option value="">Select Ward</option>';
            document.getElementById('pollingUnitSelect').innerHTML = '<option value="">Select Polling Unit</option>';
            document.getElementById('resultsContainer').innerHTML = '';
        }
    });

function fetchResults(e) {
    e.preventDefault();
    loading(true);
    const pollingUnitId = document.getElementById('pollingUnitSelect').value;
    if (!pollingUnitId) return loading(false) && modal({ message: "All fields are required", state: 0 });

    fetch(`api/getResults?polling_unit_id=${pollingUnitId}`)
        .then(response => response.json())
        .then(data => {
            const resultsContainer = document.getElementById('resultsContainer');
            resultsContainer.innerHTML = '<div class="content"></div>'; // Clear existing results

            if (data.length === 0) {
                resultsContainer.innerHTML += '<p>No results available.</p>';
                return;
            }
            resultsContainer.innerHTML += `
            <h1 style="margin-top:1rem;">${document.querySelector("#wardSelect").selectedOptions[0].textContent}</h1>
            <p>${document.querySelector("#pollingUnitSelect").selectedOptions[0].textContent}</p>
            `;

            resultsContainer.appendChild(loadCards({ data, type: 'div' }));
        })
        .catch(error => console.error('Error fetching Results:', error))
        .finally(() => {
            loading(false);
        });
}

let fetchTotal = e => {
    e.preventDefault();
    const lgaId = document.getElementById('lgaSelect').value;
    if (!lgaId) return loading(false) && modal({ message: "All fields are required", state: 0 });
    fetch(`api/getTotal?lga_id=${lgaId}`)
        // .then(async response => {console.log(await response.text())})
        .then(response => response.json())
        .then(data => {
            const resultsContainer = document.getElementById('resultsContainer');
            resultsContainer.innerHTML = '<div class="content"></div>'; // Clear existing results

            if (data.length === 0) {
                resultsContainer.innerHTML += '<p>No results available.</p>';
                return;
            }
            resultsContainer.appendChild(loadCards({ data, type: 'div' }));
        })
        .catch(error => console.error('Error fetching LGAs:', error))
        .finally(() => { loading(false); });
}

let collate = e => {
    e.preventDefault();
    loading(true);
    const lgaSelect = document.getElementById('lgaSelect').value,
        wardSelect = document.getElementById('wardSelect').value,
        pollingUnitId = document.getElementById('pollingUnitSelect').selectedOptions[0].textContent,
        partySelect = document.getElementById('partySelect').value,
        voteSelect = document.getElementById('vote').value;
    if (!lgaSelect || !wardSelect || !pollingUnitId || !partySelect || !voteSelect) return loading(false) && modal({ message: "All fields are required", state: 0 });

    fetch(`api/collate?lga=${lgaSelect}&ward=${wardSelect}&poll=${pollingUnitId}&party=${partySelect}&vote=${voteSelect}`)
        // .then(async response => { console.log(await response.text()) })
        .then(response => response.json())
        .then(({ message, state }) => {
            modal({ message, state });
        })
        .catch(error => modal({ state: 0, message: `Error occurred: ${error}` }))
        .finally(() => {
            loading(false);
        });
}

let loadCards = ({ data, type, link }) => {
    type = type || "div";
    link = link || "#";
    const cards = document.createElement("div");
    cards.classList.add("cards");

    data.forEach(({ party_abbreviation, party_score }) => {
        const card = document.createElement(type);
        card.classList.add("card")
        if (type == "a") {
            card.setAttribute("href", link)
        }
        card.innerHTML = `
            <div class="label">${party_abbreviation}</div>
            <div class="text">
                <p>Votes</p>
                <h2>${party_score}</h2>
            </div>
        `;
        cards.appendChild(card);
    });

    return cards;
}
let loading = (n = false) => {
    if (n) {
        document.querySelector(".loading").classList.add("show")
        return true;
    }
    document.querySelector(".loading").classList.remove("show")
    return true;
}
let modal = ({ message, state }) => {
    return Toastify({
        text: message,
        duration: 3000,
        close: true,
        gravity: "top",
        position: "center",
        stopOnFocus: true, // Prevents dismissing of toast on hover
        style: {
            background: state == 1 ? "var(--primary)" : "#ff5555",
        }
    }).showToast();
}