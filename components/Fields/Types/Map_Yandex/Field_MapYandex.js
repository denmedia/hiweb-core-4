let hiweb_field_map_yandex = {

    default_lat_long: [55.76, 37.64],
    maps: {},
    points: {},

    init: function () {
        jQuery('.hiweb-field-map-yandex').each(function () {
            let id = jQuery(this).attr('id');
            hiweb_field_map_yandex.make(id);
        });
        jQuery('body').on('hiweb-field-repeat-added-new-row', '[data-col]', function (e, col, row, root) {
            col.find('.hiweb-field-map-yandex').each(function () {
                let id = jQuery(this).attr('id');
                hiweb_field_map_yandex.make(id);
            });
        });
    },

    make: function (id) {
        hiweb_field_map_yandex.maps[id] = new ymaps.Map(id + '-map', {
            center: hiweb_field_map_yandex.default_lat_long,
            zoom: 7
        });
        ///Map Click Event
        hiweb_field_map_yandex.maps[id].events.add('click', function (e) {
            hiweb_field_map_yandex.set_value_make_point(id, e.get('coords'), hiweb_field_map_yandex.maps[id].getZoom());
        });
        hiweb_field_map_yandex.maps[id].events.add('boundschange', function (e) {
            let $inputs = jQuery('.hiweb-field-map-yandex[id="' + id + '"] input[name]').eq(2).val(e.get('newZoom'));
        });
        // ///read values
        let $inputs = jQuery('.hiweb-field-map-yandex[id="' + id + '"] input[name]');
        let lat = parseFloat($inputs.eq(0).val());
        let long = parseFloat($inputs.eq(1).val());
        let zoom = parseFloat($inputs.eq(2).val());
        if (!isNaN(lat) && !isNaN(long)) {
            //set value
            hiweb_field_map_yandex._set_point_coords(id, [lat, long]);
            hiweb_field_map_yandex.maps[id].setCenter([lat, long]);
            if (!isNaN(zoom)) {
                hiweb_field_map_yandex.maps[id].setZoom(zoom, {duration: 1000});
            }
        }
    },

    set_value_make_point: function (id, coords, zoom) {
        if (hiweb_field_map_yandex.maps.hasOwnProperty(id) < 0) {
            console.warn('Ошибка установки данных в форме карты');
        } else if (coords.hasOwnProperty(0) < 0 || coords.hasOwnProperty(1) < 0) {
            console.warn(['Не верно переданы коодинаты для установки значений', coords]);
        } else {
            hiweb_field_map_yandex._set_value(id, coords, zoom);
            hiweb_field_map_yandex._set_point_coords(id, coords);
        }
    },

    _set_value: function (id, coords, zoom) {
        let $inputs = jQuery('.hiweb-field-map-yandex[id="' + id + '"] input[name]');
        $inputs.eq(0).val(coords[0]);
        $inputs.eq(1).val(coords[1]);
        $inputs.eq(2).val(zoom);
    },

    _set_point_coords: function (id, coords) {
        if (hiweb_field_map_yandex.points.hasOwnProperty(id)) {
            //Move exists point
            hiweb_field_map_yandex.points[id].geometry.setCoordinates(coords);
        } else {
            //Make new point
            hiweb_field_map_yandex.points[id] = new ymaps.Placemark(coords, {
                balloonContent: 'Установленная точка'
            }, {
                preset: 'islands#blueDeliveryIcon',
                draggable: true
            });
            hiweb_field_map_yandex.points[id].events.add('dragend', function (event) {
                hiweb_field_map_yandex._set_value(id, event.get('target').geometry.getCoordinates());
            });
            hiweb_field_map_yandex.maps[id].geoObjects.add(hiweb_field_map_yandex.points[id]);
        }
    }

};

ymaps.ready(hiweb_field_map_yandex.init);
//jQuery(document).ready();