var ac = {
  init: function(){
    console.log("loaded");
    $("form.autocomplete").each(ac.setupAutocomplete);
  },
  
  setupAutocomplete: function(){
    var form = $(this);
    var id = form.attr("id");
    var input = $("input:text", form);
    var submit = $("input:submit", form);
    
    var q = $(".q", form);
    var limit = $(".limit", form);

    var url = form.attr("action");
    var cb = form.attr("title");
    if (cb)
      url += "?" + cb;
          
    var options = { 
      url: url,
      autoFill: true,
      delay: 500,
      dataType: "json",
      extraParams: {},
      queryVar: q.attr("name"),
      parse: response[id],
    };
    
    options.extraParams[limit.attr("name")] = limit.val();
    
    input.val(submit.val()).click(ac.empty).autocomplete(options).autocomplete("result", ac.selected);
    submit.hide();
  },
  
  search: function(e){
    var site = ac.sites[id];
    var url = site[0];
    var siteOptions = site[1];
    siteOptions[siteOptions[q]] = input.val();
  },
  
  empty: function(e){
  	var input = $(e.target);
  	var submit = $("input:submit", input.parent());
  	if (input.val() == submit.val())
  		input.val("");
  },
  
  selected: function(e, item){
    e.preventDefault();
    var input = $(e.target);
    console.log(item);
    console.log(input);
    return false;
    $(input.parent()).submit();
  }
};

var response = {};

$().ready(ac.init);

