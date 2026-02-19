<section class="dsgn-gold-tab-bg dsgn-gold">

    <div class="dsgn-gold-title-div">
        <h1 class="dsgn-gold-tab-title"><?= $title ?></h1>
        <p class="dsgn-gold-tab-para"><?= $subtitle ?></p>
    </div>
    <div class="dsgn-gold-tab-container">
        <div class="dsgn-gold-tab-items">
            <div class="dsgn-gold-tab-contain dsgn-gold-active">
                <li class="dsgn-gold-tab dsgn-gold-active" data-tab="gold">Gold</li>
            </div>
            <div class="dsgn-gold-tab-contain">
                <li class="dsgn-gold-tab" data-tab="silver">Silver</li>
            </div>
            <div class="dsgn-gold-tab-contain">
                <li class="dsgn-gold-tab" data-tab="platinum">Platinum</li>
            </div>
            <div class="dsgn-gold-tab-contain">
                <li class="dsgn-gold-tab" data-tab="palladium">Palladium</li>
            </div>
        </div>
        <div class="dsgn-gold-tab-body">
            <div id="loaderOverlay" class="page-loader"></div>

            <div class="dsgn-gold-tab-card-container" id="tabCardContainer">
                
            </div>

            <div class="dsgn-gold-carat-table-price">
                <div class="dsgn-gold-table-link">
                    <!-- table for pc -->
                    <div class="dsgn-gold-table-pc-div">
                        <!-- gold table -->
                        <table data-metal="gold" class="dsgn-gold-table">
                            <thead>
                                <tr>
                                    <th class="dsgn-gold-t-head dsgn-gold-font16">Metal Carat</th>
                                    <th class="dsgn-gold-t-head dsgn-gold-weight dsgn-gold-font16">Weight (g)</th>
                                    <th class="dsgn-gold-t-head dsgn-gold-price-gram dsgn-gold-font16">Price (g)</th>
                                    <th class="dsgn-gold-t-head dsgn-gold-font16">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                        <!-- platinum table -->
                        <table data-metal="platinum" class="dsgn-gold-table">
                            <thead>
                                <tr>
                                    <th class="dsgn-gold-t-head dsgn-gold-font16">Metal Carat</th>
                                    <th class="dsgn-gold-t-head dsgn-gold-weight dsgn-gold-font16">Weight (g)</th>
                                    <th class="dsgn-gold-t-head dsgn-gold-price-gram dsgn-gold-font16">Price (g)</th>
                                    <th class="dsgn-gold-t-head dsgn-gold-font16">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                        <!-- silver table -->
                        <table data-metal="silver" class="dsgn-gold-table">
                            <thead>
                                <tr>
                                    <th class="dsgn-gold-t-head dsgn-gold-font16">Metal Carat</th>
                                    <th class="dsgn-gold-t-head dsgn-gold-weight dsgn-gold-font16">Weight (g)</th>
                                    <th class="dsgn-gold-t-head dsgn-gold-price-gram dsgn-gold-font16">Price (g)</th>
                                    <th class="dsgn-gold-t-head dsgn-gold-font16">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                      
                            </tbody>
                        </table>
                        <!-- palladium table -->
                        <table data-metal="palladium" class="dsgn-gold-table">
                            <thead>
                                <tr>
                                    <th class="dsgn-gold-t-head dsgn-gold-font16">Metal Carat</th>
                                    <th class="dsgn-gold-t-head dsgn-gold-weight dsgn-gold-font16">Weight (g)</th>
                                    <th class="dsgn-gold-t-head dsgn-gold-price-gram dsgn-gold-font16">Price (g)</th>
                                    <th class="dsgn-gold-t-head dsgn-gold-font16">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                      
                            </tbody>
                        </table>
                    </div>
                    <!-- table for mobile only -->
                    <div class="dsgn-gold-table-mble-div">
                        <!-- gold table -->
                        <div class="dsgn-gold-table-mble" data-metal="gold">
                            <h5 class="dsgn-gold-gold dsgn-gold-font16">9ct Gold</h5>
                            <div class="dsgn-gold-head-table-element">
                                <div class="dsgn-gold-head-table">
                                    <p class="dsgn-gold-table-title dsgn-gold-table-title1 ab">Weight (g)</p>
                                    <p class="dsgn-gold-table-title dsgn-gold-table-title2">price (g)</p>
                                    <p class="dsgn-gold-table-title dsgn-gold-table-title3">Value</p>
                                </div>
                                <div class="dsgn-gold-t-field">
                                    <input class="dsgn-gold-number-input dsgn-gold-table-title1" type="number" value="0"
                                        min="0" step="0.1" data-carat="${item.carat}" />
                                    <p class="dsgn-gold-p-i dsgn-gold-table-title2">£34.36</p>
                                    <p class="dsgn-gold-value dsgn-gold-table-title3">£0.00</p>
                                </div>
                            </div>
                        </div>
                        <!-- platinum table -->
                        <div class="dsgn-gold-table-mble" data-metal="platinum">
                            <h5 class="dsgn-gold-gold dsgn-gold-font16">9ct Gold</h5>
                            <div class="dsgn-gold-head-table-element">
                                <div class="dsgn-gold-head-table">
                                    <p class="dsgn-gold-table-title dsgn-gold-table-title1 ab">Weight (g)</p>
                                    <p class="dsgn-gold-table-title dsgn-gold-table-title2">price (g)</p>
                                    <p class="dsgn-gold-table-title dsgn-gold-table-title3">Value</p>
                                </div>
                                <div class="dsgn-gold-t-field">
                                    <input class="dsgn-gold-number-input dsgn-gold-table-title1" type="number" value="0"
                                        min="0" step="0.1" data-carat="${item.carat}" />
                                    <p class="dsgn-gold-p-i dsgn-gold-table-title2">£34.36</p>
                                    <p class="dsgn-gold-value dsgn-gold-table-title3">£0.00</p>
                                </div>
                            </div>
                        </div>
                        <!-- silver table -->
                        <div class="dsgn-gold-table-mble" data-metal="silver">
                            <h5 class="dsgn-gold-gold dsgn-gold-font16">9ct Gold</h5>
                            <div class="dsgn-gold-head-table-element">
                                <div class="dsgn-gold-head-table">
                                    <p class="dsgn-gold-table-title dsgn-gold-table-title1 ab">Weight (g)</p>
                                    <p class="dsgn-gold-table-title dsgn-gold-table-title2">price (g)</p>
                                    <p class="dsgn-gold-table-title dsgn-gold-table-title3">Value</p>
                                </div>
                                <div class="dsgn-gold-t-field">
                                    <input class="dsgn-gold-number-input dsgn-gold-table-title1" type="number" value="0"
                                        min="0" step="0.1" data-carat="${item.carat}" />
                                    <p class="dsgn-gold-p-i dsgn-gold-table-title2">£34.36</p>
                                    <p class="dsgn-gold-value dsgn-gold-table-title3">£0.00</p>
                                </div>
                            </div>
                        </div>
                        <!-- palladium table -->
                        <div class="dsgn-gold-table-mble" data-metal="palladium">
                            <h5 class="dsgn-gold-gold dsgn-gold-font16">9ct Gold</h5>
                            <div class="dsgn-gold-head-table-element">
                                <div class="dsgn-gold-head-table">
                                    <p class="dsgn-gold-table-title dsgn-gold-table-title1 ab">Weight (g)</p>
                                    <p class="dsgn-gold-table-title dsgn-gold-table-title2">price (g)</p>
                                    <p class="dsgn-gold-table-title dsgn-gold-table-title3">Value</p>
                                </div>
                                <div class="dsgn-gold-t-field">
                                    <input class="dsgn-gold-number-input dsgn-gold-table-title1" type="number" value="0"
                                        min="0" step="0.1" data-carat="${item.carat}" />
                                    <p class="dsgn-gold-p-i dsgn-gold-table-title2">£34.36</p>
                                    <p class="dsgn-gold-value dsgn-gold-table-title3">£0.00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dsgn-gold-total-price-body">
                    <h4 class="dsgn-gold-estimate">Estimated Total Payment</h4>
                    <div class="dsgn-gold-metal-totals">
                        <div class="dsgn-gold-metal" data-metal="gold">
                            <p class="dsgn-gold-font16">Gold</p>
                            <p class="dsgn-gold-total-price dsgn-gold-font16">£0.00</p>
                        </div>

                        <div class="dsgn-gold-metal" data-metal="silver">
                            <p class="dsgn-gold-font16">Silver</p>
                            <p class="dsgn-gold-total-price dsgn-gold-font16">£0.00</p>
                        </div>

                        <div class="dsgn-gold-metal" data-metal="platinum">
                            <p class="dsgn-gold-font16">Platinum</p>
                            <p class="dsgn-gold-total-price dsgn-gold-font16">£0.00</p>
                        </div>

                        <div class="dsgn-gold-metal" data-metal="palladium">
                            <p class="dsgn-gold-font16">Palladium</p>
                            <p class="dsgn-gold-total-price dsgn-gold-font16">£0.00</p>
                        </div>

                        <hr class="dsgn-gold-hr" />

                        <div class="dsgn-gold-estimate-price">
                            <p class="dsgn-gold-font16">Estimated</p>
                            <p class="dsgn-gold-estimated-price dsgn-gold-price">£0.00</p>
                        </div>
                    </div>

                    <button class="dsgn-gold-apply"  disabled>Apply Now</button>
                </div>

            </div>
        </div>
    </div>
</section>

