function searchLoaded(){    
  var reset = $("<a href='sidebar.php'>Reset</a>");
  $("#sidebar").prepend(reset);
  reset.click(function(e){
    e.preventDefault();
    $("#sidebar").load("sidebar.php");
    return false;
  });
}

function init(){
  var searchBox = $("#search");
  
  var input = $("input:text", searchBox);
  var submit = $("input:submit", searchBox);
  
  console.log(input.val());
  if (!input.val())
    input.val(submit.val()).click(function(e){
    	var input = $(e.target);
    	var submit = $("input:submit", input.parent());
    	if (input.val() == submit.val())
    		input.val("");
    });
  //submit.hide();

  $("a.player").click(function(e){
  //$("a.player").live("click", function(e){
    e.preventDefault();
    $("#player").attr("data", this.href);
    //return false;
  });
  
  $("#search").submit(function(e){
  //$("#search").live("submit", function(e){
    e.preventDefault();
    location.hash = "#search";
    $("#sidebar").load($("#search").attr("action"), { q: $("#sidebar .q").val(), n: $("#sidebar .limit").val() }, searchLoaded);
    //return false;
  });
}

