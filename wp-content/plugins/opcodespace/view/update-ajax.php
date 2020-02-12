      <?php
        global $wpdb;
        $user = get_current_user_id();

        $current_user = wp_get_current_user();
        $tt = $current_user->user_email;
        echo '<pre>';
        print_r($_SESSION[$current_user->user_email]);
        echo '</pre>';

        $OpsWooCrud = new OpsWooCrud();
        // $getAll = $OpsWooCrud->search($_GET['search_col'], $_GET['search_term']);
        // $getAll = $OpsWooCrud->getAll('id');
        $getAll = $wpdb->get_results("SELECT * FROM wp_crud");

// Date filter
/*     global $wpdb;
    $query = "SELECT * FROM wpdh_bookings ";
       if(isset($_POST['button_search'])){
          $fromDate = $_POST['fromDate'];
          $endDate = $_POST['endDate'];

          if(!empty($fromDate) && !empty($endDate)){
             $query .= " WHERE schedule_date 
                        between '".$fromDate."' and '".$endDate."' ";
          }
        }
        // Sort
        $query .= " ORDER BY schedule_date DESC";
        $get_total = $wpdb->get_results($query); */
// Date filter End


        // echo '<pre>';
        // print_r($getAll);
        // echo '</pre>';
        ?>

      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


      <?php
if (isset($_GET['form'])) {
   $_SESSION['eName'] = $_GET['event_name'];
}

?>

      <div class="container">

      
<div class="form-group row">
    <label for="event_name" class="col-sm-2  col-md-1 col-form-label">Event Name</label>
    <div class="col-sm-4 col-md-4">
    <form method="GET" action="">
        <select class="form-control event" name='event_name' id='event_name'>
            <option value="Option_1">Option 1</option>
            <option value="Option_2">Option 2</option>
            <option value="Option_3">Option 3</option>
            <option value="Option_4">Option 4</option>
            <option value="Option_5">Option 5</option>
        </select>
        <input type="submit" name="form">
    </form>
    </div>
</div>

<h4><?php if(isset($_SESSION['eName'])) { echo $_SESSION['eName']; }?></h4>


          <!-- Search filter -->
          <form method='post' action=''>
              Start Date <input type='text' class='dateFilter' name='fromDate' value='<?php if (isset($_POST['fromDate'])) echo $_POST['fromDate']; ?>'>

              End Date <input type='text' class='dateFilter' name='endDate' value='<?php if (isset($_POST['endDate'])) echo $_POST['endDate']; ?>'>

              <input type='button' name='but_search' value='Search'>
          </form>

          <!--           <h3 align="center">Order Data</h3><br />
          <div class="col-md-3">
              <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" />
          </div>
          <div class="col-md-3">
              <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" />
          </div>
          <div class="col-md-5">
              <input type="button" name="filter" id="filter" value="Filter" class="btn btn-info" />
          </div> -->
          <div style="clear:both"></div>



          <h2>Basic Table</h2>
          <div id="order_table">
              <table class="table">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>Firstname</th>
                          <th>Lastname</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Country</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($getAll as $getEmp) : ?>
                          <tr>
                              <td><?= $getEmp->id; ?></td>
                              <td><?= $getEmp->first_name; ?></td>
                              <td><?= $getEmp->last_name; ?></td>
                              <td><?= $getEmp->email; ?></td>
                              <td><?= $getEmp->mobile_phone; ?></td>
                              <td><?= $getEmp->country; ?></td>

                              <!-- <td> <a href="#" data-role="update" data-id="<?= $getEmp->id; ?>">Update</a>
                          </td> -->
                              <td>
                                  <span class="update-ajax" data-toggle="modal" data-target="#myModal" data-id="<?= $getEmp->id; ?>"><i class="fa fa-edit"></i></span>
                              </td>
                          </tr>
                      <?php endforeach; ?>
                  </tbody>
              </table>
          </div>
      </div>


      <!-- Modal -->
      <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Update</h4>
                  </div>
                  <form class="" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                      <input type="hidden" name="action" value="update_ajax">
                      <input type="hidden" name="id" id="id" class="form-control">
                      <div class="form-group">
                          <label for="pwd">FirstName:</label>
                          <input type="text" class="form-control" name="firstName">
                      </div>
                      <div class="form-group">
                          <label for="pwd">LastName:</label>
                          <input type="text" class="form-control" name="lastName">
                      </div>
                      <div class="form-group">
                          <label for="email">Email address:</label>
                          <input type="email" class="form-control" name="email">
                      </div>
                      <div class="form-group">
                          <label for="country">Country: </label>
                          <select class="select-style form-control details-input-control" value="<?= $getEmp->country; ?>" name="country">
                              <?php foreach (OpsSelectOption::CountryName() as $country) : ?>
                                  <option <?= $country == $getEmp->country ? "selected" : ""; ?>><?= $country; ?></option>
                              <?php endforeach; ?>
                          </select>
                      </div>

                      <div class="form-group">
                          <label for="address">Address:</label>
                          <input type="text" name="home_address" value="" class="form-control">
                      </div>
                      <div class="form-group">
                          <label for="mobile">Phone: </label>
                          <input type="number" name="mobile_phone" value="" class="form-control">
                      </div>
                      <div class="form-group">
                          <label for="mobile">Note: </label>
                          <input type="text" name="note_update" value="" class="form-control">
                      </div>

                      <button type="submit" class="btn btn-primary pull-right" id="save">Submit</button>
                      <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                  </form>
              </div>
          </div>
      </div>
      </div>



      <script>
          $(document).ready(function() {

              $.datepicker.setDefaults({
                  dateFormat: 'yy-mm-dd'
              });

              $(function() {
                  $("#from_date").datepicker();
                  $("#to_date").datepicker();
              });

              $('#filter').click(function() {
                  console.log('done');

                  var from_date = $('#from_date').val();
                  var to_date = $('#to_date').val();
                  if (from_date != '' && to_date != '') {
                      $.ajax({
                          url: frontend_form_object.ajaxurl,
                          type: "POST",
                          data: {
                              action: date_filter,
                              from_date: from_date,
                              to_date: to_date
                          },
                          success: function(data) {
                              $('#order_table').html(data);
                          }
                      });
                  } else {
                      alert("Please Select Date");
                  }
              });
          });
      </script>