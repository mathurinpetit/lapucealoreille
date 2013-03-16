/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var Music = function (lapalo) {


	var container = document.createElement( 'img' );
	container.id = 'music';
	container.addEventListener( 'mousedown', function ( event ) {
            lapalo.musicSwitch();
            lapalo.panelNoRemove = true;
        }, false );
	container.style.cssText = 'width:80px; opacity:0.9; cursor:pointer; border:1px;';
        container.setAttribute('src', './assets/music.png');
	return {

		REVISION: 01,

		domElement: container

	}

};

