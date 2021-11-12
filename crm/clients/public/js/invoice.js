

function fourdigits(number) {
    return (number < 1000) ? number + 1900 : number;
}

function roundNumber(number, decimals) {
    var newString;
    decimals = Number(decimals);
    if (decimals < 1) {
        newString = (Math.round(number)).toString();
    } else {
        var numString = number.toString();
        if (numString.lastIndexOf(".") == -1) {
            numString += ".";
        }
        var cutoff = numString.lastIndexOf(".") + decimals;
        var d1 = Number(numString.substring(cutoff, cutoff + 1)); 
        var d2 = Number(numString.substring(cutoff + 1, cutoff + 2)); 
        if (d2 >= 5) {
            if (d1 == 9 && cutoff > 0) {
                while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
                    if (d1 != ".") {
                        cutoff -= 1;
                        d1 = Number(numString.substring(cutoff, cutoff + 1));
                    } else {
                        cutoff -= 1;
                    }
                }
            }
            d1 += 1;
        }
        if (d1 == 10) {
            numString = numString.substring(0, numString.lastIndexOf("."));
            var roundedNum = Number(numString) + 1;
            newString = roundedNum.toString() + '.';
        } else {
            newString = numString.substring(0, cutoff) + d1.toString();
        }
    }
    if (newString.lastIndexOf(".") == -1) {
        newString += ".";
    }
    var decs = (newString.substring(newString.lastIndexOf(".") + 1)).length;
    for (var i = 0; i < decimals - decs; i++) newString += "0";
        return newString;
}

function update_total() {
    var total = 0;
    $('.item-price').each(function(i) {
        price = $(this).val();
        if (!isNaN(price)) total += Number(price);
    });

    var taxtotal = 0;
    $('.item-tax-price').each(function(i) {
        taxprice = $(this).val();
        if (!isNaN(taxprice)) taxtotal += Number(taxprice);
    });

    total = roundNumber(total, 2);
    taxprice = roundNumber(taxtotal, 2);
    var amount = roundNumber(+total + +taxprice , 2);

    $('.sub-total').val(total);
    $('.tax-total').val(taxprice);
    $('.total-amount').val(amount);
    $('.due-amount').val(amount);

    update_balance();
}

function update_balance() {
    var subtotal = Number($(".sub-total").val()),
    tax = Number($(".tax-total").val()),
    discount = Number($(".discount-total").val());
    var after_discount = (+subtotal) + (+tax);

    if ($('.discount-type').val() === "2") {
        var after_discount = subtotal - discount;
        after_discount = after_discount;
    } else {
        discount = discount * subtotal * 0.01;
        var after_discount = subtotal - discount;
        after_discount = roundNumber(after_discount, 2);
    }

    var due = (+after_discount + +tax) - $(".paid-amount").val();
    due = roundNumber(due, 2);
    var total = +after_discount + +tax;
    $('.discount_amount').val(discount);
    $('.total-amount').val(roundNumber(total, 2));
    $('.due-amount').val(due);
}

function update_price() {
    var row = $(this).parents('.item-row'), 
    price = row.find('.item-cost').val() * row.find('.item-quantity').val(),
    tax_price = roundNumber(row.find('.item-tax').find(':selected').data( "rate" ) * price * 0.01, 2);

    price = roundNumber(price, 2);
    tax_price = roundNumber(tax_price, 2);
    
    var unit_price = (+price) + (+tax_price);

    isNaN(price) ? row.find('.item-price').val("N/A") : row.find('.item-price').val(price);
    isNaN(unit_price) ? row.find('.item-total-price').val("N/A") : row.find('.item-total-price').val(price);
    isNaN(tax_price) ? row.find('.item-tax-price').html("N/A") : row.find('.item-tax-price').html(tax_price);
    update_total();
}

function bind() {
    $(".item-cost").on('blur', update_price);
    $(".item-quantity").on('blur', update_price);
    $("body").on('change', '.item-tax', update_price);
}

$(document).ready(function () {
    "use strict";
    var tax_html = '', items = [];

    $(".discount-total").on('blur', update_balance);
    $(".paid-amount").on('blur', update_balance);

    $.each( JSON.parse($('.taxes').val()) , function( key, value ) {
        tax_html += '<option value="'+value['id']+'" data-rate="'+value['rate']+'">'+value['name']+' ('+value['rate']+'%)</option>';
    });

    $.each( JSON.parse($('.items').val()) , function( key, value ) {
        items.push(value);
    });
    
    
    function item_html(count) {
        var item_html = '<tr class="item-row">'+
        '<td class="">'+
        '<textarea name="invoice[item]['+count+'][name]" class="item-name"></textarea>'+
        '<a href="#" class="delete"><i class="icon-close"></i></a>'+
        '</td>'+
        '<td class="invoice-item">'+
        '<textarea name="invoice[item]['+count+'][descr]" class="item-descr"></textarea>'+
        '</td>'+
        '<td class="">'+
        '<textarea type="text" name="invoice[item]['+count+'][quantity]" class="item-quantity">1</textarea>'+
        '</td>'+
        '<td class="">'+
        '<textarea type="text" name="invoice[item]['+count+'][cost]" class="item-cost"></textarea>'+
        '</td>'+
        '<td class="invoice-tax">'+
        '<select name="invoice[item]['+count+'][tax]" class="custom-select item-tax">'+
        '<option value="0" data-rate="0">None</option>'
        +tax_html+
        '</select>'+
        '</td>'+
        '<td class="">'+
        '<textarea type="text" name="invoice[item]['+count+'][taxprice]" class="item-tax-price" readonly>0</textarea>'+
        '</td>'+
        '<td class="">'+
        '<textarea type="text" name="invoice[item]['+count+'][price]" class="item-total-price" readonly></textarea>'+
        '<input type="hidden" class="item-price">'+
        '</td></tr>';

        if (count === 0) {
            $(".invoice-items tbody").prepend(item_html);
        } else {
            $(".item-row:last").after(item_html);
        }
    }

    function initAutocomplete() {
        $( ".item-name" ).autocomplete({
            source: items,
            minLength: 0,
            focus : function(event, ui){
                $(".item").val( ui.item.label );
                return false;
            },
            select: function( event, ui ) {
                var ele = $(this).parents('tr');
                ele.find(".item-name").val(ui.item.label);
                ele.find(".item-descr").val(ui.item.desc);
                ele.find(".item-cost" ).val(roundNumber(ui.item.cost, 2));
                ele.find(".item-total-price" ).val(roundNumber(ui.item.cost,2));
                ele.find(".item-price" ).val(roundNumber(ui.item.cost,2));
                update_price();
                return false;
            }
        }).focus(function(){
            if (this.value == ""){
                $(this).autocomplete("search");
            }
        });
    }

    $('.invoice-items').on('click', '.add-items', function () {

        if($(".item-row").length === 0) {
            item_html(0);
        } else {
            var count = $('.invoice-items table tr.item-row:last .item-name').attr('name').split('[')[2];
            count = parseInt(count.split(']')[0]) + 1;
            item_html(count);
        }
        initAutocomplete();
        bind();
    });

    $('.invoice-items').on('click', '.delete', function () {
        var ele = $(this);
        ele.parents('.item-row').remove();
        bind();
        return false;
    });

    bind();

    initAutocomplete();
});