/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var Panier = function (lapalo) {


	var container = document.createElement( 'img' );
	container.id = 'panier';
	container.addEventListener( 'mousedown', function ( event ) { console.log('panier'); 
        lapalo.sphereDebug(40,3,40);
        }, false );
	container.style.cssText = 'width:80px; opacity:0.9; cursor:pointer; border:1px;';
        container.setAttribute('src', './assets/panier.png');
	return {

		REVISION: 01,

		domElement: container

	}

};

