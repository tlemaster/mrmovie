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
require('jquery-ui/ui/widgets/autocomplete');
require('bootstrap');

$(document).ready(function() {
    $('#movie-search').val('');
    $('#movie-id').val('');
    
    
    $('.demo').click(function() {
        $('#username').val('test@testmail.com');
        $('#password').val('Password!');
        $('#mrm-login').submit();
    });

    $(".movie-search").focus(function() {
        $('#movie-search').val('');
        $('#movie-id').val('');
    });

    $("#movie-id").on('input', function(){
        console.log('fired');
        var mdbId = $('#movie-id').val();

    });

    $("#movie-search" ).autocomplete({
        minLength: 3,
        source: function(request, response) {
            $.ajax({
                type: "GET",
                url: "/api/movie/search",
                data: {
                    searchTerm: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        focus: function(event, ui) {
            $("#movie-search").val(ui.item.title);
            
            return false;
        },
        select: function(event, ui) {
            $("#movie-search").val(ui.item.title);
            $("#movie-id").val(ui.item.id);
            
            return false;
        }
    })
    .autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
          .append( "<div>" + item.title + "</div>")
          .appendTo(ul);
    };


    $("#movie-list-add").submit(function(event) {
        var mdbId = $('#movie-id').val();
        var addUrl = $('#movie-add-submit').attr("data-addUrl");
        
        if (mdbId) {
            window.location = addUrl + '/movie/list/addMdb/' + mdbId; 
        }
        
        event.preventDefault();
    });

});
