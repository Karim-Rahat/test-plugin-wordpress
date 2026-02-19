document.addEventListener("input", function (e) {
  if (!e.target.classList.contains("dsgn-gold-number-input")) return;

  const input = e.target;

  if (input.value < 0) input.value = 0;


  const weight = parseFloat(input.value) || 0;
  const pricePerGram = parseFloat(input.dataset.price) || 0;


  const value = format2Decimals(weight * pricePerGram);


  const row = input.closest("tr") || input.closest("div.dsgn-gold-t-field");
  if (!row) return;


  const valueEl = row.querySelector(".dsgn-gold-value");
  if (valueEl) valueEl.textContent = `£${value}`;

  // update totals
  updateTotals();
});

function updateTotals() {
  const applyBtn = document.querySelector(".dsgn-gold-apply");
  const visibleTable = document.querySelector(
    ".dsgn-gold-carat-table-price table:not([style*='display: none'])"
  );
  if (!visibleTable) return;

  const metal = visibleTable.dataset.metal;

  // --- 1. Calculate total for this visible table (metal) ---
  let total = 0;
  visibleTable.querySelectorAll(".dsgn-gold-value").forEach(cell => {
    total += parseFloat(cell.textContent.replace("£", "")) || 0;
  });
  total = format2Decimals(total);

  // --- 2. Disable apply button if total <= 0 ---
  if (applyBtn) applyBtn.disabled = total <= 0;

  // --- 3. Update this metal's total in the sidebar ---
  const metalTotalEl = document.querySelector(`.dsgn-gold-metal[data-metal="${metal}"] .dsgn-gold-total-price`);
  if (metalTotalEl) metalTotalEl.textContent = `£${total}`;

  // --- 4. Read all metal totals from the DOM safely ---
  const getMetalTotal = metalName => {
    const el = document.querySelector(`.dsgn-gold-metal[data-metal="${metalName}"] .dsgn-gold-total-price`);
    return el ? parseFloat(el.textContent.replace("£", "")) || 0 : 0;
  };

  const gold = getMetalTotal("gold");
  const platinum = getMetalTotal("platinum");
  const silver = getMetalTotal("silver");
  const palladium = getMetalTotal("palladium");

  // --- 5. Calculate combined estimated total ---
  const estimated = format2Decimals(gold + platinum + silver + palladium);

  // --- 6. Update estimated total in the DOM ---
  const estimatedEl = document.querySelector(".dsgn-gold-estimated-price");
  if (estimatedEl) {
    estimatedEl.textContent = `£${estimated}`;
  } else {
    console.warn("Missing .dsgn-gold-estimated-price element");
  }
}

// truncate helper
function format2Decimals(num) {
  return Math.floor(num * 100) / 100;
}