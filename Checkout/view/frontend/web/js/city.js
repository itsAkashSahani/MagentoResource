/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Checkout/js/model/default-post-code-resolver',
    'jquery',
    'mage/utils/wrapper',
    'mage/template',
    'mage/validation',
    'underscore',
    'Magento_Ui/js/form/element/abstract',
    'jquery/ui',
], function (_, registry, Select, defaultPostCodeResolver, $) {
    'use strict';

    return Select.extend({
        defaults: {
            skipValidation: false,
            imports: {
                update: '${ $.parentName }.country_id:value'
            },
            options: [],
            city:[]
        },

        /**
         * @param {String} value
         */
        update: function (value) {
            var city = registry.get(this.parentName + '.' + 'city');
            var options;
            if(city === undefined){
                options = [{"title":"","value":"","label":"Select city"},{"value":"Jeddah","name":"Jeddah","region_id":"1101"},{"value":"Mecca","name":"Mecca","region_id":"1101"},{"value":"Ta'if","name":"Ta'if","region_id":"1101"},{"value":"Al Batha","name":"Al Batha","region_id":"1102"},{"value":"Al-'Olayya & Sulaymaniyyah","name":"Al-'Olayya & Sulaymaniyyah","region_id":"1102"},{"value":"Nemar","name":"Nemar","region_id":"1102"},{"value":"Irqah","name":"Irqah","region_id":"1102"},{"value":"Diplomatic Quarter","name":"Diplomatic Quarter","region_id":"1102"},{"value":"Al-Ma'athar","name":"Al-Ma'athar","region_id":"1102"},{"value":"Al-Ha'ir","name":"Al-Ha'ir","region_id":"1102"},{"value":"Al-'Aziziyyah","name":"Al-'Aziziyyah","region_id":"1102"},{"value":"Al-Malaz","name":"Al-Malaz","region_id":"1102"},{"value":"Al-Shifa","name":"Al-Shifa","region_id":"1102"},{"value":"Al-Urayja","name":"Al-Urayja","region_id":"1102"},{"value":"Al-Shemal","name":"Al-Shemal","region_id":"1102"},{"value":"Al-Naseem","name":"Al-Naseem","region_id":"1102"},{"value":"Al-Rawdhah","name":"Al-Rawdhah","region_id":"1102"},{"value":"Al-Selayy","name":"Al-Selayy","region_id":"1102"},{"value":"King Abdullah Financial District","name":"King Abdullah Financial District","region_id":"1102"},{"value":"Dammam","name":"Dammam","region_id":"1103"},{"value":"Al Khobar","name":"Al Khobar","region_id":"1103"},{"value":"Dhahran","name":"Dhahran","region_id":"1103"},{"value":"Jubail","name":"Jubail","region_id":"1103"},{"value":"Qatif","name":"Qatif","region_id":"1103"},{"value":"Hofuf","name":"Hofuf","region_id":"1103"},{"value":"Ras Tanura","name":"Ras Tanura","region_id":"1103"},{"value":"Khafji","name":"Khafji","region_id":"1103"},{"value":"Hafar Al Batin","name":"Hafar Al Batin","region_id":"1103"},{"value":"Buqayq","name":"Buqayq","region_id":"1103"},{"value":"Abha","name":"Abha","region_id":"1104"},{"value":"Khamis Mushait","name":"Khamis Mushait","region_id":"1104"},{"value":"Bisha","name":"Bisha","region_id":"1104"},{"value":"Al Namas","name":"Al Namas","region_id":"1104"},{"value":"Habala","name":"Habala","region_id":"1104"},{"value":"Bariq","name":"Bariq","region_id":"1104"},{"value":"Al Bahah","name":"Al Bahah","region_id":"1104"},{"value":"Al Majaridah","name":"Al Majaridah","region_id":"1104"},{"value":"Balqarn","name":"Balqarn","region_id":"1104"},{"value":"Dhahran Al Janub","name":"Dhahran Al Janub","region_id":"1104"},{"value":"Jazan","name":"Jazan","region_id":"1105"},{"value":"Ghawiyah","name":"Ghawiyah","region_id":"1105"},{"value":"Alwasly","name":"Alwasly","region_id":"1105"},{"value":"Al Rokobah","name":"Al Rokobah","region_id":"1105"},{"value":"Al Marabi","name":"Al Marabi","region_id":"1105"},{"value":"Al Edabi","name":"Al Edabi","region_id":"1105"},{"value":"Khumsiyah","name":"Khumsiyah","region_id":"1105"},{"value":"Mijannah","name":"Mijannah","region_id":"1105"},{"value":"Albadawi","name":"Albadawi","region_id":"1105"},{"value":"Alduraeah","name":"Alduraeah","region_id":"1105"},{"value":"Medina","name":"Medina","region_id":"1106"},{"value":"Khaybar","name":"Khaybar","region_id":"1106"},{"value":"Al Biq?�","name":"Al Biq?�","region_id":"1106"},{"value":"`Ushash","name":"`Ushash","region_id":"1106"},{"value":"Alhafah","name":"Alhafah","region_id":"1106"},{"value":"Al Mufrihat","name":"Al Mufrihat","region_id":"1106"},{"value":"Sultanah","name":"Sultanah","region_id":"1106"},{"value":"Husayniyah","name":"Husayniyah","region_id":"1106"},{"value":"Bartiyah","name":"Bartiyah","region_id":"1106"},{"value":"Al Wuday","name":"Al Wuday","region_id":"1106"},{"value":"Ad-Dulaymiyah","name":"Ad-Dulaymiyah","region_id":"1107"},{"value":"'A?n Bin Fuha?d","name":"'A?n Bin Fuha?d","region_id":"1107"},{"value":"Al-Fuwayliq","name":"Al-Fuwayliq","region_id":"1107"},{"value":"Al-Khubar?'","name":"Al-Khubar?'","region_id":"1107"},{"value":"Al-Mal?d?'","name":"Al-Mal?d?'","region_id":"1107"},{"value":"Al-Midhnab","name":"Al-Midhnab","region_id":"1107"},{"value":"Al-Quw?rah","name":"Al-Quw?rah","region_id":"1107"},{"value":"Ar-Rass","name":"Ar-Rass","region_id":"1107"},{"value":"Ash-Shim?iyah","name":"Ash-Shim?iyah","region_id":"1107"},{"value":"Buraydah","name":"Buraydah","region_id":"1107"},{"value":"Tabuk","name":"Tabuk","region_id":"1108"},{"value":"Umluj","name":"Umluj","region_id":"1108"},{"value":"Halat Ammar","name":"Halat Ammar","region_id":"1108"},{"value":"Duba","name":"Duba","region_id":"1108"},{"value":"Haql","name":"Haql","region_id":"1108"},{"value":"Magna","name":"Magna","region_id":"1108"},{"value":"Al Wajh","name":"Al Wajh","region_id":"1108"},{"value":"Al Bad'","name":"Al Bad'","region_id":"1108"},{"value":"Tayyib al Ism","name":"Tayyib al Ism","region_id":"1108"},{"value":"Tayma","name":"Tayma","region_id":"1108"},{"value":"Ha?il","name":"Ha?il","region_id":"1109"},{"value":"Najran","name":"Najran","region_id":"1110"},{"value":"Hubuna","name":"Hubuna","region_id":"1110"},{"value":"Khbash","name":"Khbash","region_id":"1110"},{"value":"Sharorah","name":"Sharorah","region_id":"1110"},{"value":"Badr Al Janoub","name":"Badr Al Janoub","region_id":"1110"},{"value":"Al Kharkhir","name":"Al Kharkhir","region_id":"1110"},{"value":"Thar","name":"Thar","region_id":"1110"},{"value":"Yadamah","name":"Yadamah","region_id":"1110"},{"value":"Sakakah","name":"Sakakah","region_id":"1111"},{"value":"Tubarjal","name":"Tubarjal","region_id":"1111"},{"value":"Jamajm","name":"Jamajm","region_id":"1111"},{"value":"Al Qurayyat","name":"Al Qurayyat","region_id":"1111"},{"value":"Al Hadithah","name":"Al Hadithah","region_id":"1111"},{"value":"Suwayr","name":"Suwayr","region_id":"1111"},{"value":"Dumah","name":"Dumah","region_id":"1111"},{"value":"An Nabk","name":"An Nabk","region_id":"1111"}];
            }
            else{
                options = city.initialOptions;
            }
            
            var cityOptions = [];

            //console.log(options);

            $.each(options, function (index, cityOptionValue) {
                //if(value == cityOptionValue.region_id){
                    var name = cityOptionValue.name;
                    var jsonObject = {
                        value: name,
                        title: name,
                        country_id: "",
                        label: name
                    };
                    cityOptions.push(jsonObject);
                //}
            });
            this.setOptions(cityOptions);
        }
    });
});