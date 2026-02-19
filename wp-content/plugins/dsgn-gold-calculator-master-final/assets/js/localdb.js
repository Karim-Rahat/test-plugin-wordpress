document.querySelector(".dsgn-gold-apply").addEventListener("click", () => {


  const payload = {
    items: [],
    totals: {
      gold: 0,
      silver: 0,
      platinum: 0,
      palladium: 0,
      estimated: 0
    }
  };

  // ===== PC TABLES =====
  document.querySelectorAll(".dsgn-gold-carat-table-price .dsgn-gold-table-pc-div table").forEach(table => {
    const metal = table.dataset.metal;

    table.querySelectorAll("tbody tr").forEach(row => {
      const input = row.querySelector(".dsgn-gold-number-input");
      if (!input) return;

      const weight = parseFloat(input.value) || 0;
      if (weight <= 0) return;

      const value = parseFloat(row.querySelector(".dsgn-gold-value").textContent.replace("£","")) || 0;

      // Push item
      payload.items.push({
        metal,
        carat: row.children[0].innerText.trim(),
        weight,
        value
      });

      // Add to metal total
      payload.totals[metal] += value;
    });
  });

  // ===== MOBILE TABLES =====
  document.querySelectorAll(".dsgn-gold-carat-table-price .dsgn-gold-table-mble-div .dsgn-gold-table-mble").forEach(div => {
    const metal = div.dataset.metal;

    div.querySelectorAll(".dsgn-gold-t-field").forEach(row => {
      const input = row.querySelector(".dsgn-gold-number-input");
      if (!input) return;

      const weight = parseFloat(input.value) || 0;
      if (weight <= 0) return;

      const caratLabel = div.querySelector("h5")?.innerText.trim() || "N/A";
      const value = parseFloat(row.querySelector(".dsgn-gold-value")?.innerText.replace("£","")) || 0;

      // Prevent duplicate: check if same metal + carat + weight exists
      const exists = payload.items.some(i => i.metal === metal && i.carat === caratLabel && i.weight === weight);
      if (!exists) {
        payload.items.push({ metal, carat: caratLabel, weight, value });
        payload.totals[metal] += value;
      }
    });
  });

  // ===== Calculate Estimated Total =====
  payload.totals.estimated = Object.keys(payload.totals)
    .filter(k => k !== "estimated")
    .reduce((sum, key) => sum + payload.totals[key], 0);

  // ===== Save to Local Storage =====
  localStorage.setItem("scrapSellData", JSON.stringify(payload));

  // ===== Optional: Redirect =====
  window.location.href = contact_form_data.contact_form_url;
});

console.log(contact_form_data.contact_form_url);