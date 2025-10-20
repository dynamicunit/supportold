
$(document).ready(function () {
    var imageArray = []; // Array to store image data
    // Function to handle file selection
    function handleFileSelect(event) {
        var files = event.target.files; // Get selected files

        // Loop through each file
        for (var i = 0; i < files.length; i++) {
            var file = files[i];

            // Check if the file is an image
            if (file.type.match('image.*')) {
                // Check if the image is already uploaded
                if (isImageUploaded(file)) {

                    $('#error-message').text('Duplicate image: ' + file.name);

                    continue;
                }

                var reader = new FileReader();

                // Closure to capture the file information
                reader.onload = (function (file) {
                    return function (e) {
                        $(
                            '<div class="thumbs me-3">' +
                            '<img src="' +
                            e.target.result +
                            '" title="' +
                            file.name +
                            '" width="132" class="rounded thumb-img w-full"/>' +
                            '<div class="btn-light delete_existing_pic remove"><small>Delete</small></div>' +
                            "</div>"
                        ).appendTo("#uploaded");

                        // Push the image data to the array
                        imageArray.push(file);

                        // Event handler for delete button click
                        $('.remove').on('click', function () {
                            // Remove the container from the preview div
                            // $('.thumbs').remove();
                            $(this).parent().remove();
                            // Remove the image data from the array
                            imageArray.splice(imageArray.indexOf(file), 1);
                        });

                    };
                })(file);

                // Read the image file as a data URL
                reader.readAsDataURL(file);
            } else {
                // Display error message for unsupported file formats
                $('#error-message').text('Error: Only JPEG, PNG, and GIF images are allowed.');
            }
        }
    }

    // Function to check if image is already uploaded
    function isImageUploaded(file) {
        var duplicate = false;

        for (var i = 0; i < imageArray.length; i++) {
            if (imageArray[i].name === file.name && imageArray[i].size === file.size && imageArray[i].type === file.type) {
                duplicate = true;
                break;
            }
        }

        return duplicate;
    }

    // Event listener for upload button click
    $('#upload-button').on('click', function () {

        if (imageArray.length < 5) {
            $('#item_img').trigger('click');
            $('#error-message').text('');
        } else {
            $('#error-message').text('You can upload a maximum of 5 images.');
        }
    });

    // Event listener for file input change
    $('#item_img').on('change', handleFileSelect);
});



// $(document).ready(function () {
//     const max_images = 5;
//     const imag = [];
//     if (window.File && window.FileList && window.FileReader) {
//         $("#item_img").on("change", function (e) {
//             var files = e.target.files,
//                 filesLength = files.length;
//             for (var i = 0; i < filesLength; i++) {
//                 var f = files[i];
//                 var fileReader = new FileReader();
//                 fileReader.onload = function (e) {
//                     imag.push(
//                         img_name = e.target.result,
//                     );

//                     var file = e.target;
//                     $(
//                         '<div class="thumbs me-3">' +
//                         '<img src="' +
//                         e.target.result +
//                         '" title="' +
//                         file.name +
//                         '" width="132" class="rounded thumb-img w-full"/>' +
//                         '<div class="btn-light delete_existing_pic" id="remove"><small>Delete</small></div>' +
//                         "</div>"
//                     ).appendTo("#uploaded");
//                     $(".remove").click(function () {
//                         $(this).parent(".thumbs").remove();
//                     });
//                 };
//                 fileReader.readAsDataURL(f);
//             }


//             console.log(imag.length);
//         });
//     } else {
//         alert("Your browser doesn't support to File API");
//     }
// });

// upload image

const uploadButton = document.getElementById("upload-logo-btn");
const fileInput = document.getElementById("logo_img");
const imagePreview = document.getElementById("imagePreview");
const deleteButton = document.getElementById("delete-logo-btn");

uploadButton.addEventListener("click", function () {
    fileInput.click();
});

fileInput.addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.addEventListener("load", function () {
            imagePreview.setAttribute("src", this.result);
        });
        reader.readAsDataURL(file);
    }
});
deleteButton.addEventListener("click", function () {
    imagePreview.setAttribute("src", "#");
    fileInput.value = "";
});


// remove first --select
$('.js-example-basic-single').select2({ maximumSelectionLength: 5 });
var isFirstOptionRemoved = false;

$("#additional-category").on("change", function () {
    if (!isFirstOptionRemoved) {
        $(this).find("option:first").remove();
        isFirstOptionRemoved = true;
    }
});



