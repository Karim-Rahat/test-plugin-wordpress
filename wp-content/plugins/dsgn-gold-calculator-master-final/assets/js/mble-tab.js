document.addEventListener("DOMContentLoaded", () => {


async function loadGoldMarqueeAllCT() {
  try {
    const fdata = new FormData();
    fdata.append("action", "get_metal_prices");
    fdata.append("nonce", calculator_data.nonce);
    
    const res = await fetch(calculator_data.ajax_url, {
      method: "POST",
      body: fdata
    });

    const result = await res.json();
    const gold = result.data.metal_prices.XAU;
    const carats = [9, 14, 16, 18, 20, 21, 22, 24];

    const singleMarquee = carats.map(ct => {
      const priceKey = `price_${ct}k`;
      return `<span class="marquee-item">${ct}ct £${gold[priceKey].toFixed(2)}</span>`;
    }).join("");


    document.getElementById("goldMarqueeText").innerHTML = singleMarquee + singleMarquee + singleMarquee;

  } catch (err) {
    console.error("Error loading gold marquee:", err);
    document.getElementById("goldMarqueeText").textContent = "Gold prices not available";
  }
}

loadGoldMarqueeAllCT();
});