function anbFunction1() {
	var x = document.getElementById('anb-id-1').className += ' anb-fade-out';
	setTimeout(removeAnb, 500);
	function removeAnb() {
		var x = document.getElementById('anb-id-1');
		x.parentNode.removeChild(x);
	}

	var d = new Date();
	d.setTime(d.getTime() + (1*24*60*60*1000));
	var expires = "expires="+ d.toUTCString();
	document.cookie = "close_anb_1=cancel;" + expires;
}
var anbSetTimeoutVar1;
anbSetTimeoutVar1 = setTimeout(function(){ anbTimeoutFunction1(); }, 10000);
function anbTimeoutFunction1() {
	var x = document.getElementById('anb-id-1').className += ' anb-fade-out';
	setTimeout(removeAnb, 500);
	function removeAnb() {
		var x = document.getElementById('anb-id-1');
		x.parentNode.removeChild(x);
	}
}
function anbOnMouseOverFunction1() {
	clearTimeout(anbSetTimeoutVar1);
}
function anbOnMouseOutFunction1() {
anbSetTimeoutVar1 = setTimeout(function(){ anbTimeoutFunction1(); }, 8000);
function anbTimeoutFunction1() {
	var x = document.getElementById('anb-id-1').className += ' anb-fade-out';
	setTimeout(removeAnb, 500);
	function removeAnb() {
		var x = document.getElementById('anb-id-1');
		x.parentNode.removeChild(x);
	}
}
}

function anbFunction2() {
	var x = document.getElementById('anb-id-2').className += ' anb-fade-out';
	setTimeout(removeAnb, 500);
	function removeAnb() {
		var x = document.getElementById('anb-id-2');
		x.parentNode.removeChild(x);
	}

	var d = new Date();
	d.setTime(d.getTime() + (14*24*60*60*1000));
	var expires = "expires="+ d.toUTCString();
	document.cookie = "close_anb_2=cancel;" + expires;
}
var anbSetTimeoutVar2;
anbSetTimeoutVar2 = setTimeout(function(){ anbTimeoutFunction2(); }, 10000005000);
function anbTimeoutFunction2() {
	var x = document.getElementById('anb-id-2').className += ' anb-fade-out';
	setTimeout(removeAnb, 500);
	function removeAnb() {
		var x = document.getElementById('anb-id-2');
		x.parentNode.removeChild(x);
	}
}
function anbOnMouseOverFunction2() {
	clearTimeout(anbSetTimeoutVar2);
}
function anbOnMouseOutFunction2() {
anbSetTimeoutVar2 = setTimeout(function(){ anbTimeoutFunction2(); }, 10000000000);
function anbTimeoutFunction2() {
	var x = document.getElementById('anb-id-2').className += ' anb-fade-out';
	setTimeout(removeAnb, 500);
	function removeAnb() {
		var x = document.getElementById('anb-id-2');
		x.parentNode.removeChild(x);
	}
}
}

