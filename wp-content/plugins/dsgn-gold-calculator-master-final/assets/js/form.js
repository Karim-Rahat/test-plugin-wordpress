document.addEventListener("DOMContentLoaded", () => {

  // ==================== DOM ELEMENTS Of IIMAGE PREVIEW====================
const imageInput = document.getElementById("imageUpload");
const uploadContainer = document.getElementById("form-upload-image-div");

const previewContainer = document.getElementById("imagePreviewContainer");
const previewImage = document.getElementById("imagePreview");

let file='';

uploadContainer?.addEventListener("click", (e) => {
  if (e.target.closest("label")) {
    e.preventDefault();
  }

  imageInput.value = "";
  imageInput.click();
});

imageInput?.addEventListener("change", () => {
  file = imageInput.files[0];
  let imageURL = "";
  console.log(file);
  if (!file) return;

  if (imageURL) URL.revokeObjectURL(imageURL);

  imageURL = URL.createObjectURL(file);

  previewImage.src = imageURL;

  previewContainer.style.display = "flex";
});



  const STORAGE_KEY = "scrapSellData";

  if (window.__formLoaded) return;
  window.__formLoaded = true;

  // ==================== DOM ELEMENTS ====================
  const mainContainer = document.querySelector(".form-metal-main-container");
  const estimatedEl = document.querySelector(".form-estimated-value");
  const anchorEl = document.querySelector(".add-minus-estimated-div");
  const addMoreBtn = document.querySelector(".add-more-div");
  const minusBtn = document.querySelector(".minus-div");
  const resetBtn = document.querySelector(".reset-div");
  const submitBtn = document.querySelector(".get-btn.submit-btn");

  if (!mainContainer || !estimatedEl || !anchorEl || !addMoreBtn || !minusBtn || !resetBtn || !submitBtn) return;

  // ==================== HELPERS ====================
  const safe = v => (isNaN(v) || v < 0 ? 0 : v);

  let allData = {}; 

  function save(data) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
  }

  function updateEstimated(data) {
    const total = data.items.reduce((sum, i) => sum + safe(i.value), 0);
    data.totals.estimated = total;
    estimatedEl.textContent = `£${total.toFixed(2)}`;
    save(data);
  }

  // ==================== LOAD LOCAL STORAGE ====================
  let raw = localStorage.getItem(STORAGE_KEY);
  let data = raw ? JSON.parse(raw) : { items: [], totals: { estimated: 0 }, contact: {} };

  data.items.forEach(item => {
    item.persisted = true;
    if (!item.unitPrice) item.unitPrice = item.weight > 0 ? item.value / item.weight : 0;
  });

  // ==================== RENDER ROW ====================
  function renderRow(item, index) {
    const row = document.createElement("div");
    row.className = "form-metal-container";
    row.dataset.index = index;

    let metalOptions = "";
    if (!item.persisted) {
      Object.keys(allData).forEach(metal => {
        allData[metal].forEach(opt => {
          const selected = (opt.metal === item.metal && opt.carat === item.carat) ? "selected" : "";
          metalOptions += `<option value="${metal}|${opt.carat}" ${selected}>${opt.carat} ${metal}</option>`;
        });
      });
    } else {
      metalOptions = `<option selected>${item.carat} ${item.metal}</option>`;
    }

    row.innerHTML = `
      <div class="form-metal-heading">
        <p class="font16 scrap-metal-weight1">Weight (Grams)</p>
        <p class="font16 scrap-metal-weight2">Scrap Metal</p>
        <p class="font16 scrap-metal-weight3">Total Value</p>
      </div>

      <div class="form-metal-input-div">
        <input
          type="number"
          min="0"
          step="0.01"
          class="scrap-metal-weight1 form-input-number font16 filled fill_inited"
          value="${safe(item.weight)}"
        />

        <div class="scrap-metal-weight2">
          <div class="select_container">
            <select class="form-input-select font16 filled fill_inited">
              ${metalOptions}
            </select>
          </div>
        </div>

        <p class="scrap-metal-weight3 font16 form-total-value">
          £${safe(item.value).toFixed(2)}
        </p>
      </div>
    `;

    mainContainer.insertBefore(row, anchorEl);
  }

  mainContainer.querySelectorAll(".form-metal-container").forEach(el => el.remove());
  data.items.forEach((item, index) => renderRow(item, index));
  updateEstimated(data);

  // ==================== GATHER OVERALL DATA ====================
  function gatherOverallData() {
    // Scrap items
    const rows = document.querySelectorAll(".form-metal-container");
    const items = Array.from(rows).map((row) => {
      const weight = parseFloat(row.querySelector(".form-input-number")?.value) || 0;
      const selectVal = row.querySelector(".form-input-select")?.value || "";
      const [metal, carat] = selectVal.split("|");
      const totalValue = parseFloat(row.querySelector(".form-total-value")?.textContent.replace("£", "")) || 0;
      return { weight, metal: metal || "", carat: carat || "", value: totalValue };
    });

    // Contact info
    const contact = {
      firstName: document.querySelector(".first-name")?.value || "",
      lastName: document.querySelector(".last-name")?.value || "",
      phone: document.querySelector(".main-phone")?.value || "",
      email: document.querySelector(".email")?.value || "",
      houseNumber: document.querySelector(".house-number")?.value || "",
      town: document.querySelector(".town")?.value || "",
      apartment: document.querySelector(".Apartment")?.value || "",
      postcode: document.querySelector(".Postcode")?.value || "",
      description: document.querySelector(".form-desciption")?.value || "",
      image: file || "" 
    };

    const estimated = items.reduce((sum, i) => sum + i.value, 0);

    return { items, totals: { estimated }, contact };
  }

  // ==================== EVENT LISTENERS ====================

  mainContainer.addEventListener("input", e => {
    if (!e.target.classList.contains("form-input-number")) return;
    const input = e.target;
    const row = input.closest(".form-metal-container");
    const index = Number(row.dataset.index);
    const item = data.items[index];

    let weight = parseFloat(input.value);
    if (isNaN(weight) || weight < 0) weight = 0;
    input.value = weight;

    item.weight = weight;
    item.value = item.unitPrice * weight;
    row.querySelector(".form-total-value").textContent = `£${item.value.toFixed(2)}`;

    const overallData = gatherOverallData();
    save(overallData);
    console.log("Overall form data updated:", overallData);
    estimatedEl.textContent = `£${overallData.totals.estimated.toFixed(2)}`;
  });

  mainContainer.addEventListener("change", e => {
    if (!e.target.classList.contains("form-input-select")) return;
    const select = e.target;
    const row = select.closest(".form-metal-container");
    const index = Number(row.dataset.index);
    const item = data.items[index];

    const [metal, carat] = select.value.split("|");
    item.metal = metal;
    item.carat = carat;

    if (!item.persisted && allData[metal]) {
      const found = allData[metal].find(m => m.carat === carat);
      if (found) {
        item.unitPrice = found.price;
        item.value = item.weight * item.unitPrice;
        row.querySelector(".form-total-value").textContent = `£${item.value.toFixed(2)}`;
      }
    }

    const overallData = gatherOverallData();
    save(overallData);
    console.log("Overall form data updated:", overallData);
    estimatedEl.textContent = `£${overallData.totals.estimated.toFixed(2)}`;
  });


// ==================== SUBMIT ====================
submitBtn.addEventListener("click", (e) => {
  e.preventDefault();

  // Wrap your fetch logic in the spinner callback
  withSubmitSpinner(submitBtn, async () => {
    const overallData = gatherOverallData();
    console.log("Overall form data saved:", overallData);

    localStorage.setItem("scrapSellData", JSON.stringify(overallData));

    const formData = new FormData();
    formData.append("action", "gold_sell_form");
    formData.append("nonce", goldSell.nonce);
    formData.append("items", JSON.stringify(overallData.items));
    formData.append("totals", JSON.stringify(overallData.totals));
    formData.append("contact", JSON.stringify(overallData.contact));

    const successBox = document.querySelector(".success-message");
    const errorBox = document.querySelector(".error-message");
    const form = document.querySelector(".contact-main-form");

    try {
      const res = await fetch(goldSell.ajax_url, { method: "POST", body: formData });
      const response = await res.json();

      if (response.success) {
        console.log("✅ Success:", response);

        if (successBox) successBox.style.display = "block";
        if (form) form.reset();
        localStorage.removeItem("scrapSellData");

        setTimeout(() => {
          if (successBox) successBox.style.display = "none";
          // window.location.reload();
        }, 3000);
      } else {
        console.error("❌ Error:", response);
        if (errorBox) {
          errorBox.style.display = "block";
          errorBox.textContent = response.data?.message || "An error occurred. Please try again.";
          setTimeout(() => { errorBox.style.display = "none"; }, 5000);
        }
      }
    } catch (err) {
      console.error("❌ Fetch Error:", err);
      if (errorBox) {
        errorBox.style.display = "block";
        errorBox.textContent = "A network error occurred. Please try again.";
        setTimeout(() => { errorBox.style.display = "none"; }, 5000);
      }
    }
  });
});





  // ==================== OTHER BUTTONS (Add/Remove/Reset) ====================
  addMoreBtn.addEventListener("click", () => {
    const metals = Object.keys(allData);
    if (!metals.length) return;

    const firstMetal = metals[0];
    const firstCarat = allData[firstMetal][0].carat;
    const firstPrice = allData[firstMetal][0].price;

    const newItem = { metal: firstMetal, carat: firstCarat, weight: 0, value: 0, unitPrice: firstPrice, persisted: false };
    data.items.push(newItem);
    renderRow(newItem, data.items.length - 1);

    const overallData = gatherOverallData();
    save(overallData);
    console.log("Overall form data updated (Add More):", overallData);
    updateEstimated(overallData);
  });

  minusBtn.addEventListener("click", () => {
    if (!data.items.length) return;
    data.items.pop();
    mainContainer.querySelectorAll(".form-metal-container")[data.items.length]?.remove();

    const overallData = gatherOverallData();
    save(overallData);
    console.log("Overall form data updated (Remove):", overallData);
    updateEstimated(overallData);
  });

  resetBtn.addEventListener("click", () => {
    data.items = [];
    data.totals.estimated = 0;
    mainContainer.querySelectorAll(".form-metal-container").forEach(el => el.remove());

    const overallData = gatherOverallData();
    save(overallData);
    console.log("Overall form data updated (Reset):", overallData);
    updateEstimated(overallData);
  });

  // ==================== LOAD METAL PRICES ====================
  async function loadMetalData() {
    try {
      if (!calculator_data || !calculator_data.ajax_url || !calculator_data.nonce) return;
      const fdata = new FormData();
      fdata.append("action", "get_metal_prices");
      fdata.append("nonce", calculator_data.nonce);
      const res = await fetch(calculator_data.ajax_url, { method: "POST", body: fdata });
      const result = await res.json();
      if (result.status !== "success") return;

      allData = {};
      Object.entries(result.data.metal_prices).forEach(([key, val]) => {
        if (key === "XAU") {
          const blockedCarats = ["16k", "20k", "21k"];
          allData.gold = Object.entries(val)
            .filter(([k]) => k.startsWith("price_"))
            .map(([k, v]) => ({ carat: k.replace("price_", ""), price: v, metal: "gold" }))
            .filter(item => !blockedCarats.includes(item.carat));
        } else if (key === "XAG") {
          const silverPurities = { "925": 0.925, "999": 0.999 };
          allData.silver = Object.entries(silverPurities).map(([carat, ratio]) => ({
            carat, price: val.price * ratio ?? 0, metal: "silver"
          }));
        } else if (key === "XPT") {
          const platinumPurities = { "950": 0.95, "999": 0.999 };
          allData.platinum = Object.entries(platinumPurities).map(([carat, ratio]) => ({
            carat, price: val.price * ratio ?? 0, metal: "platinum"
          }));
        } else if (key === "XPD") {
          const palladiumPurities = { "950": 0.95, "999": 0.999 };
          allData.palladium = Object.entries(palladiumPurities).map(([carat, ratio]) => ({
            carat, price: val.price * ratio ?? 0, metal: "palladium"
          }));
        }
      });
      console.log("Transformed metal data:", allData);
    } catch (err) {
      console.error("Failed to load metal data:", err);
    }
  }

  loadMetalData();



  function withSubmitSpinner(button, callback) {
  if (!button) return;

  // Show spinner and disable button
  button.classList.add('loading');
  const textEl = button.querySelector('.btn-text');
  const spinnerEl = button.querySelector('.spinner');
  if (textEl) textEl.textContent = 'Submit...';
  if (spinnerEl) spinnerEl.style.display = 'inline-block';

  // Run the callback (can be async)
  const result = callback();

  // If callback returns a Promise, wait for it
  if (result && typeof result.then === 'function') {
    result.finally(() => resetButton(button, textEl, spinnerEl));
  } else {
    // Otherwise, reset immediately
    resetButton(button, textEl, spinnerEl);
  }
}

// Reset button after callback finishes
function resetButton(button, textEl, spinnerEl) {
  button.classList.remove('loading');
  if (textEl) textEl.textContent = 'Submit';
  if (spinnerEl) spinnerEl.style.display = 'none';
}

});
