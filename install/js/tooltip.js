$(document).ready(function()
{
  $("img.warning").hover(function(e)
  {
    this.t = this.title;
    this.title = "";
    $("body").append('<p id="tooltip">'+ this.t +'</p>');
    $("#tooltip").css("top",(e.pageY - 10) + "px").css("left",(e.pageX + 20) + "px").fadeIn("fast");
  },
  function()
  {
    this.title = this.t;
    $("#tooltip").remove();
  });
});
