/*
 *  @category   Hatimeria
 *  @author      (office@hatimeria.com)
 *  @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 *  @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

define([
    'jquery'
], function($) {
    "use strict";
    var formData = [];
    function pushStep() {
        window.dataLayer = window.dataLayer || [];
        $.each(formData, function( index, data ) {
            if ($('#' + data.form_id).length) {
                $('#' + data.form_id).data('eventname', data.event_name);
                $.each(data.form_field_ids,  function( i, fieldId ) {
                    if ($('#' + data.form_id).find('#' + fieldId).length) {
                        $('#' + data.form_id).find('#' + fieldId).addClass('trackfield');
                    }
                });
                $('#' + data.form_id).on("submit", function(event) {
                    trackFormSubmit(event);
                });
            }
        });
    }

    function trackFormSubmit(event) {
        event.preventDefault();
        var data = {};
        data.event = $(event.target).data('eventname');
        $(event.target).find('.trackfield').each(function(i, obj) {
            data[$(obj).attr('id')] = $(obj).val();
        });
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push(data);
        $(event.target).unbind('submit');
        $(event.target).submit();
    }

    return function (config) {
        formData = config.formData;
        pushStep();
    };
});
