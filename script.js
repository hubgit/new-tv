$().ready(function(){
  $("a.player").click(function(e){
    e.preventDefault();
    $("#player").attr("data", this.href);
    return false;
  });
});
