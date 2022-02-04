/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import 'bootstrap/dist/css/bootstrap.min.css';

const $ = require('jquery');
require('bootstrap');

$(document).ready(function() {
    
    // pop demo account values and submit
    $('.demo').click(function() {
        $('#username').val('test@testmail.com');
        $('#password').val('Password!');
        $('#mrm-login').submit();
    });

    // ajax search functions
    $(".movie-search").focus(function() {
        $('.movie-search').val('');
        $("#movie-search-results").html("").hide();
    });

    $(".movie-search").keyup(function() {
        var term = $('.movie-search').val();
        
        if (term.length > 3) {
            $.ajax({
                type: "GET",
                url: "/api/movie/search",
                data: {
                    searchTerm: term
                },
                success: function(data) {
                    var html = '';

                    if (data.length == 0) {
                       var html = "No results found, try changing your search!";
                    }
                    
                    $.each(data, function(arrKey, dataObj) {
                        if (dataObj.title) {
                            html += "<p>" + dataObj.title + "</p>";
                        }
                        
                    }); 

                    $("#movie-search-results").html(html).show();
                }
            });
        }
    });

});
