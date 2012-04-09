function createXMLHTTPRequest(){
	// Gecko / IE7
	if ( typeof(XMLHttpRequest) != 'undefined' )
		return new XMLHttpRequest() ;

	// IE6
	try { return new ActiveXObject( 'Msxml2.XMLHTTP' ) ; }
	catch(e) {}

	// IE5
	try { return new ActiveXObject( 'Microsoft.XMLHTTP' ) ; }
	catch(e) {}

	return null;
}

function Ajax(container,asyncFunctionHandle){

	//均使用临时局部变量.
	var xmlHttp = createXMLHTTPRequest();
	var container = container ? container : null;
	var returnText = '';
	var returnXML = null;
	
	//视乎得改局部变量
	this.pKeys = Array();
	this.pValues = Array();
	this.pCount = 0;
	
	this.hKeys = Array();
	this.hValues = Array();
	this.hCount = 0;
	this.hType = 'text';
	this.returnXML = null;
	
	this.isWork = function(){
		if(xmlHttp && xmlHttp != undefined) return true;
		else return false;
	}
	
	this.addHead = function(akey,avalue){
		this.hKeys[this.hCount] = akey;
		this.hValues[this.hCount] = avalue;
		this.hCount++;
	}
	
	this.sendHead = function() {
		if(this.hCount > 0) {
			var i = 0;
			for(i=0;i<this.hCount;i++) {
				xmlHttp.setRequestHeader(this.hKeys[i],this.hValues[i]);
			}
		}
		if(this.hType == 'binary') {
			xmlHttp.setRequestHeader('Content-Type','multipart/form-data');
		}else {
			xmlHttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		}
	}
	
	this.addPData = function(akey,avalue) {
		this.pKeys[this.pCount] = akey;
		this.pValues[this.pCount] = avalue;
		this.pCount++;
	}
	
	this.clearData = function() {
		this.pKeys = Array();
		this.pValues = Array();
		this.pCount = 0;
		this.hKeys = Array();
		this.hValues = Array();
		this.hCount = 0;
	}
	
	
	this.doOpen = function(doMethod,uri,async) {
		if(doMethod != 'POST' && doMethod != 'GET' && doMethod != 'PUT') doMethod = 'GET';
		if(async !== false) async = true;
		xmlHttp.open(doMethod,encodeURI(uri),async);
	}
	
	this.open = this.doOpen;
	
	this.doSend = function(dodata,domethod) {	
		if(xmlHttp.readyState == 1) {
			container.innerHTML = 'loading...';
			if(domethod == 'POST') {
				xmlHttp.send(dodata);
			}else if(domethod == 'GET') {
				xmlHttp.send(null);
			}else {
				xmlHttp.send(null);
			}
		}else {
			container.innerHTML = 'readyState error';
		}
	}
	
	this.send = this.doSend;
	
	xmlHttp.onreadystatechange = function() {
			var oXML = this;
			if(xmlHttp.readyState == 4){
				if(xmlHttp.status == 200 || xmlHttp.responseText) {
					if(typeof(asyncFunctionHandle) == 'function') {
						//can't do directly use xmlHttp to do whit responseXML.
						oXML.DOMDocument = xmlHttp.responseXML;
						asyncFunctionHandle ( oXML );
					}else {
						returnText = xmlHttp.responseText;
						if(container){
							container . innerHTML = returnText;
						}
					}
				}else {
					container. innerHTML = 'server error ' + xmlHttp.status;
				}
			}else if(xmlHttp.readyState == 3) {
				container.innerHTML = 'reciving...';
			}else if(xmlHttp.readyState == 2) {
				container.innerHTML = 'sending...';
			}else {
				
			}
	}
	
	//在非异步情况下可用,否则得不到.下同
	this.getReturnText = function() {
		if(xmlHttp.readyState == 4) return returnText;
		else return null;
	}
	
	this.getReturnXML = function(){
		if(xmlHttp.readyState == 4) return returnXML;
		else return null;
	}
	
	this.getReadyState = function() {
		return xmlHttp.readyState;
	}
}//end of class Ajax.

//Mom Functions
function $(id) {
	return document.getElementById(id);
}