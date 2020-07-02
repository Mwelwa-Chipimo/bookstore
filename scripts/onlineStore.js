/********************************

Copyright (c) 2020 Lyndon Daniels

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*********************************/

const isElemVisable = (elem, className)=> 
{
    $(elem).each(function(){
            //returns elements Y pos offset from document window
            let elementBottom = $(this).offset().top + $(this).outerHeight()/2;
            let windowBottom = $(window).scrollTop() + $(window).height();
            if( windowBottom > elementBottom ){
                $(this).addClass(className);
            }
        });    
}

$(document).ready(function() {
    isElemVisable('.fadeIn', 'isVisible');
    //will execute everytime user scrolls
    $(window).scroll( function(){
       isElemVisable('.fadeIn', 'isVisible'); 
    });//end scroll functionallity

    $(document).on('click', 'a.addAction', function() {
        const action = 'add';
        console.log(action); 
        const book_code = $(this).data('book_code');
        console.log(book_code);
        const book_title = $(this).data('book_title');
        console.log(book_title);
        const book_author = $(this).data('book_author');
        console.log(book_author);
        const book_price = $(this).data('book_price');
        console.log(book_price);

        $.ajax({
            type: 'POST',
            url: 'product/action.php',
            data: {
                action: action,
                code: book_code,
                title: book_title,
                author: book_author,
                price: book_price
            },
            dataType: 'html',
            success: function(data) {
                alert("Product has been added to your cart");
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        })
    });
    
});//end document.ready



const updateCartAjax = ()=> {
    //let itemsArr = [];
    //let itemsQuantity = [];
    let allItems = [];
    for(let items in shoppingCart.$data.itemsObj){
        //itemsArr.push(items);
        //itemsQuantity.push(shoppingCart.$data.itemsObj[items].quantity);
        allItems.push(items);
        allItems.push(shoppingCart.$data.itemsObj[items].quantity);

    }
    //allItems.push(itemsArr);
    //allItems.push(itemsQuantity);
    
    var xhr = new XMLHttpRequest();
        console.log(xhr.readyState);    

        // Track the state changes of the request.
        xhr.onreadystatechange = function () {
            const DONE = 4; // readyState 4 means the request is done.
            const OK = 200; // status 200 is a successful return.
            if (xhr.readyState === DONE) {
                if (xhr.status === OK) {
                    //console.log(xhr.responseText); // 'This is the output.'
                    console.log(xhr.readyState);
                } else {
                    console.log('Error: ' + xhr.status); // An error occurred during the request.
                }
            }
        };

        // Send the request to send-ajax-data.php
        xhr.open("GET", "../incl/cart.php?q="+allItems, true);
        xhr.send();
    console.log(allItems);
    };

const overlayOn = ()=>{
    document.getElementById("overlay").style.display = "block";
    document.getElementById("editCart").style.display = "block";
    };
        
const overlayOff = ()=> {
    document.getElementById("overlay").style.display = "none";
    };

const cleanUpVue = (paramArrClean)=> {
    shoppingCart.cleanup(paramArrClean);
};

