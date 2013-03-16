/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var Panier = function (lapalo) {


	var container = document.createElement( 'img' );
	container.id = 'panier';
	container.addEventListener( 'mousedown', function ( event ) { 
            lapalo.clickPanier();
            lapalo.panelNoRemove = true;
        }, false );
	container.style.cssText = 'width:80px; opacity:0.9; cursor:pointer; border:1px;';
        container.setAttribute('src', './assets/panier.png');
      
	return {
		REVISION: 01,
		domElement: container,
                addProduct: function(model){
                    
                    if($('div#qte').length > 0){
                        var newQte = 1 + parseInt($('div#qte').text());
                       $('div#qte').text(newQte.toString());
                    } else {
                     var qte = document.createElement( 'div' );
                        qte.id = 'qte';
                        $(qte).attr('style','position:absolute; top:100px; right:100px');
                        $(qte).text('1');
                        lapalo.container.appendChild( qte );
                    }
                }
	}
        

};

