<div class="tem-gcal-container">
    <div class="view-and-filters">
        <div class="view-icons">
            <div class="grid-view active tem-gcal-switch-view">
                <img src="<?php echo TEM_GCAL_INTEGRATION_URI  ?>/assets/img/grid.svg">
            </div>
            <div class="list-view tem-gcal-switch-view ">
                <img src="<?php echo TEM_GCAL_INTEGRATION_URI  ?>/assets/img/list-view.svg">
            </div>
        </div>
    </div>
    <div class="event-list-wrapper">
        
        <div class="event-list-grid grid-view-active">
<!-- <pre> -->
            <?php if(is_array($event_items) && !empty($event_items)): ?>
                <?php foreach($event_items as $index => $event_item): ?>
                    <?php 
                    // var_dump($event_item);
                        // Parse start and end datetime
                        $is_all_day = false;
                        $start_datetime = isset($event_item['start']['dateTime']) ? new DateTime($event_item['start']['dateTime']) : null;
                        $end_datetime = isset($event_item['end']['dateTime']) ? new DateTime($event_item['end']['dateTime']) : null;
                        
                        if(!$start_datetime) {
                            $start_datetime = isset($event_item['start']['date']) ? new DateTime($event_item['start']['date']) : null;
                            $is_all_day = true;
                            }
                        if(!$end_datetime) {
                            $end_datetime = isset($event_item['end']['date']) ? new DateTime($event_item['end']['date']) : null;
                        }
                            
                        $event_day = $start_datetime ? $start_datetime->format('d') : '';
                        $event_month = $start_datetime ? $start_datetime->format('F') : '';
                        $start_time = $start_datetime ? $start_datetime->format('H:i A') : '';
                        $end_time = $end_datetime ? $end_datetime->format('H:i A') : '';
                        
                        // Format date range
                        $start_date_formatted = $start_datetime ? $start_datetime->format('d-m-Y') : '';
                        $end_date_formatted = $end_datetime ? $end_datetime->format('d-m-Y') : '';
                        $date_range = $start_date_formatted && $end_date_formatted ? $start_date_formatted . ' - ' . $end_date_formatted : '';
                        
                        // Format time range
                        $time_range = $start_time && $end_time ? $start_time . ' - ' . $end_time : '';
                        // var_dump($is_all_day);
                        // if the summery is empty then skip the event 
                        if( empty( $event_item['summary'] ) ) {
                            continue;
                        }
                        
                        // Select random color from allowed colors
                        $allowed_colors = ['#ED1D24'];
                    ?>
                    <div class="single-event-card" style="--event-color: <?php echo esc_attr($allowed_colors[0]); ?>;">
                        <div class="event-datetime event-datetime-left">
                            <div class="event-date-month">
                                <h3 class="event-date"><?php echo esc_html($event_day); ?></h3>
                                <h2 class="event-month"><?php echo esc_html($event_month); ?></h2>
                            </div>
                        </div>
                        <div class="single-card-inner">
                            <div class="card-heading">
                                <div class="event-metadata">
                                    <div class="event-metadata-title-and-date">
                                          <div class="event-datetime event-datetime-top">
                                            <div class="event-date-month">
                                                <h3 class="event-date"><?php echo esc_html($event_day); ?></h3>
                                                <h2 class="event-month"><?php echo esc_html($event_month); ?></h2>
                                            </div>
                                        </div>
                                                <div class="bottom-shadow-box" style="
                                                    width: 100%;
                                                    height: 1px;
                                                    border-bottom: 1px solid #00000021;
                                                ">
                                                    
                                                </div>

                                        <h2 class="event-title"><?php echo isset($event_item['summary']) ? esc_html($event_item['summary']) : ""; ?></h2>
                                      
                                    </div>

                                    <div class="event-location-and-time">
                                        <?php if(isset($event_item['location']) && !empty($event_item['location'])): ?>
                                        <div class="event-info-item">
                                            <img src="<?php echo TEM_GCAL_INTEGRATION_URI  ?>/assets/img/location.svg">
                                            <p class="info-text"><?php echo esc_html($event_item['location']); ?></p>
                                        </div>
                                        <?php endif; ?>
                                        <div class="info-date-time-wrapper">
                                            <?php if(!empty($date_range)): ?>
                                            <div class="event-info-item">
                                                <img src="<?php echo TEM_GCAL_INTEGRATION_URI  ?>/assets/img/calendar.svg">
                                                <p class="info-text"><?php echo esc_html($date_range); ?></p>
                                            </div>
                                            <?php endif; ?>
                                            <?php if(!empty($time_range) && !$is_all_day): ?>
                                            <div class="event-info-item">
                                                <img src="<?php echo TEM_GCAL_INTEGRATION_URI  ?>/assets/img/clock.svg">
                                                <p class="info-text"><?php echo esc_html($time_range); ?></p>
                                            </div>

                                            <?php elseif($is_all_day): ?>
                                            <div class="event-info-item">
                                                <img src="<?php echo TEM_GCAL_INTEGRATION_URI  ?>/assets/img/clock.svg">
                                                <p class="info-text">All Day</p>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-content">
                                <?php 
                                    $description = isset($event_item['description']) ? $event_item['description'] : '';
                                    $char_limit = 200;
                                    $show_read_more = strlen($description) > $char_limit;
                                    $truncated = $show_read_more ? substr($description, 0, $char_limit) : $description;
                                ?>
                                <p class="card-description" data-full-text="<?php echo esc_attr($description); ?>">
                                    <?php echo wp_kses_post($truncated); ?><?php if($show_read_more): ?><span class="ellipsis">...</span><?php endif; ?>
                                </p>
                                <?php if($show_read_more): ?>
                                <div class="readmore">
                                    <p class="read-more-label">Read More</p>
                                    <img class="arrow-icon" src="<?php echo TEM_GCAL_INTEGRATION_URI  ?>/assets/img/arrow-down.svg" alt="Arrow Down">
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
        </div>
    </div>
</div>