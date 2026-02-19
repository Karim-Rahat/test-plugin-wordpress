<section class="gold-contact-form-container">
    <div class="gold-form-heading-div">
        <h1 class="instant-title"><?= $title ?></h1>
        <p class="instant-para">
            Please enter your item information below to receive an instant quote.
        </p>
    </div>

    <div class="sell-scrap-contact-container">
        <!-- sell scrap gold-->
        <div class="sell-scrap-container">
            <div class="sell-scrap-dropdown" bis_skin_checked="1">
                <h4 class="sell-scrap-title">Particulars</h4>
<!-- 				<small style="
					display: block;
					font-size: 11px;
					line-height: 1.4;
					font-family: 'Poppins', sans-serif;
					color: #555;
				">
					If you are unsure of the weight, please leave blank and fill in your contact details below. Once we receive your precious gold, we'll weigh them and come back to you with a valuation.
				</small> -->
                <p class="instant-dropdown">
                    <img decoding="async" src="&lt;? ?&gt;" alt="">
                </p>
            </div>

            <div class="form-metal-main-container">
                <div class="form-metal-container">
                    <div class="form-metal-heading">
                        <p class="font16 scrap-metal-weight1">Weight (Grams)</p>
                        <p class="font16 scrap-metal-weight2">Scrap Metal</p>
                        <p class="font16 scrap-metal-weight3">Total Value</p>
                    </div>
                    <div class="form-metal-input-div">
                        <input class="scrap-metal-weight1 form-input-number font16" type="number" value="0" min="0"
                            step="0.01" />
                        <div class="scrap-metal-weight2">
                            <select class="scrap-metal-weight2 font16 form-input-select">
                                <option value="" disabled selected>9ct scrap gold</option>
                                <option value="iron">9ct scrap gold</option>
                            </select>
                        </div>
                        <p class="scrap-metal-weight3 font16 form-total-value">£0.00</p>
                    </div>
                </div>
                <div class="form-metal-container">
                    <div class="form-metal-heading">
                        <p class="font16 scrap-metal-weight1">Weight (Grams)</p>
                        <p class="font16 scrap-metal-weight2">Scrap Metal</p>
                        <p class="font16 scrap-metal-weight3">Total Value</p>
                    </div>
                    <div class="form-metal-input-div">
                        <input class="scrap-metal-weight1 form-input-number font16" type="number" value="0" min="0"
                            step="0.01" />
                        <div class="scrap-metal-weight2">
                            <select class="scrap-metal-weight2 font16 form-input-select">
                                <option value="" disabled selected>9ct scrap gold</option>
                                <option value="iron">9ct scrap gold</option>
                            </select>
                        </div>
                        <p class="scrap-metal-weight3 font16 form-total-value">£0.00</p>
                    </div>
                </div>

                <div class="add-minus-estimated-div">
                    <div class="add-minus-reset-div">
                        <p class="minus-div">-</p>
                        <div class="add-more-div">
                            <p class="plus-div">+</p>
                            <p class="font16 addmore">Add More</p>
                        </div>

                        <div class="reset-div">
                            <p class="reset-icon"><img
                                    src="<?php echo plugins_url(); ?>/dsgn-gold-calculator-master-final/assets/img/aaa.svg"
                                    alt=""></p>
                            <p class="font16 reset">Reset</p>
                        </div>
                    </div>
                    <p class="sell-scrap-title form-estimated-value">£0.00</p>
                </div>
                <div class="form-metal-container1">
                    <p class="font16 description">
                        Description of items you are sending - optional notes -information you would like to tell us
                    </p>
                    <div>
                        <textarea class="form-desciption text-area" name="" id=""></textarea>
                    </div>
                </div>

                <div class="image-pre-container">
                    <div id="imagePreviewContainer" style="">
                        <img id="imagePreview" src="" alt="Preview" style="" />
                    </div>
                    <div class="form-upload-image-div" id="form-upload-image-div" style="display:none">
                        <input type="file" id="imageUpload" accept="image/*" hidden />
                        <img class="upload-icon"
                            src="<?php echo plugins_url(); ?>/dsgn-gold-calculator-master-final/assets/img/icon.svg" alt="" />
                        <label for="imageUpload" class="upload-box">
                            <span class="upload-text font16">Upload Image</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <hr class="form-hr" />
        <!-- contact form -->

        <div class="contact-details-container">
            <div class="sell-scrap-dropdown contact-dropdown">
                <h4 class="sell-scrap-title">Contact Details</h4>
                <p class="contact-dropdown">
                    <img src="./assets/Vector.svg" alt="" />
                </p>
            </div>

            <div class="condition-contact-form-container">
                <div class="condition-container">
                    <!-- <div class="search-container">
                        <p class="find-text font16">Find an Address</p>
                        <p class="type-part">
                            Type part of an address or postcode to begin
                        </p>
                        <div class="search-location-div">
                            <input class="search-location" type="text" placeholder="Search your location" />
                            <span class="search-btn-div">Search</span>
                        </div>
                    </div> -->

                    <div class="request-container">
                        <!-- <label class="custom-checkbox">
                            <input type="checkbox" />
                            <span class="request-text">Request a Seller Pack?</span>
                        </label> -->
                        <div class="request-condition-div">
                           <?= $instructions ?>
                        </div>
                       
                    </div>
                        <div class="royal-logo" style="text-align: center; margin-bottom: 50px;">
                            <img src="<?php echo plugins_url(); ?>/dsgn-gold-calculator-master-final/assets/img/royal-mail-logo.png" style="width:70%" alt="Royal Mail Logo" />
                        </div>
                    <!-- <div class="register-container">
                        <label class="custom-checkbox">
                            <input type="checkbox" />
                            <span class="register-text">Register an Account?</span>
                        </label>
                        <p class="register-para">
                            View a history of all scrap transactions in your customer
                            account area.
                        </p>
                    </div> -->
                </div>

                <div class="contact-form-main-container">
                    <form class="contact-main-form">
                        <!-- First Name + Last Name -->
                        <div class="form-row">
                            <div class="lable-input">
                                <label class="all-label font16">First Name</label>
                                <input class="all-input first-name" placeholder="First Name" type="text"
                                    name="first_name" required />
                            </div>
                            <div class="lable-input">
                                <label class="all-label font16">Last Name</label>
                                <input class="all-input last-name" placeholder="Last Name" type="text" name="last_name"
                                    required />
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="lable-input">
                            <label class="all-label font16">Phone Number</label>
                            <input class="all-input main-phone" placeholder="XXX" type="number" name="main-phone"
                                required />
                        </div>

                        <!-- Email -->
                        <div class="lable-input">
                            <label class="all-label font16">Email</label>
                            <input class="all-input email" placeholder="example@mail.com" type="email" name="email"
                                required />
                        </div>

                        <!-- House Number + Town/City -->
                        <div class="form-row">
                            <div class="lable-input">
                                <label class="all-label font16">House Number</label>
                                <input class="all-input house-number" placeholder="" type="text" name="house-number"
                                    required />
                            </div>
                            <div class="lable-input">
                                <label class="all-label font16">Town/City</label>
                                <input class="all-input town" placeholder="" type="text" name="town" required />
                            </div>
                        </div>

                        <div class="form-row">
                            <!-- Apartment -->
                            <div class="lable-input">
                                <label class="all-label font16">Apartment, Suite, Unit etc</label>
                                <input class="all-input Apartment" placeholder="" type="Apartment" name="Apartment"
                                    required />
                            </div>

                            <!-- Postcode -->
                            <div class="lable-input">
                                <label class="all-label font16">Postcode</label>
                                <input class="all-input Postcode" placeholder="" type="Apartment" name="Postcode"
                                    required />
                            </div>

                        </div>



                        <!-- Checkbox -->
                        <!-- <div class="form-row single"><label class="check-news"><input type="checkbox" name="agree"
                                    required /><span>Newsletter</span>
                            </label>
                        </div> -->
                       <div class="get-btn-div" style="margin-top:20px">
                        <button class="get-btn submit-btn font16" id="submitBtn">
                            <span class="btn-text">Submit</span>
                            <span class="spinner" style="display:none;"></span>
                        </button>
                        </div>

                     
                        <div class="success-message" style="display:none;color:green;margin-top:10px;">
                           Thank you! Your submission has been received. 
                        </div>
                        <div class="error-message" style="display:none;color:red;margin-top:10px;">
                           Oops! Something went wrong. Please try again.    
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- <hr class="form-hr" />
        <hr class="form-hr" /> -->

        <!-- <div class="all-button-container">
            <div class="get-btn-div">
                <button class="get-btn font16">Get A Valuation</button>
                <p class="digital-copy">
                    Send us a digital copy of your scrap details & we will wait for
                    your parcel. You also get emailed a copy.
                </p>
            </div>
            <div class="get-btn-div">
                <button class="print-form font16">Print Form</button>
                <p class="digital-copy unable">
                    If unable to print please make a hand written note with the
                    required info.
                </p>
            </div>
        </div> -->
    </div>
</section>