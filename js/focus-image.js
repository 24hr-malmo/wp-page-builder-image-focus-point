jQuery(document).ready(function ($) {

    let list = $('.pbfi-gallery_widget_attached_images_list');
    let hidden = $('.focus_image');

    let focusVisualizer = $('<div class="pbfi--focus-visualizer"></div>');

    let focusInputX;
    let focusInputY;

    function addAddListeners() {

        $('.pbfi--gallery-widget--add-images').click(function(e) {

            e.preventDefault();

            var frame = wp.media({ multiple: false });

            frame.on('select', function() {

                var selection = frame.state().get('selection');

                selection.each(function(attachment) {

                    hidden.val(attachment.id);

                    let image = getNewImage(attachment);
                    list.html('');
                    list.append(image);

                    addEditListeners();

                });

            });

            frame.open();

        });

    }

    function addEditListeners() {

        focusInputX = $('input[id="pbfi-focus-image-value-x"]');
        focusInputY = $('input[id="pbfi-focus-image-value-y"]');

        $('.pbfi--focus-image--icon-remove').click(function(e) {

            e.preventDefault();
            hidden.val('');

            let addButton = getNewImageButton();

            list.html('');
            list.append(addButton);

            addAddListeners();

        });

        focusInputX.on('blur', save);
        focusInputY.on('blur', save);

        updateVisualizer();

    }

    function updateVisualizer() {

        if (list.find('.added').length > 0 && focusVisualizer.parent()) {
            list.find('.added').append(focusVisualizer);
        }

        if (focusVisualizer && focusInputX && focusInputY) {
            let focusXValue = focusInputX.val();
            let focusYValue = focusInputY.val();
            if (focusXValue && focusYValue) {
                focusVisualizer.css('left', parseInt(focusXValue.replace('%', '')) + '%');
                focusVisualizer.css('top', parseInt(focusYValue.replace('%', '')) + '%');
            }
        }

    }

    function save() {
        let focusX = parseInt(focusInputX.val().replace('%', '')) / 100;
        let focusY = parseInt(focusInputY.val().replace('%', '')) / 100;
        let focus = [focusX, focusY].join(',');
        let currentValue = hidden.val();
        let valuePairs = currentValue.split('|');
        let newValue = [valuePairs[0], focus].join('|');
        updateVisualizer();

        hidden.val(newValue);
    };


    function getNewImage(image) {
      
        let thumbnail = image.attributes.sizes.full.url;
        let focusX = '50%';
        let focusY = '50%';

        let imageElement = '';

        imageElement += '<li class="added">';
        imageElement += '<img src="' + thumbnail + '" />';
        imageElement += '<a href="#" class="pbfi--focus-image--icon pbfi--focus-image--icon-remove vc_icon-remove">';
        imageElement += '<i class="pbfi--focus-image--icon-close"></i>';
        imageElement += '</a>';

        imageElement += '<div class="pbfi-focus-box">';
        imageElement += '<div style="margin-bottom: 5px;"><label>focus point (in %)</label></div>';
        imageElement += '<div style="padding-bottom: 5px;">x: <input style="width: 50px; height: 20px;" id="pbfi-focus-image-value-x" type="text" value="' + focusX + '"/></div>';
        imageElement += '<div>y: <input style="width: 50px; height: 20px;" id="pbfi-focus-image-value-y" type="text" value="' + focusY + '"/></div>';

        imageElement += '</div>';

        imageElement += '</li>';

        let element = $(imageElement);

        return element;

    }

    function getNewImageButton() {

        let html = '';
      
        html += '<a class="pbfi--gallery-widget--add-images" href="#" use-single="true" title="Add image">';
        html += '<i class="pbfi--focus-image--icon pbfi--focus-image--icon-add"></i>Add image</a>';

        let element = $(html);

        return element;

    }

    addAddListeners();
    addEditListeners();

});

