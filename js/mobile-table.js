jQuery(document).ready(function() {
  if (window.matchMedia("(max-width: 800px)").matches) {
    jQuery("table.predikan-table tbody tr").each(function(index) {
      current_row = jQuery("table.predikan-table tbody tr").eq(index);
      current_row.prepend("<th class=\"predikan_toggle_hide\">"+this.cells[2].innerText+"</th>");
      current_row.find("td").slice(2, 3).remove();
    });

    jQuery("table.predikan-table thead").remove();
    jQuery("table.predikan-table th").css("display", "block");
    jQuery("table.predikan-table td").css("display", "block");
  }
});

