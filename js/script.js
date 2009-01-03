var searchBox;

$().ready(function(){
  searchBox = $("#search").clone();

  $("a.player").click(function(e){
  //$("a.player").live("click", function(e){
    e.preventDefault();
    $("#player").attr("data", this.href);
    //return false;
  });
  
  $("#search").submit(function(e){
  //$("#search").live("submit", function(e){
    e.preventDefault();
    $("#sidebar").load($("#search").attr("action"), { q: $("#sidebar .q").val(), n: $("#sidebar .limit").val() }, searchLoaded);
    //return false;
  });
});

function searchLoaded(){
  $("#sidebar").prepend(searchBox);
    
  var reset = $("<a href='sidebar.php'>Reset</a>");
  $("#sidebar").prepend(reset);
  reset.click(function(e){
    e.preventDefault();
    $("#sidebar").load("sidebar.php");
    return false;
  });
}

