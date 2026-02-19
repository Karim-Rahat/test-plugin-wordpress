<div id="cvs_leaderboard">
    <div class="container">
        <!-- Other Contestants Grid -->
        <div class="row">
            <?php foreach ($contestant_array as $index => $contestant):
                    ++$index;
                    switch ($index) {
                        case 1:
                            $suffix = 'st';
                            break;
                        case 2:
                            $suffix = 'nd';
                            break;
                        case 3:
                            $suffix = 'rd';
                            break;
                        default:
                            $suffix = 'th';
                    }
                ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="contestant-box">
                    <?php if ($index === 1): ?>
                    <div class="medal-icon gold">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <?php elseif ($index === 2): ?>
                    <div class="medal-icon silver">
                        <i class="fas fa-medal"></i>
                    </div>
                    <?php elseif ($index === 3): ?>
                    <div class="medal-icon bronze">
                        <i class="fas fa-medal"></i>
                    </div>
                    <?php endif?>
                    <img decoding="async" src="<?php echo $contestant['image'] ?>"
                        alt="<?php echo $contestant['name'] ?>" class="contestant-img">
                    <div class="contestant-info">
                        <h5 class="contestant-name"><?php echo $contestant['name'] ?></h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="votes-count">
                                <i class="fa-solid fa-tag"></i> <?php echo $contestant['category'] ?? '' ?> &nbsp;&nbsp;
                                <span class="cvs_no_wrap"><i class="fas fa-heart text-danger"></i>
                                    <?php echo $contestant['vote'] ?? 0 ?> Votes</span>
                            </span>
                            <span class="rank-badge">
                                <?php echo $index ?><sup><?php echo $suffix ?></sup>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach?>
        </div>
    </div>
</div>