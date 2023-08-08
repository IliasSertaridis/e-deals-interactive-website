$(function(){
  $("#submit-nav").attr("class", "nav-link active");
});

var last;
function select(element)
            {
    if (typeof last !== "undefined")
{
        last.setAttribute("class", "accordion-button collapsed");
    }
    if (element.getAttribute("class") === "accordion-button collapsed")
{
        element.setAttribute("class", "accordion-button");
    }
    else
{
        element.setAttribute("class", "accordion-button collapsed");
    }
    last = element;
}
