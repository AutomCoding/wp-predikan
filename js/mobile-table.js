if (window.matchMedia("(max-width: 800px)").matches) {
  $("table.predikan-table tbody tr").each(function(index) {
    current_row = $("table.predikan-table tbody tr").eq(index);
    current_row.prepend("<th class=\"predikan_toggle_hide\">"+this.cells[2].innerText+"</th>");
    current_row.find("td").slice(2, 3).remove();
  });

  $("table.predikan-table thead").remove();
  $("table.predikan-table th").css("display", "block");
  $("table.predikan-table td").css("display", "block");
}
