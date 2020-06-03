<?php
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <title>CSV Importer</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      type="text/css"
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css"
    />
    <link
      type="text/css"
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css"
    />

    <script
      type="text/javascript"
      src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"
    ></script>
    <script src="assets/js/custom/csv_importer.js"></script>
  </head>

  <body class="overflow-hidden">
    <div>
      <div class="row mt-3">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
          <form class="border border-primary p-5">
            <div class="row">
              <div class="col-sm-10">
                <div class="custom-file">
                  <input
                    type="file"
                    class="custom-file-input"
                    id="csv_upload_file_inp"
                    name="csv_file"
                    accept=".csv"
                  />
                  <label class="custom-file-label" for="csv_upload_file_inp">
                    Choose file
                  </label>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="">
                  <button
                    type="button"
                    class="btn btn-primary"
                    id="csv_grid_submit"
                  >
                    Submit
                  </button>
                </div>
              </div>
            </div>
            <!-- d-none -->
            <div class="row mt-3 d-none" id="export_option_row">
              <div class="col-sm-3"></div>
              <div class="col-sm-6 d-flex justify-content-center">
                <select
                  name="export_option"
                  id="csv_export_option"
                  class="h-100 mr-2"
                >
                  <option value="" disabled selected>Select Option</option>
                  <option value="csv">CSV</option>
                  <option value="excel">Excel</option>
                  <option value="pdf">PDF</option>
                </select>
                <button
                  type="button"
                  class="btn btn-success"
                  id="csv_grid_export"
                >
                  Export
                </button>
              </div>
              <div class="col-sm-3"></div>
            </div>
            <div class="row mt-3 d-none" id="select_csv_column">
              <div class="col-sm-10" id="column_checkboxes"></div>
              <div class="col-sm-2">
                <button
                  type="button"
                  class="btn btn-danger"
                  id="csv_select_columns"
                >
                  Select
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="col-sm-3">
          <h4>Previous Files:</h4>
          <ul id="previous_file_list">
            <?php
              if (count($previous_files) > 0) {
                  foreach ($previous_files as $previous_file) {
                      echo '<li><a href="javascript:void(0);" onclick="showPreviousFile(\'' .
                          $previous_file["actual_name"] .
                          '\'); return false;">' .
                          $previous_file["user_file_name"] .
                          '</a></li>';
                  }
              }
            ?>
          </ul>
        </div>
      </div>
      <div class="row mt-2">
        <div class="col-sm-1"></div>
        <div id="csv_grid_data" class="col-sm-10" cellspacing="0"></div>
        <div class="col-sm-1"></div>
      </div>
    </div>
  </body>
</html>
