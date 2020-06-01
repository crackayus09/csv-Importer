$(function () {
  $(".custom-file-input").on("change", function () {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
  });

  $("#csv_grid_submit").click(function () {
    var formData = new FormData();
    formData.append("file", $("#csv_upload_file_inp")[0].files[0]);
    formData.append("action", "upload");

    $.ajax({
      url: location.href + "parser",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (data) {
        if (data.success) {
          show_grid(data.headers);
          $("#export_option_row").removeClass("d-none");
          var checkbox_html = "";
          data.headers.forEach(function (item) {
            checkbox_html +=
              '<input type="checkbox" class="column_checkboxes mr-1" name="column_checkbox" value="' +
              item +
              '" checked><label class ="mr-3"> ' +
              item +
              "</label>";
          });
          $("#column_checkboxes").html(checkbox_html);
          $("#select_csv_column").removeClass("d-none");
        } else {
          alert(data.message);
        }
      },
    });
  });

  $("#csv_select_columns").click(function () {
    var columns = [];
    $("#csv_grid_data").jsGrid("openPage", 1);
    $("input:checkbox[name=column_checkbox]:checked").each(function () {
      columns.push($(this).val());
    });
    show_grid(columns);
  });

  $("#csv_grid_export").click(function () {
    if (!$("#csv_grid_data").html()) {
      alert("Invalid Access");
      exit;
    }
    var filters = $("#csv_grid_data").jsGrid("getFilter");
    var exp_option = $("#csv_export_option").val();
    var json_data = { exp_option, filters };
    var url = $.ajax({
      url: location.href + "export",
      type: "POST",
      data: JSON.stringify(json_data),
      contentType: "application/json",
      dataType: "json",
      success: function (res) {
        if (res.success) {
          downloadURI(location.href + res.data);
        } else {
          alert(res.message);
        }
      },
    });
  });

  function show_grid(header) {
    var header_arr = header.map((obj) => ({
      name: obj,
      type: "text",
    }));
    $("#csv_grid_data").jsGrid({
      height: "550px",
      width: "100%",

      sorting: false,
      autoload: true,
      filtering: true,
      paging: true,
      pageSize: 10,
      pageButtonCount: 5,
      pageLoading: true,

      controller: {
        loadData: function (filter) {
          var d = $.Deferred();

          $.ajax({
            type: "GET",
            url: location.href + "items",
            data: filter,
            dataType: "json",
          }).done(function (response) {
            d.resolve(response);
          });

          return d.promise();
        },
      },

      fields: header_arr,
    });
  }
  function downloadURI(uri) {
    var link = document.createElement("a");
    link.href = uri;
    link.target = "_blank";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    delete link;
  }
});
