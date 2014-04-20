/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var About = function (lapalo) {


	var container = document.createElement( 'img' );
	container.id = 'about';
	container.addEventListener( 'mousedown', function ( event ) { 
            lapalo.clickAbout();
            lapalo.panelNoRemove = true;
        }, false );
	container.style.cssText = 'width:80px; opacity:0.9; cursor:pointer; border:1px;';
        container.setAttribute('src', './assets/about.png');
      
	return {
		REVISION: 02,
		domElement: container
	}
        

};

