(function($) {
    $(document).ready(function() {
        var picker = document.getElementById('classcolorpicker');
        var text = document.getElementById('classcolor');

        // Sync color picker → text input
        picker.addEventListener('input', function() {
            text.value = this.value;
        });

        // Sync text input → color picker
        text.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                picker.value = this.value;
            }
        });
    });
})(jQuery);

function check_form()
{
    if (document.getElementById('class_name').value.length < 2) {
        alert('{MSG_NAME_EMPTY}');
        return false;
    }
    if (document.getElementById('class_id').value.length < 1) {
        alert('{MSG_ID_EMPTY}');
        return false;
    }
    return true;
}

function isValidChar(char, e){
    var txt = char;
    var found = false;
    var validChars = "0123456789";

    for(var j=0;j<txt.length;j++){
        var c = txt.charAt(j);
        found = false;
        for(var x=0;x<validChars.length;x++){
            if(c==validChars.charAt(x)) {
                found=true;
                break;
            }
        }
        if(!found) {
            document.getElementById(e).value = char.substring(0, char.length -1);
            break;
        }
    }
}
