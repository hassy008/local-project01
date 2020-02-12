<?php

$OpsSchedule = new OpsSchedule();
$schedules = $OpsSchedule->scheduleList();
$user = wp_get_current_user();

echo '<pre>';
print_r($schedules);
echo '</pre>';
?>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h2>Schedule form</h2>
            <form class="" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                <input type="hidden" name="action" value="add_event_schedule">
                <input type="text" name="user_id" value="<?= $user->ID; ?>">
                <div class="form-group">
                    <label for="event">Event Name:</label>
                    <select name="event_name">
                        <option value="Event_No_01">Event_No 01</option>
                        <option value="Event_No_02">Event_No 02</option>
                        <option value="Event_No_03">Event_No 03</option>
                        <option value="Event_No_04">Event_No 04</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="email">Date/Day:</label>
                    <input type="text" class="form-control" id="Date/Day" placeholder="Enter Date/Day" name="date_day">
                </div>
                <div class="form-group">
                    <label for="email">Time From: </label>
                    <input type="text" class="form-control" id="time_from" placeholder="Enter time_from" name="time_from">
                </div>
                <div class="form-group">
                    <label for="email">Time to: </label>
                    <input type="text" class="form-control" id="time_to" placeholder="Enter time_to" name="time_to">
                </div>
                <div class="form-group">
                    <label for="email">Type: </label>
                    <input type="text" class="form-control" id="type" placeholder="Enter type" name="type">
                </div>
                <div class="form-group">
                    <label for="email">Availability: </label>
                    <input type="text" class="form-control" id="availability" placeholder="Enter availability" name="availability">
                </div>
                <div class="form-group">
                    <label for="email">test_time: </label>
                    <input type="text" class="form-control" id="availability" placeholder="Enter availability" name="test_time">
                </div>

                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="hotal-wrapper">
                <div class="hotel-booking-table">
                    <div class="text-right form-group">
                        <button class="btn btn-danger" data-toggle="modal" data-target="#addHotelModal">Add
                            Employee</button>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><a href='?order=id&&sort=$sort'><?php _e('ID', 'opcodespace') ?></a></th>
                                <th>Date/Day</th>
                                <th>Time From</th>
                                <th>Time To</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($schedules as $getAllData) : ?>
                                <tr>
                                    <td class="action"><?= $getAllData->id; ?></td>
                                    <td><?= $getAllData->date_day; ?></td>
                                    <td><?= $getAllData->time_from; ?></td>
                                    <td><?= $getAllData->time_to ?></td>
                                    <td class="action">
                                        <span class="delete-schedule" data-id="<?= $getAllData->id; ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></span>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>