
var selectorOwner = '#color_picker';
var selectorShowing = false;
var defaultColors = ['000000','993300','333300','000080','333399','333333','800000','FF6600','808000','008000','008080','0000FF','666699','808080','FF0000','FF9900','99CC00','339966','33CCCC','3366FF','800080','999999','FF00FF','FFCC00','FFFF00','00FF00','00FFFF','00CCFF','993366','C0C0C0','FF99CC','FFCC99','FFFF99','99CCFF','FFFAFA','FFFFFF'];

function colorPicker()
{
	buildSelector();
	$(selectorOwner).bind("click", toggleSelector);
}
function buildSelector()
{
	selector = $('<div id="color_selector"></div>');
	$.each(defaultColors, function(i,n){
	swatch = $("<div class='color_swatch'>&nbsp;</div>")
	swatch.css("background-color", "#" + n);
	swatch.bind("click", function(e){changeColor($(this).css("background-color")) });
	swatch.bind("mouseover", function(e){$(this).css("border-color", "#598FEF");$("#color_code").val(toHex($(this).css("background-color")));}); 
	swatch.bind("mouseout", function(e){$(this).css("border-color", "#000");$("#color_code").val(toHex($(selectorOwner).css("background-color")));});
	swatch.appendTo(selector);});
	$('<div id="color_custom"></div>').append('<input type="text" size="8" id="color_code" />').appendTo(selector);
	$("body").append(selector);
	$(selectorOwner).css("background-color", toHex($('#style_color').val()));
	selector.hide();
}
function changeColor(value)
{
    if(selectedValue = toHex(value))
    {
		$(selectorOwner).css("background-color", selectedValue);
		$('#style_color').val(selectedValue).change();
		hideSelector();
    }
}
function hideSelector()
{
	var selector = $("#color_selector");
	$(document).unbind("mousedown", checkMouse);
	selector.hide();
	selectorShowing = false
}
function showSelector()
{
	var selector = $("#color_selector");
	selector.css({top: $(selectorOwner).offset().top + $(selectorOwner).outerHeight(),left: $(selectorOwner).offset().left,zIndex:9000});
	$("#color_code").val($('#style_color').val());
	selector.show();
	$(document).bind("mousedown", checkMouse);
	selectorShowing = true;
}
function toggleSelector(event)
{
	selectorShowing ? hideSelector() : showSelector();
}
function checkMouse(event)
{
    var selector = "#color_selector";
    var selectorParent = $(event.target).parents(selector).length;
    if(event.target == $(selector)[0] || event.target == $(selectorOwner)[0] || selectorParent > 0) return;
    hideSelector();
}
function toHex(color)
{
    if(color.match(/[0-9a-fA-F]{3}$/) || color.match(/[0-9a-fA-F]{6}$/)){
      color = (color.charAt(0) == "#") ? color : ("#" + color);
    }
    else if(color.match(/^rgb\(([0-9]|[1-9][0-9]|[1][0-9]{2}|[2][0-4][0-9]|[2][5][0-5]),[ ]{0,1}([0-9]|[1-9][0-9]|[1][0-9]{2}|[2][0-4][0-9]|[2][5][0-5]),[ ]{0,1}([0-9]|[1-9][0-9]|[1][0-9]{2}|[2][0-4][0-9]|[2][5][0-5])\)$/)){
      var c = ([parseInt(RegExp.$1),parseInt(RegExp.$2),parseInt(RegExp.$3)]);
      var pad = function(str){
            if(str.length < 2){
              for(var i = 0,len = 2 - str.length ; i<len ; i++){
                str = '0'+str;
              }
            }
            return str;
      }
      if(c.length == 3){
        var r = pad(c[0].toString(16)),g = pad(c[1].toString(16)),b= pad(c[2].toString(16));
        color = '#' + r + g + b;
      }
    }
    else color = false;
    return color
}
function cancelColor()
{
	$(selectorOwner).css("background-color", '');
	$('#style_color').val('');
}
