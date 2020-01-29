/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)

import '@fortawesome/fontawesome-free/css/all.min.css';


import '../css/app.scss';
import 'bootstrap';
import 'jquery';




const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);




$(function(){
    console.log('ready');
    $('.movie-fav').click(function(){

       let $that = $(this);
       let movieId = $that.data('id');

        $.ajax({
            method: "GET",
            url: Routing.generate('add_favourite_movie', { 'id' : movieId}),
        }).done(function( jsonResponse ) {
            // Retour du controller : [ 'data' => $message ]
            console.log(jsonResponse);
            if (jsonResponse == 'added'){
                $that.removeClass('far');
                $that.addClass('fas')
            }else{
                $that.removeClass('fas');
                $that.addClass('far');
            }
        });
    });
});