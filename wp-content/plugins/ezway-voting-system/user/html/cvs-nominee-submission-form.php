<div id="cvs_nominee_submissions" style="<?php echo esc_attr($style ?? '') ?>">
    <div class="cvs_nominee_form">
        <form method="POST" id="cvs_submit_nomination" enctype="multipart/form-data">
            <p class="alert alert-danger mb-4 error-message" style="display:none;"></p>
            <div class="mb-4    ">
                <label for="cvs_name">Full Name <span style="color:red">*</span></label>
                <input required required type="text" id="cvs_name" name="name" class="form-control"
                    placehlder="Enter your name">
            </div>
            <div class="mb-4">
                <label for="cvs_email">Email Address <span style="color:red">*</span></label>
                <input required type="email" id="cvs_email" name="email" class="form-control"
                    placehlder="Enter email address">
            </div>
            <div class="mb-4">
                <label for="cvs_phone">Phone Number <span style="color:red">*</span></label>
                <input required type="text" id="cvs_phone" name="phone" class="form-control"
                    placehlder="Enter phone number">
            </div>
            <div class="mb-4">
                <label for="cvs_category">Category <span style="color:red">*</span></label>
                <select required name="category" id="cvs_category" class="form-control">
                    <option value="">Please select a category —</option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?php echo esc_attr(trim($category)) ?>"><?php echo $category ?></option>
                    <?php endforeach?>
                </select>
            </div>
            <div class="mb-4">
                <label for="cvs_image">Upload Image <span style="color:red">*</span></label>
                <input required type="file" id="cvs_image" name="image" class="form-control">
                <img id="cvs_nominee_avatar" style="display:none" src="" class="img-thumbnail mt-2" width="120"
                    height="120">
            </div>
            <button type="submit" id="cvs_nominee_form_submit_btn"><span style="display:none;"><i
                        class="fa-solid fa-spinner fa-spin-pulse"></i>&nbsp;</span>Submit Nomination</button>
        </form>
        <div id="cvs_nomination_success" style="display:none">
            <div class="cvs_nomination_complete">
                <i class="fa-solid fa-circle-check fs-3 fa-bounce"></i>
                <h3 class="mt-1">Success</h3>
                <p class="mb-0 mt-3">Nomination has been submitted successfully.</p>
            </div>
        </div>
    </div>
</div>