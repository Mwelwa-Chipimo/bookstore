/********************************

Copyright (c) 2020 Lyndon Daniels

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*********************************/

let shoppingCart = new Vue({
                el : '#shoppingApp',
                data : {
                    totalItems : 0,
                    itemsObj : {},
                    quantities : [],
                    totalPrice : 0,
                    priceArr : []
                }, 
                methods : {
                    addToCart(){
                        let prodName = event.target.id;
                        //console.log(prodName);
                        let numberOfItems = parseInt(document.getElementById(prodName+"quantity").value);
                        //console.log(numberOfItems);
                        this.updateItemsObj(this.itemsObj, prodName, numberOfItems);
                        //forceUpdate not needed because Vue.set is used to add a reactive property
                        //this.$forceUpdate();
                        this.calcTotalPrice(this.itemsObj);
                        this.priceArr = [];
                    },
                    updateItemsObj(obj, elem, item){
                        //obj[elem] = item;
                        //Must use Vue.set when adding a reactive property to an object
                        let itemObj = {};
                        Vue.set( itemObj, 'quantity', item);
                        Vue.set( obj, elem, itemObj );
                        this.totalItems = this.addAllCartItems(obj)
                    },
                    addAllCartItems(cartItems){
                        var x = 0;
                        var total = 0;
                        for (x in cartItems) {
                           //total += cartItems[x];
                           //console.log(cartItems[x].quantity);
                             total += Number(cartItems[x].quantity);
                        }
                        return total;
                    },
                    deleteItem(){
                        //returns property Boxers, Dress etc
                        let x = event.target.value;
                        //console.log(x);
                        this.updateItemsObj(this.itemsObj, x, 0);
                        this.calcTotalPrice(this.itemsObj);
                        this.priceArr = [];
                    },
                    duplicateObj(obj){
                        for (x in obj){
                            //console.log(obj[x].quantity);
                            this.quantities.push(obj[x].quantity);
                            this.$forceUpdate();
                        }
                    },
                    resetChanges(arr, obj){
                            //console.log("something special" + arr[x]);
                            let x = 0;
                            for(y in obj){
                                    Vue.set( obj[y], 'quantity', arr[x]);
                                    x++;
                                }
                            //Vue.set( obj[x], 'quantity', arr[x]);
                        this.totalItems = this.addAllCartItems(obj);
                        this.calcTotalPrice(obj);
                        this.priceArr = [];
                        return this.quantities = [];
                    },
                    calcTotalPrice(obj){
                        for (x in obj){
                            let y = x + "price";
                            let price = document.getElementById(y).value;
                            let itemTotal = Number(price) * Number(obj[x].quantity);
                            this.priceArr.push(itemTotal);
                            //console.log(this.priceArr);
                            this.totalPrice = this.priceArr.reduce((a, b) => a + b);
                            //console.log(itemTotal);
                        }
                    },
                    calcTotalPriceCleanUp(obj,paramArr){
                        for (x in obj){
                            let y = x + "price";
                            let price = document.getElementById(y).value;
                            let itemTotal = Number(price) * Number(obj[x].quantity);
                            this.priceArr.push(itemTotal);
                            //console.log(this.priceArr);
                            this.totalPrice = this.priceArr.reduce((a, b) => a + b);
                            //console.log(itemTotal);
                        }
                    },
                    upDateEditCart(obj){
                        this.calcTotalPrice(obj);
                        this.priceArr = [];
                        this.totalItems = this.addAllCartItems(obj);
                    },
                    continueShopping(){
                        this.quantities = [];
                        this.priceArr = [];
                        this.$forceUpdate();
                    },
                    cleanup(paramArr){
                        console.log("we are cleaning up");
                        //incomplete
                        for(i = 0; i < paramArr.length; i+=2){
                            this.updateItemsObj(this.itemsObj, paramArr[i], paramArr[i+1]);
                            //console.log(paramArr[0]);
                        }    
                        this.calcTotalPriceCleanUp(this.itemsObj, paramArr);
                        this.priceArr = [];
                    }
                },//end methods
                computed : {
                    calcCartItems(){
                        return 0;
                    },
                    ZARtoUSD(){
                        return this.totalPrice/18.43;
                    }
                }
            });