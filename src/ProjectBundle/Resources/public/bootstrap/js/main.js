//$('.dropdown-toggle').dropdown()

$(function(){

console.log("coucou");
let ga = document.getElementById('fos_user_registration_form_imageFile');
let gu = document.getElementById('fos_user_registration_form');


let img = document.createElement('img');
img.src ="";

gu.appendChild(img);

    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                console.log(img);
                img.src=e.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        }
    }


    $('#fos_user_registration_form_imageFile').change(function(){
        readURL(this);
    });


});
