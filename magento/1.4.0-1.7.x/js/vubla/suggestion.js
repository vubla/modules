function vublaAddOnloadFunction(callback)
{
    var oldonload = window.onload;
    if (typeof window.onload != 'function') {
        window.onload = function() {
            callback();
        };
    } else {
        window.onload = function() {
            if (oldonload) {
                oldonload();
            }
            callback();
        }
    }
}

vublaAddOnloadFunction(function() 
{
    var search_element = document.getElementById("search");
    if(search_element != null)
    {
        search_element.focus();
    }
});
