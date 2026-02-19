document.addEventListener("DOMContentLoaded", () => {

  const tabs = document.querySelectorAll(".dsgn-gold-tab");
  const tabContainers = document.querySelectorAll(".dsgn-gold-tab-contain");
  const cardContainer = document.getElementById("tabCardContainer");
  const tableContainers = document.querySelectorAll(".dsgn-gold-carat-table-price table");
  const tabContainerDiv = document.querySelector(".dsgn-gold-tab-container"); // <- TARGET ELEMENT TO BLUR

  let allData = [];
  let userInputs = { gold: {}, silver: {}, platinum: {}, palladium: {} };


  // ================= LOAD METAL DATA =================
  async function loadMetalData() {
    try {
      const fdata = new FormData();
      fdata.append('action', 'get_metal_prices');
      fdata.append('nonce', calculator_data.nonce);

      const res = await fetch(calculator_data.ajax_url, { method: 'POST', body: fdata });
      const result = await res.json();
      console.log(result)

      allData = transformApiData(result);
      setActiveTab("gold");
    } catch (err) {
      console.error("Error loading metal data:", err);
      cardContainer.innerHTML = `<p style="color:#fff; text-align:center;">Failed to load data</p>`;
    } finally {
  const loader = document.querySelector(".page-loader");
  const container = document.querySelector(".dsgn-gold-tab-container");

  loader?.classList.add("is-hidden");
  container?.classList.add("is-clear");


  loader?.addEventListener(
    "transitionend",
    () => {
      loader.style.display = "none";
    },
    { once: true }
  );
}


  }

  // ================= TRANSFORM API DATA =================
  function transformApiData(apiData) {
    const metalsMap = { gold: "XAU", silver: "XAG", platinum: "XPT", palladium: "XPD" };
    const transformed = [];
    const nowDate = new Date().toLocaleDateString();
    const nowTime = new Date().toLocaleTimeString();

    Object.keys(metalsMap).forEach(metal => {
      const code = metalsMap[metal];
      const metalData = apiData.data.metal_prices[code];
      if (!metalData) return;

      if (metal === "gold") {
        const carats =["9k","14k","18k","22k","24k"];
        carats.forEach(carat => {
          const priceKey = `price_${carat}`;
          if (metalData[priceKey] !== undefined) {
            transformed.push({ metal, carat: carat.toUpperCase(), price: Number(metalData[priceKey]).toFixed(2), date: nowDate, time: nowTime });
          }
        });
      } else if (metal === "silver") {
        const basePrice = Number(metalData.price);
        const silverPurity = { "925 SLV": 0.925, "999 SLV": 0.999 };
        Object.keys(silverPurity).forEach(purity => {
          transformed.push({ metal, carat: purity, price: (basePrice * silverPurity[purity]).toFixed(2), date: nowDate, time: nowTime });
        });
      } else if (metal === "platinum") {
        const basePrice = Number(metalData.price);
        const platinumPurity = { "950 PLT": 0.95, "999 PLT": 0.999 };
        Object.keys(platinumPurity).forEach(purity => {
          transformed.push({ metal, carat: purity, price: (basePrice * platinumPurity[purity]).toFixed(2), date: nowDate, time: nowTime });
        });
      } else if (metal === "palladium") {
        const basePrice = Number(metalData.price);
        const palladiumPurity = { "950 PLD": 0.95, "999 PLD": 0.999 };
        Object.keys(palladiumPurity).forEach(purity => {
          transformed.push({ metal, carat: purity, price: (basePrice * palladiumPurity[purity]).toFixed(2), date: nowDate, time: nowTime });
        });
      }
    });

    return transformed;
  }

  // ================= TAB CLICK =================
  tabs.forEach(tab => tab.addEventListener("click", () => setActiveTab(tab.dataset.tab)));

  // ================= SET ACTIVE TAB =================
  function setActiveTab(metalType) {
    tabs.forEach(t => t.classList.remove("dsgn-gold-active"));

    tabContainers.forEach(tc => tc.classList.remove("dsgn-gold-active"));

    const activeTab = document.querySelector(`.dsgn-gold-tab[data-tab="${metalType}"]`);
    if (!activeTab) return;

    activeTab.classList.add("dsgn-gold-active");
    activeTab.parentElement.classList.add("dsgn-gold-active");

    renderCards(metalType);
    renderTable(metalType);
    renderMobileTable(metalType);

    tableContainers.forEach(table => {
      table.style.display = table.dataset.metal === metalType ? "table" : "none";
    });
  }

  // ================= RENDER CARDS =================
  function renderCards(metalType) {
    const filtered = allData.filter(i => i.metal === metalType);

    if (!filtered.length) {
      cardContainer.innerHTML = `<div style="text-align:center;padding:40px;color:#fff;">No card data available</div>`;
      return;
    }

    cardContainer.innerHTML = filtered.map(item => `
      <div class="dsgn-gold-tab-card">
        <h6 class="dsgn-gold-ct">${item.carat}</h6>
        <div class="dsgn-gold-tab-card-body">
          <p class="dsgn-gold-price">£${item.price}</p>
          <div class="dsgn-gold-i-div">
            <p class="dsgn-gold-date">${item.date}</p>
            <p class="dsgn-gold-date">${item.time}</p>
          </div>
        </div>
      </div>
    `).join("");
  }

  // ================= RENDER PC TABLE =================
  function renderTable(metalType) {
    const table = document.querySelector(`table[data-metal="${metalType}"]`);
    if (!table) return;

    const tbody = table.querySelector("tbody");
    if (!tbody) return;

    const filtered = allData.filter(i => i.metal === metalType);
    tbody.innerHTML = "";

    if (!filtered.length) {
      tbody.innerHTML = `<tr><td colspan="4" style="text-align:center;padding:40px;color:#fff;">No table data available</td></tr>`;
      return;
    }

    filtered.forEach(item => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${item.carat}</td>
        <td class="dsgn-gold-number-div">
          <input class="dsgn-gold-number-input" type="number" min="0" step="0.1"
            value="${userInputs[metalType][item.carat] || 0}"
            data-metal="${metalType}" data-carat="${item.carat}" data-price="${item.price}" />
        </td>
        <td>£${item.price}</td>
        <td class="dsgn-gold-value dsgn-gold-font16">£${((userInputs[metalType][item.carat] || 0) * item.price).toFixed(2)}</td>
      `;
      tbody.appendChild(row);
    });

    tbody.querySelectorAll(".dsgn-gold-number-input").forEach(input => input.addEventListener("input", handleInputChange));
  }

  // ================= RENDER MOBILE TABLE =================
  function renderMobileTable(metalType) {
    const mobileDivWrapper = document.querySelector(".dsgn-gold-table-mble-div");
    if (!mobileDivWrapper) return;
    mobileDivWrapper.innerHTML = "";

    const filtered = allData.filter(i => i.metal === metalType);
    if (!filtered.length) {
      mobileDivWrapper.innerHTML = `<p style="text-align:center;color:#fff;padding:20px;">No mobile table data</p>`;
      return;
    }

    filtered.forEach(item => {
      const mbDiv = document.createElement("div");
      mbDiv.className = "dsgn-gold-table-mble";
      mbDiv.dataset.metal = metalType;

      mbDiv.innerHTML = `
        <h5 class="dsgn-gold-gold dsgn-gold-font16">${item.carat}</h5>
        <div class="dsgn-gold-head-table-element">
          <div class="dsgn-gold-head-table">
            <p class="dsgn-gold-table-title dsgn-gold-table-title1">Weight (g)</p>
            <p class="dsgn-gold-table-title dsgn-gold-table-title2">Price (g)</p>
            <p class="dsgn-gold-table-title dsgn-gold-table-title3">Value</p>
          </div>
          <div class="dsgn-gold-t-field">
            <input class="dsgn-gold-number-input dsgn-gold-table-title1" type="number" min="0" step="0.1"
              value="${userInputs[metalType][item.carat] || 0}"
              data-metal="${metalType}" data-carat="${item.carat}" data-price="${item.price}" />
            <p class="dsgn-gold-p-i dsgn-gold-table-title2">£${item.price}</p>
            <p class="dsgn-gold-value dsgn-gold-table-title3">£${((userInputs[metalType][item.carat] || 0) * item.price).toFixed(2)}</p>
          </div>
        </div>
      `;

      const input = mbDiv.querySelector("input.dsgn-gold-number-input");
      if (input) input.addEventListener("input", handleInputChange);

      mobileDivWrapper.appendChild(mbDiv);
    });
  }

  // ================= HANDLE INPUT CHANGE =================
  function handleInputChange(e) {
    const input = e.target;
    const weight = parseFloat(input.value) || 0;
    const price = parseFloat(input.dataset.price);
    const metal = input.dataset.metal;
    const carat = input.dataset.carat;

    userInputs[metal][carat] = weight;

    // Update PC table
    const pcInput = document.querySelector(`table[data-metal="${metal}"] input[data-carat="${carat}"]`);
    if (pcInput && pcInput !== input) pcInput.value = weight;
    const pcValue = pcInput?.closest("tr")?.querySelector(".dsgn-gold-value");
    if (pcValue) pcValue.textContent = "£" + (weight * price).toFixed(2);

    // Update Mobile table
    const mbInput = document.querySelector(`.dsgn-gold-table-mble input[data-metal="${metal}"][data-carat="${carat}"]`);
    if (mbInput && mbInput !== input) mbInput.value = weight;
    const mbValue = mbInput?.closest(".dsgn-gold-t-field")?.querySelector(".dsgn-gold-value");
    if (mbValue) mbValue.textContent = "£" + (weight * price).toFixed(2);

    updateTotals();
  }

  // ================= UPDATE TOTALS =================
  function updateTotals() {
    ["gold","silver","platinum","palladium"].forEach(metal => {
      let total = 0;
      for (let carat in userInputs[metal]) {
        total += userInputs[metal][carat] * parseFloat(allData.find(i => i.metal === metal && i.carat === carat)?.price || 0);
      }

      const totalEl = document.querySelector(`.dsgn-gold-metal[data-metal="${metal}"] .dsgn-gold-total-price`);
      if (totalEl) totalEl.textContent = "£" + total.toFixed(2);
    });

    const estimated = ["gold","silver","platinum","palladium"].reduce((sum, metal) => {
      return sum + Object.keys(userInputs[metal]).reduce((s, carat) => {
        return s + userInputs[metal][carat] * parseFloat(allData.find(i => i.metal === metal && i.carat === carat)?.price || 0);
      }, 0);
    }, 0);

    const estimatedEl = document.querySelector(".dsgn-gold-estimated-price");
    if (estimatedEl) estimatedEl.textContent = "£" + estimated.toFixed(2);
  }

  // ================= INITIAL LOAD =================
  loadMetalData();

});